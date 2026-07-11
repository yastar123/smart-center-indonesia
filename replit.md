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

## Admin: Registrasi Siswa Baru (5-step wizard)
`admin/registrasi-baru` (route `admin.registration.wizard`) is a 5-step wizard for admins to register a brand-new student directly: Informasi Siswa → Paket Kelas → Mapel & Guru → Pembayaran → Preview. On submit it creates the User/Student account and invoice, then shows a "Kirim ke WhatsApp Siswa" button that opens a pre-filled `wa.me` link with the login credentials — mirroring the pattern already used in the lead-verification wizard (`admin/registration-list/{id}/process`). Controller: `RegistrationController@wizardCreate`/`wizardStore`. View: `resources/views/admin/registration/wizard.blade.php`.

## Admin: Verifikasi pendaftaran lead → wizard (not instant account creation)
The "Verifikasi" button on the dashboard's "Siswa Terbaru Mendaftar" widget (for public leads in `student_registrations`) now **redirects** to the same 5-step wizard (`admin.registration-list.process`, view `resources/views/admin/registration/process.blade.php`) instead of instantly creating an account. Admin fills Paket Kelas → Mapel & Guru → Pembayaran → Preview, then submits; only then is the User/Student/Invoice created. WhatsApp send after success is manual-click (`sendToWA()` opens a pre-filled `wa.me` link using the phone number from the original registration) — deliberately not fully automated (no WhatsApp Business API is integrated). The old instant-verify endpoint (`StudentRegistrationController@verify`, route `admin.student-registrations.verify`) was removed as dead code.

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
