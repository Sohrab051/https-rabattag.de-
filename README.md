# Rabattag — Discount Marketplace

Bilingual (DE/EN, German-first) discount marketplace built with Laravel, Livewire, Alpine.js, and Tailwind CSS. No cashback, no coupon codes — direct daily discount deals only, focused on fashion, shoes, and beauty. Built to run on ordinary shared/cPanel hosting — no Node.js, Redis, or root access required in production.

## Stack

- **Backend:** Laravel 13, PHP 8.3+
- **Frontend:** Blade + Livewire 3 + Alpine.js, Tailwind CSS 3 (compiled ahead of time with Vite — no Node process needed on the server)
- **Database:** MySQL in production (SQLite for local dev)
- **Queue:** Laravel Queue, `database` driver (no Redis)
- **Auth:** Laravel Breeze (Livewire stack)
- **Roles/permissions:** spatie/laravel-permission

## Local development

```bash
composer install
npm install
cp .env.example .env   # already pre-filled with sqlite for local dev
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
npm run build           # or `npm run dev` while developing CSS/JS
php artisan serve
```

Seeded admin login: `admin@example.com` / `password`.

## Architecture notes

- **Bilingual routing:** every public/authenticated route is nested under a `{locale}` prefix (`en|de`), enforced by `App\Http\Middleware\SetLocale`. The root `/` redirects to the browser's preferred/previously-chosen locale. Because Laravel matches unnamed controller parameters *positionally*, any controller action that has an additional route parameter next to `{locale}` (e.g. `/store/{merchant}`) must declare a leading `string $locale` parameter even if unused — see `MerchantController::show()` for the pattern.
- **Translatable content:** merchant/offer/category text is stored in parallel `_en`/`_de` columns rather than a separate translations table, since there are only two locales. Static UI strings go through `__()` and `lang/de.json` (English strings act as their own keys).
- **Live publishing:** the admin "Add store & offer" wizard (`/admin/merchant-offer/create`) creates a `Merchant` (or reuses one) and an `Offer` in one submission. The homepage/offer queries always filter `status = published AND published_at <= now()` live against the database — there is no page cache to bust.
- **Click tracking:** `GET /go/{merchant:slug}` logs a `click_logs` row (with a UUID `click_id`) and 302-redirects to the merchant's affiliate link. `conversions` rows are linked back to `click_logs.id` and carry the pending/approved/rejected/paid lifecycle for commission + cashback.
- **GDPR:** cookie consent banner (`x-cookie-consent`, essential-only vs. accept-all, stored in `localStorage`), plus `/impressum`, `/datenschutz`, and `/terms` pages. These are drafted templates — have them reviewed by a lawyer before going live.

## Queue Worker (Background Jobs)

Some admin actions — notably the **"Sync Now"** button on `/admin/awin` (`RunAwinSyncJob`) — are dispatched to the queue instead of running inline. With `QUEUE_CONNECTION=database` (the default, see `.env.example`), a dispatched job is only *inserted* into the `jobs` table; nothing processes it until a queue worker is running. Without a worker, the job just sits in `jobs` forever and the sync never actually happens.

**In development**, run this in a separate terminal alongside `php artisan serve`:

```bash
php artisan queue:work --tries=3
```

Leave it running while you use the admin panel. It processes jobs as they're dispatched, retries up to 3 times on failure (per `RunAwinSyncJob::$tries`), and logs output to the terminal. Stop it with Ctrl+C when done; restart it if you change job/service code, since `queue:work` boots the app once and keeps workers warm (use `queue:listen` instead if you want it to pick up code changes automatically without restarting, at the cost of being slower).

Note: `.claude/launch.json` intentionally does **not** include a `queue:work` entry — that file's schema (consumed by the dev-preview tooling) requires a `port` for each configuration, since it's designed for HTTP dev servers. `queue:work` is a long-running CLI process with no HTTP port to bind, so it doesn't fit that format; run the command above directly in a terminal instead.

## Deploying to a shared cPanel host

### Deploy SSH key

A public SSH key has been generated for automated/Git-based deployment access (e.g. cPanel Git Version Control, or a CI/deploy user), labeled `rabattag-deploy`. Add it to the host's authorized keys (cPanel → Security → SSH Access → Manage SSH Keys → Import Key, or append to `~/.ssh/authorized_keys` on the server) when setting up deployment access:

```
ssh-ed25519 AAAAC3NzaC1lZDI1NTE5AAAAIG2dEYdKr2Ks1GV5+qVTF4uWBzETUL8giSV77C/CtQ4p rabattag-deploy
```

This is a public key only — safe to store/share. The matching private key is not stored in this repo and must be kept securely by whoever controls the deploy process.

1. **Build locally first** (cPanel has no Node.js in this workflow):
   ```bash
   composer install --no-dev --optimize-autoloader
   npm install && npm run build
   ```
2. **Upload the project.** Either use cPanel's Git Version Control feature to clone your repo, or zip the whole project (including `vendor/` and `public/build/`, excluding `node_modules/`) and extract it via File Manager. Put it outside `public_html`, e.g. `~/dealhub-app/`.
3. **Set the domain's Document Root to the `public/` folder** of the project (Domains → Manage → Document Root in cPanel). This is the single most important security step — the rest of the Laravel app must never be web-accessible.
4. **Create a MySQL database and user** in cPanel (MySQL Databases), and note the database name, username, and password (cPanel usually prefixes these with your account name).
5. **Configure `.env`** on the server:
   ```
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://yourdomain.com
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_db_name
   DB_USERNAME=your_db_user
   DB_PASSWORD=your_db_password
   QUEUE_CONNECTION=database
   SESSION_DRIVER=database
   CACHE_STORE=database
   MAIL_MAILER=smtp   # use your host's SMTP or Mailgun/SendGrid — many shared hosts throttle mail
   ```
   Generate a fresh app key on the server: `php artisan key:generate`.
6. **Run migrations.** If your host gives SSH access:
   ```bash
   php artisan migrate --force
   php artisan db:seed --force   # optional, only if you want the demo/role data
   ```
   Without SSH, most cPanel hosts let you run a one-off PHP script via a browser-triggered route or the "Cron Jobs" → "Run once" trick, or you can use phpMyAdmin to import a SQL dump exported locally with `php artisan schema:dump`.
7. **Set up the cron job** (cPanel → Cron Jobs) so Laravel's scheduler (which handles offer publish/expire and queued jobs) runs every minute:
   ```
   * * * * * cd /home/youruser/dealhub-app && php artisan schedule:run >> /dev/null 2>&1
   ```
7a. **Set up the queue worker for background jobs (Awin sync).** The `schedule:run` cron entry above (step 7) runs the scheduler, which in turn runs commands like `awin:sync` and `offers:expire-check` **synchronously** — those need no queue worker. But the admin panel's **"Sync Now"** button dispatches `RunAwinSyncJob` onto the queue instead, and that job sits unprocessed in the `jobs` table until something runs it. `schedule:run` does **not** substitute for a queue worker — they are two independent mechanisms.

   - **Recommended for most shared cPanel hosting (no Supervisor/root access):** trigger the queue via cron instead of running a persistent worker. Add a second cPanel Cron Job that runs every minute:
     ```
     * * * * * cd /home/youruser/rabattag-app && php artisan queue:work --stop-when-empty --tries=3 >> /dev/null 2>&1
     ```
     `--stop-when-empty` makes the worker process whatever is currently queued and then exit immediately, rather than running forever. This is the standard, low-privilege-friendly approach for shared hosting: it needs no long-running process, works within typical shared-host cron/process limits, and is self-healing (a stuck or crashed run is simply replaced by the next minute's cron trigger).

   - **Alternative — only if your host supports Supervisor or a process manager** (a VPS-backed cPanel plan, WHM with root access, or a "Setup Node.js App"-style process manager some hosts offer): run a persistent worker that auto-restarts on crash or reboot. Most shared cPanel plans do **not** offer this, so treat it as an option rather than the default. Example Supervisor config:
     ```ini
     [program:rabattag-worker]
     process_name=%(program_name)s
     command=php /home/youruser/rabattag-app/artisan queue:work --sleep=3 --tries=3
     autostart=true
     autorestart=true
     numprocs=1
     ```

8. **Storage permissions.** Ensure `storage/` and `bootstrap/cache/` are writable by the web server user (`chmod -R 775` is usually enough on shared hosts), and run `php artisan storage:link` if you use the local disk for merchant logos.
9. **Verify:** visit `https://yourdomain.com` — it should redirect to `/en` or `/de` depending on the visitor's browser language.

### Re-deploying updates

Whenever you push a change:

```bash
composer install --no-dev --optimize-autoloader
npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Upload the changed files (or `git pull` if using cPanel Git), then re-run the cache commands above on the server.

## Testing

```bash
php artisan test
```
