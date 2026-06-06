<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Center Indonesia — Platform Manajemen Bimbel Terpadu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #c84ddf;
            --primary-dark: #68117e;
            --dark: #260632;
            --gold: #f6af23;
        }

        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Inter', sans-serif;
            color: #1a0a24;
            background: #fff;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* ===== NAVBAR ===== */
        .navbar {
            background: rgba(255,255,255,.92);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(200,77,223,.1);
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 1000;
            padding: 0 0;
            transition: box-shadow .3s;
        }
        .navbar.scrolled { box-shadow: 0 4px 30px rgba(38,6,50,.1); }
        .navbar-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
            height: 68px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .nav-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
        .nav-brand-logo {
            width: 38px; height: 38px;
            border-radius: 10px;
            background: linear-gradient(135deg, #c84ddf, #68117e);
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; color: white;
            box-shadow: 0 4px 14px rgba(200,77,223,.4);
        }
        .nav-brand-text {
            font-size: 16px;
            font-weight: 800;
            color: #260632;
            letter-spacing: -.03em;
        }
        .nav-brand-text span { color: #c84ddf; }
        .nav-links { display: flex; align-items: center; gap: 4px; }
        .nav-links a {
            color: #4b3060;
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 500;
            padding: 8px 14px;
            border-radius: 10px;
            transition: all .2s;
        }
        .nav-links a:hover { background: rgba(200,77,223,.08); color: #c84ddf; }
        .btn-login {
            background: linear-gradient(135deg, #68117e, #c84ddf);
            color: white !important;
            border-radius: 10px !important;
            padding: 9px 20px !important;
            font-weight: 600 !important;
            box-shadow: 0 4px 14px rgba(200,77,223,.35);
            transition: all .25s !important;
        }
        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(200,77,223,.5) !important;
        }
        .nav-hamburger {
            display: none;
            background: none;
            border: none;
            font-size: 22px;
            color: #260632;
            cursor: pointer;
            padding: 4px;
        }

        /* ===== HERO ===== */
        .hero {
            min-height: 100vh;
            background: linear-gradient(160deg, #260632 0%, #461256 45%, #68117e 75%, #c84ddf 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 100px 24px 60px;
            position: relative;
            overflow: hidden;
        }

        .hero-orb {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
        }
        .hero-orb-1 {
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(246,175,35,.18) 0%, transparent 70%);
            top: -200px; right: -200px;
            animation: orbFloat 8s ease-in-out infinite alternate;
        }
        .hero-orb-2 {
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(255,255,255,.06) 0%, transparent 70%);
            bottom: -100px; left: -100px;
            animation: orbFloat 10s ease-in-out infinite alternate-reverse;
        }
        .hero-orb-3 {
            width: 300px; height: 300px;
            background: radial-gradient(circle, rgba(200,77,223,.2) 0%, transparent 70%);
            top: 40%; left: 40%;
            animation: orbFloat 7s ease-in-out infinite alternate;
        }
        @keyframes orbFloat {
            from { transform: translate(0,0) scale(1); }
            to   { transform: translate(30px,20px) scale(1.1); }
        }

        /* Grid pattern */
        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .hero-content {
            max-width: 1100px;
            margin: 0 auto;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,.12);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,.2);
            border-radius: 50px;
            padding: 7px 18px 7px 7px;
            margin-bottom: 28px;
            animation: slideDown .6s ease both;
        }
        .hero-badge-dot {
            width: 28px; height: 28px;
            border-radius: 50%;
            background: linear-gradient(135deg, #f6af23, #c84ddf);
            display: flex; align-items: center; justify-content: center;
            font-size: 13px;
        }
        .hero-badge span { font-size: 12.5px; font-weight: 600; color: rgba(255,255,255,.85); }

        .hero h1 {
            font-size: clamp(2.4rem, 5vw, 4.2rem);
            font-weight: 900;
            line-height: 1.1;
            color: white;
            letter-spacing: -.04em;
            margin-bottom: 20px;
            animation: slideDown .6s .1s ease both;
        }
        .hero h1 .highlight {
            background: linear-gradient(90deg, #f6af23, #c84ddf, #f6af23);
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: textShimmer 4s linear infinite;
        }
        @keyframes textShimmer {
            from { background-position: 200% center; }
            to   { background-position: -200% center; }
        }

        .hero-desc {
            font-size: clamp(.95rem, 1.5vw, 1.15rem);
            color: rgba(255,255,255,.7);
            line-height: 1.7;
            max-width: 580px;
            margin: 0 auto 36px;
            animation: slideDown .6s .2s ease both;
        }

        .hero-actions {
            display: flex;
            gap: 14px;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 60px;
            animation: slideDown .6s .3s ease both;
        }
        .btn-hero-primary {
            background: linear-gradient(135deg, #f6af23, #e09000);
            color: #260632;
            font-weight: 700;
            font-size: 15px;
            padding: 14px 32px;
            border-radius: 14px;
            border: none;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 8px 28px rgba(246,175,35,.4);
            transition: all .25s;
        }
        .btn-hero-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 36px rgba(246,175,35,.55);
            color: #260632;
        }
        .btn-hero-secondary {
            background: rgba(255,255,255,.12);
            color: white;
            font-weight: 600;
            font-size: 15px;
            padding: 14px 32px;
            border-radius: 14px;
            border: 1px solid rgba(255,255,255,.25);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            backdrop-filter: blur(10px);
            transition: all .25s;
        }
        .btn-hero-secondary:hover {
            background: rgba(255,255,255,.2);
            color: white;
            transform: translateY(-2px);
        }

        /* Hero stats */
        .hero-stats {
            display: flex;
            justify-content: center;
            gap: 0;
            animation: slideDown .6s .4s ease both;
        }
        .hero-stat {
            padding: 16px 32px;
            border-right: 1px solid rgba(255,255,255,.1);
            text-align: center;
        }
        .hero-stat:last-child { border-right: none; }
        .hero-stat-num {
            font-size: clamp(1.6rem, 3vw, 2.4rem);
            font-weight: 900;
            color: white;
            line-height: 1;
            letter-spacing: -.04em;
        }
        .hero-stat-num span { color: #f6af23; }
        .hero-stat-lbl { font-size: 11.5px; color: rgba(255,255,255,.55); margin-top: 4px; font-weight: 500; }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ===== FEATURES ===== */
        .section {
            padding: clamp(60px, 8vw, 100px) 24px;
        }
        .section-inner { max-width: 1100px; margin: 0 auto; }

        .section-label {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .12em;
            color: #c84ddf;
            margin-bottom: 12px;
        }
        .section-label::before {
            content: '';
            width: 18px; height: 2px;
            background: #c84ddf;
            border-radius: 2px;
        }

        .section-title {
            font-size: clamp(1.8rem, 3.5vw, 2.8rem);
            font-weight: 900;
            color: #260632;
            letter-spacing: -.04em;
            line-height: 1.15;
            margin-bottom: 16px;
        }
        .section-desc {
            color: #6b5878;
            font-size: 15px;
            line-height: 1.7;
            max-width: 540px;
        }

        /* Feature grid */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 56px;
        }
        @media (max-width: 900px) { .features-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 580px) { .features-grid { grid-template-columns: 1fr; } }

        .feature-card {
            background: white;
            border: 1.5px solid #f0e8f5;
            border-radius: 20px;
            padding: 28px;
            transition: all .3s cubic-bezier(.22,1,.36,1);
            position: relative;
            overflow: hidden;
        }
        .feature-card::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 20px;
            opacity: 0;
            transition: opacity .3s;
            background: linear-gradient(135deg, rgba(200,77,223,.04), rgba(104,17,126,.04));
        }
        .feature-card:hover { transform: translateY(-6px); box-shadow: 0 20px 50px rgba(38,6,50,.1); border-color: #e8b4f5; }
        .feature-card:hover::after { opacity: 1; }

        .feature-icon {
            width: 54px; height: 54px;
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px;
            margin-bottom: 18px;
        }
        .feature-card h3 {
            font-size: 16px;
            font-weight: 700;
            color: #260632;
            margin-bottom: 8px;
            letter-spacing: -.02em;
        }
        .feature-card p {
            font-size: 13.5px;
            color: #6b5878;
            line-height: 1.65;
            margin: 0;
        }

        /* ===== HOW IT WORKS ===== */
        .how-bg { background: #fdf7ff; }

        .steps-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
            margin-top: 56px;
            position: relative;
        }
        .steps-grid::before {
            content: '';
            position: absolute;
            top: 32px; left: 10%; right: 10%;
            height: 1.5px;
            background: linear-gradient(90deg, transparent, #e8b4f5, transparent);
        }
        @media (max-width: 880px) {
            .steps-grid { grid-template-columns: repeat(2, 1fr); }
            .steps-grid::before { display: none; }
        }
        @media (max-width: 480px) { .steps-grid { grid-template-columns: 1fr; } }

        .step-card {
            text-align: center;
            position: relative;
        }
        .step-num {
            width: 64px; height: 64px;
            border-radius: 50%;
            background: linear-gradient(135deg, #68117e, #c84ddf);
            color: white;
            font-size: 22px;
            font-weight: 900;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 18px;
            box-shadow: 0 8px 24px rgba(200,77,223,.35);
            position: relative;
            z-index: 1;
        }
        .step-card h4 { font-size: 15px; font-weight: 700; color: #260632; margin-bottom: 8px; }
        .step-card p { font-size: 13px; color: #6b5878; line-height: 1.65; }

        /* ===== ROLES ===== */
        .roles-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
            margin-top: 56px;
        }
        @media (max-width: 700px) { .roles-grid { grid-template-columns: 1fr; } }

        .role-card {
            border-radius: 20px;
            padding: 28px;
            border: 1.5px solid transparent;
            transition: all .3s;
        }
        .role-card:hover { transform: translateY(-4px); }
        .role-card-header { display: flex; align-items: center; gap: 14px; margin-bottom: 16px; }
        .role-icon {
            width: 52px; height: 52px;
            border-radius: 15px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
        }
        .role-card h3 { font-size: 17px; font-weight: 700; color: #260632; margin-bottom: 3px; }
        .role-card .role-sub { font-size: 12px; color: #6b5878; }
        .role-features { list-style: none; display: flex; flex-direction: column; gap: 8px; }
        .role-features li {
            display: flex; align-items: center; gap: 8px;
            font-size: 13.5px; color: #4b3060;
        }
        .role-features li i { font-size: 14px; flex-shrink: 0; }

        /* ===== CTA ===== */
        .cta-section {
            background: linear-gradient(135deg, #260632 0%, #461256 50%, #68117e 100%);
            padding: clamp(60px, 8vw, 100px) 24px;
            position: relative;
            overflow: hidden;
            text-align: center;
        }
        .cta-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .cta-orb {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
        }
        .cta-orb-1 {
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(246,175,35,.12), transparent 70%);
            top: -200px; right: -150px;
        }
        .cta-orb-2 {
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(200,77,223,.15), transparent 70%);
            bottom: -150px; left: -100px;
        }
        .cta-inner { position: relative; z-index: 1; max-width: 620px; margin: 0 auto; }
        .cta-section h2 {
            font-size: clamp(1.8rem, 3.5vw, 2.8rem);
            font-weight: 900;
            color: white;
            letter-spacing: -.04em;
            margin-bottom: 16px;
        }
        .cta-section p { color: rgba(255,255,255,.65); font-size: 15px; line-height: 1.7; margin-bottom: 36px; }
        .cta-actions { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
        .btn-cta-primary {
            background: linear-gradient(135deg, #f6af23, #e09000);
            color: #260632;
            font-weight: 700;
            font-size: 15px;
            padding: 14px 32px;
            border-radius: 14px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 8px 28px rgba(246,175,35,.35);
            transition: all .25s;
        }
        .btn-cta-primary:hover { transform: translateY(-2px); color: #260632; box-shadow: 0 12px 36px rgba(246,175,35,.5); }
        .btn-cta-secondary {
            background: rgba(255,255,255,.1);
            color: white;
            font-weight: 600;
            font-size: 15px;
            padding: 14px 32px;
            border-radius: 14px;
            border: 1px solid rgba(255,255,255,.2);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all .25s;
        }
        .btn-cta-secondary:hover { background: rgba(255,255,255,.18); color: white; transform: translateY(-2px); }

        /* ===== FOOTER ===== */
        footer {
            background: #0d0118;
            padding: 40px 24px;
            text-align: center;
        }
        .footer-inner { max-width: 1100px; margin: 0 auto; }
        .footer-brand { display: flex; align-items: center; gap: 10px; justify-content: center; margin-bottom: 16px; }
        .footer-brand-logo {
            width: 34px; height: 34px;
            border-radius: 9px;
            background: linear-gradient(135deg, #c84ddf, #68117e);
            display: flex; align-items: center; justify-content: center;
            font-size: 16px; color: white;
        }
        .footer-brand-text { font-size: 15px; font-weight: 700; color: white; }
        footer p { font-size: 13px; color: rgba(255,255,255,.4); }

        /* ===== MOBILE NAV ===== */
        .mobile-menu {
            display: none;
            position: fixed;
            top: 68px; left: 0; right: 0;
            background: white;
            border-bottom: 1px solid #f0e8f5;
            padding: 16px 24px 20px;
            box-shadow: 0 8px 30px rgba(0,0,0,.1);
            z-index: 999;
            flex-direction: column;
            gap: 4px;
        }
        .mobile-menu.open { display: flex; }
        .mobile-menu a {
            color: #4b3060;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            padding: 10px 14px;
            border-radius: 10px;
            transition: background .2s;
        }
        .mobile-menu a:hover { background: #fdf4ff; color: #c84ddf; }
        .mobile-menu .btn-login-mobile {
            background: linear-gradient(135deg, #68117e, #c84ddf);
            color: white !important;
            text-align: center;
            font-weight: 600;
            margin-top: 8px;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .nav-links { display: none; }
            .nav-hamburger { display: block; }
            .hero-stats { flex-wrap: wrap; }
            .hero-stat { flex: 1 1 45%; border-right: none; border-bottom: 1px solid rgba(255,255,255,.1); padding: 12px 16px; }
            .hero-stat:last-child { border-bottom: none; }
        }

        /* ===== FADE IN ON SCROLL ===== */
        .animate-on-scroll {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity .6s ease, transform .6s ease;
        }
        .animate-on-scroll.visible { opacity: 1; transform: translateY(0); }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar" id="navbar">
    <div class="navbar-inner">
        <a href="/" class="nav-brand">
            <div class="nav-brand-logo">
                <i class="bi bi-mortarboard-fill"></i>
            </div>
            <div class="nav-brand-text">Smart<span>Center</span></div>
        </a>

        <div class="nav-links">
            <a href="#features">Fitur</a>
            <a href="#cara-kerja">Cara Kerja</a>
            <a href="#peran">Peran Pengguna</a>
            <a href="{{ route('login') }}" class="btn-login">
                <i class="bi bi-box-arrow-in-right me-1"></i>Masuk
            </a>
        </div>

        <button class="nav-hamburger" id="hamburger" onclick="toggleMobileMenu()">
            <i class="bi bi-list" id="hamburgerIcon"></i>
        </button>
    </div>
</nav>

<!-- MOBILE MENU -->
<div class="mobile-menu" id="mobileMenu">
    <a href="#features" onclick="closeMobileMenu()">Fitur</a>
    <a href="#cara-kerja" onclick="closeMobileMenu()">Cara Kerja</a>
    <a href="#peran" onclick="closeMobileMenu()">Peran Pengguna</a>
    <a href="{{ route('login') }}" class="btn-login-mobile">
        <i class="bi bi-box-arrow-in-right me-1"></i>Masuk ke Dashboard
    </a>
</div>

<!-- HERO -->
<section class="hero">
    <div class="hero-orb hero-orb-1"></div>
    <div class="hero-orb hero-orb-2"></div>
    <div class="hero-orb hero-orb-3"></div>

    <div class="hero-content">
        <div class="hero-badge">
            <div class="hero-badge-dot"><i class="bi bi-stars" style="color:white"></i></div>
            <span>Platform #1 Manajemen Bimbel Indonesia</span>
        </div>

        <h1>
            Platform Manajemen<br>
            Bimbel <span class="highlight">Terpadu</span>
        </h1>

        <p class="hero-desc">
            Kelola siswa, guru, keuangan, dan jadwal belajar seluruh cabang dalam satu platform cerdas. Tingkatkan efisiensi operasional bimbel Anda mulai hari ini.
        </p>

        <div class="hero-actions">
            <a href="{{ route('login') }}" class="btn-hero-primary">
                <i class="bi bi-rocket-takeoff-fill"></i>
                Mulai Sekarang
            </a>
            <a href="#features" class="btn-hero-secondary">
                <i class="bi bi-play-circle"></i>
                Pelajari Fitur
            </a>
        </div>

        <div class="hero-stats">
            <div class="hero-stat">
                <div class="hero-stat-num">500<span>+</span></div>
                <div class="hero-stat-lbl">Siswa Terdaftar</div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-num">50<span>+</span></div>
                <div class="hero-stat-lbl">Pengajar Aktif</div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-num">10<span>+</span></div>
                <div class="hero-stat-lbl">Cabang Beroperasi</div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-num">99<span>%</span></div>
                <div class="hero-stat-lbl">Uptime Layanan</div>
            </div>
        </div>
    </div>
</section>

<!-- FEATURES -->
<section class="section" id="features">
    <div class="section-inner">
        <div class="text-center animate-on-scroll">
            <div class="section-label">Fitur Unggulan</div>
            <h2 class="section-title">Semua yang Dibutuhkan<br>Bimbel Modern</h2>
            <p class="section-desc mx-auto">Dari manajemen siswa hingga laporan keuangan — semuanya dalam satu platform terintegrasi.</p>
        </div>

        <div class="features-grid">
            @php
            $features = [
                ['icon'=>'bi-people-fill','color'=>'#68117e','bg'=>'linear-gradient(135deg,#fdf4ff,#f5e6fa)','title'=>'Manajemen Siswa','desc'=>'Kelola data lengkap siswa, riwayat akademik, informasi orang tua, dan status keaktifan di semua cabang dengan mudah.'],
                ['icon'=>'bi-person-workspace','color'=>'#059669','bg'=>'linear-gradient(135deg,#f0fdf4,#d1fae5)','title'=>'Manajemen Guru','desc'=>'Atur profil pengajar, mata pelajaran, jadwal mengajar, dan rekap gaji secara terpusat dan akurat.'],
                ['icon'=>'bi-calendar-week-fill','color'=>'#2563eb','bg'=>'linear-gradient(135deg,#eff6ff,#dbeafe)','title'=>'Jadwal & Kehadiran','desc'=>'Buat jadwal pelajaran, pantau kehadiran siswa dan guru secara real-time, serta kelola sesi online maupun offline.'],
                ['icon'=>'bi-cash-coin','color'=>'#16a34a','bg'=>'linear-gradient(135deg,#f0fdf4,#dcfce7)','title'=>'Keuangan & Invoice','desc'=>'Buat invoice otomatis, catat pembayaran, pantau tunggakan, dan ekspor laporan keuangan ke Excel/PDF.'],
                ['icon'=>'bi-building-fill-check','color'=>'#c84ddf','bg'=>'linear-gradient(135deg,#fdf4ff,#f3d6fa)','title'=>'Multi Cabang','desc'=>'Kelola banyak cabang sekaligus dari satu dashboard owner. Pantau performa, siswa, dan pendapatan per cabang.'],
                ['icon'=>'bi-graph-up-arrow','color'=>'#e09000','bg'=>'linear-gradient(135deg,#fffbeb,#fef3c7)','title'=>'Analytics & Laporan','desc'=>'Visualisasi data pertumbuhan siswa, tren pendapatan, dan performa cabang dengan grafik interaktif yang mudah dipahami.'],
            ];
            @endphp

            @foreach($features as $i => $f)
            <div class="feature-card animate-on-scroll" style="transition-delay:{{ $i * 0.08 }}s">
                <div class="feature-icon" style="background:{{ $f['bg'] }}">
                    <i class="bi {{ $f['icon'] }}" style="color:{{ $f['color'] }}"></i>
                </div>
                <h3>{{ $f['title'] }}</h3>
                <p>{{ $f['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- HOW IT WORKS -->
<section class="section how-bg" id="cara-kerja">
    <div class="section-inner">
        <div class="text-center animate-on-scroll">
            <div class="section-label">Cara Kerja</div>
            <h2 class="section-title">Mulai dalam 4 Langkah Mudah</h2>
            <p class="section-desc mx-auto">Setup platform bimbel Anda kurang dari 10 menit. Tidak perlu keahlian teknis.</p>
        </div>

        <div class="steps-grid">
            @php
            $steps = [
                ['num'=>'1','title'=>'Daftar & Login','desc'=>'Buat akun owner, masuk ke dashboard, dan atur profil lembaga Anda.'],
                ['num'=>'2','title'=>'Tambah Cabang','desc'=>'Buat cabang baru, atur admin per cabang, dan aktifkan fitur yang dibutuhkan.'],
                ['num'=>'3','title'=>'Input Data','desc'=>'Daftarkan siswa, guru, mata pelajaran, kelas, dan buat jadwal belajar.'],
                ['num'=>'4','title'=>'Kelola & Monitor','desc'=>'Pantau kehadiran, tagihan, dan performa seluruh cabang dari satu layar.'],
            ];
            @endphp
            @foreach($steps as $i => $s)
            <div class="step-card animate-on-scroll" style="transition-delay:{{ $i * 0.1 }}s">
                <div class="step-num">{{ $s['num'] }}</div>
                <h4>{{ $s['title'] }}</h4>
                <p>{{ $s['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ROLES -->
<section class="section" id="peran">
    <div class="section-inner">
        <div class="text-center animate-on-scroll">
            <div class="section-label">Peran Pengguna</div>
            <h2 class="section-title">Dirancang untuk Setiap Peran</h2>
            <p class="section-desc mx-auto">Portal khusus untuk Owner, Admin, Guru, dan Siswa — masing-masing dengan akses dan fitur yang relevan.</p>
        </div>

        <div class="roles-grid">
            <!-- Owner -->
            <div class="role-card animate-on-scroll" style="background:linear-gradient(135deg,#fdf4ff,#f3d6fa);border-color:#e8b4f5">
                <div class="role-card-header">
                    <div class="role-icon" style="background:linear-gradient(135deg,#68117e,#c84ddf);color:white">
                        <i class="bi bi-shield-fill-check"></i>
                    </div>
                    <div>
                        <h3>Owner</h3>
                        <div class="role-sub">Akses penuh ke semua cabang</div>
                    </div>
                </div>
                <ul class="role-features">
                    <li><i class="bi bi-check-circle-fill" style="color:#c84ddf"></i>Monitoring semua cabang sekaligus</li>
                    <li><i class="bi bi-check-circle-fill" style="color:#c84ddf"></i>Analytics & laporan pendapatan</li>
                    <li><i class="bi bi-check-circle-fill" style="color:#c84ddf"></i>Kelola akun admin per cabang</li>
                    <li><i class="bi bi-check-circle-fill" style="color:#c84ddf"></i>Log aktivitas seluruh sistem</li>
                </ul>
            </div>

            <!-- Admin -->
            <div class="role-card animate-on-scroll" style="background:linear-gradient(135deg,#f0fdf4,#d1fae5);border-color:#bbf7d0;transition-delay:.1s">
                <div class="role-card-header">
                    <div class="role-icon" style="background:linear-gradient(135deg,#059669,#10b981);color:white">
                        <i class="bi bi-person-gear"></i>
                    </div>
                    <div>
                        <h3>Admin Cabang</h3>
                        <div class="role-sub">Pengelola operasional cabang</div>
                    </div>
                </div>
                <ul class="role-features">
                    <li><i class="bi bi-check-circle-fill" style="color:#059669"></i>Kelola siswa & guru cabang</li>
                    <li><i class="bi bi-check-circle-fill" style="color:#059669"></i>Buat jadwal & kelola kelas</li>
                    <li><i class="bi bi-check-circle-fill" style="color:#059669"></i>Buat invoice & catat pembayaran</li>
                    <li><i class="bi bi-check-circle-fill" style="color:#059669"></i>Laporan keuangan cabang</li>
                </ul>
            </div>

            <!-- Guru -->
            <div class="role-card animate-on-scroll" style="background:linear-gradient(135deg,#eff6ff,#dbeafe);border-color:#bfdbfe;transition-delay:.2s">
                <div class="role-card-header">
                    <div class="role-icon" style="background:linear-gradient(135deg,#2563eb,#60a5fa);color:white">
                        <i class="bi bi-person-badge-fill"></i>
                    </div>
                    <div>
                        <h3>Guru / Pengajar</h3>
                        <div class="role-sub">Portal khusus pengajar</div>
                    </div>
                </div>
                <ul class="role-features">
                    <li><i class="bi bi-check-circle-fill" style="color:#2563eb"></i>Lihat jadwal mengajar</li>
                    <li><i class="bi bi-check-circle-fill" style="color:#2563eb"></i>Input absensi siswa per sesi</li>
                    <li><i class="bi bi-check-circle-fill" style="color:#2563eb"></i>Input & rekap nilai siswa</li>
                    <li><i class="bi bi-check-circle-fill" style="color:#2563eb"></i>Akses link meeting online</li>
                </ul>
            </div>

            <!-- Siswa -->
            <div class="role-card animate-on-scroll" style="background:linear-gradient(135deg,#fffbeb,#fef3c7);border-color:#fcd34d;transition-delay:.3s">
                <div class="role-card-header">
                    <div class="role-icon" style="background:linear-gradient(135deg,#e09000,#f6af23);color:white">
                        <i class="bi bi-mortarboard-fill"></i>
                    </div>
                    <div>
                        <h3>Siswa</h3>
                        <div class="role-sub">Portal belajar siswa</div>
                    </div>
                </div>
                <ul class="role-features">
                    <li><i class="bi bi-check-circle-fill" style="color:#e09000"></i>Lihat jadwal belajar mingguan</li>
                    <li><i class="bi bi-check-circle-fill" style="color:#e09000"></i>Cek status tagihan & pembayaran</li>
                    <li><i class="bi bi-check-circle-fill" style="color:#e09000"></i>Akses sesi online / link meeting</li>
                    <li><i class="bi bi-check-circle-fill" style="color:#e09000"></i>Lihat riwayat nilai & sertifikat</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section">
    <div class="cta-orb cta-orb-1"></div>
    <div class="cta-orb cta-orb-2"></div>
    <div class="cta-inner animate-on-scroll">
        <div class="section-label" style="justify-content:center;color:#f6af23;--bs-badge-color:#f6af23">
            <div style="width:18px;height:2px;background:#f6af23;border-radius:2px"></div>
            Sudah Siap?
        </div>
        <h2>Kelola Bimbel Anda<br>Lebih Efisien Hari Ini</h2>
        <p>Bergabunglah dengan ratusan lembaga bimbel yang sudah mempercayakan operasional mereka kepada Smart Center Indonesia.</p>
        <div class="cta-actions">
            <a href="{{ route('login') }}" class="btn-cta-primary">
                <i class="bi bi-rocket-takeoff-fill"></i>
                Masuk ke Dashboard
            </a>
            <a href="{{ route('register') }}" class="btn-cta-secondary">
                <i class="bi bi-person-plus"></i>
                Daftar Gratis
            </a>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer>
    <div class="footer-inner">
        <div class="footer-brand">
            <div class="footer-brand-logo">
                <i class="bi bi-mortarboard-fill"></i>
            </div>
            <div class="footer-brand-text">Smart Center Indonesia</div>
        </div>
        <p>&copy; {{ date('Y') }} Smart Center Indonesia. All rights reserved. Platform Manajemen Bimbel Enterprise.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Navbar scroll effect
    window.addEventListener('scroll', () => {
        document.getElementById('navbar').classList.toggle('scrolled', window.scrollY > 30);
    });

    // Mobile menu
    function toggleMobileMenu() {
        const menu = document.getElementById('mobileMenu');
        const icon = document.getElementById('hamburgerIcon');
        const isOpen = menu.classList.toggle('open');
        icon.className = isOpen ? 'bi bi-x-lg' : 'bi bi-list';
    }
    function closeMobileMenu() {
        document.getElementById('mobileMenu').classList.remove('open');
        document.getElementById('hamburgerIcon').className = 'bi bi-list';
    }

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(a => {
        a.addEventListener('click', e => {
            const target = document.querySelector(a.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // Animate on scroll
    const observer = new IntersectionObserver(entries => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                e.target.classList.add('visible');
                observer.unobserve(e.target);
            }
        });
    }, { threshold: 0.1 });
    document.querySelectorAll('.animate-on-scroll').forEach(el => observer.observe(el));
</script>
</body>
</html>
