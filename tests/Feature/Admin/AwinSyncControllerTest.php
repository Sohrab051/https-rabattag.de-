<?php

namespace Tests\Feature\Admin;

use App\Jobs\RunAwinSyncJob;
use App\Models\AwinSyncRun;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AwinSyncControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['super-admin', 'content-manager', 'finance-manager', 'support'] as $role) {
            Role::findOrCreate($role);
        }
    }

    private function userWithRole(string $role): User
    {
        $user = User::factory()->create();
        $user->assignRole($role);

        return $user;
    }

    public function test_index_renders_for_super_admin_and_shows_configured_status(): void
    {
        config([
            'awin.feed_enabled' => true,
            'awin.publisher_id' => '12345',
            'awin.api_token' => 'super-secret-test-token',
        ]);

        $user = $this->userWithRole('super-admin');

        $response = $this->actingAs($user)->get('/en/admin/awin');

        $response->assertOk();
        $response->assertSee('Configured');
    }

    public function test_index_shows_not_configured_when_missing_credentials(): void
    {
        config([
            'awin.feed_enabled' => false,
            'awin.publisher_id' => null,
            'awin.api_token' => null,
        ]);

        $user = $this->userWithRole('super-admin');

        $response = $this->actingAs($user)->get('/en/admin/awin');

        $response->assertOk();
        $response->assertSee('Not Configured');
    }

    public function test_api_token_never_appears_in_rendered_html(): void
    {
        config([
            'awin.feed_enabled' => true,
            'awin.publisher_id' => '12345',
            'awin.api_token' => 'super-secret-test-token',
        ]);

        $user = $this->userWithRole('super-admin');

        $response = $this->actingAs($user)->get('/en/admin/awin');

        $response->assertOk();
        $response->assertDontSee('super-secret-test-token');
    }

    public function test_finance_manager_and_support_can_view_but_not_see_sync_button(): void
    {
        foreach (['finance-manager', 'support'] as $role) {
            $user = $this->userWithRole($role);

            $response = $this->actingAs($user)->get('/en/admin/awin');

            $response->assertOk();
            $response->assertDontSee('Sync Now');
        }
    }

    public function test_super_admin_and_content_manager_see_sync_button(): void
    {
        foreach (['super-admin', 'content-manager'] as $role) {
            $user = $this->userWithRole($role);

            $response = $this->actingAs($user)->get('/en/admin/awin');

            $response->assertOk();
            $response->assertSee('Sync Now');
        }
    }

    public function test_finance_manager_and_support_get_403_posting_to_sync(): void
    {
        foreach (['finance-manager', 'support'] as $role) {
            $user = $this->userWithRole($role);

            $response = $this->actingAs($user)->post('/en/admin/awin/sync');

            $response->assertForbidden();
        }
    }

    public function test_sync_dispatches_job_and_creates_pending_run_when_none_active(): void
    {
        Queue::fake();

        $user = $this->userWithRole('super-admin');

        $response = $this->actingAs($user)->post('/en/admin/awin/sync');

        $response->assertRedirect();
        $this->assertDatabaseHas('awin_sync_runs', ['status' => 'pending']);
        Queue::assertPushed(RunAwinSyncJob::class);
    }

    public function test_sync_is_blocked_when_a_run_is_already_pending_or_running(): void
    {
        Queue::fake();

        AwinSyncRun::create(['status' => 'running']);

        $user = $this->userWithRole('content-manager');

        $response = $this->actingAs($user)->post('/en/admin/awin/sync');

        $response->assertRedirect();
        $this->assertSame(1, AwinSyncRun::count());
        Queue::assertNotPushed(RunAwinSyncJob::class);
    }

    /**
     * The test environment normally runs QUEUE_CONNECTION=sync (see phpunit.xml),
     * which executes jobs inline instead of persisting them. This test forces the
     * `database` queue driver for a single assertion to prove the queue wiring
     * itself (config/queue.php + the `jobs` table) works end-to-end at the DB
     * level — i.e. that dispatching RunAwinSyncJob actually inserts a row into
     * `jobs` rather than relying only on Queue::fake(), which never touches the
     * real queue connection or table.
     */
    public function test_dispatching_the_job_inserts_a_row_into_the_jobs_table(): void
    {
        config(['queue.default' => 'database']);

        $run = AwinSyncRun::create(['status' => 'pending']);

        $this->assertDatabaseCount('jobs', 0);

        RunAwinSyncJob::dispatch($run->id);

        $this->assertDatabaseCount('jobs', 1);
        $this->assertDatabaseHas('jobs', ['queue' => 'default']);
    }
}
