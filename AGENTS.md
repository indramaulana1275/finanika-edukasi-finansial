# AGENTS.md

Laravel 12 (PHP ^8.2) financial-literacy app: Breeze auth, Vite + Tailwind v4, Pest tests.

## Commands

- Setup: `composer run setup` (PHP/JS deps, `.env` from example, app key, migrations, asset build).
- Dev stack: `composer run dev` runs `php artisan serve`, `queue:listen`, `pail`, and `npm run dev`.
- Single process: `php artisan serve` or `npm run dev`.
- Tests: `composer test` (`config:clear`, then `php artisan test`). One file: `php artisan test tests/Feature/Auth/AuthenticationTest.php`. One test: `php artisan test --filter="users can logout"`.
- Format: `vendor/bin/pint` (Laravel preset); check only: `vendor/bin/pint --test`. The tree currently has Pint violations; format only files you touch.
- Assets: `npm run build`.

## Environment / services

- Tests use sqlite in-memory plus array stores from `phpunit.xml`, independent of `.env`; no DB service needed.
- `.env` is configured for MySQL `db_edukasi`; `.env.example` defaults to sqlite. Setup created from the example stays sqlite unless changed.
- Market feeds (CoinGecko, Yahoo Finance, XAUS) need no API keys. `MarketDataService` fetches all current/history payloads concurrently with `Http::pool`; current/history cache TTL is 5 min (`market.all.current.v2`, `market.all.history.{days}.v2`). `clearCache()` only forgets current plus 7/14/30/90-day history keys.
- Gemini requires `GEMINI_API_KEY`. `NlgService` hardcodes `gemini-3.5-flash-lite` in `$baseUrl` (env/config base URLs are ignored), sends it via `x-goog-api-key`, uses a 60s timeout, and falls back to `fallbackNarasi()` on missing key or failure. Dashboard and narasi pages call it.
- External market/Gemini calls can make dashboard and market requests take up to roughly 60s.

## Architecture

- Routes: `routes/web.php` (app) plus `routes/auth.php` (Breeze).
- Main controller: `app/Http/Controllers/EdukasiController.php`; services in `app/Services/`.
- `dashboard()` renders the top-level `dashboard` view; kuis, kalkulator, market, and narasi render `edukasi.*` views.
- Market read model: `MarketDataService::getAllCurrentPrices()` returns `bitcoin`, `stock`, and `gold` payloads; `getAllHistoricalPrices($days)` returns the same keys. Legacy single-asset methods delegate to these and ignore custom symbols (stock is always `^JKSE`).
- Market AJAX endpoints require auth: `/api/market/current`, `/api/market/history?asset=btc|stock|gold&days=...`.
- `AssetHistory` foreign key column is `id_user` (not `user_id`).
- `RiskProfile` is keyed by unique `nim`. `User.nim` is nullable/unique and is not captured during registration; dashboard/narasi fall back to `Moderat`. `/kuis-risiko` upserts from submitted `nama`, `nim`, and `skor` (0-5 Konservatif, 6-10 Moderat, 11-15 Agresif).
- `MarketDataService::clearCache()` exists but is not exposed by any route or command.

## Testing

- Pest 3 + `pest-plugin-laravel`; Feature tests use `RefreshDatabase`. Repo only ships Breeze default auth/profile tests. Use `Http::fake()` for market or Gemini paths.
