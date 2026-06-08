---
name: Brand color audit
description: Rules for brand-consistent gradients across all page headers and UI elements.
---

## Standard page header gradient
All structural page headers (`.dashboard-card` or `.page-header` used as banner) must use:
```
background: linear-gradient(135deg, #260632 0%, #461256 50%, #c84ddf 100%)
```

## Intentional non-purple colors
These are semantically correct and should NOT be changed to purple:
- **Semantic green** `#10b981`/`#059669`/`#15803d`/`#16a34a` — status "lunas", "selesai", revenue icons
- **Semantic red** `#ef4444`/`#dc2626` — danger, error states
- **Brand gold** `#f6af23`/`#e09000`/`#d97706` — owner "Monitor Cabang" quick-dash, award accent
- **Teal** `#0d9488`/`#134e4a` — videocall page (intentional communication feature distinction)
- **Sky blue** `#0284c7`/`#38bdf8`/`#0369a1` — `bg-info-soft` CSS class, izin attendance status
- **Pink** `#ec4899`/`#f472b6` — female gender stat icons
- **Soft pastels** `#fdf4ff`, `#fae8ff`, `#fffbeb`, `#fef3c7` — card accent backgrounds (not headers)

## Message/chat UI
Admin messages page uses `#c84ddf,#7c3aed` (purple→violet) gradient for sent-message bubbles — acceptable.

## Pages fixed during audit
- `admin/payments` — payment modal header: green→purple
- `owner/activity-log` — header: indigo-start→pure purple
- `siswa/certificates` — header: amber/brown→purple
- `admin/certificates` — header: brand-gold→purple
All other pages were already on brand.

**Why:** Consistent headers create visual identity; exception list preserves semantic meaning for status/feature colors.
