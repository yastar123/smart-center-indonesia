---
name: Payment and Schedule controller patterns
description: Invoice CRUD route binding, markPaid route naming, SchoolClass field names
---

## Invoice/Payment routing
- Route param for Invoice CRUD is `{payment}` → controller method gets `Invoice $payment`
- markPaid route is `{invoice}` → controller method gets `Invoice $invoice` (different param name to avoid confusion)
- Invoice status values: `belum_bayar`, `sebagian`, `lunas`
- Payment status for revenue: `verified`

## Schedule model fields
- `kelas_id` → SchoolClass model, table `school_classes`, display field `nama`
- `guru_id` → Teacher model, display field `name`
- `jenis` values: `offline`, `online`  
- `status` values: `dijadwalkan`, `berlangsung`, `selesai`, `dibatalkan`

**Why:** Route model binding requires the param name to match the variable name. Using `{payment}` for invoices is intentional to keep named routes as `admin.payments.*`.

**How to apply:** When adding new routes that overlap (e.g., markPaid uses a different param), use distinct param names in the route definition.
