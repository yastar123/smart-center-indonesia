---
name: Siswa Lama linking pattern
description: Registration process wizard can link a lead to an existing Student instead of always creating a new account.
---

The admin registration process wizard (`admin.registration-list.process` /
`RegistrationListController@process` + `@processStore`) previously always created a
brand-new `User` + `Student` on submit, with no way to enroll a returning student
into an additional program/class without duplicating their account.

A "Tipe Pendaftaran" toggle (Siswa Baru / Siswa Lama) was added to Step 1. When
"Siswa Lama" is chosen, admin searches existing students via
`admin.registration-list.student-search` (name/phone/NIS, `ilike`) and picks one;
the submitted form carries `registration_type=lama` + `existing_student_id`.

**Why:** without this, every re-registration for an existing student created a
duplicate `User`/`Student` row and a duplicate login account.

**How to apply:** in `processStore`, when `registration_type === 'lama'`, skip
`User::create`/`Student::create` entirely and reuse the found `Student` + its
`user()` relation; only update the existing student's contact/package/session
fields. The JSON response includes `is_existing: true` and `password: null` so the
frontend success panel and WhatsApp message skip showing/sending new credentials
(the account already exists) — only the new program/class enrollment is confirmed.
