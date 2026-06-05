---
name: Profile/auth views — Breeze vs Bootstrap layout conflict
description: Auth views used <x-app-layout> (Tailwind/Breeze), incompatible with the Bootstrap app layout
---

The project uses a custom Bootstrap 5 layout at `layouts/app.blade.php`, NOT the default Laravel Breeze Tailwind layout.

The original Breeze-generated views (`profile/edit.blade.php`, `auth/register.blade.php`, `auth/forgot-password.blade.php`, `auth/reset-password.blade.php`) all used `<x-app-layout>` or `<x-guest-layout>` which render Tailwind utility classes and Alpine.js directives — these are completely broken without Tailwind CSS.

**Fix:** Rewrite all auth and profile views to either:
- Use `@extends('layouts.app')` + `@section('content')` for authenticated pages
- Use standalone Bootstrap HTML (own `<html>` tag) for guest/auth pages

**How to apply:** Any new view added to this project must NOT use `<x-app-layout>`, `<x-guest-layout>`, or any Blade component from the Breeze scaffolding.
