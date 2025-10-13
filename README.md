# CyberCore – Cybersecurity Learning Platform

CyberCore is a Laravel-based e-learning platform that delivers cybersecurity awareness training through adaptive quizzes, rich module content, and certificate generation. The project provides an end-to-end learner journey—from module discovery, to quiz completion, to certificate download—with an administration area for curating content.

## Key Features

- **Adaptive quizzes** with multiple question types (MCQ, true/false, fill-in-the-blank) and difficulty tracking.
- **Instruction Gate** – learners must read module guidance before the first question, ensuring compliance with exam instructions.
- **Dynamic certificates**  
  - Auto-generated certificate number (`CERT-YYYYMMDD-XXXX`) stored in the database.  
  - PDF includes organization logo and authorized signature image (configurable via `public/images/logo.png` and `public/images/signature.png`).  
  - Download and embed views available after a successful pass.  
- **Module catalog** with real-time progress tracking, difficulty indicators, and prep notes.
- **Admin tooling** for managing modules, questions, and sections.
- **Role-based access** (admin, lecturer, learner) powered by Spatie Permissions.

## Tech Stack

- PHP 8.2+ / Laravel 11
- SQLite (default) – switchable to MySQL/PostgreSQL
- Tailwind CSS + Vite build pipeline
- Alpine.js for lightweight interactivity
- Barryvdh DomPDF for certificate rendering

## Requirements

- PHP 8.2 or newer with required extensions
- Composer
- Node.js 18+ & npm
- SQLite (bundled) or an alternative database driver

## Getting Started

1. **Clone & Install**
   ```bash
   git clone https://github.com/your-org/cybercore.git
   cd cybercore
   composer install
   npm install
   ```

2. **Environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Update `.env` database settings if you prefer MySQL/PostgreSQL. By default the project uses `database/database.sqlite` (created automatically after migrations).

3. **Database & Seed**
   ```bash
   php artisan migrate --seed
   ```
   Seeder summary:
   - `RoleAndAdminSeeder` – creates `admin@cybercore.test` with password `password`
   - `LecturerSeeder`, `CourseSeeder`, `DemoQuizSeeder` – sample users, modules, and questions

4. **Assets**
   ```bash
   npm run dev          # or npm run build for production
   ```

5. **Run the app**
   ```bash
   php artisan serve
   ```
   Visit `http://localhost:8000`

## Branding Assets

- Replace the site logo by updating `public/images/logo.png` (used across the app, including the certificate PDF).
- Replace the authorized signature graphic in `public/images/signature.png`.

If these files are missing, the certificate templates will fall back to textual placeholders.

## Default Credentials

| Role  | Email                  | Password |
|-------|------------------------|----------|
| Admin | `admin@cybercore.test` | `password` |

Run `php artisan db:seed` again after refreshing the database to recreate demo accounts and content.

## Common Commands

```bash
php artisan migrate:fresh --seed   # Rebuild the database with seed data
php artisan tinker                 # Inspect models / run quick scripts
npm run lint && npm run build      # Frontend quality + production build
```

## Testing Checklist

- Quiz flow with instruction acknowledgment
- Certificate issue + download after passing a module
- Admin module/question CRUD
- Learn catalog progress updates

## Contributing

Pull requests are welcome! Please open an issue first to discuss major changes. Follow PSR-12 for PHP and the Tailwind/Alpine conventions already present in the codebase.

## License

This project is open-sourced under the [MIT License](LICENSE).  
© {{ date('Y') }} CyberCore Security Training.
