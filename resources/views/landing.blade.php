@php
    $stats = [
        'students' => \App\Models\Student::where('status','aktif')->count(),
        'teachers' => \App\Models\Teacher::where('status','aktif')->count(),
        'branches' => \App\Models\Branch::count(),
        'schedules'=> \App\Models\Schedule::count(),
    ];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Smart Center Indonesia — Platform manajemen bimbel terpadu untuk kelola siswa, guru, keuangan, dan jadwal seluruh cabang dalam satu sistem.">
    <title>Smart Center Indonesia | Platform Manajemen Bimbel Terpadu</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }

        :root {
            --primary:      #c84ddf;
            --primary-dark: #68117e;
            --deep:         #260632;
            --mid:          #461256;
            --gold:         #f6af23;
            --gold-dark:    #e09000;
            --success:      #10b981;
            --white:        #ffffff;
            --off-white:    #fdf8ff;
            --text:         #1e0828;
            --text-muted:   #6b5878;
            --border:       rgba(200,77,223,.12);
            --font-sans:    'Inter', system-ui, sans-serif;
            --font-display: 'Plus Jakarta Sans', 'Inter', sans-serif;
            --ease-out:     cubic-bezier(.22,1,.36,1);
            --ease-in-out:  cubic-bezier(.4,0,.2,1);
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: var(--font-sans);
            color: var(--text);
            background: var(--white);
            overflow-x: hidden;
        }

        h1,h2,h3,h4,h5,h6 {
            font-family: var(--font-display);
            letter-spacing: -.025em;
        }

        /* ─── NAVBAR ─────────────────────────────────────────────── */
        .lp-nav {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 1000;
            padding: 1rem 0;
            transition: background .35s var(--ease-in-out),
                        box-shadow .35s var(--ease-in-out),
                        padding .35s var(--ease-in-out);
        }
        .lp-nav.scrolled {
            background: rgba(255,255,255,.92);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            box-shadow: 0 1px 0 rgba(200,77,223,.1), 0 4px 24px rgba(38,6,50,.07);
            padding: .6rem 0;
        }
        .lp-nav.scrolled .nav-brand-text { color: var(--deep); }
        .lp-nav.scrolled .nav-link-item  { color: #374151; }
        .lp-nav.scrolled .nav-link-item:hover { color: var(--primary-dark); }
        .lp-nav.scrolled .nav-toggle span { background: var(--deep); }

        .nav-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
        .nav-brand-icon {
            width: 38px; height: 38px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            display: flex; align-items: center; justify-content: center;
            font-size: 19px;
            color: white;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(200,77,223,.4);
        }
        .nav-brand-text {
            font-family: var(--font-display);
            font-weight: 800;
            font-size: 1.05rem;
            color: white;
            letter-spacing: -.02em;
            line-height: 1.1;
        }
        .nav-brand-text small {
            display: block;
            font-size: .65rem;
            font-weight: 500;
            opacity: .7;
            letter-spacing: .02em;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: .25rem;
            list-style: none;
        }
        .nav-link-item {
            color: rgba(255,255,255,.85);
            text-decoration: none;
            font-size: .88rem;
            font-weight: 500;
            padding: .45rem .85rem;
            border-radius: 8px;
            transition: color .2s, background .2s;
        }
        .nav-link-item:hover {
            color: white;
            background: rgba(255,255,255,.12);
        }

        .nav-cta {
            display: flex;
            align-items: center;
            gap: .6rem;
        }
        .btn-nav-login {
            padding: .45rem 1.1rem;
            border-radius: 10px;
            font-size: .88rem;
            font-weight: 600;
            color: rgba(255,255,255,.9);
            border: 1.5px solid rgba(255,255,255,.25);
            background: transparent;
            text-decoration: none;
            transition: .2s;
        }
        .btn-nav-login:hover {
            color: white;
            border-color: rgba(255,255,255,.5);
            background: rgba(255,255,255,.1);
        }
        .lp-nav.scrolled .btn-nav-login {
            color: var(--primary-dark);
            border-color: var(--border);
        }
        .lp-nav.scrolled .btn-nav-login:hover {
            background: rgba(200,77,223,.07);
            border-color: var(--primary);
        }

        .btn-nav-register {
            padding: .45rem 1.2rem;
            border-radius: 10px;
            font-size: .88rem;
            font-weight: 700;
            color: white;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            text-decoration: none;
            border: none;
            transition: transform .2s, box-shadow .2s;
            box-shadow: 0 4px 14px rgba(200,77,223,.35);
        }
        .btn-nav-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 22px rgba(200,77,223,.5);
            color: white;
        }

        .nav-toggle {
            display: none;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
            padding: 6px;
            background: none;
            border: none;
        }
        .nav-toggle span {
            display: block;
            width: 24px; height: 2px;
            background: white;
            border-radius: 2px;
            transition: .3s var(--ease-in-out);
        }
        .nav-toggle.open span:nth-child(1) { transform: translateY(7px) rotate(45deg); }
        .nav-toggle.open span:nth-child(2) { opacity: 0; transform: scaleX(0); }
        .nav-toggle.open span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }

        .mobile-menu {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(38,6,50,.97);
            backdrop-filter: blur(20px);
            z-index: 999;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 1.5rem;
            opacity: 0;
            transform: scale(.96);
            transition: opacity .3s, transform .3s;
        }
        .mobile-menu.open {
            display: flex;
            opacity: 1;
            transform: scale(1);
        }
        .mobile-menu a {
            color: white;
            text-decoration: none;
            font-size: 1.4rem;
            font-family: var(--font-display);
            font-weight: 700;
            letter-spacing: -.02em;
            transition: color .2s;
        }
        .mobile-menu a:hover { color: var(--primary); }
        .mobile-close {
            position: absolute;
            top: 1.5rem; right: 1.5rem;
            background: rgba(255,255,255,.1);
            border: none; color: white;
            width: 40px; height: 40px;
            border-radius: 50%;
            font-size: 1.2rem;
            cursor: pointer;
        }

        /* ─── HERO ────────────────────────────────────────────────── */
        .hero {
            min-height: 100vh;
            background: linear-gradient(160deg, var(--deep) 0%, var(--mid) 45%, #8b1fa8 80%, #b83dd0 100%);
            position: relative;
            display: flex;
            align-items: center;
            overflow: hidden;
        }

        /* Animated grid */
        .hero-grid {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.04) 1px, transparent 1px);
            background-size: 60px 60px;
            mask-image: radial-gradient(ellipse 80% 80% at 50% 50%, black 30%, transparent 100%);
        }

        /* Glowing orbs */
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            pointer-events: none;
        }
        .orb-1 {
            width: 700px; height: 700px;
            background: radial-gradient(circle, rgba(200,77,223,.3) 0%, transparent 70%);
            top: -200px; right: -200px;
            animation: orb-drift-1 12s ease-in-out infinite alternate;
        }
        .orb-2 {
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(246,175,35,.2) 0%, transparent 70%);
            bottom: -100px; left: -100px;
            animation: orb-drift-2 15s ease-in-out infinite alternate;
        }
        .orb-3 {
            width: 350px; height: 350px;
            background: radial-gradient(circle, rgba(104,17,126,.4) 0%, transparent 70%);
            top: 40%; left: 30%;
            animation: orb-drift-3 10s ease-in-out infinite alternate;
        }
        @keyframes orb-drift-1 {
            from { transform: translate(0,0) scale(1); }
            to   { transform: translate(-60px, 80px) scale(1.15); }
        }
        @keyframes orb-drift-2 {
            from { transform: translate(0,0) scale(1); }
            to   { transform: translate(50px, -60px) scale(1.2); }
        }
        @keyframes orb-drift-3 {
            from { transform: translate(0,0) scale(1); }
            to   { transform: translate(-40px, 40px) scale(1.1); }
        }

        /* Floating particles */
        .particles { position: absolute; inset: 0; overflow: hidden; pointer-events: none; }
        .particle {
            position: absolute;
            width: 4px; height: 4px;
            background: rgba(255,255,255,.4);
            border-radius: 50%;
            animation: float-particle linear infinite;
        }

        @keyframes float-particle {
            0%   { transform: translateY(100vh) scale(0); opacity: 0; }
            10%  { opacity: 1; }
            90%  { opacity: 1; }
            100% { transform: translateY(-20px) scale(1); opacity: 0; }
        }

        .hero-inner {
            position: relative;
            z-index: 2;
            max-width: 1200px;
            margin: 0 auto;
            padding: 8rem 1.5rem 5rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,.15);
            border-radius: 50px;
            padding: 6px 16px 6px 6px;
            font-size: .8rem;
            font-weight: 600;
            color: rgba(255,255,255,.9);
            margin-bottom: 1.5rem;
            animation: fade-up .6s var(--ease-out) both;
        }
        .hero-badge-dot {
            width: 24px; height: 24px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--gold), var(--primary));
            display: flex; align-items: center; justify-content: center;
            font-size: 12px;
            animation: pulse-dot 2s ease-in-out infinite;
        }
        @keyframes pulse-dot {
            0%,100% { box-shadow: 0 0 0 0 rgba(246,175,35,.4); }
            50%      { box-shadow: 0 0 0 6px rgba(246,175,35,0); }
        }

        .hero-title {
            font-size: clamp(2.2rem, 4.5vw, 3.6rem);
            font-weight: 900;
            color: white;
            line-height: 1.1;
            margin-bottom: 1.25rem;
            animation: fade-up .7s var(--ease-out) .1s both;
        }
        .hero-title .gradient-text {
            background: linear-gradient(90deg, var(--gold), var(--primary), #e879f9);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-desc {
            font-size: clamp(.9rem, 1.5vw, 1.05rem);
            color: rgba(255,255,255,.72);
            line-height: 1.75;
            margin-bottom: 2.25rem;
            max-width: 500px;
            animation: fade-up .7s var(--ease-out) .2s both;
        }

        .hero-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            animation: fade-up .7s var(--ease-out) .3s both;
        }

        .btn-hero-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: .9rem 2rem;
            border-radius: 14px;
            font-size: .95rem;
            font-weight: 700;
            color: white;
            background: linear-gradient(135deg, var(--gold-dark), var(--gold));
            text-decoration: none;
            border: none;
            transition: transform .25s, box-shadow .25s;
            box-shadow: 0 8px 24px rgba(246,175,35,.4);
            letter-spacing: -.01em;
        }
        .btn-hero-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 14px 36px rgba(246,175,35,.55);
            color: white;
        }

        .btn-hero-secondary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: .9rem 1.8rem;
            border-radius: 14px;
            font-size: .95rem;
            font-weight: 600;
            color: white;
            border: 1.5px solid rgba(255,255,255,.3);
            background: rgba(255,255,255,.08);
            backdrop-filter: blur(8px);
            text-decoration: none;
            transition: .25s;
        }
        .btn-hero-secondary:hover {
            background: rgba(255,255,255,.15);
            border-color: rgba(255,255,255,.5);
            transform: translateY(-2px);
            color: white;
        }

        .hero-trust {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-top: 2.5rem;
            animation: fade-up .7s var(--ease-out) .4s both;
        }
        .hero-avatars {
            display: flex;
        }
        .hero-avatar {
            width: 34px; height: 34px;
            border-radius: 50%;
            border: 2.5px solid rgba(255,255,255,.3);
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            display: flex; align-items: center; justify-content: center;
            font-size: 12px;
            font-weight: 700;
            color: white;
            margin-left: -10px;
        }
        .hero-avatars .hero-avatar:first-child { margin-left: 0; }
        .hero-trust-text {
            font-size: .8rem;
            color: rgba(255,255,255,.7);
            line-height: 1.4;
        }
        .hero-trust-text strong { color: white; display: block; }

        /* Hero visual right side */
        .hero-visual {
            position: relative;
            animation: fade-up .8s var(--ease-out) .2s both;
        }
        .hero-card-main {
            background: rgba(255,255,255,.08);
            backdrop-filter: blur(24px);
            border: 1px solid rgba(255,255,255,.12);
            border-radius: 24px;
            padding: 1.75rem;
            color: white;
        }
        .hero-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }
        .hero-card-title {
            font-size: .9rem;
            font-weight: 600;
            opacity: .85;
        }
        .hero-card-live {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: .72rem;
            color: #34d399;
            font-weight: 600;
        }
        .live-dot {
            width: 7px; height: 7px;
            background: #34d399;
            border-radius: 50%;
            animation: pulse-green 1.5s ease-in-out infinite;
        }
        @keyframes pulse-green {
            0%,100% { box-shadow: 0 0 0 0 rgba(52,211,153,.5); }
            50%      { box-shadow: 0 0 0 5px rgba(52,211,153,0); }
        }

        .stat-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 10px;
        }
        .stat-box {
            background: rgba(255,255,255,.07);
            border: 1px solid rgba(255,255,255,.08);
            border-radius: 14px;
            padding: 14px;
            transition: background .3s, transform .3s;
        }
        .stat-box:hover {
            background: rgba(255,255,255,.12);
            transform: translateY(-2px);
        }
        .stat-box .s-num {
            font-size: 1.6rem;
            font-weight: 800;
            font-family: var(--font-display);
            letter-spacing: -.03em;
            line-height: 1;
            margin: 4px 0 3px;
        }
        .stat-box .s-label {
            font-size: .72rem;
            color: rgba(255,255,255,.6);
            font-weight: 500;
        }
        .stat-box i { font-size: 1rem; }

        .hero-chart {
            background: rgba(255,255,255,.06);
            border: 1px solid rgba(255,255,255,.08);
            border-radius: 14px;
            padding: 16px;
            margin-top: 10px;
        }
        .chart-bars {
            display: flex;
            align-items: flex-end;
            gap: 6px;
            height: 60px;
        }
        .chart-bar {
            flex: 1;
            border-radius: 4px 4px 0 0;
            background: rgba(200,77,223,.4);
            transition: background .3s;
            animation: grow-bar .8s var(--ease-out) both;
        }
        .chart-bar.active { background: linear-gradient(180deg, #c84ddf, #68117e); }
        .chart-bar:hover { background: rgba(200,77,223,.7); }
        @keyframes grow-bar {
            from { transform: scaleY(0); transform-origin: bottom; }
            to   { transform: scaleY(1); transform-origin: bottom; }
        }
        .chart-label {
            display: flex;
            justify-content: space-between;
            margin-top: 8px;
        }
        .chart-label span { font-size: .65rem; color: rgba(255,255,255,.4); }

        /* Floating mini-cards */
        .float-card {
            position: absolute;
            background: white;
            border-radius: 16px;
            padding: 12px 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,.25), 0 0 0 1px rgba(200,77,223,.1);
            animation: float-card 4s ease-in-out infinite alternate;
            z-index: 3;
            white-space: nowrap;
        }
        .float-card-1 {
            bottom: -20px; left: -40px;
            animation-delay: 0s;
        }
        .float-card-2 {
            top: -15px; right: -30px;
            animation-delay: 2s;
        }
        @keyframes float-card {
            from { transform: translateY(0) rotate(-1deg); }
            to   { transform: translateY(-12px) rotate(1deg); }
        }
        .float-card-content {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .float-icon {
            width: 36px; height: 36px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }
        .float-card-text { font-family: var(--font-display); }
        .float-card-text .fc-val {
            font-size: 1rem;
            font-weight: 800;
            color: var(--deep);
            line-height: 1;
            letter-spacing: -.02em;
        }
        .float-card-text .fc-lab {
            font-size: .7rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        @keyframes fade-up {
            from { opacity: 0; transform: translateY(28px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Hero scroll indicator */
        .scroll-indicator {
            position: absolute;
            bottom: 2.5rem;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            color: rgba(255,255,255,.5);
            font-size: .72rem;
            font-weight: 500;
            letter-spacing: .06em;
            text-transform: uppercase;
            animation: fade-up 1s var(--ease-out) .8s both;
            z-index: 2;
        }
        .scroll-mouse {
            width: 22px; height: 36px;
            border: 2px solid rgba(255,255,255,.3);
            border-radius: 11px;
            display: flex;
            justify-content: center;
            padding-top: 6px;
        }
        .scroll-wheel {
            width: 4px; height: 8px;
            background: rgba(255,255,255,.6);
            border-radius: 2px;
            animation: scroll-down 1.5s ease-in-out infinite;
        }
        @keyframes scroll-down {
            0%   { transform: translateY(0); opacity: 1; }
            100% { transform: translateY(8px); opacity: 0; }
        }

        /* ─── STATS STRIP ─────────────────────────────────────────── */
        .stats-strip {
            background: var(--off-white);
            border-top: 1px solid rgba(200,77,223,.08);
            border-bottom: 1px solid rgba(200,77,223,.08);
            padding: 2.5rem 0;
        }
        .stats-strip-inner {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 1.5rem;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
        }
        .stat-item {
            text-align: center;
            padding: 1rem;
            border-right: 1px solid rgba(200,77,223,.1);
            transition: transform .3s;
        }
        .stat-item:last-child { border-right: none; }
        .stat-item:hover { transform: translateY(-3px); }
        .stat-item .si-num {
            font-size: clamp(1.8rem, 3vw, 2.4rem);
            font-weight: 900;
            font-family: var(--font-display);
            background: linear-gradient(135deg, var(--deep), var(--primary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -.03em;
            line-height: 1;
        }
        .stat-item .si-label {
            font-size: .82rem;
            color: var(--text-muted);
            font-weight: 500;
            margin-top: .4rem;
        }

        /* ─── SECTION COMMONS ─────────────────────────────────────── */
        .section-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(200,77,223,.08);
            border: 1px solid rgba(200,77,223,.15);
            border-radius: 50px;
            padding: 5px 14px;
            font-size: .76rem;
            font-weight: 700;
            color: var(--primary-dark);
            text-transform: uppercase;
            letter-spacing: .06em;
            margin-bottom: 1rem;
        }
        .section-title {
            font-size: clamp(1.7rem, 3vw, 2.5rem);
            font-weight: 800;
            color: var(--deep);
            line-height: 1.2;
            margin-bottom: .75rem;
        }
        .section-subtitle {
            font-size: clamp(.88rem, 1.5vw, 1rem);
            color: var(--text-muted);
            line-height: 1.7;
            max-width: 560px;
        }

        .section-pad { padding: 6rem 0; }
        .section-pad-sm { padding: 4rem 0; }

        .container-lp {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        /* ─── FEATURES ───────────────────────────────────────────── */
        .features-bg {
            background: var(--off-white);
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            margin-top: 3.5rem;
        }

        .feature-card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 22px;
            padding: 2rem;
            transition: transform .3s var(--ease-out), box-shadow .3s;
            position: relative;
            overflow: hidden;
        }
        .feature-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(200,77,223,.03), transparent);
            opacity: 0;
            transition: opacity .3s;
        }
        .feature-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 50px rgba(38,6,50,.1);
        }
        .feature-card:hover::before { opacity: 1; }

        .fc-icon {
            width: 52px; height: 52px;
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 24px;
            margin-bottom: 1.25rem;
        }
        .fc-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--deep);
            margin-bottom: .5rem;
        }
        .fc-desc {
            font-size: .87rem;
            color: var(--text-muted);
            line-height: 1.65;
        }

        .fc-tag {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-top: 1rem;
            font-size: .72rem;
            font-weight: 600;
            color: var(--primary-dark);
        }

        /* Feature highlight card */
        .feature-card.highlight {
            background: linear-gradient(160deg, var(--deep) 0%, var(--mid) 100%);
            border-color: transparent;
            color: white;
        }
        .feature-card.highlight .fc-title { color: white; }
        .feature-card.highlight .fc-desc  { color: rgba(255,255,255,.7); }
        .feature-card.highlight .fc-tag   { color: var(--gold); }
        .feature-card.highlight .fc-tag i { color: var(--gold); }

        /* ─── HOW IT WORKS ───────────────────────────────────────── */
        .how-inner {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 5rem;
            align-items: center;
        }

        .steps-list { list-style: none; margin-top: 2rem; }
        .step-item {
            display: flex;
            gap: 1.25rem;
            padding: 1.25rem 0;
            border-bottom: 1px solid rgba(200,77,223,.07);
            transition: .25s;
            cursor: default;
            border-radius: 12px;
            position: relative;
        }
        .step-item:last-child { border-bottom: none; }
        .step-item:hover { background: rgba(200,77,223,.04); padding-left: .75rem; }

        .step-num {
            width: 44px; height: 44px;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--deep), var(--primary-dark));
            color: white;
            display: flex; align-items: center; justify-content: center;
            font-size: .85rem;
            font-weight: 800;
            flex-shrink: 0;
            transition: transform .25s;
            box-shadow: 0 6px 18px rgba(38,6,50,.2);
        }
        .step-item:hover .step-num { transform: scale(1.08) rotate(-4deg); }
        .step-body {}
        .step-title {
            font-size: .95rem;
            font-weight: 700;
            color: var(--deep);
            margin-bottom: .25rem;
        }
        .step-desc { font-size: .83rem; color: var(--text-muted); line-height: 1.6; }

        /* How visual */
        .how-visual {
            position: relative;
        }
        .dashboard-mockup {
            background: linear-gradient(160deg, var(--deep), var(--mid));
            border-radius: 24px;
            padding: 1.5rem;
            box-shadow: 0 40px 80px rgba(38,6,50,.25);
            position: relative;
            overflow: hidden;
        }
        .dashboard-mockup::before {
            content: '';
            position: absolute;
            top: -50px; right: -50px;
            width: 200px; height: 200px;
            background: radial-gradient(circle, rgba(200,77,223,.3), transparent);
            pointer-events: none;
        }
        .db-topbar {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(255,255,255,.08);
        }
        .db-dot {
            width: 10px; height: 10px;
            border-radius: 50%;
        }
        .db-url {
            flex: 1;
            background: rgba(255,255,255,.06);
            border-radius: 6px;
            padding: 5px 10px;
            font-size: .7rem;
            color: rgba(255,255,255,.4);
            font-family: monospace;
            margin-left: 6px;
        }

        .db-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 10px; }
        .db-stat-card {
            background: rgba(255,255,255,.07);
            border: 1px solid rgba(255,255,255,.08);
            border-radius: 12px;
            padding: 12px;
            color: white;
        }
        .db-stat-card .db-s-label { font-size: .65rem; color: rgba(255,255,255,.5); font-weight: 500; text-transform: uppercase; letter-spacing: .04em; }
        .db-stat-card .db-s-val {
            font-size: 1.4rem;
            font-weight: 800;
            font-family: var(--font-display);
            letter-spacing: -.02em;
            margin-top: 2px;
        }
        .db-stat-card .db-s-trend {
            font-size: .68rem;
            color: #34d399;
            font-weight: 600;
            margin-top: 2px;
        }

        .db-chart-area {
            background: rgba(255,255,255,.05);
            border: 1px solid rgba(255,255,255,.07);
            border-radius: 12px;
            padding: 14px;
        }
        .db-chart-label {
            font-size: .7rem;
            color: rgba(255,255,255,.5);
            margin-bottom: 10px;
            font-weight: 500;
        }
        .db-bars {
            display: flex;
            align-items: flex-end;
            gap: 5px;
            height: 50px;
        }
        .db-bar {
            flex: 1;
            background: rgba(200,77,223,.35);
            border-radius: 3px 3px 0 0;
        }
        .db-bar.hi { background: linear-gradient(180deg, var(--primary), var(--primary-dark)); }

        .db-students {
            margin-top: 10px;
            background: rgba(255,255,255,.05);
            border: 1px solid rgba(255,255,255,.07);
            border-radius: 12px;
            overflow: hidden;
        }
        .db-student-row {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-bottom: 1px solid rgba(255,255,255,.05);
            font-size: .72rem;
            color: rgba(255,255,255,.8);
        }
        .db-student-row:last-child { border-bottom: none; }
        .db-s-avatar {
            width: 26px; height: 26px;
            border-radius: 8px;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            display: flex; align-items: center; justify-content: center;
            font-size: 11px;
            font-weight: 700;
            color: white;
            flex-shrink: 0;
        }
        .db-s-badge {
            margin-left: auto;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: .63rem;
            font-weight: 600;
        }
        .db-s-badge.aktif { background: rgba(52,211,153,.15); color: #34d399; }
        .db-s-badge.baru  { background: rgba(200,77,223,.2); color: #e879f9; }

        /* ─── ROLES ───────────────────────────────────────────────── */
        .roles-bg { background: linear-gradient(180deg, var(--off-white) 0%, white 100%); }

        .roles-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.25rem;
            margin-top: 3rem;
        }

        .role-card {
            border-radius: 22px;
            padding: 2rem 1.5rem;
            text-align: center;
            transition: transform .3s var(--ease-out), box-shadow .3s;
            position: relative;
            overflow: hidden;
            cursor: default;
        }
        .role-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 24px 60px rgba(38,6,50,.14);
        }
        .role-card .rc-icon {
            width: 64px; height: 64px;
            border-radius: 50%;
            margin: 0 auto 1.25rem;
            display: flex; align-items: center; justify-content: center;
            font-size: 28px;
        }
        .role-card .rc-role {
            font-size: .7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .08em;
            margin-bottom: .35rem;
        }
        .role-card .rc-title {
            font-size: 1rem;
            font-weight: 800;
            margin-bottom: .6rem;
            letter-spacing: -.02em;
        }
        .role-card .rc-desc {
            font-size: .82rem;
            line-height: 1.6;
        }
        .role-card .rc-features {
            list-style: none;
            margin-top: 1rem;
            display: flex;
            flex-direction: column;
            gap: 5px;
            text-align: left;
        }
        .role-card .rc-features li {
            font-size: .78rem;
            display: flex;
            align-items: center;
            gap: 7px;
        }
        .role-card .rc-features li i { font-size: 11px; flex-shrink: 0; }

        /* Owner */
        .role-owner {
            background: linear-gradient(160deg, var(--deep) 0%, #3a0950 100%);
            color: white;
        }
        .role-owner .rc-icon { background: rgba(200,77,223,.2); color: #e879f9; }
        .role-owner .rc-role { color: rgba(255,255,255,.5); }
        .role-owner .rc-title { color: white; }
        .role-owner .rc-desc  { color: rgba(255,255,255,.65); }
        .role-owner .rc-features li { color: rgba(255,255,255,.75); }
        .role-owner .rc-features li i { color: #c84ddf; }

        /* Admin */
        .role-admin {
            background: linear-gradient(160deg, #1e1b4b, #312e81);
            color: white;
        }
        .role-admin .rc-icon { background: rgba(99,102,241,.2); color: #818cf8; }
        .role-admin .rc-role { color: rgba(255,255,255,.5); }
        .role-admin .rc-title { color: white; }
        .role-admin .rc-desc  { color: rgba(255,255,255,.65); }
        .role-admin .rc-features li { color: rgba(255,255,255,.75); }
        .role-admin .rc-features li i { color: #818cf8; }

        /* Guru */
        .role-guru {
            background: white;
            border: 1px solid rgba(16,185,129,.15);
        }
        .role-guru .rc-icon { background: rgba(16,185,129,.1); color: #059669; }
        .role-guru .rc-role { color: #059669; }
        .role-guru .rc-title { color: var(--deep); }
        .role-guru .rc-desc  { color: var(--text-muted); }
        .role-guru .rc-features li { color: #374151; }
        .role-guru .rc-features li i { color: #10b981; }

        /* Siswa */
        .role-siswa {
            background: white;
            border: 1px solid rgba(246,175,35,.2);
        }
        .role-siswa .rc-icon { background: rgba(246,175,35,.1); color: var(--gold-dark); }
        .role-siswa .rc-role { color: var(--gold-dark); }
        .role-siswa .rc-title { color: var(--deep); }
        .role-siswa .rc-desc  { color: var(--text-muted); }
        .role-siswa .rc-features li { color: #374151; }
        .role-siswa .rc-features li i { color: var(--gold); }

        /* ─── TESTIMONIALS ───────────────────────────────────────── */
        .testimonials-bg {
            background: linear-gradient(135deg, var(--deep) 0%, var(--mid) 50%, #8b1fa8 100%);
            position: relative;
            overflow: hidden;
        }
        .testimonials-bg::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.03) 1px, transparent 1px);
            background-size: 40px 40px;
        }

        .testimonials-inner { position: relative; z-index: 1; }

        .testi-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.25rem;
            margin-top: 3rem;
        }
        .testi-card {
            background: rgba(255,255,255,.07);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255,255,255,.1);
            border-radius: 22px;
            padding: 1.75rem;
            transition: transform .3s, background .3s;
        }
        .testi-card:hover {
            transform: translateY(-5px);
            background: rgba(255,255,255,.11);
        }
        .testi-stars {
            display: flex;
            gap: 3px;
            margin-bottom: 1rem;
            color: var(--gold);
            font-size: .85rem;
        }
        .testi-text {
            font-size: .88rem;
            color: rgba(255,255,255,.85);
            line-height: 1.7;
            margin-bottom: 1.25rem;
        }
        .testi-author {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .testi-avatar {
            width: 40px; height: 40px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem;
            font-weight: 800;
            color: white;
            flex-shrink: 0;
        }
        .testi-name {
            font-size: .88rem;
            font-weight: 700;
            color: white;
        }
        .testi-role {
            font-size: .72rem;
            color: rgba(255,255,255,.5);
            font-weight: 500;
        }

        /* ─── CTA ─────────────────────────────────────────────────── */
        .cta-section {
            padding: 6rem 0;
        }
        .cta-box {
            background: linear-gradient(160deg, var(--deep) 0%, var(--mid) 60%, #8b1fa8 100%);
            border-radius: 32px;
            padding: 4.5rem 3rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .cta-box::before {
            content: '';
            position: absolute;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(200,77,223,.25), transparent 70%);
            top: -100px; right: -100px;
            pointer-events: none;
        }
        .cta-box::after {
            content: '';
            position: absolute;
            width: 300px; height: 300px;
            background: radial-gradient(circle, rgba(246,175,35,.15), transparent 70%);
            bottom: -80px; left: -80px;
            pointer-events: none;
        }
        .cta-content { position: relative; z-index: 1; }
        .cta-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255,255,255,.12);
            border: 1px solid rgba(255,255,255,.15);
            border-radius: 50px;
            padding: 5px 16px;
            font-size: .75rem;
            font-weight: 700;
            color: rgba(255,255,255,.9);
            text-transform: uppercase;
            letter-spacing: .06em;
            margin-bottom: 1.25rem;
        }
        .cta-title {
            font-size: clamp(1.8rem, 3vw, 2.8rem);
            font-weight: 900;
            color: white;
            line-height: 1.2;
            margin-bottom: 1rem;
        }
        .cta-desc {
            font-size: 1rem;
            color: rgba(255,255,255,.7);
            margin-bottom: 2.5rem;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }
        .cta-btns {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        .btn-cta-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 1rem 2.25rem;
            border-radius: 14px;
            font-size: 1rem;
            font-weight: 700;
            color: var(--deep);
            background: white;
            text-decoration: none;
            transition: transform .25s, box-shadow .25s;
            box-shadow: 0 8px 24px rgba(0,0,0,.2);
        }
        .btn-cta-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 14px 40px rgba(0,0,0,.3);
            color: var(--primary-dark);
        }
        .btn-cta-secondary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 1rem 2rem;
            border-radius: 14px;
            font-size: 1rem;
            font-weight: 600;
            color: white;
            border: 1.5px solid rgba(255,255,255,.3);
            background: rgba(255,255,255,.08);
            text-decoration: none;
            transition: .25s;
        }
        .btn-cta-secondary:hover {
            background: rgba(255,255,255,.15);
            border-color: rgba(255,255,255,.5);
            transform: translateY(-2px);
            color: white;
        }

        /* ─── FOOTER ─────────────────────────────────────────────── */
        .footer {
            background: var(--deep);
            color: rgba(255,255,255,.65);
            padding: 4rem 0 2rem;
        }
        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 3rem;
            padding-bottom: 3rem;
            border-bottom: 1px solid rgba(255,255,255,.07);
        }
        .footer-brand-desc {
            font-size: .85rem;
            line-height: 1.7;
            color: rgba(255,255,255,.5);
            margin-top: 1rem;
            max-width: 280px;
        }
        .footer-social {
            display: flex;
            gap: 8px;
            margin-top: 1.25rem;
        }
        .footer-social a {
            width: 36px; height: 36px;
            border-radius: 10px;
            background: rgba(255,255,255,.07);
            border: 1px solid rgba(255,255,255,.08);
            display: flex; align-items: center; justify-content: center;
            color: rgba(255,255,255,.6);
            font-size: .95rem;
            text-decoration: none;
            transition: .2s;
        }
        .footer-social a:hover {
            background: var(--primary);
            border-color: var(--primary);
            color: white;
        }
        .footer-col-title {
            font-family: var(--font-display);
            font-size: .85rem;
            font-weight: 700;
            color: white;
            margin-bottom: 1rem;
            letter-spacing: -.01em;
        }
        .footer-links { list-style: none; display: flex; flex-direction: column; gap: 8px; }
        .footer-links a {
            font-size: .82rem;
            color: rgba(255,255,255,.5);
            text-decoration: none;
            transition: color .2s;
        }
        .footer-links a:hover { color: var(--primary); }
        .footer-bottom {
            padding-top: 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .footer-bottom p { font-size: .78rem; }
        .footer-bottom-links { display: flex; gap: 1.5rem; }
        .footer-bottom-links a { font-size: .78rem; color: rgba(255,255,255,.4); text-decoration: none; transition: color .2s; }
        .footer-bottom-links a:hover { color: var(--primary); }

        /* ─── SCROLL-REVEAL ──────────────────────────────────────── */
        .reveal {
            opacity: 0;
            transform: translateY(32px);
            transition: opacity .7s var(--ease-out), transform .7s var(--ease-out);
        }
        .reveal.visible {
            opacity: 1;
            transform: none;
        }
        .reveal-delay-1 { transition-delay: .1s; }
        .reveal-delay-2 { transition-delay: .2s; }
        .reveal-delay-3 { transition-delay: .3s; }
        .reveal-delay-4 { transition-delay: .4s; }
        .reveal-delay-5 { transition-delay: .5s; }

        /* ─── RESPONSIVE ─────────────────────────────────────────── */
        @media (max-width: 1024px) {
            .hero-inner { grid-template-columns: 1fr; text-align: center; gap: 3rem; }
            .hero-desc   { margin-left: auto; margin-right: auto; }
            .hero-actions { justify-content: center; }
            .hero-trust  { justify-content: center; }
            .hero-visual { max-width: 480px; margin: 0 auto; }
            .float-card-1 { bottom: -10px; left: -20px; }
            .float-card-2 { top: -10px;  right: -15px; }
            .features-grid { grid-template-columns: 1fr 1fr; }
            .how-inner  { grid-template-columns: 1fr; }
            .how-visual { order: -1; max-width: 480px; margin: 0 auto; }
            .roles-grid { grid-template-columns: 1fr 1fr; }
            .footer-grid { grid-template-columns: 1fr 1fr; gap: 2rem; }
        }
        @media (max-width: 768px) {
            .nav-links, .nav-cta { display: none; }
            .nav-toggle { display: flex; }
            .stats-strip-inner { grid-template-columns: 1fr 1fr; }
            .stat-item { border-right: none; border-bottom: 1px solid rgba(200,77,223,.1); }
            .stat-item:nth-child(odd)  { border-right: 1px solid rgba(200,77,223,.1); }
            .stat-item:nth-child(3), .stat-item:nth-child(4) { border-bottom: none; }
            .features-grid { grid-template-columns: 1fr; }
            .testi-grid { grid-template-columns: 1fr; }
            .roles-grid { grid-template-columns: 1fr; }
            .footer-grid { grid-template-columns: 1fr; gap: 2rem; }
            .footer-bottom { flex-direction: column; text-align: center; }
            .cta-box { padding: 3rem 1.5rem; }
        }
        @media (max-width: 480px) {
            .hero-inner { padding: 6rem 1rem 3rem; }
            .btn-hero-primary, .btn-hero-secondary { width: 100%; justify-content: center; }
            .cta-btns > * { width: 100%; justify-content: center; }
            .float-card { display: none; }
        }
    </style>
</head>
<body>

{{-- ─────────────────────────────── NAVBAR ────────────────────────────────── --}}
<nav class="lp-nav" id="navbar">
    <div class="nav-inner">
        <a href="{{ url('/') }}" class="nav-brand">
            <div class="nav-brand-icon"><i class="bi bi-mortarboard-fill"></i></div>
            <div class="nav-brand-text">
                Smart Center
                <small>Indonesia</small>
            </div>
        </a>

        <ul class="nav-links">
            <li><a href="#features" class="nav-link-item">Fitur</a></li>
            <li><a href="#how-it-works" class="nav-link-item">Cara Kerja</a></li>
            <li><a href="#roles" class="nav-link-item">Pengguna</a></li>
            <li><a href="#testimonials" class="nav-link-item">Testimoni</a></li>
        </ul>

        <div class="nav-cta">
            <a href="{{ route('login') }}" class="btn-nav-login">Masuk</a>
            <a href="{{ route('register') }}" class="btn-nav-register">Daftar Gratis</a>
        </div>

        <button class="nav-toggle" id="navToggle" aria-label="Menu">
            <span></span><span></span><span></span>
        </button>
    </div>
</nav>

{{-- Mobile Menu --}}
<div class="mobile-menu" id="mobileMenu">
    <button class="mobile-close" id="mobileClose"><i class="bi bi-x-lg"></i></button>
    <a href="#features"      onclick="closeMobile()">Fitur</a>
    <a href="#how-it-works"  onclick="closeMobile()">Cara Kerja</a>
    <a href="#roles"         onclick="closeMobile()">Pengguna</a>
    <a href="#testimonials"  onclick="closeMobile()">Testimoni</a>
    <a href="{{ route('login') }}"    style="color:rgba(255,255,255,.7);font-size:1.1rem;font-weight:600">Masuk</a>
    <a href="{{ route('register') }}" style="background:linear-gradient(135deg,var(--primary-dark),var(--primary));padding:.8rem 2.5rem;border-radius:14px;font-size:1rem">Daftar Gratis</a>
</div>

{{-- ─────────────────────────────── HERO ──────────────────────────────────── --}}
<section class="hero" id="home">
    <div class="hero-grid"></div>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>
    <div class="particles" id="particles"></div>

    <div class="hero-inner">
        {{-- Left Content --}}
        <div>
            <div class="hero-badge">
                <div class="hero-badge-dot"><i class="bi bi-stars" style="color:white;font-size:11px"></i></div>
                Platform #1 Manajemen Bimbel di Indonesia
            </div>

            <h1 class="hero-title">
                Kelola Bimbel Anda<br>
                dengan <span class="gradient-text">Lebih Cerdas</span>
            </h1>

            <p class="hero-desc">
                Satu platform terpadu untuk mengelola siswa, guru, keuangan, jadwal belajar, dan laporan seluruh cabang. Hemat waktu, tingkatkan kualitas.
            </p>

            <div class="hero-actions">
                <a href="{{ route('register') }}" class="btn-hero-primary">
                    <i class="bi bi-rocket-takeoff-fill"></i>
                    Mulai Gratis Sekarang
                </a>
                <a href="{{ route('login') }}" class="btn-hero-secondary">
                    <i class="bi bi-play-circle"></i>
                    Lihat Demo
                </a>
            </div>

            <div class="hero-trust">
                <div class="hero-avatars">
                    <div class="hero-avatar">A</div>
                    <div class="hero-avatar">B</div>
                    <div class="hero-avatar">C</div>
                    <div class="hero-avatar">D</div>
                </div>
                <div class="hero-trust-text">
                    <strong>Dipercaya ratusan pengelola bimbel</strong>
                    di seluruh Indonesia ⭐⭐⭐⭐⭐
                </div>
            </div>
        </div>

        {{-- Right Visual --}}
        <div class="hero-visual">
            <div class="hero-card-main">
                <div class="hero-card-header">
                    <span class="hero-card-title">📊 Dashboard Overview</span>
                    <span class="hero-card-live">
                        <span class="live-dot"></span> Live
                    </span>
                </div>

                <div class="stat-row">
                    <div class="stat-box">
                        <i class="bi bi-people-fill" style="color:#c84ddf"></i>
                        <div class="s-num" data-count="{{ $stats['students'] }}">{{ $stats['students'] }}+</div>
                        <span class="s-label">Siswa Aktif</span>
                    </div>
                    <div class="stat-box">
                        <i class="bi bi-person-workspace" style="color:#10b981"></i>
                        <div class="s-num" data-count="{{ $stats['teachers'] }}">{{ $stats['teachers'] }}+</div>
                        <span class="s-label">Guru</span>
                    </div>
                </div>
                <div class="stat-row">
                    <div class="stat-box">
                        <i class="bi bi-building" style="color:#f6af23"></i>
                        <div class="s-num" data-count="{{ $stats['branches'] }}">{{ $stats['branches'] }}</div>
                        <span class="s-label">Cabang</span>
                    </div>
                    <div class="stat-box">
                        <i class="bi bi-calendar-check" style="color:#ab8db2"></i>
                        <div class="s-num" data-count="{{ $stats['schedules'] }}">{{ $stats['schedules'] }}+</div>
                        <span class="s-label">Jadwal</span>
                    </div>
                </div>

                <div class="hero-chart">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px">
                        <span style="font-size:.7rem;color:rgba(255,255,255,.5);font-weight:500">Pendapatan Bulanan</span>
                        <span style="font-size:.72rem;color:#34d399;font-weight:600"><i class="bi bi-arrow-up-short"></i>+18%</span>
                    </div>
                    <div class="chart-bars">
                        <div class="chart-bar" style="height:40%;animation-delay:.1s"></div>
                        <div class="chart-bar" style="height:55%;animation-delay:.15s"></div>
                        <div class="chart-bar" style="height:45%;animation-delay:.2s"></div>
                        <div class="chart-bar" style="height:70%;animation-delay:.25s"></div>
                        <div class="chart-bar" style="height:60%;animation-delay:.3s"></div>
                        <div class="chart-bar" style="height:80%;animation-delay:.35s"></div>
                        <div class="chart-bar active" style="height:100%;animation-delay:.4s"></div>
                    </div>
                    <div class="chart-label">
                        <span>Jan</span><span>Feb</span><span>Mar</span>
                        <span>Apr</span><span>Mei</span><span>Jun</span><span>Jul</span>
                    </div>
                </div>
            </div>

            <div class="float-card float-card-1">
                <div class="float-card-content">
                    <div class="float-icon" style="background:rgba(16,185,129,.1);">✅</div>
                    <div class="float-card-text">
                        <div class="fc-val">Pembayaran Lunas</div>
                        <div class="fc-lab">Rp 4.800.000 · Baru saja</div>
                    </div>
                </div>
            </div>

            <div class="float-card float-card-2">
                <div class="float-card-content">
                    <div class="float-icon" style="background:rgba(200,77,223,.1);">👤</div>
                    <div class="float-card-text">
                        <div class="fc-val">Siswa Baru Daftar</div>
                        <div class="fc-lab">Paket Intensif · 2 menit lalu</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="scroll-indicator">
        <div class="scroll-mouse"><div class="scroll-wheel"></div></div>
        <span>Scroll</span>
    </div>
</section>

{{-- ──────────────────────────── STATS STRIP ───────────────────────────────── --}}
<section class="stats-strip">
    <div class="stats-strip-inner">
        <div class="stat-item reveal">
            <div class="si-num count-up" data-target="{{ $stats['students'] }}">0</div>
            <div class="si-label">Siswa Aktif Terdaftar</div>
        </div>
        <div class="stat-item reveal reveal-delay-1">
            <div class="si-num count-up" data-target="{{ $stats['teachers'] }}">0</div>
            <div class="si-label">Guru Berpengalaman</div>
        </div>
        <div class="stat-item reveal reveal-delay-2">
            <div class="si-num count-up" data-target="{{ $stats['branches'] }}">0</div>
            <div class="si-label">Cabang Aktif</div>
        </div>
        <div class="stat-item reveal reveal-delay-3">
            <div class="si-num count-up" data-target="{{ $stats['schedules'] }}">0</div>
            <div class="si-label">Sesi Belajar Dikelola</div>
        </div>
    </div>
</section>

{{-- ──────────────────────────── FEATURES ─────────────────────────────────── --}}
<section class="section-pad features-bg" id="features">
    <div class="container-lp">
        <div class="text-center reveal">
            <div class="section-eyebrow"><i class="bi bi-grid-3x3-gap-fill"></i> Fitur Lengkap</div>
            <h2 class="section-title">Semua yang Anda Butuhkan<br>dalam Satu Platform</h2>
            <p class="section-subtitle mx-auto">Dari manajemen siswa hingga laporan keuangan, semuanya tersedia dengan tampilan yang intuitif dan mudah digunakan.</p>
        </div>

        <div class="features-grid">
            <div class="feature-card reveal reveal-delay-1">
                <div class="fc-icon" style="background:rgba(200,77,223,.1)">
                    <i class="bi bi-people-fill" style="color:#c84ddf"></i>
                </div>
                <div class="fc-title">Manajemen Siswa</div>
                <div class="fc-desc">Kelola data siswa lengkap: profil, kelas, jadwal, nilai, absensi, dan tagihan — semuanya terpusat dalam satu tempat.</div>
                <div class="fc-tag"><i class="bi bi-arrow-right-circle-fill"></i> Lihat fitur</div>
            </div>

            <div class="feature-card reveal reveal-delay-2">
                <div class="fc-icon" style="background:rgba(16,185,129,.1)">
                    <i class="bi bi-person-workspace" style="color:#10b981"></i>
                </div>
                <div class="fc-title">Manajemen Guru</div>
                <div class="fc-desc">Data guru, jadwal mengajar, presensi, gaji, dan performa mengajar dalam satu dashboard yang informatif.</div>
                <div class="fc-tag"><i class="bi bi-arrow-right-circle-fill"></i> Lihat fitur</div>
            </div>

            <div class="feature-card highlight reveal reveal-delay-3">
                <div class="fc-icon" style="background:rgba(246,175,35,.15)">
                    <i class="bi bi-cash-stack" style="color:#f6af23"></i>
                </div>
                <div class="fc-title">Keuangan & Pembayaran</div>
                <div class="fc-desc">Invoice otomatis, rekap pembayaran SPP, penggajian guru, dan laporan keuangan real-time dengan visualisasi grafik yang jelas.</div>
                <div class="fc-tag"><i class="bi bi-stars"></i> Fitur unggulan</div>
            </div>

            <div class="feature-card reveal reveal-delay-1">
                <div class="fc-icon" style="background:rgba(99,102,241,.1)">
                    <i class="bi bi-calendar3" style="color:#6366f1"></i>
                </div>
                <div class="fc-title">Jadwal Belajar</div>
                <div class="fc-desc">Buat dan kelola jadwal kelas, atur ruangan, pantau kehadiran, dan kirim pengingat otomatis ke siswa dan guru.</div>
                <div class="fc-tag"><i class="bi bi-arrow-right-circle-fill"></i> Lihat fitur</div>
            </div>

            <div class="feature-card reveal reveal-delay-2">
                <div class="fc-icon" style="background:rgba(246,175,35,.1)">
                    <i class="bi bi-graph-up-arrow" style="color:#f6af23"></i>
                </div>
                <div class="fc-title">Laporan & Analitik</div>
                <div class="fc-desc">Dashboard analitik lengkap dengan grafik perkembangan siswa, pendapatan cabang, dan performa keseluruhan bimbel.</div>
                <div class="fc-tag"><i class="bi bi-arrow-right-circle-fill"></i> Lihat fitur</div>
            </div>

            <div class="feature-card reveal reveal-delay-3">
                <div class="fc-icon" style="background:rgba(239,68,68,.08)">
                    <i class="bi bi-building-fill-check" style="color:#ef4444"></i>
                </div>
                <div class="fc-title">Multi Cabang</div>
                <div class="fc-desc">Kelola banyak cabang sekaligus dari satu dashboard owner. Pantau kinerja tiap cabang dan bandingkan performa secara real-time.</div>
                <div class="fc-tag"><i class="bi bi-arrow-right-circle-fill"></i> Lihat fitur</div>
            </div>
        </div>
    </div>
</section>

{{-- ─────────────────────────── HOW IT WORKS ──────────────────────────────── --}}
<section class="section-pad" id="how-it-works">
    <div class="container-lp">
        <div class="how-inner">
            <div>
                <div class="reveal">
                    <div class="section-eyebrow"><i class="bi bi-map"></i> Cara Kerja</div>
                    <h2 class="section-title">Mudah Digunakan,<br>Langsung Produktif</h2>
                    <p class="section-subtitle">Tidak perlu keahlian teknis. Platform kami dirancang untuk langsung bisa digunakan siapa saja.</p>
                </div>

                <ul class="steps-list">
                    <li class="step-item reveal reveal-delay-1">
                        <div class="step-num">01</div>
                        <div class="step-body">
                            <div class="step-title">Daftar & Buat Akun</div>
                            <div class="step-desc">Daftarkan lembaga Anda dalam 2 menit. Isi nama bimbel, cabang, dan akun pengelola utama.</div>
                        </div>
                    </li>
                    <li class="step-item reveal reveal-delay-2">
                        <div class="step-num">02</div>
                        <div class="step-body">
                            <div class="step-title">Input Data Awal</div>
                            <div class="step-desc">Tambahkan guru, kelas, dan paket belajar. Sistem siap dalam hitungan menit, bukan hari.</div>
                        </div>
                    </li>
                    <li class="step-item reveal reveal-delay-3">
                        <div class="step-num">03</div>
                        <div class="step-body">
                            <div class="step-title">Daftarkan Siswa</div>
                            <div class="step-desc">Siswa mendapat akun sendiri untuk melihat jadwal, nilai, dan tagihan mereka secara mandiri.</div>
                        </div>
                    </li>
                    <li class="step-item reveal reveal-delay-4">
                        <div class="step-num">04</div>
                        <div class="step-body">
                            <div class="step-title">Kelola & Pantau</div>
                            <div class="step-desc">Semua data terupdate secara real-time. Laporan otomatis tersedia setiap saat kapanpun Anda butuhkan.</div>
                        </div>
                    </li>
                </ul>
            </div>

            {{-- Dashboard Mockup --}}
            <div class="how-visual reveal reveal-delay-2">
                <div class="dashboard-mockup">
                    <div class="db-topbar">
                        <div class="db-dot" style="background:#ff5f57"></div>
                        <div class="db-dot" style="background:#ffbd2e"></div>
                        <div class="db-dot" style="background:#28c840"></div>
                        <div class="db-url">app.smartcenter.id/admin/dashboard</div>
                    </div>

                    <div class="db-grid">
                        <div class="db-stat-card">
                            <div class="db-s-label">Total Siswa</div>
                            <div class="db-s-val" style="color:white">{{ $stats['students'] }}</div>
                            <div class="db-s-trend"><i class="bi bi-arrow-up-short"></i>+12% bulan ini</div>
                        </div>
                        <div class="db-stat-card">
                            <div class="db-s-label">Pendapatan</div>
                            <div class="db-s-val" style="color:#f6af23">Rp{{ number_format(rand(15,25)*1000000/1000000,0,',','.')  }}JT</div>
                            <div class="db-s-trend"><i class="bi bi-arrow-up-short"></i>+8% vs lalu</div>
                        </div>
                    </div>

                    <div class="db-chart-area">
                        <div class="db-chart-label">Kehadiran Minggu Ini (%)</div>
                        <div class="db-bars">
                            <div class="db-bar" style="height:75%"></div>
                            <div class="db-bar" style="height:85%"></div>
                            <div class="db-bar" style="height:70%"></div>
                            <div class="db-bar hi" style="height:92%"></div>
                            <div class="db-bar" style="height:80%"></div>
                            <div class="db-bar hi" style="height:95%"></div>
                            <div class="db-bar" style="height:88%"></div>
                        </div>
                    </div>

                    <div class="db-students">
                        <div class="db-student-row" style="border-bottom:1px solid rgba(255,255,255,.08);padding:8px 12px">
                            <span style="font-size:.65rem;color:rgba(255,255,255,.4);font-weight:600;text-transform:uppercase;letter-spacing:.04em">Siswa Terbaru</span>
                        </div>
                        @foreach(\App\Models\Student::latest()->take(3)->get() as $s)
                        <div class="db-student-row">
                            <div class="db-s-avatar">{{ strtoupper(substr($s->name ?? 'S', 0, 1)) }}</div>
                            <span>{{ $s->name ?? 'Siswa' }}</span>
                            <span class="db-s-badge aktif">Aktif</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ──────────────────────────── ROLES ─────────────────────────────────────── --}}
<section class="section-pad roles-bg" id="roles">
    <div class="container-lp">
        <div class="text-center reveal">
            <div class="section-eyebrow"><i class="bi bi-person-badge"></i> Untuk Semua Pengguna</div>
            <h2 class="section-title">Satu Platform,<br>Empat Peran Pengguna</h2>
            <p class="section-subtitle mx-auto">Setiap pengguna mendapat portal yang disesuaikan dengan kebutuhan dan tanggung jawab mereka.</p>
        </div>

        <div class="roles-grid">
            <div class="role-card role-owner reveal reveal-delay-1">
                <div class="rc-icon"><i class="bi bi-crown-fill"></i></div>
                <div class="rc-role">Owner</div>
                <div class="rc-title">Pemilik Bimbel</div>
                <div class="rc-desc">Pantau semua cabang dari satu dashboard terpusat.</div>
                <ul class="rc-features">
                    <li><i class="bi bi-check-circle-fill"></i>Multi-cabang dashboard</li>
                    <li><i class="bi bi-check-circle-fill"></i>Laporan keuangan konsolidasi</li>
                    <li><i class="bi bi-check-circle-fill"></i>Log aktivitas sistem</li>
                    <li><i class="bi bi-check-circle-fill"></i>Analitik performa cabang</li>
                </ul>
            </div>

            <div class="role-card role-admin reveal reveal-delay-2">
                <div class="rc-icon"><i class="bi bi-shield-fill-check"></i></div>
                <div class="rc-role">Admin</div>
                <div class="rc-title">Admin Cabang</div>
                <div class="rc-desc">Kelola operasional harian satu cabang secara efisien.</div>
                <ul class="rc-features">
                    <li><i class="bi bi-check-circle-fill"></i>Kelola siswa & guru</li>
                    <li><i class="bi bi-check-circle-fill"></i>Invoice & pembayaran</li>
                    <li><i class="bi bi-check-circle-fill"></i>Jadwal & kelas</li>
                    <li><i class="bi bi-check-circle-fill"></i>Pengumuman cabang</li>
                </ul>
            </div>

            <div class="role-card role-guru reveal reveal-delay-3">
                <div class="rc-icon"><i class="bi bi-person-video3"></i></div>
                <div class="rc-role">Guru</div>
                <div class="rc-title">Pengajar</div>
                <div class="rc-desc">Fokus mengajar, kelola absensi dan nilai dengan mudah.</div>
                <ul class="rc-features">
                    <li><i class="bi bi-check-circle-fill"></i>Absensi digital</li>
                    <li><i class="bi bi-check-circle-fill"></i>Input nilai siswa</li>
                    <li><i class="bi bi-check-circle-fill"></i>Jadwal mengajar</li>
                    <li><i class="bi bi-check-circle-fill"></i>Chat dengan admin</li>
                </ul>
            </div>

            <div class="role-card role-siswa reveal reveal-delay-4">
                <div class="rc-icon"><i class="bi bi-mortarboard-fill"></i></div>
                <div class="rc-role">Siswa</div>
                <div class="rc-title">Pelajar</div>
                <div class="rc-desc">Portal mandiri untuk pantau belajar dan administrasi.</div>
                <ul class="rc-features">
                    <li><i class="bi bi-check-circle-fill"></i>Jadwal & materi belajar</li>
                    <li><i class="bi bi-check-circle-fill"></i>Nilai & perkembangan</li>
                    <li><i class="bi bi-check-circle-fill"></i>Status pembayaran</li>
                    <li><i class="bi bi-check-circle-fill"></i>Sertifikat digital</li>
                </ul>
            </div>
        </div>
    </div>
</section>

{{-- ──────────────────────────── TESTIMONIALS ──────────────────────────────── --}}
<section class="section-pad testimonials-bg" id="testimonials">
    <div class="container-lp testimonials-inner">
        <div class="text-center reveal">
            <div class="section-eyebrow" style="background:rgba(255,255,255,.1);border-color:rgba(255,255,255,.15);color:rgba(255,255,255,.9)">
                <i class="bi bi-chat-heart-fill"></i> Testimoni
            </div>
            <h2 class="section-title" style="color:white">Digunakan & Dipercaya<br>Pengelola Bimbel Terbaik</h2>
        </div>

        <div class="testi-grid">
            <div class="testi-card reveal reveal-delay-1">
                <div class="testi-stars">
                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                </div>
                <p class="testi-text">"Sebelumnya kami pakai spreadsheet untuk semua data. Sekarang dengan Smart Center, semua beres dalam hitungan menit. Penagihan siswa pun otomatis, tidak ada yang terlewat."</p>
                <div class="testi-author">
                    <div class="testi-avatar" style="background:linear-gradient(135deg,#c84ddf,#68117e)">R</div>
                    <div>
                        <div class="testi-name">Rini Kusumawati</div>
                        <div class="testi-role">Pemilik Bimbel Prestasi, Surabaya</div>
                    </div>
                </div>
            </div>

            <div class="testi-card reveal reveal-delay-2">
                <div class="testi-stars">
                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                </div>
                <p class="testi-text">"Manajemen 3 cabang sekarang jauh lebih mudah. Saya bisa pantau semua dari HP. Laporan keuangan bisa langsung saya lihat kapanpun, bahkan saat di luar kota."</p>
                <div class="testi-author">
                    <div class="testi-avatar" style="background:linear-gradient(135deg,#f6af23,#e09000)">D</div>
                    <div>
                        <div class="testi-name">Dimas Prasetyo</div>
                        <div class="testi-role">Owner 3 Cabang Bimbel Maju, Jakarta</div>
                    </div>
                </div>
            </div>

            <div class="testi-card reveal reveal-delay-3">
                <div class="testi-stars">
                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                </div>
                <p class="testi-text">"Sebagai guru, saya suka banget fitur absensi digitalnya. Siswa tidak bisa titip absen, semua tercatat otomatis. Input nilai pun sangat gampang dan cepat."</p>
                <div class="testi-author">
                    <div class="testi-avatar" style="background:linear-gradient(135deg,#10b981,#059669)">S</div>
                    <div>
                        <div class="testi-name">Siti Nuraini</div>
                        <div class="testi-role">Guru Matematika, Bimbel Cerdas Bandung</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ──────────────────────────── CTA ───────────────────────────────────────── --}}
<section class="cta-section">
    <div class="container-lp">
        <div class="cta-box reveal">
            <div class="cta-content">
                <div class="cta-eyebrow"><i class="bi bi-rocket-takeoff-fill"></i> Mulai Sekarang</div>
                <h2 class="cta-title">Siap Transformasi<br>Bimbel Anda?</h2>
                <p class="cta-desc">Bergabunglah bersama ratusan pengelola bimbel yang sudah merasakan manfaatnya. Daftar gratis, tanpa kartu kredit.</p>
                <div class="cta-btns">
                    <a href="{{ route('register') }}" class="btn-cta-primary">
                        <i class="bi bi-person-plus-fill"></i>
                        Daftar Gratis Sekarang
                    </a>
                    <a href="{{ route('login') }}" class="btn-cta-secondary">
                        <i class="bi bi-box-arrow-in-right"></i>
                        Masuk ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ──────────────────────────── FOOTER ────────────────────────────────────── --}}
<footer class="footer">
    <div class="container-lp">
        <div class="footer-grid">
            <div>
                <a href="{{ url('/') }}" class="nav-brand" style="text-decoration:none">
                    <div class="nav-brand-icon"><i class="bi bi-mortarboard-fill"></i></div>
                    <div class="nav-brand-text" style="color:white">
                        Smart Center<small style="color:rgba(255,255,255,.5)">Indonesia</small>
                    </div>
                </a>
                <p class="footer-brand-desc">Platform manajemen bimbel terpadu yang membantu pengelola lembaga pendidikan non-formal kelola operasional dengan lebih efisien.</p>
                <div class="footer-social">
                    <a href="#"><i class="bi bi-instagram"></i></a>
                    <a href="#"><i class="bi bi-facebook"></i></a>
                    <a href="#"><i class="bi bi-youtube"></i></a>
                    <a href="#"><i class="bi bi-whatsapp"></i></a>
                </div>
            </div>

            <div>
                <div class="footer-col-title">Produk</div>
                <ul class="footer-links">
                    <li><a href="#features">Fitur</a></li>
                    <li><a href="#how-it-works">Cara Kerja</a></li>
                    <li><a href="#roles">Pengguna</a></li>
                    <li><a href="{{ route('register') }}">Daftar</a></li>
                </ul>
            </div>

            <div>
                <div class="footer-col-title">Peran</div>
                <ul class="footer-links">
                    <li><a href="#">Owner</a></li>
                    <li><a href="#">Admin Cabang</a></li>
                    <li><a href="#">Guru</a></li>
                    <li><a href="#">Siswa</a></li>
                </ul>
            </div>

            <div>
                <div class="footer-col-title">Perusahaan</div>
                <ul class="footer-links">
                    <li><a href="#">Tentang Kami</a></li>
                    <li><a href="#">Blog</a></li>
                    <li><a href="#">Kontak</a></li>
                    <li><a href="#">Karir</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} Smart Center Indonesia. All rights reserved.</p>
            <div class="footer-bottom-links">
                <a href="#">Kebijakan Privasi</a>
                <a href="#">Syarat & Ketentuan</a>
                <a href="#">Bantuan</a>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
/* ── Navbar scroll ── */
const navbar = document.getElementById('navbar');
window.addEventListener('scroll', () => {
    navbar.classList.toggle('scrolled', window.scrollY > 40);
});

/* ── Mobile menu ── */
const toggle    = document.getElementById('navToggle');
const mobileMenu = document.getElementById('mobileMenu');
const mobileClose = document.getElementById('mobileClose');

toggle.addEventListener('click', () => {
    const open = mobileMenu.classList.toggle('open');
    toggle.classList.toggle('open', open);
    document.body.style.overflow = open ? 'hidden' : '';
});
mobileClose.addEventListener('click', closeMobile);
function closeMobile() {
    mobileMenu.classList.remove('open');
    toggle.classList.remove('open');
    document.body.style.overflow = '';
}

/* ── Particles ── */
(function createParticles() {
    const container = document.getElementById('particles');
    for (let i = 0; i < 30; i++) {
        const p = document.createElement('div');
        p.className = 'particle';
        const size = Math.random() * 3 + 2;
        p.style.cssText = `
            width:${size}px;height:${size}px;
            left:${Math.random()*100}%;
            animation-duration:${Math.random()*15+10}s;
            animation-delay:${Math.random()*10}s;
            opacity:${Math.random()*.5+.2};
        `;
        container.appendChild(p);
    }
})();

/* ── Scroll-reveal ── */
const revealEls = document.querySelectorAll('.reveal');
const revealObs = new IntersectionObserver((entries) => {
    entries.forEach(e => {
        if (e.isIntersecting) {
            e.target.classList.add('visible');
            revealObs.unobserve(e.target);
        }
    });
}, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
revealEls.forEach(el => revealObs.observe(el));

/* ── Count-up ── */
function countUp(el) {
    const target = parseInt(el.dataset.target, 10);
    if (!target) return;
    const duration = 1600;
    const step = target / (duration / 16);
    let current = 0;
    const timer = setInterval(() => {
        current = Math.min(current + step, target);
        el.textContent = Math.floor(current) + (target > 0 ? '+' : '');
        if (current >= target) clearInterval(timer);
    }, 16);
}

const countObs = new IntersectionObserver((entries) => {
    entries.forEach(e => {
        if (e.isIntersecting) {
            countUp(e.target);
            countObs.unobserve(e.target);
        }
    });
}, { threshold: 0.5 });
document.querySelectorAll('.count-up[data-target]').forEach(el => countObs.observe(el));

/* ── Smooth anchor scroll ── */
document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', e => {
        const id = a.getAttribute('href').slice(1);
        const target = document.getElementById(id);
        if (target) {
            e.preventDefault();
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
});
</script>
</body>
</html>
