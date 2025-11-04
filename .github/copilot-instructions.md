## CyberCore — AI coding agent instructions

Purpose: give an AI coding agent the minimal, actionable knowledge to make safe, correct edits in this Laravel app.

-   Short summary: this is a Laravel 11 app (PHP 8.2+) for adaptive cybersecurity training. Core domains: modules, quizzes, attempts, certificates. Key layers: HTTP controllers (app/Http/Controllers), domain services (app/Services), Eloquent models (app/Models), and blade views/resources (resources/views, resources/js).

-   Quick run/dev commands (Windows PowerShell):

```pwsh
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run dev        # vite dev server
php artisan serve   # app on http://localhost:8000
```

-   Composer script (dev) runs a concurrent dev stack (server, queue listener, pail, vite). Use `composer dev` for full local dev if you want background queue processing.

-   Tests and CI:

    -   Unit/Feature tests use in-memory SQLite by default (see `phpunit.xml`).
    -   Run tests with `composer test` or `php artisan test`.

-   Important project conventions & patterns (use these exactly):

    -   Services: business logic lives in `app/Services/` (e.g., `AdaptiveSelector.php`, `CertificateService.php`, `BadgeService.php`). Controllers orchestrate and inject services via constructor DI.
    -   Config-driven behavior: quiz/adaptive parameters are in `config/quiz.php` (e.g., `questions_per_attempt`, `adaptive_window`) and learning taxonomy in `config/learn.php`.
    -   Certificate generation uses `barryvdh/laravel-dompdf` and assets in `public/images/logo.png` and `public/images/signature.png` (modify these rather than templates when replacing branding).
    -   Role/permission checks use `spatie/laravel-permission`. Admin routes are under `routes/web.php` with `prefix('admin')->middleware('role:admin')`.

-   Typical request/flow examples to reference:

    -   Start quiz: `POST /quiz/{module}/start` -> `QuizController::start` (see `routes/web.php`).
    -   Answer question: `POST /quiz/{attempt}/answer` -> `QuizController::answer`.
    -   Certificate download/embed: `GET /certificates/{certificate}/download` -> `CertificateController::download`.

-   Database & seeding notes: default demo data seeded by seeders (see `database/seeders` and README). Default admin: `admin@cybercore.test` / `password`.

-   When editing code, follow these rules:
    1. Preserve public APIs: controllers, route names, and service method signatures are used across views and tests. If you change a signature, update callers and tests.

2.  Keep config keys stable. If adding a new config, register a sensible default in `config/*.php` and reference via `config('your.key')`.
3.  For front-end assets, prefer updating `resources/js` / `resources/css` and run `npm run build` / `npm run dev` rather than changing `public/build` directly.

-   Where to look for tests to update/add: `tests/Feature` for web flows (quiz/certificate/admin), `tests/Unit` for services and small logic. `phpunit.xml` sets test environment variables (in-memory DB, sync queue).

-   Integration points / external deps to be mindful of:

    -   SMTP: configured via `config/mail.php` — tests use `MAIL_MAILER=array` in `phpunit.xml`.
    -   Excel import/export: `maatwebsite/excel` is used for question imports and reports (`app/Imports/QuestionsImport.php`, `app/Exports/ParticipantScoresExport.php`). Keep import templates unchanged unless you also update `ImportController`.

-   Quick examples (copyable) — how to run a focused local change + tests:

```pwsh
# after changing code
php artisan migrate:fresh --seed
composer test
```

If any instruction is unclear or you need more detail on a particular area (e.g., adaptive algorithm, certificate template, import format), tell me which area and I'll expand the file with concrete examples and tests to verify changes.
