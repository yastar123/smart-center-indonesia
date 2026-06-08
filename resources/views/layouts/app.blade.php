<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title','Dashboard') | Smart Center</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <style>
        /* ============================================================
           CSS CUSTOM PROPERTIES
        ============================================================ */
        :root {
            --bs-primary: #c84ddf;
            --bs-primary-rgb: 200, 77, 223;
            --bs-link-color: #c84ddf;
            --bs-link-hover-color: #68117e;
            --sidebar-width: 270px;
            --sidebar-bg: #260632;
            --sidebar-hover: #461256;
            --primary: #c84ddf;
            --primary-dark: #68117e;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f6af23;
            --info: #ab8db2;
            --dark: #260632;
            --text: #e2d6e8;
            --border: rgba(255,255,255,.07);
            --content-bg: #f8f5fa;
            --card-bg: #ffffff;
            --card-border: #e8ddef;
            --text-primary: #260632;
            --text-muted: #6b5878;
            --topbar-bg: #ffffff;
            --input-bg: #faf7fc;
            --font-sans: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
            --font-display: 'Plus Jakarta Sans', 'Inter', system-ui, sans-serif;
            --radius-card: 20px;
            --radius-btn: 12px;
            --shadow-sm: 0 1px 3px rgba(38,6,50,.06), 0 1px 2px rgba(38,6,50,.04);
            --shadow-md: 0 4px 16px rgba(38,6,50,.08);
            --shadow-lg: 0 10px 36px rgba(38,6,50,.12);
            --transition: .25s cubic-bezier(.4,0,.2,1);
            --overdue-bg: #fef2f2;
            --overdue-border: #fecaca;
        }

        [data-theme="dark"] {
            --content-bg: #1a0425;
            --card-bg: #2d0a3e;
            --card-border: rgba(200,77,223,.12);
            --text-primary: #f0e8f5;
            --text-muted: #ab8db2;
            --topbar-bg: #2d0a3e;
            --input-bg: #1a0425;
            --overdue-bg: #2d1515;
            --overdue-border: #7f1d1d;
        }

        /* ============================================================
           RESET & BASE
        ============================================================ */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html { scroll-behavior: smooth; }

        body {
            font-family: var(--font-sans);
            font-size: clamp(13.5px, 1.1vw, 15px);
            background: var(--content-bg);
            color: var(--text-primary);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            transition: background var(--transition), color var(--transition);
        }

        h1, h2, h3, h4, h5, h6 { font-family: var(--font-display); letter-spacing: -.025em; }
        h1 { font-size: clamp(26px, 3.5vw, 40px); }
        h2 { font-size: clamp(20px, 2.8vw, 30px); }
        h3 { font-size: clamp(17px, 2vw, 22px); }

        /* ============================================================
           ANIMATIONS — full micro-animation system
        ============================================================ */
        .anim { transition: all .4s cubic-bezier(.22,1,.36,1); will-change: transform, opacity; }

        /* Keyframes */
        @keyframes fadeUp    { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
        @keyframes scaleIn   { from{opacity:0;transform:scale(.94)}        to{opacity:1;transform:scale(1)} }
        @keyframes fadeInLeft  { from{opacity:0;transform:translateX(-14px)} to{opacity:1;transform:translateX(0)} }
        @keyframes fadeInRight { from{opacity:0;transform:translateX(14px)}  to{opacity:1;transform:translateX(0)} }
        @keyframes fadeIn    { from{opacity:0;transform:translateY(8px);}  to{opacity:1;transform:translateY(0);} }

        /* Utility classes */
        .anim-fade-up    { animation: fadeUp      .45s cubic-bezier(.22,1,.36,1) both; }
        .anim-scale-in   { animation: scaleIn     .30s cubic-bezier(.22,1,.36,1) both; }
        .anim-fade-left  { animation: fadeInLeft  .40s cubic-bezier(.22,1,.36,1) both; }
        .anim-fade-right { animation: fadeInRight .40s cubic-bezier(.22,1,.36,1) both; }
        .fade-in         { animation: fadeIn      .40s ease both; }

        /* Animation delay helpers */
        .anim-d1 { animation-delay:.05s!important; }
        .anim-d2 { animation-delay:.10s!important; }
        .anim-d3 { animation-delay:.15s!important; }
        .anim-d4 { animation-delay:.20s!important; }
        .anim-d5 { animation-delay:.25s!important; }
        .anim-d6 { animation-delay:.30s!important; }
        .anim-d7 { animation-delay:.35s!important; }
        .anim-d8 { animation-delay:.40s!important; }

        /* Page wrapper entrance (IntersectionObserver adds .in-view) */
        .fade-up { opacity: 0; transform: translateY(16px); transition: opacity .45s cubic-bezier(.22,1,.36,1), transform .45s cubic-bezier(.22,1,.36,1); }
        .fade-up.in-view { opacity: 1; transform: translateY(0); }

        /* ---- Staggered stat-card entrance (auto-applied globally) ---- */
        .stat-card {
            animation: fadeUp .5s cubic-bezier(.22,1,.36,1) both;
        }
        .row > [class*='col']:nth-child(1) .stat-card { animation-delay: .00s; }
        .row > [class*='col']:nth-child(2) .stat-card { animation-delay: .08s; }
        .row > [class*='col']:nth-child(3) .stat-card { animation-delay: .16s; }
        .row > [class*='col']:nth-child(4) .stat-card { animation-delay: .24s; }
        .row > [class*='col']:nth-child(5) .stat-card { animation-delay: .32s; }
        .row > [class*='col']:nth-child(6) .stat-card { animation-delay: .40s; }

        /* ---- Staggered dashboard-card sections ---- */
        .fade-up.in-view > .row:nth-child(1),
        .fade-up.in-view > .dashboard-card:nth-child(1),
        .fade-up.in-view > .mb-4:nth-child(1) { animation: fadeUp .4s .05s cubic-bezier(.22,1,.36,1) both; }
        .fade-up.in-view > .row:nth-child(2),
        .fade-up.in-view > .dashboard-card:nth-child(2),
        .fade-up.in-view > .mb-4:nth-child(2) { animation: fadeUp .4s .10s cubic-bezier(.22,1,.36,1) both; }
        .fade-up.in-view > .row:nth-child(3),
        .fade-up.in-view > .dashboard-card:nth-child(3),
        .fade-up.in-view > .mb-4:nth-child(3) { animation: fadeUp .4s .15s cubic-bezier(.22,1,.36,1) both; }
        .fade-up.in-view > .row:nth-child(4),
        .fade-up.in-view > .dashboard-card:nth-child(4),
        .fade-up.in-view > .mb-4:nth-child(4) { animation: fadeUp .4s .20s cubic-bezier(.22,1,.36,1) both; }
        .fade-up.in-view > .row:nth-child(5),
        .fade-up.in-view > .dashboard-card:nth-child(5),
        .fade-up.in-view > .mb-4:nth-child(5) { animation: fadeUp .4s .25s cubic-bezier(.22,1,.36,1) both; }

        /* ============================================================
           SIDEBAR
        ============================================================ */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, #260632 0%, #461256 100%);
            overflow-y: auto;
            overflow-x: hidden;
            z-index: 1030;
            transition: transform var(--transition), box-shadow var(--transition);
            border-right: 1px solid var(--border);
        }

        .sidebar::-webkit-scrollbar { width: 4px; }
        .sidebar::-webkit-scrollbar-thumb { background: #68117e; border-radius: 4px; }
        .sidebar::-webkit-scrollbar-track { background: transparent; }

        /* Brand */
        .sidebar-brand {
            height: 72px;
            display: flex;
            align-items: center;
            padding: 0 20px;
            border-bottom: 1px solid var(--border);
            text-decoration: none;
        }
        .brand-logo {
            width: 42px; height: 42px;
            border-radius: 12px;
            background: linear-gradient(135deg, #c84ddf, #68117e);
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; color: white;
            margin-right: 12px;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(200,77,223,.4);
        }
        .brand-title { color: white; font-size: 17px; font-weight: 700; line-height: 1.1; }
        .brand-sub { color: #ab8db2; font-size: 11px; margin-top: 2px; }

        .sidebar-toggle {
            width: 30px; height: 30px;
            border: none;
            background: rgba(255,255,255,.08);
            border-radius: 8px;
            color: #94a3b8;
            display: none;
            align-items: center; justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            flex-shrink: 0;
            font-size: 14px;
        }
        .sidebar-toggle:hover { background: rgba(255,255,255,.16); color: white; }
        @media (max-width: 992px) { .sidebar-toggle { display: flex; } }

        /* User */
        .sidebar-user {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .sidebar-avatar {
            width: 44px; height: 44px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(255,255,255,.12);
            flex-shrink: 0;
        }
        .user-name { color: #f1f5f9; font-size: 14px; font-weight: 600; }
        .user-role {
            font-size: 10.5px;
            padding: 3px 10px;
            border-radius: 20px;
            background: rgba(200,77,223,.25);
            color: #e8b4f5;
            display: inline-block;
            margin-top: 3px;
            font-weight: 500;
        }

        /* Nav */
        .sidebar-nav { padding: 12px 10px 100px; }

        .nav-header {
            color: #ab8db2;
            font-size: 10px;
            font-weight: 700;
            margin: 18px 12px 8px;
            text-transform: uppercase;
            letter-spacing: 1.2px;
        }

        .nav-item { margin-bottom: 2px; }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #94a3b8;
            text-decoration: none;
            padding: 11px 14px;
            border-radius: 12px;
            transition: all var(--transition);
            font-size: 13.5px;
            font-weight: 500;
            position: relative;
        }
        .nav-link i { font-size: 17px; flex-shrink: 0; }
        .nav-link:hover {
            background: rgba(255,255,255,.06);
            color: #e2e8f0;
            transform: translateX(3px);
        }
        .nav-link.active {
            background: linear-gradient(90deg, #68117e, #c84ddf);
            color: white;
            box-shadow: 0 6px 20px rgba(200,77,223,.35);
        }
        .nav-link.active i { color: white; }

        .menu-badge {
            margin-left: auto;
            background: #ef4444;
            color: white;
            padding: 2px 7px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 600;
        }

        /* ============================================================
           SIDEBAR OVERLAY (mobile)
        ============================================================ */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.55);
            z-index: 1029;
            backdrop-filter: blur(8px) saturate(120%);
            -webkit-backdrop-filter: blur(8px) saturate(120%);
            animation: fadeOverlay .22s ease both;
        }
        .sidebar-overlay.show { display: block; }
        @keyframes fadeOverlay { from{opacity:0;backdrop-filter:blur(0);} to{opacity:1;backdrop-filter:blur(8px);} }

        /* ============================================================
           MAIN CONTENT
        ============================================================ */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: margin var(--transition);
        }

        /* ============================================================
           TOPBAR
        ============================================================ */
        .topbar {
            height: 70px;
            background: var(--topbar-bg);
            border-bottom: 1px solid var(--card-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            position: sticky;
            top: 0;
            z-index: 100;
            transition: background var(--transition), border-color var(--transition);
        }

        .topbar-left { display: flex; align-items: center; gap: 12px; }
        .topbar-left h4 { margin: 0; font-size: clamp(16px, 2vw, 20px); font-weight: 700; color: var(--text-primary); }
        .topbar-left p { margin: 0; color: var(--text-muted); font-size: clamp(10px, 1.2vw, 12px); }

        .topbar-right { display: flex; align-items: center; gap: 10px; }

        .top-btn {
            width: 40px; height: 40px;
            border-radius: 11px;
            background: var(--input-bg);
            border: 1px solid var(--card-border);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            position: relative;
            transition: var(--transition);
            color: var(--text-muted);
            font-size: 16px;
        }
        .top-btn:hover { background: var(--card-border); color: var(--text-primary); transform: translateY(-1px); }

        .notif-badge {
            position: absolute; top: -4px; right: -4px;
            width: 16px; height: 16px;
            border-radius: 50%;
            background: #ef4444;
            color: white;
            font-size: 9px;
            font-weight: 700;
            display: flex; align-items: center; justify-content: center;
            border: 2px solid var(--topbar-bg);
        }

        /* ============================================================
           CONTENT WRAPPER
        ============================================================ */
        .content-wrapper { padding: 24px; }

        /* ============================================================
           CARDS & STATS
        ============================================================ */
        .dashboard-card {
            background: var(--card-bg);
            border-radius: var(--radius-card);
            padding: 24px;
            border: 1px solid var(--card-border);
            box-shadow: var(--shadow-sm);
            transition: transform var(--transition), box-shadow var(--transition), background var(--transition);
        }
        .dashboard-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-md); }

        .stat-card {
            background: var(--card-bg);
            border-radius: var(--radius-card);
            padding: 22px;
            border: 1px solid var(--card-border);
            box-shadow: var(--shadow-sm);
            transition: transform var(--transition), box-shadow var(--transition), background var(--transition);
            position: relative;
            overflow: hidden;
        }
        .stat-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-lg); }

        .stat-icon {
            width: 52px; height: 52px;
            border-radius: 15px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px;
        }
        .stat-title { color: var(--text-muted); font-size: clamp(11px, 1.1vw, 13px); font-weight: 500; margin-bottom: 6px; }
        .stat-label { color: var(--text-muted); font-size: clamp(11px, 1.1vw, 13px); font-weight: 500; margin-top: 6px; }
        .stat-value { font-size: clamp(22px, 2.5vw, 28px); font-weight: 800; color: var(--text-primary); line-height: 1.1; }
        .stat-growth { font-size: 12px; margin-top: 8px; display: flex; align-items: center; gap: 4px; }

        .bg-primary-soft   { background: linear-gradient(135deg, #c84ddf, #68117e); }
        .bg-success-soft   { background: linear-gradient(135deg, #10b981, #059669); }
        .bg-warning-soft   { background: linear-gradient(135deg, #f6af23, #e09000); }
        .bg-danger-soft    { background: linear-gradient(135deg, #ef4444, #dc2626); }
        .bg-info-soft      { background: linear-gradient(135deg, #0284c7, #38bdf8); }
        .bg-purple-soft    { background: linear-gradient(135deg, #c84ddf, #461256); }

        /* ============================================================
           TABLE
        ============================================================ */
        .table-modern { background: var(--card-bg); border-radius: var(--radius-card); overflow: hidden; }
        .table-modern thead { background: var(--input-bg); }
        .table-modern th {
            border: none;
            padding: 14px 16px;
            font-size: 11.5px;
            color: var(--text-muted);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
        }
        .table-modern td { padding: 14px 16px; vertical-align: middle; color: var(--text-primary); }
        .table-modern tbody tr { border-color: var(--card-border); transition: background var(--transition); }
        .table-modern tbody tr:hover { background: var(--input-bg); }

        /* ============================================================
           BUTTONS
        ============================================================ */
        .btn {
            border-radius: var(--radius-btn);
            font-weight: 600;
            font-size: 13.5px;
            transition: all var(--transition);
        }
        .btn:hover { transform: translateY(-1px); }
        .btn:active { transform: translateY(0); }
        .btn-primary {
            background: linear-gradient(135deg, #68117e, #c84ddf);
            border: none;
            box-shadow: 0 4px 14px rgba(200,77,223,.3);
        }
        .btn-primary:hover { box-shadow: 0 6px 20px rgba(200,77,223,.45); background: linear-gradient(135deg, #461256, #68117e); }
        .btn-success {
            background: linear-gradient(135deg, #059669, #10b981);
            border: none;
            box-shadow: 0 4px 14px rgba(16,185,129,.3);
        }
        .btn-success:hover { box-shadow: 0 6px 20px rgba(16,185,129,.4); }

        /* ============================================================
           FORM CONTROLS
        ============================================================ */
        .form-control, .form-select {
            background: var(--input-bg);
            border: 1.5px solid var(--card-border);
            border-radius: 10px;
            color: var(--text-primary);
            transition: border-color var(--transition), box-shadow var(--transition);
            font-size: 13.5px;
        }
        .form-control:focus, .form-select:focus {
            background: var(--card-bg);
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(200,77,223,.15);
            color: var(--text-primary);
            outline: none;
        }

        /* ============================================================
           BADGES
        ============================================================ */
        .badge { font-weight: 600; border-radius: 8px; }

        /* ============================================================
           MODAL
        ============================================================ */
        .modal-content {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 20px;
        }

        /* Ensure Bootstrap modals/backdrops are above any global overlays */
        .modal {
            z-index: 2100 !important;
        }
        .modal-backdrop {
            z-index: 2090 !important;
        }
        /* pageLoader should never block pointer events over modals */
        #pageLoader {
            pointer-events: none !important;
        }

        /* ============================================================
           SCROLLBAR (webkit)
        ============================================================ */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-thumb { background: rgba(200,77,223,.35); border-radius: 6px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(200,77,223,.6); }
        ::-webkit-scrollbar-track { background: transparent; }

        /* ============================================================
           PAGE HEADER
        ============================================================ */
        .page-header {
            margin-bottom: 20px;
        }
        .page-header h5 {
            font-size: 17px;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
        }
        .page-header p {
            font-size: 13px;
            color: var(--text-muted);
            margin: 4px 0 0;
        }

        /* ============================================================
           MOBILE RESPONSIVE
        ============================================================ */
        @media (max-width: 992px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); box-shadow: 4px 0 30px rgba(0,0,0,0.3); }
            .main-content { margin-left: 0; }
            .content-wrapper { padding: 16px; }
            .topbar { padding: 0 16px; }
        }

        @media (max-width: 576px) {
            .stat-value { font-size: 22px; }
            .topbar-left h4 { font-size: 17px; }
        }

        /* ============================================================
           MOBILE TABLE — compact + row-expand UX
        ============================================================ */
        @media (max-width: 767px) {
            .table-responsive .table td,
            .table-responsive .table th {
                font-size: 12.5px;
                padding: 10px 8px;
            }
            .table-responsive .table td:first-child,
            .table-responsive .table th:first-child { padding-left: 14px; }
            .table-responsive .table td:last-child,
            .table-responsive .table th:last-child { padding-right: 10px; }

            /* Expand toggle button */
            .mobile-expand-btn {
                display: inline-flex !important;
                align-items: center;
                justify-content: center;
                width: 26px; height: 26px;
                border-radius: 7px;
                background: var(--input-bg);
                border: 1px solid var(--card-border);
                font-size: 11px;
                cursor: pointer;
                transition: all .2s;
                color: var(--text-muted);
                vertical-align: middle;
                margin-left: 4px;
                flex-shrink: 0;
            }
            .mobile-expand-btn.open {
                background: var(--primary);
                color: white;
                border-color: var(--primary);
            }

            /* Expand detail row */
            .expand-detail-row td {
                background: var(--input-bg) !important;
                border-top: none !important;
                padding: 10px 14px !important;
                font-size: 12.5px;
            }
            .expand-detail-grid {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 10px 14px;
            }
            .expand-detail-item .expand-label {
                font-size: 10px;
                font-weight: 700;
                color: var(--text-muted);
                text-transform: uppercase;
                letter-spacing: .06em;
                margin-bottom: 3px;
            }
        }
        @media (min-width: 768px) {
            .mobile-expand-btn { display: none !important; }
        }

        /* ============================================================
           DARK MODE OVERRIDES
        ============================================================ */
        [data-theme="dark"] .table { color: var(--text-primary); }
        [data-theme="dark"] .table-light { background: rgba(200,77,223,.04) !important; }
        [data-theme="dark"] .modal-header { border-color: var(--card-border); }
        [data-theme="dark"] .modal-footer { border-color: var(--card-border); }
        [data-theme="dark"] .dropdown-menu {
            background: #2d0a3e;
            border-color: rgba(200,77,223,.15);
        }
        [data-theme="dark"] .dropdown-item { color: #f0e8f5; }
        [data-theme="dark"] .dropdown-item:hover { background: rgba(200,77,223,.1); }
        [data-theme="dark"] .dropdown-divider { border-color: rgba(200,77,223,.15); }
        [data-theme="dark"] .btn-light { background: #461256; border-color: #68117e; color: #f0e8f5; }
        [data-theme="dark"] .form-control:focus, [data-theme="dark"] .form-select:focus {
            background: #2d0a3e;
        }
        [data-theme="dark"] .form-control::placeholder,
        [data-theme="dark"] .form-select::placeholder { color: #4b5563; }
        [data-theme="dark"] input[type="date"]::-webkit-calendar-picker-indicator { filter: invert(.7); }
        [data-theme="dark"] input[type="time"]::-webkit-calendar-picker-indicator { filter: invert(.7); }
        [data-theme="dark"] .table-responsive { color: var(--text-primary); }
        [data-theme="dark"] code { background: rgba(255,255,255,.08); color: #7dd3fc; }

        /* ============================================================
           EMPTY STATE
        ============================================================ */
        .empty-state {
            padding: 3rem;
            text-align: center;
        }
        .empty-state i { font-size: 3.5rem; color: #cbd5e1; }
        .empty-state p { color: var(--text-muted); margin-top: .75rem; font-size: 14px; }

        /* ============================================================
           SCROLL TO TOP
        ============================================================ */
        #scrollTop {
            position: fixed;
            bottom: 28px; right: 24px;
            width: 42px; height: 42px;
            border-radius: 12px;
            background: linear-gradient(135deg, #68117e, #c84ddf);
            color: white;
            border: none;
            box-shadow: 0 4px 16px rgba(200,77,223,.35);
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            font-size: 17px;
            opacity: 0;
            transform: translateY(10px) scale(.9);
            transition: opacity .25s, transform .25s;
            pointer-events: none;
            z-index: 998;
        }
        #scrollTop.visible { opacity: 1; transform: translateY(0) scale(1); pointer-events: all; }
        #scrollTop:hover { transform: translateY(-2px) scale(1.05); box-shadow: 0 6px 20px rgba(200,77,223,.5); }
        @media (max-width: 992px) { #scrollTop { bottom: 80px; } }

        /* ============================================================
           MOBILE BOTTOM NAV
        ============================================================ */
        .mobile-bottom-nav {
            display: none;
            position: fixed;
            bottom: 0; left: 0; right: 0;
            height: 62px;
            background: var(--card-bg);
            border-top: 1px solid var(--card-border);
            z-index: 1048;
            align-items: center;
            justify-content: space-around;
            padding: 0 4px;
            box-shadow: 0 -4px 24px rgba(0,0,0,.08);
        }
        @media (max-width: 992px) {
            .mobile-bottom-nav { display: flex; }
            .content-wrapper { padding-bottom: 78px !important; }
        }
        .mob-nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 3px;
            flex: 1;
            padding: 8px 4px;
            cursor: pointer;
            border-radius: 10px;
            text-decoration: none;
            color: var(--text-muted);
            transition: color var(--transition), background var(--transition);
            font-size: 11px;
            font-weight: 600;
            min-width: 44px;
            min-height: 44px;
        }
        .mob-nav-item i { font-size: 20px; transition: transform .2s; }
        .mob-nav-item:hover, .mob-nav-item.active { color: #c84ddf; }
        .mob-nav-item.active i { transform: scale(1.15); }

        /* ============================================================
           GLOBAL FLASH TOAST
        ============================================================ */
        #globalToastWrap {
            position: fixed;
            top: 80px; right: 20px;
            z-index: 9990;
            display: flex; flex-direction: column; gap: 10px;
            pointer-events: none;
        }
        .g-toast {
            min-width: 260px; max-width: 360px;
            padding: 14px 16px;
            border-radius: 14px;
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            box-shadow: 0 8px 28px rgba(0,0,0,.12);
            display: flex; align-items: flex-start; gap: 12px;
            pointer-events: all;
            opacity: 0; transform: translateX(20px);
            animation: toastIn .3s cubic-bezier(.34,1.56,.64,1) forwards;
        }
        .g-toast.hide { animation: toastOut .25s ease forwards; }
        @keyframes toastIn  { to { opacity:1; transform:translateX(0); } }
        @keyframes toastOut { to { opacity:0; transform:translateX(20px); } }

        /* ============================================================
           TOAST
        ============================================================ */
        .toast-container { z-index: 9999; }

        /* ============================================================
           LOADING SPINNER
        ============================================================ */
        #pageLoader {
            position: fixed;
            inset: 0;
            background: rgba(255,255,255,.8);
            /* keep loader below modal/backdrop so modals remain interactive */
            z-index: 1040;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            pointer-events: none;
            transition: opacity .2s;
        }
        #pageLoader.show { opacity: 1; pointer-events: all; }
        [data-theme="dark"] #pageLoader { background: rgba(15,23,42,.85); }

        /* ============================================================
           NAV PROGRESS BAR (top of page, NProgress-style)
        ============================================================ */
        #navProgress {
            position: fixed;
            top: 0; left: 0;
            height: 3px;
            width: 0%;
            background: linear-gradient(90deg, #c84ddf, #f6af23, #c84ddf);
            background-size: 200% 100%;
            z-index: 99999;
            transition: width .3s ease, opacity .4s ease;
            opacity: 0;
            animation: shimmerBar 1.5s linear infinite;
        }
        #navProgress.active { opacity: 1; }
        @keyframes shimmerBar {
            0%   { background-position: 100% 0; }
            100% { background-position: -100% 0; }
        }

        /* ============================================================
           SKELETON SHIMMER
        ============================================================ */
        @keyframes skeletonShimmer {
            0%   { background-position: -200% 0; }
            100% { background-position:  200% 0; }
        }
        .skeleton {
            background: linear-gradient(90deg,
                var(--card-border) 25%,
                var(--input-bg) 50%,
                var(--card-border) 75%);
            background-size: 200% 100%;
            animation: skeletonShimmer 1.4s ease-in-out infinite;
            border-radius: 6px;
        }

        /* ============================================================
           STAT CARD — accent glow on hover
        ============================================================ */
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, #68117e, #c84ddf, #f6af23);
            border-radius: var(--radius-card) var(--radius-card) 0 0;
            opacity: 0;
            transition: opacity var(--transition);
        }
        .stat-card:hover::before { opacity: 1; }
        .stat-card::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: var(--radius-card);
            box-shadow: 0 0 0 0 rgba(200,77,223,0);
            transition: box-shadow .35s ease;
            pointer-events: none;
        }
        .stat-card:hover::after { box-shadow: 0 0 0 2px rgba(200,77,223,.12); }

        /* ============================================================
           RIPPLE EFFECT ON BUTTONS
        ============================================================ */
        .btn { position: relative; overflow: hidden; }
        .btn .ripple-wave {
            position: absolute;
            border-radius: 50%;
            transform: scale(0);
            animation: rippleAnim .55s linear;
            background: rgba(255,255,255,.35);
            pointer-events: none;
        }
        @keyframes rippleAnim {
            to { transform: scale(4); opacity: 0; }
        }

        /* ============================================================
           CARD HOVER — micro-lift with glow
        ============================================================ */
        .dashboard-card {
            position: relative;
        }
        .dashboard-card::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: var(--radius-card);
            box-shadow: 0 0 0 0 rgba(200,77,223,0);
            transition: box-shadow .35s ease;
            pointer-events: none;
        }
        .dashboard-card:hover::after { box-shadow: 0 0 0 1.5px rgba(200,77,223,.1); }

        /* ============================================================
           STAGGER FADE-UP (children auto-stagger)
        ============================================================ */
        .stagger-children > * { opacity: 0; transform: translateY(14px); }
        .stagger-children.in-view > *:nth-child(1) { animation: fadeSlideUp .4s .00s ease both; }
        .stagger-children.in-view > *:nth-child(2) { animation: fadeSlideUp .4s .06s ease both; }
        .stagger-children.in-view > *:nth-child(3) { animation: fadeSlideUp .4s .12s ease both; }
        .stagger-children.in-view > *:nth-child(4) { animation: fadeSlideUp .4s .18s ease both; }
        .stagger-children.in-view > *:nth-child(5) { animation: fadeSlideUp .4s .24s ease both; }
        .stagger-children.in-view > *:nth-child(6) { animation: fadeSlideUp .4s .30s ease both; }
        @keyframes fadeSlideUp {
            from { opacity:0; transform:translateY(14px); }
            to   { opacity:1; transform:translateY(0); }
        }

        /* ============================================================
           SIDEBAR NAV LINK — active indicator stripe
        ============================================================ */
        .nav-link { position: relative; }
        .nav-link.active::before {
            content: '';
            position: absolute;
            left: -10px; top: 50%; transform: translateY(-50%);
            width: 4px; height: 60%;
            background: #f6af23;
            border-radius: 0 4px 4px 0;
        }

        /* ============================================================
           NAV-ITEM hover — slide-in bg
        ============================================================ */
        .nav-link:not(.active) { overflow: hidden; }
        .nav-link:not(.active)::after {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(255,255,255,.04);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform .22s ease;
            border-radius: 10px;
        }
        .nav-link:not(.active):hover::after { transform: scaleX(1); }

        /* ============================================================
           BREADCRUMB IN TOPBAR
        ============================================================ */
        .topbar-breadcrumb {
            font-size: 12px;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .topbar-breadcrumb a { color: var(--text-muted); text-decoration: none; }
        .topbar-breadcrumb a:hover { color: var(--primary); }

        /* ============================================================
           SIDEBAR BRAND GLOW PULSE
        ============================================================ */
        .brand-logo {
            animation: brandPulse 4s ease-in-out infinite;
        }
        @keyframes brandPulse {
            0%, 100% { box-shadow: 0 4px 12px rgba(200,77,223,.4); }
            50%       { box-shadow: 0 4px 22px rgba(200,77,223,.7); }
        }

        /* ============================================================
           CARD HOVER — lift shadow upgrade
        ============================================================ */
        .dashboard-card { cursor: default; }
        a .dashboard-card, a.dashboard-card { cursor: pointer; }

        /* ============================================================
           PRINT
        ============================================================ */
        @media print {
            .sidebar, .topbar, .sidebar-overlay, #pageLoader, #navProgress,
            .mobile-bottom-nav, #scrollTop, #globalToastWrap,
            .btn, .dropdown, form[action*="logout"] { display: none !important; }
            .main-content { margin-left: 0 !important; }
            .content-wrapper { padding: 0 !important; }
            .stat-card, .dashboard-card { break-inside: avoid; box-shadow: none !important; border: 1px solid #ddd !important; }
            body { font-size: 12px; }
        }

        /* ============================================================
           DARK MODE — BOOTSTRAP CARD COMPONENT
        ============================================================ */
        [data-theme="dark"] .card {
            background: var(--card-bg) !important;
            border-color: var(--card-border) !important;
            color: var(--text-primary);
        }

        /* ============================================================
           DARK MODE — INPUT GROUP TEXT
        ============================================================ */
        [data-theme="dark"] .input-group-text {
            background: var(--input-bg);
            border-color: var(--card-border);
            color: var(--text-muted);
        }

        /* ============================================================
           DARK MODE — ALERTS
        ============================================================ */
        [data-theme="dark"] .alert-success {
            background: rgba(16,185,129,.1) !important;
            color: #4ade80 !important;
            border-color: rgba(16,185,129,.2) !important;
        }
        [data-theme="dark"] .alert-danger {
            background: rgba(239,68,68,.1) !important;
            color: #f87171 !important;
            border-color: rgba(239,68,68,.2) !important;
        }
        [data-theme="dark"] .alert-warning {
            background: rgba(246,175,35,.1) !important;
            color: #fbbf24 !important;
            border-color: rgba(246,175,35,.2) !important;
        }
        [data-theme="dark"] .alert-info {
            background: rgba(200,77,223,.1) !important;
            color: #e8b4f5 !important;
            border-color: rgba(200,77,223,.2) !important;
        }

        /* ============================================================
           DARK MODE — OUTLINE BUTTONS
        ============================================================ */
        [data-theme="dark"] .btn-outline-secondary {
            border-color: var(--card-border);
            color: var(--text-muted);
        }
        [data-theme="dark"] .btn-outline-secondary:hover {
            background: var(--input-bg);
            color: var(--text-primary);
            border-color: var(--card-border);
        }

        /* ============================================================
           DARK MODE — TABLE HOVER ROWS
        ============================================================ */
        [data-theme="dark"] .table-hover tbody tr:hover td {
            background: rgba(200,77,223,.05);
            color: var(--text-primary);
        }
        [data-theme="dark"] thead tr { background: rgba(200,77,223,.05) !important; }
        [data-theme="dark"] .table thead th,
        [data-theme="dark"] .table-modern th { color: var(--text-muted); }

        /* ============================================================
           DARK MODE — PAGINATION
        ============================================================ */
        [data-theme="dark"] .page-link {
            background: var(--card-bg);
            border-color: var(--card-border);
            color: var(--text-primary);
        }
        [data-theme="dark"] .page-link:hover {
            background: var(--input-bg);
            color: var(--primary);
            border-color: var(--card-border);
        }
        [data-theme="dark"] .page-item.active .page-link {
            background: var(--primary);
            border-color: var(--primary);
            color: white;
        }
        [data-theme="dark"] .page-item.disabled .page-link {
            background: var(--input-bg);
            border-color: var(--card-border);
            color: var(--text-muted);
        }

        /* ============================================================
           PAGINATION / ICON FIXES
           Normalize pagination/button icon sizes and spacing so
           large icons (injected by extensions or fonts) don't
           overflow the layout.
        ============================================================ */
        .pagination { display: flex; gap: .25rem; align-items: center; }
        .pagination .page-link { padding: .3125rem .6rem; min-width: 2.2rem; height: 2.2rem; display: inline-flex; align-items: center; justify-content: center; }
        .pagination .page-link, .pagination .page-item .page-link { font-size: .9rem; }
            .page-link .bi, .page-link i { font-size: 1rem; line-height: 1; vertical-align: middle; }
            .page-link svg, .page-link svg * { width: 1rem; height: 1rem; }
        .btn-sm .bi, #paginationLinks .bi { font-size: .95rem !important; line-height: 1 !important; }
            /* Strong rule to protect pagination icons from extension font replacement */
            #paginationLinks svg, .pagination .page-link svg { width: 1rem !important; height: 1rem !important; max-width:1rem !important; max-height:1rem !important; }

            /* If icons keep getting injected by extensions, hide them entirely in pagination areas */
            .pagination .bi, #paginationLinks .bi, .pagination svg, #paginationLinks svg, .pagination img, #paginationLinks img { display: none !important; }

            /* Target common Heroicon path used in injected chevrons and hide it */
            .pagination svg path[d*="M12.707 5.293"], #paginationLinks svg path[d*="M12.707 5.293"] { display: none !important; }
            /* Also hide svg elements using Tailwind-like size classes if present */
            .pagination svg.w-5.h-5, #paginationLinks svg.w-5.h-5 { display: none !important; }

            /* Also neutralize pseudo-element content inside pagination */
            .pagination::before, .pagination::after,
            .pagination *::before, .pagination *::after,
            #paginationLinks::before, #paginationLinks::after,
            #paginationLinks *::before, #paginationLinks *::after {
                content: none !important; display: none !important; width:0; height:0; overflow:hidden; visibility:hidden;
            }
        #paginationLinks .btn { display: inline-flex; align-items: center; justify-content: center; }
        /* ensure prev/next custom buttons don't expand vertically */
        #paginationLinks .btn { height: auto; padding-top: .25rem; padding-bottom: .25rem; }

        /* ============================================================
           DARK MODE — SELECT OPTIONS
        ============================================================ */
        [data-theme="dark"] option { background: #2d0a3e; color: #f0e8f5; }

        /* ============================================================
           DARK MODE — MOBILE BOTTOM NAV
        ============================================================ */
        [data-theme="dark"] .mobile-bottom-nav {
            background: #2d0a3e;
            border-top-color: rgba(200,77,223,.15);
            box-shadow: 0 -8px 32px rgba(0,0,0,.4);
        }

        /* ============================================================
           DARK MODE — TOPBAR DEPTH
        ============================================================ */
        [data-theme="dark"] .topbar {
            box-shadow: 0 1px 0 rgba(200,77,223,.1), 0 4px 16px rgba(0,0,0,.2);
        }

        /* ============================================================
           DARK MODE — PROGRESS BAR TRACK
        ============================================================ */
        [data-theme="dark"] .progress { background: rgba(255,255,255,.08); }

        /* ============================================================
           GRADIENT HERO BANNERS — suppress card hover lift
           (banner cards have border:none in their inline style)
        ============================================================ */
        .dashboard-card[style*="border:none"]:hover {
            transform: none;
            box-shadow: var(--shadow-sm);
        }
        .dashboard-card[style*="border:none"]::after { display: none; }

        /* ============================================================
           INPUT GROUP — focus-within border highlight
        ============================================================ */
        .input-group-text {
            background: var(--input-bg);
            border-color: var(--card-border);
            color: var(--text-muted);
            transition: border-color var(--transition);
        }
        .input-group:focus-within .input-group-text {
            border-color: var(--primary);
        }
        .input-group:focus-within .form-control {
            border-color: var(--primary);
        }

        /* ============================================================
           FOCUS RINGS — accessibility
        ============================================================ */
        :focus-visible {
            outline: 2px solid rgba(200,77,223,.6);
            outline-offset: 2px;
        }
        .btn:focus-visible { outline-offset: 3px; }
        .nav-link:focus-visible { outline-color: rgba(255,255,255,.4); }

        /* ============================================================
           QUICK DASH ACTION CARDS
        ============================================================ */
        .quick-dash {
            transition: transform var(--transition), box-shadow var(--transition);
            border-radius: 12px;
        }
        .quick-dash:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0,0,0,.1) !important;
        }

        /* ============================================================
           TABLE — consistent header text
        ============================================================ */
        .table thead th { white-space: nowrap; color: var(--text-muted); font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; }

        /* ============================================================
           MOBILE ≤ 480PX — extra small phone polish
        ============================================================ */
        @media (max-width: 480px) {
            .content-wrapper { padding: 12px !important; }
            .dashboard-card { padding: 16px 14px; border-radius: 16px; }
            .stat-card { padding: 14px; border-radius: 16px; }
            .stat-value { font-size: 20px !important; }
            .stat-icon { width: 42px !important; height: 42px !important; font-size: 17px !important; }
            .topbar { height: 58px; padding: 0 12px; }
            .topbar-left h4 { font-size: 16px; }
            .topbar-right { gap: 6px; }
            .top-btn { width: 36px; height: 36px; font-size: 14px; }
            .modal-dialog { margin: 8px; }
            .modal-content { border-radius: 16px; }
            #globalToastWrap { right: 10px; left: 10px; max-width: 100%; }
            .g-toast { max-width: 100%; min-width: 0; }
        }

        /* ============================================================
           TOPBAR — depth shadow when page is scrolled
        ============================================================ */
        .topbar { transition: box-shadow .25s ease, background var(--transition); }
        .topbar.scrolled {
            box-shadow: 0 4px 24px rgba(38,6,50,.1);
        }
        [data-theme="dark"] .topbar.scrolled {
            box-shadow: 0 4px 24px rgba(0,0,0,.35);
        }

        /* ============================================================
           MOBILE TOAST — move to bottom on small screens
        ============================================================ */
        @media (max-width: 576px) {
            #globalToastWrap {
                top: auto;
                bottom: 74px;
                right: 10px;
                left: 10px;
                flex-direction: column-reverse;
            }
        }

        /* ============================================================
           SKELETON ROWS — table loading state
        ============================================================ */
        .skeleton-row td {
            padding: 12px 16px !important;
        }
        .skeleton-cell {
            height: 14px;
            border-radius: 6px;
            background: linear-gradient(90deg,
                var(--card-border) 25%,
                var(--input-bg) 50%,
                var(--card-border) 75%);
            background-size: 200% 100%;
            animation: skeletonShimmer 1.4s ease-in-out infinite;
        }

        /* ============================================================
           TABLE-MODERN THEAD — unified gradient header
        ============================================================ */
        .table-modern thead tr,
        .thead-modern {
            background: linear-gradient(90deg, rgba(38,6,50,.04), rgba(200,77,223,.04)) !important;
        }
        .table-modern th {
            border-bottom: 2px solid var(--card-border) !important;
        }

        /* ============================================================
           MICRO-ANIMATIONS — stat-card hover lift
        ============================================================ */
        .stat-card {
            will-change: transform;
            transition: transform .22s cubic-bezier(.4,0,.2,1), box-shadow .22s cubic-bezier(.4,0,.2,1);
        }
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 28px rgba(200,77,223,.15), 0 2px 8px rgba(38,6,50,.08);
        }
        .dashboard-card.clickable, a.dashboard-card {
            transition: transform .22s cubic-bezier(.4,0,.2,1), box-shadow .22s cubic-bezier(.4,0,.2,1);
            will-change: transform;
        }
        .dashboard-card.clickable:hover, a.dashboard-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 28px rgba(200,77,223,.12);
        }

        /* ============================================================
           TABLE — action button group
        ============================================================ */
        .btn-action-group { display: flex; gap: 6px; }
        .btn-action-group .btn { transition: transform .18s, box-shadow .18s; }
        .btn-action-group .btn:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,.12) !important; }

        /* ============================================================
           QUICK-ACTION GRID
        ============================================================ */
        .quick-action-card {
            border-radius: 16px;
            padding: 18px 16px;
            display: flex;
            align-items: center;
            gap: 14px;
            text-decoration: none;
            transition: transform var(--transition), box-shadow var(--transition);
            background: var(--card-bg);
            border: 1.5px solid var(--card-border);
            cursor: pointer;
        }
        .quick-action-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(200,77,223,.12);
            border-color: rgba(200,77,223,.3);
        }
        .quick-action-icon {
            width: 46px; height: 46px;
            border-radius: 13px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }
        .quick-action-label {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-primary);
            line-height: 1.3;
        }
        .quick-action-desc {
            font-size: 11px;
            color: var(--text-muted);
            margin-top: 2px;
        }

        /* ============================================================
           TABLE RESPONSIVE — fade edge hint on mobile
        ============================================================ */
        .table-scroll-wrap {
            position: relative;
        }
        .table-scroll-wrap::after {
            content: '';
            position: absolute;
            top: 0; right: 0; bottom: 0;
            width: 32px;
            background: linear-gradient(to left, var(--card-bg), transparent);
            pointer-events: none;
            border-radius: 0 var(--radius-card) var(--radius-card) 0;
            opacity: 0;
            transition: opacity .3s;
        }
        .table-scroll-wrap.can-scroll::after { opacity: 1; }

        /* ============================================================
           KEYBOARD SHORTCUT BADGE
        ============================================================ */
        .kbd {
            display: inline-flex;
            align-items: center;
            gap: 2px;
            background: var(--input-bg);
            border: 1px solid var(--card-border);
            border-radius: 5px;
            padding: 1px 5px;
            font-size: 10px;
            font-weight: 600;
            color: var(--text-muted);
            font-family: monospace;
        }

        /* ============================================================
           MODAL — slide-up entrance
        ============================================================ */
        .modal.fade .modal-dialog {
            transform: translateY(20px) scale(.98);
            transition: transform .3s cubic-bezier(.34,1.56,.64,1), opacity .25s ease;
        }
        .modal.show .modal-dialog {
            transform: translateY(0) scale(1);
        }

        /* ============================================================
           SIDEBAR MINI MODE (desktop-only collapse to icons)
        ============================================================ */
        .sidebar.mini { width: 72px; }
        .sidebar.mini .brand-title,
        .sidebar.mini .brand-sub,
        .sidebar.mini .user-name,
        .sidebar.mini .user-role,
        .sidebar.mini .sidebar-nav span,
        .sidebar.mini .nav-header,
        .sidebar.mini .menu-badge { display: none !important; }
        .sidebar.mini .sidebar-brand { justify-content: center; padding: 0; }
        .sidebar.mini .brand-logo { margin-right: 0; }
        .sidebar.mini .sidebar-user { justify-content: center; padding: 12px; }
        .sidebar.mini .sidebar-avatar { border: 2px solid rgba(255,255,255,.2); }
        .sidebar.mini .sidebar-nav { padding: 8px 8px 100px; }
        .sidebar.mini .nav-link { justify-content: center; padding: 12px; border-radius: 14px; gap: 0; overflow: visible; }
        .sidebar.mini .nav-link i { font-size: 20px; }
        .sidebar.mini .nav-link.active::before { left: -8px; }
        /* Tooltip on mini hover */
        .sidebar.mini .nav-link[data-label]:hover::after {
            content: attr(data-label);
            position: absolute;
            left: calc(100% + 14px);
            top: 50%; transform: translateY(-50%);
            background: #260632;
            color: white;
            padding: 5px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
            pointer-events: none;
            box-shadow: 0 4px 16px rgba(0,0,0,.25);
            z-index: 2000;
            border: 1px solid rgba(200,77,223,.25);
        }
        .main-content.mini { margin-left: 72px; }
        @media (max-width: 992px) {
            .sidebar.mini { transform: translateX(-100%); width: var(--sidebar-width); }
            .sidebar.mini.show { transform: translateX(0); }
            .main-content.mini { margin-left: 0; }
        }

        /* Mini toggle button */
        .mini-toggle {
            width: 26px; height: 26px;
            border: none;
            background: rgba(255,255,255,.08);
            border-radius: 7px;
            color: #94a3b8;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            flex-shrink: 0;
            font-size: 13px;
            margin-left: auto;
        }
        .mini-toggle:hover { background: rgba(255,255,255,.18); color: white; }
        @media (max-width: 992px) { .mini-toggle { display: none !important; } }
        .sidebar.mini .mini-toggle { margin: 0; }

        /* ============================================================
           COMMAND PALETTE
        ============================================================ */
        #cmdOverlay {
            position: fixed; inset: 0;
            background: rgba(0,0,0,.55);
            backdrop-filter: blur(6px);
            z-index: 9900;
            display: none;
            align-items: flex-start;
            justify-content: center;
            padding-top: clamp(60px, 14vh, 140px);
            animation: fadeOverlay .15s ease both;
        }
        #cmdOverlay.open { display: flex; }
        #cmdBox {
            width: min(600px, calc(100vw - 32px));
            background: var(--card-bg);
            border: 1.5px solid rgba(200,77,223,.25);
            border-radius: 20px;
            box-shadow: 0 24px 64px rgba(0,0,0,.35), 0 0 0 1px rgba(200,77,223,.08);
            overflow: hidden;
            animation: cmdSlideIn .22s cubic-bezier(.34,1.56,.64,1) both;
        }
        @keyframes cmdSlideIn {
            from { opacity:0; transform:translateY(-16px) scale(.97); }
            to   { opacity:1; transform:translateY(0) scale(1); }
        }
        #cmdInputWrap {
            display: flex; align-items: center; gap: 12px;
            padding: 16px 20px;
            border-bottom: 1px solid var(--card-border);
        }
        #cmdInputWrap i { font-size: 18px; color: var(--text-muted); flex-shrink: 0; }
        #cmdInput {
            flex: 1; border: none; outline: none;
            background: transparent;
            font-size: 15px; font-weight: 500;
            color: var(--text-primary);
            font-family: var(--font-sans);
        }
        #cmdInput::placeholder { color: var(--text-muted); }
        .cmd-shortcut {
            font-size: 11px; color: var(--text-muted);
            background: var(--input-bg);
            border: 1px solid var(--card-border);
            border-radius: 5px;
            padding: 2px 7px;
            font-family: monospace;
            flex-shrink: 0;
        }
        #cmdResults {
            max-height: 360px;
            overflow-y: auto;
            padding: 8px;
        }
        #cmdResults::-webkit-scrollbar { width: 4px; }
        #cmdResults::-webkit-scrollbar-thumb { background: rgba(200,77,223,.3); border-radius: 4px; }
        .cmd-section {
            font-size: 10.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .1em;
            color: var(--text-muted);
            padding: 8px 12px 4px;
        }
        .cmd-item {
            display: flex; align-items: center; gap: 12px;
            padding: 10px 12px;
            border-radius: 12px;
            cursor: pointer;
            text-decoration: none;
            color: var(--text-primary);
            transition: background .12s;
        }
        .cmd-item:hover, .cmd-item.cmd-active {
            background: rgba(200,77,223,.1);
            color: var(--text-primary);
        }
        .cmd-item.cmd-active { background: rgba(200,77,223,.15); }
        .cmd-icon {
            width: 34px; height: 34px;
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
            background: rgba(200,77,223,.1);
            color: #c84ddf;
        }
        .cmd-label { font-size: 13.5px; font-weight: 600; flex: 1; }
        .cmd-desc { font-size: 11px; color: var(--text-muted); margin-top: 1px; }
        .cmd-arrow { font-size: 12px; color: var(--text-muted); opacity: .5; }
        #cmdEmpty {
            text-align: center; padding: 32px 16px;
            color: var(--text-muted); font-size: 13.5px;
        }
        #cmdEmpty i { font-size: 2rem; display: block; margin-bottom: 8px; opacity: .4; }
        #cmdFooter {
            padding: 10px 16px;
            border-top: 1px solid var(--card-border);
            display: flex; gap: 16px; align-items: center;
            background: var(--input-bg);
        }
        .cmd-hint { display: flex; align-items: center; gap: 5px; font-size: 11px; color: var(--text-muted); }
        .cmd-hint kbd {
            background: var(--card-bg); border: 1px solid var(--card-border);
            border-radius: 4px; padding: 1px 5px; font-size: 10px; font-family: monospace;
        }

        /* ============================================================
           DARK MODE — Bootstrap .card-body full coverage
        ============================================================ */
        [data-theme="dark"] .card-body { color: var(--text-primary); }
        [data-theme="dark"] .card .border-top,
        [data-theme="dark"] .card .border-bottom { border-color: var(--card-border) !important; }
        [data-theme="dark"] .card .text-muted { color: var(--text-muted) !important; }
        [data-theme="dark"] .card { box-shadow: 0 2px 16px rgba(0,0,0,.35) !important; }
        [data-theme="dark"] .table { --bs-table-bg: transparent; --bs-table-hover-bg: rgba(200,77,223,.05); }
        [data-theme="dark"] table thead tr { background: rgba(200,77,223,.05) !important; }
        [data-theme="dark"] .table td, [data-theme="dark"] .table th { border-color: var(--card-border); }
        [data-theme="dark"] .input-group-text { background: var(--input-bg) !important; border-color: var(--card-border) !important; color: var(--text-muted) !important; }
        [data-theme="dark"] .form-control, [data-theme="dark"] .form-select { background-color: var(--input-bg) !important; border-color: var(--card-border) !important; color: var(--text-primary) !important; }
        [data-theme="dark"] .form-control::placeholder { color: var(--text-muted) !important; opacity: .7; }
        [data-theme="dark"] .modal-content { background: var(--card-bg) !important; color: var(--text-primary); }
        [data-theme="dark"] .modal-header { border-color: var(--card-border) !important; }
        [data-theme="dark"] .modal-footer { border-color: var(--card-border) !important; }
        [data-theme="dark"] .dropdown-menu { background: var(--card-bg); border-color: var(--card-border); }
        [data-theme="dark"] .dropdown-item { color: var(--text-primary); }
        [data-theme="dark"] .dropdown-item:hover { background: var(--sidebar-hover); color: white; }
        [data-theme="dark"] .page-link { background: var(--card-bg); border-color: var(--card-border); color: var(--text-primary); }
        [data-theme="dark"] .page-item.active .page-link { background: var(--primary); border-color: var(--primary); }
        [data-theme="dark"] .pagination .page-link:hover { background: var(--input-bg); }

        /* ============================================================
           TOPBAR — breadcrumb separator icon
        ============================================================ */
        .topbar-sub {
            font-size: 11.5px;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 4px;
            margin-top: 1px;
        }
        .topbar-sub a { color: var(--text-muted); text-decoration: none; transition: color .15s; }
        .topbar-sub a:hover { color: var(--primary); }
        .topbar-sub .sep { opacity: .35; font-size: 10px; }

        /* ============================================================
           BUTTON VARIANTS — gradient danger & warning
        ============================================================ */
        .btn-danger {
            background: linear-gradient(135deg, #dc2626, #ef4444);
            border: none;
            box-shadow: 0 4px 14px rgba(239,68,68,.3);
        }
        .btn-danger:hover {
            background: linear-gradient(135deg, #b91c1c, #dc2626);
            box-shadow: 0 6px 20px rgba(239,68,68,.4);
        }
        .btn-warning {
            background: linear-gradient(135deg, #e09000, #f6af23);
            border: none;
            color: white;
            box-shadow: 0 4px 14px rgba(246,175,35,.3);
        }
        .btn-warning:hover {
            background: linear-gradient(135deg, #c47d00, #e09000);
            color: white;
            box-shadow: 0 6px 20px rgba(246,175,35,.4);
        }
        .btn-info {
            background: linear-gradient(135deg, #0284c7, #38bdf8);
            border: none;
            color: white;
            box-shadow: 0 4px 14px rgba(2,132,199,.25);
        }
        .btn-info:hover {
            background: linear-gradient(135deg, #0369a1, #0284c7);
            color: white;
        }

        /* ============================================================
           TEXTAREA — min-height and resize control
        ============================================================ */
        textarea.form-control {
            min-height: 90px;
            resize: vertical;
            line-height: 1.6;
        }

        /* ============================================================
           FORM LABEL — consistent micro-label style
        ============================================================ */
        .form-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 6px;
        }
        .form-label.label-xs {
            font-size: 10.5px;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: var(--text-muted);
        }

        /* ============================================================
           BADGE — status variants
        ============================================================ */
        .badge-success { background: linear-gradient(135deg,#059669,#10b981); color:white; }
        .badge-danger  { background: linear-gradient(135deg,#dc2626,#ef4444); color:white; }
        .badge-warning { background: linear-gradient(135deg,#e09000,#f6af23); color:white; }
        .badge-info    { background: linear-gradient(135deg,#0284c7,#38bdf8); color:white; }
        .badge-purple  { background: linear-gradient(135deg,#68117e,#c84ddf); color:white; }

        /* ============================================================
           TEXT CLAMP UTILITIES
        ============================================================ */
        .text-clamp-1 { overflow:hidden; display:-webkit-box; -webkit-line-clamp:1; -webkit-box-orient:vertical; }
        .text-clamp-2 { overflow:hidden; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; }
        .text-clamp-3 { overflow:hidden; display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; }

        /* ============================================================
           EMPTY STATE — improved icon glow
        ============================================================ */
        .empty-state { padding: 3.5rem 2rem; text-align: center; }
        .empty-state i {
            font-size: 3.5rem;
            background: linear-gradient(135deg, #c84ddf, #68117e);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            display: block;
            margin-bottom: 1rem;
            opacity: .35;
        }
        .empty-state h6 { font-weight: 700; color: var(--text-primary); margin-bottom: .25rem; }
        .empty-state p { color: var(--text-muted); font-size: 13.5px; max-width: 320px; margin: 0 auto; }

        /* ============================================================
           CARD — clickable state (links that wrap a card)
        ============================================================ */
        a.stat-card { text-decoration: none; display: block; }
        a.stat-card:hover { transform: translateY(-4px); }

        /* ============================================================
           TABLE — zebra stripe (light mode only)
        ============================================================ */
        .table-striped > tbody > tr:nth-child(odd) > td,
        .table-striped > tbody > tr:nth-child(odd) > th {
            background-color: rgba(200,77,223,.025);
        }
        [data-theme="dark"] .table-striped > tbody > tr:nth-child(odd) > td,
        [data-theme="dark"] .table-striped > tbody > tr:nth-child(odd) > th {
            background-color: rgba(200,77,223,.04);
        }

        /* ============================================================
           MODAL HEADER — gradient helper class
        ============================================================ */
        .modal-header-gradient {
            background: linear-gradient(135deg, #68117e, #c84ddf);
            color: white;
            border: none;
        }
        .modal-header-gradient .btn-close { filter: invert(1); }

        /* ============================================================
           SELECT — custom dropdown arrow color
        ============================================================ */
        .form-select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23c84ddf' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
        }
        [data-theme="dark"] .form-select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23e8b4f5' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
        }

        /* ============================================================
           OUTLINE BUTTON — primary hover fill
        ============================================================ */
        .btn-outline-primary {
            border-color: var(--primary);
            color: var(--primary);
        }
        .btn-outline-primary:hover {
            background: linear-gradient(135deg,#68117e,#c84ddf);
            border-color: transparent;
            color: white;
        }

        /* ============================================================
           STAT VALUE — responsive font size cap
        ============================================================ */
        .stat-value { min-width: 0; word-break: break-word; }

        /* ============================================================
           IMPERSONATE BANNER — dark mode aware
        ============================================================ */
        [data-theme="dark"] .impersonate-banner {
            background: rgba(254,226,226,.08);
            border-color: rgba(239,68,68,.2);
            color: #fca5a5;
        }

        /* ============================================================
           CARD SECTION HEADER — consistent left accent bar
        ============================================================ */
        .section-title {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 16px;
        }
        .section-title::before {
            content: '';
            display: block;
            width: 4px;
            height: 18px;
            border-radius: 2px;
            background: linear-gradient(135deg, #68117e, #c84ddf);
            flex-shrink: 0;
        }

        /* ============================================================
           PREMIUM ENHANCEMENTS — Typography, Depth & Polish
        ============================================================ */

        /* Stat values use display font with tabular nums */
        .stat-value {
            font-family: var(--font-display);
            font-feature-settings: 'tnum' 1, 'kern' 1;
            letter-spacing: -.04em;
        }

        /* Topbar title uses display font */
        .topbar-left h4 {
            font-family: var(--font-display);
            font-weight: 800;
            letter-spacing: -.03em;
        }

        /* Brand title uses display font */
        .brand-title { font-family: var(--font-display); font-weight: 800; }

        /* User name in sidebar */
        .user-name { font-family: var(--font-display); font-weight: 700; }

        /* fw-bold / fw-semibold headings use display font too */
        .fw-bold, .fw-semibold { font-family: var(--font-display); }

        /* ============================================================
           GLASSMORPHISM TOPBAR — pronounced blur on scroll
        ============================================================ */
        .topbar.scrolled {
            background: rgba(255,255,255,.88) !important;
            backdrop-filter: blur(24px) saturate(180%);
            -webkit-backdrop-filter: blur(24px) saturate(180%);
            box-shadow: 0 8px 32px rgba(38,6,50,.08), 0 1px 0 rgba(200,77,223,.07) !important;
        }
        [data-theme="dark"] .topbar.scrolled {
            background: rgba(45,10,62,.92) !important;
            backdrop-filter: blur(24px) saturate(180%);
            -webkit-backdrop-filter: blur(24px) saturate(180%);
            box-shadow: 0 8px 32px rgba(0,0,0,.4), 0 1px 0 rgba(200,77,223,.15) !important;
        }

        /* ============================================================
           AMBIENT GLOW — subtle purple radial on content bg
        ============================================================ */
        .main-content::before {
            content: '';
            position: fixed;
            top: -15%;
            right: -10%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(200,77,223,.055) 0%, transparent 68%);
            pointer-events: none;
            z-index: 0;
            border-radius: 50%;
        }
        .main-content { position: relative; z-index: 1; }
        .content-wrapper { position: relative; z-index: 1; }

        /* ============================================================
           SIDEBAR — glowing right border accent
        ============================================================ */
        .sidebar::after {
            content: '';
            position: absolute;
            right: 0; top: 10%; bottom: 10%;
            width: 1px;
            background: linear-gradient(180deg, transparent, rgba(200,77,223,.45) 40%, rgba(200,77,223,.45) 60%, transparent);
            pointer-events: none;
        }

        /* ============================================================
           NAV LINK — stronger active glow
        ============================================================ */
        .nav-link.active {
            box-shadow: 0 6px 20px rgba(200,77,223,.4), inset 0 1px 0 rgba(255,255,255,.12) !important;
        }

        /* ============================================================
           SIDEBAR USER SECTION — glass card feel
        ============================================================ */
        .sidebar-user {
            background: rgba(255,255,255,.04);
            margin: 8px 10px;
            border-radius: 14px;
            border: 1px solid rgba(255,255,255,.07) !important;
        }

        /* ============================================================
           CARD HOVER — inset shine on hover
        ============================================================ */
        .dashboard-card, .stat-card {
            isolation: isolate;
        }
        .stat-card::before {
            background: linear-gradient(90deg, var(--primary), #f6af23, var(--primary));
            background-size: 200% 100%;
            transition: opacity var(--transition), background-position .8s ease;
        }
        .stat-card:hover::before {
            opacity: 1;
            background-position: right center;
        }

        /* ============================================================
           TOPBAR — user dropdown button polish
        ============================================================ */
        .topbar .dropdown > button.btn-light {
            background: var(--input-bg) !important;
            border-color: var(--card-border) !important;
            transition: all var(--transition);
        }
        .topbar .dropdown > button.btn-light:hover {
            background: var(--card-border) !important;
            border-color: rgba(200,77,223,.3) !important;
        }
        [data-theme="dark"] .topbar .dropdown > button.btn-light {
            background: rgba(255,255,255,.06) !important;
            border-color: rgba(200,77,223,.15) !important;
        }

        /* ============================================================
           TOPBAR BUTTONS — consistent hover ring
        ============================================================ */
        .top-btn:hover {
            background: var(--input-bg);
            color: var(--primary);
            border-color: rgba(200,77,223,.3);
            box-shadow: 0 0 0 3px rgba(200,77,223,.1);
        }

        /* ============================================================
           SCROLLBAR — thinner and more subtle
        ============================================================ */
        .sidebar::-webkit-scrollbar { width: 3px; }
        .sidebar::-webkit-scrollbar-thumb { background: rgba(200,77,223,.3); }

        /* ============================================================
           NAV LINK LABELS — slightly tighter tracking
        ============================================================ */
        .nav-link span { letter-spacing: .005em; }

        /* ============================================================
           MOBILE BOTTOM NAV — active icon glow
        ============================================================ */
        .mob-nav-item.active i {
            filter: drop-shadow(0 0 5px rgba(200,77,223,.6));
        }
        .mob-nav-item.active {
            background: rgba(200,77,223,.08);
            border-radius: 10px;
        }

        /* ============================================================
           GRADIENT BANNER CARDS — top shimmer highlight line
        ============================================================ */
        .dashboard-card[style*="border:none"] {
            position: relative;
        }
        .dashboard-card[style*="border:none"]::before {
            content: '';
            position: absolute;
            top: 0; left: 10%; right: 10%;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,.2), transparent);
            border-radius: 50%;
            pointer-events: none;
        }

        /* ============================================================
           PAGE ENTRANCE — smoother fade-in
        ============================================================ */
        .content-wrapper.fade-in {
            animation: pageEntrance .35s cubic-bezier(.22,1,.36,1) both;
        }
        @keyframes pageEntrance {
            from { opacity: 0; transform: translateY(6px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ============================================================
           SECTION HEADERS — table card headers
        ============================================================ */
        .card-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .1em;
            color: var(--text-muted);
            font-family: var(--font-sans);
        }

        /* ============================================================
           MODAL CONTENT — subtle top border accent
        ============================================================ */
        .modal-content {
            border-top: 3px solid;
            border-image: linear-gradient(90deg, #68117e, #c84ddf, #f6af23) 1;
        }

        /* ============================================================
           FORM INPUTS — slightly taller touch target
        ============================================================ */
        .form-control, .form-select {
            min-height: 42px;
        }

        /* ============================================================
           DARK MODE — ambient glow tint
        ============================================================ */
        [data-theme="dark"] .main-content::before {
            background: radial-gradient(circle, rgba(200,77,223,.08) 0%, transparent 68%);
        }

        /* ============================================================
           BADGE — improved legibility
        ============================================================ */
        .badge { font-family: var(--font-sans); font-size: 11px; }

        /* ============================================================
           PAGE LOADER — dark mode aware
        ============================================================ */
        [data-theme="dark"] #pageLoader {
            background: rgba(26,4,37,.85);
            backdrop-filter: blur(6px);
        }

        /* ============================================================
           SCROLL TOP — float from bottom-right clear of nav
        ============================================================ */
        @media (max-width: 992px) { #scrollTop { bottom: 82px; right: 16px; } }

        /* ============================================================
           TABLE — sticky first column helper
        ============================================================ */
        .table-sticky-col td:first-child,
        .table-sticky-col th:first-child {
            position: sticky; left: 0; z-index: 1;
            background: var(--card-bg);
        }

        /* ============================================================
           IMPERSONATE BANNER — improved style
        ============================================================ */
        .impersonate-banner {
            background: linear-gradient(90deg, #fef2f2, #fff4f4);
            border-bottom: 1px solid #fecaca;
            padding: 10px 20px;
            text-align: center;
            font-size: 13px;
            font-weight: 500;
            color: #dc2626;
        }
    </style>

    @stack('styles')
</head>
<body>
    @if(session()->has('impersonate.original_user'))
        <div style="background:#fff4f4;border-bottom:1px solid #fecaca;padding:8px;text-align:center;z-index:1200">
            Anda sedang <strong>mengakses sebagai admin cabang</strong>. 
            <form method="POST" action="{{ route('impersonate.leave') }}" style="display:inline;margin-left:8px">
                @csrf
                <button class="btn btn-sm btn-danger">Kembali ke Pusat</button>
            </form>
        </div>
    @endif

{{-- NAV PROGRESS BAR --}}
<div id="navProgress"></div>

{{-- SIDEBAR OVERLAY (mobile) --}}
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

{{-- SIDEBAR --}}
<nav class="sidebar" id="sidebar">

    <a href="{{ route('dashboard') }}" class="sidebar-brand">
        <div class="brand-logo">
            <i class="bi bi-mortarboard-fill"></i>
        </div>
        <div style="flex:1;min-width:0">
            <div class="brand-title">Smart Center</div>
            <div class="brand-sub">Enterprise System</div>
        </div>
        <button class="mini-toggle" id="miniToggle" onclick="toggleMini(event)" title="Perkecil sidebar">
            <i class="bi bi-layout-sidebar-reverse" id="miniIcon"></i>
        </button>
    </a>

    <div class="sidebar-user">
        <img src="{{ auth()->user()->avatar ? asset('storage/'.auth()->user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=68117e&color=fff&size=80' }}"
             class="sidebar-avatar" alt="Avatar" id="sidebarAvatar">
        <div>
            <div class="user-name">{{ auth()->user()->name }}</div>
            <div class="user-role">{{ ucfirst(auth()->user()->getRoleNames()->first() ?? 'User') }}</div>
        </div>
    </div>

    <div class="sidebar-nav">

        {{-- DASHBOARD --}}
        <div class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" data-label="Dashboard">
                <i class="bi bi-grid-fill"></i>
                <span>Dashboard</span>
            </a>
        </div>

        {{-- OWNER --}}
        @role('owner')
        <div class="nav-header">OWNER PANEL</div>

        <div class="nav-item">
            <a href="{{ route('owner.branches.index') }}" class="nav-link {{ request()->routeIs('owner.branches.*') ? 'active' : '' }}" data-label="Monitoring Cabang">
                <i class="bi bi-building"></i>
                <span>Monitoring Cabang</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('owner.activity-log') }}" class="nav-link {{ request()->routeIs('owner.activity-log') ? 'active' : '' }}" data-label="Log Aktivitas">
                <i class="bi bi-journal-text"></i>
                <span>Log Aktivitas</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('owner.settings.index') }}" class="nav-link {{ request()->routeIs('owner.settings.*') ? 'active' : '' }}" data-label="Pengaturan">
                <i class="bi bi-gear"></i>
                <span>Pengaturan</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('owner.analytics') }}" class="nav-link {{ request()->routeIs('owner.analytics') ? 'active' : '' }}" data-label="Analytics">
                <i class="bi bi-graph-up-arrow"></i>
                <span>Analytics</span>
            </a>
        </div>
        @endrole

        {{-- ADMIN --}}
        @role('admin|owner')
        <div class="nav-header">AKADEMIK</div>

        <div class="nav-item">
            <a href="{{ route('admin.students.index') }}" class="nav-link {{ request()->routeIs('admin.students.*') ? 'active' : '' }}" data-label="Siswa">
                <i class="bi bi-mortarboard"></i>
                <span>Siswa</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.teachers.index') }}" class="nav-link {{ request()->routeIs('admin.teachers.*') ? 'active' : '' }}" data-label="Guru">
                <i class="bi bi-person-workspace"></i>
                <span>Guru</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.modules.index') }}" class="nav-link {{ request()->routeIs('admin.modules.*') ? 'active' : '' }}" data-label="Modul Belajar">
                <i class="bi bi-book-half"></i>
                <span>Modul Belajar</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.packages.index') }}" class="nav-link {{ request()->routeIs('admin.packages.*') ? 'active' : '' }}" data-label="Paket Belajar">
                <i class="bi bi-box-seam"></i>
                <span>Paket Belajar</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.courses.index') }}" class="nav-link {{ request()->routeIs('admin.courses.*') ? 'active' : '' }}" data-label="Mata Pelajaran">
                <i class="bi bi-journal-bookmark"></i>
                <span>Mata Pelajaran</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.classes.index') }}" class="nav-link {{ request()->routeIs('admin.classes.*') ? 'active' : '' }}" data-label="Kelas">
                <i class="bi bi-diagram-3"></i>
                <span>Kelas</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.schedules.index') }}" class="nav-link {{ request()->routeIs('admin.schedules.*') ? 'active' : '' }}" data-label="Jadwal">
                <i class="bi bi-calendar-week"></i>
                <span>Jadwal</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.certificates.index') }}" class="nav-link {{ request()->routeIs('admin.certificates.*') ? 'active' : '' }}" data-label="Sertifikat">
                <i class="bi bi-award"></i>
                <span>Sertifikat</span>
            </a>
        </div>

        <div class="nav-header">KEUANGAN</div>

        <div class="nav-item">
            <a href="{{ route('admin.payments.index') }}" class="nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}" data-label="Pembayaran">
                <i class="bi bi-wallet2"></i>
                <span>Pembayaran</span>
                @php $unpaidInvoices = \App\Models\Invoice::where('status','belum_bayar')->count() @endphp
                @if($unpaidInvoices > 0)
                    <span class="menu-badge">{{ $unpaidInvoices > 99 ? '99+' : $unpaidInvoices }}</span>
                @endif
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.salaries.index') }}" class="nav-link {{ request()->routeIs('admin.salaries.*') ? 'active' : '' }}" data-label="Gaji Guru">
                <i class="bi bi-cash-stack"></i>
                <span>Gaji Guru</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" data-label="Laporan Keuangan">
                <i class="bi bi-bar-chart-line"></i>
                <span>Laporan Keuangan</span>
            </a>
        </div>

        <div class="nav-header">KOMUNIKASI</div>

        <div class="nav-item">
            <a href="{{ route('admin.announcements.index') }}" class="nav-link {{ request()->routeIs('admin.announcements.*') ? 'active' : '' }}" data-label="Pengumuman">
                <i class="bi bi-megaphone"></i>
                <span>Pengumuman</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.messages.index') }}" class="nav-link {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}" data-label="Pesan">
                <i class="bi bi-chat-dots"></i>
                <span>Pesan Aplikasi</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.videocall.index') }}" class="nav-link {{ request()->routeIs('admin.videocall.*') ? 'active' : '' }}" data-label="Video Call">
                <i class="bi bi-camera-video"></i>
                <span>Video Call</span>
            </a>
        </div>

        <div class="nav-header">TRYOUT CBT</div>

        <div class="nav-item">
            <a href="{{ route('admin.tryouts.index') }}" class="nav-link {{ request()->routeIs('admin.tryouts.*') ? 'active' : '' }}" data-label="Tryout">
                <i class="bi bi-journal-check"></i>
                <span>Tryout UTBK/PTN</span>
            </a>
        </div>
        @endrole

        {{-- GURU --}}
        @role('guru')
        <div class="nav-header">GURU PANEL</div>
        <div class="nav-item">
            <a href="{{ route('guru.dashboard') }}" class="nav-link {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}" data-label="Dashboard Guru">
                <i class="bi bi-speedometer2"></i><span>Dashboard Guru</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('guru.dashboard') }}#jadwal" class="nav-link" data-label="Jadwal Mengajar">
                <i class="bi bi-calendar2-week"></i><span>Jadwal Mengajar</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('guru.attendance') }}" class="nav-link {{ request()->routeIs('guru.attendance') ? 'active' : '' }}" data-label="Input Absensi">
                <i class="bi bi-check2-square"></i><span>Input Absensi</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('guru.grades') }}" class="nav-link {{ request()->routeIs('guru.grades') ? 'active' : '' }}" data-label="Input Nilai">
                <i class="bi bi-pencil-square"></i><span>Input Nilai</span>
            </a>
        </div>
        @endrole

        {{-- SISWA --}}
        @role('siswa')
        <div class="nav-header">SISWA PANEL</div>
        <div class="nav-item">
            <a href="{{ route('siswa.dashboard') }}" class="nav-link {{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}" data-label="Dashboard">
                <i class="bi bi-speedometer2"></i><span>Dashboard</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('siswa.schedule') }}" class="nav-link {{ request()->routeIs('siswa.schedule') ? 'active' : '' }}" data-label="Jadwal Belajar">
                <i class="bi bi-calendar-event"></i><span>Jadwal Belajar</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('siswa.dashboard') }}#pembayaran" class="nav-link" data-label="Tagihan">
                <i class="bi bi-credit-card"></i><span>Tagihan & Bayar</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('siswa.certificates.index') }}" class="nav-link {{ request()->routeIs('siswa.certificates.*') ? 'active' : '' }}" data-label="Sertifikat Saya">
                <i class="bi bi-award"></i><span>Sertifikat Saya</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('siswa.tryout') }}" class="nav-link {{ request()->routeIs('siswa.tryout') ? 'active' : '' }}" data-label="Tryout CBT">
                <i class="bi bi-laptop"></i><span>Tryout CBT</span>
                <span class="menu-badge" style="background:#68117e">Soon</span>
            </a>
        </div>
        @endrole

        {{-- SYSTEM --}}
        <div class="nav-header">SYSTEM</div>

        <div class="nav-item">
            <a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}" data-label="Profil Saya">
                <i class="bi bi-person-circle"></i>
                <span>Profil Saya</span>
            </a>
        </div>

        <div class="nav-item">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="nav-link border-0 bg-transparent w-100 text-start" style="color:#94a3b8" data-label="Logout">
                    <i class="bi bi-box-arrow-left" style="color:#ef4444"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>

    </div>

</nav>

{{-- MAIN CONTENT --}}
<div class="main-content">

    {{-- TOPBAR --}}
    <div class="topbar">

        <div class="topbar-left">
            <button class="top-btn d-lg-none" onclick="toggleSidebar()" aria-label="Menu">
                <i class="bi bi-list" style="font-size:18px"></i>
            </button>

            <div>
                <h4>@yield('page-title','Dashboard')</h4>
                <p class="d-none d-md-block">Smart Center — Sistem Manajemen Bimbel Enterprise</p>
            </div>
        </div>

        <div class="topbar-right">

            {{-- Command palette trigger --}}
            <button class="top-btn d-none d-md-flex align-items-center gap-1" onclick="openCmdPalette()" title="Cari (Ctrl+K)"
                style="width:auto;padding:0 12px;gap:8px;font-size:12.5px;color:var(--text-muted)">
                <i class="bi bi-search" style="font-size:14px"></i>
                <span>Cari</span>
                <span style="display:inline-flex;align-items:center;gap:2px;background:var(--card-border);border-radius:5px;padding:1px 6px;font-size:10px;font-family:monospace;margin-left:4px">⌘K</span>
            </button>
            {{-- Mobile search icon --}}
            <button class="top-btn d-md-none" onclick="openCmdPalette()" title="Cari">
                <i class="bi bi-search"></i>
            </button>

            <button class="top-btn" id="darkToggle" onclick="toggleDark()" title="Toggle dark mode" aria-label="Toggle dark mode">
                <i class="bi bi-moon" id="darkIcon"></i>
            </button>

            <button class="top-btn position-relative" title="Notifikasi" id="notifBtn" aria-label="Notifikasi">
                <i class="bi bi-bell"></i>
            </button>

            <div class="dropdown">
                <button class="btn btn-light dropdown-toggle d-flex align-items-center gap-2 border"
                        data-bs-toggle="dropdown" style="border-radius:12px;padding:6px 12px 6px 8px;font-size:13px">
                    <img id="topbarAvatar"
                         src="{{ auth()->user()->avatar ? asset('storage/'.auth()->user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=68117e&color=fff&size=64' }}"
                         width="32" height="32" class="rounded-circle" style="object-fit:cover">
                    <div class="text-start d-none d-md-block">
                        <div class="fw-semibold" style="font-size:13px;line-height:1.2">{{ Str::limit(auth()->user()->name, 16) }}</div>
                        <div style="font-size:10px;color:var(--text-muted)">{{ ucfirst(auth()->user()->getRoleNames()->first() ?? 'User') }}</div>
                    </div>
                    <i class="bi bi-chevron-down small d-none d-md-block ms-1" style="font-size:10px"></i>
                </button>

                <ul class="dropdown-menu dropdown-menu-end shadow border-0 py-1" style="border-radius:14px;min-width:180px">
                    <li>
                        <a class="dropdown-item py-2" href="{{ route('profile.edit') }}">
                            <i class="bi bi-person me-2 text-primary"></i>Profil Saya
                        </a>
                    </li>
                    <li><hr class="dropdown-divider my-1"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="dropdown-item py-2 text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>

        </div>

    </div>

    {{-- CONTENT --}}
    <div class="content-wrapper fade-in">
        @yield('content')
    </div>

</div>

{{-- SCROLL TO TOP --}}
<button id="scrollTop" onclick="window.scrollTo({top:0,behavior:'smooth'})" title="Kembali ke atas">
    <i class="bi bi-arrow-up"></i>
</button>

{{-- MOBILE BOTTOM NAV --}}
<nav class="mobile-bottom-nav" id="mobileBottomNav">
    <a href="{{ route('dashboard') }}" class="mob-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <i class="bi bi-house-door{{ request()->routeIs('dashboard') ? '-fill' : '' }}"></i>
        <span>Home</span>
    </a>
    @role('admin|owner')
    <a href="{{ route('admin.students.index') }}" class="mob-nav-item {{ request()->routeIs('admin.students.*') ? 'active' : '' }}">
        <i class="bi bi-people{{ request()->routeIs('admin.students.*') ? '-fill' : '' }}"></i>
        <span>Siswa</span>
    </a>
    <a href="{{ route('admin.payments.index') }}" class="mob-nav-item {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
        <i class="bi bi-cash{{ request()->routeIs('admin.payments.*') ? '-stack' : '' }}"></i>
        <span>Bayar</span>
    </a>
    @endrole
    @role('guru')
    <a href="{{ route('guru.dashboard') }}#jadwal" class="mob-nav-item">
        <i class="bi bi-calendar2-week"></i>
        <span>Jadwal</span>
    </a>
    <a href="{{ route('guru.attendance') }}" class="mob-nav-item {{ request()->routeIs('guru.attendance') ? 'active' : '' }}">
        <i class="bi bi-clipboard-check"></i>
        <span>Absensi</span>
    </a>
    @endrole
    @role('siswa')
    <a href="{{ route('siswa.schedule') }}" class="mob-nav-item {{ request()->routeIs('siswa.schedule') ? 'active' : '' }}">
        <i class="bi bi-calendar-event{{ request()->routeIs('siswa.schedule') ? '-fill' : '' }}"></i>
        <span>Jadwal</span>
    </a>
    <a href="{{ route('siswa.dashboard') }}#pembayaran" class="mob-nav-item">
        <i class="bi bi-credit-card"></i>
        <span>Tagihan</span>
    </a>
    @endrole
    <a href="{{ route('profile.edit') }}" class="mob-nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
        <i class="bi bi-person{{ request()->routeIs('profile.*') ? '-fill' : '' }}"></i>
        <span>Profil</span>
    </a>
    <button class="mob-nav-item border-0" onclick="toggleSidebar()" style="background:none">
        <i class="bi bi-grid-3x3-gap-fill"></i>
        <span>Menu</span>
    </button>
</nav>

{{-- COMMAND PALETTE --}}
<div id="cmdOverlay" onclick="closeCmdPalette(event)">
    <div id="cmdBox" role="dialog" aria-label="Command Palette">
        <div id="cmdInputWrap">
            <i class="bi bi-search"></i>
            <input id="cmdInput" type="text" placeholder="Cari halaman, menu, atau fitur..." autocomplete="off" spellcheck="false">
            <span class="cmd-shortcut">ESC</span>
        </div>
        <div id="cmdResults"></div>
        <div id="cmdFooter">
            <div class="cmd-hint"><kbd>↑</kbd><kbd>↓</kbd> Navigasi</div>
            <div class="cmd-hint"><kbd>↵</kbd> Buka</div>
            <div class="cmd-hint"><kbd>Esc</kbd> Tutup</div>
            <div class="cmd-hint" style="margin-left:auto"><kbd>Ctrl</kbd><kbd>K</kbd> Buka palette</div>
        </div>
    </div>
</div>

{{-- GLOBAL TOAST WRAPPER --}}
<div id="globalToastWrap"></div>

{{-- PAGE LOADER --}}
<div id="pageLoader">
    <div class="text-center">
        <div class="spinner-border text-primary mb-2" role="status"></div>
        <div style="font-size:13px;color:#64748b">Loading...</div>
    </div>
</div>

{{-- FLASH DATA FOR JS TOAST SYSTEM --}}
<script id="__flash__" type="application/json">
{
    "success": "{{ addslashes(session('success') ?? '') }}",
    "error":   "{{ addslashes(session('error')   ?? '') }}",
    "warning": "{{ addslashes(session('warning') ?? '') }}",
    "info":    "{{ addslashes(session('info')    ?? '') }}"
}
</script>

{{-- SCRIPTS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// ---- SIDEBAR TOGGLE ----
function toggleSidebar() {
    const sidebar  = document.getElementById('sidebar');
    const overlay  = document.getElementById('sidebarOverlay');
    const isOpen   = sidebar.classList.contains('show');
    if (isOpen) {
        closeSidebar();
    } else {
        sidebar.classList.add('show');
        overlay.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
}
function closeSidebar() {
    document.getElementById('sidebar').classList.remove('show');
    document.getElementById('sidebarOverlay').classList.remove('show');
    document.body.style.overflow = '';
}

// ---- DARK MODE ----
(function() {
    const saved = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-theme', saved);
    updateDarkIcon(saved);
})();

function toggleDark() {
    const current = document.documentElement.getAttribute('data-theme');
    const next    = current === 'dark' ? 'light' : 'dark';
    document.documentElement.setAttribute('data-theme', next);
    localStorage.setItem('theme', next);
    updateDarkIcon(next);
    document.dispatchEvent(new CustomEvent('themechange', { detail: { theme: next } }));
}

function updateDarkIcon(theme) {
    const icon = document.getElementById('darkIcon');
    if (!icon) return;
    icon.className = theme === 'dark' ? 'bi bi-sun' : 'bi bi-moon';
}

// ---- INTERSECTION OBSERVER (fade-up + stagger) ----
document.addEventListener('DOMContentLoaded', function() {
    const io = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('in-view');
                io.unobserve(entry.target);
            }
        });
    }, { threshold: 0.05 });
    document.querySelectorAll('.fade-up, .stagger-children').forEach(el => io.observe(el));
});

// ---- MOBILE TABLE ROW-EXPAND ----
function initMobileRowExpand(scope) {
    if (window.innerWidth >= 768) return;
    const root = scope || document;
    root.querySelectorAll('.table-hover tbody tr:not(.expand-detail-row)').forEach(row => {
        if (row.querySelector('.mobile-expand-btn')) return; // already initialised
        const hiddenCells = Array.from(row.querySelectorAll('td')).filter(td =>
            td.className.includes('d-none') || getComputedStyle(td).display === 'none'
        );
        if (!hiddenCells.length) return;

        // Get header labels for the hidden columns
        const table   = row.closest('table');
        const headers = table ? Array.from(table.querySelectorAll('thead th')) : [];
        const allCells = Array.from(row.querySelectorAll('td'));

        // Append expand button inside last cell
        const lastTd = row.querySelector('td:last-child');
        if (!lastTd) return;
        const btn = document.createElement('button');
        btn.className    = 'mobile-expand-btn';
        btn.type         = 'button';
        btn.setAttribute('aria-label', 'Lihat detail');
        btn.innerHTML    = '<i class="bi bi-chevron-down"></i>';
        lastTd.appendChild(btn);

        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const open     = this.classList.toggle('open');
            this.innerHTML = open ? '<i class="bi bi-chevron-up"></i>' : '<i class="bi bi-chevron-down"></i>';
            this.setAttribute('aria-expanded', open);

            // Remove existing detail row if any
            const existing = row.nextElementSibling;
            if (existing && existing.classList.contains('expand-detail-row')) {
                existing.remove();
                if (!open) return;
            }

            // Build detail row
            const detailRow = document.createElement('tr');
            detailRow.className = 'expand-detail-row';
            const td = document.createElement('td');
            td.colSpan = row.cells.length;

            let innerHtml = '<div class="expand-detail-grid">';
            hiddenCells.forEach(cell => {
                const idx   = allCells.indexOf(cell);
                const label = headers[idx] ? headers[idx].textContent.trim() : '';
                if (!label || label === '#') return;
                innerHtml += `<div class="expand-detail-item">
                    <div class="expand-label">${label}</div>
                    <div>${cell.innerHTML}</div>
                </div>`;
            });
            innerHtml += '</div>';
            td.innerHTML = innerHtml;
            detailRow.appendChild(td);
            row.after(detailRow);
        });
    });
}

// Run on DOM load for static tables; watch AJAX-populated tbodies
document.addEventListener('DOMContentLoaded', function() {
    initMobileRowExpand();
    if (window.innerWidth < 768) {
        const obs = new MutationObserver(mutations => {
            mutations.forEach(m => {
                if (m.type === 'childList' && m.addedNodes.length) {
                    const tbody = m.target;
                    if (tbody.tagName === 'TBODY') {
                        setTimeout(() => initMobileRowExpand(tbody.closest('table')), 80);
                    }
                }
            });
        });
        document.querySelectorAll('tbody').forEach(tb => obs.observe(tb, { childList: true }));
    }
});

// ---- COUNT-UP ANIMATION ----
document.addEventListener('DOMContentLoaded', function() {
    const countEls = document.querySelectorAll('.count-up[data-target]');
    if (!countEls.length) return;
    const io2 = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (!entry.isIntersecting) return;
            const el = entry.target;
            const target = parseInt(el.getAttribute('data-target'), 10);
            if (isNaN(target)) return;
            const duration = 900;
            const step = 16;
            const increment = target / (duration / step);
            let current = 0;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) { current = target; clearInterval(timer); }
                el.textContent = Math.floor(current).toLocaleString('id-ID');
            }, step);
            io2.unobserve(el);
        });
    }, { threshold: 0.3 });
    countEls.forEach(el => io2.observe(el));
});

// ---- RIPPLE EFFECT ON ALL BUTTONS ----
document.addEventListener('click', function(e) {
    const btn = e.target.closest('.btn');
    if (!btn) return;
    const circle = document.createElement('span');
    const d = Math.max(btn.clientWidth, btn.clientHeight);
    const r = btn.getBoundingClientRect();
    circle.className = 'ripple-wave';
    circle.style.cssText = `width:${d}px;height:${d}px;left:${e.clientX - r.left - d/2}px;top:${e.clientY - r.top - d/2}px`;
    btn.appendChild(circle);
    setTimeout(() => circle.remove(), 600);
});

// ---- HOVER SOUND-LIKE MICRO-FEEDBACK (scale on icon hover) ----
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.stat-card .stat-icon').forEach(icon => {
        icon.style.transition = 'transform .25s cubic-bezier(.34,1.56,.64,1)';
        const card = icon.closest('.stat-card');
        if (!card) return;
        card.addEventListener('mouseenter', () => { icon.style.transform = 'scale(1.12) rotate(-4deg)'; });
        card.addEventListener('mouseleave', () => { icon.style.transform = ''; });
    });
});

// ---- CLOSE SIDEBAR ON ESC ----
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') { closeSidebar(); closeCmdPalette(); }
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') { e.preventDefault(); openCmdPalette(); }
});

// ---- TOPBAR SCROLL SHADOW ----
(function() {
    const topbar = document.querySelector('.topbar');
    if (!topbar) return;
    function onScroll() {
        topbar.classList.toggle('scrolled', window.pageYOffset > 10);
    }
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
})();

// ---- TABLE SCROLL FADE HINT ----
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.table-responsive').forEach(wrap => {
        wrap.classList.add('table-scroll-wrap');
        function check() { wrap.classList.toggle('can-scroll', wrap.scrollWidth > wrap.clientWidth + 4); }
        check();
        new ResizeObserver(check).observe(wrap);
    });
});

// ---- NAV PROGRESS BAR ----
(function() {
    const bar = document.getElementById('navProgress');
    if (!bar) return;
    let timer;
    function startProgress() {
        bar.classList.add('active');
        bar.style.width = '0%';
        let pct = 0;
        clearInterval(timer);
        timer = setInterval(() => {
            pct += pct < 70 ? Math.random() * 8 : (pct < 90 ? Math.random() * 2 : 0);
            if (pct >= 95) { clearInterval(timer); pct = 95; }
            bar.style.width = pct + '%';
        }, 120);
    }
    function finishProgress() {
        clearInterval(timer);
        bar.style.width = '100%';
        setTimeout(() => { bar.classList.remove('active'); bar.style.width = '0%'; }, 400);
    }
    // Intercept sidebar nav link clicks
    document.querySelectorAll('.nav-link[href]:not([href="#"])').forEach(a => {
        a.addEventListener('click', function(e) {
            if (!e.ctrlKey && !e.metaKey && !e.shiftKey) startProgress();
        });
    });
    window.addEventListener('pageshow', finishProgress);
    document.addEventListener('DOMContentLoaded', finishProgress);
})();

// ---- SKELETON → SHIMMER for placeholder spans ----
document.querySelectorAll('.placeholder').forEach(el => el.classList.add('skeleton'));

// ---- SCROLL TO TOP ----
(function() {
    const btn = document.getElementById('scrollTop');
    if (!btn) return;
    function checkScroll() {
        btn.classList.toggle('visible', window.pageYOffset > 220);
    }
    window.addEventListener('scroll', checkScroll, { passive: true });
    checkScroll();
})();

// ---- GLOBAL FLASH TOASTS ----
(function() {
    const icons = {
        success: { icon: 'bi-check-circle-fill', color: '#10b981', bg: '#ecfdf5', border: '#bbf7d0' },
        error:   { icon: 'bi-x-circle-fill',     color: '#ef4444', bg: '#fef2f2', border: '#fecaca' },
        warning: { icon: 'bi-exclamation-triangle-fill', color: '#f6af23', bg: '#fffbeb', border: '#fde68a' },
        info:    { icon: 'bi-info-circle-fill',   color: '#c84ddf', bg: '#fdf4ff', border: '#e8b4f5' },
    };
    function showToast(msg, type = 'info', duration = 4000) {
        if (!msg) return;
        const wrap = document.getElementById('globalToastWrap');
        if (!wrap) return;
        const cfg = icons[type] || icons.info;
        const el = document.createElement('div');
        el.className = 'g-toast';
        el.style.cssText = `background:${cfg.bg};border-color:${cfg.border}`;
        el.innerHTML = `
            <i class="bi ${cfg.icon}" style="font-size:20px;color:${cfg.color};flex-shrink:0;margin-top:1px"></i>
            <div style="flex:1;min-width:0">
                <div style="font-size:13.5px;font-weight:600;color:var(--text-primary);line-height:1.3">${msg}</div>
            </div>
            <button onclick="this.closest('.g-toast').remove()" style="border:none;background:none;color:var(--text-muted);cursor:pointer;padding:0;font-size:16px;line-height:1;flex-shrink:0">&times;</button>`;
        wrap.appendChild(el);
        setTimeout(() => {
            el.classList.add('hide');
            el.addEventListener('animationend', () => el.remove());
        }, duration);
    }
    // Expose globally
    window.showToast = showToast;
    // Read server-sent flash data from meta tags rendered at page load
    document.addEventListener('DOMContentLoaded', () => {
        const flashEl = document.getElementById('__flash__');
        if (!flashEl) return;
        try {
            const data = JSON.parse(flashEl.textContent);
            if (data.success) showToast(data.success, 'success');
            if (data.error)   showToast(data.error,   'error');
            if (data.warning) showToast(data.warning, 'warning');
            if (data.info)    showToast(data.info,    'info');
            // Only show data.status if it looks like a human-readable string (not a Laravel key like 'profile-updated')
            if (data.status && !data.status.includes('-') && data.status.length > 4) showToast(data.status, 'success');
        } catch(e) {}
    });
})();

// ---- NOTIFICATION DROPDOWN (click → show simple panel) ----
(function() {
    const btn = document.getElementById('notifBtn');
    if (!btn) return;
    btn.addEventListener('click', function(e) {
        e.stopPropagation();
        this.querySelector('.notif-badge')?.remove();
        const existing = document.getElementById('notifPanel');
        if (existing) { existing.remove(); return; }
        const rect = btn.getBoundingClientRect();
        const panel = document.createElement('div');
        panel.id = 'notifPanel';
        panel.style.cssText = `
            position:fixed; top:${rect.bottom + 8}px;
            right:${Math.max(8, document.documentElement.clientWidth - rect.right)}px;
            width:min(300px, calc(100vw - 16px)); background:var(--card-bg); border:1px solid var(--card-border);
            border-radius:16px; box-shadow:0 12px 40px rgba(0,0,0,.15);
            z-index:9998; overflow:hidden; animation:fadeIn .2s ease both;
        `;
        panel.innerHTML = `
            <div style="padding:14px 16px;border-bottom:1px solid var(--card-border);display:flex;justify-content:space-between;align-items:center">
                <span style="font-size:14px;font-weight:700;color:var(--text-primary)"><i class="bi bi-bell-fill me-2 text-primary"></i>Notifikasi</span>
                <span style="font-size:11px;color:var(--text-muted)">Hari ini</span>
            </div>
            <div style="padding:20px 16px;text-align:center">
                <i class="bi bi-bell-slash" style="font-size:2.5rem;color:#cbd5e1;display:block;margin-bottom:8px"></i>
                <div style="font-size:13px;color:var(--text-muted)">Tidak ada notifikasi baru</div>
            </div>`;
        document.body.appendChild(panel);
        const close = e2 => { if (!panel.contains(e2.target) && e2.target !== btn) { panel.remove(); document.removeEventListener('click', close); } };
        setTimeout(() => document.addEventListener('click', close), 0);
    });
})();
</script>

<script src="{{ asset('js/modal-fallback.js') }}" defer></script>

<script>
// ================================================================
// COMMAND PALETTE
// ================================================================
(function() {
    const cmdPages = [
        { label:'Dashboard', desc:'Halaman utama', href:'{{ route("dashboard") }}', icon:'bi-grid-fill', color:'#c84ddf', group:'Navigasi' },
        { label:'Data Siswa', desc:'Kelola siswa', href:'{{ route("admin.students.index") }}', icon:'bi-mortarboard', color:'#c84ddf', group:'Akademik' },
        { label:'Data Guru', desc:'Kelola guru & pengajar', href:'{{ route("admin.teachers.index") }}', icon:'bi-person-workspace', color:'#10b981', group:'Akademik' },
        { label:'Modul Belajar', desc:'Upload & kelola materi', href:'{{ route("admin.modules.index") }}', icon:'bi-book-half', color:'#68117e', group:'Akademik' },
        { label:'Paket Belajar', desc:'Atur paket & harga', href:'{{ route("admin.packages.index") }}', icon:'bi-box-seam', color:'#059669', group:'Akademik' },
        { label:'Mata Pelajaran', desc:'Kelola mapel', href:'{{ route("admin.courses.index") }}', icon:'bi-journal-bookmark', color:'#10b981', group:'Akademik' },
        { label:'Kelas', desc:'Manajemen kelas belajar', href:'{{ route("admin.classes.index") }}', icon:'bi-diagram-3', color:'#68117e', group:'Akademik' },
        { label:'Jadwal', desc:'Jadwal mengajar & sesi', href:'{{ route("admin.schedules.index") }}', icon:'bi-calendar-week', color:'#461256', group:'Akademik' },
        { label:'Sertifikat', desc:'Terbitkan sertifikat siswa', href:'{{ route("admin.certificates.index") }}', icon:'bi-award', color:'#f6af23', group:'Akademik' },
        { label:'Pembayaran', desc:'Invoice & tagihan siswa', href:'{{ route("admin.payments.index") }}', icon:'bi-wallet2', color:'#059669', group:'Keuangan' },
        { label:'Gaji Guru', desc:'Kelola gaji & slip', href:'{{ route("admin.salaries.index") }}', icon:'bi-cash-stack', color:'#68117e', group:'Keuangan' },
        { label:'Laporan Keuangan', desc:'Rekap & analitik keuangan', href:'{{ route("admin.reports.index") }}', icon:'bi-bar-chart-line', color:'#260632', group:'Keuangan' },
        { label:'Pengumuman', desc:'Buat & kelola pengumuman', href:'{{ route("admin.announcements.index") }}', icon:'bi-megaphone', color:'#68117e', group:'Komunikasi' },
        { label:'Pesan Aplikasi', desc:'Chat internal', href:'{{ route("admin.messages.index") }}', icon:'bi-chat-dots', color:'#0284c7', group:'Komunikasi' },
        { label:'Video Call', desc:'Kelas virtual online', href:'{{ route("admin.videocall.index") }}', icon:'bi-camera-video', color:'#0d9488', group:'Komunikasi' },
        { label:'Tryout UTBK/PTN', desc:'Kelola soal & ujian CBT', href:'{{ route("admin.tryouts.index") }}', icon:'bi-journal-check', color:'#c84ddf', group:'Tryout CBT' },
        { label:'Profil Saya', desc:'Edit akun & password', href:'{{ route("profile.edit") }}', icon:'bi-person-circle', color:'#c84ddf', group:'Akun' },
    ];

    let cmdActive = -1;
    let cmdFiltered = [];

    function buildResults(query) {
        const q = (query || '').toLowerCase().trim();
        cmdFiltered = q
            ? cmdPages.filter(p => p.label.toLowerCase().includes(q) || p.desc.toLowerCase().includes(q) || p.group.toLowerCase().includes(q))
            : cmdPages;

        const res = document.getElementById('cmdResults');
        if (!cmdFiltered.length) {
            res.innerHTML = '<div id="cmdEmpty"><i class="bi bi-search"></i>Tidak ada hasil untuk "<strong>' + q + '</strong>"</div>';
            return;
        }

        // Group by category
        const groups = {};
        cmdFiltered.forEach(p => { (groups[p.group] = groups[p.group] || []).push(p); });

        let html = '';
        Object.entries(groups).forEach(([g, items]) => {
            if (!q) html += `<div class="cmd-section">${g}</div>`;
            items.forEach((p, i) => {
                const idx = cmdFiltered.indexOf(p);
                html += `<a href="${p.href}" class="cmd-item" data-idx="${idx}" tabindex="-1">
                    <div class="cmd-icon" style="background:${p.color}18;color:${p.color}"><i class="bi ${p.icon}"></i></div>
                    <div style="flex:1;min-width:0">
                        <div class="cmd-label">${p.label}</div>
                        <div class="cmd-desc">${p.desc}</div>
                    </div>
                    <i class="bi bi-arrow-right-short cmd-arrow"></i>
                </a>`;
            });
        });
        res.innerHTML = html;
        cmdActive = -1;
    }

    function setActive(idx) {
        const items = document.querySelectorAll('#cmdResults .cmd-item');
        items.forEach(el => el.classList.remove('cmd-active'));
        if (idx >= 0 && idx < items.length) {
            items[idx].classList.add('cmd-active');
            items[idx].scrollIntoView({ block: 'nearest' });
        }
        cmdActive = idx;
    }

    window.openCmdPalette = function() {
        const overlay = document.getElementById('cmdOverlay');
        overlay.classList.add('open');
        document.body.style.overflow = 'hidden';
        const input = document.getElementById('cmdInput');
        input.value = '';
        buildResults('');
        setTimeout(() => input.focus(), 60);
    };

    window.closeCmdPalette = function(e) {
        if (e && document.getElementById('cmdBox').contains(e.target)) return;
        document.getElementById('cmdOverlay').classList.remove('open');
        document.body.style.overflow = '';
        cmdActive = -1;
    };

    document.addEventListener('DOMContentLoaded', () => {
        const input = document.getElementById('cmdInput');
        if (!input) return;

        input.addEventListener('input', () => { buildResults(input.value); cmdActive = -1; });

        input.addEventListener('keydown', e => {
            const items = document.querySelectorAll('#cmdResults .cmd-item');
            if (e.key === 'ArrowDown') { e.preventDefault(); setActive(Math.min(cmdActive + 1, items.length - 1)); }
            else if (e.key === 'ArrowUp') { e.preventDefault(); setActive(Math.max(cmdActive - 1, 0)); }
            else if (e.key === 'Enter') {
                e.preventDefault();
                if (cmdActive >= 0 && items[cmdActive]) { items[cmdActive].click(); }
                else if (cmdFiltered[0]) { window.location.href = cmdFiltered[0].href; }
            }
        });

        // Delegate clicks
        document.getElementById('cmdResults').addEventListener('click', () => {
            document.getElementById('cmdOverlay').classList.remove('open');
            document.body.style.overflow = '';
        });
    });
})();

// ================================================================
// SIDEBAR MINI MODE
// ================================================================
(function() {
    const KEY = 'sidebar_mini';
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.querySelector('.main-content');

    function applyMini(mini) {
        if (!sidebar) return;
        sidebar.classList.toggle('mini', mini);
        mainContent && mainContent.classList.toggle('mini', mini);
        const icon = document.getElementById('miniIcon');
        if (icon) icon.className = mini ? 'bi bi-layout-sidebar' : 'bi bi-layout-sidebar-reverse';
    }

    // Restore from localStorage
    const saved = localStorage.getItem(KEY) === '1';
    applyMini(saved);

    window.toggleMini = function(e) {
        if (e) { e.preventDefault(); e.stopPropagation(); }
        const isMini = sidebar.classList.contains('mini');
        applyMini(!isMini);
        localStorage.setItem(KEY, isMini ? '0' : '1');
    };
})();
</script>

@stack('scripts')
</body>
<script>
// Sanitize pagination UI: hide or shrink unexpected oversized elements
function sanitizePaginationArea(selector) {
    document.querySelectorAll(selector).forEach(root => {
        function clean() {
            // Remove icons (svg, i, img) and large injected nodes
            root.querySelectorAll('svg,i,img').forEach(e => e.remove());
            root.querySelectorAll('*').forEach(el => {
                try {
                    const rect = el.getBoundingClientRect();
                    const tag = el.tagName || '';
                    // Hide any element that's clearly oversized inside pagination
                    if ((rect.width > 48 || rect.height > 48) && !['BUTTON','A','UL','LI','NAV','SPAN'].includes(tag)) {
                        el.style.display = 'none';
                    }
                    // Ensure buttons/links show text, not icons
                    if ((tag === 'A' || tag === 'BUTTON' || tag === 'SPAN') && el.querySelectorAll && el.querySelectorAll('svg,i,img').length) {
                        el.querySelectorAll('svg,i,img').forEach(x=>x.remove());
                        if (!el.textContent.trim()) {
                            // set sensible fallback
                            if (el.getAttribute('aria-label') && /previous/i.test(el.getAttribute('aria-label'))) el.textContent = '‹';
                            else if (el.getAttribute('aria-label') && /next/i.test(el.getAttribute('aria-label'))) el.textContent = '›';
                        }
                    }
                } catch (e) {
                    // ignore
                }
            });
        }
        clean();
        const mo = new MutationObserver(clean);
        mo.observe(root, { childList: true, subtree: true, attributes: true });
    });
}
document.addEventListener('DOMContentLoaded', function() {
    sanitizePaginationArea('#paginationLinks');
    sanitizePaginationArea('.pagination');
    sanitizePaginationArea('nav.d-flex');
    // Remove specific SVGs matching known chevron path or tailwind classes inside pagination
    function removeKnownChevrons() {
        const targetPath = 'M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z';
        document.querySelectorAll('#paginationLinks svg, .pagination svg, nav.d-flex svg').forEach(s => {
            try {
                const inner = s.innerHTML || '';
                if (inner.includes(targetPath) || s.classList.contains('w-5') || s.classList.contains('h-5')) s.remove();
            } catch(e) {}
        });
    }
    removeKnownChevrons();
    // Scope MutationObserver to pagination containers only, not entire document.body
    document.querySelectorAll('#paginationLinks, .pagination, nav.d-flex').forEach(el => {
        if (el) new MutationObserver(removeKnownChevrons).observe(el, { childList:true, subtree:true });
    });
});
</script>
</html>
