---
name: Demo data seeding
description: DemoDataSeeder approach for Akademi Bimbel — what columns exist, what was seeded, and common pitfalls
---

## What was seeded
- 3 branches (Jakarta Selatan, Bandung, Surabaya)
- 4 teachers (linked via user_id to Teacher record)
- 10 students (linked via user_id to Student record)
- 7 courses + 3 school_classes + 18 schedules
- 30 invoices + 20 payments (verified, status=lunas for 2 months ago)

## Columns added via migration 2026_06_05_100003
- `teachers.user_id` — was missing from original migration, added as nullable unsignedBigInteger

## Branch fillable does NOT include `user_id` or `admin_id`
- Branches link to admin via `admin_id` (in DB) but it's not in fillable — must use `DB::table` or add to fillable if needed
- The `user_id` column exists in branches table but is excluded from fillable

## User-branch linkage
- Admin user `admincabangsci@akademi.com` → branch_id must be set manually (was NULL after seeding)
- Fixed: set admin user branch_id = Branch::first()->id

## Key relations confirmed working
- Invoice: siswa(), cabang(), pembayaran()
- Schedule: guru(), kelas(), cabang()
- Student: branch(), user()
- Teacher: branch(), user(); subjects cast as 'array'
- Payment: siswa(), cabang()

**Why:** these were all tested end-to-end via tinker and returned correct data.
