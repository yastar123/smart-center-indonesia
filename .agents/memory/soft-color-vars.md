---
name: Soft-color CSS variables
description: CSS custom properties for soft status colors that adapt to dark mode — defined in layouts/app.blade.php :root and [data-theme="dark"].
---

## Rule
Never use hardcoded hex values like `background:#fdf4ff;color:#68117e` for inline badges, status chips, summary boxes, or progress bar tracks. Always use the CSS variable equivalents.

## Available variables
| Token | Light | Dark |
|---|---|---|
| `--soft-primary-bg/border/text` | `#fdf4ff / #e8b4f5 / #68117e` | `rgba(200,77,223,.12) / rgba(200,77,223,.25) / #d68eef` |
| `--soft-success-bg/border/text` | `#dcfce7 / #bbf7d0 / #15803d` | `rgba(16,185,129,.12) / rgba(16,185,129,.25) / #34d399` |
| `--soft-warning-bg/border/text` | `#fef3c7 / #fcd34d / #92400e` | `rgba(246,175,35,.12) / rgba(246,175,35,.25) / #fbbf24` |
| `--soft-info-bg/border/text`    | `#e0f2fe / #7dd3fc / #075985` | `rgba(14,165,233,.12) / rgba(14,165,233,.25) / #38bdf8` |
| `--soft-danger-bg/border/text`  | `#fee2e2 / #fecaca / #991b1b` | `rgba(239,68,68,.12) / rgba(239,68,68,.25) / #f87171` |
| `--soft-muted-bg/border/text`   | `#f1f5f9 / #e2e8f0 / #64748b` | `rgba(255,255,255,.06) / rgba(255,255,255,.1) / #94a3b8` |

## How to apply
- Inline badge: `style="background:var(--soft-primary-bg);color:var(--soft-primary-text)"`
- With border: add `border:1px solid var(--soft-primary-border)`
- PHP statusMap: use `'bg'=>'var(--soft-success-bg)'` etc — CSS vars work inside PHP strings rendered as inline styles
- JS template literals: `background:${sbg}` where sbg = `var(--soft-success-bg)` — works as inline style value
- Progress track: `style="background:var(--soft-primary-bg)"` (bar itself keeps solid brand color)
- Alert boxes: use Bootstrap `alert-warning/alert-success` class — has built-in dark mode at layout ~line 983

**Why:** Hardcoded light-mode hex values (#fdf4ff etc.) are invisible or clash in dark mode. The CSS variable approach costs zero extra JS and works across PHP, Blade, and JS template literals uniformly.
