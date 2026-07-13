---
name: Registration flow end-to-end test findings
description: Results of testing public registration -> admin verification wizard -> WA send -> student account, and the class-enrollment gap found.
---

Tested the full flow via curl (CSRF-aware, cookie-jar login) + tinker DB checks, not just code reading:

1. Public sign-up is `POST /register` (Breeze `RegisteredUserController@store`, overridden to write a `StudentRegistration` row with status `pending`) — **not** `/public/student-registrations` (that endpoint exists but no live view posts to it; `formdaftarsiswa.blade.php` referencing it is an orphan, confirming the earlier orphan-file memory).
2. Admin flow: `admin.registration-list.process` wizard → `RegistrationListController@processStore` creates User(role siswa)+Student+Invoice, assigns teacher via `student_teachers`, marks registration `verified`. Works correctly end-to-end when given valid branch/package/course ids.
3. WA send (`sendToWA()` in `process.blade.php`) is client-side only — builds a `wa.me` link from the JSON response's name/email/password/no_reg. No backend action needed; confirmed correct.
4. **Fixed**: `RegistrationListController@processStore` now auto-enrolls the student into `class_students` right when the account is created — per selected course it reuses an existing active `school_classes` row matching branch+course+teacher, or creates one (naming mirrors `SchoolClassController@store`'s auto-naming), then inserts the pivot row. Verified via a second full registration → process cycle that `$student->schoolClasses()` returns the expected class.
5. Demo credentials in replit.md are stale: seeded DB (`SlimSeeder`) has no `admin`-role user, only `owner` (adminpusatsci@akademi.com), `guru`, `siswa`. Admin-only-looking pages are actually reachable by `owner` too (`role:admin|owner` middleware), so testing as owner works fine.

**Why this matters**: don't assume "student registered" means "student scheduled into a class" in this codebase — those are two separate, currently disconnected steps.
