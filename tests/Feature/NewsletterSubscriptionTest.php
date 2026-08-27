<?php

namespace Tests\Feature;

use App\Models\NewsletterSubscriber;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsletterSubscriptionTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_visitor_can_subscribe_to_the_newsletter(): void
    {
        $response = $this->post('/de/newsletter/subscribe', [
            'email' => 'subscriber@example.com',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('newsletter_status');

        $this->assertDatabaseHas('newsletter_subscribers', [
            'email' => 'subscriber@example.com',
            'locale' => 'de',
        ]);
    }

    public function test_duplicate_email_subscription_is_rejected(): void
    {
        NewsletterSubscriber::create([
            'email' => 'existing@example.com',
            'locale' => 'de',
        ]);

        $response = $this->post('/de/newsletter/subscribe', [
            'email' => 'existing@example.com',
        ]);

        $response->assertSessionHasErrors('email');

        $this->assertSame(1, NewsletterSubscriber::where('email', 'existing@example.com')->count());
    }
}
