---
name: Registration verify flow must go through the wizard
description: Why the dashboard "Verifikasi" button on new-lead registrations redirects into the multi-step wizard instead of creating an account instantly, and how WhatsApp sending stays manual.
---

## Rule
Clicking "Verifikasi" on a pending `student_registrations` lead (shown in the dashboard's "Siswa Terbaru Mendaftar" widget) must **navigate** into the existing 5-step admin wizard (route `admin.registration-list.process`, view `resources/views/admin/registration/process.blade.php`) — Informasi Siswa → Paket Kelas → Mapel & Guru → Pembayaran → Preview. The User/Student/Invoice account is only created when the admin submits the wizard's final Preview step, never on the initial button click.

**Why:** The product owner explicitly rejected instant account creation on click — admin must choose package/course/teacher/payment status first. An instant-verify endpoint (`StudentRegistrationController@verify`) previously existed and was removed as dead code once the button started redirecting instead of calling it via AJAX.

**How to apply:** If asked to change this flow again, don't re-introduce an AJAX "verify" endpoint that creates accounts directly from the dashboard list — route it through the wizard's `process`/`processStore` pair (or an equivalent wizard) instead.

## WhatsApp sending stays manual-click
After account creation succeeds (in this wizard, and in the standalone `admin.registration.wizard` for brand-new students with no lead), the success panel shows a "Kirim ke WhatsApp Siswa" button that opens a `wa.me` link pre-filled with the credentials, using the phone number captured at registration. The admin still presses "send" inside WhatsApp itself.

**Why:** The user was asked to choose between this (no setup, but not literally automatic) vs. a true WhatsApp Business API integration (e.g. Twilio, which is available as a not-yet-configured connector) that would send with zero manual steps but needs business account setup/approval and per-message cost. The user chose the manual-click approach explicitly on 2026-07-11.

**How to apply:** Don't build automatic backend WhatsApp sending unless the user asks to revisit this decision and is willing to set up a WhatsApp Business API connector.
