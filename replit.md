# Akademi Bimbel

A multi-branch academic management system (SaaS-style) for tutoring centers (bimbel). Built on Laravel 9 with PostgreSQL, Tailwind CSS, Alpine.js, and Vite.

## Stack
- **Backend:** PHP 8.2 / Laravel 9
- **Frontend:** Tailwind CSS, Alpine.js, Vite
- **Database:** PostgreSQL (Replit managed)
- **Auth:** Laravel Breeze (native session auth)
- **RBAC:** Spatie Laravel Permission (roles: owner, admin, guru, siswa, karyawan)

## Running the app
The app starts via `bash start.sh` which:
1. Installs Composer deps if missing
2. Writes `.env` from Replit env vars (PGHOST, PGPORT, etc.)
3. Runs migrations
4. Seeds the DB if empty
5. Builds frontend assets if `public/build` is missing
6. Starts `php artisan serve` on port 5000

## Demo accounts
| Role  | Email | Password |
|-------|-------|----------|
| Owner | adminpusatsci@akademi.com | password |
| Admin | admincabangasci@akademi.com | password |
| Guru  | gurusci@gmail.com | password123 |

## Key routes
- `/` — Landing page
- `/login` — Login
- `/register` — Student registration (multi-step)
- `/admin/*` — Admin dashboard
- `/owner/*` — Owner dashboard
- `/guru/*` — Teacher dashboard
- `/siswa/*` — Student dashboard

## User preferences
- Keep existing MVC structure (controllers per role under `app/Http/Controllers/Admin|Guru|Owner|Siswa/`)
- Use Blade templates; do not switch to a JS-heavy SPA
- Indonesian language UI (Bahasa Indonesia)
