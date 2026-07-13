---
name: Registration flow end-to-end test findings
description: Results of testing public registration -> admin verification wizard -> WA send -> student account, and the class-enrollment gap found.
---

Tested the full flow via curl (CSRF-aware, cookie-jar login) + tinker DB checks, not just code reading:

1. Public sign-up is `POST /register` (Breeze `RegisteredUserController@store`, overridden to write a `StudentRegistration` row with status `pending`) — **not** `/public/student-registrations` (that endpoint exists but no live view posts to it; `formdaftarsiswa.blade.php` referencing it is an orphan, confirming the earlier orphan-file memory).
2. Admin flow: `admin.registration-list.process` wizard → `RegistrationListController@processStore` creates User(role siswa)+Student+Invoice, assigns teacher via `student_teachers`, marks registration `verified`. Works correctly end-to-end when given valid branch/package/course ids.
3. WA send (`sendToWA()` in `process.blade.php`) is client-side only — builds a `wa.me` link from the JSON response's name/email/password/no_reg. No backend action needed; confirmed correct.
4. **Confirmed gap**: nothing in the entire app ever inserts into `class_students` (grepped all controllers) — it's read-only everywhere (attendance, schedules, messages, siswa dashboard). A freshly verified student is never actually placed in a `school_classes` row despite having a package/teacher. Filed as follow-up task, not fixed inline (out of scope for the test request).
5. Demo credentials in replit.md are stale: seeded DB (`SlimSeeder`) has no `admin`-role user, only `owner` (adminpusatsci@akademi.com), `guru`, `siswa`. Admin-only-looking pages are actually reachable by `owner` too (`role:admin|owner` middleware), so testing as owner works fine.

**Why this matters**: don't assume "student registered" means "student scheduled into a class" in this codebase — those are two separate, currently disconnected steps.
