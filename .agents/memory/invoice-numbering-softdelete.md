---
name: Invoice numbering collides with soft-deleted rows
description: Why nomor_invoice generation can throw a unique-constraint violation even when the visible invoice count looks low.
---

## Rule
`Invoice` uses `SoftDeletes`. Invoice-number generators in registration controllers (e.g. `RegistrationListController@processStore`, `RegistrationController@wizardStore`) build `nomor_invoice` from `Invoice::whereYear(...)->whereMonth(...)->count() + 1` — a query that excludes soft-deleted rows by default. But the unique index on `nomor_invoice` still sees soft-deleted rows, since they're still physically in the table.

**Why:** If a test/dev flow creates then soft-deletes an invoice within the same month, the "next" count-based number can collide with the deleted row's number, and the insert throws `SQLSTATE[23505]` unique violation.

**How to apply:** When cleaning up test data created via these registration flows, use `forceDelete()` on the `Invoice` (not `delete()`) so the number is freed. If this collision starts happening in real (non-test) usage, the generator itself needs fixing — e.g. use `withTrashed()->count()` or a dedicated sequence — this has not been fixed yet, only worked around during manual testing.
