@php
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
@endphp
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
        .lp-nav { position:fixed; top:0; left:0; right:0; z-index:1000; padding:1rem 0; transition:padding .45s var(--ease-in-out); }
        .lp-nav.scrolled { padding:.85rem 2rem; }
        .nav-inner {
            display:flex; align-items:center; justify-content:space-between;
            max-width:1200px; margin:0 auto; padding:0 1.5rem;
            border-radius:0; background:transparent;
            transition:background .45s var(--ease-in-out), border-radius .45s var(--ease-in-out),
                        box-shadow .45s var(--ease-in-out), padding .45s var(--ease-in-out), max-width .45s var(--ease-in-out);
        }
        .lp-nav.scrolled .nav-inner {
            background:rgba(255,255,255,.96); backdrop-filter:blur(28px); -webkit-backdrop-filter:blur(28px);
            border-radius:100px; padding:.4rem .5rem .4rem 1.5rem;
            box-shadow:0 10px 44px rgba(0,0,0,.13),0 2px 10px rgba(0,0,0,.07); max-width:960px;
        }
        .lp-nav.scrolled .nav-brand-text    { color:var(--deep); }
        .lp-nav.scrolled .nav-link-item     { color:#4b5563; }
        .lp-nav.scrolled .nav-link-item:hover { color:var(--primary-dark); background:rgba(200,77,223,.08); }
        .lp-nav.scrolled .nav-link-item.nav-active { background:var(--gold); color:#1a1a1a !important; font-weight:700; border-radius:50px; }
        .lp-nav.scrolled .nav-toggle span   { background:var(--deep); }

        .nav-brand { display:flex; align-items:center; gap:10px; text-decoration:none; }
        .nav-brand-icon {
            width:38px; height:38px; border-radius:12px;
            background:linear-gradient(135deg,var(--primary-dark),var(--primary));
            display:flex; align-items:center; justify-content:center; font-size:19px; color:white;
            flex-shrink:0; box-shadow:0 4px 12px rgba(200,77,223,.4);
        }
        .nav-brand-text { font-family:var(--font-display); font-weight:800; font-size:1.05rem; color:white; letter-spacing:-.02em; line-height:1.1; }
        .nav-brand-text small { display:block; font-size:.65rem; font-weight:500; opacity:.7; letter-spacing:.02em; }

        .nav-links { display:flex; align-items:center; gap:.25rem; list-style:none; }
        .nav-link-item { color:rgba(255,255,255,.85); text-decoration:none; font-size:.88rem; font-weight:500; padding:.45rem .85rem; border-radius:8px; transition:color .2s, background .2s; }
        .nav-link-item:hover { color:white; background:rgba(255,255,255,.12); }

        .nav-cta { display:flex; align-items:center; gap:.6rem; }
        .btn-nav-login { padding:.45rem 1.1rem; border-radius:10px; font-size:.88rem; font-weight:600; color:rgba(255,255,255,.9); border:1.5px solid rgba(255,255,255,.25); background:transparent; text-decoration:none; transition:.2s; }
        .btn-nav-login:hover { color:white; border-color:rgba(255,255,255,.5); background:rgba(255,255,255,.1); }
        .lp-nav.scrolled .btn-nav-login { color:var(--primary-dark); border-color:rgba(200,77,223,.28); }
        .lp-nav.scrolled .btn-nav-login:hover { background:rgba(200,77,223,.07); border-color:var(--primary); }

        .btn-nav-register { padding:.45rem 1.2rem; border-radius:10px; font-size:.88rem; font-weight:700; color:white; background:linear-gradient(135deg,var(--primary-dark),var(--primary)); text-decoration:none; border:none; transition:transform .2s, box-shadow .2s, border-radius .45s var(--ease-in-out); box-shadow:0 4px 14px rgba(200,77,223,.35); }
        .btn-nav-register:hover { transform:translateY(-2px); box-shadow:0 8px 22px rgba(200,77,223,.5); color:white; }
        .lp-nav.scrolled .btn-nav-register { border-radius:50px; padding:.5rem 1.4rem; }

        .nav-toggle { display:none; flex-direction:column; gap:5px; cursor:pointer; padding:6px; background:none; border:none; }
        .nav-toggle span { display:block; width:24px; height:2px; background:white; border-radius:2px; transition:.3s var(--ease-in-out); }
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
        .hero { position:relative; min-height:100vh; overflow:hidden; display:flex; align-items:center; justify-content:center; }
        .hero-slides { position:absolute; inset:0; z-index:0; }
        .hero-slide { position:absolute; inset:0; background-size:cover; background-position:center center; background-repeat:no-repeat; opacity:0; transition:opacity 1.4s ease-in-out; transform:scale(1.04); }
        .hero-slide.active { opacity:1; animation:hero-zoom 8s ease-in-out forwards; }
        @keyframes hero-zoom { from { transform:scale(1.06); } to { transform:scale(1.0); } }
        .hero-slide-overlay { position:absolute; inset:0; background:linear-gradient(to bottom, rgba(20,5,32,.72) 0%, rgba(20,5,32,.42) 40%, rgba(20,5,32,.52) 65%, rgba(20,5,32,.78) 100%); }
        .hero-inner { position:relative; z-index:2; text-align:center; max-width:860px; padding:9rem 2rem 8rem; margin:0 auto; width:100%; }
        .hero-badge { display:inline-flex; align-items:center; gap:8px; background:rgba(255,255,255,.12); backdrop-filter:blur(12px); border:1px solid rgba(255,255,255,.18); border-radius:50px; padding:6px 18px 6px 6px; font-size:.8rem; font-weight:600; color:rgba(255,255,255,.9); margin-bottom:1.75rem; animation:fade-up .6s var(--ease-out) both; }
        .hero-badge-dot { width:24px; height:24px; border-radius:50%; background:linear-gradient(135deg,var(--gold),var(--primary)); display:flex; align-items:center; justify-content:center; font-size:12px; animation:pulse-dot 2s ease-in-out infinite; }
        @keyframes pulse-dot { 0%,100% { box-shadow:0 0 0 0 rgba(246,175,35,.4); } 50% { box-shadow:0 0 0 6px rgba(246,175,35,0); } }
        .hero-title { font-size:clamp(2.4rem,5.5vw,4.2rem); font-weight:900; color:white; line-height:1.08; margin-bottom:1.25rem; text-shadow:0 2px 24px rgba(0,0,0,.3); animation:fade-up .7s var(--ease-out) .1s both; }
        .hero-title .gradient-text { background:linear-gradient(90deg,var(--gold),#f8d07a); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
        .hero-desc { font-size:clamp(.92rem,1.5vw,1.1rem); color:rgba(255,255,255,.82); line-height:1.8; margin-bottom:2.5rem; max-width:640px; margin-left:auto; margin-right:auto; animation:fade-up .7s var(--ease-out) .2s both; }
        .hero-actions { display:flex; gap:1rem; flex-wrap:wrap; justify-content:center; animation:fade-up .7s var(--ease-out) .3s both; }
        .btn-hero-primary { display:inline-flex; align-items:center; gap:8px; padding:.95rem 2.2rem; border-radius:50px; font-size:.95rem; font-weight:700; color:#1a0a00; background:linear-gradient(135deg,var(--gold),#f8d07a); text-decoration:none; border:none; transition:transform .25s, box-shadow .25s; box-shadow:0 8px 28px rgba(246,175,35,.45); letter-spacing:-.01em; }
        .btn-hero-primary:hover { transform:translateY(-3px); box-shadow:0 14px 40px rgba(246,175,35,.6); color:#1a0a00; }
        .btn-hero-secondary { display:inline-flex; align-items:center; gap:8px; padding:.95rem 2rem; border-radius:50px; font-size:.95rem; font-weight:600; color:white; border:2px solid rgba(255,255,255,.35); background:rgba(255,255,255,.1); backdrop-filter:blur(10px); text-decoration:none; transition:.25s; }
        .btn-hero-secondary:hover { background:rgba(255,255,255,.2); border-color:rgba(255,255,255,.6); transform:translateY(-2px); color:white; }
        .hero-trust { display:flex; align-items:center; gap:1rem; margin-top:2.75rem; justify-content:center; animation:fade-up .7s var(--ease-out) .4s both; }
        .hero-avatars { display:flex; }
        .hero-avatar { width:34px; height:34px; border-radius:50%; border:2.5px solid rgba(255,255,255,.35); background:linear-gradient(135deg,var(--primary-dark),var(--primary)); display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:700; color:white; margin-left:-10px; }
        .hero-avatars .hero-avatar:first-child { margin-left:0; }
        .hero-trust-text { font-size:.8rem; color:rgba(255,255,255,.75); line-height:1.4; }
        .hero-trust-text strong { color:white; display:block; }
        .hero-dots { position:absolute; bottom:5.5rem; left:50%; transform:translateX(-50%); display:flex; align-items:center; gap:9px; z-index:3; }
        .hero-dot { width:10px; height:10px; border-radius:50%; background:rgba(255,255,255,.38); border:none; cursor:pointer; transition:background .35s, width .35s, border-radius .35s; padding:0; }
        .hero-dot.active { background:white; width:30px; border-radius:5px; }
        .hero-dot:hover:not(.active) { background:rgba(255,255,255,.7); }

        .float-card { position:absolute; background:white; border-radius:16px; padding:12px 16px; box-shadow:0 20px 60px rgba(0,0,0,.25),0 0 0 1px rgba(200,77,223,.08); animation:float-card 4s ease-in-out infinite alternate; z-index:3; white-space:nowrap; }
        .float-card-1 { bottom:9rem; left:3rem; animation-delay:0s; }
        .float-card-2 { top:8rem; right:3rem; animation-delay:2s; }
        @keyframes float-card { from { transform:translateY(0) rotate(-1deg); } to { transform:translateY(-12px) rotate(1deg); } }
        .float-card-content { display:flex; align-items:center; gap:10px; }
        .float-icon { width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:18px; flex-shrink:0; }
        .float-card-text { font-family:var(--font-display); }
        .float-card-text .fc-val { font-size:1rem; font-weight:800; color:var(--deep); line-height:1; letter-spacing:-.02em; }
        .float-card-text .fc-lab { font-size:.7rem; color:var(--text-muted); font-weight:500; }
        .particle { position:absolute; border-radius:50%; background:rgba(255,255,255,.35); animation:float-particle linear infinite; pointer-events:none; }
        @keyframes float-particle { 0% { transform:translateY(100vh) scale(0); opacity:0; } 10% { opacity:1; } 90% { opacity:1; } 100% { transform:translateY(-60px) scale(1); opacity:0; } }
        @keyframes fade-up { from { opacity:0; transform:translateY(28px); } to { opacity:1; transform:translateY(0); } }
        .scroll-indicator { position:absolute; bottom:2.5rem; left:50%; transform:translateX(-50%); display:flex; flex-direction:column; align-items:center; gap:8px; color:rgba(255,255,255,.5); font-size:.72rem; font-weight:500; letter-spacing:.06em; text-transform:uppercase; animation:fade-up 1s var(--ease-out) .8s both; z-index:2; }
        .scroll-mouse { width:22px; height:36px; border:2px solid rgba(255,255,255,.3); border-radius:11px; display:flex; justify-content:center; padding-top:6px; }
        .scroll-wheel { width:4px; height:8px; background:rgba(255,255,255,.6); border-radius:2px; animation:scroll-down 1.5s ease-in-out infinite; }
        @keyframes scroll-down { 0% { transform:translateY(0); opacity:1; } 100% { transform:translateY(8px); opacity:0; } }

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
        .jenjang-bg { background:var(--off-white); }
        .jenjang-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:1.5rem; margin-top:3rem; }
        .jenjang-card {
            background:white; border-radius:24px; padding:2.25rem 1.75rem;
            border:1px solid rgba(200,77,223,.1);
            box-shadow:0 4px 20px rgba(38,6,50,.06);
            text-align:center; position:relative; overflow:hidden;
            transition:transform .35s var(--ease-out), box-shadow .35s;
            cursor:pointer; text-decoration:none; display:block; color:inherit;
        }
        .jenjang-card::before { content:''; position:absolute; inset:0; background:linear-gradient(160deg,var(--deep),var(--primary)); opacity:0; transition:opacity .35s; }
        .jenjang-card:hover { transform:translateY(-8px); box-shadow:0 20px 60px rgba(38,6,50,.18); }
        .jenjang-card:hover::before { opacity:1; }
        .jenjang-card:hover .jc-num,
        .jenjang-card:hover .jc-icon,
        .jenjang-card:hover .jc-name,
        .jenjang-card:hover .jc-label,
        .jenjang-card:hover .jc-link { color:rgba(255,255,255,.9); }
        .jenjang-card:hover .jc-icon-wrap { background:rgba(255,255,255,.15); }
        .jenjang-card:hover .jc-link { color:white; }
        .jc-num { font-size:3.5rem; font-weight:900; font-family:var(--font-display); color:rgba(200,77,223,.12); position:absolute; top:.75rem; right:1.25rem; line-height:1; letter-spacing:-.04em; transition:color .35s; z-index:1; }
        .jc-content { position:relative; z-index:2; }
        .jc-icon-wrap { width:64px; height:64px; border-radius:18px; background:rgba(200,77,223,.08); display:flex; align-items:center; justify-content:center; margin:0 auto 1.25rem; transition:background .35s; }
        .jc-icon { font-size:1.8rem; transition:color .35s; }
        .jc-name { font-size:1.5rem; font-weight:900; font-family:var(--font-display); color:var(--deep); line-height:1; margin-bottom:.35rem; transition:color .35s; }
        .jc-label { font-size:.85rem; font-weight:500; color:var(--text-muted); margin-bottom:1.5rem; transition:color .35s; }
        .jc-link { font-size:.8rem; font-weight:700; color:var(--primary); display:inline-flex; align-items:center; gap:5px; transition:color .35s; }

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
            .float-card-1 { bottom:8rem; left:1.5rem; }
            .float-card-2 { top:7rem; right:1.5rem; }
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
            /* ── Transparent fixed navbar on mobile ── */
            .lp-nav {
                position: fixed !important;
                top: 0 !important; left: 0 !important; right: 0 !important;
                background: transparent !important;
                padding: .75rem 0 !important;
            }
            .lp-nav.scrolled {
                padding: .75rem 0 !important;
                background: transparent !important;
            }
            .lp-nav.scrolled .nav-inner {
                background: rgba(38,6,50,.72) !important;
                backdrop-filter: blur(16px) !important;
                -webkit-backdrop-filter: blur(16px) !important;
                border-radius: 14px !important;
                padding: .45rem 1rem !important;
                max-width: calc(100% - 2rem) !important;
                box-shadow: 0 4px 24px rgba(0,0,0,.18) !important;
            }
            .lp-nav .nav-inner {
                background: transparent !important;
                backdrop-filter: none !important;
                -webkit-backdrop-filter: none !important;
                border-radius: 0 !important;
                box-shadow: none !important;
            }
            .lp-nav.scrolled .nav-brand-text,
            .lp-nav .nav-brand-text { color: white !important; }
            .lp-nav.scrolled .nav-toggle span,
            .lp-nav .nav-toggle span { background: white !important; }
            /* Hero needs top padding to clear fixed nav */
            .hero-inner { padding: 6.5rem 1.5rem 5rem; }
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
            <li><a href="#program"      class="nav-link-item">Program</a></li>
            <li><a href="#jenjang"      class="nav-link-item">Jenjang</a></li>
            <li><a href="#mengapa-sci"  class="nav-link-item">Mengapa SCI</a></li>
            <li><a href="#tutor"        class="nav-link-item">Tutor</a></li>
            <li><a href="#testimonials" class="nav-link-item">Testimoni</a></li>
            <li><a href="#cabang"       class="nav-link-item">Cabang</a></li>
        </ul>

        <div class="nav-cta">
            <a href="{{ route('login') }}"    class="btn-nav-login">Masuk</a>
            <a href="{{ route('register') }}" class="btn-nav-register">Daftar Sekarang</a>
        </div>

        <button class="nav-toggle" id="navToggle" aria-label="Menu">
            <span></span><span></span><span></span>
        </button>
    </div>
</nav>

{{-- Mobile Menu --}}
<div class="mobile-menu" id="mobileMenu" aria-hidden="true">
    <a href="#program"      onclick="closeMobile()">Program</a>
    <a href="#jenjang"      onclick="closeMobile()">Jenjang</a>
    <a href="#mengapa-sci"  onclick="closeMobile()">Mengapa SCI</a>
    <a href="#tutor"        onclick="closeMobile()">Tutor</a>
    <a href="#testimonials" onclick="closeMobile()">Testimoni</a>
    <a href="#cabang"       onclick="closeMobile()">Cabang</a>
    <div class="mobile-divider"></div>
    <a href="{{ route('login') }}"    onclick="closeMobile()" style="color:rgba(255,255,255,.65);font-size:1.05rem;font-weight:600"><i class="bi bi-box-arrow-in-right" style="font-size:.9rem"></i> Masuk</a>
    <a href="{{ route('register') }}" onclick="closeMobile()" style="background:linear-gradient(135deg,var(--primary-dark),var(--primary));padding:.8rem 2.5rem;border-radius:14px;font-size:1rem;color:white">Daftar Sekarang</a>
</div>

{{-- ─────────────────────────────── HERO ──────────────────────────────────── --}}
<section class="hero" id="home">
    <div class="hero-slides" id="heroSlides">
        @php
            $heroSlides = array_filter([
                $ls('hero.slide_1_url','https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=1920&q=80'),
                $ls('hero.slide_2_url','https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&fit=crop&w=1920&q=80'),
                $ls('hero.slide_3_url','https://images.unsplash.com/photo-1571260899304-425eee4c7efc?auto=format&fit=crop&w=1920&q=80'),
            ]);
        @endphp
        @foreach($heroSlides as $i => $slideUrl)
        <div class="hero-slide {{ $i === 0 ? 'active' : '' }}" style="background-image:url('{{ $slideUrl }}')">
            <div class="hero-slide-overlay"></div>
        </div>
        @endforeach
    </div>

    <div class="hero-inner">
        <div class="hero-badge">
            <div class="hero-badge-dot"><i class="bi bi-stars" style="color:white;font-size:11px"></i></div>
            {{ $ls('hero.badge_text','Bimbel & Kursus Terbaik #1 di Indonesia') }}
        </div>

        <h1 class="hero-title">
            {{ $ls('hero.title_line1','Wujudkan Mimpi,') }}<br><span class="gradient-text">{{ $ls('hero.title_line2','Raih Prestasi!') }}</span>
        </h1>

        <p class="hero-desc">
            {{ $ls('hero.description','Smart Center Indonesia — lembaga bimbingan belajar, kursus, dan les privat berbasis offline & online. Tutor profesional, metode modern, hasil terukur untuk semua jenjang dari TK hingga umum.') }}
        </p>

        <div class="hero-actions">
            <a href="{{ route('register') }}" class="btn-hero-primary">
                <i class="bi bi-rocket-takeoff-fill"></i>
                Daftar Sekarang
            </a>
            <a href="#program" class="btn-hero-secondary">
                <i class="bi bi-grid-3x3-gap-fill"></i>
                Lihat Program
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
                <strong>Dipercaya ribuan siswa & orang tua</strong>
                di seluruh Indonesia ⭐⭐⭐⭐⭐
            </div>
        </div>
    </div>

    <div class="float-card float-card-1">
        <div class="float-card-content">
            <div class="float-icon" style="background:rgba(16,185,129,.12);">🏆</div>
            <div class="float-card-text">
                <div class="fc-val">{{ $ls('hero.float1_title','Nilai Naik!') }}</div>
                <div class="fc-lab">{{ $ls('hero.float1_subtitle','Rata-rata +30 poin · Bulan ini') }}</div>
            </div>
        </div>
    </div>

    <div class="float-card float-card-2">
        <div class="float-card-content">
            <div class="float-icon" style="background:rgba(200,77,223,.12);">👤</div>
            <div class="float-card-text">
                <div class="fc-val">{{ $ls('hero.float2_title','Siswa Baru Daftar') }}</div>
                <div class="fc-lab">{{ $ls('hero.float2_subtitle','Les Privat · Baru saja') }}</div>
            </div>
        </div>
    </div>

    <div class="hero-dots" id="heroDots">
        <button class="hero-dot active" data-slide="0" aria-label="Slide 1"></button>
        <button class="hero-dot"        data-slide="1" aria-label="Slide 2"></button>
        <button class="hero-dot"        data-slide="2" aria-label="Slide 3"></button>
    </div>

</section>

{{-- ──────────────────────────── STATS STRIP ───────────────────────────────── --}}
<section class="stats-strip">
    <div class="stats-strip-inner">
        <div class="stat-item reveal">
            <div class="si-num count-up" data-target="{{ max($stats['students'], 500) }}">0</div>
            <div class="si-label">Siswa Aktif</div>
        </div>
        <div class="stat-item reveal reveal-delay-1">
            <div class="si-num count-up" data-target="{{ max($stats['teachers'], 50) }}">0</div>
            <div class="si-label">Tutor Profesional</div>
        </div>
        <div class="stat-item reveal reveal-delay-2">
            <div class="si-num">{{ $ls('stats.years_exp','14+') }}</div>
            <div class="si-label">Tahun Pengalaman</div>
        </div>
        <div class="stat-item reveal reveal-delay-3">
            <div class="si-num">{{ $ls('stats.satisfaction','98%') }}</div>
            <div class="si-label">Kepuasan Pelanggan</div>
        </div>
    </div>
</section>

{{-- ──────────────────────────── JENJANG PENDIDIKAN ───────────────────────── --}}
<section class="section-pad jenjang-bg" id="jenjang">
    <div class="container-lp">
        <div class="text-center reveal">
            <div class="section-eyebrow"><i class="bi bi-layers-fill"></i> Jenjang Pendidikan</div>
            <h2 class="section-title">Kami Melayani Semua Jenjang</h2>
            <p class="section-subtitle mx-auto">Dari TK hingga umum dengan pendekatan personal yang tepat untuk setiap tahap perkembangan.</p>
        </div>

        <div class="jenjang-grid" id="jenjangGrid">
            <a href="{{ route('register') }}" class="jenjang-card reveal reveal-delay-1">
                <div class="jc-num">1</div>
                <div class="jc-content">
                    <div class="jc-icon-wrap">
                        <span class="jc-icon">🌱</span>
                    </div>
                    <div class="jc-name">TK</div>
                    <div class="jc-label">Taman Kanak-Kanak</div>
                    <div class="jc-link">Lihat Detail <i class="bi bi-arrow-right"></i></div>
                </div>
            </a>

            <a href="{{ route('register') }}" class="jenjang-card reveal reveal-delay-2">
                <div class="jc-num">2</div>
                <div class="jc-content">
                    <div class="jc-icon-wrap">
                        <span class="jc-icon">📚</span>
                    </div>
                    <div class="jc-name">SD</div>
                    <div class="jc-label">Sekolah Dasar</div>
                    <div class="jc-link">Lihat Detail <i class="bi bi-arrow-right"></i></div>
                </div>
            </a>

            <a href="{{ route('register') }}" class="jenjang-card reveal reveal-delay-3">
                <div class="jc-num">3</div>
                <div class="jc-content">
                    <div class="jc-icon-wrap">
                        <span class="jc-icon">🔬</span>
                    </div>
                    <div class="jc-name">SMP</div>
                    <div class="jc-label">Sekolah Menengah Pertama</div>
                    <div class="jc-link">Lihat Detail <i class="bi bi-arrow-right"></i></div>
                </div>
            </a>

            <a href="{{ route('register') }}" class="jenjang-card reveal reveal-delay-4">
                <div class="jc-num">4</div>
                <div class="jc-content">
                    <div class="jc-icon-wrap">
                        <span class="jc-icon">🎓</span>
                    </div>
                    <div class="jc-name">SMA</div>
                    <div class="jc-label">SMA & Karyawan / Umum</div>
                    <div class="jc-link">Lihat Detail <i class="bi bi-arrow-right"></i></div>
                </div>
            </a>
        </div>
        <div class="mobile-carousel-dots" id="jenjang-dots"></div>
    </div>
</section>

{{-- ──────────────────────────── PROGRAM UNGGULAN ─────────────────────────── --}}
<section class="section-pad" id="program">
    <div class="container-lp">
        <div class="text-center reveal">
            <div class="section-eyebrow"><i class="bi bi-award-fill"></i> Program Unggulan</div>
            <h2 class="section-title">Pilih Program Terbaik<br>Sesuai Kebutuhan Anda</h2>
            <p class="section-subtitle mx-auto">Pilih program yang sesuai kebutuhanmu bersama para tutor terbaik kami — klik kartu untuk melihat detail lengkap.</p>
        </div>

        <div class="program-grid" id="programGrid">
            @php $delays = ['reveal-delay-1','reveal-delay-2','reveal-delay-3']; @endphp
            @forelse($dbPrograms as $pi => $prog)
            <a href="{{ route('register') }}" class="program-card reveal {{ $delays[$pi % 3] }}" style="text-decoration:none;color:inherit{{ $prog->is_popular ? ';border-color:rgba(246,175,35,.3)' : ($prog->is_new ? ';border-color:rgba(16,185,129,.25)' : '') }}">
                <div class="pc-badge" style="background:{{ $prog->badge_bg }};color:{{ $prog->badge_color }};">{{ $prog->badge_label }}</div>
                <div class="pc-icon-wrap" style="background:{{ $prog->badge_bg }};">
                    <span style="font-size:1.5rem">{{ $prog->icon_emoji }}</span>
                </div>
                <div class="pc-title">{{ $prog->title }}</div>
                <div class="pc-desc">{{ $prog->description }}</div>
                <div class="pc-link">Daftar Sekarang <i class="bi bi-arrow-right"></i></div>
            </a>
            @empty
            <div class="col-12 text-center py-5 text-muted">Program segera hadir.</div>
            @endforelse
        </div>
        <div class="mobile-carousel-dots" id="program-dots"></div>
    </div>
</section>

{{-- ──────────────────────────── CARA BERGABUNG ───────────────────────────── --}}
<section class="section-pad" id="cara-bergabung" style="background:var(--off-white)">
    <div class="container-lp">
        <div class="how-inner">
            <div>
                <div class="reveal">
                    <div class="section-eyebrow"><i class="bi bi-map"></i> Cara Bergabung</div>
                    <h2 class="section-title">Mulai Belajar di SCI<br>Sangat Mudah</h2>
                    <p class="section-subtitle">Daftar, pilih program, dan mulai belajar — semua bisa dilakukan dalam hitungan menit.</p>
                </div>

                <ul class="steps-list">
                    <li class="step-item reveal reveal-delay-1">
                        <div class="step-num">01</div>
                        <div class="step-body">
                            <div class="step-title">Konsultasi Gratis</div>
                            <div class="step-desc">Hubungi kami via WhatsApp atau kunjungi cabang terdekat. Tim kami siap membantu memilih program terbaik.</div>
                        </div>
                    </li>
                    <li class="step-item reveal reveal-delay-2">
                        <div class="step-num">02</div>
                        <div class="step-body">
                            <div class="step-title">Pilih Program & Jadwal</div>
                            <div class="step-desc">Tentukan program, jenjang, dan jadwal belajar yang sesuai. Tersedia offline, online, maupun home visit.</div>
                        </div>
                    </li>
                    <li class="step-item reveal reveal-delay-3">
                        <div class="step-num">03</div>
                        <div class="step-body">
                            <div class="step-title">Mulai Belajar</div>
                            <div class="step-desc">Siswa langsung belajar bersama tutor profesional pilihan. Materi disesuaikan dengan kebutuhan dan gaya belajar.</div>
                        </div>
                    </li>
                    <li class="step-item reveal reveal-delay-4">
                        <div class="step-num">04</div>
                        <div class="step-body">
                            <div class="step-title">Pantau Perkembangan</div>
                            <div class="step-desc">Laporan bulanan dikirimkan ke orang tua. Progress nilai terpantau, evaluasi rutin, dijamin hasilnya!</div>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="how-visual reveal reveal-delay-2">
                <div class="how-visual-img">
                    <img src="https://images.unsplash.com/photo-1491841573634-28140fc7ced7?auto=format&fit=crop&w=800&q=80" alt="Belajar bersama tutor SCI">
                </div>
                <div class="how-visual-badge">
                    <div class="hvb-icon">🎓</div>
                    <div class="hvb-text">
                        <div class="hvb-val">14+ Tahun</div>
                        <div class="hvb-lab">Melayani Indonesia</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ──────────────────────────── MENGAPA SCI ───────────────────────────────── --}}
<section class="section-pad" id="mengapa-sci">
    <div class="container-lp">
        <div class="text-center reveal">
            <div class="section-eyebrow"><i class="bi bi-shield-fill-check"></i> Mengapa SCI?</div>
            <h2 class="section-title">Keunggulan SCI</h2>
            <p class="section-subtitle mx-auto">Lima pilar yang membuat SCI menjadi pilihan terpercaya jutaan keluarga Indonesia selama 14+ tahun.</p>
        </div>

        <div class="why-grid" id="whyGrid">
            <div class="why-card reveal reveal-delay-1">
                <div class="why-icon-wrap"><i class="bi bi-person-badge-fill"></i></div>
                <div class="why-num">1</div>
                <div class="why-title">Tutor Profesional</div>
                <div class="why-desc">Pengajar ahli bersertifikat resmi dengan pengalaman bertahun-tahun dan rekam jejak hasil nyata.</div>
            </div>

            <div class="why-card reveal reveal-delay-2">
                <div class="why-icon-wrap"><i class="bi bi-house-heart-fill"></i></div>
                <div class="why-num">2</div>
                <div class="why-title">Bisa Home Visit</div>
                <div class="why-desc">Tutor kami siap datang ke rumah Anda kapan saja. Jadwal fleksibel, nyaman, tanpa perlu repot.</div>
            </div>

            <div class="why-card reveal reveal-delay-3">
                <div class="why-icon-wrap"><i class="bi bi-lightbulb-fill"></i></div>
                <div class="why-num">3</div>
                <div class="why-title">Metode Modern</div>
                <div class="why-desc">Sistem belajar interaktif yang disesuaikan gaya belajar masing-masing siswa. Belajar itu menyenangkan!</div>
            </div>

            <div class="why-card reveal reveal-delay-4">
                <div class="why-icon-wrap"><i class="bi bi-graph-up-arrow"></i></div>
                <div class="why-num">4</div>
                <div class="why-title">Hasil Terukur</div>
                <div class="why-desc">Evaluasi rutin, progress terpantau, laporan bulanan. Nilai meningkat signifikan — dijamin atau kami ulang!</div>
            </div>

            <div class="why-card reveal reveal-delay-5">
                <div class="why-icon-wrap"><i class="bi bi-headset"></i></div>
                <div class="why-num">5</div>
                <div class="why-title">Support Penuh</div>
                <div class="why-desc">Bantuan belajar & konsultasi 24/7 via WhatsApp. Kami selalu ada untuk mendukung perjalanan belajar Anda.</div>
            </div>
        </div>
        <div class="mobile-carousel-dots" id="why-dots"></div>
    </div>
</section>

{{-- ──────────────────────────── TESTIMONIALS ──────────────────────────────── --}}
<section class="section-pad testimonials-bg" id="testimonials">
    <div class="testimonials-inner">
        <div class="container-lp">
            <div class="text-center reveal">
                <div class="section-eyebrow" style="background:rgba(255,255,255,.1);border-color:rgba(255,255,255,.15);color:rgba(255,255,255,.9)">
                    <i class="bi bi-chat-heart-fill"></i> Kata Mereka
                </div>
                <h2 class="section-title" style="color:white">Testimoni Siswa</h2>
                <p class="section-subtitle mx-auto" style="color:rgba(255,255,255,.7)">Dengarkan cerita sukses ribuan siswa yang telah mempercayai SCI sebagai mitra belajar mereka.</p>
            </div>
        </div>

        {{-- Infinite carousel — items duplicated for seamless loop --}}
        <div class="carousel-viewport">
            <div class="carousel-track">
                @php
                    $testiSource = $dbTestis->isNotEmpty() ? $dbTestis : collect([
                        (object)['text'=>'"Nilai matematika saya naik dari 60 ke 90 setelah 3 bulan bimbel di SCI! Tutornya sabar dan cara jelasinnya mudah dipahami."','name'=>'Rini Kusumawati','role'=>'Siswa SMA · Surabaya','initial'=>'R','gradient'=>'linear-gradient(135deg,#c84ddf,#68117e)'],
                        (object)['text'=>'"Berkat program intensif SBMPTN di SCI, saya berhasil masuk ITB! Materinya lengkap, soal-soal latihannya mirip ujian asli."','name'=>'Siti Nuraini','role'=>'Mahasiswa ITB · Bandung','initial'=>'S','gradient'=>'linear-gradient(135deg,#10b981,#059669)'],
                        (object)['text'=>'"Kursus komputer di SCI luar biasa! Dalam 3 bulan saya sudah bisa desain grafis dan sekarang sudah dapat klien freelance."','name'=>'Andika Putra','role'=>'Alumni Kursus Komputer · Yogyakarta','initial'=>'A','gradient'=>'linear-gradient(135deg,#6366f1,#4338ca)'],
                    ]);
                @endphp
                {{-- Set 1 --}}
                @foreach($testiSource as $t)
                <div class="testi-card">
                    <div class="testi-stars">
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                    </div>
                    <p class="testi-text">{{ $t->text }}</p>
                    <div class="testi-author">
                        <div class="testi-avatar" style="background:{{ $t->gradient }}">{{ $t->initial }}</div>
                        <div>
                            <div class="testi-name">{{ $t->name }}</div>
                            <div class="testi-role">{{ $t->role }}</div>
                        </div>
                    </div>
                </div>
                @endforeach
                {{-- Set 2 (duplicate for seamless loop) --}}
                @foreach($testiSource as $t)
                <div class="testi-card">
                    <div class="testi-stars">
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                    </div>
                    <p class="testi-text">{{ $t->text }}</p>
                    <div class="testi-author">
                        <div class="testi-avatar" style="background:{{ $t->gradient }}">{{ $t->initial }}</div>
                        <div>
                            <div class="testi-name">{{ $t->name }}</div>
                            <div class="testi-role">{{ $t->role }}</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ──────────────────────────── GALERI KEGIATAN ──────────────────────────── --}}
<section class="section-pad" id="galeri">
    <div class="container-lp">
        <div class="text-center reveal">
            <div class="section-eyebrow"><i class="bi bi-images"></i> Galeri Kegiatan</div>
            <h2 class="section-title">Momen Belajar Bersama SCI</h2>
            <p class="section-subtitle mx-auto">Momen belajar menyenangkan bersama siswa dan tutor terbaik SCI di seluruh Indonesia.</p>
        </div>

        <div class="galeri-grid reveal">
            <div class="galeri-item large">
                <img src="https://images.unsplash.com/photo-1503676260728-1c00da094a0b?auto=format&fit=crop&w=800&q=80" alt="Kegiatan belajar SCI" loading="lazy">
                <div class="galeri-overlay"><span>Sesi Belajar Interaktif</span></div>
            </div>
            <div class="galeri-item">
                <img src="https://images.unsplash.com/photo-1543269865-cbf427effbad?auto=format&fit=crop&w=800&q=80" alt="Diskusi kelompok" loading="lazy">
                <div class="galeri-overlay"><span>Diskusi Kelompok</span></div>
            </div>
            <div class="galeri-item">
                <img src="https://images.unsplash.com/photo-1427504494785-3a9ca7044f45?auto=format&fit=crop&w=800&q=80" alt="Les privat" loading="lazy">
                <div class="galeri-overlay"><span>Les Privat 1 on 1</span></div>
            </div>
            <div class="galeri-item">
                <img src="https://images.unsplash.com/photo-1509869175650-a1d97972541a?auto=format&fit=crop&w=800&q=80" alt="Kelas komputer" loading="lazy">
                <div class="galeri-overlay"><span>Kursus Komputer</span></div>
            </div>
            <div class="galeri-item">
                <img src="https://images.unsplash.com/photo-1434030216411-0b793f4b4173?auto=format&fit=crop&w=800&q=80" alt="Persiapan ujian" loading="lazy">
                <div class="galeri-overlay"><span>Persiapan Ujian</span></div>
            </div>
        </div>
    </div>
</section>

{{-- ──────────────────────────── TUTOR TERBAIK ─────────────────────────────── --}}
<section class="section-pad tutor-bg" id="tutor">
    <div class="container-lp">
        <div class="text-center reveal">
            <div class="section-eyebrow"><i class="bi bi-person-hearts"></i> Tim Tutor</div>
            <h2 class="section-title">Tutor Terbaik Kami</h2>
            <p class="section-subtitle mx-auto">Dilatih secara profesional dan berpengalaman di bidangnya masing-masing untuk memastikan kualitas belajar terbaik.</p>
        </div>
    </div>

    {{-- Infinite tutor carousel — full width, edge-faded --}}
    <div class="carousel-viewport tutor-carousel-viewport" style="margin-top:3rem;">
        <div class="tutor-carousel-track">
            {{-- Set 1 --}}
            @foreach($tutors as $i => $tutor)
            @php
                $grad  = $tutorGrads[$i % count($tutorGrads)];
                $subj  = is_array($tutor->subjects) ? implode(', ', array_slice($tutor->subjects, 0, 2)) : ($tutor->subjects ?? 'Tutor');
                $init  = strtoupper(substr($tutor->name ?? 'T', 0, 1));
                $hasPhoto = !empty($tutor->photo) && file_exists(public_path('storage/'.$tutor->photo));
            @endphp
            <div class="tutor-card">
                <div class="tutor-avatar-wrap" style="background:{{ $grad }}">
                    @if($hasPhoto)
                        <img src="{{ asset('storage/'.$tutor->photo) }}" alt="{{ $tutor->name }}">
                    @else
                        <div class="tutor-avatar-fallback">{{ $init }}</div>
                    @endif
                    <div class="tutor-badge-subject">{{ $subj }}</div>
                </div>
                <div class="tutor-info">
                    <div class="tutor-name">{{ $tutor->name }}</div>
                    <div class="tutor-meta">Tutor Profesional SCI</div>
                    <div class="tutor-stars">
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                    </div>
                </div>
            </div>
            @endforeach
            {{-- Set 2 (duplicate for seamless loop) --}}
            @foreach($tutors as $i => $tutor)
            @php
                $grad  = $tutorGrads[$i % count($tutorGrads)];
                $subj  = is_array($tutor->subjects) ? implode(', ', array_slice($tutor->subjects, 0, 2)) : ($tutor->subjects ?? 'Tutor');
                $init  = strtoupper(substr($tutor->name ?? 'T', 0, 1));
                $hasPhoto = !empty($tutor->photo) && file_exists(public_path('storage/'.$tutor->photo));
            @endphp
            <div class="tutor-card">
                <div class="tutor-avatar-wrap" style="background:{{ $grad }}">
                    @if($hasPhoto)
                        <img src="{{ asset('storage/'.$tutor->photo) }}" alt="{{ $tutor->name }}">
                    @else
                        <div class="tutor-avatar-fallback">{{ $init }}</div>
                    @endif
                    <div class="tutor-badge-subject">{{ $subj }}</div>
                </div>
                <div class="tutor-info">
                    <div class="tutor-name">{{ $tutor->name }}</div>
                    <div class="tutor-meta">Tutor Profesional SCI</div>
                    <div class="tutor-stars">
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ──────────────────────────── CABANG SCI ─────────────────────────────────── --}}
<section class="section-pad" id="cabang">
    <div class="container-lp">
        <div class="text-center reveal">
            <div class="section-eyebrow"><i class="bi bi-geo-alt-fill"></i> Cabang Kami</div>
            <h2 class="section-title">Cabang SCI Indonesia</h2>
            <p class="section-subtitle mx-auto">Temukan cabang SCI terdekat di kota Anda dan mulai perjalanan belajar bersama kami.</p>
        </div>

        @php
            $branches = \App\Models\Branch::take(6)->get();
        @endphp

        @if($branches->count() > 0)
        <div class="cabang-grid">
            @foreach($branches as $i => $branch)
            @php $branchName = $branch->nama ?? $branch->name ?? 'Cabang SCI'; @endphp
            <div class="cabang-card reveal reveal-delay-{{ ($i % 3) + 1 }}">
                <div class="cabang-icon"><i class="bi bi-building-fill"></i></div>
                <div class="cabang-name">{{ $branchName }}</div>
                <div class="cabang-address">{{ $branch->alamat ?? $branch->address ?? 'Indonesia' }}</div>
                <div class="cabang-tag"><i class="bi bi-circle-fill" style="font-size:.45rem"></i> Aktif</div>
                <a href="https://wa.me/628001234567?text={{ urlencode('Halo SCI, saya ingin tanya tentang program di cabang '.$branchName) }}" target="_blank" rel="noopener" class="btn-cabang-wa">
                    <i class="bi bi-whatsapp"></i> Hubungi Cabang
                </a>
            </div>
            @endforeach
        </div>
        @else
        <div class="cabang-grid">
            <div class="cabang-card reveal reveal-delay-1">
                <div class="cabang-icon"><i class="bi bi-building-fill"></i></div>
                <div class="cabang-name">SCI Pusat — Jakarta</div>
                <div class="cabang-address">Jl. Pendidikan No. 1, Jakarta Selatan</div>
                <div class="cabang-tag"><i class="bi bi-circle-fill" style="font-size:.45rem"></i> Aktif</div>
                <a href="https://wa.me/628001234567?text={{ urlencode('Halo SCI Jakarta, saya ingin tanya tentang program bimbel.') }}" target="_blank" rel="noopener" class="btn-cabang-wa"><i class="bi bi-whatsapp"></i> Hubungi Cabang</a>
            </div>
            <div class="cabang-card reveal reveal-delay-2">
                <div class="cabang-icon"><i class="bi bi-building-fill"></i></div>
                <div class="cabang-name">SCI Surabaya</div>
                <div class="cabang-address">Jl. Raya Darmo No. 45, Surabaya</div>
                <div class="cabang-tag"><i class="bi bi-circle-fill" style="font-size:.45rem"></i> Aktif</div>
                <a href="https://wa.me/628001234567?text={{ urlencode('Halo SCI Surabaya, saya ingin tanya tentang program bimbel.') }}" target="_blank" rel="noopener" class="btn-cabang-wa"><i class="bi bi-whatsapp"></i> Hubungi Cabang</a>
            </div>
            <div class="cabang-card reveal reveal-delay-3">
                <div class="cabang-icon"><i class="bi bi-building-fill"></i></div>
                <div class="cabang-name">SCI Bandung</div>
                <div class="cabang-address">Jl. Asia Afrika No. 22, Bandung</div>
                <div class="cabang-tag"><i class="bi bi-circle-fill" style="font-size:.45rem"></i> Aktif</div>
                <a href="https://wa.me/628001234567?text={{ urlencode('Halo SCI Bandung, saya ingin tanya tentang program bimbel.') }}" target="_blank" rel="noopener" class="btn-cabang-wa"><i class="bi bi-whatsapp"></i> Hubungi Cabang</a>
            </div>
            <div class="cabang-card reveal reveal-delay-1">
                <div class="cabang-icon"><i class="bi bi-building-fill"></i></div>
                <div class="cabang-name">SCI Yogyakarta</div>
                <div class="cabang-address">Jl. Malioboro No. 88, Yogyakarta</div>
                <div class="cabang-tag"><i class="bi bi-circle-fill" style="font-size:.45rem"></i> Aktif</div>
                <a href="https://wa.me/628001234567?text={{ urlencode('Halo SCI Yogyakarta, saya ingin tanya tentang program bimbel.') }}" target="_blank" rel="noopener" class="btn-cabang-wa"><i class="bi bi-whatsapp"></i> Hubungi Cabang</a>
            </div>
            <div class="cabang-card reveal reveal-delay-2">
                <div class="cabang-icon"><i class="bi bi-building-fill"></i></div>
                <div class="cabang-name">SCI Medan</div>
                <div class="cabang-address">Jl. Gatot Subroto No. 12, Medan</div>
                <div class="cabang-tag"><i class="bi bi-circle-fill" style="font-size:.45rem"></i> Aktif</div>
                <a href="https://wa.me/628001234567?text={{ urlencode('Halo SCI Medan, saya ingin tanya tentang program bimbel.') }}" target="_blank" rel="noopener" class="btn-cabang-wa"><i class="bi bi-whatsapp"></i> Hubungi Cabang</a>
            </div>
            <div class="cabang-card reveal reveal-delay-3">
                <div class="cabang-icon"><i class="bi bi-building-fill"></i></div>
                <div class="cabang-name">SCI Makassar</div>
                <div class="cabang-address">Jl. Penghibur No. 5, Makassar</div>
                <div class="cabang-tag"><i class="bi bi-circle-fill" style="font-size:.45rem"></i> Aktif</div>
                <a href="https://wa.me/628001234567?text={{ urlencode('Halo SCI Makassar, saya ingin tanya tentang program bimbel.') }}" target="_blank" rel="noopener" class="btn-cabang-wa"><i class="bi bi-whatsapp"></i> Hubungi Cabang</a>
            </div>
        </div>
        @endif
    </div>
</section>

{{-- ──────────────────────────── CTA ───────────────────────────────────────── --}}
<section class="cta-section">
    <div class="container-lp">
        <div class="cta-box reveal">
            <div class="cta-content">
                <div class="cta-eyebrow"><i class="bi bi-mortarboard-fill"></i> {{ $ls('cta.eyebrow','Mulai Sekarang') }}</div>
                <h2 class="cta-title">{{ $ls('cta.title','Wujudkan Mimpi Bersama SCI!') }}</h2>
                <p class="cta-desc">{{ $ls('cta.description','Bergabunglah bersama ribuan siswa yang telah meraih prestasi bersama Smart Center Indonesia. Konsultasi gratis, daftar mudah!') }}</p>
                <div class="cta-btns">
                    <a href="{{ route('register') }}" class="btn-cta-primary">
                        <i class="bi bi-person-plus-fill"></i>
                        Daftar Sekarang
                    </a>
                    <a href="{{ route('login') }}" class="btn-cta-secondary">
                        <i class="bi bi-box-arrow-in-right"></i>
                        Masuk ke Portal
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ──────────────────────────── FLOATING BUTTONS ──────────────────────────── --}}
<a href="https://wa.me/{{ $ls('footer.wa_number','628001234567') }}?text={{ urlencode('Halo Smart Center Indonesia! Saya ingin konsultasi tentang program bimbel/kursus. Bisa bantu?') }}"
   class="wa-float" target="_blank" rel="noopener" aria-label="Hubungi via WhatsApp">
    <i class="bi bi-whatsapp"></i>
    <span class="wa-float-label">Konsultasi Gratis 💬</span>
</a>

<button class="scroll-top" id="scrollTopBtn" aria-label="Kembali ke atas" onclick="window.scrollTo({top:0,behavior:'smooth'})">
    <i class="bi bi-arrow-up"></i>
</button>

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
                <p class="footer-brand-desc">{{ $ls('footer.brand_desc','Lembaga bimbingan belajar, kursus, dan les privat terbaik di Indonesia. Berkomitmen menjadi lembaga pendidikan nomor 1 di Indonesia. "Wujudkan mimpi, raih prestasi!"') }}</p>
                <div class="footer-social">
                    <a href="{{ $ls('footer.instagram','#') }}"><i class="bi bi-instagram"></i></a>
                    <a href="{{ $ls('footer.facebook','#') }}"><i class="bi bi-facebook"></i></a>
                    <a href="{{ $ls('footer.youtube','#') }}"><i class="bi bi-youtube"></i></a>
                    <a href="https://wa.me/{{ $ls('footer.wa_number','628001234567') }}"><i class="bi bi-whatsapp"></i></a>
                </div>
            </div>

            <div>
                <div class="footer-col-title">Program</div>
                <ul class="footer-links">
                    <li><a href="#program">Bimbel Mata Pelajaran</a></li>
                    <li><a href="#program">Persiapan Ujian</a></li>
                    <li><a href="#program">Kursus Bahasa</a></li>
                    <li><a href="#program">Kursus Komputer</a></li>
                    <li><a href="#program">Kursus Akuntansi</a></li>
                </ul>
            </div>

            <div>
                <div class="footer-col-title">Jenjang</div>
                <ul class="footer-links">
                    <li><a href="#jenjang">TK</a></li>
                    <li><a href="#jenjang">SD</a></li>
                    <li><a href="#jenjang">SMP</a></li>
                    <li><a href="#jenjang">SMA / Umum</a></li>
                </ul>
            </div>

            <div>
                <div class="footer-col-title">Perusahaan</div>
                <ul class="footer-links">
                    <li><a href="#">Tentang SCI</a></li>
                    <li><a href="#cabang">Lokasi Cabang</a></li>
                    <li><a href="#tutor">Tim Tutor</a></li>
                    <li><a href="#">Kontak Kami</a></li>
                    <li><a href="{{ route('login') }}">Portal Siswa</a></li>
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
/* ── Navbar scroll + pill ── */
const navbar = document.getElementById('navbar');
window.addEventListener('scroll', () => {
    navbar.classList.toggle('scrolled', window.scrollY > 60);
}, { passive: true });

/* ── Active nav link tracking ── */
const sections = document.querySelectorAll('section[id]');
const navLinks = document.querySelectorAll('.nav-link-item');
const navObs = new IntersectionObserver((entries) => {
    entries.forEach(e => {
        if (e.isIntersecting) {
            navLinks.forEach(l => l.classList.remove('nav-active'));
            const active = document.querySelector(`.nav-link-item[href="#${e.target.id}"]`);
            if (active) active.classList.add('nav-active');
        }
    });
}, { threshold: 0.35, rootMargin: '-60px 0px -40% 0px' });
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
    const slides = document.querySelectorAll('.hero-slide');
    const dots   = document.querySelectorAll('.hero-dot');
    let current  = 0;
    let timer    = null;
    function goTo(idx) {
        slides[current].classList.remove('active');
        dots[current].classList.remove('active');
        current = (idx + slides.length) % slides.length;
        slides[current].classList.add('active');
        dots[current].classList.add('active');
    }
    function startAuto() { timer = setInterval(() => goTo(current + 1), 6000); }
    dots.forEach(dot => {
        dot.addEventListener('click', () => {
            clearInterval(timer);
            goTo(parseInt(dot.dataset.slide));
            startAuto();
        });
    });
    startAuto();
})();

/* ── Particles ── */
(function createParticles() {
    const container = document.querySelector('.hero');
    if (!container) return;
    const wrap = document.createElement('div');
    wrap.style.cssText = 'position:absolute;inset:0;overflow:hidden;pointer-events:none;z-index:1;';
    for (let i = 0; i < 18; i++) {
        const p = document.createElement('div');
        p.className = 'particle';
        const size = Math.random() * 3 + 2;
        p.style.cssText = `width:${size}px;height:${size}px;left:${Math.random()*100}%;animation-duration:${Math.random()*15+10}s;animation-delay:${Math.random()*10}s;opacity:${Math.random()*.4+.15};`;
        wrap.appendChild(p);
    }
    container.appendChild(wrap);
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
                grid.scrollTo({ left: cards[i].offsetLeft - grid.offsetLeft, behavior: 'smooth' });
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
initMobileCarousel('whyGrid',     'why-dots');

/* ── Respect reduced-motion ── */
if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    document.querySelectorAll('.carousel-track, .tutor-carousel-track').forEach(el => {
        el.style.animationDuration = '0s';
        el.style.animationPlayState = 'paused';
    });
}
</script>
</body>
</html>
