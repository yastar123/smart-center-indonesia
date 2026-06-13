@php
    $stats = [
        'students' => \App\Models\Student::where('status','aktif')->count(),
        'teachers' => \App\Models\Teacher::where('status','aktif')->count(),
        'branches' => \App\Models\Branch::count(),
    ];
    $tutors     = \App\Models\Teacher::where('status','aktif')->get();
    $branches   = \App\Models\Branch::all();
    $lsAll      = \App\Models\LandingSetting::all()->keyBy('key');
    $ls         = fn(string $k, string $d='') => $lsAll[$k]->value ?? $d;
    $dbTestis   = \App\Models\LandingTestimonial::active()->orderBy('sort_order')->get();
    $dbPrograms = \App\Models\LandingProgram::active()->orderBy('sort_order')->get();
    $waMain     = \App\Models\LandingWaNumber::primaryNumber($ls('footer.wa_number','628001234567'));
    $waNumbers  = \App\Models\LandingWaNumber::active()->orderBy('sort_order')->get();

    $heroSlides = [
        ['img'=>'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=1600&q=80','alt'=>'Siswa belajar bersama'],
        ['img'=>'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?auto=format&fit=crop&w=1600&q=80','alt'=>'Les privat'],
        ['img'=>'https://images.unsplash.com/photo-1427504494785-3a9ca7044f45?auto=format&fit=crop&w=1600&q=80','alt'=>'Kelas bimbel'],
    ];

    $jenjangItems = [
        ['label'=>'TK','desc'=>'Taman Kanak-Kanak','img'=>'https://images.unsplash.com/photo-1503454537195-1dcabb73ffb9?auto=format&fit=crop&w=300&q=80'],
        ['label'=>'SD','desc'=>'Sekolah Dasar','img'=>'https://images.unsplash.com/photo-1503676382389-4809596d5290?auto=format&fit=crop&w=300&q=80'],
        ['label'=>'SMP','desc'=>'Sekolah Menengah Pertama','img'=>'https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&fit=crop&w=300&q=80'],
        ['label'=>'SMA / Umum','desc'=>'SMA & Karyawan','img'=>'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?auto=format&fit=crop&w=300&q=80'],
    ];

    $keunggulanItems = [
        ['icon'=>'🎓','title'=>'Tutor Bersertifikat','desc'=>'Pengajar ahli bersertifikat resmi dengan pengalaman bertahun-tahun dan rekam jejak membuktikan hasil nyata.'],
        ['icon'=>'🏠','title'=>'Bisa Home Visit','desc'=>'Tutor kami siap datang ke rumah Anda kapan saja. Jadwal fleksibel, nyaman, dan tanpa perlu repot.'],
        ['icon'=>'💻','title'=>'Kelas Online & Offline','desc'=>'Sistem belajar interaktif yang disesuaikan dengan gaya belajar masing-masing siswa. Belajar itu menyenangkan!'],
        ['icon'=>'📊','title'=>'Evaluasi Rutin Bulanan','desc'=>'Evaluasi rutin, progress terpantau, laporan bulanan. Nilai meningkat signifikan — dijamin atau kami ulang!'],
        ['icon'=>'💬','title'=>'Konsultasi 24/7','desc'=>'Bantuan belajar & konsultasi 24/7 via WhatsApp. Kami selalu ada untuk mendukung perjalanan belajar Anda.'],
    ];

    $programFallback = collect([
        (object)['nama'=>'Bimbel Mata Pelajaran','deskripsi'=>'Bimbingan semua mata pelajaran sekolah dengan metode efektif dan menyenangkan.','badge'=>'SEMUA JENJANG','icon'=>'📚','image'=>'https://images.unsplash.com/photo-1509869175650-a1d97972541a?auto=format&fit=crop&w=600&q=80'],
        (object)['nama'=>'Persiapan Ujian','deskripsi'=>'Persiapan UTS, UAS & Ujian Sekolah agar nilai meningkat pesat dan lulus terbaik.','badge'=>'SMP - SMA','icon'=>'📝','image'=>'https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?auto=format&fit=crop&w=600&q=80'],
        (object)['nama'=>'Persiapan Tes & SBMPTN','deskripsi'=>'Persiapan masuk sekolah favorit, PTN, CPNS & tes lainnya secara intensif.','badge'=>'INTENSIF','icon'=>'🏆','image'=>'https://images.unsplash.com/photo-1434030216411-0b793f4b4173?auto=format&fit=crop&w=600&q=80'],
        (object)['nama'=>'Kursus Bahasa','deskripsi'=>'Inggris, Jepang, Mandarin, Arab — tingkatkan kemampuan bahasa Anda bersama kami.','badge'=>'SEMUA LEVEL','icon'=>'🌐','image'=>'https://images.unsplash.com/photo-1543269865-cbf427effbad?auto=format&fit=crop&w=600&q=80'],
        (object)['nama'=>'Kursus Komputer','deskripsi'=>'Microsoft Office, Desain Grafis, Programming — teknologi terkini untuk karir masa depan.','badge'=>'POPULER 🔥','icon'=>'💻','image'=>'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&w=600&q=80'],
        (object)['nama'=>'Kursus Akuntansi','deskripsi'=>'Akuntansi dasar hingga profesional, perpajakan & keuangan untuk mahasiswa dan karyawan.','badge'=>'TERBARU ✨','icon'=>'📊','image'=>'https://images.unsplash.com/photo-1554224155-6726b3ff858f?auto=format&fit=crop&w=600&q=80'],
    ]);
    $programs = $dbPrograms->isNotEmpty() ? $dbPrograms : $programFallback;

    $testisFallback = collect([
        (object)['text'=>'Belajar di SCI sangat menyenangkan! Tutor menjelaskan dengan cara yang mudah dipahami dan nilai saya meningkat pesat. Sangat merekomendasikan untuk semua!','name'=>'Aisyah Rahma','role'=>'Siswa SMA · Matematika','photo'=>null,'initial'=>'A'],
        (object)['text'=>'Program persiapan ujian di SCI sangat membantu. Akhirnya lolos ke kampus impian! Materinya lengkap banget dan tutornya super sabar dan profesional!','name'=>'Ricky Pratama','role'=>'Mahasiswa · Persiapan SBMPTN','photo'=>null,'initial'=>'R'],
        (object)['text'=>'Kursus akuntansi di SCI sangat bermanfaat untuk tugas kuliah dan persiapan kerja. Tutornya sabar, materi lengkap, dan nilai kuliah naik drastis!','name'=>'Dinda Lestari','role'=>'Mahasiswi · Akuntansi','photo'=>null,'initial'=>'D'],
        (object)['text'=>'Anakku belajar lebih semangat sejak ikut SCI. Metodenya menyenangkan dan hasilnya terlihat nyata dalam waktu singkat. Terima kasih SCI!','name'=>'Budi Santoso','role'=>'Orang Tua Siswa · Jakarta','photo'=>null,'initial'=>'B'],
    ]);
    $testis = $dbTestis->isNotEmpty() ? $dbTestis : $testisFallback;

    $galeriPhotos = [
        'https://images.unsplash.com/photo-1580582932707-520aed937b7b?auto=format&fit=crop&w=600&q=80',
        'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?auto=format&fit=crop&w=600&q=80',
        'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=600&q=80',
        'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?auto=format&fit=crop&w=600&q=80',
        'https://images.unsplash.com/photo-1427504494785-3a9ca7044f45?auto=format&fit=crop&w=600&q=80',
        'https://images.unsplash.com/photo-1509869175650-a1d97972541a?auto=format&fit=crop&w=600&q=80',
    ];
    $tutorGrads = ['#c84ddf','#7c3aed','#2563eb','#10b981','#f97316','#f43f5e','#14b8a6','#f59e0b'];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Smart Center Indonesia — Lembaga bimbingan belajar, kursus, dan les privat terbaik di Indonesia. Melayani TK hingga umum dengan tutor profesional, metode modern, dan hasil terukur.">
    <title>Smart Center Indonesia | Bimbel & Kursus Terbaik #1 di Indonesia</title>
    <meta property="og:title" content="Smart Center Indonesia | Bimbel & Kursus Terbaik #1">
    <meta property="og:description" content="Lembaga bimbingan belajar, kursus, dan les privat terbaik di Indonesia.">
    <meta name="theme-color" content="#7c3aed">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Playfair+Display:ital,wght@0,700;0,800;1,700;1,800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
    *,*::before,*::after{margin:0;padding:0;box-sizing:border-box}
    :root{
        --pri:#7c3aed;
        --pri-dark:#5b21b6;
        --pri-light:#a855f7;
        --gold:#f6af23;
        --gold-dark:#d97706;
        --lavender:#f5f0ff;
        --lavender2:#ede9fe;
        --dark:#1e1245;
        --dark2:#2e1065;
        --white:#ffffff;
        --text:#1e1245;
        --text-muted:#6b7280;
        --border:rgba(124,58,237,.12);
        --font:'Inter',system-ui,sans-serif;
        --serif:'Playfair Display',Georgia,serif;
    }
    html{scroll-behavior:smooth}
    body{font-family:var(--font);color:var(--text);background:#fff;overflow-x:hidden}
    .italic-accent{font-family:var(--serif);font-style:italic;color:var(--pri-light)}

    /* ── NAVBAR ── */
    .sci-nav{position:fixed;top:0;left:0;right:0;z-index:1000;background:#fff;border-bottom:1px solid rgba(0,0,0,.07);box-shadow:0 2px 12px rgba(0,0,0,.06)}
    .sci-nav-inner{max-width:1200px;margin:0 auto;padding:0 1.5rem;display:flex;align-items:center;justify-content:space-between;height:68px}
    .sci-logo{display:flex;align-items:center;gap:10px;text-decoration:none}
    .sci-logo-sq{width:40px;height:40px;border-radius:10px;background:linear-gradient(135deg,var(--pri-dark),var(--pri-light));display:flex;align-items:center;justify-content:center;color:#fff;font-weight:900;font-size:14px;letter-spacing:-.5px;flex-shrink:0}
    .sci-logo-text{line-height:1.1}
    .sci-logo-text strong{display:block;font-size:.95rem;font-weight:800;color:var(--dark);letter-spacing:-.02em}
    .sci-logo-text small{display:block;font-size:.72rem;font-weight:500;color:var(--pri-light)}
    .sci-nav-links{display:flex;align-items:center;gap:.25rem;list-style:none;margin:0;padding:0}
    .sci-nav-links a{color:#374151;text-decoration:none;font-size:.88rem;font-weight:600;padding:.5rem .9rem;border-radius:8px;transition:color .2s,background .2s}
    .sci-nav-links a:hover,.sci-nav-links a.active{color:var(--pri);background:rgba(124,58,237,.07)}
    .sci-nav-toggle{display:none;flex-direction:column;gap:5px;cursor:pointer;padding:6px;border:none;background:none}
    .sci-nav-toggle span{display:block;width:24px;height:2px;background:var(--dark);border-radius:2px;transition:.3s}
    .sci-nav-toggle.open span:nth-child(1){transform:translateY(7px) rotate(45deg)}
    .sci-nav-toggle.open span:nth-child(2){opacity:0;transform:scaleX(0)}
    .sci-nav-toggle.open span:nth-child(3){transform:translateY(-7px) rotate(-45deg)}
    .sci-mobile-menu{display:none;position:fixed;inset:0;background:rgba(30,18,69,.97);z-index:999;flex-direction:column;align-items:center;justify-content:center;gap:1.5rem}
    .sci-mobile-menu.open{display:flex}
    .sci-mobile-menu a{color:rgba(255,255,255,.88);text-decoration:none;font-size:1.3rem;font-weight:700}
    .sci-mobile-menu a:hover{color:#fff}
    .sci-mobile-close{position:absolute;top:1.5rem;right:1.5rem;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.15);color:#fff;width:42px;height:42px;border-radius:50%;font-size:1.1rem;cursor:pointer;display:flex;align-items:center;justify-content:center}

    /* ── HERO ── */
    .sci-hero{position:relative;height:100vh;min-height:560px;overflow:hidden;display:flex;align-items:center;justify-content:center;margin-top:68px}
    .sci-hero-slides{position:absolute;inset:0;z-index:0}
    .sci-hero-slide{position:absolute;inset:0;background-size:cover;background-position:center;opacity:0;transition:opacity 1.4s ease-in-out}
    .sci-hero-slide.active{opacity:1}
    .sci-hero-overlay{position:absolute;inset:0;background:linear-gradient(to bottom,rgba(30,10,60,.75) 0%,rgba(80,20,120,.55) 50%,rgba(30,10,60,.75) 100%)}
    .sci-hero-content{position:relative;z-index:2;text-align:center;color:#fff;padding:2rem 1.5rem;max-width:800px}
    .sci-hero-title{font-size:clamp(2.2rem,5vw,3.8rem);font-weight:900;line-height:1.1;margin-bottom:1rem;text-shadow:0 2px 20px rgba(0,0,0,.3)}
    .sci-hero-sub{font-size:clamp(.9rem,1.5vw,1.1rem);color:rgba(255,255,255,.82);max-width:560px;margin:0 auto 2rem;line-height:1.7}
    .sci-hero-btns{display:flex;gap:1rem;flex-wrap:wrap;justify-content:center}
    .btn-hero-gold{display:inline-flex;align-items:center;gap:8px;padding:.85rem 2rem;border-radius:50px;font-size:.92rem;font-weight:700;color:#1a0a00;background:linear-gradient(135deg,var(--gold),#f8d07a);text-decoration:none;box-shadow:0 6px 24px rgba(246,175,35,.4);transition:.25s}
    .btn-hero-gold:hover{transform:translateY(-2px);box-shadow:0 10px 32px rgba(246,175,35,.55);color:#1a0a00}
    .btn-hero-outline{display:inline-flex;align-items:center;gap:8px;padding:.85rem 1.8rem;border-radius:50px;font-size:.92rem;font-weight:600;color:#fff;border:2px solid rgba(255,255,255,.4);background:rgba(255,255,255,.1);backdrop-filter:blur(8px);text-decoration:none;transition:.25s}
    .btn-hero-outline:hover{background:rgba(255,255,255,.2);border-color:rgba(255,255,255,.7);color:#fff}
    .sci-hero-scroll{position:absolute;bottom:2.5rem;left:50%;transform:translateX(-50%);z-index:3;display:flex;flex-direction:column;align-items:center;gap:6px;color:rgba(255,255,255,.55);font-size:.7rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase}
    .scroll-line{width:1px;height:40px;background:linear-gradient(to bottom,rgba(255,255,255,.5),transparent);animation:scrollln 1.5s ease-in-out infinite}
    @keyframes scrollln{0%,100%{opacity:.4;transform:scaleY(1)}50%{opacity:1;transform:scaleY(1.2)}}
    .sci-hero-dots{position:absolute;bottom:5rem;left:50%;transform:translateX(-50%);display:flex;gap:8px;z-index:3}
    .sci-hero-dot{width:8px;height:8px;border-radius:50%;background:rgba(255,255,255,.35);border:none;cursor:pointer;padding:0;transition:.3s}
    .sci-hero-dot.active{background:#fff;width:26px;border-radius:4px}
    .sci-hero-arrow{position:absolute;top:50%;transform:translateY(-50%);z-index:3;width:44px;height:44px;border-radius:50%;background:rgba(255,255,255,.15);backdrop-filter:blur(8px);border:1px solid rgba(255,255,255,.25);color:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:1.1rem;transition:.2s}
    .sci-hero-arrow:hover{background:rgba(255,255,255,.28)}
    .sci-hero-arrow.prev{left:2rem}
    .sci-hero-arrow.next{right:2rem}
    @media(max-width:576px){.sci-hero-arrow{display:none}}

    /* ── TICKER ── */
    .sci-ticker{background:var(--gold);padding:.65rem 0;overflow:hidden;white-space:nowrap}
    .sci-ticker-inner{display:inline-flex;animation:ticker-scroll 28s linear infinite}
    .sci-ticker-inner:hover{animation-play-state:paused}
    .sci-ticker-item{display:inline-flex;align-items:center;gap:.5rem;font-size:.83rem;font-weight:700;color:#1a0a00;padding:0 2rem}
    .sci-ticker-item::after{content:'|';opacity:.3;margin-left:2rem}
    @keyframes ticker-scroll{0%{transform:translateX(0)}100%{transform:translateX(-50%)}}

    /* ── SECTION COMMONS ── */
    .lp-section{padding:5rem 0}
    .lp-section-lavender{background:var(--lavender)}
    .lp-section-dark{background:linear-gradient(135deg,#2e1065 0%,#4c1d95 50%,#7c3aed 100%)}
    .container-lp{max-width:1160px;margin:0 auto;padding:0 1.5rem}
    .section-eyebrow-line{display:flex;align-items:center;gap:.5rem;color:var(--pri);font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;margin-bottom:.75rem;justify-content:center}
    .section-eyebrow-line::before{content:'';display:block;width:28px;height:2px;background:var(--pri);border-radius:2px}
    .section-title-lg{font-size:clamp(1.9rem,3.5vw,2.8rem);font-weight:900;color:var(--dark);line-height:1.2;margin-bottom:1rem}
    .section-subtitle-lp{font-size:.98rem;color:var(--text-muted);line-height:1.7;max-width:580px;margin:0 auto}

    /* ── BADGE PILLS ── */
    .pill-badge{display:inline-flex;align-items:center;gap:5px;background:var(--lavender2);border:1px solid rgba(124,58,237,.2);border-radius:50px;padding:5px 14px;font-size:.75rem;font-weight:600;color:var(--pri);margin-bottom:1rem}

    /* ── TENTANG SECTION ── */
    .feature-grid{display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-top:1.75rem}
    .feature-item{display:flex;align-items:center;gap:.85rem;background:#fff;border:1.5px solid rgba(124,58,237,.1);border-radius:14px;padding:1rem 1.25rem;transition:.2s}
    .feature-item:hover{border-color:rgba(124,58,237,.3);box-shadow:0 4px 18px rgba(124,58,237,.08)}
    .feature-icon{width:42px;height:42px;border-radius:12px;background:linear-gradient(135deg,var(--pri-dark),var(--pri-light));display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0;color:#fff}
    .feature-label{font-size:.88rem;font-weight:700;color:var(--dark)}

    /* ── JENJANG ── */
    .jenjang-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:1.5rem;margin-top:3rem}
    .jenjang-card{background:#fff;border-radius:20px;padding:2rem 1.5rem;text-align:center;border:1.5px solid rgba(124,58,237,.1);box-shadow:0 4px 20px rgba(0,0,0,.05);transition:.3s;text-decoration:none;color:inherit;display:block}
    .jenjang-card:hover{transform:translateY(-6px);box-shadow:0 16px 44px rgba(124,58,237,.15);border-color:rgba(124,58,237,.25);color:inherit}
    .jenjang-photo{width:90px;height:90px;border-radius:50%;object-fit:cover;margin:0 auto 1rem;border:3px solid rgba(124,58,237,.15);display:block}
    .jenjang-label{font-size:1.5rem;font-weight:900;color:var(--dark);margin-bottom:.25rem}
    .jenjang-desc{font-size:.82rem;color:var(--text-muted);margin-bottom:1rem}
    .jenjang-link{font-size:.8rem;font-weight:700;color:var(--pri);display:inline-flex;align-items:center;gap:4px}

    /* ── CARI GURU ── */
    .cari-guru-section{background:linear-gradient(135deg,#2e1065 0%,#4c1d95 50%,#7c3aed 100%);padding:5rem 0}
    .cari-guru-eyebrow{color:rgba(255,255,255,.6);font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;display:flex;align-items:center;gap:.5rem;margin-bottom:.75rem;justify-content:center}
    .cari-guru-eyebrow::before{content:'';display:block;width:28px;height:2px;background:var(--gold);border-radius:2px}
    .cari-guru-title{font-size:clamp(1.9rem,3.5vw,2.8rem);font-weight:900;color:#fff;line-height:1.2;margin-bottom:.75rem}
    .cari-guru-sub{font-size:.97rem;color:rgba(255,255,255,.7);max-width:520px;margin:0 auto 2.5rem;line-height:1.7}
    .search-box{background:rgba(255,255,255,.1);backdrop-filter:blur(16px);border:1px solid rgba(255,255,255,.18);border-radius:20px;padding:1.75rem;display:grid;grid-template-columns:1fr 1fr 1fr auto;gap:1rem;align-items:end;margin-bottom:1.5rem}
    .search-field label{display:block;font-size:.72rem;font-weight:700;color:rgba(255,255,255,.7);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.5rem}
    .search-field label i{margin-right:.3rem;color:var(--gold)}
    .search-field select{width:100%;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.2);border-radius:12px;padding:.65rem 1rem;font-size:.88rem;font-weight:500;color:#fff;appearance:none;cursor:pointer;outline:none;transition:.2s}
    .search-field select:focus{border-color:rgba(255,255,255,.5);background:rgba(255,255,255,.18)}
    .search-field select option{color:#1e1245;background:#fff}
    .btn-cari{display:inline-flex;align-items:center;gap:.5rem;background:var(--gold);border:none;border-radius:12px;padding:.7rem 1.5rem;font-size:.9rem;font-weight:800;color:#1a0a00;cursor:pointer;transition:.25s;white-space:nowrap;width:100%}
    .btn-cari:hover{background:var(--gold-dark);transform:translateY(-1px);box-shadow:0 6px 20px rgba(246,175,35,.4)}
    .trust-badges{display:flex;flex-wrap:wrap;justify-content:center;gap:1.5rem}
    .trust-badge{display:flex;align-items:center;gap:.4rem;color:rgba(255,255,255,.75);font-size:.8rem;font-weight:600}
    .trust-badge i{color:var(--gold)}

    /* ── PROGRAM ── */
    .program-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem;margin-top:3rem}
    .program-card{background:#fff;border-radius:18px;overflow:hidden;border:1.5px solid rgba(0,0,0,.06);box-shadow:0 4px 18px rgba(0,0,0,.06);transition:.3s;text-decoration:none;color:inherit;display:block}
    .program-card:hover{transform:translateY(-6px);box-shadow:0 16px 44px rgba(0,0,0,.12);color:inherit}
    .program-img{width:100%;height:200px;object-fit:cover}
    .program-body{padding:1.5rem}
    .program-badge{display:inline-flex;align-items:center;gap:4px;background:var(--lavender2);border-radius:50px;padding:4px 12px;font-size:.7rem;font-weight:700;color:var(--pri);margin-bottom:.75rem;text-transform:uppercase;letter-spacing:.04em}
    .program-title{font-size:1.05rem;font-weight:800;color:var(--dark);margin-bottom:.45rem}
    .program-desc{font-size:.84rem;color:var(--text-muted);line-height:1.65;margin-bottom:1rem}
    .program-link{font-size:.82rem;font-weight:700;color:var(--pri);display:inline-flex;align-items:center;gap:5px}
    .program-link i{font-size:.65rem}

    /* ── KEUNGGULAN ── */
    .keunggulan-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:1.25rem;margin-top:3rem}
    .keunggulan-card{background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.12);border-radius:18px;padding:1.75rem 1.25rem;transition:.25s}
    .keunggulan-card:hover{background:rgba(255,255,255,.14);transform:translateY(-4px)}
    .keunggulan-emoji{font-size:2rem;margin-bottom:1rem;display:block}
    .keunggulan-title{font-size:.95rem;font-weight:800;color:#fff;margin-bottom:.6rem}
    .keunggulan-desc{font-size:.82rem;color:rgba(255,255,255,.68);line-height:1.65}

    /* ── TESTIMONI ── */
    .testi-slider-wrap{position:relative;overflow:hidden;margin-top:3rem}
    .testi-track{display:flex;gap:1.25rem;transition:transform .5s cubic-bezier(.22,1,.36,1);padding:.5rem 0}
    .testi-card{flex:0 0 320px;background:#fff;border-radius:18px;padding:1.75rem;border:1.5px solid rgba(124,58,237,.1);box-shadow:0 4px 20px rgba(0,0,0,.06)}
    .testi-stars{color:var(--gold);font-size:.85rem;margin-bottom:.75rem;display:flex;gap:2px}
    .testi-quote{font-size:3rem;color:rgba(124,58,237,.08);line-height:1;font-family:var(--serif);margin-bottom:.25rem}
    .testi-text{font-size:.87rem;color:#374151;line-height:1.7;margin-bottom:1.25rem}
    .testi-author{display:flex;align-items:center;gap:.75rem}
    .testi-avatar{width:44px;height:44px;border-radius:50%;object-fit:cover;flex-shrink:0}
    .testi-avatar-fallback{width:44px;height:44px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:800;color:#fff;font-size:1.1rem;flex-shrink:0}
    .testi-name{font-size:.88rem;font-weight:700;color:var(--dark)}
    .testi-role{font-size:.75rem;color:var(--pri);font-weight:600}
    .testi-verified{font-size:.72rem;color:#10b981;font-weight:600;display:flex;align-items:center;gap:3px}
    .slider-controls{display:flex;justify-content:center;gap:.75rem;margin-top:2rem}
    .slider-btn{width:40px;height:40px;border-radius:50%;border:1.5px solid rgba(124,58,237,.25);background:#fff;color:var(--pri);cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:.9rem;transition:.2s}
    .slider-btn:hover{background:var(--pri);color:#fff;border-color:var(--pri)}

    /* ── GALERI ── */
    .galeri-slider-wrap{position:relative;overflow:hidden;margin-top:3rem}
    .galeri-track{display:flex;gap:1.25rem;transition:transform .5s cubic-bezier(.22,1,.36,1)}
    .galeri-item{flex:0 0 280px;height:200px;border-radius:18px;overflow:hidden;position:relative;cursor:pointer}
    .galeri-item img{width:100%;height:100%;object-fit:cover;transition:transform .4s}
    .galeri-item:hover img{transform:scale(1.06)}
    .galeri-overlay{position:absolute;inset:0;background:linear-gradient(to top,rgba(30,18,69,.65),transparent);opacity:0;transition:.3s;display:flex;align-items:flex-end;padding:1rem}
    .galeri-item:hover .galeri-overlay{opacity:1}
    .galeri-overlay span{color:#fff;font-size:.8rem;font-weight:600}

    /* ── TUTOR ── */
    .tutor-slider-wrap{position:relative;overflow:hidden;margin-top:3rem}
    .tutor-track{display:flex;gap:1.25rem;transition:transform .5s cubic-bezier(.22,1,.36,1)}
    .tutor-card{flex:0 0 180px;background:#fff;border-radius:18px;padding:1.5rem 1.25rem;text-align:center;border:1.5px solid rgba(124,58,237,.1);box-shadow:0 4px 16px rgba(0,0,0,.06);transition:.25s}
    .tutor-card:hover{transform:translateY(-4px);box-shadow:0 12px 32px rgba(124,58,237,.12)}
    .tutor-photo-wrap{position:relative;width:80px;height:80px;margin:0 auto 1rem}
    .tutor-photo{width:80px;height:80px;border-radius:50%;object-fit:cover;border:3px solid rgba(124,58,237,.15)}
    .tutor-photo-fb{width:80px;height:80px;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.6rem;font-weight:800}
    .tutor-star-badge{position:absolute;bottom:-2px;right:-2px;width:24px;height:24px;background:var(--gold);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.65rem;color:#1a0a00}
    .tutor-name{font-size:.87rem;font-weight:800;color:var(--dark);margin-bottom:.35rem}
    .tutor-subj{display:inline-block;background:var(--lavender2);border-radius:50px;padding:3px 10px;font-size:.72rem;font-weight:700;color:var(--pri);margin-bottom:.5rem}
    .tutor-rating{font-size:.78rem;color:var(--text-muted);display:flex;align-items:center;gap:3px;justify-content:center}
    .tutor-rating i{color:var(--gold);font-size:.65rem}
    .tutor-exp{font-size:.72rem;color:var(--text-muted)}

    /* ── FAQ + CONTACT ── */
    .faq-item{border:1.5px solid rgba(0,0,0,.08);border-radius:14px;margin-bottom:.75rem;overflow:hidden;transition:.2s}
    .faq-item:hover{border-color:rgba(124,58,237,.2)}
    .faq-q{display:flex;align-items:center;justify-content:space-between;padding:1rem 1.25rem;cursor:pointer;font-size:.9rem;font-weight:700;color:var(--dark)}
    .faq-q .faq-icon{width:28px;height:28px;border-radius:50%;border:1.5px solid rgba(124,58,237,.25);display:flex;align-items:center;justify-content:center;color:var(--pri);font-size:1rem;flex-shrink:0;transition:.3s}
    .faq-item.open .faq-icon{background:var(--pri);color:#fff;border-color:var(--pri);transform:rotate(45deg)}
    .faq-a{display:none;padding:.75rem 1.25rem 1.25rem;font-size:.87rem;color:var(--text-muted);line-height:1.7}
    .faq-item.open .faq-a{display:block}
    .contact-box{background:linear-gradient(135deg,#2e1065,#7c3aed);border-radius:22px;padding:2.25rem;color:#fff;height:100%}
    .contact-box h4{font-size:1.3rem;font-weight:800;margin-bottom:.4rem}
    .contact-box p{font-size:.85rem;opacity:.75;margin-bottom:1.5rem}
    .contact-field{margin-bottom:1rem}
    .contact-field label{display:block;font-size:.75rem;font-weight:700;color:rgba(255,255,255,.7);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.4rem}
    .contact-field input,.contact-field textarea{width:100%;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.2);border-radius:10px;padding:.65rem 1rem;font-size:.88rem;color:#fff;outline:none;transition:.2s;font-family:var(--font)}
    .contact-field input::placeholder,.contact-field textarea::placeholder{color:rgba(255,255,255,.4)}
    .contact-field input:focus,.contact-field textarea:focus{border-color:rgba(255,255,255,.5);background:rgba(255,255,255,.18)}
    .contact-field textarea{resize:none;height:80px}
    .btn-contact-send{width:100%;background:var(--gold);border:none;border-radius:12px;padding:.8rem;font-size:.9rem;font-weight:800;color:#1a0a00;cursor:pointer;transition:.25s;margin-top:.25rem;display:flex;align-items:center;justify-content:center;gap:.5rem}
    .btn-contact-send:hover{background:var(--gold-dark);transform:translateY(-1px);box-shadow:0 6px 20px rgba(246,175,35,.35)}

    /* ── CABANG ── */
    .cabang-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:1.5rem;margin-top:3rem}
    .cabang-card{border-radius:18px;overflow:hidden;position:relative;height:200px;cursor:pointer;text-decoration:none;display:block}
    .cabang-card img{width:100%;height:100%;object-fit:cover;transition:transform .4s}
    .cabang-card:hover img{transform:scale(1.05)}
    .cabang-card-overlay{position:absolute;inset:0;background:linear-gradient(to top,rgba(30,18,69,.75) 0%,rgba(0,0,0,.1) 50%);display:flex;flex-direction:column;justify-content:flex-end;padding:1.5rem}
    .cabang-city{font-size:1.1rem;font-weight:800;color:#fff;margin-bottom:.2rem}
    .cabang-tagline{font-size:.8rem;color:rgba(255,255,255,.75);margin-bottom:.75rem}
    .cabang-btn{display:inline-flex;align-items:center;gap:.4rem;background:rgba(255,255,255,.18);backdrop-filter:blur(8px);border:1px solid rgba(255,255,255,.3);border-radius:8px;padding:.4rem .85rem;font-size:.75rem;font-weight:700;color:#fff;text-decoration:none;transition:.2s}
    .cabang-btn:hover{background:rgba(255,255,255,.3);color:#fff}
    .cabang-fallback{display:flex;flex-direction:column;align-items:flex-start;justify-content:flex-end;background:linear-gradient(135deg,var(--pri-dark),var(--pri-light));padding:1.5rem}

    /* ── FOOTER ── */
    .sci-footer{background:linear-gradient(180deg,#1e1245 0%,#0f0825 100%);padding:4rem 0 2rem;color:rgba(255,255,255,.75)}
    .footer-grid{display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:3rem;margin-bottom:3rem}
    .footer-brand-desc{font-size:.85rem;line-height:1.7;margin:1.25rem 0 1.5rem;max-width:280px}
    .footer-social{display:flex;gap:.6rem}
    .footer-social a{width:36px;height:36px;border-radius:10px;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.1);color:rgba(255,255,255,.7);display:flex;align-items:center;justify-content:center;font-size:.9rem;text-decoration:none;transition:.2s}
    .footer-social a:hover{background:var(--pri);color:#fff;border-color:var(--pri)}
    .footer-col-title{font-size:.85rem;font-weight:800;color:#fff;margin-bottom:1rem;text-transform:uppercase;letter-spacing:.06em}
    .footer-links{list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:.5rem}
    .footer-links a{color:rgba(255,255,255,.6);text-decoration:none;font-size:.84rem;transition:.2s}
    .footer-links a:hover{color:#fff}
    .footer-contact{display:flex;flex-direction:column;gap:.65rem}
    .footer-contact-item{display:flex;align-items:center;gap:.6rem;font-size:.83rem;color:rgba(255,255,255,.65)}
    .footer-contact-item i{color:var(--pri-light);flex-shrink:0}
    .footer-bottom{border-top:1px solid rgba(255,255,255,.08);padding-top:1.75rem;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.75rem;font-size:.8rem;color:rgba(255,255,255,.4)}
    .footer-bottom a{color:rgba(255,255,255,.4);text-decoration:none}
    .footer-bottom a:hover{color:rgba(255,255,255,.7)}

    /* ── WA FLOAT ── */
    .wa-float{position:fixed;bottom:2rem;right:2rem;z-index:500;width:56px;height:56px;border-radius:50%;background:#25d366;color:#fff;display:flex;align-items:center;justify-content:center;font-size:1.5rem;text-decoration:none;box-shadow:0 6px 24px rgba(37,211,102,.45);transition:.25s}
    .wa-float:hover{transform:scale(1.1);color:#fff;box-shadow:0 10px 32px rgba(37,211,102,.55)}
    .scroll-top{position:fixed;bottom:5.5rem;right:2rem;z-index:500;width:44px;height:44px;border-radius:50%;background:#fff;border:1.5px solid rgba(0,0,0,.1);color:var(--pri);display:none;align-items:center;justify-content:center;font-size:.95rem;cursor:pointer;box-shadow:0 4px 16px rgba(0,0,0,.1);transition:.2s}
    .scroll-top:hover{background:var(--pri);color:#fff}
    .scroll-top.visible{display:flex}

    /* ── REVEAL ANIMATIONS ── */
    .reveal{opacity:0;transform:translateY(28px);transition:opacity .6s cubic-bezier(.22,1,.36,1),transform .6s cubic-bezier(.22,1,.36,1)}
    .reveal.visible{opacity:1;transform:none}
    .reveal-d1{transition-delay:.08s}
    .reveal-d2{transition-delay:.16s}
    .reveal-d3{transition-delay:.24s}
    .reveal-d4{transition-delay:.32s}

    /* ── RESPONSIVE ── */
    @media(max-width:900px){
        .sci-nav-links{display:none}
        .sci-nav-toggle{display:flex}
        .search-box{grid-template-columns:1fr;gap:.75rem}
        .keunggulan-grid{grid-template-columns:repeat(2,1fr)}
        .jenjang-grid{grid-template-columns:repeat(2,1fr)}
        .program-grid{grid-template-columns:repeat(2,1fr)}
        .footer-grid{grid-template-columns:1fr 1fr;gap:2rem}
    }
    @media(max-width:640px){
        .sci-hero{height:80vh}
        .jenjang-grid{grid-template-columns:repeat(2,1fr)}
        .program-grid{grid-template-columns:1fr}
        .keunggulan-grid{grid-template-columns:1fr}
        .cabang-grid{grid-template-columns:1fr}
        .footer-grid{grid-template-columns:1fr}
        .feature-grid{grid-template-columns:1fr}
        .lp-section{padding:3.5rem 0}
    }
    @media(max-width:480px){
        .jenjang-grid{grid-template-columns:repeat(2,1fr)}
    }
    </style>
</head>
<body>

{{-- ── NAVBAR ── --}}
<nav class="sci-nav" id="sciNav">
    <div class="sci-nav-inner">
        <a href="{{ url('/') }}" class="sci-logo">
            <div class="sci-logo-sq">SCI</div>
            <div class="sci-logo-text">
                <strong>Smart Center</strong>
                <small>Indonesia</small>
            </div>
        </a>
        <ul class="sci-nav-links">
            <li><a href="#tentang">Tentang</a></li>
            <li><a href="#program">Program</a></li>
            <li><a href="#keunggulan">Keunggulan</a></li>
            <li><a href="#testimoni">Testimoni</a></li>
            <li><a href="#tutor">Tutor</a></li>
            <li><a href="#cabang">Cabang</a></li>
        </ul>
        <button class="sci-nav-toggle" id="navToggle" aria-label="Menu">
            <span></span><span></span><span></span>
        </button>
    </div>
</nav>

{{-- Mobile Menu --}}
<div class="sci-mobile-menu" id="mobileMenu">
    <button class="sci-mobile-close" id="mobileClose"><i class="bi bi-x"></i></button>
    <a href="#tentang" onclick="closeMobile()">Tentang</a>
    <a href="#program" onclick="closeMobile()">Program</a>
    <a href="#keunggulan" onclick="closeMobile()">Keunggulan</a>
    <a href="#testimoni" onclick="closeMobile()">Testimoni</a>
    <a href="#tutor" onclick="closeMobile()">Tutor</a>
    <a href="#cabang" onclick="closeMobile()">Cabang</a>
    <a href="{{ route('login') }}" onclick="closeMobile()" style="color:var(--gold)">Masuk Portal</a>
</div>

{{-- ── HERO ── --}}
<section class="sci-hero" id="beranda">
    <div class="sci-hero-slides">
        @foreach($heroSlides as $i => $slide)
        <div class="sci-hero-slide {{ $i===0?'active':'' }}" style="background-image:url('{{ $slide['img'] }}')"></div>
        @endforeach
        <div class="sci-hero-overlay"></div>
    </div>

    <button class="sci-hero-arrow prev" id="heroPrev"><i class="bi bi-chevron-left"></i></button>
    <button class="sci-hero-arrow next" id="heroNext"><i class="bi bi-chevron-right"></i></button>

    <div class="sci-hero-content">
        <h1 class="sci-hero-title">{{ $ls('hero.title','Wujudkan Mimpi,') }}<br><span class="italic-accent">{{ $ls('hero.subtitle','Raih Prestasi!') }}</span></h1>
        <p class="sci-hero-sub">{{ $ls('hero.description','Smart Center Indonesia — lembaga bimbingan belajar, kursus, dan les privat berbasis offline & online. Tutor profesional, metode modern, hasil terukur untuk semua jenjang dari TK hingga umum.') }}</p>
        <div class="sci-hero-btns">
            <a href="{{ route('register') }}" class="btn-hero-gold"><i class="bi bi-rocket-takeoff-fill"></i> Daftar Sekarang</a>
            <a href="#program" class="btn-hero-outline"><i class="bi bi-grid-3x3-gap-fill"></i> Lihat Program</a>
        </div>
    </div>

    <div class="sci-hero-dots" id="heroDots">
        @foreach($heroSlides as $i => $slide)
        <button class="sci-hero-dot {{ $i===0?'active':'' }}" data-slide="{{ $i }}"></button>
        @endforeach
    </div>

    <div class="sci-hero-scroll">
        <div class="scroll-line"></div>
        SCROLL
    </div>
</section>

{{-- ── TICKER ── --}}
<div class="sci-ticker">
    @php
        $tickerItems = [
            '📚 Daftar sekarang & dapatkan sesi konsultasi GRATIS!',
            '⚡ Promo Paket Hemat: Beli 10 sesi gratis 2 sesi ekstra',
            '⭐ Rating bintang 5 dari 10.000+ siswa di seluruh Indonesia',
            '🎓 500+ Tutor Bersertifikat siap mengajar di kotamu',
            '🏆 Lembaga Bimbel #1 Terpercaya di Indonesia sejak 2010',
            '📈 95% siswa mengalami peningkatan nilai dalam 3 bulan',
        ];
    @endphp
    <div class="sci-ticker-inner">
        @foreach($tickerItems as $item)
        <span class="sci-ticker-item">{{ $item }}</span>
        @endforeach
        @foreach($tickerItems as $item)
        <span class="sci-ticker-item">{{ $item }}</span>
        @endforeach
    </div>
</div>

{{-- ── TENTANG KAMI ── --}}
<section class="lp-section" id="tentang">
    <div class="container-lp">
        <div class="row align-items-center g-5">
            <div class="col-lg-5 reveal">
                <div style="display:flex;flex-wrap:wrap;gap:.5rem;margin-bottom:1rem">
                    <span class="pill-badge">🏫 Tentang Kami</span>
                    <span class="pill-badge">📅 Sejak 2010</span>
                    <span class="pill-badge">✅ ISO Certified</span>
                </div>
                <h2 class="section-title-lg" style="text-align:left">Tentang <span class="italic-accent">Smart Center Indonesia</span></h2>
                <p style="font-size:.95rem;color:#374151;line-height:1.8;margin-bottom:.75rem">
                    {{ $ls('about.description','Smart Center Indonesia (SCI) adalah lembaga pendidikan yang bergerak di bidang bimbingan belajar, kursus, dan les privat (1 guru 1 siswa) berbasis offline dan online yang berkomitmen menjadi lembaga terbaik nomor 1 di Indonesia.') }}
                </p>
                <p style="font-size:.95rem;color:#374151;line-height:1.8;margin-bottom:1.5rem">
                    Dengan metode pembelajaran efektif, pengajar berpengalaman, serta pendekatan personal, SCI hadir sebagai solusi pendidikan terpercaya. <em style="color:var(--pri);font-family:var(--serif)">"Wujudkan mimpi, raih prestasi!"</em>
                </p>
                <div class="row g-2 text-center" style="margin-top:1.5rem">
                    <div class="col-4">
                        <div style="font-size:1.8rem;font-weight:900;color:var(--pri)">{{ number_format($stats['students']) }}+</div>
                        <div style="font-size:.75rem;color:var(--text-muted);font-weight:600">Siswa Aktif</div>
                    </div>
                    <div class="col-4">
                        <div style="font-size:1.8rem;font-weight:900;color:var(--pri)">{{ number_format($stats['teachers']) }}+</div>
                        <div style="font-size:.75rem;color:var(--text-muted);font-weight:600">Tutor Aktif</div>
                    </div>
                    <div class="col-4">
                        <div style="font-size:1.8rem;font-weight:900;color:var(--pri)">{{ $branches->count() ?: '150' }}+</div>
                        <div style="font-size:.75rem;color:var(--text-muted);font-weight:600">Cabang</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 reveal reveal-d1">
                <div class="feature-grid">
                    @php
                    $features = [
                        ['icon'=>'bi bi-award-fill','label'=>'Tutor Bersertifikat'],
                        ['icon'=>'bi bi-house-heart-fill','label'=>'Bisa Home Visit'],
                        ['icon'=>'bi bi-display-fill','label'=>'Kelas Online & Offline'],
                        ['icon'=>'bi bi-clipboard2-data-fill','label'=>'Evaluasi Rutin Bulanan'],
                        ['icon'=>'bi bi-headset','label'=>'Konsultasi 24/7'],
                        ['icon'=>'bi bi-graph-up-arrow','label'=>'Target & Hasil Terukur'],
                    ];
                    @endphp
                    @foreach($features as $f)
                    <div class="feature-item">
                        <div class="feature-icon"><i class="{{ $f['icon'] }}"></i></div>
                        <div class="feature-label">{{ $f['label'] }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── JENJANG PENDIDIKAN ── --}}
<section class="lp-section lp-section-lavender" id="jenjang">
    <div class="container-lp text-center">
        <div class="reveal">
            <div class="section-eyebrow-line">LAYANAN KAMI</div>
            <h2 class="section-title-lg">Jenjang <span class="italic-accent">Pendidikan</span></h2>
            <p class="section-subtitle-lp">Kami melayani semua jenjang dari TK hingga umum dengan pendekatan personal yang tepat untuk setiap tahap perkembangan.</p>
        </div>
        <div class="jenjang-grid">
            @foreach($jenjangItems as $i => $j)
            <a href="#program" class="jenjang-card reveal reveal-d{{ $i+1 }}">
                <img src="{{ $j['img'] }}" alt="{{ $j['label'] }}" class="jenjang-photo" loading="lazy">
                <div class="jenjang-label">{{ $j['label'] }}</div>
                <div class="jenjang-desc">{{ $j['desc'] }}</div>
                <span class="jenjang-link">Lihat Detail <i class="bi bi-arrow-right"></i></span>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ── CARI GURU ── --}}
<section class="cari-guru-section" id="cari-guru">
    <div class="container-lp text-center">
        <div class="reveal">
            <div class="cari-guru-eyebrow">TEMUKAN PENGAJAR TERBAIK</div>
            <h2 class="cari-guru-title">Cari Guru <span style="font-family:var(--serif);font-style:italic;color:var(--gold)">Terbaik</span>, Secepat Klik</h2>
            <p class="cari-guru-sub">Temukan tutor privat terbaik di kotamu — pilih berdasarkan mata pelajaran, lokasi, dan metode belajar yang kamu inginkan.</p>
        </div>
        <div class="search-box reveal reveal-d1">
            <div class="search-field">
                <label><i class="bi bi-geo-alt-fill"></i> KOTA / LOKASI</label>
                <select>
                    <option>Semua Kota</option>
                    @foreach($branches as $b)
                    <option>{{ $b->nama ?? $b->name ?? 'Cabang' }}</option>
                    @endforeach
                </select>
            </div>
            <div class="search-field">
                <label><i class="bi bi-journal-bookmark-fill"></i> MATA PELAJARAN</label>
                <select>
                    <option>Semua Mata Pelajaran</option>
                    <option>Matematika</option><option>Bahasa Inggris</option>
                    <option>IPA / Fisika</option><option>Kimia</option>
                    <option>Biologi</option><option>Bahasa Indonesia</option>
                    <option>Komputer</option><option>Akuntansi</option>
                </select>
            </div>
            <div class="search-field">
                <label><i class="bi bi-laptop-fill"></i> METODE BELAJAR</label>
                <select>
                    <option>Semua Metode</option>
                    <option>Online</option><option>Offline</option><option>Home Visit</option>
                </select>
            </div>
            <div>
                <a href="{{ route('register') }}" class="btn-cari">🔍 Cari Guru</a>
            </div>
        </div>
        <div class="trust-badges reveal reveal-d2">
            <span class="trust-badge"><i class="bi bi-patch-check-fill"></i> 500+ Tutor Bersertifikat</span>
            <span class="trust-badge"><i class="bi bi-lightning-fill"></i> Respon dalam 1 Jam</span>
            <span class="trust-badge"><i class="bi bi-shield-lock-fill"></i> Aman & Terpercaya</span>
            <span class="trust-badge"><i class="bi bi-trophy-fill"></i> Garansi Hasil Belajar</span>
        </div>
    </div>
</section>

{{-- ── PROGRAM UNGGULAN ── --}}
<section class="lp-section" id="program">
    <div class="container-lp">
        <div class="text-center reveal">
            <div class="section-eyebrow-line">PROGRAM SCI</div>
            <h2 class="section-title-lg">Program <span class="italic-accent">Unggulan</span></h2>
            <p class="section-subtitle-lp">Pilih program yang sesuai kebutuhan Anda bersama para tutor terbaik kami — klik kartu untuk melihat detail lengkap.</p>
        </div>
        <div class="program-grid">
            @php
                $badgeList = ['SEMUA JENJANG','SMP - SMA','INTENSIF','SEMUA LEVEL','POPULER 🔥','TERBARU ✨'];
                $progImgs  = [
                    'https://images.unsplash.com/photo-1509869175650-a1d97972541a?auto=format&fit=crop&w=600&q=80',
                    'https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?auto=format&fit=crop&w=600&q=80',
                    'https://images.unsplash.com/photo-1434030216411-0b793f4b4173?auto=format&fit=crop&w=600&q=80',
                    'https://images.unsplash.com/photo-1543269865-cbf427effbad?auto=format&fit=crop&w=600&q=80',
                    'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&w=600&q=80',
                    'https://images.unsplash.com/photo-1554224155-6726b3ff858f?auto=format&fit=crop&w=600&q=80',
                ];
            @endphp
            @foreach($programs as $i => $prog)
            @php
                $hasImg = !empty($prog->image) && (str_starts_with($prog->image,'http') || file_exists(public_path('storage/'.$prog->image)));
                $imgSrc = $hasImg ? (str_starts_with($prog->image,'http') ? $prog->image : asset('storage/'.$prog->image)) : $progImgs[$i % count($progImgs)];
                $badge  = $prog->badge ?? $badgeList[$i % count($badgeList)];
                $nama   = $prog->nama ?? $prog->name ?? 'Program SCI';
                $desc   = $prog->deskripsi ?? $prog->description ?? '';
            @endphp
            <a href="{{ route('register') }}" class="program-card reveal reveal-d{{ ($i%3)+1 }}">
                <img src="{{ $imgSrc }}" alt="{{ $nama }}" class="program-img" loading="lazy">
                <div class="program-body">
                    <span class="program-badge">{{ $badge }}</span>
                    <div class="program-title">{{ $nama }}</div>
                    @if($desc)<p class="program-desc">{{ Str::limit($desc,90) }}</p>@endif
                    <span class="program-link">Lihat Detail <i class="bi bi-arrow-down-circle-fill"></i></span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ── KEUNGGULAN SCI ── --}}
<section class="lp-section lp-section-dark" id="keunggulan">
    <div class="container-lp text-center">
        <div class="reveal">
            <div class="section-eyebrow-line" style="color:rgba(255,255,255,.6);justify-content:center">
                <span style="background:var(--gold);height:2px;width:28px;border-radius:2px;display:inline-block"></span>
                MENGAPA SCI?
            </div>
            <h2 class="section-title-lg" style="color:#fff">Keunggulan <span style="font-family:var(--serif);font-style:italic;color:var(--pri-light)">SCI</span></h2>
            <p class="section-subtitle-lp" style="color:rgba(255,255,255,.65)">Lima pilar yang membuat SCI menjadi pilihan terpercaya jutaan keluarga Indonesia selama 14+ tahun.</p>
        </div>
        <div class="keunggulan-grid">
            @foreach($keunggulanItems as $i => $k)
            <div class="keunggulan-card reveal reveal-d{{ ($i%4)+1 }}">
                <span class="keunggulan-emoji">{{ $k['icon'] }}</span>
                <div class="keunggulan-title">{{ $k['title'] }}</div>
                <div class="keunggulan-desc">{{ $k['desc'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ── TESTIMONI SISWA ── --}}
<section class="lp-section lp-section-lavender" id="testimoni">
    <div class="container-lp">
        <div class="text-center reveal">
            <div class="section-eyebrow-line">KATA MEREKA</div>
            <h2 class="section-title-lg">Testimoni <span class="italic-accent">Siswa</span></h2>
            <p class="section-subtitle-lp">Dengarkan cerita sukses ribuan siswa yang telah mempercayai SCI sebagai mitra belajar mereka.</p>
        </div>
        <div class="testi-slider-wrap reveal reveal-d1">
            <div class="testi-track" id="testiTrack">
                @php
                    $testiColors = ['linear-gradient(135deg,#7c3aed,#a855f7)','linear-gradient(135deg,#10b981,#059669)','linear-gradient(135deg,#f97316,#dc2626)','linear-gradient(135deg,#2563eb,#1d4ed8)','linear-gradient(135deg,#ec4899,#db2777)','linear-gradient(135deg,#f59e0b,#d97706)'];
                @endphp
                @foreach($testis as $i => $t)
                <div class="testi-card">
                    <div class="testi-stars">
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                    </div>
                    <div class="testi-quote">"</div>
                    <p class="testi-text">{{ $t->text ?? $t->comment ?? '' }}</p>
                    <div class="testi-author">
                        @if(!empty($t->photo) && file_exists(public_path('storage/'.$t->photo)))
                            <img src="{{ asset('storage/'.$t->photo) }}" alt="{{ $t->name }}" class="testi-avatar">
                        @else
                            <div class="testi-avatar-fallback" style="background:{{ $testiColors[$i % count($testiColors)] }}">{{ strtoupper(substr($t->name??'S',0,1)) }}</div>
                        @endif
                        <div>
                            <div class="testi-name">{{ $t->name }}</div>
                            <div class="testi-role">{{ $t->role ?? 'Siswa SCI' }}</div>
                            <div class="testi-verified"><i class="bi bi-patch-check-fill"></i> Siswa Terverifikasi</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <div class="slider-controls">
            <button class="slider-btn" id="testiPrev"><i class="bi bi-chevron-left"></i></button>
            <button class="slider-btn" id="testiNext"><i class="bi bi-chevron-right"></i></button>
        </div>
    </div>
</section>

{{-- ── GALERI KEGIATAN ── --}}
<section class="lp-section" id="galeri">
    <div class="container-lp">
        <div class="text-center reveal">
            <div class="section-eyebrow-line">DOKUMENTASI</div>
            <h2 class="section-title-lg">Galeri <span class="italic-accent">Kegiatan</span></h2>
            <p class="section-subtitle-lp">Momen belajar menyenangkan bersama siswa dan tutor terbaik SCI di seluruh Indonesia.</p>
        </div>
        <div class="galeri-slider-wrap reveal reveal-d1">
            <div class="galeri-track" id="galeriTrack">
                @foreach($galeriPhotos as $photo)
                <div class="galeri-item">
                    <img src="{{ $photo }}" alt="Galeri SCI" loading="lazy">
                    <div class="galeri-overlay"><span>Kegiatan SCI</span></div>
                </div>
                @endforeach
            </div>
        </div>
        <div class="slider-controls">
            <button class="slider-btn" id="galeriPrev"><i class="bi bi-chevron-left"></i></button>
            <button class="slider-btn" id="galeriNext"><i class="bi bi-chevron-right"></i></button>
        </div>
    </div>
</section>

{{-- ── TUTOR TERBAIK ── --}}
<section class="lp-section lp-section-lavender" id="tutor">
    <div class="container-lp">
        <div class="text-center reveal">
            <div class="section-eyebrow-line">TIM PENGAJAR</div>
            <h2 class="section-title-lg">Tutor <span class="italic-accent">Terbaik</span> Kami</h2>
            <p class="section-subtitle-lp">Dilatih secara profesional dan berpengalaman di bidangnya masing-masing untuk memberikan hasil terbaik bagi setiap siswa.</p>
        </div>
        <div class="tutor-slider-wrap reveal reveal-d1">
            <div class="tutor-track" id="tutorTrack">
                @php
                    $tutorFallback = collect([
                        (object)['name'=>'Ms. Anisa Putri','subjects'=>['Matematika'],'photo'=>null,'experience'=>7],
                        (object)['name'=>'Mr. Budi Santoso','subjects'=>['Fisika'],'photo'=>null,'experience'=>9],
                        (object)['name'=>'Ms. Cindy Lestari','subjects'=>['Bahasa Inggris'],'photo'=>null,'experience'=>6],
                        (object)['name'=>'Mr. Dimas Arif','subjects'=>['Akuntansi'],'photo'=>null,'experience'=>8],
                        (object)['name'=>'Ms. Rina Wulandari','subjects'=>['Kimia'],'photo'=>null,'experience'=>6],
                        (object)['name'=>'Mr. Hendra Wijaya','subjects'=>['Biologi'],'photo'=>null,'experience'=>5],
                    ]);
                    $tutorSource = $tutors->isNotEmpty() ? $tutors : $tutorFallback;
                @endphp
                @foreach($tutorSource as $i => $tutor)
                @php
                    $subj  = is_array($tutor->subjects) ? ($tutor->subjects[0] ?? 'Tutor') : ($tutor->subjects ?? 'Tutor');
                    $init  = strtoupper(substr($tutor->name??'T',0,1));
                    $hasP  = !empty($tutor->photo) && file_exists(public_path('storage/'.$tutor->photo));
                    $exp   = $tutor->experience ?? rand(5,12);
                    $rating = number_format(4.7 + ($i%3)*0.1, 1);
                @endphp
                <div class="tutor-card">
                    <div class="tutor-photo-wrap">
                        @if($hasP)
                            <img src="{{ asset('storage/'.$tutor->photo) }}" alt="{{ $tutor->name }}" class="tutor-photo" loading="lazy">
                        @else
                            <div class="tutor-photo-fb" style="background:{{ $tutorGrads[$i % count($tutorGrads)] }}">{{ $init }}</div>
                        @endif
                        <div class="tutor-star-badge">⭐</div>
                    </div>
                    <div class="tutor-name">{{ $tutor->name }}</div>
                    <span class="tutor-subj">{{ $subj }}</span>
                    <div class="tutor-rating">
                        <i class="bi bi-star-fill"></i> {{ $rating }}
                    </div>
                    <div class="tutor-exp">{{ $exp }} Tahun Pengalaman</div>
                </div>
                @endforeach
            </div>
        </div>
        <div class="slider-controls">
            <button class="slider-btn" id="tutorPrev"><i class="bi bi-chevron-left"></i></button>
            <button class="slider-btn" id="tutorNext"><i class="bi bi-chevron-right"></i></button>
        </div>
    </div>
</section>

{{-- ── FAQ + KONTAK ── --}}
<section class="lp-section" id="kontak">
    <div class="container-lp">
        <div class="text-center reveal">
            <div class="section-eyebrow-line">BANTUAN & KONTAK</div>
            <h2 class="section-title-lg">Pertanyaan <span style="font-family:var(--serif);font-style:normal;color:var(--dark)">&</span> <span class="italic-accent">Hubungi Kami</span></h2>
            <p class="section-subtitle-lp">Punya pertanyaan atau ingin bergabung? Kami siap membantu Anda kapan saja.</p>
        </div>
        <div class="row g-4 mt-2">
            <div class="col-lg-6 reveal">
                @php
                $faqs = [
                    ['q'=>'Bagaimana cara mendaftar di SCI?','a'=>'Pendaftaran bisa dilakukan secara online melalui website ini, atau langsung datang ke cabang SCI terdekat. Proses mudah dan cepat, tidak ada biaya pendaftaran.'],
                    ['q'=>'Apakah bisa datang ke rumah?','a'=>'Ya! SCI menyediakan layanan Home Visit di mana tutor kami akan datang langsung ke rumah Anda. Jadwal sangat fleksibel sesuai kebutuhan Anda.'],
                    ['q'=>'Berapa biaya les privat di SCI?','a'=>'Biaya bervariasi tergantung jenjang, mata pelajaran, dan metode belajar. Hubungi kami untuk mendapatkan penawaran terbaik sesuai kebutuhan Anda.'],
                    ['q'=>'Bagaimana sistem pembayaran di SCI?','a'=>'Pembayaran bisa per sesi, per paket, atau per bulan. Kami menerima berbagai metode pembayaran termasuk transfer bank dan e-wallet.'],
                    ['q'=>'Apakah ada garansi hasil belajar?','a'=>'Ya! SCI memberikan garansi peningkatan nilai. Jika tidak ada kemajuan dalam waktu yang disepakati, kami siap mengulang sesi tanpa biaya tambahan.'],
                ];
                @endphp
                @foreach($faqs as $i => $faq)
                <div class="faq-item" onclick="toggleFaq(this)">
                    <div class="faq-q">
                        {{ $faq['q'] }}
                        <span class="faq-icon"><i class="bi bi-plus"></i></span>
                    </div>
                    <div class="faq-a">{{ $faq['a'] }}</div>
                </div>
                @endforeach
            </div>
            <div class="col-lg-6 reveal reveal-d1">
                <div class="contact-box">
                    <h4>Kirim Pesan 📩</h4>
                    <p>Isi form di bawah ini, kami akan segera menghubungi Anda.</p>
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="contact-field">
                                <label>NAMA LENGKAP</label>
                                <input type="text" placeholder="Nama Anda">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="contact-field">
                                <label>NO. WHATSAPP</label>
                                <input type="tel" placeholder="08xx-xxxx-xxxx">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="contact-field">
                                <label>PESAN</label>
                                <textarea placeholder="Tulis pesan atau pertanyaan Anda..."></textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <a href="https://wa.me/{{ $waMain }}?text={{ urlencode('Halo SCI! Saya ingin konsultasi tentang program bimbel/kursus.') }}"
                               target="_blank" rel="noopener" class="btn-contact-send">
                                <i class="bi bi-whatsapp"></i> Kirim Pesan ✨
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── CABANG SCI ── --}}
<section class="lp-section lp-section-lavender" id="cabang">
    <div class="container-lp">
        <div class="text-center reveal">
            <div class="section-eyebrow-line">HADIR DI SELURUH INDONESIA</div>
            <h2 class="section-title-lg">Cabang SCI <span class="italic-accent">Indonesia</span></h2>
            <p class="section-subtitle-lp">Dengan {{ $branches->count() ?: '150' }}+ cabang di berbagai kota, SCI selalu dekat dengan Anda dan keluarga.</p>
        </div>
        @php
            $cityImages = [
                'https://images.unsplash.com/photo-1588668214407-6ea9a6d8c272?auto=format&fit=crop&w=600&q=80',
                'https://images.unsplash.com/photo-1569367178534-dfcd8ef28f7b?auto=format&fit=crop&w=600&q=80',
                'https://images.unsplash.com/photo-1555899434-94d1368aa7af?auto=format&fit=crop&w=600&q=80',
                'https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?auto=format&fit=crop&w=600&q=80',
                'https://images.unsplash.com/photo-1477959858617-67f85cf4f1df?auto=format&fit=crop&w=600&q=80',
                'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=600&q=80',
            ];
            $displayBranches = $branches->take(6);
            $fallbackBranches = collect([
                (object)['nama'=>'Riau','alamat'=>'Jasa Les Privat Riau'],
                (object)['nama'=>'Sumatera Barat','alamat'=>'Jasa Les Privat Sumatera Barat'],
                (object)['nama'=>'Sumatera Utara','alamat'=>'Jasa Les Privat Sumatera Utara'],
                (object)['nama'=>'DKI Jakarta','alamat'=>'Jasa Les Privat Jakarta'],
                (object)['nama'=>'Jawa Barat','alamat'=>'Jasa Les Privat Jawa Barat'],
                (object)['nama'=>'Jawa Timur','alamat'=>'Jasa Les Privat Jawa Timur'],
            ]);
            $branchShow = $displayBranches->isNotEmpty() ? $displayBranches : $fallbackBranches;
        @endphp
        <div class="cabang-grid reveal reveal-d1">
            @foreach($branchShow as $i => $branch)
            @php
                $branchName = $branch->nama ?? $branch->name ?? 'Cabang SCI';
                $branchDesc = $branch->alamat ?? $branch->address ?? 'Jasa Les Privat '.$branchName;
            @endphp
            <a href="https://wa.me/{{ $waMain }}?text={{ urlencode('Halo SCI, saya ingin tahu tentang cabang '.$branchName) }}"
               target="_blank" rel="noopener" class="cabang-card">
                <img src="{{ $cityImages[$i % count($cityImages)] }}" alt="{{ $branchName }}" loading="lazy">
                <div class="cabang-card-overlay">
                    <div class="cabang-city">{{ $branchName }}</div>
                    <div class="cabang-tagline">{{ $branchDesc }}</div>
                    <span class="cabang-btn">Lihat Detail</span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ── WA FLOAT ── --}}
<a href="https://wa.me/{{ $waMain }}?text={{ urlencode('Halo Smart Center Indonesia! Saya ingin konsultasi tentang program bimbel/kursus.') }}"
   class="wa-float" target="_blank" rel="noopener" aria-label="Chat WhatsApp">
    <i class="bi bi-whatsapp"></i>
</a>
<button class="scroll-top" id="scrollTopBtn" onclick="window.scrollTo({top:0,behavior:'smooth'})">
    <i class="bi bi-arrow-up"></i>
</button>

{{-- ── FOOTER ── --}}
<footer class="sci-footer">
    <div class="container-lp">
        <div class="footer-grid">
            <div>
                <a href="{{ url('/') }}" class="sci-logo" style="text-decoration:none">
                    <div class="sci-logo-sq">SCI</div>
                    <div class="sci-logo-text">
                        <strong style="color:#fff">Smart Center Indonesia</strong>
                        <small style="color:rgba(255,255,255,.5)">Wujudkan Mimpi, Raih Prestasi</small>
                    </div>
                </a>
                <p class="footer-brand-desc">Platform pendidikan modern untuk semua jenjang. Dari TK hingga profesional — kami selalu ada untuk mendukung perjalanan belajar Anda.</p>
                <div class="footer-social">
                    <a href="{{ $ls('footer.facebook','#') }}" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                    <a href="{{ $ls('footer.instagram','#') }}" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                    <a href="{{ $ls('footer.youtube','#') }}" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
                    <a href="https://wa.me/{{ $waMain }}" aria-label="WhatsApp"><i class="bi bi-whatsapp"></i></a>
                </div>
            </div>
            <div>
                <div class="footer-col-title">Navigasi</div>
                <ul class="footer-links">
                    <li><a href="#beranda">Beranda</a></li>
                    <li><a href="#tentang">Tentang Kami</a></li>
                    <li><a href="#program">Program</a></li>
                    <li><a href="#keunggulan">Keunggulan</a></li>
                    <li><a href="#testimoni">Testimoni</a></li>
                </ul>
            </div>
            <div>
                <div class="footer-col-title">Layanan</div>
                <ul class="footer-links">
                    <li><a href="#galeri">Galeri</a></li>
                    <li><a href="#tutor">Tutor</a></li>
                    <li><a href="#kontak">FAQ</a></li>
                    <li><a href="#cabang">Cabang</a></li>
                    <li><a href="#kontak">Kontak</a></li>
                </ul>
            </div>
            <div>
                <div class="footer-col-title">Kontak</div>
                <div class="footer-contact">
                    <div class="footer-contact-item"><i class="bi bi-telephone-fill"></i> {{ $ls('footer.phone','+62 853-3339-9210') }}</div>
                    <div class="footer-contact-item"><i class="bi bi-envelope-fill"></i> {{ $ls('footer.email','smartcenterindonesia@gmail.com') }}</div>
                    <div class="footer-contact-item"><i class="bi bi-clock-fill"></i> Senin–Sabtu (08.00–20.00)</div>
                    <div class="footer-contact-item"><i class="bi bi-geo-alt-fill"></i> {{ $branches->count() ?: '150' }}+ Cabang di Indonesia</div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© {{ date('Y') }} Smart Center Indonesia (SCI). All Rights Reserved.</p>
            <p>Made with ❤️ for Indonesian Education</p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
/* ── NAV ACTIVE ── */
const navLinks = document.querySelectorAll('.sci-nav-links a');
const sections = document.querySelectorAll('section[id]');
const navObs = new IntersectionObserver(entries => {
    entries.forEach(e => {
        if (e.isIntersecting) {
            navLinks.forEach(l => l.classList.remove('active'));
            const a = document.querySelector(`.sci-nav-links a[href="#${e.target.id}"]`);
            if (a) a.classList.add('active');
        }
    });
}, { threshold: 0.3, rootMargin: '-80px 0px -35% 0px' });
sections.forEach(s => navObs.observe(s));

/* ── MOBILE MENU ── */
const toggle  = document.getElementById('navToggle');
const mMenu   = document.getElementById('mobileMenu');
const mClose  = document.getElementById('mobileClose');
toggle.addEventListener('click', () => { mMenu.classList.toggle('open'); toggle.classList.toggle('open'); document.body.style.overflow = mMenu.classList.contains('open') ? 'hidden' : ''; });
mClose.addEventListener('click', closeMobile);
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeMobile(); });
function closeMobile() { mMenu.classList.remove('open'); toggle.classList.remove('open'); document.body.style.overflow = ''; }

/* ── HERO SLIDER ── */
(function() {
    const slides = document.querySelectorAll('.sci-hero-slide');
    const dots   = document.querySelectorAll('.sci-hero-dot');
    if (!slides.length) return;
    let cur = 0, timer;
    function goTo(n) {
        slides[cur].classList.remove('active'); dots[cur].classList.remove('active');
        cur = (n + slides.length) % slides.length;
        slides[cur].classList.add('active'); dots[cur].classList.add('active');
    }
    function start() { clearInterval(timer); timer = setInterval(() => goTo(cur+1), 6000); }
    dots.forEach(d => d.addEventListener('click', () => { clearInterval(timer); goTo(+d.dataset.slide); start(); }));
    document.getElementById('heroPrev').addEventListener('click', () => { clearInterval(timer); goTo(cur-1); start(); });
    document.getElementById('heroNext').addEventListener('click', () => { clearInterval(timer); goTo(cur+1); start(); });
    document.addEventListener('visibilitychange', () => document.hidden ? clearInterval(timer) : start());
    start();
})();

/* ── GENERIC SLIDER FACTORY ── */
function makeSlider(trackId, prevId, nextId, cardWidth) {
    const track = document.getElementById(trackId);
    if (!track) return;
    let pos = 0;
    const step = cardWidth + 20;
    function clamp() {
        const max = track.scrollWidth - track.parentElement.offsetWidth;
        pos = Math.max(0, Math.min(pos, max));
    }
    document.getElementById(prevId).addEventListener('click', () => { pos -= step * 2; clamp(); track.style.transform = `translateX(-${pos}px)`; });
    document.getElementById(nextId).addEventListener('click', () => { pos += step * 2; clamp(); track.style.transform = `translateX(-${pos}px)`; });
}
makeSlider('testiTrack', 'testiPrev', 'testiNext', 320);
makeSlider('galeriTrack', 'galeriPrev', 'galeriNext', 280);
makeSlider('tutorTrack', 'tutorPrev', 'tutorNext', 180);

/* ── FAQ TOGGLE ── */
function toggleFaq(el) {
    const isOpen = el.classList.contains('open');
    document.querySelectorAll('.faq-item.open').forEach(f => f.classList.remove('open'));
    if (!isOpen) el.classList.add('open');
}

/* ── SCROLL REVEAL ── */
const revObs = new IntersectionObserver(entries => {
    entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); revObs.unobserve(e.target); } });
}, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });
document.querySelectorAll('.reveal').forEach(el => revObs.observe(el));

/* ── SCROLL TOP ── */
const scrollBtn = document.getElementById('scrollTopBtn');
window.addEventListener('scroll', () => { scrollBtn.classList.toggle('visible', window.scrollY > 400); }, { passive: true });
</script>
</body>
</html>
