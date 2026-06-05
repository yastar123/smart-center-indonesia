<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title','Dashboard') | Smart Center</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <style>
        /* ============================================================
           CSS CUSTOM PROPERTIES
        ============================================================ */
        :root {
            --sidebar-width: 270px;
            --sidebar-bg: #0f172a;
            --sidebar-hover: #1e293b;
            --primary: #3b82f6;
            --primary-dark: #2563eb;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #06b6d4;
            --dark: #0f172a;
            --text: #cbd5e1;
            --border: rgba(255,255,255,.07);
            --content-bg: #f1f5f9;
            --card-bg: #ffffff;
            --card-border: #e2e8f0;
            --text-primary: #0f172a;
            --text-muted: #64748b;
            --topbar-bg: #ffffff;
            --input-bg: #f8fafc;
            --font-sans: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
            --radius-card: 20px;
            --radius-btn: 12px;
            --shadow-sm: 0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
            --shadow-md: 0 4px 16px rgba(0,0,0,.07);
            --shadow-lg: 0 10px 36px rgba(0,0,0,.10);
            --transition: .25s cubic-bezier(.4,0,.2,1);
        }

        [data-theme="dark"] {
            --content-bg: #0f172a;
            --card-bg: #1e293b;
            --card-border: rgba(255,255,255,.08);
            --text-primary: #f1f5f9;
            --text-muted: #94a3b8;
            --topbar-bg: #1e293b;
            --input-bg: #0f172a;
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

        h1 { font-size: clamp(26px, 3.5vw, 40px); }
        h2 { font-size: clamp(20px, 2.8vw, 30px); }
        h3 { font-size: clamp(17px, 2vw, 22px); }

        /* ============================================================
           ANIMATIONS
        ============================================================ */
        .anim { transition: all .4s cubic-bezier(.22,1,.36,1); will-change: transform, opacity; }
        .fade-up { opacity: 0; transform: translateY(16px); }
        .fade-up.in-view { opacity: 1; transform: translateY(0); }
        .fade-in { animation: fadeIn .4s ease both; }
        @keyframes fadeIn { from{opacity:0;transform:translateY(8px);} to{opacity:1;transform:translateY(0);} }

        /* ============================================================
           SIDEBAR
        ============================================================ */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, #0f172a 0%, #111827 100%);
            overflow-y: auto;
            overflow-x: hidden;
            z-index: 1050;
            transition: transform var(--transition), box-shadow var(--transition);
            border-right: 1px solid var(--border);
        }

        .sidebar::-webkit-scrollbar { width: 4px; }
        .sidebar::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }
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
            background: linear-gradient(135deg, #3b82f6, #6366f1);
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; color: white;
            margin-right: 12px;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(59,130,246,.4);
        }
        .brand-title { color: white; font-size: 17px; font-weight: 700; line-height: 1.1; }
        .brand-sub { color: #64748b; font-size: 11px; margin-top: 2px; }

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
            background: rgba(59,130,246,.2);
            color: #93c5fd;
            display: inline-block;
            margin-top: 3px;
            font-weight: 500;
        }

        /* Nav */
        .sidebar-nav { padding: 12px 10px 100px; }

        .nav-header {
            color: #475569;
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
            background: linear-gradient(90deg, #2563eb, #3b82f6);
            color: white;
            box-shadow: 0 6px 20px rgba(59,130,246,.35);
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
            z-index: 1049;
            backdrop-filter: blur(2px);
            animation: fadeOverlay .2s ease both;
        }
        .sidebar-overlay.show { display: block; }
        @keyframes fadeOverlay { from{opacity:0;} to{opacity:1;} }

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
        .topbar-left h4 { margin: 0; font-size: 20px; font-weight: 700; color: var(--text-primary); }
        .topbar-left p { margin: 0; color: var(--text-muted); font-size: 12px; }

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
        .stat-title { color: var(--text-muted); font-size: 13px; font-weight: 500; margin-bottom: 6px; }
        .stat-label { color: var(--text-muted); font-size: 13px; font-weight: 500; margin-top: 6px; }
        .stat-value { font-size: 28px; font-weight: 800; color: var(--text-primary); line-height: 1.1; }
        .stat-growth { font-size: 12px; margin-top: 8px; display: flex; align-items: center; gap: 4px; }

        .bg-primary-soft   { background: linear-gradient(135deg, #3b82f6, #2563eb); }
        .bg-success-soft   { background: linear-gradient(135deg, #10b981, #059669); }
        .bg-warning-soft   { background: linear-gradient(135deg, #f59e0b, #d97706); }
        .bg-danger-soft    { background: linear-gradient(135deg, #ef4444, #dc2626); }
        .bg-info-soft      { background: linear-gradient(135deg, #06b6d4, #0891b2); }
        .bg-purple-soft    { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }

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
            background: linear-gradient(135deg, #2563eb, #3b82f6);
            border: none;
            box-shadow: 0 4px 14px rgba(59,130,246,.3);
        }
        .btn-primary:hover { box-shadow: 0 6px 20px rgba(59,130,246,.4); background: linear-gradient(135deg, #1d4ed8, #2563eb); }
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
            box-shadow: 0 0 0 3px rgba(59,130,246,.15);
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

        /* ============================================================
           SCROLLBAR (webkit)
        ============================================================ */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 6px; }
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
           DARK MODE OVERRIDES
        ============================================================ */
        [data-theme="dark"] .table { color: var(--text-primary); }
        [data-theme="dark"] .table-light { background: rgba(255,255,255,.04) !important; }
        [data-theme="dark"] .modal-header { border-color: var(--card-border); }
        [data-theme="dark"] .modal-footer { border-color: var(--card-border); }
        [data-theme="dark"] .dropdown-menu {
            background: #1e293b;
            border-color: rgba(255,255,255,.1);
        }
        [data-theme="dark"] .dropdown-item { color: #e2e8f0; }
        [data-theme="dark"] .dropdown-item:hover { background: rgba(255,255,255,.08); }
        [data-theme="dark"] .dropdown-divider { border-color: rgba(255,255,255,.1); }
        [data-theme="dark"] .btn-light { background: #334155; border-color: #475569; color: #e2e8f0; }
        [data-theme="dark"] .form-control:focus, [data-theme="dark"] .form-select:focus {
            background: #1e293b;
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
            background: linear-gradient(135deg, #2563eb, #6366f1);
            color: white;
            border: none;
            box-shadow: 0 4px 16px rgba(37,99,235,.35);
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
        #scrollTop:hover { transform: translateY(-2px) scale(1.05); box-shadow: 0 6px 20px rgba(37,99,235,.45); }
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
            padding: 8px 2px;
            cursor: pointer;
            border-radius: 10px;
            text-decoration: none;
            color: var(--text-muted);
            transition: color var(--transition), background var(--transition);
            font-size: 9.5px;
            font-weight: 600;
        }
        .mob-nav-item i { font-size: 20px; transition: transform .2s; }
        .mob-nav-item:hover, .mob-nav-item.active { color: #3b82f6; }
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
            z-index: 9999;
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
            background: linear-gradient(90deg, #3b82f6, #6366f1, #3b82f6);
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
           STAT CARD — accent line animation on hover
        ============================================================ */
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, #3b82f6, #6366f1);
            border-radius: var(--radius-card) var(--radius-card) 0 0;
            opacity: 0;
            transition: opacity var(--transition);
        }
        .stat-card:hover::before { opacity: 1; }

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
            0%, 100% { box-shadow: 0 4px 12px rgba(59,130,246,.4); }
            50%       { box-shadow: 0 4px 22px rgba(99,102,241,.7); }
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
            .btn, .dropdown, form[action*="logout"] { display: none !important; }
            .main-content { margin-left: 0 !important; }
            .content-wrapper { padding: 0 !important; }
            .stat-card, .dashboard-card { break-inside: avoid; box-shadow: none !important; border: 1px solid #ddd !important; }
            body { font-size: 12px; }
        }
    </style>

    @stack('styles')
</head>
<body>

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
        <div>
            <div class="brand-title">Smart Center</div>
            <div class="brand-sub">Enterprise System</div>
        </div>
    </a>

    <div class="sidebar-user">
        <img src="{{ auth()->user()->avatar ? asset('storage/'.auth()->user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=2563eb&color=fff&size=80' }}"
             class="sidebar-avatar" alt="Avatar" id="sidebarAvatar">
        <div>
            <div class="user-name">{{ auth()->user()->name }}</div>
            <div class="user-role">{{ ucfirst(auth()->user()->getRoleNames()->first() ?? 'User') }}</div>
        </div>
    </div>

    <div class="sidebar-nav">

        {{-- DASHBOARD --}}
        <div class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-fill"></i>
                <span>Dashboard</span>
            </a>
        </div>

        {{-- OWNER --}}
        @role('owner')
        <div class="nav-header">OWNER PANEL</div>

        <div class="nav-item">
            <a href="{{ route('owner.branches.index') }}" class="nav-link {{ request()->routeIs('owner.branches.*') ? 'active' : '' }}">
                <i class="bi bi-building"></i>
                <span>Monitoring Cabang</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('owner.activity-log') }}" class="nav-link {{ request()->routeIs('owner.activity-log') ? 'active' : '' }}">
                <i class="bi bi-journal-text"></i>
                <span>Log Aktivitas</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('owner.settings.index') }}" class="nav-link {{ request()->routeIs('owner.settings.*') ? 'active' : '' }}">
                <i class="bi bi-gear"></i>
                <span>Pengaturan</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('owner.analytics') }}" class="nav-link {{ request()->routeIs('owner.analytics') ? 'active' : '' }}">
                <i class="bi bi-graph-up-arrow"></i>
                <span>Analytics</span>
            </a>
        </div>
        @endrole

        {{-- ADMIN --}}
        @role('admin|owner')
        <div class="nav-header">AKADEMIK</div>

        <div class="nav-item">
            <a href="{{ route('admin.students.index') }}" class="nav-link {{ request()->routeIs('admin.students.*') ? 'active' : '' }}">
                <i class="bi bi-mortarboard"></i>
                <span>Siswa</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.teachers.index') }}" class="nav-link {{ request()->routeIs('admin.teachers.*') ? 'active' : '' }}">
                <i class="bi bi-person-workspace"></i>
                <span>Guru</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="#" class="nav-link">
                <i class="bi bi-book-half"></i>
                <span>Modul Belajar</span>
                <span class="menu-badge" style="background:#6366f1">Soon</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="#" class="nav-link">
                <i class="bi bi-journal-bookmark"></i>
                <span>Mata Pelajaran</span>
                <span class="menu-badge" style="background:#6366f1">Soon</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="#" class="nav-link">
                <i class="bi bi-diagram-3"></i>
                <span>Kelas</span>
                <span class="menu-badge" style="background:#6366f1">Soon</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.schedules.index') }}" class="nav-link {{ request()->routeIs('admin.schedules.*') ? 'active' : '' }}">
                <i class="bi bi-calendar-week"></i>
                <span>Jadwal</span>
            </a>
        </div>

        <div class="nav-header">KEUANGAN</div>

        <div class="nav-item">
            <a href="{{ route('admin.payments.index') }}" class="nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                <i class="bi bi-wallet2"></i>
                <span>Pembayaran</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                <i class="bi bi-bar-chart-line"></i>
                <span>Laporan Keuangan</span>
            </a>
        </div>

        <div class="nav-header">TRYOUT CBT</div>

        <div class="nav-item">
            <a href="{{ route('admin.tryouts.index') }}" class="nav-link {{ request()->routeIs('admin.tryouts.*') ? 'active' : '' }}">
                <i class="bi bi-ui-checks-grid"></i>
                <span>Tryout Online</span>
            </a>
        </div>
        @endrole

        {{-- GURU --}}
        @role('guru')
        <div class="nav-header">GURU PANEL</div>
        <div class="nav-item">
            <a href="{{ route('guru.dashboard') }}" class="nav-link {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i><span>Dashboard Guru</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('guru.dashboard') }}" class="nav-link {{ request()->routeIs('guru.dashboard') && request()->is('guru/dashboard') ? 'active' : '' }}">
                <i class="bi bi-calendar2-week"></i><span>Jadwal Mengajar</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="#" class="nav-link">
                <i class="bi bi-check2-square"></i><span>Absensi</span>
                <span class="menu-badge" style="background:#6366f1">Soon</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="#" class="nav-link">
                <i class="bi bi-bar-chart-line"></i><span>Input Nilai</span>
                <span class="menu-badge" style="background:#6366f1">Soon</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="#" class="nav-link">
                <i class="bi bi-cloud-upload"></i><span>Upload Materi</span>
                <span class="menu-badge" style="background:#6366f1">Soon</span>
            </a>
        </div>
        @endrole

        {{-- SISWA --}}
        @role('siswa')
        <div class="nav-header">SISWA PANEL</div>
        <div class="nav-item">
            <a href="{{ route('siswa.dashboard') }}" class="nav-link {{ request()->routeIs('siswa.*') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i><span>Dashboard Siswa</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('siswa.dashboard') }}" class="nav-link">
                <i class="bi bi-calendar-event"></i><span>Jadwal Belajar</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('siswa.dashboard') }}" class="nav-link">
                <i class="bi bi-credit-card"></i><span>Pembayaran</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.tryouts.index') }}" class="nav-link {{ request()->routeIs('admin.tryouts.*') ? 'active' : '' }}">
                <i class="bi bi-laptop"></i><span>Tryout CBT</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="#" class="nav-link">
                <i class="bi bi-file-earmark-text"></i><span>Raport Digital</span>
                <span class="menu-badge" style="background:#6366f1">Soon</span>
            </a>
        </div>
        @endrole

        {{-- SYSTEM --}}
        <div class="nav-header">SYSTEM</div>

        <div class="nav-item">
            <a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <i class="bi bi-person-circle"></i>
                <span>Profil Saya</span>
            </a>
        </div>

        <div class="nav-item">
            <a href="#" class="nav-link">
                <i class="bi bi-chat-dots"></i>
                <span>Chat Realtime</span>
                <span class="menu-badge" style="background:#6366f1">Soon</span>
            </a>
        </div>

        <div class="nav-item">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="nav-link border-0 bg-transparent w-100 text-start" style="color:#94a3b8">
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

            <button class="top-btn" id="darkToggle" onclick="toggleDark()" title="Toggle dark mode">
                <i class="bi bi-moon" id="darkIcon"></i>
            </button>

            <button class="top-btn position-relative" title="Notifikasi" id="notifBtn"
                    onclick="this.querySelector('.notif-badge')?.remove(); this.querySelector('i').style.color='var(--text-muted)'">
                <i class="bi bi-bell"></i>
            </button>

            <div class="dropdown">
                <button class="btn btn-light dropdown-toggle d-flex align-items-center gap-2 border"
                        data-bs-toggle="dropdown" style="border-radius:12px;padding:6px 12px 6px 8px;font-size:13px">
                    <img id="topbarAvatar"
                         src="{{ auth()->user()->avatar ? asset('storage/'.auth()->user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=2563eb&color=fff&size=64' }}"
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
    <a href="{{ route('profile.edit') }}" class="mob-nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
        <i class="bi bi-person{{ request()->routeIs('profile.*') ? '-fill' : '' }}"></i>
        <span>Profil</span>
    </a>
    <button class="mob-nav-item border-0" onclick="toggleSidebar()" style="background:none">
        <i class="bi bi-grid-3x3-gap-fill"></i>
        <span>Menu</span>
    </button>
</nav>

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
    "success": "{{ addslashes(session('success') ?? session('status') ?? '') }}",
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
}

function updateDarkIcon(theme) {
    const icon = document.getElementById('darkIcon');
    if (!icon) return;
    icon.className = theme === 'dark' ? 'bi bi-sun' : 'bi bi-moon';
}

// ---- INTERSECTION OBSERVER (fade-up) ----
document.addEventListener('DOMContentLoaded', function() {
    const io = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('in-view');
                io.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });
    document.querySelectorAll('.fade-up').forEach(el => io.observe(el));
});

// ---- CLOSE SIDEBAR ON ESC ----
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeSidebar();
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
    const mainEl = document.querySelector('.main-content') || window;
    const scroller = document.querySelector('.main-content') || document.documentElement;
    function checkScroll() {
        const top = (mainEl === window ? window.pageYOffset : mainEl.scrollTop);
        btn.classList.toggle('visible', top > 220);
    }
    (mainEl === window ? window : mainEl).addEventListener('scroll', checkScroll, { passive: true });
    checkScroll();
})();

// ---- GLOBAL FLASH TOASTS ----
(function() {
    const icons = {
        success: { icon: 'bi-check-circle-fill', color: '#10b981', bg: '#ecfdf5', border: '#bbf7d0' },
        error:   { icon: 'bi-x-circle-fill',     color: '#ef4444', bg: '#fef2f2', border: '#fecaca' },
        warning: { icon: 'bi-exclamation-triangle-fill', color: '#f59e0b', bg: '#fffbeb', border: '#fde68a' },
        info:    { icon: 'bi-info-circle-fill',   color: '#3b82f6', bg: '#eff6ff', border: '#bfdbfe' },
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
                <div style="font-size:13.5px;font-weight:600;color:#0f172a;line-height:1.3">${msg}</div>
            </div>
            <button onclick="this.closest('.g-toast').remove()" style="border:none;background:none;color:#94a3b8;cursor:pointer;padding:0;font-size:16px;line-height:1;flex-shrink:0">&times;</button>`;
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
            if (data.status)  showToast(data.status,  'success');
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
        const panel = document.createElement('div');
        panel.id = 'notifPanel';
        panel.style.cssText = `
            position:fixed; top:${btn.getBoundingClientRect().bottom + 8}px;
            right:${document.documentElement.clientWidth - btn.getBoundingClientRect().right}px;
            width:300px; background:var(--card-bg); border:1px solid var(--card-border);
            border-radius:16px; box-shadow:0 12px 40px rgba(0,0,0,.12);
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

@stack('scripts')
</body>
</html>
