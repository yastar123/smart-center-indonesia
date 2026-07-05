@php
    $cityName = $city; // passed from controller

    /* ── features, subjects, metodeImages, heroBg, ctaEyebrow, ctaTitle, ctaDesc ──
       All passed from BranchLandingController; fallbacks already computed there.    */

    /* ── Metode belajar — combine controller data ── */
    $metodes = [
        ['type'=>'HOME VISIT','icon'=>'🏠','title'=>'Les Privat ke Rumah',      'desc'=>'Tutor kami datang langsung ke rumah Anda di seluruh area '.$cityName.' dan sekitarnya. Nyaman, privat, dan efisien.','price'=>$prices['homevisi'],'img'=>$metodeImages['homevisi']],
        ['type'=>'ONLINE',    'icon'=>'🖥️','title'=>'Les Online via Zoom/Meet', 'desc'=>'Belajar dari rumah via Zoom, Google Meet, atau platform pilihan Anda. Rekaman sesi tersedia untuk review ulang.','price'=>$prices['online'],'img'=>$metodeImages['online']],
        ['type'=>'OFFLINE',   'icon'=>'🏫','title'=>'Belajar di Kantor SCI',    'desc'=>'Datang ke kantor SCI '.$cityName.' dan nikmati fasilitas belajar modern, AC, WiFi cepat, dan perpustakaan materi eksklusif.','price'=>$prices['offline'],'img'=>$metodeImages['offline']],
    ];

    /* ── Testimonials ── */
    $testiData = $testimonials->take(6);

    /* ── Packages / Pricing (priority: branch custom cards > DB packages > defaults) ── */
    $hasBranchPricingCards = !empty($branchPricingCards);
    $hasDbPackages = !$hasBranchPricingCards && $packages->isNotEmpty();
    $defaultPricing = [
        ['name'=>'Paket Reguler', 'desc'=>'Cocok untuk pemula dan maintenance nilai.','price'=>'Rp 50.000','unit'=>'/sesi','sessions'=>'1× per minggu (4 sesi/bln)','fitur'=>['Durasi 90 menit/sesi','Pilihan tutor sesuai kebutuhan','Laporan perkembangan bulanan','Bisa home visit/online/offline'],'unggulan'=>false],
        ['name'=>'Paket Intensif','desc'=>'Cocok untuk persiapan ujian dan akselerasi nilai.','price'=>'Rp 45.000','unit'=>'/sesi','sessions'=>'2–3× per minggu (8–12 sesi/bln)','fitur'=>['Durasi 90 menit/sesi','Materi soal ujian eksklusif','Laporan perkembangan mingguan','Try-out bulanan gratis','Konsultasi guru kapan saja'],'unggulan'=>true],
        ['name'=>'Paket Premium', 'desc'=>'Solusi lengkap untuk target nilai terbaik.','price'=>'Hubungi Kami','unit'=>'','sessions'=>'Jadwal & sesi fleksibel','fitur'=>['Sesi tak terbatas per bulan','Tutor spesialis bidang studi','Monitoring nilai real-time','Garansi nilai naik tertulis','Materi custom sesuai kurikulum'],'unggulan'=>false],
    ];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Les Privat Terbaik di {{ $cityName }} — Smart Center Indonesia. Tutor bersertifikat, home visit, online & offline. Garansi nilai naik!">
    <title>Les Privat Terbaik di {{ $cityName }} | Smart Center Indonesia</title>
    <meta property="og:title" content="Les Privat Terbaik di {{ $cityName }} | SCI">
    <meta property="og:description" content="SCI hadir di {{ $cityName }} dengan {{ $tutorCount }}+ tutor bersertifikat. Home visit, online & offline untuk semua jenjang.">
    <meta property="og:type" content="website">
    <meta name="theme-color" content="#260632">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
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
            --success:      #10b981;
            --white:        #ffffff;
            --off-white:    #fdf8ff;
            --text:         #1e0828;
            --text-muted:   #6b5878;
            --border:       rgba(200,77,223,.12);
            --font-sans:    'Inter', system-ui, sans-serif;
            --font-display: 'Plus Jakarta Sans', 'Inter', sans-serif;
            --ease-out:     cubic-bezier(.22,1,.36,1);
        }
        html { scroll-behavior:smooth; }
        body { font-family:var(--font-sans); color:var(--text); background:var(--white); overflow-x:hidden; }
        h1,h2,h3,h4,h5,h6 { font-family:var(--font-display); letter-spacing:-.025em; }
        .container-bl { max-width:1180px; margin:0 auto; padding:0 2rem; }

        /* ─── PROMO TICKER ──────────────────────────────────────── */
        .promo-ticker {
            background:linear-gradient(90deg,var(--gold-dark,#e09000) 0%,var(--gold) 50%,var(--gold-dark,#e09000) 100%);
            padding:.45rem 0; overflow:hidden; position:relative; z-index:1001;
        }
        .ticker-track {
            display:flex; gap:2.5rem; width:max-content;
            animation:ticker-scroll 28s linear infinite;
            white-space:nowrap;
        }
        .ticker-track:hover { animation-play-state:paused; }
        @keyframes ticker-scroll {
            0%   { transform:translateX(0); }
            100% { transform:translateX(-50%); }
        }
        .ticker-item { font-size:.78rem; font-weight:700; color:var(--deep); display:flex; align-items:center; gap:.4rem; }
        .ticker-sep  { color:rgba(38,6,50,.35); font-weight:400; }

        /* ─── NAVBAR ────────────────────────────────────────────── */
        .bl-nav {
            position:sticky; top:0; z-index:1000; background:white;
            border-bottom:1px solid rgba(0,0,0,.07);
            box-shadow:0 2px 16px rgba(0,0,0,.06);
        }
        .bl-nav-inner {
            display:flex; align-items:center; justify-content:space-between;
            max-width:1180px; margin:0 auto; padding:0 2rem; height:62px;
        }
        .bl-brand { display:flex; align-items:center; gap:10px; text-decoration:none; }
        .bl-brand-icon {
            width:38px; height:38px; border-radius:10px; flex-shrink:0;
            background:linear-gradient(135deg,var(--primary-dark),var(--primary));
            display:flex; align-items:center; justify-content:center;
            font-size:.75rem; font-weight:900; color:white; font-family:var(--font-display);
            box-shadow:0 4px 12px rgba(200,77,223,.35);
        }
        .bl-brand-text { font-family:var(--font-display); font-weight:800; font-size:.96rem; color:var(--deep); line-height:1.15; }
        .bl-brand-text small { display:block; font-size:.6rem; font-weight:500; color:var(--primary); letter-spacing:.01em; }
        .bl-nav-links { display:flex; align-items:center; gap:.05rem; list-style:none; }
        .bl-nav-links a { color:#374151; text-decoration:none; font-size:.85rem; font-weight:500; padding:.4rem .85rem; border-radius:8px; transition:color .2s,background .2s; }
        .bl-nav-links a:hover { color:var(--primary-dark); background:rgba(200,77,223,.07); }
        .bl-nav-links a.active { color:var(--primary-dark); font-weight:600; }
        .btn-bl-cta { padding:.48rem 1.25rem; border-radius:10px; font-size:.85rem; font-weight:700; color:white; background:linear-gradient(135deg,var(--primary-dark),var(--primary)); text-decoration:none; transition:transform .2s,box-shadow .2s; box-shadow:0 4px 14px rgba(200,77,223,.35); white-space:nowrap; }
        .btn-bl-cta:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(200,77,223,.5); color:white; }
        .bl-nav-toggle { display:none; flex-direction:column; gap:5px; cursor:pointer; padding:6px; background:none; border:none; }
        .bl-nav-toggle span { display:block; width:22px; height:2px; background:var(--deep); border-radius:2px; transition:.3s; }
        .bl-nav-toggle.open span:nth-child(1) { transform:translateY(7px) rotate(45deg); }
        .bl-nav-toggle.open span:nth-child(2) { opacity:0; }
        .bl-nav-toggle.open span:nth-child(3) { transform:translateY(-7px) rotate(-45deg); }
        .bl-mobile-menu {
            display:flex; position:fixed; inset:0; z-index:999;
            background:rgba(38,6,50,.96); backdrop-filter:blur(20px);
            flex-direction:column; align-items:center; justify-content:center; gap:1.5rem;
            opacity:0; transform:scale(.96); visibility:hidden; pointer-events:none;
            transition:opacity .35s var(--ease-out),transform .35s var(--ease-out),visibility .35s;
        }
        .bl-mobile-menu.open { opacity:1; transform:scale(1); visibility:visible; pointer-events:auto; }
        .bl-mobile-menu a { color:rgba(255,255,255,.85); text-decoration:none; font-size:1.3rem; font-family:var(--font-display); font-weight:700; }
        .bl-mobile-close { position:absolute; top:1.5rem; right:1.5rem; background:rgba(255,255,255,.1); border:1px solid rgba(255,255,255,.12); color:white; width:42px; height:42px; border-radius:50%; font-size:1.1rem; cursor:pointer; transition:background .2s; display:flex; align-items:center; justify-content:center; }
        .bl-mobile-close:hover { background:rgba(255,255,255,.2); }

        /* ─── HERO ──────────────────────────────────────────────── */
        .bl-hero {
            background:linear-gradient(160deg,#1a0228 0%,var(--mid) 45%,#6d1a7e 100%);
            position:relative; overflow:hidden; min-height:88vh; display:flex; align-items:center;
        }
        .bl-hero::before {
            content:''; position:absolute; inset:0;
            background-image:url('{{ $heroBg }}');
            background-size:cover; background-position:center;
            opacity:.15; pointer-events:none;
        }
        .bl-hero::after {
            content:''; position:absolute; inset:0;
            background:radial-gradient(circle at 80% 50%,rgba(200,77,223,.12) 0%,transparent 60%);
            pointer-events:none;
        }
        .bl-hero-inner {
            position:relative; z-index:1; width:100%; padding:7rem 0 4rem;
        }
        .bl-hero-grid {
            display:grid; grid-template-columns:1fr 420px; gap:3.5rem; align-items:center;
        }
        .bl-breadcrumb { display:flex; align-items:center; gap:.4rem; font-size:.75rem; color:rgba(255,255,255,.5); margin-bottom:1.25rem; flex-wrap:wrap; }
        .bl-breadcrumb a { color:rgba(255,255,255,.5); text-decoration:none; transition:color .2s; }
        .bl-breadcrumb a:hover { color:rgba(255,255,255,.85); }
        .bl-breadcrumb span { color:rgba(255,255,255,.3); }
        .bl-hero-badge {
            display:inline-flex; align-items:center; gap:6px;
            background:rgba(246,175,35,.15); border:1px solid rgba(246,175,35,.3);
            border-radius:50px; padding:5px 14px; font-size:.73rem; font-weight:700;
            color:var(--gold); margin-bottom:1.25rem; letter-spacing:.04em;
        }
        .bl-hero-title { font-size:clamp(2.4rem,4.5vw,3.6rem); font-weight:900; color:white; line-height:1.1; margin-bottom:1.25rem; }
        .bl-hero-title em { font-style:italic; color:var(--gold); }
        .bl-hero-desc { font-size:1rem; color:rgba(255,255,255,.72); line-height:1.75; max-width:480px; margin-bottom:2rem; }
        .bl-hero-stats { display:flex; gap:1rem; flex-wrap:wrap; }
        .bl-stat-chip {
            display:flex; align-items:center; gap:.5rem;
            background:rgba(255,255,255,.1); backdrop-filter:blur(8px);
            border:1px solid rgba(255,255,255,.15); border-radius:12px;
            padding:.55rem 1rem; font-size:.78rem; color:white;
        }
        .bl-stat-chip-icon { font-size:1rem; }
        .bl-stat-chip-val { font-weight:800; font-family:var(--font-display); }
        .bl-stat-chip-lab { color:rgba(255,255,255,.55); font-size:.7rem; display:block; line-height:1.2; }

        /* ── Hero Form Card ── */
        .bl-form-card {
            background:white; border-radius:22px; padding:2rem;
            box-shadow:0 32px 80px rgba(0,0,0,.35), 0 0 0 1px rgba(200,77,223,.08);
        }
        .bl-form-title { font-size:1.15rem; font-weight:800; color:var(--deep); font-family:var(--font-display); margin-bottom:.3rem; display:flex; align-items:center; gap:.4rem; }
        .bl-form-sub { font-size:.8rem; color:var(--text-muted); margin-bottom:1.5rem; }
        .bl-form-row { display:grid; grid-template-columns:1fr 1fr; gap:.85rem; margin-bottom:.85rem; }
        .bl-field { display:flex; flex-direction:column; gap:.35rem; margin-bottom:.85rem; }
        .bl-field:last-of-type { margin-bottom:0; }
        .bl-label { font-size:.67rem; font-weight:700; color:var(--text-muted); text-transform:uppercase; letter-spacing:.07em; }
        .bl-input, .bl-select {
            border:1.5px solid rgba(200,77,223,.15); border-radius:10px;
            padding:.65rem .9rem; font-size:.875rem; font-family:var(--font-sans);
            color:var(--text); outline:none; transition:border-color .2s, box-shadow .2s;
            background:white; width:100%;
        }
        .bl-input:focus, .bl-select:focus { border-color:var(--primary); box-shadow:0 0 0 3px rgba(200,77,223,.1); }
        .bl-input::placeholder { color:#aaa; }
        .btn-bl-form {
            display:flex; align-items:center; justify-content:center; gap:8px;
            width:100%; padding:.9rem 1.5rem; border-radius:12px;
            font-size:.95rem; font-weight:800; color:white;
            background:linear-gradient(135deg,var(--primary-dark),var(--primary));
            border:none; cursor:pointer; margin-top:1.1rem;
            transition:transform .25s,box-shadow .25s;
            box-shadow:0 6px 20px rgba(200,77,223,.4);
        }
        .btn-bl-form:hover { transform:translateY(-2px); box-shadow:0 10px 28px rgba(200,77,223,.55); }
        .bl-form-note { text-align:center; font-size:.72rem; color:var(--text-muted); margin-top:.75rem; display:flex; align-items:center; justify-content:center; gap:.3rem; }

        /* ─── SECTION COMMONS ───────────────────────────────────── */
        .bl-section { padding:5.5rem 0; }
        .bl-section-eyebrow { display:flex; align-items:center; gap:.4rem; font-size:.7rem; font-weight:700; color:var(--primary-dark); text-transform:uppercase; letter-spacing:.1em; margin-bottom:.85rem; }
        .bl-section-eyebrow::before { content:''; display:block; width:24px; height:2px; background:var(--primary); border-radius:2px; }
        .bl-section-title { font-size:clamp(1.85rem,3vw,2.7rem); font-weight:900; color:var(--deep); line-height:1.2; margin-bottom:1rem; }
        .bl-section-title em { font-style:italic; color:var(--primary); }
        .bl-section-sub { font-size:1rem; color:var(--text-muted); line-height:1.7; max-width:580px; }

        /* ─── DIPERCAYA (FEATURES) ─────────────────────────────── */
        .bl-features-section { background:#fdf6ff; }
        .bl-features-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:1.25rem; margin-top:3rem; }
        .bl-feat-card {
            background:white; border-radius:20px; padding:1.75rem 1.5rem;
            border:1px solid rgba(200,77,223,.1); box-shadow:0 4px 18px rgba(38,6,50,.04);
            transition:transform .3s var(--ease-out),box-shadow .3s;
        }
        .bl-feat-card:hover { transform:translateY(-5px); box-shadow:0 16px 45px rgba(38,6,50,.1); }
        .bl-feat-num { font-size:2.5rem; font-weight:900; color:rgba(200,77,223,.12); font-family:var(--font-display); line-height:1; margin-bottom:.75rem; }
        .bl-feat-icon { font-size:1.8rem; margin-bottom:.75rem; display:block; }
        .bl-feat-title { font-size:.97rem; font-weight:800; color:var(--deep); font-family:var(--font-display); margin-bottom:.55rem; }
        .bl-feat-desc { font-size:.835rem; color:var(--text-muted); line-height:1.68; }

        /* ─── SUBJECTS ──────────────────────────────────────────── */
        .bl-subjects-section { background:white; }
        .bl-subjects-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:1rem; margin-top:3rem; }
        .bl-subj-card {
            background:#fdf8ff; border:1.5px solid rgba(200,77,223,.1); border-radius:18px;
            padding:1.5rem 1.25rem; transition:transform .3s var(--ease-out),box-shadow .3s,border-color .3s;
        }
        .bl-subj-card:hover { transform:translateY(-4px); box-shadow:0 14px 40px rgba(38,6,50,.09); border-color:rgba(200,77,223,.3); }
        .bl-subj-icon { font-size:2rem; margin-bottom:.75rem; display:block; }
        .bl-subj-name { font-size:.95rem; font-weight:800; color:var(--deep); font-family:var(--font-display); margin-bottom:.4rem; }
        .bl-subj-desc { font-size:.78rem; color:var(--text-muted); line-height:1.6; margin-bottom:.75rem; }
        .bl-badge { display:inline-flex; align-items:center; gap:3px; font-size:.67rem; font-weight:700; padding:3px 10px; border-radius:50px; }
        .bl-badge.popular { background:rgba(200,77,223,.1); color:var(--primary-dark); }
        .bl-badge.popular::before { content:'⚡'; }
        .bl-badge.hot { background:rgba(239,68,68,.08); color:#dc2626; }
        .bl-badge.hot::before { content:'🔥'; }
        .bl-badge.level { background:rgba(59,130,246,.08); color:#1d4ed8; }
        .bl-badge.general { background:rgba(16,185,129,.08); color:#047857; }

        /* ─── METODE BELAJAR ────────────────────────────────────── */
        .bl-metode-section { background:#f5eeff; }
        .bl-metode-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:1.5rem; margin-top:3rem; }
        .bl-metode-card {
            background:white; border-radius:20px; overflow:hidden;
            border:1px solid rgba(200,77,223,.1); box-shadow:0 4px 18px rgba(38,6,50,.05);
            transition:transform .35s var(--ease-out),box-shadow .35s;
        }
        .bl-metode-card:hover { transform:translateY(-6px); box-shadow:0 20px 55px rgba(38,6,50,.13); }
        .bl-metode-img { height:210px; position:relative; overflow:hidden; }
        .bl-metode-img img { width:100%; height:100%; object-fit:cover; transition:transform .5s var(--ease-out); }
        .bl-metode-card:hover .bl-metode-img img { transform:scale(1.06); }
        .bl-metode-type-badge {
            position:absolute; top:.75rem; left:.75rem;
            background:var(--primary); color:white;
            font-size:.65rem; font-weight:700; letter-spacing:.06em;
            padding:3px 10px; border-radius:50px; display:flex; align-items:center; gap:4px;
        }
        .bl-metode-body { padding:1.5rem; }
        .bl-metode-title { font-size:1.02rem; font-weight:800; color:var(--deep); font-family:var(--font-display); margin-bottom:.5rem; }
        .bl-metode-desc { font-size:.82rem; color:var(--text-muted); line-height:1.65; margin-bottom:1rem; }
        .bl-metode-price { font-size:.82rem; color:var(--text-muted); }
        .bl-metode-price strong { font-size:1.15rem; font-weight:800; color:var(--primary-dark); font-family:var(--font-display); }
        .bl-metode-price span { font-size:.75rem; color:var(--text-muted); }

        /* ─── LOKASI ─────────────────────────────────────────────── */
        .bl-lokasi-section { background:white; }
        .bl-lokasi-grid { display:grid; grid-template-columns:1fr 1fr; gap:3rem; align-items:start; margin-top:3rem; }
        .bl-contact-list { display:flex; flex-direction:column; gap:1.25rem; }
        .bl-contact-item { display:flex; align-items:flex-start; gap:1rem; }
        .bl-contact-icon {
            width:40px; height:40px; flex-shrink:0; border-radius:12px;
            background:rgba(200,77,223,.1); display:flex; align-items:center; justify-content:center;
            font-size:1rem; color:var(--primary);
        }
        .bl-contact-label { font-size:.7rem; font-weight:700; color:var(--text-muted); text-transform:uppercase; letter-spacing:.06em; margin-bottom:.2rem; }
        .bl-contact-val { font-size:.9rem; font-weight:600; color:var(--deep); line-height:1.55; }
        .bl-area-title { font-size:.72rem; font-weight:700; color:var(--primary-dark); text-transform:uppercase; letter-spacing:.08em; margin-bottom:.85rem; display:flex; align-items:center; gap:.4rem; }
        .bl-area-title::before { content:''; display:block; width:18px; height:2px; background:var(--primary); border-radius:2px; }
        .bl-area-chips { display:flex; gap:.6rem; flex-wrap:wrap; }
        .bl-area-chip { display:inline-flex; align-items:center; gap:.3rem; padding:.4rem 1rem; border-radius:8px; background:#fdf6ff; border:1.5px solid rgba(200,77,223,.15); font-size:.8rem; font-weight:600; color:var(--deep); }
        .bl-map-placeholder {
            background:linear-gradient(135deg,#fdf6ff 0%,#f5eeff 100%);
            border-radius:20px; height:280px; display:flex; align-items:center; justify-content:center;
            border:1.5px solid rgba(200,77,223,.15); flex-direction:column; gap:.75rem;
        }
        .bl-map-icon { font-size:3rem; }
        .bl-map-text { font-size:.85rem; color:var(--text-muted); text-align:center; }

        /* ─── HARGA & PAKET ─────────────────────────────────────── */
        .bl-harga-section { background:#fdf6ff; }
        .bl-harga-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:1.5rem; margin-top:3rem; }
        .bl-pkg-card {
            border-radius:22px; padding:2rem; position:relative; overflow:hidden;
            border:1.5px solid rgba(200,77,223,.15);
            transition:transform .3s var(--ease-out),box-shadow .3s;
        }
        .bl-pkg-card:hover { transform:translateY(-6px); box-shadow:0 20px 55px rgba(38,6,50,.13); }
        .bl-pkg-card.unggulan { border-color:var(--primary); }
        .bl-pkg-badge-best {
            position:absolute; top:-1px; left:50%; transform:translateX(-50%);
            background:linear-gradient(90deg,var(--primary-dark),var(--primary));
            color:white; font-size:.67rem; font-weight:800; padding:.3rem 1.2rem;
            border-radius:0 0 10px 10px; white-space:nowrap; letter-spacing:.04em;
        }
        .bl-pkg-name { font-size:1rem; font-weight:800; color:var(--deep); font-family:var(--font-display); margin-bottom:.35rem; }
        .bl-pkg-desc { font-size:.8rem; color:var(--text-muted); margin-bottom:1.25rem; line-height:1.6; }
        .bl-pkg-price { font-size:1.8rem; font-weight:900; color:var(--primary-dark); font-family:var(--font-display); line-height:1; margin-bottom:.2rem; }
        .bl-pkg-price span { font-size:.78rem; font-weight:500; color:var(--text-muted); }
        .bl-pkg-sessions { font-size:.78rem; color:var(--text-muted); margin-bottom:1.5rem; }
        .bl-pkg-fitur { list-style:none; display:flex; flex-direction:column; gap:.6rem; margin-bottom:1.75rem; }
        .bl-pkg-fitur li { display:flex; align-items:flex-start; gap:.55rem; font-size:.83rem; color:#444; }
        .bl-pkg-fitur li::before { content:'✓'; color:var(--success); font-weight:700; flex-shrink:0; margin-top:1px; }
        .btn-bl-pkg {
            display:flex; align-items:center; justify-content:center; gap:7px;
            width:100%; padding:.8rem 1.5rem; border-radius:12px;
            font-size:.88rem; font-weight:700; text-decoration:none;
            border:none; cursor:pointer; transition:transform .2s,box-shadow .2s;
        }
        .btn-bl-pkg.default { background:rgba(200,77,223,.08); color:var(--primary-dark); border:1.5px solid rgba(200,77,223,.2); }
        .btn-bl-pkg.default:hover { background:rgba(200,77,223,.14); }
        .btn-bl-pkg.primary { background:linear-gradient(135deg,var(--primary-dark),var(--primary)); color:white; box-shadow:0 6px 20px rgba(200,77,223,.4); }
        .btn-bl-pkg.primary:hover { transform:translateY(-2px); box-shadow:0 10px 28px rgba(200,77,223,.55); color:white; }

        /* ─── TESTIMONI CAROUSEL ─────────────────────────────────── */
        .bl-testi-section { background:#f5eeff; }
        .bl-testi-vp { overflow:hidden; position:relative; margin-top:3rem; }
        .bl-testi-vp::before, .bl-testi-vp::after {
            content:''; position:absolute; top:0; bottom:0; width:100px; z-index:2; pointer-events:none;
        }
        .bl-testi-vp::before { left:0;  background:linear-gradient(to right,#f5eeff 0%,transparent 100%); }
        .bl-testi-vp::after  { right:0; background:linear-gradient(to left, #f5eeff 0%,transparent 100%); }
        .bl-testi-track {
            display:flex; gap:1.25rem; width:max-content;
            animation:marquee-scroll 40s linear infinite;
        }
        .bl-testi-track:hover { animation-play-state:paused; }
        @keyframes marquee-scroll { 0% { transform:translateX(0); } 100% { transform:translateX(-50%); } }
        .bl-testi-card {
            background:white; border-radius:20px; padding:1.75rem 1.5rem;
            border:1px solid rgba(200,77,223,.1); box-shadow:0 4px 18px rgba(38,6,50,.05);
            width:340px; flex-shrink:0; display:flex; flex-direction:column;
        }
        .bl-testi-stars { display:flex; gap:3px; color:var(--gold); font-size:.82rem; margin-bottom:.75rem; }
        .bl-testi-quote { font-size:2.4rem; line-height:.9; color:rgba(200,77,223,.12); font-family:Georgia,serif; margin-bottom:.3rem; }
        .bl-testi-text { font-size:.85rem; color:#444; line-height:1.7; flex:1; margin-bottom:1.25rem; }
        .bl-testi-author { display:flex; align-items:center; gap:10px; }
        .bl-testi-avatar { width:38px; height:38px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:.95rem; font-weight:800; color:white; flex-shrink:0; }
        .bl-testi-name { font-size:.86rem; font-weight:700; color:var(--deep); }
        .bl-testi-role { font-size:.72rem; color:var(--text-muted); }
        .bl-testi-badge { display:inline-flex; align-items:center; gap:4px; margin-top:.4rem; font-size:.67rem; font-weight:700; color:#7e22ce; background:rgba(124,58,237,.1); padding:2px 8px; border-radius:50px; }

        /* ─── FAQ ───────────────────────────────────────────────── */
        .bl-faq-section { background:white; }
        .bl-faq-list { display:flex; flex-direction:column; gap:.7rem; margin-top:3rem; max-width:780px; margin-left:auto; margin-right:auto; }
        .bl-faq-item { border:1.5px solid rgba(200,77,223,.12); border-radius:14px; overflow:hidden; background:white; }
        .bl-faq-trigger { display:flex; align-items:center; justify-content:space-between; padding:1.1rem 1.4rem; cursor:pointer; font-size:.92rem; font-weight:600; color:var(--deep); user-select:none; transition:background .2s; }
        .bl-faq-trigger:hover { background:rgba(200,77,223,.04); }
        .bl-faq-icon { width:28px; height:28px; border-radius:50%; border:1.5px solid rgba(200,77,223,.3); display:flex; align-items:center; justify-content:center; font-size:.85rem; color:var(--primary); flex-shrink:0; transition:transform .3s,background .3s,border-color .3s; }
        .bl-faq-item.open .bl-faq-icon { background:var(--primary); color:white; border-color:var(--primary); transform:rotate(45deg); }
        .bl-faq-body { max-height:0; overflow:hidden; transition:max-height .35s ease; }
        .bl-faq-body-inner { padding:0 1.4rem 1.1rem; font-size:.875rem; color:var(--text-muted); line-height:1.7; }
        .bl-faq-item.open .bl-faq-body { max-height:220px; }

        /* ─── CTA ───────────────────────────────────────────────── */
        .bl-cta-section { padding:5.5rem 0; }
        .bl-cta-box {
            background:linear-gradient(160deg,var(--deep) 0%,var(--mid) 60%,#8b1fa8 100%);
            border-radius:28px; padding:4rem 3rem; text-align:center;
            position:relative; overflow:hidden;
        }
        .bl-cta-box::before { content:''; position:absolute; width:400px; height:400px; background:radial-gradient(circle,rgba(200,77,223,.25),transparent 70%); top:-100px; right:-100px; pointer-events:none; }
        .bl-cta-box::after { content:''; position:absolute; width:280px; height:280px; background:radial-gradient(circle,rgba(246,175,35,.14),transparent 70%); bottom:-70px; left:-70px; pointer-events:none; }
        .bl-cta-inner { position:relative; z-index:1; }
        .bl-cta-eyebrow { display:inline-flex; align-items:center; gap:6px; background:rgba(255,255,255,.12); border:1px solid rgba(255,255,255,.15); border-radius:50px; padding:5px 16px; font-size:.73rem; font-weight:700; color:rgba(255,255,255,.9); text-transform:uppercase; letter-spacing:.06em; margin-bottom:1.1rem; }
        .bl-cta-title { font-size:clamp(1.8rem,3vw,2.65rem); font-weight:900; color:white; line-height:1.2; margin-bottom:.9rem; }
        .bl-cta-title em { font-style:italic; color:var(--gold); }
        .bl-cta-desc { font-size:.98rem; color:rgba(255,255,255,.7); margin-bottom:2.25rem; max-width:500px; margin-left:auto; margin-right:auto; }
        .bl-cta-btns { display:flex; gap:1rem; justify-content:center; flex-wrap:wrap; }
        .btn-cta-wa { display:inline-flex; align-items:center; gap:8px; padding:.95rem 2rem; border-radius:14px; font-size:.95rem; font-weight:700; color:white; background:linear-gradient(135deg,#25D366,#128C7E); text-decoration:none; transition:transform .25s,box-shadow .25s; box-shadow:0 6px 20px rgba(37,211,102,.4); }
        .btn-cta-wa:hover { transform:translateY(-3px); box-shadow:0 12px 35px rgba(37,211,102,.55); color:white; }
        .btn-cta-form { display:inline-flex; align-items:center; gap:8px; padding:.95rem 1.9rem; border-radius:14px; font-size:.95rem; font-weight:600; color:white; border:1.5px solid rgba(255,255,255,.3); background:rgba(255,255,255,.08); text-decoration:none; transition:.25s; }
        .btn-cta-form:hover { background:rgba(255,255,255,.16); border-color:rgba(255,255,255,.5); transform:translateY(-2px); color:white; }

        /* ─── FOOTER ─────────────────────────────────────────────── */
        .bl-footer { background:var(--deep); color:rgba(255,255,255,.65); padding:3.5rem 0 2rem; }
        .bl-footer-grid { display:grid; grid-template-columns:2fr 1fr 1fr 1fr; gap:2.5rem; padding-bottom:2.5rem; border-bottom:1px solid rgba(255,255,255,.07); }
        .bl-footer-brand-desc { font-size:.82rem; line-height:1.7; color:rgba(255,255,255,.5); margin-top:.85rem; max-width:260px; }
        .bl-footer-col-title { font-family:var(--font-display); font-size:.83rem; font-weight:700; color:white; margin-bottom:.9rem; }
        .bl-footer-links { list-style:none; display:flex; flex-direction:column; gap:8px; }
        .bl-footer-links a { font-size:.81rem; color:rgba(255,255,255,.5); text-decoration:none; transition:color .2s; }
        .bl-footer-links a:hover { color:var(--primary); }
        .bl-footer-links li { font-size:.81rem; color:rgba(255,255,255,.5); }
        .bl-footer-bottom { padding-top:2rem; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:.75rem; font-size:.78rem; }

        /* ─── FLOATING BUTTONS ──────────────────────────────────── */
        .bl-wa-float {
            position:fixed; bottom:1.75rem; right:1.75rem; z-index:9000;
            width:58px; height:58px; border-radius:50%;
            background:linear-gradient(135deg,#25D366,#128C7E);
            display:flex; align-items:center; justify-content:center;
            font-size:1.5rem; color:white; text-decoration:none;
            box-shadow:0 8px 28px rgba(37,211,102,.5);
            animation:wa-pulse 2.5s ease-in-out infinite;
        }
        .bl-wa-float:hover { color:white; transform:scale(1.1); }
        @keyframes wa-pulse { 0%,100% { box-shadow:0 8px 28px rgba(37,211,102,.5),0 0 0 0 rgba(37,211,102,.3); } 50% { box-shadow:0 8px 28px rgba(37,211,102,.5),0 0 0 14px rgba(37,211,102,0); } }
        .bl-scroll-top {
            position:fixed; bottom:1.75rem; left:1.75rem; z-index:9000;
            width:44px; height:44px; border-radius:12px;
            background:rgba(38,6,50,.82); backdrop-filter:blur(12px);
            border:1px solid rgba(200,77,223,.22);
            display:flex; align-items:center; justify-content:center;
            color:white; font-size:.95rem; cursor:pointer;
            opacity:0; visibility:hidden; transition:opacity .3s,visibility .3s,transform .3s;
            transform:translateY(10px);
        }
        .bl-scroll-top.visible { opacity:1; visibility:visible; transform:translateY(0); }

        /* ─── REVEAL ────────────────────────────────────────────── */
        .reveal { opacity:0; transform:translateY(24px); transition:opacity .6s var(--ease-out),transform .6s var(--ease-out); }
        .reveal.visible { opacity:1; transform:translateY(0); }
        .reveal-d1 { transition-delay:.1s; }
        .reveal-d2 { transition-delay:.2s; }
        .reveal-d3 { transition-delay:.3s; }

        /* ─── RESPONSIVE ────────────────────────────────────────── */
        @media (max-width:1024px) {
            .bl-hero-grid { grid-template-columns:1fr; gap:2.5rem; }
            .bl-form-card { max-width:480px; margin:0 auto; }
            .bl-features-grid { grid-template-columns:repeat(2,1fr); }
            .bl-subjects-grid { grid-template-columns:repeat(3,1fr); }
            .bl-harga-grid { grid-template-columns:repeat(2,1fr); }
            .bl-footer-grid { grid-template-columns:1fr 1fr; gap:2rem; }
        }
        @media (max-width:768px) {
            .bl-nav-links, .btn-bl-cta { display:none; }
            .bl-nav-toggle { display:flex; }
            .bl-section { padding:4rem 0; }
            .bl-subjects-grid { grid-template-columns:repeat(2,1fr); }
            .bl-metode-grid { grid-template-columns:1fr; }
            .bl-lokasi-grid { grid-template-columns:1fr; gap:2rem; }
            .bl-harga-grid { grid-template-columns:1fr; }
            .bl-cta-box { padding:2.75rem 1.5rem; }
            .bl-footer-grid { grid-template-columns:1fr; gap:1.75rem; }
        }
        @media (max-width:560px) {
            .bl-hero-stats { flex-direction:column; }
            .bl-form-row { grid-template-columns:1fr; }
            .bl-features-grid { grid-template-columns:1fr; }
            .bl-subjects-grid { grid-template-columns:repeat(2,1fr); }
            .bl-hero-title { font-size:2.1rem; }
        }
    </style>
</head>
<body>

{{-- ──────────────── PROMO TICKER ──────────────────────────────────── --}}
<div class="promo-ticker" aria-label="Promo">
    <div class="ticker-track">
        @foreach(array_merge($promoItems, $promoItems) as $tk)
        <span class="ticker-item">📢 {!! htmlspecialchars($tk) !!}</span>
        <span class="ticker-sep">|</span>
        @endforeach
    </div>
</div>

{{-- ──────────────── NAVBAR ─────────────────────────────────────────── --}}
<nav class="bl-nav" id="blNav">
    <div class="bl-nav-inner">
        <a href="{{ url('/') }}" class="bl-brand">
            <div class="bl-brand-icon">SCI</div>
            <div class="bl-brand-text">
                Smart Center Indonesia
                <small>Les Privat {{ $cityName }}</small>
            </div>
        </a>
        <ul class="bl-nav-links">
            <li><a href="#keunggulan">Keunggulan</a></li>
            <li><a href="#program">Program</a></li>
            <li><a href="#harga">Harga</a></li>
            <li><a href="#lokasi">Lokasi</a></li>
            <li><a href="#testimoni">Testimoni</a></li>
            <li><a href="#faq">FAQ</a></li>
        </ul>
        <a href="https://wa.me/{{ $branchWa }}?text={{ urlencode('Halo SCI '.$cityName.'! Saya ingin konsultasi gratis tentang les privat.') }}"
           target="_blank" class="btn-bl-cta">
            <i class="bi bi-whatsapp"></i> Daftar Sekarang
        </a>
        <button class="bl-nav-toggle" id="blNavToggle" aria-label="Menu">
            <span></span><span></span><span></span>
        </button>
    </div>
</nav>

{{-- Mobile Menu --}}
<div class="bl-mobile-menu" id="blMobileMenu" aria-hidden="true">
    <button class="bl-mobile-close" onclick="closeBLMenu()" aria-label="Tutup">
        <i class="bi bi-x-lg"></i>
    </button>
    <a href="#keunggulan" onclick="closeBLMenu()">Keunggulan</a>
    <a href="#program"    onclick="closeBLMenu()">Program</a>
    <a href="#harga"      onclick="closeBLMenu()">Harga</a>
    <a href="#lokasi"     onclick="closeBLMenu()">Lokasi</a>
    <a href="#testimoni"  onclick="closeBLMenu()">Testimoni</a>
    <a href="#faq"        onclick="closeBLMenu()">FAQ</a>
</div>

{{-- ──────────────── HERO ───────────────────────────────────────────── --}}
<section class="bl-hero" id="home">
    <div class="bl-hero-inner">
        <div class="container-bl">
            <div class="bl-hero-grid">
                {{-- Left: Text --}}
                <div>
                    <div class="bl-breadcrumb">
                        <a href="{{ url('/') }}">Beranda</a>
                        <span>›</span>
                        <a href="{{ url('/') }}#cabang">Cabang</a>
                        <span>›</span>
                        <span style="color:rgba(255,255,255,.75)">{{ $cityName }}</span>
                    </div>
                    <div class="bl-hero-badge">
                        🏆 {{ $heroBadge }}
                    </div>
                    <h1 class="bl-hero-title">
                        Les Privat <em>Terbaik</em><br>di {{ $cityName }}
                    </h1>
                    <p class="bl-hero-desc">
                        {{ $heroDesc }}
                    </p>
                    <div class="bl-hero-stats">
                        <div class="bl-stat-chip">
                            <span class="bl-stat-chip-icon">⭐</span>
                            <div>
                                <div class="bl-stat-chip-val">Rating 4.8/5.0</div>
                                <span class="bl-stat-chip-lab">{{ number_format($studentCount) }}+ ulasan siswa</span>
                            </div>
                        </div>
                        <div class="bl-stat-chip">
                            <span class="bl-stat-chip-icon">🎓</span>
                            <div>
                                <div class="bl-stat-chip-val">{{ $tutorCount }}+ Tutor</div>
                                <span class="bl-stat-chip-lab">Bersertifikat resmi</span>
                            </div>
                        </div>
                        <div class="bl-stat-chip">
                            <span class="bl-stat-chip-icon">🏠</span>
                            <div>
                                <div class="bl-stat-chip-val">Home Visit</div>
                                <span class="bl-stat-chip-lab">Ke seluruh {{ $cityName }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right: Consultation Form --}}
                <div class="bl-form-card">
                    <div class="bl-form-title">📋 Daftar Konsultasi Gratis</div>
                    <div class="bl-form-sub">Isi form &amp; tim kami hubungi dalam 1 jam</div>
                    <form id="blConsultForm" onsubmit="submitConsult(event)">
                        <div class="bl-form-row">
                            <div class="bl-field">
                                <label class="bl-label">Nama</label>
                                <input type="text" id="cfName" class="bl-input" placeholder="Nama siswa" required>
                            </div>
                            <div class="bl-field">
                                <label class="bl-label">No. WA</label>
                                <input type="tel" id="cfWa" class="bl-input" placeholder="08xx" required>
                            </div>
                        </div>
                        <div class="bl-field">
                            <label class="bl-label">Jenjang / Kelas</label>
                            <select id="cfJenjang" class="bl-select" required>
                                <option value="" disabled selected>Pilih jenjang...</option>
                                <option>TK / PAUD</option>
                                <option>SD Kelas 1–3</option>
                                <option>SD Kelas 4–6</option>
                                <option>SMP Kelas 7</option>
                                <option>SMP Kelas 8</option>
                                <option>SMP Kelas 9</option>
                                <option>SMA Kelas 10</option>
                                <option>SMA Kelas 11</option>
                                <option>SMA Kelas 12</option>
                                <option>Kuliah / Mahasiswa</option>
                                <option>Umum / Orang Dewasa</option>
                            </select>
                        </div>
                        <div class="bl-field">
                            <label class="bl-label">Mata Pelajaran</label>
                            <select id="cfMapel" class="bl-select" required>
                                <option value="" disabled selected>Pilih mata pelajaran...</option>
                                <option>Matematika</option>
                                <option>Fisika</option>
                                <option>Kimia</option>
                                <option>Biologi</option>
                                <option>Bahasa Inggris</option>
                                <option>Bahasa Indonesia</option>
                                <option>Akuntansi</option>
                                <option>Komputer / IT</option>
                                <option>Bahasa Jepang</option>
                                <option>Persiapan SBMPTN/UTBK</option>
                                <option>Lainnya</option>
                            </select>
                        </div>
                        <div class="bl-field">
                            <label class="bl-label">Metode Belajar</label>
                            <select id="cfMetode" class="bl-select">
                                <option>Home Visit (Tutor ke rumah saya)</option>
                                <option>Online (Zoom / Google Meet)</option>
                                <option>Offline (Di kantor SCI)</option>
                            </select>
                        </div>
                        <button type="submit" class="btn-bl-form">
                            🚀 Kirim &amp; Dapatkan Konsultasi Gratis
                        </button>
                    </form>
                    <div class="bl-form-note">
                        <span>✅</span> 100% Gratis — Tidak ada kewajiban daftar
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ──────────────── DIPERCAYA RIBUAN KELUARGA ─────────────────────── --}}
<section class="bl-section bl-features-section" id="keunggulan">
    <div class="container-bl">
        <div class="text-center reveal">
            <div class="bl-section-eyebrow">Mengapa SCI {{ $cityName }}?</div>
            <h2 class="bl-section-title">Dipercaya Ribuan Keluarga<br><em>di {{ $cityName }}</em></h2>
            <p class="bl-section-sub mx-auto" style="text-align:center">
                SCI hadir di {{ $cityName }} sejak 2012 dengan rekam jejak nyata dalam meningkatkan prestasi siswa dari berbagai jenjang.
            </p>
        </div>
        <div class="bl-features-grid">
            @foreach($features as $fi => $f)
            <div class="bl-feat-card reveal reveal-d{{ ($fi % 3) + 1 }}">
                <div class="bl-feat-num">{{ $f['num'] }}</div>
                <span class="bl-feat-icon">{{ $f['icon'] }}</span>
                <div class="bl-feat-title">{{ $f['title'] }}</div>
                <div class="bl-feat-desc">{{ $f['desc'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ──────────────── MATA PELAJARAN ────────────────────────────────── --}}
<section class="bl-section bl-subjects-section" id="program">
    <div class="container-bl">
        <div class="text-center reveal">
            <div class="bl-section-eyebrow">Mata Pelajaran</div>
            <h2 class="bl-section-title">Program Les &amp; Kursus<br><em>di {{ $cityName }}</em></h2>
            <p class="bl-section-sub mx-auto" style="text-align:center">
                Semua mata pelajaran dan kursus tersedia dengan tutor spesialis di bidangnya masing-masing.
            </p>
        </div>
        <div class="bl-subjects-grid">
            @foreach($subjects as $si => $subj)
            <div class="bl-subj-card reveal reveal-d{{ ($si % 3) + 1 }}">
                <span class="bl-subj-icon">{{ $subj['icon'] }}</span>
                <div class="bl-subj-name">{{ $subj['name'] }}</div>
                <div class="bl-subj-desc">{{ $subj['desc'] }}</div>
                <span class="bl-badge {{ $subj['badge_type'] }}">{{ $subj['badge'] }}</span>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ──────────────── METODE BELAJAR ────────────────────────────────── --}}
<section class="bl-section bl-metode-section">
    <div class="container-bl">
        <div class="text-center reveal">
            <div class="bl-section-eyebrow">Metode Belajar</div>
            <h2 class="bl-section-title">Pilih Cara Belajar <em>Terbaik</em></h2>
            <p class="bl-section-sub mx-auto" style="text-align:center">
                Tiga metode layanan SCI {{ $cityName }} — pilih yang paling nyaman dan sesuai kebutuhan Anda.
            </p>
        </div>
        <div class="bl-metode-grid">
            @foreach($metodes as $mi => $m)
            <div class="bl-metode-card reveal reveal-d{{ $mi + 1 }}">
                <div class="bl-metode-img">
                    <img src="{{ $m['img'] }}" alt="{{ $m['title'] }}" loading="lazy">
                    <div class="bl-metode-type-badge">{{ $m['icon'] }} {{ $m['type'] }}</div>
                </div>
                <div class="bl-metode-body">
                    <div class="bl-metode-title">{{ $m['title'] }}</div>
                    <div class="bl-metode-desc">{{ $m['desc'] }}</div>
                    <div class="bl-metode-price">
                        Mulai <strong>{{ $m['price'] }}</strong><span> /sesi</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ──────────────── LOKASI ─────────────────────────────────────────── --}}
<section class="bl-section bl-lokasi-section" id="lokasi">
    <div class="container-bl">
        <div class="reveal">
            <div class="bl-section-eyebrow">Lokasi Kami</div>
            <h2 class="bl-section-title">Kantor SCI <em>{{ $cityName }}</em></h2>
        </div>
        <div class="bl-lokasi-grid">
            <div>
                <div style="font-size:1rem;font-weight:700;color:var(--deep);font-family:var(--font-display);margin-bottom:1.5rem">
                    Informasi Lokasi &amp; Kontak
                </div>
                <div class="bl-contact-list">
                    <div class="bl-contact-item reveal reveal-d1">
                        <div class="bl-contact-icon"><i class="bi bi-telephone-fill"></i></div>
                        <div>
                            <div class="bl-contact-label">Nomor Telepon &amp; WhatsApp</div>
                            <div class="bl-contact-val">
                                @if($branch->phone)
                                    {{ $branch->phone }}
                                @else
                                    Hubungi kami via WhatsApp
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="bl-contact-item reveal reveal-d2">
                        <div class="bl-contact-icon"><i class="bi bi-clock-fill"></i></div>
                        <div>
                            <div class="bl-contact-label">Jam Operasional</div>
                            <div class="bl-contact-val">
                                Senin – Sabtu: {{ $hoursWeekday }}<br>
                                Minggu: {{ $hoursWeekend }}
                            </div>
                        </div>
                    </div>
                    <div class="bl-contact-item reveal reveal-d3">
                        <div class="bl-contact-icon"><i class="bi bi-envelope-fill"></i></div>
                        <div>
                            <div class="bl-contact-label">Email</div>
                            <div class="bl-contact-val">{{ $branch->email ?: 'smartcenterindonesia@gmail.com' }}</div>
                        </div>
                    </div>
                    @if($branch->address)
                    <div class="bl-contact-item reveal reveal-d1">
                        <div class="bl-contact-icon"><i class="bi bi-geo-alt-fill"></i></div>
                        <div>
                            <div class="bl-contact-label">Alamat</div>
                            <div class="bl-contact-val">{{ $branch->address }}</div>
                        </div>
                    </div>
                    @endif
                </div>

                <div style="margin-top:2rem" class="reveal">
                    <div class="bl-area-title">Area Layanan Home Visit</div>
                    <div class="bl-area-chips">
                        @foreach($areaChips as $area)
                        <span class="bl-area-chip">📍 {{ $area }}</span>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="reveal reveal-d2">
                <div class="bl-map-placeholder">
                    <div class="bl-map-icon">📍</div>
                    <div class="bl-map-text">
                        <strong style="color:var(--deep)">Kantor SCI {{ $cityName }}</strong><br>
                        {{ $branch->address ?: 'Hubungi kami untuk alamat lengkap' }}<br><br>
                        <a href="https://wa.me/{{ $branchWa }}?text={{ urlencode('Halo SCI '.$cityName.'! Saya ingin tahu lokasi kantor SCI '.$cityName.'.') }}"
                           target="_blank"
                           style="display:inline-flex;align-items:center;gap:6px;padding:.5rem 1.25rem;border-radius:10px;background:linear-gradient(135deg,#25D366,#128C7E);color:white;font-size:.82rem;font-weight:700;text-decoration:none;margin-top:.5rem">
                            <i class="bi bi-whatsapp"></i> Tanya Lokasi via WA
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ──────────────── HARGA & PAKET ─────────────────────────────────── --}}
<section class="bl-section bl-harga-section" id="harga">
    <div class="container-bl">
        <div class="text-center reveal">
            <div class="bl-section-eyebrow">Biaya &amp; Paket</div>
            <h2 class="bl-section-title">Harga Les Privat <em>{{ $cityName }}</em></h2>
            <p class="bl-section-sub mx-auto" style="text-align:center">
                Harga transparan tanpa biaya tersembunyi. Pilih paket yang sesuai kebutuhan dan budget Anda.
            </p>
        </div>

        @if($hasBranchPricingCards)
        {{-- Branch-specific custom pricing cards (highest priority) --}}
        <div class="bl-harga-grid">
            @foreach($branchPricingCards as $pkg)
            @php $ung = $pkg['unggulan'] ?? false; @endphp
            <div class="bl-pkg-card {{ $ung ? 'unggulan' : '' }}"
                 style="{{ $ung ? 'background:linear-gradient(135deg,#260632,#461256);padding-top:2.75rem' : 'background:white' }}">
                @if($ung)
                    <div class="bl-pkg-badge-best">⭐ PALING POPULER</div>
                @endif
                <div class="bl-pkg-name" style="{{ $ung ? 'color:white' : '' }}">{{ $pkg['name'] }}</div>
                <div class="bl-pkg-desc" style="{{ $ung ? 'color:rgba(255,255,255,.6)' : '' }}">{{ $pkg['desc'] ?? '' }}</div>
                <div class="bl-pkg-price" style="{{ $ung ? 'color:var(--gold)' : '' }}">
                    {{ $pkg['price'] ?? '' }}<span>{{ $pkg['unit'] ?? '' }}</span>
                </div>
                <div class="bl-pkg-sessions" style="{{ $ung ? 'color:rgba(255,255,255,.5)' : '' }}">
                    {{ $pkg['sessions'] ?? '' }}
                </div>
                @if(!empty($pkg['fitur']))
                <ul class="bl-pkg-fitur">
                    @foreach($pkg['fitur'] as $f)
                    <li style="{{ $ung ? 'color:rgba(255,255,255,.8)' : '' }}">{{ $f }}</li>
                    @endforeach
                </ul>
                @endif
                <a href="https://wa.me/{{ $branchWa }}?text={{ urlencode('Halo SCI '.$cityName.'! Saya tertarik dengan '.($pkg['name'] ?? 'paket les').'. Bisa info lebih lanjut?') }}"
                   target="_blank"
                   class="btn-bl-pkg {{ $ung ? 'primary' : 'default' }}">
                    <i class="bi bi-whatsapp"></i> Pilih Paket Ini
                </a>
            </div>
            @endforeach
        </div>
        @elseif($hasDbPackages)
        {{-- DB Packages --}}
        <div class="bl-harga-grid">
            @foreach($packages->take(3) as $pkg)
            @php
                $fiturList = is_array($pkg->fitur) ? $pkg->fitur : [];
                $harga     = $pkg->harga ? 'Rp '.number_format($pkg->harga,0,',','.') : 'Hubungi Kami';
                $ung       = (bool)$pkg->is_unggulan;
            @endphp
            <div class="bl-pkg-card {{ $ung ? 'unggulan' : '' }}"
                 style="{{ $ung ? 'background:linear-gradient(135deg,#260632,#461256);padding-top:2.75rem' : 'background:white' }}">
                @if($ung)
                    <div class="bl-pkg-badge-best">⭐ PALING POPULER</div>
                @endif
                <div class="bl-pkg-name" style="{{ $ung ? 'color:white' : '' }}">{{ $pkg->nama }}</div>
                <div class="bl-pkg-desc" style="{{ $ung ? 'color:rgba(255,255,255,.6)' : '' }}">{{ $pkg->deskripsi }}</div>
                <div class="bl-pkg-price" style="{{ $ung ? 'color:var(--gold)' : '' }}">
                    {{ $harga }}<span>{{ $pkg->harga ? '/sesi' : '' }}</span>
                </div>
                <div class="bl-pkg-sessions" style="{{ $ung ? 'color:rgba(255,255,255,.5)' : '' }}">
                    {{ $pkg->jumlah_pertemuan ? $pkg->jumlah_pertemuan.'× pertemuan' : 'Jadwal fleksibel' }}
                </div>
                @if($fiturList)
                <ul class="bl-pkg-fitur">
                    @foreach($fiturList as $f)
                    <li style="{{ $ung ? 'color:rgba(255,255,255,.8)' : '' }}">{{ $f }}</li>
                    @endforeach
                </ul>
                @endif
                <a href="https://wa.me/{{ $branchWa }}?text={{ urlencode('Halo SCI '.$cityName.'! Saya tertarik dengan '.$pkg->nama.'. Bisa info lebih lanjut?') }}"
                   target="_blank"
                   class="btn-bl-pkg {{ $ung ? 'primary' : 'default' }}">
                    <i class="bi bi-whatsapp"></i> Pilih Paket Ini
                </a>
            </div>
            @endforeach
        </div>
        @else
        {{-- Default Packages --}}
        <div class="bl-harga-grid">
            @foreach($defaultPricing as $pkg)
            @php $ung = $pkg['unggulan'] ?? false; @endphp
            <div class="bl-pkg-card {{ $ung ? 'unggulan' : '' }}"
                 style="{{ $ung ? 'background:linear-gradient(135deg,#260632,#461256);padding-top:2.75rem' : 'background:white' }}">
                @if($ung)
                    <div class="bl-pkg-badge-best">⭐ PALING POPULER</div>
                @endif
                <div class="bl-pkg-name" style="{{ $ung ? 'color:white' : '' }}">{{ $pkg['name'] }}</div>
                <div class="bl-pkg-desc" style="{{ $ung ? 'color:rgba(255,255,255,.6)' : '' }}">{{ $pkg['desc'] }}</div>
                <div class="bl-pkg-price" style="{{ $ung ? 'color:var(--gold)' : '' }}">
                    {{ $pkg['price'] }}<span>{{ $pkg['unit'] }}</span>
                </div>
                <div class="bl-pkg-sessions" style="{{ $ung ? 'color:rgba(255,255,255,.5)' : '' }}">
                    {{ $pkg['sessions'] }}
                </div>
                <ul class="bl-pkg-fitur">
                    @foreach($pkg['fitur'] as $f)
                    <li style="{{ $ung ? 'color:rgba(255,255,255,.8)' : '' }}">{{ $f }}</li>
                    @endforeach
                </ul>
                <a href="https://wa.me/{{ $branchWa }}?text={{ urlencode('Halo SCI '.$cityName.'! Saya tertarik dengan '.$pkg['name'].'. Bisa info lebih lanjut?') }}"
                   target="_blank"
                   class="btn-bl-pkg {{ $ung ? 'primary' : 'default' }}">
                    <i class="bi bi-whatsapp"></i> Pilih Paket Ini
                </a>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</section>

{{-- ──────────────── TESTIMONI ─────────────────────────────────────── --}}
<section class="bl-section bl-testi-section" id="testimoni">
    <div class="container-bl">
        <div class="text-center reveal">
            <div class="bl-section-eyebrow">Kata Mereka</div>
            <h2 class="bl-section-title">Testimoni <em>Siswa</em> Indonesia</h2>
            <p class="bl-section-sub mx-auto" style="text-align:center">
                Dengarkan cerita sukses ribuan siswa yang telah mempercayai SCI sebagai mitra belajar mereka.
            </p>
        </div>
    </div>
    <div class="bl-testi-vp">
        <div class="bl-testi-track">
            {{-- Set 1 --}}
            @foreach($testiData as $t)
            <div class="bl-testi-card">
                <div class="bl-testi-stars">
                    @for($s=0;$s<5;$s++)<i class="bi bi-star-fill"></i>@endfor
                </div>
                <div class="bl-testi-quote">"</div>
                <p class="bl-testi-text">{{ $t->text }}</p>
                <div class="bl-testi-author">
                    <div class="bl-testi-avatar" style="background:{{ $t->gradient }}">{{ $t->initial }}</div>
                    <div>
                        <div class="bl-testi-name">{{ $t->name }}</div>
                        <div class="bl-testi-role">{{ $t->role }}</div>
                        <div class="bl-testi-badge"><i class="bi bi-patch-check-fill"></i> Siswa Terverifikasi</div>
                    </div>
                </div>
            </div>
            @endforeach
            {{-- Set 2 (duplicate for seamless loop) --}}
            @foreach($testiData as $t)
            <div class="bl-testi-card" aria-hidden="true">
                <div class="bl-testi-stars">
                    @for($s=0;$s<5;$s++)<i class="bi bi-star-fill"></i>@endfor
                </div>
                <div class="bl-testi-quote">"</div>
                <p class="bl-testi-text">{{ $t->text }}</p>
                <div class="bl-testi-author">
                    <div class="bl-testi-avatar" style="background:{{ $t->gradient }}">{{ $t->initial }}</div>
                    <div>
                        <div class="bl-testi-name">{{ $t->name }}</div>
                        <div class="bl-testi-role">{{ $t->role }}</div>
                        <div class="bl-testi-badge"><i class="bi bi-patch-check-fill"></i> Siswa Terverifikasi</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ──────────────── FAQ ────────────────────────────────────────────── --}}
<section class="bl-section bl-faq-section" id="faq">
    <div class="container-bl">
        <div class="text-center reveal">
            <div class="bl-section-eyebrow">FAQ</div>
            <h2 class="bl-section-title">Pertanyaan Umum<br><em>Les Privat {{ $cityName }}</em></h2>
            <p class="bl-section-sub mx-auto" style="text-align:center">
                Temukan jawaban atas pertanyaan yang sering diajukan tentang layanan SCI {{ $cityName }}.
            </p>
        </div>
        <div class="bl-faq-list">
            @foreach($faqItems as $fi => $faq)
            <div class="bl-faq-item">
                <button class="bl-faq-trigger" onclick="toggleBLFaq(this)" type="button">
                    {{ $faq['q'] }}
                    <span class="bl-faq-icon"><i class="bi bi-plus"></i></span>
                </button>
                <div class="bl-faq-body">
                    <div class="bl-faq-body-inner">{{ $faq['a'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ──────────────── CTA ────────────────────────────────────────────── --}}
<section class="bl-cta-section">
    <div class="container-bl">
        <div class="bl-cta-box reveal">
            <div class="bl-cta-inner">
                <div class="bl-cta-eyebrow">{{ $ctaEyebrow }}</div>
                <h2 class="bl-cta-title">{{ $ctaTitle }}<br><em>{{ $cityName }}?</em></h2>
                <p class="bl-cta-desc">
                    {{ $ctaDesc ?: 'Bergabung dengan '.number_format($studentCount).'+  siswa SCI '.$cityName.' yang telah merasakan manfaatnya. Konsultasi gratis — tanpa kewajiban daftar.' }}
                </p>
                <div class="bl-cta-btns">
                    <a href="https://wa.me/{{ $branchWa }}?text={{ urlencode('Halo SCI '.$cityName.'! Saya ingin konsultasi gratis tentang les privat di '.$cityName.'.') }}"
                       target="_blank" class="btn-cta-wa">
                        <i class="bi bi-whatsapp"></i> Konsultasi Gratis via WA
                    </a>
                    <a href="#home" class="btn-cta-form">
                        <i class="bi bi-clipboard2-check"></i> Isi Formulir Online
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ──────────────── FOOTER ─────────────────────────────────────────── --}}
<footer class="bl-footer">
    <div class="container-bl">
        <div class="bl-footer-grid">
            <div>
                <div style="display:flex;align-items:center;gap:10px;text-decoration:none">
                    <div class="bl-brand-icon" style="width:36px;height:36px;border-radius:9px;background:linear-gradient(135deg,#68117e,#c84ddf);display:flex;align-items:center;justify-content:center;font-size:.72rem;font-weight:900;color:white;font-family:var(--font-display)">SCI</div>
                    <div style="font-family:var(--font-display);font-weight:800;font-size:.95rem;color:white;line-height:1.2">
                        Smart Center Indonesia<br><small style="font-size:.62rem;font-weight:500;color:rgba(255,255,255,.5)">Les Privat {{ $cityName }}</small>
                    </div>
                </div>
                <p class="bl-footer-brand-desc">
                    Jasa les privat &amp; bimbel terbaik di {{ $cityName }}. Hadir dengan {{ $tutorCount }}+ tutor bersertifikat, melayani seluruh area {{ $cityName }} dan sekitarnya.
                </p>
            </div>
            <div>
                <div class="bl-footer-col-title">Navigasi</div>
                <ul class="bl-footer-links">
                    <li><a href="{{ url('/') }}">Beranda Utama</a></li>
                    <li><a href="{{ url('/') }}#cabang">Semua Cabang</a></li>
                    <li><a href="#keunggulan">Keunggulan</a></li>
                    <li><a href="#harga">Harga &amp; Paket</a></li>
                    <li><a href="#testimoni">Testimoni</a></li>
                </ul>
            </div>
            <div>
                <div class="bl-footer-col-title">Layanan</div>
                <ul class="bl-footer-links">
                    <li><a href="#program">Les Privat SD {{ $cityName }}</a></li>
                    <li><a href="#program">Les Privat SMP {{ $cityName }}</a></li>
                    <li><a href="#program">Les Privat SMA {{ $cityName }}</a></li>
                    <li><a href="#program">Kursus Bahasa {{ $cityName }}</a></li>
                    <li><a href="#program">Kursus Komputer {{ $cityName }}</a></li>
                </ul>
            </div>
            <div>
                <div class="bl-footer-col-title">Kontak</div>
                <ul class="bl-footer-links">
                    @if($branch->phone)
                    <li><a href="tel:{{ $branchWa }}"><i class="bi bi-telephone-fill" style="color:var(--primary);margin-right:5px"></i>{{ $branch->phone }}</a></li>
                    @endif
                    <li><a href="https://wa.me/{{ $branchWa }}" target="_blank"><i class="bi bi-whatsapp" style="color:var(--primary);margin-right:5px"></i>WhatsApp Kami</a></li>
                    <li><a href="mailto:{{ $branch->email ?: 'smartcenterindonesia@gmail.com' }}"><i class="bi bi-envelope-fill" style="color:var(--primary);margin-right:5px"></i>{{ $branch->email ?: 'Email' }}</a></li>
                    <li><i class="bi bi-clock-fill" style="color:var(--primary);margin-right:5px"></i>Senin–Sabtu (08.00–20.00)</li>
                </ul>
            </div>
        </div>
        <div class="bl-footer-bottom">
            <span>&copy; {{ date('Y') }} Smart Center Indonesia — Cabang {{ $cityName }}. All Rights Reserved.</span>
            <span style="color:rgba(255,255,255,.35)">Made with ❤️ for {{ $cityName }} Education</span>
        </div>
    </div>
</footer>

{{-- Floating Buttons --}}
<a href="https://wa.me/{{ $branchWa }}?text={{ urlencode('Halo SCI '.$cityName.'! Saya ingin konsultasi gratis.') }}"
   target="_blank" class="bl-wa-float" rel="noopener" aria-label="Hubungi via WhatsApp">
    <i class="bi bi-whatsapp"></i>
</a>
<button class="bl-scroll-top" id="blScrollTop" aria-label="Kembali ke atas"
        onclick="window.scrollTo({top:0,behavior:'smooth'})">
    <i class="bi bi-arrow-up"></i>
</button>

<script>
/* ── Mobile menu ── */
const blToggle  = document.getElementById('blNavToggle');
const blMenu    = document.getElementById('blMobileMenu');
blToggle.addEventListener('click', () => {
    const open = blMenu.classList.toggle('open');
    blToggle.classList.toggle('open', open);
    blMenu.setAttribute('aria-hidden', open ? 'false' : 'true');
    document.body.style.overflow = open ? 'hidden' : '';
});
function closeBLMenu() {
    blMenu.classList.remove('open');
    blToggle.classList.remove('open');
    blMenu.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeBLMenu(); });

/* ── Active nav link ── */
const blNavLinks = document.querySelectorAll('.bl-nav-links a');
const blSections = document.querySelectorAll('section[id]');
const blNavObs   = new IntersectionObserver((entries) => {
    entries.forEach(e => {
        if (e.isIntersecting) {
            blNavLinks.forEach(l => l.classList.remove('active'));
            const a = document.querySelector(`.bl-nav-links a[href="#${e.target.id}"]`);
            if (a) a.classList.add('active');
        }
    });
}, { threshold:.25, rootMargin:'-62px 0px -35% 0px' });
blSections.forEach(s => blNavObs.observe(s));

/* ── Scroll reveal ── */
const revEls = document.querySelectorAll('.reveal');
const revObs = new IntersectionObserver((entries) => {
    entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); revObs.unobserve(e.target); } });
}, { threshold:.1, rootMargin:'0px 0px -30px 0px' });
revEls.forEach(el => revObs.observe(el));

/* ── Scroll-to-top ── */
const blScrollTop = document.getElementById('blScrollTop');
window.addEventListener('scroll', () => {
    blScrollTop.classList.toggle('visible', window.scrollY > 400);
}, { passive:true });

/* ── Smooth anchor scroll ── */
document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', e => {
        const id = a.getAttribute('href').slice(1);
        const target = document.getElementById(id);
        if (target) { e.preventDefault(); target.scrollIntoView({ behavior:'smooth', block:'start' }); }
    });
});

/* ── FAQ accordion ── */
function toggleBLFaq(trigger) {
    const item   = trigger.closest('.bl-faq-item');
    const isOpen = item.classList.contains('open');
    document.querySelectorAll('.bl-faq-item.open').forEach(el => el.classList.remove('open'));
    if (!isOpen) item.classList.add('open');
}

/* ── Consult form → WA ── */
function submitConsult(e) {
    e.preventDefault();
    const name    = document.getElementById('cfName').value    || '';
    const wa      = document.getElementById('cfWa').value      || '';
    const jenjang = document.getElementById('cfJenjang').value || '';
    const mapel   = document.getElementById('cfMapel').value   || '';
    const metode  = document.getElementById('cfMetode').value  || '';
    const msg = `Halo SCI {{ $cityName }}! Saya ingin konsultasi les privat.\n\nNama   : ${name}\nNo. WA : ${wa}\nJenjang: ${jenjang}\nMapel  : ${mapel}\nMetode : ${metode}\n\nMohon info lebih lanjut. Terima kasih!`;
    window.open('https://wa.me/{{ $branchWa }}?text=' + encodeURIComponent(msg), '_blank');
}

/* ── Reduced motion ── */
if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    document.querySelectorAll('.bl-testi-track,.ticker-track').forEach(el => {
        el.style.animationDuration = '0s';
        el.style.animationPlayState = 'paused';
    });
}
</script>
</body>
</html>
