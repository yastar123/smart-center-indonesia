---
name: Route ordering — static before wildcard
description: Static named routes must precede wildcard {param} routes in the same Route group prefix
---

In `routes/web.php`, inside an `->prefix('owner')` group, the routes:

```php
Route::get('/branches/export/excel', ...)   // static
Route::get('/branches/{branch}', ...)       // wildcard
```

**must** have the static export routes declared BEFORE the `{branch}` wildcard, or Laravel matches `export` as a branch ID.

**Why:** Laravel resolves routes in declaration order. Once `{branch}` is registered, any subsequent `/branches/export/...` never gets a chance to match.

**How to apply:** Any time you add a static sub-path under a resource-style wildcard group, put the static route first.
