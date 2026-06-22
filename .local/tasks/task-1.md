---
title: Admin Panel — Full Audit & UI/UX Overhaul
---
# Admin Panel — Full Audit & UI/UX Overhaul

## What & Why
Comprehensive audit and upgrade of all 15 admin pages to Awwwards-level polish. The current admin already has a solid Bootstrap 5 + custom CSS foundation (sidebar, dark mode, toast system, command palette) but individual pages are inconsistent: some use Bootstrap `.card`, some use custom `.dashboard-card`; animations are minimal; mobile experience has gaps; several pages have untested or incomplete features.

## Done looks like
- Every admin page renders correctly on 320px mobile, 768px tablet, 1280px desktop, and 1440px+ wide screen with no overflow or broken layouts
- Dark mode works consistently across all 15 pages — no white flash, no unthemed elements
- Each page header/stat section has smooth entrance animations (fade-up, staggered cards)
- Tables, forms, modals, and CRUD flows all work end-to-end (create, edit, delete, filter, search)
- Known bugs fixed: messages chat-active double display:none (already done); table thead dark mode; form input dark mode gaps
- Navigation has no broken links; all modal triggers and CRUD buttons behave correctly
- Salary slip PDF and certificate PDF generation work
- Video call Jitsi embed displays at proper size on all screens
- Tryout question bank modal is fully functional
- Reports ApexCharts charts render and are readable in both light and dark mode
- All stat-card count-up animations fire consistently

## Out of scope
- Landing page (not admin)
- Guru/Siswa portals
- Authentication flow changes
- Any new features not already in the codebase

## Steps

### Phase 1 — Layout & Global Styles (app.blade.php)
1. **Micro-animation system** — Add a lightweight CSS animation library inline: `@keyframes fadeUp`, `@keyframes fadeIn`, `@keyframes scaleIn`. Apply `animate-fade-up` utility class with staggered `animation-delay` to stat cards and page headers on every page load.
2. **Dark mode completeness** — Audit and patch every unthemed element: form inputs, selects, table rows, modal backdrops, dropdown menus, ApexCharts theme, DataTables wrapper. Ensure `[data-theme="dark"]` CSS covers all Bootstrap components.
3. **Topbar & sidebar mobile polish** — On 320–480px, topbar should stack cleanly; sidebar overlay uses a blurred backdrop. Verify mini-mode toggle is hidden on mobile (it only makes sense on desktop).
4. **Fluid typography** — Replace fixed `px` font sizes on headers and stat cards with `clamp()` values so text scales gracefully from mobile to wide screen.

### Phase 2 — Dashboard & Stats Pages
5. **Dashboard** — Fix any hardcoded or zero values; ensure all ApexCharts initialize properly in dark mode; add chart legend tooltips; ensure the revenue/student count-up animations run every page load (not just first).
6. **Reports page** — ApexCharts area + donut charts: add `theme: { mode }` dynamic binding to dark/light toggle; fix chart re-render when toggling dark mode; make charts responsive (100% width).

### Phase 3 — Data Tables (Students, Teachers, Classes, Courses, Schedules, Certificates, Modules, Packages, Salaries, Tryouts)
7. **Table dark mode** — Ensure all table headers use `var(--input-bg)` + `var(--text-muted)`, row hover uses `rgba(104,17,126,.05)`, and no hardcoded white backgrounds exist.
8. **Mobile table responsiveness** — On 320–480px: convert wide tables to card-style stacked rows using CSS-only approach (`d-none d-md-table-cell` already on some columns — verify and extend). Add a "tap to expand row" detail on mobile for hidden columns.
9. **Filter bars** — Ensure all filter forms (search + dropdowns) collapse into a single-row layout on mobile with full-width inputs; add a "Reset filter" button that's actually functional on all pages.
10. **Empty states** — Every table should have a well-designed empty-state illustration/message when no results match the filter, replacing the default "No data available" DataTables text.

### Phase 4 — CRUD Modals & Forms
11. **Modal consistency** — All create/edit modals should use the same structure: `modal-dialog modal-lg` with gradient header, scrollable body, sticky footer with Cancel + Save buttons. Audit each page's modal and standardize.
12. **Form validation UX** — All forms should show inline error messages (Bootstrap `invalid-feedback`) and success highlight on each field. Confirm that server-side validation errors are passed back to the modal and displayed field-by-field (not just a generic alert).
13. **Delete confirmation** — All delete buttons should use SweetAlert2 with the app's purple theme (`confirmButtonColor: '#c84ddf'`). Verify no page still uses `onclick="return confirm()"`.
14. **Image/file upload preview** — Student and Teacher photo uploads should show a live thumbnail preview before save. Modules file upload should show filename + file size.

### Phase 5 — Specialized Pages
15. **Messages (Chat)** — Fix the chat active pane `flex` display issue (already patched). Improve the chat bubble UI: own messages right-aligned with purple bg, others left with card bg. Add scroll-to-bottom button. Ensure polling works and doesn't produce console errors.
16. **Video Call** — Jitsi embed: set `height: 600px` min on desktop, `calc(100vh - 200px)` on mobile. Room name sanitization (strip special chars). Copy-link button functional.
17. **Tryout / Question Bank** — Ensure the Question Bank modal with add/edit/delete questions works end-to-end. Fix any z-index issues where nested modals (tryout edit → question bank) overlap incorrectly.
18. **Salary slip PDF** — Verify the PDF generation route works; ensure the slip template is styled properly and prints without cutting off content.
19. **Certificates** — Certificate issue form should validate expiry date is after issue date; lifetime toggle should disable the date field.

### Phase 6 — Final Polish
20. **Consistent page banners** — All 15 page headers should follow the same structure: gradient banner card, large icon, title, subtitle, and a primary action button (Add New) — consistent padding, border-radius 20px, icon size 56px.
21. **Button & badge system** — Audit all action buttons: use consistent rounded-pill badges for status, consistent icon+text buttons for actions. No bare unstyled `<button>` elements.
22. **Accessibility pass** — Add `aria-label` to all icon-only buttons; ensure color contrast ≥ 4.5:1 for all text; add `title` tooltips to all icon buttons.
23. **Performance** — Add `loading="lazy"` to all `<img>` tags in tables (student/teacher photos). Ensure no inline `<script>` blocks declare duplicate functions across pages.
24. **Bug sweep** — Fix any remaining console errors found during the audit; remove dead/orphaned JS functions; ensure all AJAX calls handle error states with a user-visible toast.

## Relevant files
- `resources/views/layouts/app.blade.php`
- `resources/views/admin/students/index.blade.php`
- `resources/views/admin/teachers/index.blade.php`
- `resources/views/admin/classes/index.blade.php`
- `resources/views/admin/courses/index.blade.php`
- `resources/views/admin/schedules/index.blade.php`
- `resources/views/admin/certificates/index.blade.php`
- `resources/views/admin/modules/index.blade.php`
- `resources/views/admin/packages/index.blade.php`
- `resources/views/admin/salaries/index.blade.php`
- `resources/views/admin/payments/index.blade.php`
- `resources/views/admin/reports/index.blade.php`
- `resources/views/admin/tryouts/index.blade.php`
- `resources/views/admin/messages/index.blade.php`
- `resources/views/admin/videocall/index.blade.php`
- `resources/views/admin/announcements/index.blade.php`