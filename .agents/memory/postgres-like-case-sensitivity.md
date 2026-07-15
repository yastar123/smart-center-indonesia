---
name: Postgres LIKE case sensitivity
description: Free-text admin search inputs must use ilike, not like, on Postgres.
---

Postgres `LIKE` is case-sensitive (unlike MySQL). Existing codebase search queries
(`AcademicModuleController`, `BillingController`, `CourseController`, etc.) already
use `'like'` throughout and inherit this bug silently since seeded names happen to
match typed case in demo data.

**Why:** any *new* admin-typed free-text search (e.g. searching students by name/phone/NIS)
will silently return zero results for the common case of lowercase input against
Capitalized seed/display data, with no error — easy to miss in a quick test.

**How to apply:** for any new search/autocomplete endpoint added on this project, use
`'ilike'` instead of `'like'` for Postgres. Don't retrofit the many pre-existing `'like'`
call sites unless asked — that's a separate, larger cleanup.
