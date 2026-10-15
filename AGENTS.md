# AGENTS.md

Laravel 12 (PHP ^8.2) financial-literacy app with Breeze auth, Vite + Tailwind v4 frontend, Pest tests.

## Commands

- Full setup: `composer run setup` (installs PHP/JS deps, creates `.env`, generates key, migrates, builds assets). Dev `.env` uses MySQL `db_edukasi`; with the XAMPP-style default, start MySQL first or switch `DB_CONNECTION` to `sqlite`.
- Dev stack: `composer run dev` runs `php artisan serve`, `queue:listen`, `pail`, and `npm run dev` together.
- Frontend/backend only: `npm run dev` or `php artisan serve`.
- Tests: `composer test` (`php artisan config:clear` then `php artisan test`). Single suite/file: `php artisan test tests/Feature/Auth/AuthenticationTest.php`. Single test: `php artisan test --filter="users can logout"`.
- Format: `vendor/bin/pint`; check only: `vendor/bin/pint --test` (Laravel preset). Existing source currently has Pint violations, so reformat only files you touch.

## Environment / services

- Tests always use sqlite in-memory plus array stores (`phpunit.xml`), independent of `.env`; no DB service needed.
- Market data comes from CoinGecko, Yahoo Finance, and XAUS without API keys.
- Gemini needs `GEMINI_API_KEY`. On missing/failed requests, `NlgService` returns `fallbackNarasi()` after up to a 60s timeout; dashboard and narasi pages trigger this call.
- `NlgService::$baseUrl` is hardcoded to `gemini-1.5-flash`; `config('services.gemini.base_url')` / `GEMINI_BASE_URL` are not read. Edit the property to change models.
- Market responses are cached 5 minutes in the configured cache store.

## Architecture

- Routes: `routes/web.php` (app) + `routes/auth.php` (Breeze).
- Main controller: `app/Http/Controllers/EdukasiController.php`; services in `app/Services/`.
- `dashboard()` renders the top-level `dashboard` view; kuis/kalkulator/market/narasi render `edukasi.*` views.
- `MarketDataService::getAllCurrentPrices()` returns the `bitcoin`, `stock`, and `gold` payloads used by dashboard and market pages.
- `AssetHistory` foreign key column is `id_user` (not `user_id`).
- `RiskProfile` is keyed by `nim` (unique). `User.nim` is nullable and is not captured during registration; dashboard/narasi query `RiskProfile::where('nim', $user->nim)` and fall back to `Moderat` when none exists. The quiz form at `/kuis-risiko` creates/updates a profile from submitted `nama`, `nim`, and `skor` (0-5 Konservatif, 6-10 Moderat, 11-15 Agresif).
- `MarketDataService::clearCache()` exists but is not exposed by any route or command.

## Testing

Pest 3 + `pest-plugin-laravel`; repo only ships Breeze default auth/profile tests. Fakes needed for `Http` when testing market or Gemini flows.