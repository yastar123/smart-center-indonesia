<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
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
            /* soft status colors for summary boxes & inline badges */
            --soft-primary-bg:      #fdf4ff;
            --soft-primary-border:  #e8b4f5;
            --soft-primary-text:    #68117e;
            --soft-success-bg:      #dcfce7;
            --soft-success-border:  #bbf7d0;
            --soft-success-text:    #15803d;
            --soft-warning-bg:      #fef3c7;
            --soft-warning-border:  #fcd34d;
            --soft-warning-text:    #92400e;
            --soft-info-bg:         #e0f2fe;
            --soft-info-border:     #7dd3fc;
            --soft-info-text:       #075985;
            --soft-danger-bg:       #fee2e2;
            --soft-danger-border:   #fecaca;
            --soft-danger-text:     #991b1b;
            --soft-muted-bg:        #f1f5f9;
            --soft-muted-border:    #e2e8f0;
            --soft-muted-text:      #64748b;
            /* aliases for convenience */
            --body-bg:    #f8f5fa;
            --text-main:  #260632;
        }

        [data-theme="dark"] {
            --content-bg: #1a0425;
            --body-bg:    #1a0425;
            --text-main:  #f0e8f5;
            --card-bg: #2d0a3e;
            --card-border: rgba(200,77,223,.12);
            --text-primary: #f0e8f5;
            --text-muted: #ab8db2;
            --topbar-bg: #2d0a3e;
            --input-bg: #1a0425;
            --overdue-bg: #2d1515;
            --overdue-border: #7f1d1d;
            /* soft status colors — dark mode */
            --soft-primary-bg:      rgba(200,77,223,.12);
            --soft-primary-border:  rgba(200,77,223,.25);
            --soft-primary-text:    #d68eef;
            --soft-success-bg:      rgba(16,185,129,.12);
            --soft-success-border:  rgba(16,185,129,.25);
            --soft-success-text:    #34d399;
            --soft-warning-bg:      rgba(246,175,35,.12);
            --soft-warning-border:  rgba(246,175,35,.25);
            --soft-warning-text:    #fbbf24;
            --soft-info-bg:         rgba(14,165,233,.12);
            --soft-info-border:     rgba(14,165,233,.25);
            --soft-info-text:       #38bdf8;
            --soft-danger-bg:       rgba(239,68,68,.12);
            --soft-danger-border:   rgba(239,68,68,.25);
            --soft-danger-text:     #f87171;
            --soft-muted-bg:        rgba(255,255,255,.06);
            --soft-muted-border:    rgba(255,255,255,.1);
            --soft-muted-text:      #94a3b8;
        }

        /* ============================================================
           RESET & BASE
        ============================================================ */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html { scroll-behavior: smooth; scroll-padding-top: 84px; }

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
        h4 { font-size: clamp(15px, 1.6vw, 19px); }
        h5 { font-size: clamp(14px, 1.3vw, 17px); }
        h6 { font-size: clamp(13px, 1.1vw, 15px); }

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
            background-color: var(--input-bg);
            border: 1.5px solid var(--card-border);
            border-radius: 10px;
            color: var(--text-primary);
            transition: border-color var(--transition), box-shadow var(--transition);
            font-size: 13.5px;
        }
        .form-control:focus, .form-select:focus {
            background-color: var(--card-bg);
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

        /* Quick-dash cards (dashboard quick actions) — neutralise hardcoded light colours */
        [data-theme="dark"] .quick-dash {
            background: rgba(200,77,223,.08) !important;
            border-color: rgba(200,77,223,.18) !important;
        }
        [data-theme="dark"] .quick-dash div[style*="color:#461256"],
        [data-theme="dark"] .quick-dash div[style*="color:#78350f"] {
            color: var(--text-primary) !important;
        }
        [data-theme="dark"] .quick-dash div[style*="color:#e8b4f5"],
        [data-theme="dark"] .quick-dash div[style*="color:#c084fc"],
        [data-theme="dark"] .quick-dash div[style*="color:#d97706"] {
            color: var(--text-muted) !important;
        }
        [data-theme="dark"] .quick-dash i { opacity: .85; }

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
            height: calc(62px + env(safe-area-inset-bottom, 0px));
            background: var(--card-bg);
            border-top: 1px solid var(--card-border);
            z-index: 1048;
            align-items: flex-start;
            justify-content: space-around;
            padding: 0 4px env(safe-area-inset-bottom, 0px);
            box-shadow: 0 -4px 24px rgba(0,0,0,.08);
        }
        @media (max-width: 992px) {
            .mobile-bottom-nav { display: flex; }
            .content-wrapper { padding-bottom: calc(78px + env(safe-area-inset-bottom, 0px)) !important; }
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
           CUSTOM CONFIRM DIALOG
        ============================================================ */
        #confirmOverlay {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 99998;
            background: rgba(0,0,0,.5);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            align-items: center;
            justify-content: center;
            padding: 16px;
        }
        #confirmOverlay.open {
            display: flex;
            animation: fadeIn .15s ease both;
        }
        #confirmBox {
            background: var(--card-bg);
            border-radius: 20px;
            width: min(420px, 100%);
            box-shadow: 0 24px 60px rgba(0,0,0,.25), 0 0 0 1px var(--card-border);
            overflow: hidden;
            animation: slideUp .25s cubic-bezier(.22,1,.36,1) both;
        }

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

            /* Hide ONLY extension-injected oversized SVGs — never Bootstrap Icons (.bi) */
            .pagination svg.w-5, .pagination svg.h-5, .pagination svg[class*="tailwind"],
            #paginationLinks svg.w-5, #paginationLinks svg.h-5, #paginationLinks svg[class*="tailwind"] { display: none !important; }

            /* Target common Heroicon path used in injected chevrons and hide it */
            .pagination svg path[d*="M12.707 5.293"], #paginationLinks svg path[d*="M12.707 5.293"] { display: none !important; }
            /* Also hide svg elements using Tailwind-like size classes if present */
            .pagination svg.w-5.h-5, #paginationLinks svg.w-5.h-5 { display: none !important; }
            /* Hide extension-injected images inside pagination */
            #paginationLinks img { display: none !important; }
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
           PAGE HEADER CARD — used in admin/landing and similar pages
        ============================================================ */
        .page-header-card {
            background: var(--card-bg);
            border-radius: var(--radius-card);
            padding: 20px 24px;
            border: 1px solid var(--card-border);
            box-shadow: var(--shadow-sm);
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }

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
            /* Quick-action card tighter on small phones */
            .quick-action-card { padding: 12px 10px !important; gap: 10px !important; }
            .quick-action-icon { width: 40px !important; height: 40px !important; font-size: 17px !important; border-radius: 11px !important; }
            .quick-action-label { font-size: 12px !important; }
            .quick-action-desc  { font-size: 10px !important; }
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
           SEMANTIC ACTION ICON BUTTONS — dark-mode aware
           Use: btn btn-sm btn-act-view / btn-act-edit / btn-act-del /
                btn-act-pay / btn-act-info
        ============================================================ */
        .btn-act-view, .btn-act-edit, .btn-act-del, .btn-act-pay, .btn-act-info {
            border: none;
            border-radius: 8px;
            width: 32px; height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            transition: transform .18s, box-shadow .18s, opacity .15s;
            flex-shrink: 0;
        }
        .btn-act-view:hover, .btn-act-edit:hover, .btn-act-del:hover,
        .btn-act-pay:hover, .btn-act-info:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,.12);
        }
        /* light mode */
        .btn-act-view  { background: #fdf4ff; color: #68117e; }
        .btn-act-pay   { background: #f0fdf4; color: #16a34a; }
        .btn-act-edit  { background: #fffbeb; color: #e09000; }
        .btn-act-del   { background: #fef2f2; color: #dc2626; }
        .btn-act-info  { background: #eff6ff; color: #1d4ed8; }
        /* dark mode */
        [data-theme="dark"] .btn-act-view  { background: rgba(200,77,223,.14); color: #d68eef; }
        [data-theme="dark"] .btn-act-pay   { background: rgba(16,185,129,.14); color: #34d399; }
        [data-theme="dark"] .btn-act-edit  { background: rgba(246,175,35,.14); color: #fbbf24; }
        [data-theme="dark"] .btn-act-del   { background: rgba(239,68,68,.14);  color: #f87171; }
        [data-theme="dark"] .btn-act-info  { background: rgba(59,130,246,.14); color: #60a5fa; }

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

        /* ============================================================
           FILTER CARD — unified look for filter panels
        ============================================================ */
        .filter-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: var(--radius-card);
            padding: 18px 20px;
            margin-bottom: 20px;
        }
        .filter-card .input-group-text {
            border-radius: 10px 0 0 10px;
            font-size: 13px;
        }
        .filter-card .form-control,
        .filter-card .form-select {
            font-size: 13px;
        }

        /* ============================================================
           PROGRESS BAR — brand gradient
        ============================================================ */
        .progress-brand .progress-bar {
            background: linear-gradient(90deg, #260632, #c84ddf);
        }

        /* ============================================================
           CHIP / FILTER TAG
        ============================================================ */
        .chip {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            background: rgba(200,77,223,.1);
            color: var(--primary);
            border: 1px solid rgba(200,77,223,.2);
            cursor: pointer;
            transition: all var(--transition);
        }
        .chip:hover { background: rgba(200,77,223,.18); }
        .chip.active { background: var(--primary); color: white; border-color: transparent; }

        /* ============================================================
           DIVIDER WITH LABEL
        ============================================================ */
        .divider-label {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 20px 0;
            color: var(--text-muted);
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .08em;
        }
        .divider-label::before, .divider-label::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--card-border);
        }

        /* ============================================================
           AVATAR STACK
        ============================================================ */
        .avatar-stack { display: flex; }
        .avatar-stack img, .avatar-stack .avatar-item {
            width: 28px; height: 28px;
            border-radius: 50%;
            border: 2px solid var(--card-bg);
            margin-left: -8px;
            object-fit: cover;
        }
        .avatar-stack img:first-child, .avatar-stack .avatar-item:first-child { margin-left: 0; }

        /* ============================================================
           VERTICAL TIMELINE — activity log style
        ============================================================ */
        .timeline { position: relative; }
        .timeline::before {
            content: '';
            position: absolute;
            left: 16px; top: 0; bottom: 0;
            width: 2px;
            background: linear-gradient(180deg, var(--primary), transparent);
            opacity: .2;
        }
        .timeline-item {
            display: flex;
            gap: 14px;
            margin-bottom: 20px;
            position: relative;
        }
        .timeline-dot {
            width: 34px; height: 34px;
            border-radius: 50%;
            flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
            font-size: 14px;
            z-index: 1;
            background: rgba(200,77,223,.1);
            border: 2px solid rgba(200,77,223,.2);
            color: var(--primary);
        }
        .timeline-content { flex: 1; min-width: 0; }

        /* ============================================================
           DATA LABEL — key:value pairs in detail panels
        ============================================================ */
        .data-label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .07em;
            color: var(--text-muted);
            margin-bottom: 3px;
        }
        .data-value {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-primary);
        }

        /* ============================================================
           ICON BADGE — round icon with brand glow
        ============================================================ */
        .icon-badge {
            width: 44px; height: 44px;
            border-radius: 13px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }
        .icon-badge-primary { background: rgba(200,77,223,.12); color: var(--primary); }
        .icon-badge-success { background: rgba(16,185,129,.12); color: #10b981; }
        .icon-badge-warning { background: rgba(246,175,35,.12); color: #e09000; }
        .icon-badge-danger  { background: rgba(239,68,68,.12);  color: #ef4444; }
        .icon-badge-info    { background: rgba(2,132,199,.12);  color: #0284c7; }

        /* ============================================================
           SMOOTH PAGE TRANSITIONS
        ============================================================ */
        a:not([href^="#"]):not([href^="javascript"]):not([download]):not([target="_blank"]) {
            transition: color var(--transition);
        }

        /* ============================================================
           MOBILE — improve stat cards on very small screens
        ============================================================ */
        @media (max-width: 360px) {
            .stat-value { font-size: 17px !important; }
            .stat-icon { width: 38px !important; height: 38px !important; font-size: 15px !important; }
        }

        /* ============================================================
           DARK MODE — Filter card
        ============================================================ */
        [data-theme="dark"] .filter-card {
            background: var(--card-bg);
            border-color: var(--card-border);
        }

        /* ============================================================
           QUICK DASH LINK — shared across views
        ============================================================ */
        .quick-dash, .quick-owner-link, .quick-link-card {
            transition: transform .22s cubic-bezier(.22,1,.36,1), box-shadow .22s cubic-bezier(.22,1,.36,1);
        }
        .quick-dash:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0,0,0,.10);
        }
        .quick-owner-link:hover {
            transform: translateX(4px);
            box-shadow: 0 4px 16px rgba(0,0,0,.08);
        }
        .quick-link-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 30px rgba(200,77,223,.15);
        }

        /* ============================================================
           ENHANCED CARD HOVER — lift + glow
        ============================================================ */
        .dashboard-card {
            transition: box-shadow .3s cubic-bezier(.22,1,.36,1), transform .3s cubic-bezier(.22,1,.36,1), border-color .3s;
        }
        .dashboard-card:hover {
            box-shadow: 0 12px 40px rgba(200,77,223,.12), 0 2px 8px rgba(0,0,0,.06);
            transform: translateY(-2px);
        }
        /* Cards that are header banners (gradient bg) should not lift */
        .dashboard-card[style*="linear-gradient"]:hover {
            transform: none;
            box-shadow: var(--shadow-md);
        }

        /* ============================================================
           STAT CARD — enhanced hover
        ============================================================ */
        .stat-card {
            transition: box-shadow .3s cubic-bezier(.22,1,.36,1), transform .3s cubic-bezier(.22,1,.36,1);
        }
        .stat-card:hover {
            box-shadow: 0 10px 32px rgba(200,77,223,.13);
            transform: translateY(-2px);
        }

        /* ============================================================
           GRADIENT TEXT UTILITY
        ============================================================ */
        .text-gradient {
            background: linear-gradient(135deg, #c84ddf, #68117e);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .text-gradient-gold {
            background: linear-gradient(135deg, #f6af23, #e09000);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* ============================================================
           STAGGERED TABLE ROW ENTRANCE
        ============================================================ */
        @keyframes rowSlideIn {
            from { opacity: 0; transform: translateX(-8px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        .table-animate tbody tr {
            animation: rowSlideIn .3s cubic-bezier(.22,1,.36,1) both;
        }
        .table-animate tbody tr:nth-child(1)  { animation-delay: .02s; }
        .table-animate tbody tr:nth-child(2)  { animation-delay: .05s; }
        .table-animate tbody tr:nth-child(3)  { animation-delay: .08s; }
        .table-animate tbody tr:nth-child(4)  { animation-delay: .11s; }
        .table-animate tbody tr:nth-child(5)  { animation-delay: .14s; }
        .table-animate tbody tr:nth-child(6)  { animation-delay: .17s; }
        .table-animate tbody tr:nth-child(7)  { animation-delay: .20s; }
        .table-animate tbody tr:nth-child(8)  { animation-delay: .23s; }
        .table-animate tbody tr:nth-child(9)  { animation-delay: .26s; }
        .table-animate tbody tr:nth-child(10) { animation-delay: .29s; }

        /* ============================================================
           FLOATING LABEL INPUT GROUP
        ============================================================ */
        .form-floating-brand {
            position: relative;
        }
        .form-floating-brand label {
            position: absolute;
            top: 50%;
            left: 14px;
            transform: translateY(-50%);
            font-size: 13px;
            color: var(--text-muted);
            pointer-events: none;
            transition: all .2s cubic-bezier(.22,1,.36,1);
            background: transparent;
            padding: 0 4px;
        }
        .form-floating-brand .form-control:focus ~ label,
        .form-floating-brand .form-control:not(:placeholder-shown) ~ label {
            top: 0;
            font-size: 10.5px;
            color: var(--primary);
            background: var(--card-bg);
            font-weight: 600;
        }

        /* ============================================================
           BUTTON LOADING STATE
        ============================================================ */
        .btn-loading {
            position: relative;
            pointer-events: none;
            color: transparent !important;
        }
        .btn-loading::after {
            content: '';
            position: absolute;
            top: 50%; left: 50%;
            width: 16px; height: 16px;
            margin: -8px 0 0 -8px;
            border: 2px solid rgba(255,255,255,.4);
            border-top-color: white;
            border-radius: 50%;
            animation: btnSpin .6s linear infinite;
        }
        @keyframes btnSpin { to { transform: rotate(360deg); } }

        /* ============================================================
           BADGE PULSE — for notification/alert badges
        ============================================================ */
        @keyframes badgePulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(239,68,68,.4); }
            50%       { box-shadow: 0 0 0 6px rgba(239,68,68,.0); }
        }
        .badge-pulse { animation: badgePulse 2s ease-in-out infinite; }

        /* ============================================================
           MODAL — slide from bottom on mobile
        ============================================================ */
        @media (max-width: 576px) {
            .modal-dialog {
                margin: 0 !important;
                position: fixed !important;
                bottom: 0 !important;
                left: 0 !important;
                right: 0 !important;
                max-width: 100% !important;
                transform: translateY(20px) !important;
                transition: transform .35s cubic-bezier(.22,1,.36,1) !important;
            }
            .modal.show .modal-dialog {
                transform: translateY(0) !important;
            }
            .modal-content {
                border-radius: 20px 20px 0 0 !important;
            }
            .modal-header {
                border-radius: 20px 20px 0 0 !important;
            }
        }

        /* ============================================================
           FORM VALIDATION STATES — visual feedback
        ============================================================ */
        .form-control.is-valid, .form-select.is-valid {
            border-color: #10b981 !important;
            box-shadow: 0 0 0 3px rgba(16,185,129,.12) !important;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12'%3E%3Cpath fill='%2310b981' d='M10.28 1.28L3.989 7.575 1.695 5.28A1 1 0 00.28 6.695l3 3a1 1 0 001.414 0l7-7A1 1 0 0010.28 1.28z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 14px;
            padding-right: 36px;
        }
        .form-control.is-invalid, .form-select.is-invalid {
            border-color: #ef4444 !important;
            box-shadow: 0 0 0 3px rgba(239,68,68,.12) !important;
        }
        .invalid-feedback {
            font-size: 12px;
            font-weight: 600;
            color: #ef4444;
        }

        /* ============================================================
           AUTO-DISMISS ALERT PROGRESS BAR
        ============================================================ */
        .alert-auto-dismiss {
            position: relative;
            overflow: hidden;
        }
        .alert-auto-dismiss::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0;
            height: 3px;
            background: currentColor;
            opacity: .3;
            animation: alertProgress 5s linear forwards;
        }
        @keyframes alertProgress { from { width: 100%; } to { width: 0%; } }

        /* ============================================================
           EMPTY STATE — universal empty state component
        ============================================================ */
        .empty-state {
            text-align: center;
            padding: 60px 24px;
        }
        .empty-state-icon {
            font-size: 3.5rem;
            opacity: .18;
            display: block;
            margin-bottom: 20px;
            color: var(--primary);
        }
        .empty-state-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 8px;
            letter-spacing: -.01em;
        }
        .empty-state-desc {
            font-size: 13px;
            color: var(--text-muted);
            max-width: 320px;
            margin: 0 auto 20px;
            line-height: 1.6;
        }

        /* ============================================================
           SELECT — custom arrow
        ============================================================ */
        .form-select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath fill='%23c84ddf' d='M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 01.753 1.659l-4.796 5.48a1 1 0 01-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right .75rem center;
            background-size: 12px;
            padding-right: 2.25rem;
        }
        [data-theme="dark"] .form-select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath fill='%23e8b4f5' d='M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 01.753 1.659l-4.796 5.48a1 1 0 01-1.506 0z'/%3E%3C/svg%3E");
        }

        /* ============================================================
           BREADCRUMB — brand style
        ============================================================ */
        .breadcrumb-item + .breadcrumb-item::before {
            content: "›";
            color: var(--text-muted);
        }
        .breadcrumb-item a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            font-size: 12.5px;
        }
        .breadcrumb-item.active { color: var(--text-muted); font-size: 12.5px; }

        /* ============================================================
           TOOLTIP — custom brand style
        ============================================================ */
        [data-bs-toggle="tooltip"] { cursor: default; }
        .tooltip-inner {
            background: #260632 !important;
            color: white !important;
            font-size: 12px;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 8px;
        }
        .tooltip .tooltip-arrow::before { border-top-color: #260632 !important; }

        /* ============================================================
           PRINT STYLES
        ============================================================ */
        @media print {
            .sidebar, .topbar, .mobile-bottom-nav, #scrollTop,
            #globalToastWrap, #navProgress, .no-print { display: none !important; }
            .main-content { margin: 0 !important; }
            .dashboard-card { box-shadow: none !important; break-inside: avoid; }
        }

        /* ============================================================
           PREFERS-REDUCED-MOTION — accessibility
        ============================================================ */
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: .01ms !important;
            }
            .fade-up { opacity: 1 !important; transform: none !important; }
        }

        /* ============================================================
           LARGE SCREEN (1441px+) — max content width + generous padding
        ============================================================ */
        @media (min-width: 1441px) {
            .content-wrapper { padding: 28px 36px; }
            .dashboard-card  { padding: 28px; }
            .stat-card       { padding: 24px; }
        }

        /* ============================================================
           TABLET (481px – 1024px) — tighter layout adjustments
        ============================================================ */
        @media (min-width: 481px) and (max-width: 1024px) {
            .stat-value { font-size: 24px; }
            .row.g-3 > [class*='col-6'] { margin-bottom: 2px; }
        }

        /* ============================================================
           CARD GRID — equal height helper
        ============================================================ */
        .card-grid { display: grid; gap: 16px; }
        .card-grid-2 { grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); }
        .card-grid-3 { grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); }
        .card-grid-4 { grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); }

        /* ============================================================
           SCROLL SNAP — horizontal card scroll on mobile
        ============================================================ */
        .scroll-snap-x {
            display: flex;
            gap: 12px;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            -webkit-overflow-scrolling: touch;
            padding-bottom: 8px;
        }
        .scroll-snap-x::-webkit-scrollbar { height: 3px; }
        .scroll-snap-x > * {
            scroll-snap-align: start;
            flex-shrink: 0;
        }

        /* ============================================================
           NAV LINK ACTIVE INDICATOR — animated underline
        ============================================================ */
        .nav-link-underline {
            position: relative;
            padding-bottom: 6px;
        }
        .nav-link-underline::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0;
            height: 2px;
            width: 0;
            background: var(--primary);
            border-radius: 2px;
            transition: width .3s cubic-bezier(.22,1,.36,1);
        }
        .nav-link-underline.active::after,
        .nav-link-underline:hover::after { width: 100%; }

        /* ============================================================
           GLASSMORPHISM CARD — premium variant
        ============================================================ */
        .glass-card {
            background: rgba(255,255,255,.07);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,.12);
            border-radius: var(--radius-card);
        }
        [data-theme="dark"] .glass-card {
            background: rgba(255,255,255,.04);
            border-color: rgba(255,255,255,.08);
        }

        /* ============================================================
           PAGINATION — brand-themed page links
        ============================================================ */
        .pagination { gap: 4px; }
        .page-item .page-link {
            border-radius: 10px !important;
            border: 1.5px solid var(--card-border);
            color: var(--text-muted);
            background: var(--card-bg);
            font-weight: 600;
            font-size: 13px;
            padding: 6px 13px;
            transition: all var(--transition);
            min-width: 36px;
            text-align: center;
        }
        .page-item .page-link:hover {
            background: rgba(200,77,223,.08);
            border-color: rgba(200,77,223,.3);
            color: var(--primary);
        }
        .page-item.active .page-link {
            background: linear-gradient(135deg, #68117e, #c84ddf);
            border-color: transparent;
            color: white;
            box-shadow: 0 4px 12px rgba(200,77,223,.35);
        }
        .page-item.disabled .page-link {
            opacity: .4;
            pointer-events: none;
        }
        [data-theme="dark"] .page-item .page-link {
            background: var(--card-bg);
            color: var(--text-muted);
        }

        /* ============================================================
           INPUT GROUP — seamless search bar look
        ============================================================ */
        .input-group .input-group-text {
            background: var(--input-bg);
            border: 1.5px solid var(--card-border);
            color: var(--text-muted);
        }
        .input-group .form-control:focus ~ .input-group-text,
        .input-group:focus-within .input-group-text {
            border-color: var(--primary);
        }
        .input-group:focus-within .form-control,
        .input-group:focus-within .input-group-text {
            border-color: var(--primary);
        }

        /* ============================================================
           TABLE HOVER ROW — CSS only, removes inline onmouseover need
        ============================================================ */
        .table-hover tbody tr { transition: background var(--transition); }
        .table-hover tbody tr:hover { background: rgba(104,17,126,.04) !important; }

        /* ============================================================
           CARD — top colour strip via data-topcolor
        ============================================================ */
        .stat-card[data-topcolor] {
            border-top: 3px solid;
        }
        .stat-card[data-topcolor="primary"]  { border-top-color: var(--primary); }
        .stat-card[data-topcolor="success"]  { border-top-color: var(--success); }
        .stat-card[data-topcolor="warning"]  { border-top-color: var(--warning); }
        .stat-card[data-topcolor="danger"]   { border-top-color: var(--danger); }
        .stat-card[data-topcolor="info"]     { border-top-color: #0284c7; }

        /* ============================================================
           MODAL HEADER GRADIENT — auto white close btn
        ============================================================ */
        .modal-header-gradient {
            background: linear-gradient(135deg, #260632, #461256, #c84ddf);
            border-radius: 20px 20px 0 0;
            padding: 20px 24px;
        }
        .modal-header-gradient .modal-title { color: white; font-weight: 700; }
        .modal-header-gradient .btn-close { filter: brightness(0) invert(1); opacity: .85; }

        /* ============================================================
           SELECTION HIGHLIGHT — brand-tinted text selection
        ============================================================ */
        ::selection { background: rgba(200,77,223,.22); color: inherit; }

        /* ============================================================
           SIDEBAR SCROLLBAR — thin custom track
        ============================================================ */
        .sidebar-nav::-webkit-scrollbar { width: 3px; }
        .sidebar-nav::-webkit-scrollbar-track { background: transparent; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,.12); border-radius: 3px; }
        .sidebar-nav::-webkit-scrollbar-thumb:hover { background: rgba(200,77,223,.35); }

        /* ============================================================
           TOPBAR — scrolled state border glow
        ============================================================ */
        .topbar { transition: box-shadow .25s, border-color .25s; }
        .topbar.scrolled {
            box-shadow: 0 2px 20px rgba(0,0,0,.08);
            border-bottom-color: rgba(200,77,223,.18) !important;
        }
        [data-theme="dark"] .topbar.scrolled {
            box-shadow: 0 2px 20px rgba(0,0,0,.35);
            border-bottom-color: rgba(200,77,223,.22) !important;
        }

        /* ============================================================
           FOCUS RING — brand-coloured keyboard focus
        ============================================================ */
        :focus-visible {
            outline: 2px solid rgba(200,77,223,.7) !important;
            outline-offset: 2px !important;
            box-shadow: none !important;
        }
        .btn:focus-visible {
            outline: 2px solid rgba(200,77,223,.7) !important;
            outline-offset: 3px !important;
        }

        /* ============================================================
           STAT CARD — enhanced stagger via nth-child when in .row
        ============================================================ */
        .row > *:nth-child(1) .stat-card { transition-delay: .00s; }
        .row > *:nth-child(2) .stat-card { transition-delay: .04s; }
        .row > *:nth-child(3) .stat-card { transition-delay: .08s; }
        .row > *:nth-child(4) .stat-card { transition-delay: .12s; }

        /* ============================================================
           DASHBOARD CARD — content section separator
        ============================================================ */
        .card-section-title {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .07em;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 12px;
        }

        /* ============================================================
           TABLE — zebra stripe very subtle, only in light mode
        ============================================================ */
        [data-theme="light"] .table-striped > tbody > tr:nth-of-type(odd) > * {
            background-color: rgba(200,77,223,.025);
        }

        /* ============================================================
           BADGE — animated new indicator
        ============================================================ */
        .badge-new {
            position: relative;
        }
        .badge-new::after {
            content: '';
            position: absolute;
            top: -3px; right: -3px;
            width: 7px; height: 7px;
            background: #ef4444;
            border-radius: 50%;
            border: 1.5px solid var(--card-bg);
            animation: badgePulse 2s ease-in-out infinite;
        }

        /* ============================================================
           SKELETON LOADER — improved shimmer direction
        ============================================================ */
        .skeleton, .placeholder {
            background: linear-gradient(90deg,
                var(--card-border) 25%,
                rgba(200,77,223,.07) 50%,
                var(--card-border) 75%);
            background-size: 200% 100%;
            animation: skeletonShimmer 1.4s ease-in-out infinite;
            border-radius: 6px;
            color: transparent !important;
            pointer-events: none;
            user-select: none;
        }
        @keyframes skeletonShimmer {
            0%   { background-position: 200% center; }
            100% { background-position: -200% center; }
        }

        /* ============================================================
           AVATAR RING — online indicator utility
        ============================================================ */
        .avatar-online {
            position: relative;
            display: inline-block;
        }
        .avatar-online::after {
            content: '';
            position: absolute;
            bottom: 2px; right: 2px;
            width: 10px; height: 10px;
            background: #10b981;
            border-radius: 50%;
            border: 2px solid var(--card-bg);
        }

        /* ============================================================
           IMPROVED DARK MODE — card/table borders more visible
        ============================================================ */
        [data-theme="dark"] .table > :not(caption) > * > * {
            border-bottom-color: rgba(255,255,255,.06);
        }
        [data-theme="dark"] .table thead th {
            background: rgba(255,255,255,.04);
        }

        /* ============================================================
           DARK MODE — placeholder text visibility
        ============================================================ */
        [data-theme="dark"] .form-control::placeholder,
        [data-theme="dark"] .form-select::placeholder { color: rgba(180,140,200,.45); }

        /* ============================================================
           BADGE — consistent soft variants matching icon classes
        ============================================================ */
        .badge.bg-primary-subtle { background: rgba(200,77,223,.15) !important; color: #9b1ab5 !important; }
        .badge.bg-success-subtle { background: rgba(16,185,129,.15) !important; color: #059669 !important; }
        .badge.bg-warning-subtle { background: rgba(246,175,35,.15)  !important; color: #b45309 !important; }
        .badge.bg-danger-subtle  { background: rgba(239,68,68,.15)   !important; color: #dc2626 !important; }
        .badge.bg-info-subtle    { background: rgba(2,132,199,.15)   !important; color: #0369a1 !important; }
        [data-theme="dark"] .badge.bg-primary-subtle { background: rgba(200,77,223,.2) !important; color: #d97df5 !important; }
        [data-theme="dark"] .badge.bg-success-subtle { background: rgba(16,185,129,.2) !important; color: #34d399 !important; }
        [data-theme="dark"] .badge.bg-warning-subtle { background: rgba(246,175,35,.2) !important; color: #fcd34d !important; }
        [data-theme="dark"] .badge.bg-danger-subtle  { background: rgba(239,68,68,.2)  !important; color: #f87171 !important; }
        [data-theme="dark"] .badge.bg-info-subtle    { background: rgba(2,132,199,.2)  !important; color: #38bdf8 !important; }

        /* ============================================================
           EMPTY STATE — reusable utility class
        ============================================================ */
        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem 2rem;
            text-align: center;
        }
        .empty-state-icon {
            width: 72px; height: 72px;
            border-radius: 20px;
            background: rgba(200,77,223,.08);
            display: flex; align-items: center; justify-content: center;
            font-size: 2rem;
            color: #c84ddf;
            margin-bottom: 1.25rem;
        }
        .empty-state-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: .4rem;
        }
        .empty-state-desc {
            font-size: 13px;
            color: var(--text-muted);
            max-width: 280px;
            line-height: 1.55;
        }

        /* ============================================================
           LOADING BUTTON STATE
        ============================================================ */
        .btn.is-loading {
            pointer-events: none;
            opacity: .75;
        }
        .btn.is-loading::after {
            content: '';
            display: inline-block;
            width: .8em; height: .8em;
            border: 2px solid rgba(255,255,255,.6);
            border-right-color: transparent;
            border-radius: 50%;
            animation: spin .6s linear infinite;
            margin-left: .5em;
            vertical-align: middle;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* ============================================================
           PRINT — hide sidebar, topbar, bottom-nav; full width content
        ============================================================ */
        @media print {
            .sidebar, .topbar, .mobile-bottom-nav,
            #globalToastWrap, #cmdOverlay,
            .btn-action-group, .filters-bar { display: none !important; }
            .main-content { margin-left: 0 !important; }
            .content-wrapper { padding: 0 !important; }
            .dashboard-card { box-shadow: none !important; border: 1px solid #e5e7eb !important; }
            body { background: white !important; }
        }

        /* ============================================================
           LIVE SCHEDULE INDICATOR PULSE
        ============================================================ */
        @keyframes pulseDot {
            0%, 100% { opacity: 1; }
            50%       { opacity: .45; }
        }
        .live-dot {
            display: inline-block;
            width: 7px; height: 7px;
            border-radius: 50%;
            background: #10b981;
            animation: pulseDot 1.5s ease-in-out infinite;
            flex-shrink: 0;
        }

        /* ============================================================
           QUICK ACTION CARD — global hover
        ============================================================ */
        .quick-action-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(200,77,223,.12) !important;
            border-color: rgba(200,77,223,.3) !important;
            text-decoration: none;
        }

        /* ============================================================
           FORM INPUT — consistent brand focus ring
        ============================================================ */
        .form-control:focus, .form-select:focus, textarea.form-control:focus {
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 3px rgba(200,77,223,.12) !important;
        }

        /* ============================================================
           TABLE SCROLL WRAP — fade edge auto-detect
        ============================================================ */
        .table-scroll-wrap.has-overflow::after { opacity: 1; }

        /* ============================================================
           MOBILE — schedule time column compact
        ============================================================ */
        @media (max-width: 480px) {
            .schedule-time-col { min-width: 38px !important; font-size: 10.5px !important; }
        }

        /* ============================================================
           SECTION TITLE — refined left-bar accent
        ============================================================ */
        .section-title i { flex-shrink: 0; }

        /* ============================================================
           NOTIFICATION PANEL — dark mode
        ============================================================ */
        [data-theme="dark"] #notifPanel {
            background: var(--card-bg);
            border-color: var(--card-border);
        }

        /* ============================================================
           INPUT[TYPE=NUMBER] — remove browser spin arrows for clean UI
        ============================================================ */
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
        input[type="number"] { -moz-appearance: textfield; }

        /* ============================================================
           AUTOFILL — override browser yellow background on inputs
        ============================================================ */
        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus,
        textarea:-webkit-autofill,
        select:-webkit-autofill {
            -webkit-box-shadow: 0 0 0 1000px var(--input-bg) inset !important;
            -webkit-text-fill-color: var(--text-primary) !important;
            transition: background-color 5000s ease-in-out 0s;
        }

        /* ============================================================
           MOBILE TOUCH TARGETS — minimum 44×44px interactive areas
        ============================================================ */
        @media (max-width: 992px) {
            .nav-link { min-height: 44px; }
            .mob-nav-item { min-height: 56px; }
            .btn-sm { min-height: 36px; }
        }

        /* ============================================================
           CARD LINK DECORATION — remove underline on card anchors
        ============================================================ */
        a.dashboard-card, a.stat-card, a.quick-action-card {
            text-decoration: none;
            color: var(--text-primary);
        }

        /* ============================================================
           DROPDOWN MENU — improved shadow & border
        ============================================================ */
        .dropdown-menu {
            border: 1px solid var(--card-border) !important;
            box-shadow: 0 8px 32px rgba(0,0,0,.12), 0 2px 8px rgba(38,6,50,.06) !important;
            border-radius: 14px !important;
            padding: 6px !important;
            font-size: 13.5px;
        }
        .dropdown-item {
            border-radius: 10px !important;
            padding: 8px 14px !important;
            font-weight: 500;
            transition: background var(--transition), color var(--transition);
        }
        .dropdown-item:hover {
            background: rgba(200,77,223,.08) !important;
            color: var(--primary) !important;
        }
        .dropdown-item.text-danger:hover {
            background: rgba(239,68,68,.08) !important;
            color: #dc2626 !important;
        }
        [data-theme="dark"] .dropdown-menu {
            box-shadow: 0 8px 32px rgba(0,0,0,.35), 0 2px 8px rgba(0,0,0,.2) !important;
        }

        /* ============================================================
           NAV PROGRESS BAR — brand gradient
        ============================================================ */
        #navProgress {
            position: fixed;
            top: 0; left: 0;
            height: 3px;
            background: linear-gradient(90deg, #68117e, #c84ddf, #f6af23);
            z-index: 9999;
            width: 0%;
            opacity: 0;
            transition: opacity .2s, width .1s linear;
            border-radius: 0 3px 3px 0;
            box-shadow: 0 0 8px rgba(200,77,223,.5);
            pointer-events: none;
        }
        #navProgress.active { opacity: 1; }

        /* ============================================================
           SIDEBAR LINK — active item left bar with rounded cap
        ============================================================ */
        .nav-link.active::before {
            border-radius: 0 3px 3px 0 !important;
        }

        /* ============================================================
           INPUT GROUP TEXT — seamless with form-control border
        ============================================================ */
        .form-control + .input-group-text,
        .input-group-text + .form-control {
            border-radius: 0;
        }
        .input-group .form-control:last-child,
        .input-group .input-group-text:last-child {
            border-radius: 0 var(--radius-input) var(--radius-input) 0;
        }
        .input-group .form-control:first-child,
        .input-group .input-group-text:first-child {
            border-radius: var(--radius-input) 0 0 var(--radius-input);
        }

        /* ============================================================
           RESPONSIVE TABLE — improved horizontal scroll on mobile
        ============================================================ */
        @media (max-width: 768px) {
            .table-responsive { border-radius: var(--radius-card); }
            .table-responsive .table { margin-bottom: 0; }
            .table td, .table th { white-space: nowrap; }
            /* Exceptions: allow wrapping for description columns */
            .table td.text-wrap, .table th.text-wrap { white-space: normal; }
        }

        /* ============================================================
           CARD FOOTER — consistent border + background
        ============================================================ */
        .card-footer-subtle {
            background: var(--input-bg);
            border-top: 1px solid var(--card-border);
            border-radius: 0 0 var(--radius-card) var(--radius-card);
            padding: 12px 20px;
            font-size: 12.5px;
            color: var(--text-muted);
        }

    </style>

    @stack('styles')
    <script>!function(){var t=localStorage.getItem('theme');if(t==='dark'||t==='light')document.documentElement.setAttribute('data-theme',t);}();</script>
</head>
<body>
    @if(session()->has('impersonate.original_user'))
        <div class="impersonate-banner">
            <i class="bi bi-person-fill-gear me-2"></i>
            Anda sedang <strong>mengakses sebagai admin cabang</strong>.
            <form method="POST" action="{{ route('impersonate.leave') }}" style="display:inline;margin-left:10px">
                @csrf
                <button class="btn btn-sm btn-danger" style="border-radius:8px;font-size:12px;padding:3px 12px">
                    <i class="bi bi-arrow-left-circle me-1"></i>Kembali ke Pusat
                </button>
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
            <a href="{{ route('admin.courses.index') }}" class="nav-link {{ (request()->routeIs('admin.courses.*') && !request()->routeIs('admin.courses.fees*')) ? 'active' : '' }}" data-label="Mata Pelajaran">
                <i class="bi bi-journal-bookmark"></i>
                <span>Mata Pelajaran</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.courses.fees') }}" class="nav-link {{ request()->routeIs('admin.courses.fees*') ? 'active' : '' }}" data-label="Biaya Mapel">
                <i class="bi bi-tag"></i>
                <span>Biaya Mapel</span>
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
        @if(auth()->check() && auth()->user()->hasAnyRole(['admin','owner']))
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
        @endif

        <div class="nav-header">LANDING PAGE</div>
        <div class="nav-item">
            <a href="{{ route('admin.landing.index') }}" class="nav-link {{ request()->routeIs('admin.landing.*') ? 'active' : '' }}" data-label="Kelola Landing Page">
                <i class="bi bi-window-fullscreen"></i>
                <span>Kelola Landing Page</span>
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
            <a href="{{ route('guru.classes.index') }}" class="nav-link {{ request()->routeIs('guru.classes.*') ? 'active' : '' }}" data-label="Kelas">
                <i class="bi bi-diagram-3"></i><span>Kelas</span>
            </a>
        </div>
        
        <div class="nav-item">
            <a href="{{ route('guru.attendance.history') }}" class="nav-link {{ request()->routeIs('guru.attendance.*') ? 'active' : '' }}" data-label="Riwayat Absensi">
                <i class="bi bi-clipboard2-check"></i><span>Riwayat Absensi</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('guru.schedules.index') }}" class="nav-link {{ request()->routeIs('guru.schedules.*') ? 'active' : '' }}" data-label="Jadwal Saya">
                <i class="bi bi-calendar3"></i><span>Jadwal Saya</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('guru.payments.index') }}" class="nav-link {{ request()->routeIs('guru.payments.*') ? 'active' : '' }}" data-label="Gaji Saya">
                <i class="bi bi-cash-coin"></i><span>Gaji Saya</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('guru.messages.index') }}" class="nav-link {{ request()->routeIs('guru.messages.*') ? 'active' : '' }}" data-label="Pesan">
                <i class="bi bi-chat-dots"></i><span>Pesan</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('guru.announcements') }}" class="nav-link {{ request()->routeIs('guru.announcements') ? 'active' : '' }}" data-label="Pengumuman">
                <i class="bi bi-megaphone"></i><span>Pengumuman</span>
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
            <a href="{{ route('siswa.schedules.index') }}" class="nav-link {{ request()->routeIs('siswa.schedules.*') ? 'active' : '' }}" data-label="Jadwal Pertemuan">
                <i class="bi bi-calendar-check"></i><span>Jadwal Pertemuan</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('siswa.courses.index') }}" class="nav-link {{ request()->routeIs('siswa.courses.index') ? 'active' : '' }}" data-label="List Mata Pelajaran">
                <i class="bi bi-journal-bookmark"></i><span>List Mata Pelajaran</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('siswa.courses.fees') }}" class="nav-link {{ request()->routeIs('siswa.courses.fees') ? 'active' : '' }}" data-label="Harga Mapel">
                <i class="bi bi-cash-coin"></i><span>Harga Mapel</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('siswa.attendance') }}" class="nav-link {{ request()->routeIs('siswa.attendance*') ? 'active' : '' }}" data-label="Absensi">
                <i class="bi bi-clipboard-check"></i><span>Absensi</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('siswa.billing.index') }}" class="nav-link {{ request()->routeIs('siswa.billing.*') ? 'active' : '' }}" data-label="Tagihan">
                <i class="bi bi-credit-card"></i><span>Tagihan & Bayar</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('siswa.certificates.index') }}" class="nav-link {{ request()->routeIs('siswa.certificates.*') ? 'active' : '' }}" data-label="Sertifikat Saya">
                <i class="bi bi-award"></i><span>Sertifikat Saya</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('siswa.announcements') }}" class="nav-link {{ request()->routeIs('siswa.announcements') ? 'active' : '' }}" data-label="Pengumuman">
                <i class="bi bi-megaphone"></i><span>Pengumuman</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('siswa.tryout') }}" class="nav-link {{ request()->routeIs('siswa.tryout*') ? 'active' : '' }}" data-label="Tryout CBT">
                <i class="bi bi-laptop"></i><span>Tryout CBT</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('siswa.messages.index') }}" class="nav-link {{ request()->routeIs('siswa.messages.*') ? 'active' : '' }}" data-label="Pesan">
                <i class="bi bi-chat-dots"></i><span>Pesan</span>
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

            {{-- Landing page home link --}}
            <a href="{{ url('/') }}" class="top-btn d-none d-md-flex" title="Beranda" style="text-decoration:none;align-items:center;">
                <i class="bi bi-house-door" style="font-size:15px"></i>
            </a>

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

            @php
            $notifAnnouncements = \App\Models\Announcement::where('status','aktif')
                ->where(function($q){ $q->whereNull('tanggal_mulai')->orWhere('tanggal_mulai','<=',now()); })
                ->where(function($q){ $q->whereNull('tanggal_selesai')->orWhere('tanggal_selesai','>=',now()); })
                ->orderByDesc('is_pinned')->orderByDesc('created_at')
                ->limit(6)->get(['id','judul','jenis','konten','is_pinned','created_at']);
            $notifCount = $notifAnnouncements->count();
            @endphp
            <button class="top-btn position-relative" title="Notifikasi" id="notifBtn" aria-label="Notifikasi">
                <i class="bi bi-bell"></i>
                @if($notifCount > 0)
                <span id="notifDot" style="position:absolute;top:5px;right:5px;width:8px;height:8px;background:#ef4444;border-radius:50%;border:1.5px solid var(--card-bg);pointer-events:none"></span>
                @endif
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
    <a href="{{ route('guru.classes.index') }}" class="mob-nav-item {{ request()->routeIs('guru.classes.*') ? 'active' : '' }}">
        <i class="bi bi-diagram-3"></i>
        <span>Kelas</span>
    </a>
    <a href="{{ route('guru.announcements') }}" class="mob-nav-item {{ request()->routeIs('guru.announcements') ? 'active' : '' }}">
        <i class="bi bi-megaphone"></i>
        <span>Pengumuman</span>
    </a>
    @endrole
    @role('siswa')
    {{-- Mobile nav: Jadwal siswa dihapus sesuai permintaan --}}
    <a href="{{ route('siswa.courses.index') }}" class="mob-nav-item {{ request()->routeIs('siswa.courses*') ? 'active' : '' }}">
        <i class="bi bi-journal-bookmark{{ request()->routeIs('siswa.courses*') ? '-fill' : '' }}"></i>
        <span>Mapel</span>
    </a>
    <a href="{{ route('siswa.courses.fees') }}" class="mob-nav-item {{ request()->routeIs('siswa.courses.fees') ? 'active' : '' }}">
        <i class="bi bi-cash-coin{{ request()->routeIs('siswa.courses.fees') ? '-fill' : '' }}"></i>
        <span>Harga</span>
    </a>
    <a href="{{ route('siswa.attendance') }}" class="mob-nav-item {{ request()->routeIs('siswa.attendance*') ? 'active' : '' }}">
        <i class="bi bi-clipboard-check"></i>
        <span>Absensi</span>
    </a>
    <a href="{{ route('siswa.announcements') }}" class="mob-nav-item {{ request()->routeIs('siswa.announcements') ? 'active' : '' }}">
        <i class="bi bi-megaphone{{ request()->routeIs('siswa.announcements') ? '-fill' : '' }}"></i>
        <span>Pengumuman</span>
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

{{-- CUSTOM CONFIRM DIALOG --}}
<div id="confirmOverlay">
    <div id="confirmBox">
        <div style="padding:24px 24px 0">
            <div id="confirmIconWrap" style="width:52px;height:52px;border-radius:16px;background:rgba(239,68,68,.12);display:flex;align-items:center;justify-content:center;font-size:22px;margin-bottom:16px;flex-shrink:0">
                <i class="bi bi-exclamation-triangle-fill" id="confirmIconEl" style="color:#ef4444"></i>
            </div>
            <div id="confirmTitle" style="font-size:17px;font-weight:700;color:var(--text-primary);margin-bottom:6px;font-family:'Plus Jakarta Sans',sans-serif;letter-spacing:-.02em">Konfirmasi</div>
            <div id="confirmMessage" style="font-size:13.5px;color:var(--text-muted);line-height:1.6;margin-bottom:24px"></div>
        </div>
        <div style="padding:0 24px 24px;display:flex;gap:10px;justify-content:flex-end">
            <button id="confirmCancelBtn" type="button" class="btn btn-light fw-semibold" style="border-radius:10px;font-size:13.5px">Batal</button>
            <button id="confirmOkBtn" type="button" class="btn btn-danger fw-semibold" style="border-radius:10px;font-size:13.5px">Ya, Lanjutkan</button>
        </div>
    </div>
</div>

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
    "success": {!! json_encode(session('success') ?? '') !!},
    "error":   {!! json_encode(session('error')   ?? '') !!},
    "warning": {!! json_encode(session('warning') ?? '') !!},
    "info":    {!! json_encode(session('info')    ?? '') !!}
}
</script>

{{-- SCRIPTS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
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

// ---- COUNT-UP ANIMATION (manual .count-up[data-target] handled by per-page scripts) ----
// ---- AUTO COUNT-UP — detects numeric .stat-value not already managed ----
document.addEventListener('DOMContentLoaded', function() {
    // Use data-auto-count to avoid interfering with per-page .count-up IOs
    document.querySelectorAll('.stat-value:not(.count-up):not(.no-countup):not([data-auto-count])').forEach(function(el) {
        const raw = el.textContent.trim().replace(/[.,\s]/g, '');
        const num = parseInt(raw, 10);
        if (!isNaN(num) && num > 0 && num <= 999999 && /^\d+$/.test(raw)) {
            el.setAttribute('data-auto-count', num);
            el.textContent = '0';
        }
    });

    const autoEls = document.querySelectorAll('.stat-value[data-auto-count]');
    if (!autoEls.length) return;

    const io2 = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (!entry.isIntersecting) return;
            const el = entry.target;
            if (el.hasAttribute('data-counted')) return;
            const target = parseInt(el.getAttribute('data-auto-count'), 10);
            if (isNaN(target)) return;
            el.setAttribute('data-counted', '1');
            const duration = 800;
            const step = 14;
            const increment = Math.max(1, target / (duration / step));
            let current = 0;
            const timer = setInterval(function() {
                current += increment;
                if (current >= target) { current = target; clearInterval(timer); }
                el.textContent = Math.floor(current).toLocaleString('id-ID');
            }, step);
            io2.unobserve(el);
        });
    }, { threshold: 0.2 });
    autoEls.forEach(function(el) { io2.observe(el); });
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

// ---- STAGGERED TABLE ROW ANIMATION ----
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.table-responsive table, table.table').forEach(tbl => {
        tbl.classList.add('table-animate');
    });
});

// ---- AUTO-DISMISS ALERTS ----
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.alert:not(.alert-permanent)').forEach(function(el) {
        el.classList.add('alert-auto-dismiss');
        setTimeout(function() {
            el.classList.add('fade');
            el.style.transition = 'opacity .5s, max-height .5s, margin .5s, padding .5s';
            el.style.opacity = '0';
            el.style.maxHeight = '0';
            el.style.margin = '0';
            el.style.padding = '0';
            setTimeout(() => el.remove(), 500);
        }, 6000);
    });
});

// ---- FORM VALIDATION ENHANCEMENT ----
document.addEventListener('DOMContentLoaded', function() {
    // Add real-time validation visual feedback
    document.querySelectorAll('form .form-control[required], form .form-select[required]').forEach(function(input) {
        input.addEventListener('blur', function() {
            if (this.value.trim()) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else {
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
            }
        });
        input.addEventListener('input', function() {
            if (this.classList.contains('is-invalid') && this.value.trim()) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }
        });
    });

    // Textarea character counter
    document.querySelectorAll('textarea[maxlength]').forEach(function(ta) {
        const max = parseInt(ta.getAttribute('maxlength'));
        const counter = document.createElement('div');
        counter.style.cssText = 'text-align:right;font-size:11px;color:var(--text-muted);margin-top:4px';
        counter.textContent = `0 / ${max}`;
        ta.after(counter);
        ta.addEventListener('input', function() {
            const len = this.value.length;
            counter.textContent = `${len} / ${max}`;
            counter.style.color = len > max * .85 ? '#ef4444' : 'var(--text-muted)';
        });
    });
});

// ---- CUSTOM CONFIRM DIALOG ----
window.confirmAction = function(message, onConfirm, onCancel, opts) {
    opts = opts || {};
    const overlay = document.getElementById('confirmOverlay');
    if (!overlay) {
        if (confirm(message)) { if (onConfirm) onConfirm(); } else { if (onCancel) onCancel(); }
        return;
    }
    document.getElementById('confirmMessage').innerHTML = message;
    document.getElementById('confirmTitle').textContent = opts.title || 'Konfirmasi';
    const okBtn = document.getElementById('confirmOkBtn');
    const cancelBtn = document.getElementById('confirmCancelBtn');
    okBtn.innerHTML = opts.okText || 'Ya, Lanjutkan';
    okBtn.className = 'btn fw-semibold ' + (opts.btnClass || 'btn-danger');
    okBtn.style.borderRadius = '10px'; okBtn.style.fontSize = '13.5px';
    cancelBtn.textContent = opts.cancelText || 'Batal';
    const iconEl   = document.getElementById('confirmIconEl');
    const iconWrap = document.getElementById('confirmIconWrap');
    if (opts.type === 'info') {
        iconWrap.style.background = 'rgba(200,77,223,.12)';
        iconEl.className = 'bi bi-info-circle-fill'; iconEl.style.color = '#c84ddf';
    } else if (opts.type === 'warning') {
        iconWrap.style.background = 'rgba(246,175,35,.12)';
        iconEl.className = 'bi bi-exclamation-triangle-fill'; iconEl.style.color = '#f6af23';
    } else {
        iconWrap.style.background = 'rgba(239,68,68,.12)';
        iconEl.className = 'bi bi-exclamation-triangle-fill'; iconEl.style.color = '#ef4444';
    }
    function doClose() {
        overlay.classList.remove('open');
        okBtn.onclick = null; cancelBtn.onclick = null; overlay.onclick = null;
    }
    okBtn.onclick     = function() { doClose(); if (onConfirm) onConfirm(); };
    cancelBtn.onclick = function() { doClose(); if (onCancel) onCancel(); };
    overlay.onclick   = function(e) { if (e.target === overlay) { doClose(); if (onCancel) onCancel(); } };
    overlay.classList.add('open');
};

// ---- COUNT-UP VALUE HELPER (for AJAX-loaded stat elements) ----
window.countUpValue = function(el, target) {
    if (!el) return;
    const num = parseInt(target, 10);
    if (isNaN(num) || num < 0) { el.textContent = target; return; }
    const duration = 600;
    const step = 14;
    const increment = Math.max(1, num / (duration / step));
    let current = 0;
    clearInterval(el._countTimer);
    el._countTimer = setInterval(function() {
        current += increment;
        if (current >= num) { current = num; clearInterval(el._countTimer); }
        el.textContent = Math.floor(current).toLocaleString('id-ID');
    }, step);
};

// ---- BUTTON LOADING STATE HELPER ----
window.setButtonLoading = function(btn, loading) {
    if (!btn) return;
    if (loading) {
        btn.setAttribute('data-original-text', btn.innerHTML);
        btn.classList.add('is-loading');
        btn.disabled = true;
    } else {
        const orig = btn.getAttribute('data-original-text');
        if (orig) btn.innerHTML = orig;
        btn.classList.remove('is-loading');
        btn.disabled = false;
    }
};

// ---- ENHANCED FADE-UP OBSERVER (individual elements with stagger) ----
(function() {
    const allFadeUps = document.querySelectorAll('.fade-up');
    if (!allFadeUps.length) return;
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, idx) => {
            if (entry.isIntersecting) {
                // Stagger siblings with same parent
                const siblings = entry.target.parentElement
                    ? Array.from(entry.target.parentElement.children).filter(el => el.classList.contains('fade-up'))
                    : [];
                const sibIdx = siblings.indexOf(entry.target);
                const delay = sibIdx >= 0 ? sibIdx * 0.05 : 0;
                setTimeout(() => entry.target.classList.add('in-view'), delay * 1000);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.08, rootMargin: '0px 0px -40px 0px' });
    allFadeUps.forEach(el => observer.observe(el));
})();

// ---- TOOLTIP INIT ----
document.addEventListener('DOMContentLoaded', function() {
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function(el) {
            new bootstrap.Tooltip(el, { trigger: 'hover' });
        });
    }
});

// ---- STICKY TABLE HEADER (scroll-aware) ----
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.table-responsive').forEach(function(wrap) {
        const thead = wrap.querySelector('thead');
        if (!thead) return;
        thead.style.position = 'sticky';
        thead.style.top = '0';
        thead.style.zIndex = '2';
    });
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

// ---- NOTIFICATION DROPDOWN (click → show panel with real announcements) ----
(function() {
    const btn = document.getElementById('notifBtn');
    if (!btn) return;

    const notifData = @json($notifAnnouncements ?? collect());
    const jenisMap = {
        info:   { icon:'bi-info-circle-fill', color:'#2563eb' },
        promo:  { icon:'bi-tag-fill',         color:'#f6af23' },
        penting:{ icon:'bi-exclamation-triangle-fill', color:'#ef4444' },
        update: { icon:'bi-arrow-up-circle-fill',       color:'#10b981' },
    };

    function timeAgo(ts) {
        const d = new Date(ts);
        const diff = Math.floor((Date.now() - d)/1000);
        if (diff < 60) return 'Baru saja';
        if (diff < 3600) return Math.floor(diff/60) + ' mnt lalu';
        if (diff < 86400) return Math.floor(diff/3600) + ' jam lalu';
        return Math.floor(diff/86400) + ' hari lalu';
    }

    btn.addEventListener('click', function(e) {
        e.stopPropagation();
        document.getElementById('notifDot')?.remove();
        const existing = document.getElementById('notifPanel');
        if (existing) { existing.remove(); return; }
        const rect = btn.getBoundingClientRect();
        const panel = document.createElement('div');
        panel.id = 'notifPanel';
        panel.style.cssText = `
            position:fixed; top:${rect.bottom + 8}px;
            right:${Math.max(8, document.documentElement.clientWidth - rect.right)}px;
            width:min(320px, calc(100vw - 16px)); background:var(--card-bg); border:1px solid var(--card-border);
            border-radius:16px; box-shadow:0 12px 40px rgba(0,0,0,.18);
            z-index:9998; overflow:hidden; animation:fadeIn .2s ease both;
        `;

        let itemsHtml = '';
        if (notifData.length === 0) {
            itemsHtml = `<div style="padding:24px 16px;text-align:center">
                <i class="bi bi-bell-slash" style="font-size:2.5rem;color:#cbd5e1;display:block;margin-bottom:8px"></i>
                <div style="font-size:13px;color:var(--text-muted)">Tidak ada pengumuman aktif</div>
            </div>`;
        } else {
            notifData.forEach(n => {
                const j = jenisMap[n.jenis] || { icon:'bi-megaphone-fill', color:'#68117e' };
                const pin = n.is_pinned ? '<i class="bi bi-pin-fill" style="color:#f6af23;font-size:10px;margin-left:4px"></i>' : '';
                const preview = (n.konten || '').replace(/<[^>]+>/g,'').slice(0, 60);
                itemsHtml += `<div style="padding:11px 16px;border-bottom:1px solid var(--card-border);display:flex;gap:12px;align-items:flex-start">
                    <div style="width:34px;height:34px;border-radius:10px;background:${j.color}18;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        <i class="bi ${j.icon}" style="color:${j.color};font-size:15px"></i>
                    </div>
                    <div style="flex:1;min-width:0">
                        <div style="font-size:13px;font-weight:600;color:var(--text-primary);line-height:1.3;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">${n.judul}${pin}</div>
                        ${preview ? `<div style="font-size:11.5px;color:var(--text-muted);margin-top:2px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">${preview}</div>` : ''}
                        <div style="font-size:10.5px;color:var(--text-muted);margin-top:3px">${timeAgo(n.created_at)}</div>
                    </div>
                </div>`;
            });
        }

        @php
            $announcementsRoute = auth()->user()->hasAnyRole(['admin','owner'])
                ? route('admin.announcements.index')
                : (auth()->user()->hasRole('siswa') ? route('siswa.announcements') : '');
        @endphp
        const announcementsRoute = @json($announcementsRoute);

        panel.innerHTML = `
            <div style="padding:13px 16px;border-bottom:1px solid var(--card-border);display:flex;justify-content:space-between;align-items:center">
                <span style="font-size:14px;font-weight:700;color:var(--text-primary)"><i class="bi bi-bell-fill me-2" style="color:#c84ddf"></i>Pengumuman</span>
                <span style="font-size:11px;color:var(--text-muted)">${notifData.length} aktif</span>
            </div>
            <div style="max-height:340px;overflow-y:auto">${itemsHtml}</div>
            ${notifData.length > 0 && announcementsRoute ? `<div style="padding:10px 16px;border-top:1px solid var(--card-border);text-align:center">
                <a href="${announcementsRoute}" style="font-size:12px;color:var(--primary);font-weight:600;text-decoration:none">Lihat semua pengumuman →</a>
            </div>` : ''}`;
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
        @role('admin|owner')
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
        @endrole
        @role('guru')
        { label:'Dashboard Guru', desc:'Portal guru & jadwal', href:'{{ route("guru.dashboard") }}', icon:'bi-speedometer2', color:'#c84ddf', group:'Guru' },
        { label:'Kelas', desc:'Kelas yang diajar', href:'{{ route("guru.classes.index") }}', icon:'bi-diagram-3', color:'#68117e', group:'Guru' },
        { label:'Pengumuman', desc:'Informasi untuk guru', href:'{{ route("guru.announcements") }}', icon:'bi-megaphone', color:'#c84ddf', group:'Guru' },
        @endrole
        @role('siswa')
        { label:'Dashboard Siswa', desc:'Portal siswa & tagihan', href:'{{ route("siswa.dashboard") }}', icon:'bi-speedometer2', color:'#c84ddf', group:'Siswa' },
        { label:'List Mata Pelajaran', desc:'Mata pelajaran yang diambil', href:'{{ route("siswa.courses.index") }}', icon:'bi-journal-bookmark', color:'#10b981', group:'Siswa' },
        { label:'Harga Mapel', desc:'Daftar harga mata pelajaran', href:'{{ route("siswa.courses.fees") }}', icon:'bi-cash-coin', color:'#f6af23', group:'Siswa' },
        // Jadwal Belajar (siswa) dihapus — entri command palette dihilangkan
        { label:'Sertifikat Saya', desc:'Lihat sertifikat yang diterbitkan', href:'{{ route("siswa.certificates.index") }}', icon:'bi-award', color:'#f6af23', group:'Siswa' },
        { label:'Pengumuman', desc:'Informasi & pengumuman terbaru', href:'{{ route("siswa.announcements") }}', icon:'bi-megaphone', color:'#c84ddf', group:'Siswa' },
        { label:'Tryout CBT', desc:'Ujian online UTBK/PTN', href:'{{ route("siswa.tryout") }}', icon:'bi-laptop', color:'#461256', group:'Siswa' },
        @endrole
        @role('owner')
        { label:'Monitoring Cabang', desc:'Pantau semua cabang', href:'{{ route("owner.branches.index") }}', icon:'bi-building', color:'#c84ddf', group:'Owner' },
        { label:'Analytics', desc:'Laporan performa bisnis', href:'{{ route("owner.analytics") }}', icon:'bi-graph-up-arrow', color:'#10b981', group:'Owner' },
        { label:'Log Aktivitas', desc:'Riwayat aktivitas sistem', href:'{{ route("owner.activity-log") }}', icon:'bi-journal-text', color:'#68117e', group:'Owner' },
        @endrole
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
// Sanitize pagination UI: remove ONLY extension-injected SVG chevrons, not Bootstrap Icons
function removeKnownChevrons() {
    const targetPath = 'M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z';
    document.querySelectorAll('#paginationLinks svg, .pagination svg, nav.d-flex svg').forEach(s => {
        try {
            const inner = s.innerHTML || '';
            if (inner.includes(targetPath) || s.classList.contains('w-5') || s.classList.contains('h-5')) s.remove();
        } catch(e) {}
    });
    // Remove oversized SVGs injected by extensions (width > 24px is suspicious in pagination)
    document.querySelectorAll('#paginationLinks svg, .pagination svg').forEach(s => {
        try {
            const w = parseFloat(s.getAttribute('width') || '0');
            const h = parseFloat(s.getAttribute('height') || '0');
            if ((w > 24 || h > 24) && !s.closest('.bi')) s.remove();
        } catch(e) {}
    });
}
document.addEventListener('DOMContentLoaded', function() {
    removeKnownChevrons();
    document.querySelectorAll('#paginationLinks, .pagination, nav.d-flex').forEach(el => {
        if (el) new MutationObserver(removeKnownChevrons).observe(el, { childList:true, subtree:true });
    });
});
</script>
</html>
