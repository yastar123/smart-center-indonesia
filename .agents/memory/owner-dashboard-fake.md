---
name: Owner dashboard — always use real Eloquent queries
description: The original owner dashboard had hardcoded fake numbers, replaced with live DB queries
---

`resources/views/owner/dashboard.blade.php` originally had hardcoded values (12 cabang, 1240 siswa, 85 guru, Rp 120JT).

**Fix:** Use inline `@php` blocks with Eloquent at the top of the view to query real counts:
- `Branch::count()`, `Branch::where('status','active')->count()`
- `Student::count()`, `Student::where('status','aktif')->count()`
- `Teacher::count()`, `Teacher::where('status','aktif')->count()`

**Why:** The pendapatan (revenue) stat remains `Rp 0` because the payments module is not yet implemented.

**How to apply:** Any stat card in any view must pull from real DB, never hardcode demo numbers.
