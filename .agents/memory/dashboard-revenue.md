---
name: Dashboard revenue data source
description: Revenue stat cards were hardcoded to 0; now live from Payment model
---

## Revenue queries
- `DashboardService::ownerDashboard()` — `Payment::where('status','verified')->sum('jumlah')`
- `DashboardService::adminDashboard($branchId)` — same but scoped by `cabang_id` when branchId is set
- `dashboard.blade.php` stat card — `Payment::where('status','verified')->whereYear/Month->sum('jumlah')` for current month

**Why:** Original code hardcoded `total_revenue => 0`. Payment model exists with `jumlah` and `status` fields. `verified` is the confirmed payment status.

**How to apply:** Any new revenue display should query `Payment::where('status','verified')->sum('jumlah')` (with optional branch/date scope).
