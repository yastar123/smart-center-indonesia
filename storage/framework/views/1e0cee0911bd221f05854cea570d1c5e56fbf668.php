<?php
    $stats = [
        'students' => \App\Models\Student::where('status','aktif')->count(),
        'teachers' => \App\Models\Teacher::where('status','aktif')->count(),
        'branches' => \App\Models\Branch::count(),
    ];
    $tutors = \App\Models\Teacher::where('status','aktif')->get();
    if ($tutors->isEmpty()) {
        $tutors = collect([
            (object)['name'=>'Ahmad Fauzi, S.Pd',   'subjects'=>['Matematika'],        'photo'=>null],
            (object)['name'=>'Sarah Dewi, M.Pd',    'subjects'=>['Bahasa Inggris'],     'photo'=>null],
            (object)['name'=>'Budi Santoso, S.Si',  'subjects'=>['IPA / Fisika'],       'photo'=>null],
            (object)['name'=>'Rina Agustina, S.Kom','subjects'=>['Komputer & IT'],      'photo'=>null],
            (object)['name'=>'Dina Rahayu, S.Pd',   'subjects'=>['Bahasa Indonesia'],   'photo'=>null],
            (object)['name'=>'Eko Prasetyo, M.Sc',  'subjects'=>['Kimia'],             'photo'=>null],
        ]);
    }
    // Landing page DB content
    $lsAll       = \App\Models\LandingSetting::all()->keyBy('key');
    $ls          = fn(string $k, string $d='') => $lsAll[$k]->value ?? $d;
    $dbTestis    = \App\Models\LandingTestimonial::active()->orderBy('sort_order')->get();
    $dbPrograms  = \App\Models\LandingProgram::active()->orderBy('sort_order')->get();
    $waMain      = \App\Models\LandingWaNumber::primaryNumber($ls('footer.wa_number','628001234567'));
    $waNumbers   = \App\Models\LandingWaNumber::active()->orderBy('sort_order')->get();
    $tutorGrads = [
        'linear-gradient(160deg,#260632,#c84ddf)',
        'linear-gradient(160deg,#1a3a6b,#2563eb)',
        'linear-gradient(160deg,#064e3b,#10b981)',
        'linear-gradient(160deg,#7c2d12,#f97316)',
        'linear-gradient(160deg,#312e81,#8b5cf6)',
        'linear-gradient(160deg,#881337,#f43f5e)',
        'linear-gradient(160deg,#134e4a,#14b8a6)',
        'linear-gradient(160deg,#422006,#f59e0b)',
    ];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Smart Center Indonesia — Lembaga bimbingan belajar, kursus, dan les privat terbaik di Indonesia. Melayani TK hingga umum dengan tutor profesional, metode modern, dan hasil terukur.">
    <title>Smart Center Indonesia | Bimbel & Kursus Terbaik #1 di Indonesia</title>

    <meta property="og:title" content="Smart Center Indonesia | Bimbel & Kursus Terbaik #1">
    <meta property="og:description" content="Lembaga bimbingan belajar, kursus, dan les privat terbaik di Indonesia. Tutor profesional, metode modern, hasil terukur.">
    <meta property="og:type" content="website">
    <meta name="theme-color" content="#260632">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
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
        body { font-family: var(--font-sans); color: var(--text); background: var(--white); overflow-x: hidden; }
        h1,h2,h3,h4,h5,h6 { font-family: var(--font-display); letter-spacing: -.025em; }

        /* ─── NAVBAR ─────────────────────────────────────────────── */
        .lp-nav {
            position:fixed; top:0; left:0; right:0; z-index:1000;
            background:white;
            border-bottom:1px solid rgba(0,0,0,.06);
            box-shadow:0 2px 16px rgba(0,0,0,.06);
            padding:0;
            transition:box-shadow .3s;
        }
        .nav-inner {
            display:flex; align-items:center; justify-content:space-between;
            max-width:1280px; margin:0 auto; padding:0 2rem;
            height:64px;
        }
        .nav-brand { display:flex; align-items:center; gap:10px; text-decoration:none; }
        .nav-brand-icon {
            width:40px; height:40px; border-radius:12px;
            background:linear-gradient(135deg,var(--primary-dark),var(--primary));
            display:flex; align-items:center; justify-content:center; font-size:14px; color:white; font-weight:900;
            flex-shrink:0; box-shadow:0 4px 12px rgba(200,77,223,.35); font-family:var(--font-display);
            letter-spacing:-.03em;
        }
        .nav-brand-text { font-family:var(--font-display); font-weight:800; font-size:1rem; color:var(--deep); letter-spacing:-.02em; line-height:1.15; }
        .nav-brand-text small { display:block; font-size:.62rem; font-weight:500; color:var(--text-muted); letter-spacing:.02em; }

        .nav-links { display:flex; align-items:center; gap:.1rem; list-style:none; }
        .nav-link-item { color:#374151; text-decoration:none; font-size:.87rem; font-weight:500; padding:.45rem .9rem; border-radius:8px; transition:color .2s, background .2s; }
        .nav-link-item:hover { color:var(--primary-dark); background:rgba(200,77,223,.07); }
        .nav-link-item.nav-active { color:var(--primary-dark); font-weight:600; }

        .nav-cta { display:flex; align-items:center; gap:.6rem; }
        .btn-nav-login { padding:.45rem 1.2rem; border-radius:10px; font-size:.87rem; font-weight:600; color:var(--deep); border:1.5px solid rgba(38,6,50,.15); background:transparent; text-decoration:none; transition:.2s; }
        .btn-nav-login:hover { color:var(--primary-dark); border-color:var(--primary); background:rgba(200,77,223,.06); }

        .btn-nav-register { padding:.48rem 1.25rem; border-radius:10px; font-size:.87rem; font-weight:700; color:white; background:linear-gradient(135deg,var(--primary-dark),var(--primary)); text-decoration:none; border:none; transition:transform .2s, box-shadow .2s; box-shadow:0 4px 14px rgba(200,77,223,.35); }
        .btn-nav-register:hover { transform:translateY(-2px); box-shadow:0 8px 22px rgba(200,77,223,.5); color:white; }

        .nav-toggle { display:none; flex-direction:column; gap:5px; cursor:pointer; padding:6px; background:none; border:none; }
        .nav-toggle span { display:block; width:24px; height:2px; background:var(--deep); border-radius:2px; transition:.3s var(--ease-in-out); }
        .nav-toggle.open span:nth-child(1) { transform:translateY(7px) rotate(45deg); }
        .nav-toggle.open span:nth-child(2) { opacity:0; transform:scaleX(0); }
        .nav-toggle.open span:nth-child(3) { transform:translateY(-7px) rotate(-45deg); }

        .mobile-menu {
            display:flex; position:fixed; top:0; left:0; right:0; bottom:0;
            background:rgba(38,6,50,.97); backdrop-filter:blur(20px); -webkit-backdrop-filter:blur(20px);
            z-index:999; flex-direction:column; align-items:center; justify-content:center; gap:1.5rem;
            opacity:0; transform:scale(.96); visibility:hidden; pointer-events:none;
            transition:opacity .35s var(--ease-out), transform .35s var(--ease-out), visibility .35s;
        }
        .mobile-menu.open { opacity:1; transform:scale(1); visibility:visible; pointer-events:auto; }
        .mobile-menu a { color:rgba(255,255,255,.85); text-decoration:none; font-size:1.35rem; font-family:var(--font-display); font-weight:700; letter-spacing:-.02em; transition:color .2s, transform .2s; }
        .mobile-menu a:hover { color:white; transform:translateX(4px); }
        .mobile-menu .mobile-divider { width:40px; height:2px; background:rgba(255,255,255,.1); border-radius:2px; margin:.25rem 0; }
        .mobile-close { position:absolute; top:1.5rem; right:1.5rem; background:rgba(255,255,255,.1); border:1px solid rgba(255,255,255,.12); color:white; width:42px; height:42px; border-radius:50%; font-size:1.1rem; cursor:pointer; transition:background .2s; display:flex; align-items:center; justify-content:center; }
        .mobile-close:hover { background:rgba(255,255,255,.2); }

        /* ─── HERO ─────────────────────────────────────────────────── */
        .hero { position:relative; height:100vh; min-height:560px; overflow:hidden; }
        .hero-slides { position:absolute; inset:0; z-index:0; }
        .hero-slide { position:absolute; inset:0; background-size:cover; background-position:center center; background-repeat:no-repeat; opacity:0; transition:opacity 1.4s ease-in-out; }
        .hero-slide.active { opacity:1; animation:hero-zoom 9s ease-in-out forwards; }
        @keyframes hero-zoom { from { transform:scale(1.06); } to { transform:scale(1.0); } }
        .hero-slide-overlay { position:absolute; inset:0; background:linear-gradient(160deg, rgba(104,17,126,.55) 0%, rgba(38,6,50,.45) 50%, rgba(104,17,126,.6) 100%); }

        /* Arrow nav */
        .hero-arrow {
            position:absolute; top:50%; transform:translateY(-50%); z-index:4;
            width:42px; height:42px; border-radius:50%;
            background:rgba(255,255,255,.18); backdrop-filter:blur(8px); -webkit-backdrop-filter:blur(8px);
            border:1.5px solid rgba(255,255,255,.3);
            color:white; font-size:1rem; cursor:pointer;
            display:flex; align-items:center; justify-content:center;
            transition:background .2s, transform .2s;
        }
        .hero-arrow:hover { background:rgba(255,255,255,.32); transform:translateY(-50%) scale(1.08); }
        .hero-arrow-left  { left:1.5rem; }
        .hero-arrow-right { right:1.5rem; }

        /* Notification popup */
        .hero-notify {
            position:absolute; top:5.5rem; left:1.5rem; z-index:4;
            background:white; border-radius:16px;
            padding:12px 14px 12px 12px;
            box-shadow:0 12px 40px rgba(0,0,0,.22), 0 0 0 1px rgba(0,0,0,.04);
            display:flex; align-items:flex-start; gap:10px;
            max-width:260px;
            animation:notify-in .7s var(--ease-out) 1s both;
        }
        @keyframes notify-in { from { opacity:0; transform:translateX(-18px); } to { opacity:1; transform:translateX(0); } }
        .notify-avatar {
            width:38px; height:38px; border-radius:50%; flex-shrink:0;
            background:linear-gradient(135deg,var(--primary-dark),var(--primary));
            display:flex; align-items:center; justify-content:center;
            font-size:.9rem; font-weight:800; color:white; font-family:var(--font-display);
            position:relative;
        }
        .notify-dot {
            position:absolute; bottom:0; right:0;
            width:10px; height:10px; border-radius:50%;
            background:#22c55e; border:2px solid white;
        }
        .notify-body { flex:1; min-width:0; }
        .notify-title { font-size:.78rem; font-weight:700; color:var(--deep); line-height:1.3; }
        .notify-sub { font-size:.71rem; color:var(--text-muted); margin-top:3px; }
        .notify-time { font-size:.67rem; color:var(--primary); font-weight:600; margin-top:5px; display:flex; align-items:center; gap:3px; }
        .notify-close {
            position:absolute; top:8px; right:8px;
            width:18px; height:18px; border-radius:50%;
            background:rgba(0,0,0,.06); border:none; cursor:pointer;
            display:flex; align-items:center; justify-content:center;
            font-size:.6rem; color:#666; transition:background .2s;
        }
        .notify-close:hover { background:rgba(0,0,0,.12); }

        /* SCROLL + dots */
        .hero-scroll-hint {
            position:absolute; bottom:2rem; left:50%; transform:translateX(-50%);
            z-index:4; display:flex; flex-direction:column; align-items:center; gap:6px;
        }
        .hero-scroll-text {
            font-size:.65rem; font-weight:700; letter-spacing:.18em; text-transform:uppercase;
            color:rgba(255,255,255,.7);
        }
        .hero-scroll-chevron {
            color:rgba(255,255,255,.6); font-size:.75rem;
            animation:bounce-down 1.6s ease-in-out infinite;
        }
        @keyframes bounce-down { 0%,100% { transform:translateY(0); } 50% { transform:translateY(5px); } }
        .hero-dots { display:flex; align-items:center; gap:8px; margin-top:2px; }
        .hero-dot { width:8px; height:8px; border-radius:50%; background:rgba(255,255,255,.38); border:none; cursor:pointer; transition:background .35s, width .35s, border-radius .35s; padding:0; }
        .hero-dot.active { background:white; width:26px; border-radius:4px; }
        .hero-dot:hover:not(.active) { background:rgba(255,255,255,.7); }

        /* Float cards — keep for potential reuse but hidden in hero redesign */
        .float-card { display:none !important; }

        @keyframes fade-up { from { opacity:0; transform:translateY(28px); } to { opacity:1; transform:translateY(0); } }

        /* ─── GOLD TICKER BAR ──────────────────────────────────── */
        .hero-ticker {
            background:var(--gold);
            overflow:hidden; white-space:nowrap;
            padding:.55rem 0;
            position:relative; z-index:5;
        }
        .ticker-track {
            display:inline-flex; gap:0;
            animation:ticker-scroll 28s linear infinite;
        }
        .ticker-track:hover { animation-play-state:paused; }
        @keyframes ticker-scroll { 0% { transform:translateX(0); } 100% { transform:translateX(-50%); } }
        .ticker-item {
            display:inline-flex; align-items:center; gap:.5rem;
            padding:0 2.5rem;
            font-size:.82rem; font-weight:700; color:#1a0a00;
        }
        .ticker-sep { color:rgba(0,0,0,.25); font-size:.9rem; }

        /* ─── SCROLL PROGRESS BAR ─────────────────────────────── */
        #scroll-progress {
            position:fixed; top:0; left:0; width:0%; height:3px;
            background:linear-gradient(90deg,#68117e,#c84ddf,#f6af23);
            z-index:2000; transition:width .1s linear;
            border-radius:0 3px 3px 0;
            box-shadow:0 0 8px rgba(200,77,223,.5);
        }

        /* ─── FLOAT CARDS — hide on mobile ───────────────────── */
        @media (max-width:900px) { .float-card { display:none !important; } }

        /* ─── MOBILE MENU — stagger entrance ─────────────────── */
        .mobile-menu a, .mobile-menu .mobile-divider {
            opacity:0; transform:translateY(20px);
            transition:opacity .45s var(--ease-out), transform .45s var(--ease-out);
        }
        .mobile-menu.open a:nth-child(1)  { opacity:1; transform:none; transition-delay:.06s; }
        .mobile-menu.open a:nth-child(2)  { opacity:1; transform:none; transition-delay:.11s; }
        .mobile-menu.open a:nth-child(3)  { opacity:1; transform:none; transition-delay:.16s; }
        .mobile-menu.open a:nth-child(4)  { opacity:1; transform:none; transition-delay:.21s; }
        .mobile-menu.open a:nth-child(5)  { opacity:1; transform:none; transition-delay:.26s; }
        .mobile-menu.open a:nth-child(6)  { opacity:1; transform:none; transition-delay:.31s; }
        .mobile-menu.open .mobile-divider { opacity:1; transform:none; transition-delay:.34s; }
        .mobile-menu.open a:nth-child(8)  { opacity:1; transform:none; transition-delay:.38s; }
        .mobile-menu.open a:nth-child(9)  { opacity:1; transform:none; transition-delay:.43s; }
        /* close button always visible */
        .mobile-menu .mobile-close { opacity:1 !important; transform:none !important; }

        /* ─── TENTANG SECTION ────────────────────────────────────── */
        .tentang-section { background:#fdf5ff; padding:5rem 0; }
        .tentang-inner { max-width:1160px; margin:0 auto; padding:0 1.5rem; display:grid; grid-template-columns:1fr 1fr; gap:5rem; align-items:center; }
        .tentang-pills { display:flex; flex-wrap:wrap; gap:.5rem; margin-bottom:1.25rem; }
        .tentang-pill {
            display:inline-flex; align-items:center; gap:5px;
            border:1.5px solid rgba(200,77,223,.25); background:rgba(200,77,223,.06);
            border-radius:50px; padding:4px 14px; font-size:.76rem; font-weight:600; color:var(--primary-dark);
        }
        .tentang-pill.neutral { border-color:rgba(0,0,0,.13); background:transparent; color:#555; }
        .tentang-title { font-size:clamp(1.6rem,2.5vw,2.5rem); font-weight:900; color:var(--deep); line-height:1.2; margin-bottom:1.25rem; }
        .tentang-title-accent { font-style:italic; color:var(--primary); font-family:var(--font-display); }
        .tentang-desc { font-size:.95rem; color:#444; line-height:1.8; margin-bottom:.85rem; }
        .tentang-desc-quote { font-style:italic; color:var(--primary); font-weight:600; }

        .tentang-features { display:grid; grid-template-columns:1fr 1fr; gap:.75rem; }
        .tentang-feat {
            background:white; border-radius:14px; padding:.9rem 1.1rem;
            border:1.5px solid rgba(200,77,223,.1);
            display:flex; align-items:center; gap:.75rem;
            box-shadow:0 2px 10px rgba(38,6,50,.05);
            transition:transform .25s, box-shadow .25s, border-color .25s;
        }
        .tentang-feat:hover { transform:translateY(-3px); box-shadow:0 8px 24px rgba(38,6,50,.1); border-color:rgba(200,77,223,.25); }
        .tentang-feat-icon {
            width:38px; height:38px; border-radius:10px; flex-shrink:0;
            background:linear-gradient(135deg,var(--primary-dark),var(--primary));
            display:flex; align-items:center; justify-content:center;
            font-size:.95rem; color:white;
        }
        .tentang-feat-label { font-size:.86rem; font-weight:700; color:var(--deep); line-height:1.3; }

        @media (max-width:900px) { .tentang-inner { grid-template-columns:1fr; gap:2.5rem; } }
        @media (max-width:480px) { .tentang-features { grid-template-columns:1fr; } .tentang-section { padding:3.5rem 0; } }

        /* ─── PROGRAM UNGGULAN PHOTO CARDS ───────────────────────── */
        .program-photo-section { background:#fff; padding:6rem 0; }
        .program-photo-eyebrow { display:inline-flex; align-items:center; gap:8px; color:var(--primary); font-size:.78rem; font-weight:700; text-transform:uppercase; letter-spacing:.08em; margin-bottom:1rem; }
        .program-photo-eyebrow::before { content:''; display:inline-block; width:28px; height:2px; background:var(--primary); border-radius:2px; }
        .section-title-accent { font-style:italic; color:var(--primary); font-family:var(--font-display); }
        .program-photo-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:1.5rem; margin-top:3rem; }
        .ppc {
            background:white; border-radius:20px; overflow:hidden;
            border:1px solid rgba(200,77,223,.1);
            box-shadow:0 4px 20px rgba(38,6,50,.06);
            cursor:pointer; transition:transform .3s var(--ease-out), box-shadow .3s;
            text-decoration:none; color:inherit; display:flex; flex-direction:column;
        }
        .ppc:hover { transform:translateY(-6px); box-shadow:0 20px 52px rgba(38,6,50,.14); }
        .ppc-img-wrap { width:100%; height:200px; overflow:hidden; }
        .ppc-img-wrap img { width:100%; height:100%; object-fit:cover; display:block; transition:transform .5s var(--ease-out); }
        .ppc:hover .ppc-img-wrap img { transform:scale(1.07); }
        .ppc-body { padding:1.5rem 1.75rem 1.75rem; flex:1; display:flex; flex-direction:column; }
        .ppc-badge { display:inline-flex; align-items:center; gap:5px; font-size:.65rem; font-weight:700; padding:4px 12px; border-radius:50px; text-transform:uppercase; letter-spacing:.05em; margin-bottom:.875rem; width:fit-content; }
        .ppc-title { font-size:1.08rem; font-weight:800; color:var(--deep); margin-bottom:.45rem; font-family:var(--font-display); line-height:1.3; }
        .ppc-desc { font-size:.875rem; color:var(--text-muted); line-height:1.65; margin-bottom:1.25rem; flex:1; }
        .ppc-link { font-size:.82rem; font-weight:700; color:var(--primary); display:inline-flex; align-items:center; gap:5px; transition:gap .2s; }
        .ppc:hover .ppc-link { gap:9px; }
        @media (max-width:900px) { .program-photo-grid { grid-template-columns:1fr 1fr; } .program-photo-section { padding:4.5rem 0; } }
        @media (max-width:600px) { .program-photo-grid { grid-template-columns:1fr; } .program-photo-section { padding:3.5rem 0; } }

        /* ─── STATS STRIP ─────────────────────────────────────────── */
        .stats-strip { background:var(--off-white); border-top:1px solid rgba(200,77,223,.08); border-bottom:1px solid rgba(200,77,223,.08); padding:2.5rem 0; }
        .stats-strip-inner { max-width:1100px; margin:0 auto; padding:0 1.5rem; display:grid; grid-template-columns:repeat(4,1fr); gap:1rem; }
        .stat-item { text-align:center; padding:1rem; border-right:1px solid rgba(200,77,223,.1); transition:transform .3s; }
        .stat-item:last-child { border-right:none; }
        .stat-item:hover { transform:translateY(-3px); }
        .stat-item .si-num { font-size:clamp(1.8rem,3vw,2.4rem); font-weight:900; font-family:var(--font-display); background:linear-gradient(135deg,var(--deep),var(--primary)); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; letter-spacing:-.03em; line-height:1; }
        .stat-item .si-label { font-size:.82rem; color:var(--text-muted); font-weight:500; margin-top:.4rem; }

        /* ─── SECTION COMMONS ─────────────────────────────────────── */
        .section-eyebrow { display:inline-flex; align-items:center; gap:6px; background:rgba(200,77,223,.08); border:1px solid rgba(200,77,223,.15); border-radius:50px; padding:5px 16px; font-size:.75rem; font-weight:700; color:var(--primary-dark); text-transform:uppercase; letter-spacing:.06em; margin-bottom:1rem; }
        .section-title { font-size:clamp(1.8rem,3vw,2.6rem); font-weight:900; color:var(--deep); line-height:1.2; margin-bottom:1rem; }
        .section-subtitle { font-size:1rem; color:var(--text-muted); line-height:1.7; max-width:560px; }
        .section-pad { padding:6rem 0; }
        .container-lp { max-width:1160px; margin:0 auto; padding:0 1.5rem; }

        /* ─── JENJANG PENDIDIKAN ──────────────────────────────────── */
        .jenjang-bg { background:#f3e8ff; }
        .jenjang-eyebrow { display:flex; align-items:center; gap:.6rem; justify-content:center; margin-bottom:.75rem; color:var(--primary); font-size:.72rem; font-weight:700; letter-spacing:.12em; text-transform:uppercase; }
        .jenjang-eyebrow::before { content:''; width:28px; height:1.5px; background:var(--primary); border-radius:2px; }
        .jenjang-title-wrap { font-size:clamp(2rem,3.5vw,3rem); font-weight:900; color:var(--deep); line-height:1.15; }
        .jenjang-title-accent { font-style:italic; color:var(--primary); }
        .jenjang-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:1.25rem; margin-top:2.75rem; }
        .jenjang-card {
            background:white; border-radius:20px; padding:2rem 1.5rem 1.5rem;
            border:1.5px solid rgba(200,77,223,.1);
            box-shadow:0 4px 18px rgba(38,6,50,.06);
            text-align:center; position:relative;
            transition:transform .3s var(--ease-out), box-shadow .3s, border-color .3s;
            cursor:pointer; text-decoration:none; display:block; color:inherit;
        }
        .jenjang-card:hover { transform:translateY(-6px); box-shadow:0 16px 48px rgba(38,6,50,.14); border-color:rgba(200,77,223,.3); }
        .jc-photo-wrap { width:90px; height:90px; border-radius:50%; overflow:hidden; margin:0 auto 1.1rem; border:3px solid rgba(200,77,223,.18); box-shadow:0 4px 14px rgba(104,17,126,.15); }
        .jc-photo-wrap img { width:100%; height:100%; object-fit:cover; object-position:center top; }
        .jc-photo-fallback { width:100%; height:100%; background:linear-gradient(135deg,var(--primary-dark),var(--primary)); display:flex; align-items:center; justify-content:center; font-size:1.8rem; }
        .jc-name { font-size:1.3rem; font-weight:900; font-family:var(--font-display); color:var(--deep); line-height:1; margin-bottom:.3rem; }
        .jc-label { font-size:.8rem; font-weight:500; color:var(--text-muted); margin-bottom:1rem; }
        .jc-link { font-size:.8rem; font-weight:700; color:var(--primary); display:inline-flex; align-items:center; gap:4px; transition:gap .2s; }
        .jenjang-card:hover .jc-link { gap:8px; }

        /* ─── CARI GURU SECTION ───────────────────────────────────── */
        .cari-guru-section {
            background:linear-gradient(135deg,#1a0228 0%,#260632 40%,#461256 80%,#68117e 100%);
            padding:5rem 0 4rem;
            position:relative; overflow:hidden;
        }
        .cari-guru-section::before {
            content:''; position:absolute; inset:0;
            background-image:radial-gradient(circle at 80% 20%,rgba(200,77,223,.18) 0%,transparent 55%),
                             radial-gradient(circle at 20% 80%,rgba(246,175,35,.08) 0%,transparent 50%);
            pointer-events:none;
        }
        .cari-guru-inner { position:relative; z-index:1; max-width:860px; margin:0 auto; padding:0 1.5rem; text-align:center; }
        .cg-eyebrow { display:flex; align-items:center; gap:.6rem; justify-content:center; margin-bottom:.85rem; color:var(--gold); font-size:.72rem; font-weight:700; letter-spacing:.12em; text-transform:uppercase; }
        .cg-eyebrow::before { content:''; width:28px; height:1.5px; background:var(--gold); border-radius:2px; }
        .cg-title { font-size:clamp(2rem,3.8vw,3.2rem); font-weight:900; color:white; line-height:1.15; margin-bottom:.85rem; }
        .cg-title-accent { font-style:italic; color:var(--gold); }
        .cg-subtitle { font-size:.97rem; color:rgba(255,255,255,.65); line-height:1.75; max-width:520px; margin:0 auto 2.5rem; }
        .cg-form {
            background:rgba(255,255,255,.07); backdrop-filter:blur(16px); -webkit-backdrop-filter:blur(16px);
            border:1px solid rgba(255,255,255,.12); border-radius:20px;
            padding:1.75rem 2rem;
        }
        .cg-fields { display:grid; grid-template-columns:1fr 1fr 1fr auto; gap:1rem; align-items:end; margin-bottom:1.1rem; }
        .cg-field label { display:block; font-size:.67rem; font-weight:700; color:rgba(255,255,255,.55); text-transform:uppercase; letter-spacing:.1em; margin-bottom:.5rem; display:flex; align-items:center; gap:5px; }
        .cg-select {
            width:100%; padding:.7rem 1rem; border-radius:12px;
            background:rgba(255,255,255,.1); border:1.5px solid rgba(255,255,255,.15);
            color:white; font-size:.9rem; font-family:var(--font-sans); font-weight:500;
            appearance:none; -webkit-appearance:none;
            background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='rgba(255,255,255,.5)' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
            background-repeat:no-repeat; background-position:right .85rem center;
            padding-right:2.25rem;
            transition:border-color .2s, background .2s;
        }
        .cg-select:focus { outline:none; border-color:rgba(200,77,223,.6); background-color:rgba(255,255,255,.15); }
        .cg-select option { background:#260632; color:white; }
        .btn-cg-search {
            display:inline-flex; align-items:center; gap:.5rem;
            padding:.72rem 1.5rem; border-radius:12px; border:none; cursor:pointer;
            background:linear-gradient(135deg,var(--gold-dark),var(--gold));
            color:#1a0a00; font-size:.92rem; font-weight:800; font-family:var(--font-display);
            white-space:nowrap;
            transition:transform .2s, box-shadow .2s;
            box-shadow:0 6px 20px rgba(246,175,35,.4);
        }
        .btn-cg-search:hover { transform:translateY(-2px); box-shadow:0 10px 28px rgba(246,175,35,.6); }
        .cg-trust { display:flex; align-items:center; justify-content:center; gap:1.75rem; flex-wrap:wrap; margin-top:.1rem; }
        .cg-trust-item { display:flex; align-items:center; gap:.4rem; font-size:.76rem; color:rgba(255,255,255,.55); font-weight:500; }
        .cg-trust-item i { color:var(--gold); font-size:.8rem; }

        @media (max-width:768px) {
            .cg-fields { grid-template-columns:1fr; }
            .btn-cg-search { width:100%; justify-content:center; }
            .cg-trust { gap:1rem; }
        }

        /* ─── PROGRAM UNGGULAN ────────────────────────────────────── */
        .program-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:1.25rem; margin-top:3rem; }
        .program-card {
            background:white; border-radius:22px; padding:2rem 1.75rem;
            border:1.5px solid rgba(200,77,223,.1);
            box-shadow:0 4px 18px rgba(38,6,50,.05);
            cursor:pointer; transition:transform .3s var(--ease-out), box-shadow .3s, border-color .3s;
            position:relative; overflow:hidden;
        }
        .program-card:hover { transform:translateY(-6px); box-shadow:0 18px 50px rgba(38,6,50,.14); border-color:rgba(200,77,223,.3); }
        .pc-badge { display:inline-flex; align-items:center; gap:5px; font-size:.68rem; font-weight:700; padding:3px 10px; border-radius:50px; margin-bottom:1rem; text-transform:uppercase; letter-spacing:.04em; }
        .pc-icon-wrap { width:52px; height:52px; border-radius:16px; display:flex; align-items:center; justify-content:center; margin-bottom:1rem; font-size:1.5rem; }
        .pc-title { font-size:1.1rem; font-weight:800; color:var(--deep); margin-bottom:.5rem; font-family:var(--font-display); }
        .pc-desc { font-size:.87rem; color:var(--text-muted); line-height:1.65; margin-bottom:1.25rem; }
        .pc-link { font-size:.82rem; font-weight:700; color:var(--primary); display:inline-flex; align-items:center; gap:5px; transition:gap .2s; }
        .program-card:hover .pc-link { gap:9px; }

        /* ─── MENGAPA SCI ─────────────────────────────────────────── */
        .why-bg { background:var(--off-white); }
        .why-grid { display:grid; grid-template-columns:repeat(5,1fr); gap:1.25rem; margin-top:3rem; }
        .why-card {
            background:white; border-radius:22px; padding:2rem 1.5rem;
            border:1px solid rgba(200,77,223,.1); text-align:center;
            box-shadow:0 4px 18px rgba(38,6,50,.05);
            transition:transform .35s var(--ease-out), box-shadow .35s;
        }
        .why-card:hover { transform:translateY(-8px); box-shadow:0 20px 55px rgba(38,6,50,.14); }
        .why-num { font-size:2.5rem; font-weight:900; font-family:var(--font-display); background:linear-gradient(135deg,var(--deep),var(--primary)); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; line-height:1; margin-bottom:.5rem; }
        .why-icon-wrap { width:58px; height:58px; border-radius:16px; background:linear-gradient(135deg,var(--deep),var(--primary)); display:flex; align-items:center; justify-content:center; margin:0 auto .875rem; font-size:1.4rem; color:white; box-shadow:0 8px 20px rgba(104,17,126,.35); }
        .why-title { font-size:.95rem; font-weight:800; color:var(--deep); margin-bottom:.5rem; font-family:var(--font-display); }
        .why-desc { font-size:.8rem; color:var(--text-muted); line-height:1.6; }

        /* ─── HOW IT WORKS ────────────────────────────────────────── */
        .how-inner { display:grid; grid-template-columns:1fr 1fr; gap:5rem; align-items:center; }
        .steps-list { list-style:none; display:flex; flex-direction:column; gap:1.5rem; margin-top:2.5rem; }
        .step-item { display:flex; gap:1.25rem; align-items:flex-start; }
        .step-num { flex-shrink:0; width:44px; height:44px; border-radius:14px; background:linear-gradient(135deg,var(--deep),var(--primary)); display:flex; align-items:center; justify-content:center; font-size:.78rem; font-weight:900; color:white; letter-spacing:.02em; font-family:var(--font-display); box-shadow:0 6px 18px rgba(104,17,126,.3); }
        .step-body {}
        .step-title { font-size:1rem; font-weight:700; color:var(--deep); margin-bottom:.3rem; font-family:var(--font-display); }
        .step-desc { font-size:.88rem; color:var(--text-muted); line-height:1.6; }
        .how-visual { position:relative; }
        .how-visual-img { width:100%; border-radius:28px; overflow:hidden; box-shadow:0 30px 80px rgba(38,6,50,.22); position:relative; }
        .how-visual-img img { width:100%; height:420px; object-fit:cover; display:block; }
        .how-visual-badge { position:absolute; bottom:-1.5rem; left:-1.5rem; background:white; border-radius:18px; padding:1rem 1.4rem; box-shadow:0 16px 48px rgba(0,0,0,.18); display:flex; align-items:center; gap:12px; min-width:200px; }
        .hvb-icon { width:44px; height:44px; border-radius:12px; background:linear-gradient(135deg,var(--gold),#f8d07a); display:flex; align-items:center; justify-content:center; font-size:1.2rem; flex-shrink:0; }
        .hvb-text .hvb-val { font-size:1.2rem; font-weight:900; color:var(--deep); font-family:var(--font-display); line-height:1; }
        .hvb-text .hvb-lab { font-size:.7rem; color:var(--text-muted); font-weight:500; margin-top:2px; }

        /* ─── TESTIMONIALS INFINITE CAROUSEL ─────────────────────── */
        .testimonials-bg { background:linear-gradient(135deg,var(--deep) 0%,var(--mid) 50%,#8b1fa8 100%); position:relative; overflow:hidden; }
        .testimonials-bg::before { content:''; position:absolute; inset:0; background-image:linear-gradient(rgba(255,255,255,.03) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.03) 1px,transparent 1px); background-size:40px 40px; }
        .testimonials-inner { position:relative; z-index:1; }

        .carousel-viewport {
            overflow:hidden; position:relative; margin-top:3rem;
        }
        .carousel-viewport::before,
        .carousel-viewport::after {
            content:''; position:absolute; top:0; bottom:0; width:120px; z-index:2; pointer-events:none;
        }
        .carousel-viewport::before { left:0;  background:linear-gradient(to right, rgba(38,6,50,1) 0%, transparent 100%); }
        .carousel-viewport::after  { right:0; background:linear-gradient(to left,  rgba(38,6,50,1) 0%, transparent 100%); }

        .carousel-track {
            display:flex; gap:1.25rem;
            width:max-content;
            animation: marquee-scroll 38s linear infinite;
        }
        .carousel-track:hover { animation-play-state: paused; }

        @keyframes marquee-scroll {
            0%   { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        .testi-card {
            background:rgba(255,255,255,.07); backdrop-filter:blur(16px);
            border:1px solid rgba(255,255,255,.1); border-radius:22px;
            padding:1.75rem; width:360px; flex-shrink:0;
            transition:transform .3s, background .3s;
        }
        .testi-card:hover { transform:translateY(-5px); background:rgba(255,255,255,.11); }
        .testi-stars { display:flex; gap:3px; margin-bottom:1rem; color:var(--gold); font-size:.85rem; }
        .testi-text { font-size:.88rem; color:rgba(255,255,255,.85); line-height:1.7; margin-bottom:1.25rem; min-height:80px; }
        .testi-author { display:flex; align-items:center; gap:10px; }
        .testi-avatar { width:40px; height:40px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1rem; font-weight:800; color:white; flex-shrink:0; }
        .testi-name { font-size:.88rem; font-weight:700; color:white; }
        .testi-role { font-size:.72rem; color:rgba(255,255,255,.5); font-weight:500; }

        /* fade edge for tutor carousel (light bg) */
        .tutor-carousel-viewport::before { background:linear-gradient(to right, var(--off-white) 0%, transparent 100%) !important; }
        .tutor-carousel-viewport::after  { background:linear-gradient(to left,  var(--off-white) 0%, transparent 100%) !important; }

        /* ─── GALERI ──────────────────────────────────────────────── */
        .galeri-grid { display:grid; grid-template-columns:repeat(3,1fr); grid-template-rows:auto auto; gap:1rem; margin-top:2.5rem; }
        .galeri-item { border-radius:20px; overflow:hidden; position:relative; cursor:pointer; }
        .galeri-item img { width:100%; height:100%; object-fit:cover; display:block; transition:transform .5s var(--ease-out); }
        .galeri-item:hover img { transform:scale(1.06); }
        .galeri-item.large { grid-row:span 2; }
        .galeri-item { height:220px; }
        .galeri-item.large { height:auto; min-height:455px; }
        .galeri-overlay { position:absolute; inset:0; background:linear-gradient(to top,rgba(38,6,50,.75) 0%,transparent 50%); opacity:0; transition:opacity .3s; display:flex; align-items:flex-end; padding:1.25rem; }
        .galeri-item:hover .galeri-overlay { opacity:1; }
        .galeri-overlay span { color:white; font-size:.82rem; font-weight:600; font-family:var(--font-display); }

        /* ─── TUTOR INFINITE CAROUSEL ────────────────────────────── */
        .tutor-bg { background:var(--off-white); }
        .tutor-carousel-track {
            display:flex; gap:1.25rem;
            width:max-content;
            animation: marquee-scroll 32s linear infinite;
        }
        .tutor-carousel-track:hover { animation-play-state: paused; }
        .tutor-card {
            background:white; border-radius:22px; overflow:hidden;
            border:1px solid rgba(200,77,223,.1);
            box-shadow:0 4px 18px rgba(38,6,50,.06);
            transition:transform .35s var(--ease-out), box-shadow .35s;
            text-align:center; width:240px; flex-shrink:0;
        }
        .tutor-card:hover { transform:translateY(-6px); box-shadow:0 18px 50px rgba(38,6,50,.14); }
        .tutor-avatar-wrap { height:200px; position:relative; overflow:hidden; }
        .tutor-avatar-wrap img { width:100%; height:100%; object-fit:cover; object-position:top center; }
        .tutor-avatar-fallback { width:100%; height:100%; display:flex; align-items:center; justify-content:center; font-size:3.5rem; font-weight:900; color:white; font-family:var(--font-display); }
        .tutor-badge-subject { position:absolute; bottom:.75rem; left:50%; transform:translateX(-50%); background:rgba(38,6,50,.85); backdrop-filter:blur(8px); color:white; font-size:.68rem; font-weight:700; padding:3px 12px; border-radius:50px; white-space:nowrap; letter-spacing:.04em; }
        .tutor-info { padding:1rem 1rem 1.25rem; }
        .tutor-name { font-size:.9rem; font-weight:800; color:var(--deep); font-family:var(--font-display); margin-bottom:.2rem; line-height:1.3; }
        .tutor-meta { font-size:.73rem; color:var(--text-muted); margin-bottom:.6rem; }
        .tutor-stars { display:flex; gap:2px; justify-content:center; color:var(--gold); font-size:.72rem; }

        /* ─── CABANG ──────────────────────────────────────────────── */
        .cabang-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:1.25rem; margin-top:3rem; }
        .cabang-card { background:white; border-radius:22px; padding:1.75rem; border:1px solid rgba(200,77,223,.1); box-shadow:0 4px 18px rgba(38,6,50,.05); transition:transform .3s var(--ease-out), box-shadow .3s; }
        .cabang-card:hover { transform:translateY(-5px); box-shadow:0 16px 45px rgba(38,6,50,.13); }
        .cabang-icon { width:46px; height:46px; border-radius:14px; background:linear-gradient(135deg,var(--deep),var(--primary)); display:flex; align-items:center; justify-content:center; color:white; font-size:1.1rem; margin-bottom:1rem; box-shadow:0 6px 18px rgba(104,17,126,.3); }
        .cabang-name { font-size:1rem; font-weight:800; color:var(--deep); font-family:var(--font-display); margin-bottom:.3rem; }
        .cabang-address { font-size:.82rem; color:var(--text-muted); line-height:1.5; }
        .cabang-tag { display:inline-flex; align-items:center; gap:4px; margin-top:.75rem; font-size:.7rem; font-weight:700; color:var(--success); background:rgba(16,185,129,.08); padding:3px 10px; border-radius:50px; }

        /* ─── CTA ─────────────────────────────────────────────────── */
        .cta-section { padding:6rem 0; }
        .cta-box { background:linear-gradient(160deg,var(--deep) 0%,var(--mid) 60%,#8b1fa8 100%); border-radius:32px; padding:4.5rem 3rem; text-align:center; position:relative; overflow:hidden; }
        .cta-box::before { content:''; position:absolute; width:400px; height:400px; background:radial-gradient(circle,rgba(200,77,223,.25),transparent 70%); top:-100px; right:-100px; pointer-events:none; }
        .cta-box::after { content:''; position:absolute; width:300px; height:300px; background:radial-gradient(circle,rgba(246,175,35,.15),transparent 70%); bottom:-80px; left:-80px; pointer-events:none; }
        .cta-content { position:relative; z-index:1; }
        .cta-eyebrow { display:inline-flex; align-items:center; gap:6px; background:rgba(255,255,255,.12); border:1px solid rgba(255,255,255,.15); border-radius:50px; padding:5px 16px; font-size:.75rem; font-weight:700; color:rgba(255,255,255,.9); text-transform:uppercase; letter-spacing:.06em; margin-bottom:1.25rem; }
        .cta-title { font-size:clamp(1.8rem,3vw,2.8rem); font-weight:900; color:white; line-height:1.2; margin-bottom:1rem; }
        .cta-desc { font-size:1rem; color:rgba(255,255,255,.7); margin-bottom:2.5rem; max-width:520px; margin-left:auto; margin-right:auto; }
        .cta-btns { display:flex; gap:1rem; justify-content:center; flex-wrap:wrap; }
        .btn-cta-primary { display:inline-flex; align-items:center; gap:8px; padding:1rem 2.25rem; border-radius:14px; font-size:1rem; font-weight:700; color:var(--deep); background:white; text-decoration:none; transition:transform .25s, box-shadow .25s; box-shadow:0 8px 24px rgba(0,0,0,.2); }
        .btn-cta-primary:hover { transform:translateY(-3px); box-shadow:0 14px 40px rgba(0,0,0,.3); color:var(--primary-dark); }
        .btn-cta-secondary { display:inline-flex; align-items:center; gap:8px; padding:1rem 2rem; border-radius:14px; font-size:1rem; font-weight:600; color:white; border:1.5px solid rgba(255,255,255,.3); background:rgba(255,255,255,.08); text-decoration:none; transition:.25s; }
        .btn-cta-secondary:hover { background:rgba(255,255,255,.15); border-color:rgba(255,255,255,.5); transform:translateY(-2px); color:white; }

        /* ─── FOOTER ─────────────────────────────────────────────── */
        .footer { background:var(--deep); color:rgba(255,255,255,.65); padding:4rem 0 2rem; }
        .footer-grid { display:grid; grid-template-columns:2fr 1fr 1fr 1fr; gap:3rem; padding-bottom:3rem; border-bottom:1px solid rgba(255,255,255,.07); }
        .footer-brand-desc { font-size:.85rem; line-height:1.7; color:rgba(255,255,255,.5); margin-top:1rem; max-width:280px; }
        .footer-social { display:flex; gap:8px; margin-top:1.25rem; }
        .footer-social a { width:36px; height:36px; border-radius:10px; background:rgba(255,255,255,.07); border:1px solid rgba(255,255,255,.08); display:flex; align-items:center; justify-content:center; color:rgba(255,255,255,.6); font-size:.95rem; text-decoration:none; transition:.2s; }
        .footer-social a:hover { background:var(--primary); border-color:var(--primary); color:white; }
        .footer-col-title { font-family:var(--font-display); font-size:.85rem; font-weight:700; color:white; margin-bottom:1rem; letter-spacing:-.01em; }
        .footer-links { list-style:none; display:flex; flex-direction:column; gap:8px; }
        .footer-links a { font-size:.82rem; color:rgba(255,255,255,.5); text-decoration:none; transition:color .2s; }
        .footer-links a:hover { color:var(--primary); }
        .footer-bottom { padding-top:2rem; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem; }
        .footer-bottom p { font-size:.78rem; }
        .footer-bottom-links { display:flex; gap:1.5rem; }
        .footer-bottom-links a { font-size:.78rem; color:rgba(255,255,255,.4); text-decoration:none; transition:color .2s; }
        .footer-bottom-links a:hover { color:var(--primary); }

        /* ─── CABANG CARD EXTRAS ──────────────────────────────────── */
        .cabang-phone { font-size:.8rem; color:var(--text-muted); margin-top:.4rem; display:flex; align-items:center; gap:5px; }
        .btn-cabang-wa { display:inline-flex; align-items:center; gap:6px; margin-top:1rem; padding:.45rem 1rem; border-radius:10px; font-size:.78rem; font-weight:700; color:#128C7E; background:rgba(37,211,102,.1); border:1px solid rgba(37,211,102,.2); text-decoration:none; transition:background .2s, color .2s, transform .2s; }
        .btn-cabang-wa:hover { background:rgba(37,211,102,.18); color:#128C7E; transform:translateY(-1px); }

        /* ─── FLOATING WA BUTTON ──────────────────────────────────── */
        .wa-float {
            position:fixed; bottom:1.75rem; right:1.75rem; z-index:9000;
            width:58px; height:58px; border-radius:50%;
            background:linear-gradient(135deg,#25D366,#128C7E);
            display:flex; align-items:center; justify-content:center;
            font-size:1.6rem; color:white; text-decoration:none;
            box-shadow:0 8px 28px rgba(37,211,102,.5), 0 0 0 0 rgba(37,211,102,.3);
            animation:wa-pulse 2.5s ease-in-out infinite;
            transition:transform .25s var(--ease-out), box-shadow .25s;
        }
        .wa-float:hover { transform:scale(1.12) translateY(-2px); box-shadow:0 16px 44px rgba(37,211,102,.6), 0 0 0 8px rgba(37,211,102,.1); color:white; }
        .wa-float-label {
            position:absolute; right:68px; top:50%; transform:translateY(-50%);
            background:rgba(38,6,50,.88); backdrop-filter:blur(10px);
            color:white; font-size:.78rem; font-weight:700; white-space:nowrap;
            padding:.38rem .85rem; border-radius:50px;
            opacity:0; pointer-events:none;
            transition:opacity .25s, transform .25s;
            transform:translateY(-50%) translateX(6px);
        }
        .wa-float:hover .wa-float-label { opacity:1; transform:translateY(-50%) translateX(0); }
        @keyframes wa-pulse {
            0%,100% { box-shadow:0 8px 28px rgba(37,211,102,.5), 0 0 0 0 rgba(37,211,102,.25); }
            50%      { box-shadow:0 8px 28px rgba(37,211,102,.5), 0 0 0 14px rgba(37,211,102,0); }
        }

        /* ─── SCROLL-TO-TOP ───────────────────────────────────────── */
        .scroll-top {
            position:fixed; bottom:1.75rem; left:1.75rem; z-index:9000;
            width:46px; height:46px; border-radius:14px;
            background:rgba(38,6,50,.82); backdrop-filter:blur(12px); -webkit-backdrop-filter:blur(12px);
            border:1px solid rgba(200,77,223,.22);
            display:flex; align-items:center; justify-content:center;
            color:white; font-size:1rem; cursor:pointer;
            opacity:0; visibility:hidden;
            transition:opacity .3s, visibility .3s, transform .3s var(--ease-out), background .2s;
            transform:translateY(10px);
        }
        .scroll-top.visible { opacity:1; visibility:visible; transform:translateY(0); }
        .scroll-top:hover { background:var(--primary); transform:translateY(-2px); }

        /* ─── SCROLL-REVEAL ──────────────────────────────────────── */
        .reveal { opacity:0; transform:translateY(32px); transition:opacity .7s var(--ease-out), transform .7s var(--ease-out); }
        .reveal.visible { opacity:1; transform:none; }
        .reveal-delay-1 { transition-delay:.1s; }
        .reveal-delay-2 { transition-delay:.2s; }
        .reveal-delay-3 { transition-delay:.3s; }
        .reveal-delay-4 { transition-delay:.4s; }
        .reveal-delay-5 { transition-delay:.5s; }
        /* Fallback: show content if JS is disabled */
        @media (scripting: none) { .reveal { opacity:1; transform:none; } }

        /* ─── RESPONSIVE ─────────────────────────────────────────── */
        @media (max-width:1200px) {
            .why-grid { grid-template-columns:repeat(3,1fr); }
        }
        @media (max-width:900px) {
            .section-pad { padding:4.5rem 0; }
            .jenjang-grid { grid-template-columns:1fr 1fr; }
            .program-grid { grid-template-columns:1fr 1fr; }
            .why-grid { grid-template-columns:1fr 1fr; }
            .cabang-grid { grid-template-columns:1fr 1fr; }
            .how-inner { grid-template-columns:1fr; }
            .how-visual { order:-1; max-width:480px; margin:0 auto; }
            .how-visual-badge { bottom:.75rem; left:.75rem; }
            .footer-grid { grid-template-columns:1fr 1fr; gap:2rem; }
            /* hamburger — hide links, show toggle */
            .nav-links, .nav-cta { display:none !important; }
            .nav-toggle { display:flex !important; }
            .hero-notify { top:5rem; left:.75rem; max-width:210px; }
            .hero-arrow { width:34px; height:34px; font-size:.85rem; }
            .hero-arrow-left { left:.75rem; }
            .hero-arrow-right { right:.75rem; }
        }
        @media (max-width:768px) {
            .lp-nav.scrolled { padding:.6rem 0; }
            .section-pad { padding:3.75rem 0; }
            .stats-strip-inner { grid-template-columns:1fr 1fr; }
            .stat-item { border-right:none; border-bottom:1px solid rgba(200,77,223,.1); }
            .stat-item:nth-child(odd) { border-right:1px solid rgba(200,77,223,.1); }
            .stat-item:nth-child(3), .stat-item:nth-child(4) { border-bottom:none; }
            .jenjang-grid { grid-template-columns:1fr 1fr; }
            .program-grid { grid-template-columns:1fr; }
            .why-grid { grid-template-columns:1fr 1fr; }
            .galeri-grid { grid-template-columns:1fr 1fr; }
            .galeri-item.large { grid-row:auto; min-height:220px; }
            .cabang-grid { grid-template-columns:1fr; }
            .footer-grid { grid-template-columns:1fr; gap:2rem; }
            .footer-bottom { flex-direction:column; text-align:center; }
            .cta-box { padding:3rem 1.5rem; border-radius:24px; }
            .float-card { display:none; }
            .scroll-indicator { display:none; }
            .wa-float { width:52px; height:52px; font-size:1.4rem; bottom:1.25rem; right:1.25rem; }
            .scroll-top { bottom:1.25rem; left:1.25rem; }
            .how-visual-badge { bottom:.5rem; left:.5rem; min-width:160px; padding:.75rem 1rem; }
            .hvb-text .hvb-val { font-size:1rem; }
        }
        /* ── Mobile carousels (≤640px) ── */
        @media (max-width:640px) {
            .jenjang-grid,
            .program-grid,
            .why-grid {
                display: flex !important;
                overflow-x: auto;
                scroll-snap-type: x mandatory;
                -webkit-overflow-scrolling: touch;
                scrollbar-width: none;
                gap: 1rem;
                padding: 1.5rem 1.25rem 0.75rem;
                margin: 0 -1.25rem;
            }
            .jenjang-grid::-webkit-scrollbar,
            .program-grid::-webkit-scrollbar,
            .why-grid::-webkit-scrollbar { display: none; }
            .jenjang-card { flex: 0 0 72vw; scroll-snap-align: start; }
            .program-card { flex: 0 0 78vw; scroll-snap-align: start; height: auto; }
            .why-card     { flex: 0 0 72vw; scroll-snap-align: start; }
            /* Dot indicators */
            .mobile-carousel-dots {
                display: flex;
                justify-content: center;
                align-items: center;
                gap: 7px;
                margin-top: 1.25rem;
            }
            .mobile-carousel-dots .mcd {
                width: 8px; height: 8px;
                border-radius: 50%;
                background: rgba(200,77,223,.2);
                border: none; cursor: pointer; padding: 0;
                transition: background .25s, width .25s, border-radius .25s;
            }
            .mobile-carousel-dots .mcd.active {
                background: var(--primary);
                width: 22px;
                border-radius: 4px;
            }
        }
        @media (max-width:480px) {
            .section-pad { padding:3rem 0; }
            .hero-inner { padding:6.5rem 1.25rem 5.5rem; }
            .hero-title { font-size:clamp(1.9rem,8vw,2.4rem); }
            .btn-hero-primary, .btn-hero-secondary { width:100%; justify-content:center; }
            .cta-btns > * { width:100%; justify-content:center; }
            .hero-dots { bottom:4.5rem; }
            .galeri-grid { grid-template-columns:1fr; }
            .galeri-item.large { min-height:240px; }
            .testi-card { width:300px; }
            .tutor-card { width:200px; }
            .tutor-avatar-wrap { height:170px; }
            .section-title { font-size:clamp(1.55rem,6vw,2rem); }
            .how-inner { gap:2.5rem; }
            .footer-grid { gap:1.5rem; }
            .cta-box { padding:2.5rem 1.25rem; }
        }
        @media (max-width:360px) {
            .testi-card { width:260px; }
            .tutor-card { width:175px; }
        }

        /* ─── KEUNGGULAN DARK PHOTO CARDS ────────────────────────── */
        .keunggulan-dark-section {
            background:linear-gradient(135deg,var(--deep) 0%,var(--mid) 55%,#8b1fa8 100%);
            padding:6rem 0; position:relative; overflow:hidden;
        }
        .keunggulan-dark-section::before {
            content:''; position:absolute; inset:0;
            background:radial-gradient(circle at 15% 85%,rgba(200,77,223,.18) 0%,transparent 50%),
                        radial-gradient(circle at 85% 15%,rgba(246,175,35,.08) 0%,transparent 50%);
            pointer-events:none;
        }
        .keunggulan-dark-grid { display:grid; grid-template-columns:repeat(5,1fr); gap:1.25rem; margin-top:3rem; }
        .kd-card {
            background:rgba(255,255,255,.07); backdrop-filter:blur(16px);
            border:1px solid rgba(255,255,255,.1); border-radius:22px;
            padding:2rem 1.5rem; text-align:center;
            transition:transform .35s var(--ease-out), background .3s;
        }
        .kd-card:hover { transform:translateY(-8px); background:rgba(255,255,255,.12); }
        .kd-photo { width:90px; height:90px; border-radius:50%; overflow:hidden; margin:0 auto 1.25rem;
            border:3px solid rgba(255,255,255,.2); box-shadow:0 8px 28px rgba(0,0,0,.35); }
        .kd-photo img { width:100%; height:100%; object-fit:cover; display:block; }
        .kd-photo-fallback { width:90px; height:90px; border-radius:50%; display:flex; align-items:center; justify-content:center;
            font-size:2rem; margin:0 auto 1.25rem; border:3px solid rgba(255,255,255,.2); }
        .kd-title { font-size:1rem; font-weight:800; color:white; font-family:var(--font-display); margin-bottom:.65rem; }
        .kd-desc { font-size:.82rem; color:rgba(255,255,255,.7); line-height:1.65; }

        /* ─── TESTIMONI LIGHT CARDS ─────────────────────────────── */
        .testi-light-section { background:#f5eeff; padding:6rem 0; }
        .testi-light-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:1.25rem; margin-top:3rem; }
        .tlc {
            background:white; border-radius:20px; padding:1.75rem 1.5rem;
            border:1px solid rgba(200,77,223,.1); box-shadow:0 4px 18px rgba(38,6,50,.05);
            display:flex; flex-direction:column;
        }
        .tlc-stars { display:flex; gap:3px; margin-bottom:.75rem; color:var(--gold); font-size:.85rem; }
        .tlc-quote { font-size:2.8rem; line-height:.9; color:rgba(200,77,223,.15); font-family:Georgia,serif; margin-bottom:.35rem; }
        .tlc-text { font-size:.875rem; color:#444; line-height:1.7; flex:1; margin-bottom:1.25rem; }
        .tlc-author { display:flex; align-items:center; gap:10px; }
        .tlc-avatar { width:40px; height:40px; border-radius:50%; overflow:hidden; flex-shrink:0; }
        .tlc-avatar img { width:100%; height:100%; object-fit:cover; }
        .tlc-avatar-fb { width:40px; height:40px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:1rem; font-weight:800; color:white; flex-shrink:0; }
        .tlc-name { font-size:.88rem; font-weight:700; color:var(--deep); }
        .tlc-role { font-size:.72rem; color:var(--text-muted); }
        .tlc-badge { display:inline-flex; align-items:center; gap:4px; margin-top:.4rem; font-size:.68rem; font-weight:700; color:#7e22ce; background:rgba(124,58,237,.1); padding:2px 8px; border-radius:50px; }

        /* ─── GALERI ROW + NAV ───────────────────────────────────── */
        .galeri-row-wrap { overflow:hidden; margin-top:3rem; }
        .galeri-row { display:grid; grid-template-columns:repeat(4,1fr); gap:1rem; transition:transform .4s var(--ease-out); }
        .galeri-row-item { height:220px; border-radius:18px; overflow:hidden; position:relative; }
        .galeri-row-item img { width:100%; height:100%; object-fit:cover; display:block; transition:transform .5s var(--ease-out); }
        .galeri-row-item:hover img { transform:scale(1.06); }
        .galeri-nav { display:flex; gap:.75rem; justify-content:center; margin-top:1.75rem; }
        .galeri-nav-btn { width:42px; height:42px; border-radius:50%; border:1.5px solid rgba(200,77,223,.3); background:white; color:var(--deep); display:flex; align-items:center; justify-content:center; font-size:.95rem; cursor:pointer; transition:background .2s, border-color .2s, color .2s, transform .2s; }
        .galeri-nav-btn:hover { background:var(--primary); border-color:var(--primary); color:white; transform:scale(1.08); }

        /* ─── TUTOR GRID CARDS ───────────────────────────────────── */
        .tutor-grid-section { background:#f9f4ff; padding:6rem 0; }
        .tutor-grid-cards { display:grid; grid-template-columns:repeat(6,1fr); gap:1.25rem; margin-top:3rem; }
        .tgc {
            background:white; border-radius:20px; padding:1.75rem 1rem;
            text-align:center; border:1px solid rgba(200,77,223,.1);
            box-shadow:0 4px 18px rgba(38,6,50,.05);
            transition:transform .3s var(--ease-out), box-shadow .3s;
        }
        .tgc:hover { transform:translateY(-6px); box-shadow:0 18px 50px rgba(38,6,50,.12); }
        .tgc-photo-wrap { position:relative; width:80px; height:80px; margin:0 auto 1rem; }
        .tgc-photo { width:80px; height:80px; border-radius:50%; overflow:hidden; border:3px solid rgba(200,77,223,.2); }
        .tgc-photo img { width:100%; height:100%; object-fit:cover; object-position:top center; }
        .tgc-photo-fb { width:80px; height:80px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:2rem; font-weight:900; color:white; }
        .tgc-star-badge { position:absolute; bottom:-4px; right:-4px; width:22px; height:22px; border-radius:50%; background:var(--gold); display:flex; align-items:center; justify-content:center; font-size:.55rem; border:2px solid white; color:white; }
        .tgc-name { font-size:.87rem; font-weight:800; color:var(--deep); font-family:var(--font-display); margin-bottom:.5rem; line-height:1.3; }
        .tgc-subject { display:inline-block; font-size:.68rem; font-weight:700; padding:3px 12px; border-radius:50px; background:rgba(200,77,223,.1); color:var(--primary-dark); margin-bottom:.75rem; }
        .tgc-rating { font-size:.8rem; color:var(--gold); margin-bottom:.3rem; }
        .tgc-rating b { color:var(--deep); }
        .tgc-exp { font-size:.72rem; color:var(--text-muted); }

        /* ─── BANTUAN & KONTAK ────────────────────────────────────── */
        .bantuan-section { background:white; padding:6rem 0; }
        .bantuan-inner { display:grid; grid-template-columns:1fr 1fr; gap:4rem; margin-top:3.5rem; align-items:start; }
        .faq-list { display:flex; flex-direction:column; gap:.75rem; }
        .faq-item { border:1.5px solid rgba(200,77,223,.12); border-radius:14px; overflow:hidden; background:white; }
        .faq-trigger { display:flex; align-items:center; justify-content:space-between; padding:1.1rem 1.4rem; cursor:pointer; font-size:.92rem; font-weight:600; color:var(--deep); user-select:none; transition:background .2s; }
        .faq-trigger:hover { background:rgba(200,77,223,.04); }
        .faq-icon { width:28px; height:28px; border-radius:50%; border:1.5px solid rgba(200,77,223,.3); display:flex; align-items:center; justify-content:center; font-size:.85rem; color:var(--primary); flex-shrink:0; transition:transform .3s, background .3s, border-color .3s; }
        .faq-item.open .faq-icon { background:var(--primary); color:white; border-color:var(--primary); transform:rotate(45deg); }
        .faq-body { max-height:0; overflow:hidden; transition:max-height .35s ease; }
        .faq-body-inner { padding:0 1.4rem 1.1rem; font-size:.875rem; color:var(--text-muted); line-height:1.7; }
        .faq-item.open .faq-body { max-height:200px; }
        .contact-dark-card { background:linear-gradient(160deg,#260632 0%,#461256 55%,#6d1a7e 100%); border-radius:24px; padding:2.25rem 2rem; }
        .contact-dark-title { font-size:1.4rem; font-weight:900; color:white; font-family:var(--font-display); margin-bottom:.4rem; }
        .contact-dark-sub { font-size:.85rem; color:rgba(255,255,255,.65); margin-bottom:1.75rem; }
        .contact-form-row { display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem; }
        .contact-field { display:flex; flex-direction:column; gap:.45rem; }
        .contact-field label { font-size:.68rem; font-weight:700; color:rgba(255,255,255,.7); letter-spacing:.06em; text-transform:uppercase; }
        .contact-field input,.contact-field select,.contact-field textarea { background:rgba(255,255,255,.1); border:1px solid rgba(255,255,255,.15); border-radius:10px; padding:.7rem 1rem; color:white; font-size:.88rem; outline:none; transition:border-color .2s,background .2s; width:100%; }
        .contact-field input::placeholder,.contact-field textarea::placeholder { color:rgba(255,255,255,.4); }
        .contact-field input:focus,.contact-field select:focus,.contact-field textarea:focus { border-color:rgba(200,77,223,.6); background:rgba(255,255,255,.14); }
        .contact-field select option { background:#260632; color:white; }
        .contact-field textarea { resize:none; height:110px; }
        .btn-send { display:flex; align-items:center; justify-content:center; gap:8px; width:100%; padding:1rem; border-radius:12px; font-size:.95rem; font-weight:800; color:var(--deep); background:var(--gold); border:none; cursor:pointer; transition:transform .25s,box-shadow .25s; margin-top:1rem; }
        .btn-send:hover { transform:translateY(-2px); box-shadow:0 10px 30px rgba(246,175,35,.4); }

        /* ─── CABANG PHOTO CARDS ─────────────────────────────────── */
        .cabang-photo-section { background:#f5eeff; padding:6rem 0; }
        .cabang-photo-grid { display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-top:3rem; }
        .cabang-photo-left { display:grid; grid-template-rows:auto; gap:1rem; }
        .cpc { border-radius:20px; overflow:hidden; position:relative; cursor:pointer; height:195px; }
        .cpc.cpc-tall { height:auto; }
        .cpc img { width:100%; height:100%; object-fit:cover; display:block; transition:transform .5s var(--ease-out); }
        .cpc:hover img { transform:scale(1.06); }
        .cpc-overlay { position:absolute; inset:0; background:linear-gradient(to top,rgba(20,4,35,.85) 0%,rgba(20,4,35,.2) 55%,transparent 100%); display:flex; flex-direction:column; justify-content:flex-end; padding:1.5rem; }
        .cpc-name { font-size:1.2rem; font-weight:900; color:white; font-family:var(--font-display); margin-bottom:.2rem; }
        .cpc-sub { font-size:.78rem; color:rgba(255,255,255,.75); margin-bottom:.875rem; }
        .btn-cpc { display:inline-flex; align-items:center; gap:6px; padding:.45rem 1.1rem; border-radius:8px; font-size:.78rem; font-weight:700; color:var(--deep); background:white; text-decoration:none; transition:.2s; width:fit-content; }
        .btn-cpc:hover { background:var(--gold); }

        /* ─── RESPONSIVE OVERRIDES (new sections) ────────────────── */
        @media (max-width:1100px) {
            .keunggulan-dark-grid { grid-template-columns:repeat(3,1fr); }
            .tutor-grid-cards { grid-template-columns:repeat(4,1fr); }
            .testi-light-grid { grid-template-columns:repeat(2,1fr); }
        }
        @media (max-width:768px) {
            .keunggulan-dark-grid { grid-template-columns:repeat(2,1fr); }
            .tutor-grid-cards { grid-template-columns:repeat(3,1fr); }
            .bantuan-inner { grid-template-columns:1fr; gap:2.5rem; }
            .cabang-photo-grid { grid-template-columns:1fr; }
            .galeri-row { grid-template-columns:repeat(2,1fr); }
            .galeri-row-item { height:180px; }
            .testi-light-section,.tutor-grid-section,.bantuan-section,.cabang-photo-section,.keunggulan-dark-section { padding:4.5rem 0; }
        }
        @media (max-width:560px) {
            .keunggulan-dark-grid { grid-template-columns:1fr 1fr; }
            .tutor-grid-cards { grid-template-columns:repeat(2,1fr); }
            .testi-light-grid { grid-template-columns:1fr; }
            .contact-form-row { grid-template-columns:1fr; }
            .galeri-row { grid-template-columns:1fr 1fr; }
        }
    </style>
</head>
<body>
<div id="scroll-progress" role="progressbar" aria-hidden="true"></div>


<nav class="lp-nav" id="navbar">
    <div class="nav-inner">
        <a href="<?php echo e(url('/')); ?>" class="nav-brand">
            <div class="nav-brand-icon"><i class="bi bi-mortarboard-fill"></i></div>
            <div class="nav-brand-text">
                Smart Center
                <small>Indonesia</small>
            </div>
        </a>

        <ul class="nav-links">
            <li><a href="#tentang"      class="nav-link-item">Tentang</a></li>
            <li><a href="#program"      class="nav-link-item">Program</a></li>
            <li><a href="#mengapa-sci"  class="nav-link-item">Keunggulan</a></li>
            <li><a href="#testimonials" class="nav-link-item">Testimoni</a></li>
            <li><a href="#tutor"        class="nav-link-item">Tutor</a></li>
            <li><a href="#cabang"       class="nav-link-item">Cabang</a></li>
        </ul>

        <div class="nav-cta">
            <a href="<?php echo e(route('login')); ?>"    class="btn-nav-login">Masuk</a>
            <a href="<?php echo e(route('register')); ?>" class="btn-nav-register">Daftar Sekarang</a>
        </div>

        <button class="nav-toggle" id="navToggle" aria-label="Menu">
            <span></span><span></span><span></span>
        </button>
    </div>
</nav>


<div class="mobile-menu" id="mobileMenu" aria-hidden="true">
    <button class="mobile-close" onclick="closeMobile()" aria-label="Tutup menu">
        <i class="bi bi-x-lg"></i>
    </button>
    <a href="#tentang"      onclick="closeMobile()">Tentang</a>
    <a href="#program"      onclick="closeMobile()">Program</a>
    <a href="#mengapa-sci"  onclick="closeMobile()">Keunggulan</a>
    <a href="#testimonials" onclick="closeMobile()">Testimoni</a>
    <a href="#tutor"        onclick="closeMobile()">Tutor</a>
    <a href="#cabang"       onclick="closeMobile()">Cabang</a>
    <div class="mobile-divider"></div>
    <a href="<?php echo e(route('login')); ?>"    onclick="closeMobile()" style="color:rgba(255,255,255,.65);font-size:1.05rem;font-weight:600"><i class="bi bi-box-arrow-in-right" style="font-size:.9rem"></i> Masuk</a>
    <a href="<?php echo e(route('register')); ?>" onclick="closeMobile()" style="background:linear-gradient(135deg,var(--primary-dark),var(--primary));padding:.8rem 2.5rem;border-radius:14px;font-size:1rem;color:white">Daftar Sekarang</a>
</div>


<section class="hero" id="home">
    <div class="hero-slides" id="heroSlides">
        <?php
            $heroSlides = array_values(array_filter([
                $ls('hero.slide_1_url','https://images.unsplash.com/photo-1580582932707-520aed937b7b?auto=format&fit=crop&w=1920&q=80'),
                $ls('hero.slide_2_url','https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&fit=crop&w=1920&q=80'),
                $ls('hero.slide_3_url','https://images.unsplash.com/photo-1571260899304-425eee4c7efc?auto=format&fit=crop&w=1920&q=80'),
            ]));
        ?>
        <?php $__currentLoopData = $heroSlides; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $slideUrl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="hero-slide <?php echo e($i === 0 ? 'active' : ''); ?>" style="background-image:url('<?php echo e($slideUrl); ?>')">
            <div class="hero-slide-overlay"></div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <button class="hero-arrow hero-arrow-left" id="heroArrowLeft" aria-label="Slide sebelumnya">
        <i class="bi bi-chevron-left"></i>
    </button>

    
    <button class="hero-arrow hero-arrow-right" id="heroArrowRight" aria-label="Slide berikutnya">
        <i class="bi bi-chevron-right"></i>
    </button>

    
    <div class="hero-notify" id="heroNotify">
        <div class="notify-avatar">
            W
            <span class="notify-dot"></span>
        </div>
        <div class="notify-body">
            <div class="notify-title">Wahyu baru saja mendaftar! 🎉</div>
            <div class="notify-sub">Kursus Akuntansi &middot; Medan</div>
            <div class="notify-time"><i class="bi bi-clock"></i> 5 menit lalu</div>
        </div>
        <button class="notify-close" onclick="document.getElementById('heroNotify').style.display='none'" aria-label="Tutup">
            <i class="bi bi-x"></i>
        </button>
    </div>

    
    <div class="hero-scroll-hint">
        <div class="hero-scroll-text">Scroll</div>
        <div class="hero-scroll-chevron"><i class="bi bi-chevron-down"></i></div>
        <div class="hero-dots" id="heroDots">
            <?php $__currentLoopData = $heroSlides; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $slideUrl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <button class="hero-dot <?php echo e($i === 0 ? 'active' : ''); ?>" data-slide="<?php echo e($i); ?>" aria-label="Slide <?php echo e($i + 1); ?>"></button>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>


<div class="hero-ticker" aria-hidden="true">
    <div class="ticker-track">
        <?php
            $tickerItems = [
                ['🎉', 'Diskon Spesial! Gratis biaya pendaftaran bulan ini'],
                ['📚', 'Daftar sekarang &amp; dapatkan sesi konsultasi GRATIS!'],
                ['🎁', 'Promo Paket Hemat: Beli 10 sesi gratis 2 sesi ekstra'],
                ['⭐', 'Lebih dari 1.000+ siswa sudah bergabung bersama kami'],
                ['🏆', 'Tutor berpengalaman &amp; bersertifikat nasional'],
                ['📞', 'Hubungi kami sekarang &mdash; konsultasi gratis!'],
            ];
        ?>
        
        <?php $__currentLoopData = $tickerItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ti): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <span class="ticker-item"><?php echo e($ti[0]); ?> <?php echo $ti[1]; ?> <span class="ticker-sep">|</span></span>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        
        <?php $__currentLoopData = $tickerItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ti): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <span class="ticker-item"><?php echo e($ti[0]); ?> <?php echo $ti[1]; ?> <span class="ticker-sep">|</span></span>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>


<section class="tentang-section" id="tentang">
    <div class="tentang-inner">
        
        <div class="reveal">
            <div class="tentang-pills">
                <span class="tentang-pill"><i class="bi bi-building"></i> Tentang Kami</span>
                <span class="tentang-pill neutral">Sejak 2010</span>
                <span class="tentang-pill neutral">ISO Certified</span>
            </div>
            <h2 class="tentang-title">
                Tentang <span class="tentang-title-accent">Smart Center Indonesia</span>
            </h2>
            <p class="tentang-desc">
                Smart Center Indonesia (SCI) adalah lembaga pendidikan yang bergerak di bidang bimbingan belajar, kursus, dan les privat (1 guru 1 siswa) berbasis offline dan online yang berkomitmen menjadi lembaga terbaik nomor 1 di Indonesia.
            </p>
            <p class="tentang-desc">
                Dengan metode pembelajaran efektif, pengajar berpengalaman, serta pendekatan personal, SCI hadir sebagai solusi pendidikan terpercaya. <span class="tentang-desc-quote">"Wujudkan mimpi, raih prestasi!"</span>
            </p>
        </div>

        
        <div class="tentang-features">
            <div class="tentang-feat">
                <div class="tentang-feat-icon"><i class="bi bi-patch-check-fill"></i></div>
                <div class="tentang-feat-label">Tutor Bersertifikat</div>
            </div>
            <div class="tentang-feat">
                <div class="tentang-feat-icon"><i class="bi bi-house-heart-fill"></i></div>
                <div class="tentang-feat-label">Bisa Home Visit</div>
            </div>
            <div class="tentang-feat">
                <div class="tentang-feat-icon"><i class="bi bi-camera-video-fill"></i></div>
                <div class="tentang-feat-label">Kelas Online &amp; Offline</div>
            </div>
            <div class="tentang-feat">
                <div class="tentang-feat-icon"><i class="bi bi-bar-chart-fill"></i></div>
                <div class="tentang-feat-label">Evaluasi Rutin Bulanan</div>
            </div>
            <div class="tentang-feat">
                <div class="tentang-feat-icon"><i class="bi bi-headset"></i></div>
                <div class="tentang-feat-label">Konsultasi 24/7</div>
            </div>
            <div class="tentang-feat">
                <div class="tentang-feat-icon"><i class="bi bi-bullseye"></i></div>
                <div class="tentang-feat-label">Target &amp; Hasil Terukur</div>
            </div>
        </div>
    </div>
</section>


<section class="program-photo-section" id="program-unggulan">
    <div class="container-lp">
        <div class="text-center reveal">
            <div class="program-photo-eyebrow">Program SCI</div>
            <h2 class="section-title">Program <em class="section-title-accent">Unggulan</em></h2>
            <p class="section-subtitle mx-auto">Pilih program yang sesuai kebutuhan Anda bersama para tutor terbaik kami —<br>klik kartu untuk melihat detail lengkap.</p>
        </div>

        <div class="program-photo-grid">
            
            <a href="<?php echo e(route('register')); ?>" class="ppc reveal reveal-delay-1">
                <div class="ppc-img-wrap">
                    <img src="https://images.unsplash.com/photo-1509228468518-180dd4864904?w=600&q=80" alt="Bimbel Mata Pelajaran" loading="lazy">
                </div>
                <div class="ppc-body">
                    <span class="ppc-badge" style="background:#e8f5e9;color:#2e7d32">SEMUA JENJANG</span>
                    <div class="ppc-title">Bimbel Mata Pelajaran</div>
                    <div class="ppc-desc">Bimbingan semua mata pelajaran sekolah dengan metode efektif dan menyenangkan.</div>
                    <div class="ppc-link">Lihat Detail <i class="bi bi-arrow-down"></i></div>
                </div>
            </a>
            
            <a href="<?php echo e(route('register')); ?>" class="ppc reveal reveal-delay-2">
                <div class="ppc-img-wrap">
                    <img src="https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?w=600&q=80" alt="Persiapan Ujian" loading="lazy">
                </div>
                <div class="ppc-body">
                    <span class="ppc-badge" style="background:#f3e8ff;color:#7e22ce">SMP · SMA</span>
                    <div class="ppc-title">Persiapan Ujian</div>
                    <div class="ppc-desc">Persiapan UTS, UAS & Ujian Sekolah agar nilai meningkat pesat dan lulus terbaik.</div>
                    <div class="ppc-link">Lihat Detail <i class="bi bi-arrow-down"></i></div>
                </div>
            </a>
            
            <a href="<?php echo e(route('register')); ?>" class="ppc reveal reveal-delay-3">
                <div class="ppc-img-wrap">
                    <img src="https://images.unsplash.com/photo-1503676382389-4809596d5290?w=600&q=80" alt="Persiapan Tes & SBMPTN" loading="lazy">
                </div>
                <div class="ppc-body">
                    <span class="ppc-badge" style="background:#fff7ed;color:#c2410c">INTENSIF</span>
                    <div class="ppc-title">Persiapan Tes & SBMPTN</div>
                    <div class="ppc-desc">Persiapan masuk sekolah favorit, PTN, CPNS & tes lainnya secara intensif.</div>
                    <div class="ppc-link">Lihat Detail <i class="bi bi-arrow-down"></i></div>
                </div>
            </a>
            
            <a href="<?php echo e(route('register')); ?>" class="ppc reveal reveal-delay-1">
                <div class="ppc-img-wrap">
                    <img src="https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=600&q=80" alt="Kursus Bahasa" loading="lazy">
                </div>
                <div class="ppc-body">
                    <span class="ppc-badge" style="background:#e0f2fe;color:#0369a1">SEMUA LEVEL</span>
                    <div class="ppc-title">Kursus Bahasa</div>
                    <div class="ppc-desc">Inggris, Jepang, Mandarin, Arab — tingkatkan kemampuan bahasa Anda bersama kami.</div>
                    <div class="ppc-link">Lihat Detail <i class="bi bi-arrow-down"></i></div>
                </div>
            </a>
            
            <a href="<?php echo e(route('register')); ?>" class="ppc reveal reveal-delay-2">
                <div class="ppc-img-wrap">
                    <img src="https://images.unsplash.com/photo-1498050108023-c5249f4df085?w=600&q=80" alt="Kursus Komputer" loading="lazy">
                </div>
                <div class="ppc-body">
                    <span class="ppc-badge" style="background:#fef2f2;color:#b91c1c">POPULER 🔥</span>
                    <div class="ppc-title">Kursus Komputer</div>
                    <div class="ppc-desc">Microsoft Office, Desain Grafis, Programming — teknologi terkini untuk karir masa depan.</div>
                    <div class="ppc-link">Lihat Detail <i class="bi bi-arrow-down"></i></div>
                </div>
            </a>
            
            <a href="<?php echo e(route('register')); ?>" class="ppc reveal reveal-delay-3">
                <div class="ppc-img-wrap">
                    <img src="https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?w=600&q=80" alt="Kursus Akuntansi" loading="lazy">
                </div>
                <div class="ppc-body">
                    <span class="ppc-badge" style="background:#f5f3ff;color:#6d28d9">TERBARU ✨</span>
                    <div class="ppc-title">Kursus Akuntansi</div>
                    <div class="ppc-desc">Akuntansi dasar hingga profesional: perpajakan & keuangan untuk mahasiswa dan karyawan.</div>
                    <div class="ppc-link">Lihat Detail <i class="bi bi-arrow-down"></i></div>
                </div>
            </a>
        </div>
    </div>
</section>


<section class="stats-strip">
    <div class="stats-strip-inner">
        <div class="stat-item reveal">
            <div class="si-num count-up" data-target="<?php echo e(max($stats['students'], 500)); ?>">0</div>
            <div class="si-label">Siswa Aktif</div>
        </div>
        <div class="stat-item reveal reveal-delay-1">
            <div class="si-num count-up" data-target="<?php echo e(max($stats['teachers'], 50)); ?>">0</div>
            <div class="si-label">Tutor Profesional</div>
        </div>
        <div class="stat-item reveal reveal-delay-2">
            <div class="si-num"><?php echo e($ls('stats.years_exp','14+')); ?></div>
            <div class="si-label">Tahun Pengalaman</div>
        </div>
        <div class="stat-item reveal reveal-delay-3">
            <div class="si-num"><?php echo e($ls('stats.satisfaction','98%')); ?></div>
            <div class="si-label">Kepuasan Pelanggan</div>
        </div>
    </div>
</section>


<section class="section-pad jenjang-bg" id="jenjang">
    <div class="container-lp">
        <div class="text-center reveal">
            <div class="jenjang-eyebrow">LAYANAN KAMI</div>
            <h2 class="jenjang-title-wrap">Jenjang <span class="jenjang-title-accent">Pendidikan</span></h2>
            <p class="section-subtitle mx-auto" style="margin-top:.75rem">Kami melayani semua jenjang dari TK hingga umum dengan pendekatan personal yang tepat untuk setiap tahap perkembangan.</p>
        </div>

        <div class="jenjang-grid" id="jenjangGrid">
            <a href="<?php echo e(route('register')); ?>" class="jenjang-card">
                <div class="jc-photo-wrap">
                    <img src="https://images.unsplash.com/photo-1503454537195-1dcabb73ffb9?w=200&q=80&auto=format&fit=crop" alt="TK" loading="lazy"
                         onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                    <div class="jc-photo-fallback" style="display:none">🌱</div>
                </div>
                <div class="jc-name">TK</div>
                <div class="jc-label">Taman Kanak-Kanak</div>
                <div class="jc-link">Lihat Detail <i class="bi bi-arrow-right"></i></div>
            </a>

            <a href="<?php echo e(route('register')); ?>" class="jenjang-card">
                <div class="jc-photo-wrap">
                    <img src="https://images.unsplash.com/photo-1588072432836-e10032774350?w=200&q=80&auto=format&fit=crop" alt="SD" loading="lazy"
                         onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                    <div class="jc-photo-fallback" style="display:none">📚</div>
                </div>
                <div class="jc-name">SD</div>
                <div class="jc-label">Sekolah Dasar</div>
                <div class="jc-link">Lihat Detail <i class="bi bi-arrow-right"></i></div>
            </a>

            <a href="<?php echo e(route('register')); ?>" class="jenjang-card">
                <div class="jc-photo-wrap">
                    <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=200&q=80&auto=format&fit=crop" alt="SMP" loading="lazy"
                         onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                    <div class="jc-photo-fallback" style="display:none">🔬</div>
                </div>
                <div class="jc-name">SMP</div>
                <div class="jc-label">Sekolah Menengah Pertama</div>
                <div class="jc-link">Lihat Detail <i class="bi bi-arrow-right"></i></div>
            </a>

            <a href="<?php echo e(route('register')); ?>" class="jenjang-card">
                <div class="jc-photo-wrap">
                    <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=200&q=80&auto=format&fit=crop" alt="SMA" loading="lazy"
                         onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                    <div class="jc-photo-fallback" style="display:none">🎓</div>
                </div>
                <div class="jc-name">SMA / Umum</div>
                <div class="jc-label">SMA &amp; Karyawan</div>
                <div class="jc-link">Lihat Detail <i class="bi bi-arrow-right"></i></div>
            </a>
        </div>
        <div class="mobile-carousel-dots" id="jenjang-dots"></div>
    </div>
</section>


<section class="cari-guru-section" id="cari-guru">
    <div class="cari-guru-inner reveal">
        <div class="cg-eyebrow">TEMUKAN PENGAJAR TERBAIK</div>
        <h2 class="cg-title">Cari Guru <span class="cg-title-accent">Terbaik</span>, Secepat Klik</h2>
        <p class="cg-subtitle">Temukan tutor privat terbaik di kotamu — pilih berdasarkan mata pelajaran, lokasi, dan metode belajar yang kamu inginkan.</p>

        <div class="cg-form">
            <div class="cg-fields">
                <div class="cg-field">
                    <label><i class="bi bi-geo-alt-fill"></i> KOTA / LOKASI</label>
                    <select class="cg-select">
                        <option>Semua Kota</option>
                        <option>Jakarta</option>
                        <option>Surabaya</option>
                        <option>Bandung</option>
                        <option>Medan</option>
                        <option>Yogyakarta</option>
                        <option>Makassar</option>
                    </select>
                </div>
                <div class="cg-field">
                    <label><i class="bi bi-book-fill"></i> MATA PELAJARAN</label>
                    <select class="cg-select">
                        <option>Semua Mata Pelajaran</option>
                        <option>Matematika</option>
                        <option>Bahasa Inggris</option>
                        <option>IPA / Fisika</option>
                        <option>Kimia</option>
                        <option>Bahasa Indonesia</option>
                        <option>Akuntansi</option>
                    </select>
                </div>
                <div class="cg-field">
                    <label><i class="bi bi-laptop-fill"></i> METODE BELAJAR</label>
                    <select class="cg-select">
                        <option>Semua Metode</option>
                        <option>Online</option>
                        <option>Offline / Tatap Muka</option>
                        <option>Home Visit</option>
                    </select>
                </div>
                <a href="<?php echo e(route('register')); ?>" class="btn-cg-search">
                    <i class="bi bi-search"></i> Cari Guru
                </a>
            </div>
            <div class="cg-trust">
                <div class="cg-trust-item"><i class="bi bi-patch-check-fill"></i> 500+ Tutor Bersertifikat</div>
                <div class="cg-trust-item"><i class="bi bi-lightning-fill"></i> Respon dalam 1 Jam</div>
                <div class="cg-trust-item"><i class="bi bi-shield-fill-check"></i> Aman &amp; Terpercaya</div>
                <div class="cg-trust-item"><i class="bi bi-award-fill"></i> Garansi Hasil Belajar</div>
            </div>
        </div>
    </div>
</section>


<section class="keunggulan-dark-section" id="mengapa-sci">
    <div class="container-lp" style="position:relative;z-index:1">
        <div class="text-center reveal">
            <div class="section-eyebrow" style="background:rgba(255,255,255,.1);border-color:rgba(255,255,255,.15);color:rgba(255,255,255,.9)">
                <i class="bi bi-shield-fill-check"></i> Keunggulan
            </div>
            <h2 class="section-title" style="color:white">Keunggulan <em class="section-title-accent">SCI</em></h2>
            <p class="section-subtitle mx-auto" style="color:rgba(255,255,255,.7)">Lima pilar yang membuat SCI menjadi pilihan terpercaya jutaan keluarga Indonesia selama 14+ tahun.</p>
        </div>

        <div class="keunggulan-dark-grid">
            <div class="kd-card reveal reveal-delay-1">
                <div class="kd-photo">
                    <img src="https://images.unsplash.com/photo-1607990281513-2c110a25bd8c?w=200&q=80" alt="Tutor Profesional" loading="lazy">
                </div>
                <div class="kd-title">Tutor Profesional</div>
                <div class="kd-desc">Pengajar ahli bersertifikat resmi dengan pengalaman bertahun-tahun dan rekam jejak hasil nyata.</div>
            </div>
            <div class="kd-card reveal reveal-delay-2">
                <div class="kd-photo">
                    <img src="https://images.unsplash.com/photo-1529156069898-49953e39b3ac?w=200&q=80" alt="Bisa Home Visit" loading="lazy">
                </div>
                <div class="kd-title">Bisa Home Visit</div>
                <div class="kd-desc">Tutor kami siap datang ke rumah Anda kapan saja. Jadwal fleksibel, nyaman, dan tanpa perlu repot.</div>
            </div>
            <div class="kd-card reveal reveal-delay-3">
                <div class="kd-photo">
                    <img src="https://images.unsplash.com/photo-1581092334651-ddf26d9a09d0?w=200&q=80" alt="Metode Modern" loading="lazy">
                </div>
                <div class="kd-title">Metode Modern</div>
                <div class="kd-desc">Sistem belajar interaktif yang disesuaikan dengan gaya belajar masing-masing siswa. Belajar itu menyenangkan!</div>
            </div>
            <div class="kd-card reveal reveal-delay-1">
                <div class="kd-photo">
                    <img src="https://images.unsplash.com/photo-1551836022-d5d88e9218df?w=200&q=80" alt="Hasil Terukur" loading="lazy">
                </div>
                <div class="kd-title">Hasil Terukur</div>
                <div class="kd-desc">Evaluasi rutin, progress terpantau, laporan bulanan. Nilai meningkat signifikan — dijamin atau kami ulang!</div>
            </div>
            <div class="kd-card reveal reveal-delay-2">
                <div class="kd-photo">
                    <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?w=200&q=80" alt="Support Penuh" loading="lazy">
                </div>
                <div class="kd-title">Support Penuh</div>
                <div class="kd-desc">Bantuan belajar & konsultasi 24/7 via WhatsApp. Kami selalu ada untuk mendukung perjalanan belajar Anda.</div>
            </div>
        </div>
    </div>
</section>


<section class="testi-light-section" id="testimonials">
    <div class="container-lp">
        <div class="text-center reveal">
            <div class="program-photo-eyebrow">Kata Mereka</div>
            <h2 class="section-title">Testimoni <em class="section-title-accent">Siswa</em></h2>
            <p class="section-subtitle mx-auto">Dengarkan cerita sukses ribuan siswa yang telah mempercayai SCI sebagai mitra belajar mereka.</p>
        </div>

        <?php
        $testiCards = $dbTestis->isNotEmpty() ? $dbTestis->take(4) : collect([
            (object)['text'=>'Belajar di SCI sangat menyenangkan! Tutor menjelaskan dengan cara yang mudah dipahami dan nilai saya meningkat pesat. Sangat merekomendasikan untuk semua!','name'=>'Aisyah Rahma','role'=>'Siswa SMA · Matematika','initial'=>'A','gradient'=>'linear-gradient(135deg,#c84ddf,#68117e)'],
            (object)['text'=>'Program persiapan ujian di SCI sangat membantu. Akhirnya lolos ke kampus impian! Materinya lengkap banget dan tutornya super sabar dan profesional.','name'=>'Ricky Pratama','role'=>'Mahasiswa · Persiapan SBMPTN','initial'=>'R','gradient'=>'linear-gradient(135deg,#10b981,#059669)'],
            (object)['text'=>'Kursus akuntansi di SCI sangat bermanfaat untuk tugas kuliah dan persiapan kerja. Tutornya sabar, materi lengkap, dan nilai kuliah saya jadi meningkat!','name'=>'Dinda Lestari','role'=>'Mahasiswi · Akuntansi','initial'=>'D','gradient'=>'linear-gradient(135deg,#6366f1,#4338ca)'],
            (object)['text'=>'Anakku yang awalnya kesulitan di pelajaran IPA sekarang jadi juara kelas! Metode belajar di SCI sangat efektif dan tutornya sangat sabar dan perhatian.','name'=>'Bunda Sari','role'=>'Orang Tua Siswa · Jakarta','initial'=>'B','gradient'=>'linear-gradient(135deg,#f97316,#ea580c)'],
        ]);
        ?>
        <div class="testi-light-grid">
            <?php $__currentLoopData = $testiCards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="tlc reveal reveal-delay-<?php echo e(($i % 4) + 1); ?>">
                <div class="tlc-stars">
                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                </div>
                <div class="tlc-quote">"</div>
                <p class="tlc-text"><?php echo e($t->text); ?></p>
                <div class="tlc-author">
                    <div class="tlc-avatar-fb" style="background:<?php echo e($t->gradient); ?>"><?php echo e($t->initial); ?></div>
                    <div>
                        <div class="tlc-name"><?php echo e($t->name); ?></div>
                        <div class="tlc-role"><?php echo e($t->role); ?></div>
                        <div class="tlc-badge"><i class="bi bi-patch-check-fill"></i> Siswa Terverifikasi</div>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>


<section class="section-pad" id="galeri">
    <div class="container-lp">
        <div class="text-center reveal">
            <h2 class="section-title">Galeri <em class="section-title-accent">Kegiatan</em></h2>
            <p class="section-subtitle mx-auto">Momen belajar menyenangkan bersama siswa dan tutor terbaik SCI di seluruh Indonesia.</p>
        </div>

        <?php
        $galeriPhotos = [
            ['url'=>'https://images.unsplash.com/photo-1580582932707-520aed937b7b?w=700&q=80','alt'=>'Kelas Belajar'],
            ['url'=>'https://images.unsplash.com/photo-1509062522246-3755977927d7?w=700&q=80','alt'=>'Diskusi Kelompok'],
            ['url'=>'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=700&q=80','alt'=>'Les Online'],
            ['url'=>'https://images.unsplash.com/photo-1544717305-2782549b5136?w=700&q=80','alt'=>'Tutor Mengajar'],
            ['url'=>'https://images.unsplash.com/photo-1427504494785-3a9ca7044f45?w=700&q=80','alt'=>'Les Privat'],
            ['url'=>'https://images.unsplash.com/photo-1509869175650-a1d97972541a?w=700&q=80','alt'=>'Kursus Komputer'],
            ['url'=>'https://images.unsplash.com/photo-1434030216411-0b793f4b4173?w=700&q=80','alt'=>'Persiapan Ujian'],
            ['url'=>'https://images.unsplash.com/photo-1543269865-cbf427effbad?w=700&q=80','alt'=>'Belajar Bersama'],
        ];
        ?>

        <div class="galeri-row-wrap">
            <div class="galeri-row" id="galeriRow">
                <?php $__currentLoopData = $galeriPhotos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="galeri-row-item">
                    <img src="<?php echo e($gp['url']); ?>" alt="<?php echo e($gp['alt']); ?>" loading="lazy">
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <div class="galeri-nav">
            <button class="galeri-nav-btn" id="galeriPrev" aria-label="Sebelumnya"><i class="bi bi-chevron-left"></i></button>
            <button class="galeri-nav-btn" id="galeriNext" aria-label="Berikutnya"><i class="bi bi-chevron-right"></i></button>
        </div>
    </div>
</section>


<section class="tutor-grid-section" id="tutor">
    <div class="container-lp">
        <div class="text-center reveal">
            <div class="program-photo-eyebrow">Tim Pengajar</div>
            <h2 class="section-title">Tutor <em class="section-title-accent">Terbaik</em> Kami</h2>
            <p class="section-subtitle mx-auto">Dilatih secara profesional dan berpengalaman di bidangnya masing-masing untuk memberikan hasil terbaik bagi setiap siswa.</p>
        </div>

        <?php
        $tutorGridItems = $tutors->isNotEmpty() ? $tutors->take(6) : collect([
            (object)['name'=>'Ms. Anisa Putri',   'subjects'=>['Matematika'],   'photo'=>null,'gender'=>'P'],
            (object)['name'=>'Mr. Budi Santoso',  'subjects'=>['Fisika'],       'photo'=>null,'gender'=>'L'],
            (object)['name'=>'Ms. Cindy Lestari', 'subjects'=>['Bahasa Inggris'],'photo'=>null,'gender'=>'P'],
            (object)['name'=>'Mr. Dimas Arif',    'subjects'=>['Akuntansi'],    'photo'=>null,'gender'=>'L'],
            (object)['name'=>'Ms. Rina Wulandari','subjects'=>['Kimia'],        'photo'=>null,'gender'=>'P'],
            (object)['name'=>'Mr. Hendra Saputra','subjects'=>['Biologi'],      'photo'=>null,'gender'=>'L'],
        ]);
        $ratingPool = [4.9, 4.8, 4.7, 4.8, 4.9, 4.7];
        $expPool    = [7, 9, 6, 8, 6, 5];
        ?>
        <div class="tutor-grid-cards">
            <?php $__currentLoopData = $tutorGridItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ti => $tutor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $subj    = is_array($tutor->subjects) ? ($tutor->subjects[0] ?? 'Tutor') : ($tutor->subjects ?? 'Tutor');
                $init    = strtoupper(substr($tutor->name ?? 'T', 0, 1));
                $grad    = $tutorGrads[$ti % count($tutorGrads)];
                $hasPhoto= !empty($tutor->photo) && file_exists(public_path('storage/'.$tutor->photo));
                $rating  = $ratingPool[$ti % count($ratingPool)];
                $exp     = $expPool[$ti % count($expPool)];
            ?>
            <div class="tgc reveal reveal-delay-<?php echo e(($ti % 3) + 1); ?>">
                <div class="tgc-photo-wrap">
                    <div class="tgc-photo" style="background:<?php echo e($grad); ?>">
                        <?php if($hasPhoto): ?>
                            <img src="<?php echo e(asset('storage/'.$tutor->photo)); ?>" alt="<?php echo e($tutor->name); ?>" loading="lazy">
                        <?php else: ?>
                            <div class="tgc-photo-fb" style="background:<?php echo e($grad); ?>"><?php echo e($init); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="tgc-star-badge">⭐</div>
                </div>
                <div class="tgc-name"><?php echo e($tutor->name); ?></div>
                <div class="tgc-subject"><?php echo e($subj); ?></div>
                <div class="tgc-rating"><i class="bi bi-star-fill"></i> <b><?php echo e($rating); ?></b></div>
                <div class="tgc-exp"><?php echo e($exp); ?> Tahun Pengalaman</div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>


<section class="bantuan-section" id="bantuan">
    <div class="container-lp">
        <div class="text-center reveal">
            <div class="program-photo-eyebrow">Bantuan &amp; Kontak</div>
            <h2 class="section-title">Pertanyaan &amp; <em class="section-title-accent">Hubungi Kami</em></h2>
            <p class="section-subtitle mx-auto">Punya pertanyaan atau ingin bergabung? Kami siap membantu Anda kapan saja.</p>
        </div>

        <div class="bantuan-inner">
            
            <div class="faq-list reveal">
                <?php
                $faqs = [
                    ['q'=>'Bagaimana cara mendaftar di SCI?',           'a'=>'Anda bisa mendaftar melalui website ini, menghubungi kami via WhatsApp, atau langsung datang ke cabang SCI terdekat. Tim kami akan membantu proses pendaftaran dengan mudah dan cepat.'],
                    ['q'=>'Apakah bisa datang ke rumah?',               'a'=>'Ya! Kami menyediakan layanan home visit di mana tutor kami akan datang langsung ke rumah Anda. Jadwal fleksibel dan nyaman tanpa perlu keluar rumah.'],
                    ['q'=>'Jenjang apa saja yang dilayani?',            'a'=>'SCI melayani semua jenjang mulai dari TK, SD, SMP, SMA, hingga mahasiswa dan umum. Tersedia juga kursus bahasa, komputer, dan akuntansi untuk semua usia.'],
                    ['q'=>'Berapa biaya les privat di SCI?',            'a'=>'Biaya bervariasi tergantung jenjang, mata pelajaran, dan metode belajar (online/offline/home visit). Hubungi kami untuk mendapatkan penawaran terbaik sesuai kebutuhan Anda.'],
                    ['q'=>'Apakah ada garansi hasil belajar?',          'a'=>'Ya! SCI memberikan garansi hasil belajar. Jika nilai tidak meningkat sesuai target yang disepakati, kami siap memberikan sesi tambahan tanpa biaya ekstra.'],
                    ['q'=>'Bagaimana sistem pembayaran di SCI?',        'a'=>'Pembayaran bisa dilakukan bulanan atau per paket belajar. Tersedia berbagai metode pembayaran termasuk transfer bank, dompet digital, dan tunai di cabang.'],
                ];
                ?>
                <?php $__currentLoopData = $faqs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fi => $faq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="faq-item <?php echo e($fi === 0 ? 'open' : ''); ?>">
                    <div class="faq-trigger" onclick="toggleFaq(this)">
                        <span><?php echo e($faq['q']); ?></span>
                        <span class="faq-icon"><i class="bi bi-plus"></i></span>
                    </div>
                    <div class="faq-body">
                        <div class="faq-body-inner"><?php echo e($faq['a']); ?></div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            
            <div class="contact-dark-card reveal reveal-delay-2">
                <div class="contact-dark-title">Kirim Pesan 📩</div>
                <div class="contact-dark-sub">Isi form di bawah ini, kami akan segera menghubungi Anda.</div>
                <form action="https://wa.me/<?php echo e($waMain); ?>" method="get" target="_blank" id="contactForm" onsubmit="return submitContact(event)">
                    <div class="contact-form-row">
                        <div class="contact-field">
                            <label>Nama Lengkap</label>
                            <input type="text" id="cfName" placeholder="Nama Anda" required>
                        </div>
                        <div class="contact-field">
                            <label>No. WhatsApp</label>
                            <input type="tel" id="cfPhone" placeholder="08xxxxxx" required>
                        </div>
                    </div>
                    <div class="contact-field" style="margin-bottom:1rem">
                        <label>Kota / Cabang</label>
                        <select id="cfCity">
                            <option value="">Pilih kota</option>
                            <?php $branches2 = \App\Models\Branch::orderBy('id')->get(); ?>
                            <?php if($branches2->count()): ?>
                                <?php $__currentLoopData = $branches2; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $br): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <option><?php echo e($br->nama ?? $br->name); ?></option> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <option>Jakarta</option><option>Surabaya</option><option>Bandung</option>
                                <option>Yogyakarta</option><option>Medan</option><option>Makassar</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="contact-field">
                        <label>Pesan / Kebutuhan Anda</label>
                        <textarea id="cfMsg" placeholder="Ceritakan kebutuhan belajar Anda..."></textarea>
                    </div>
                    <button type="submit" class="btn-send">Kirim Pesan 🚀</button>
                </form>
            </div>
        </div>
    </div>
</section>


<section class="cabang-photo-section" id="cabang">
    <div class="container-lp">
        <div class="text-center reveal">
            <div class="program-photo-eyebrow">Hadir di Seluruh Indonesia</div>
            <h2 class="section-title">Cabang SCI <em class="section-title-accent">Indonesia</em></h2>
            <p class="section-subtitle mx-auto">Dengan 150+ cabang di berbagai kota, SCI selalu dekat dengan Anda dan keluarga.</p>
        </div>

        <?php
        $branches3 = \App\Models\Branch::take(3)->get();
        $cityPhotos = [
            'https://images.unsplash.com/photo-1555899434-94d1368aa7af?w=800&q=80',
            'https://images.unsplash.com/photo-1477959858617-67f85cf4f1df?w=800&q=80',
            'https://images.unsplash.com/photo-1486325212027-8081e485255e?w=800&q=80',
        ];
        $cabangDisplay = $branches3->count() >= 3 ? $branches3 : collect([
            (object)['nama'=>'Riau',           'alamat'=>null],
            (object)['nama'=>'Sumatera Barat', 'alamat'=>null],
            (object)['nama'=>'Sumatera Utara', 'alamat'=>null],
        ]);
        ?>

        <div class="cabang-photo-grid reveal">
            
            <div class="cabang-photo-left">
                <?php $__currentLoopData = [$cabangDisplay[0], $cabangDisplay[2]]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ci => $cb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $bName = $cb->nama ?? $cb->name ?? 'Cabang SCI'; ?>
                <div class="cpc">
                    <img src="<?php echo e($cityPhotos[$ci === 0 ? 0 : 2]); ?>" alt="<?php echo e($bName); ?>" loading="lazy">
                    <div class="cpc-overlay">
                        <div class="cpc-name"><?php echo e($bName); ?></div>
                        <div class="cpc-sub">Jasa Les Privat <?php echo e($bName); ?></div>
                        <a href="https://wa.me/<?php echo e($waMain); ?>?text=<?php echo e(urlencode('Halo SCI, saya ingin tanya tentang les privat di '.$bName)); ?>" target="_blank" class="btn-cpc">Lihat Detail</a>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            
            <?php $cb2 = $cabangDisplay[1]; $bName2 = $cb2->nama ?? $cb2->name ?? 'Cabang SCI'; ?>
            <div class="cpc cpc-tall">
                <img src="<?php echo e($cityPhotos[1]); ?>" alt="<?php echo e($bName2); ?>" loading="lazy" style="height:100%;min-height:410px">
                <div class="cpc-overlay">
                    <div class="cpc-name"><?php echo e($bName2); ?></div>
                    <div class="cpc-sub">Jasa Les Privat <?php echo e($bName2); ?></div>
                    <a href="https://wa.me/<?php echo e($waMain); ?>?text=<?php echo e(urlencode('Halo SCI, saya ingin tanya tentang les privat di '.$bName2)); ?>" target="_blank" class="btn-cpc">Lihat Detail</a>
                </div>
            </div>
        </div>
    </div>
</section>


<a href="https://wa.me/<?php echo e($waMain); ?>?text=<?php echo e(urlencode('Halo Smart Center Indonesia! Saya ingin konsultasi tentang program bimbel/kursus. Bisa bantu?')); ?>"
   class="wa-float" target="_blank" rel="noopener" aria-label="Hubungi via WhatsApp">
    <i class="bi bi-whatsapp"></i>
    <span class="wa-float-label">Konsultasi Gratis 💬</span>
</a>

<button class="scroll-top" id="scrollTopBtn" aria-label="Kembali ke atas" onclick="window.scrollTo({top:0,behavior:'smooth'})">
    <i class="bi bi-arrow-up"></i>
</button>


<footer class="footer">
    <div class="container-lp">
        <div class="footer-grid">
            
            <div>
                <a href="<?php echo e(url('/')); ?>" class="nav-brand" style="text-decoration:none">
                    <div class="nav-brand-icon"><i class="bi bi-mortarboard-fill"></i></div>
                    <div class="nav-brand-text" style="color:white">
                        Smart Center<small style="color:rgba(255,255,255,.5)">Indonesia</small>
                    </div>
                </a>
                <p style="font-size:.82rem;color:rgba(255,255,255,.5);margin-top:1rem;line-height:1.75;max-width:260px">Wujudkan Mimpi, Raih Prestasi</p>
                <p class="footer-brand-desc">Platform pendidikan modern untuk semua jenjang. Dari TK hingga profesional — kami selalu ada untuk mendukung perjalanan belajar Anda.</p>
                <div class="footer-social">
                    <a href="<?php echo e($ls('footer.facebook','#')); ?>" title="Facebook"><i class="bi bi-facebook"></i></a>
                    <a href="<?php echo e($ls('footer.instagram','#')); ?>" title="Instagram"><i class="bi bi-instagram"></i></a>
                    <a href="<?php echo e($ls('footer.youtube','#')); ?>" title="YouTube"><i class="bi bi-youtube"></i></a>
                    <a href="https://wa.me/<?php echo e($waMain); ?>" title="WhatsApp"><i class="bi bi-whatsapp"></i></a>
                </div>
            </div>

            
            <div>
                <div class="footer-col-title">Navigasi</div>
                <ul class="footer-links">
                    <li><a href="#home">Beranda</a></li>
                    <li><a href="#tentang">Tentang Kami</a></li>
                    <li><a href="#program-unggulan">Program</a></li>
                    <li><a href="#mengapa-sci">Keunggulan</a></li>
                    <li><a href="#testimonials">Testimoni</a></li>
                </ul>
            </div>

            
            <div>
                <div class="footer-col-title">Layanan</div>
                <ul class="footer-links">
                    <li><a href="#galeri">Galeri</a></li>
                    <li><a href="#tutor">Tutor</a></li>
                    <li><a href="#bantuan">FAQ</a></li>
                    <li><a href="#cabang">Cabang</a></li>
                    <li><a href="#bantuan">Kontak</a></li>
                </ul>
            </div>

            
            <div>
                <div class="footer-col-title">Kontak</div>
                <ul class="footer-links">
                    <li><a href="tel:+6285333399210"><i class="bi bi-telephone-fill" style="color:var(--primary);margin-right:6px"></i>+62 853-3339-9210</a></li>
                    <li><a href="mailto:smartcenterindonesia@gmail.com"><i class="bi bi-envelope-fill" style="color:var(--primary);margin-right:6px"></i>smartcenterindonesia@gmail.com</a></li>
                    <li style="color:rgba(255,255,255,.5);font-size:.82px"><i class="bi bi-clock-fill" style="color:var(--primary);margin-right:6px;font-size:.82rem"></i>Senin–Sabtu (08.00–20.00)</li>
                    <li style="color:rgba(255,255,255,.5);font-size:.82rem;margin-top:2px"><i class="bi bi-geo-alt-fill" style="color:var(--primary);margin-right:6px"></i>150+ Cabang di Indonesia</li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; <?php echo e(date('Y')); ?> Smart Center Indonesia (SCI). All Rights Reserved.</p>
            <p style="font-size:.78rem;color:rgba(255,255,255,.35)">Made with ❤️ for Indonesian Education</p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
/* ── Navbar scroll + pill ── */
const navbar = document.getElementById('navbar');
window.addEventListener('scroll', () => {
    navbar.classList.toggle('scrolled', window.scrollY > 60);
}, { passive: true });

/* ── Active nav link tracking ── */
const sections = document.querySelectorAll('section[id]');
const navLinks = document.querySelectorAll('.nav-link-item');
let lastActiveId = '';
const navObs = new IntersectionObserver((entries) => {
    entries.forEach(e => {
        if (e.isIntersecting) {
            lastActiveId = e.target.id;
            navLinks.forEach(l => l.classList.remove('nav-active'));
            const active = document.querySelector(`.nav-link-item[href="#${e.target.id}"]`);
            if (active) active.classList.add('nav-active');
        }
    });
}, { threshold: 0.25, rootMargin: '-80px 0px -35% 0px' });
sections.forEach(s => navObs.observe(s));

/* ── Mobile menu ── */
const toggle     = document.getElementById('navToggle');
const mobileMenu = document.getElementById('mobileMenu');
toggle.addEventListener('click', () => {
    const open = mobileMenu.classList.toggle('open');
    toggle.classList.toggle('open', open);
    document.body.style.overflow = open ? 'hidden' : '';
    mobileMenu.setAttribute('aria-hidden', open ? 'false' : 'true');
});
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeMobile(); });
function closeMobile() {
    mobileMenu.classList.remove('open');
    toggle.classList.remove('open');
    document.body.style.overflow = '';
    mobileMenu.setAttribute('aria-hidden', 'true');
}

/* ── Hero slideshow ── */
(function initSlider() {
    const slides  = document.querySelectorAll('.hero-slide');
    const dots    = document.querySelectorAll('.hero-dot');
    const btnL    = document.getElementById('heroArrowLeft');
    const btnR    = document.getElementById('heroArrowRight');
    if (slides.length <= 1) { if(btnL) btnL.style.display='none'; if(btnR) btnR.style.display='none'; return; }
    let current = 0;
    let timer   = null;
    function goTo(idx) {
        slides[current].classList.remove('active');
        if (dots[current]) dots[current].classList.remove('active');
        current = (idx + slides.length) % slides.length;
        slides[current].classList.add('active');
        if (dots[current]) dots[current].classList.add('active');
    }
    function startAuto() {
        clearInterval(timer);
        timer = setInterval(() => goTo(current + 1), 6000);
    }
    dots.forEach(dot => {
        dot.addEventListener('click', () => { clearInterval(timer); goTo(parseInt(dot.dataset.slide)); startAuto(); });
    });
    if (btnL) btnL.addEventListener('click', () => { clearInterval(timer); goTo(current - 1); startAuto(); });
    if (btnR) btnR.addEventListener('click', () => { clearInterval(timer); goTo(current + 1); startAuto(); });
    document.addEventListener('visibilitychange', () => { document.hidden ? clearInterval(timer) : startAuto(); });
    startAuto();
})();

/* ── Scroll-reveal ── */
const revealEls = document.querySelectorAll('.reveal');
const revealObs = new IntersectionObserver((entries) => {
    entries.forEach(e => {
        if (e.isIntersecting) { e.target.classList.add('visible'); revealObs.unobserve(e.target); }
    });
}, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
revealEls.forEach(el => revealObs.observe(el));

/* ── Count-up ── */
function countUp(el) {
    const target = parseInt(el.dataset.target, 10);
    if (!target) return;
    const exact  = el.dataset.exact === '1';
    const suffix = (!exact && target > 0) ? '+' : '';
    const duration = 1600;
    const step = target / (duration / 16);
    let current = 0;
    const timer = setInterval(() => {
        current = Math.min(current + step, target);
        el.textContent = Math.floor(current) + suffix;
        if (current >= target) clearInterval(timer);
    }, 16);
}
const countObs = new IntersectionObserver((entries) => {
    entries.forEach(e => {
        if (e.isIntersecting) { countUp(e.target); countObs.unobserve(e.target); }
    });
}, { threshold: 0.5 });
document.querySelectorAll('.count-up[data-target]').forEach(el => countObs.observe(el));

/* ── Smooth anchor scroll ── */
document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', e => {
        const id = a.getAttribute('href').slice(1);
        const target = document.getElementById(id);
        if (target) { e.preventDefault(); target.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
    });
});

/* ── Scroll-to-top ── */
const scrollTopBtn = document.getElementById('scrollTopBtn');
window.addEventListener('scroll', () => {
    scrollTopBtn.classList.toggle('visible', window.scrollY > 400);
}, { passive: true });

/* ── Mobile carousel dots ── */
function initMobileCarousel(gridId, dotsId) {
    const grid   = document.getElementById(gridId);
    const dotsEl = document.getElementById(dotsId);
    if (!grid || !dotsEl) return;
    const mq = window.matchMedia('(max-width:640px)');
    function buildDots() {
        dotsEl.innerHTML = '';
        if (!mq.matches) return;
        const cards = Array.from(grid.children);
        cards.forEach((_, i) => {
            const btn = document.createElement('button');
            btn.className = 'mcd' + (i === 0 ? ' active' : '');
            btn.setAttribute('aria-label', 'Slide ' + (i + 1));
            btn.addEventListener('click', () => {
                const pl = parseFloat(getComputedStyle(grid).paddingLeft) || 0;
                grid.scrollTo({ left: Math.max(0, cards[i].offsetLeft - pl), behavior: 'smooth' });
            });
            dotsEl.appendChild(btn);
        });
    }
    function updateDots() {
        if (!mq.matches) return;
        const cards = Array.from(grid.children);
        const gap   = parseFloat(getComputedStyle(grid).gap) || 16;
        const cardW = (cards[0]?.offsetWidth || 1) + gap;
        const idx   = Math.min(Math.round(grid.scrollLeft / cardW), cards.length - 1);
        dotsEl.querySelectorAll('.mcd').forEach((d, i) => d.classList.toggle('active', i === idx));
    }
    grid.addEventListener('scroll', updateDots, { passive: true });
    mq.addEventListener('change', buildDots);
    buildDots();
}
initMobileCarousel('jenjangGrid', 'jenjang-dots');
initMobileCarousel('programGrid', 'program-dots');

/* ── FAQ accordion ── */
function toggleFaq(trigger) {
    const item = trigger.closest('.faq-item');
    const isOpen = item.classList.contains('open');
    document.querySelectorAll('.faq-item.open').forEach(el => el.classList.remove('open'));
    if (!isOpen) item.classList.add('open');
}

/* ── Gallery row navigation ── */
(function() {
    const row   = document.getElementById('galeriRow');
    const prev  = document.getElementById('galeriPrev');
    const next  = document.getElementById('galeriNext');
    if (!row || !prev || !next) return;
    const items = Array.from(row.children);
    const total = items.length;
    const vis   = () => window.innerWidth < 600 ? 2 : window.innerWidth < 900 ? 2 : 4;
    let offset  = 0;
    function clamp(v) { return Math.max(0, Math.min(v, total - vis())); }
    function render() {
        items.forEach((el, i) => {
            el.style.display = (i >= offset && i < offset + vis()) ? '' : 'none';
        });
        prev.disabled = offset === 0;
        next.disabled = offset >= total - vis();
        prev.style.opacity = prev.disabled ? '.4' : '';
        next.style.opacity = next.disabled ? '.4' : '';
    }
    prev.addEventListener('click', () => { offset = clamp(offset - 1); render(); });
    next.addEventListener('click', () => { offset = clamp(offset + 1); render(); });
    window.addEventListener('resize', () => { offset = clamp(offset); render(); });
    render();
})();

/* ── Contact form WA redirect ── */
function submitContact(e) {
    e.preventDefault();
    const name  = document.getElementById('cfName')?.value  || '';
    const phone = document.getElementById('cfPhone')?.value || '';
    const city  = document.getElementById('cfCity')?.value  || '';
    const msg   = document.getElementById('cfMsg')?.value   || '';
    const text  = `Halo Smart Center Indonesia!\nNama: ${name}\nNo. WA: ${phone}\nKota: ${city}\nPesan: ${msg}`;
    window.open('https://wa.me/<?php echo e($waMain); ?>?text=' + encodeURIComponent(text), '_blank');
    return false;
}

/* ── Respect reduced-motion ── */
if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    document.querySelectorAll('.carousel-track, .tutor-carousel-track').forEach(el => {
        el.style.animationDuration = '0s';
        el.style.animationPlayState = 'paused';
    });
}

/* ── Scroll progress bar ── */
(function() {
    const bar = document.getElementById('scroll-progress');
    if (!bar) return;
    function updateBar() {
        const scrolled = window.scrollY;
        const total    = document.documentElement.scrollHeight - window.innerHeight;
        bar.style.width = total > 0 ? Math.min((scrolled / total) * 100, 100) + '%' : '0%';
    }
    window.addEventListener('scroll', updateBar, { passive: true });
    updateBar();
})();

/* ── Float card mouse parallax ── */
(function() {
    const fc1  = document.querySelector('.float-card-1');
    const fc2  = document.querySelector('.float-card-2');
    const hero = document.querySelector('.hero');
    if (!fc1 || !fc2 || !hero) return;
    if (window.matchMedia('(max-width:900px)').matches) return;
    let rafId = null;
    hero.addEventListener('mousemove', e => {
        if (rafId) cancelAnimationFrame(rafId);
        rafId = requestAnimationFrame(() => {
            const rect = hero.getBoundingClientRect();
            const cx   = (e.clientX - rect.left) / rect.width  - 0.5;
            const cy   = (e.clientY - rect.top)  / rect.height - 0.5;
            fc1.style.transform = `translate(${cx * -16}px, ${cy * -11}px) rotate(-1deg)`;
            fc2.style.transform = `translate(${cx *  14}px, ${cy *  9}px)  rotate(1deg)`;
        });
    });
    hero.addEventListener('mouseleave', () => {
        if (rafId) cancelAnimationFrame(rafId);
        fc1.style.transform = '';
        fc2.style.transform = '';
    });
})();
</script>
</body>
</html>
<?php /**PATH /home/runner/workspace/resources/views/landing.blade.php ENDPATH**/ ?>