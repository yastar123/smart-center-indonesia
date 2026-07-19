@php
    $pageTitle = 'Artikel & Blog';
    // inherit landing page globals if available
    $lsAll    = isset($lsAll) ? $lsAll : \App\Models\LandingSetting::all()->keyBy('key');
    $ls       = fn(string $k, string $d='') => $lsAll[$k]->value ?? $d;
    $katLabel = ['tips'=>'Tips & Trik','berita'=>'Berita','akademik'=>'Akademik','promo'=>'Promo','lainnya'=>'Lainnya'];
    $katColor = ['tips'=>'#c84ddf','berita'=>'#2563eb','akademik'=>'#10b981','promo'=>'#f59e0b','lainnya'=>'#6b7280'];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artikel & Blog | Smart Center Indonesia</title>
    <meta name="description" content="Baca artikel terbaru seputar tips belajar, berita pendidikan, dan info program Smart Center Indonesia.">
    <meta name="theme-color" content="#260632">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *,*::before,*::after{margin:0;padding:0;box-sizing:border-box}
        :root{--primary:#c84ddf;--primary-dark:#68117e;--deep:#260632;--font-sans:'Inter',system-ui,sans-serif;--font-display:'Plus Jakarta Sans','Inter',sans-serif;--ease-out:cubic-bezier(.22,1,.36,1)}
        html{scroll-behavior:smooth}
        body{font-family:var(--font-sans);background:#fafafa;color:#1e0828;overflow-x:hidden}
        /* NAV */
        .lp-nav{position:fixed;top:0;left:0;right:0;z-index:1000;background:white;border-bottom:1px solid rgba(0,0,0,.06);box-shadow:0 2px 16px rgba(0,0,0,.06)}
        .nav-inner{display:flex;align-items:center;justify-content:space-between;max-width:1280px;margin:0 auto;padding:0 2rem;height:64px}
        .nav-brand{display:flex;align-items:center;gap:10px;text-decoration:none}
        .nav-brand-icon{width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,var(--primary-dark),var(--primary));display:flex;align-items:center;justify-content:center;font-size:14px;color:white;font-weight:900;flex-shrink:0;box-shadow:0 4px 12px rgba(200,77,223,.35)}
        .nav-brand-text{font-family:var(--font-display);font-weight:800;font-size:1rem;color:var(--deep);letter-spacing:-.02em;line-height:1.15}
        .nav-brand-text small{display:block;font-size:.62rem;font-weight:500;color:#6b7280;letter-spacing:.02em}
        .btn-nav-login{padding:.45rem 1.2rem;border-radius:10px;font-size:.87rem;font-weight:600;color:var(--deep);border:1.5px solid rgba(38,6,50,.15);background:transparent;text-decoration:none;transition:.2s}
        .btn-nav-login:hover{color:var(--primary-dark);border-color:var(--primary)}
        .btn-nav-register{padding:.48rem 1.25rem;border-radius:10px;font-size:.87rem;font-weight:700;color:white;background:linear-gradient(135deg,var(--primary-dark),var(--primary));text-decoration:none;border:none;display:inline-block}
        /* HERO */
        .artikel-hero{background:linear-gradient(135deg,#260632 0%,#461256 55%,#c84ddf 100%);padding:7rem 0 4rem;margin-top:64px;position:relative;overflow:hidden}
        .artikel-hero::after{content:'';position:absolute;right:-100px;top:-100px;width:400px;height:400px;border-radius:50%;background:rgba(255,255,255,.04);pointer-events:none}
        /* FILTER BAR */
        .filter-bar{background:white;border-bottom:1px solid #f0e6f5;position:sticky;top:64px;z-index:100;padding:.875rem 0}
        .kat-pill{display:inline-flex;align-items:center;gap:5px;padding:.35rem 1rem;border-radius:50px;font-size:.82rem;font-weight:600;background:#f5f0fa;color:#6b5878;border:2px solid transparent;text-decoration:none;transition:.2s;cursor:pointer;white-space:nowrap}
        .kat-pill:hover,.kat-pill.active{background:var(--deep);color:white;border-color:var(--deep)}
        /* CARDS */
        .articles-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:1.75rem}
        @media(max-width:900px){.articles-grid{grid-template-columns:repeat(2,1fr)}}
        @media(max-width:580px){.articles-grid{grid-template-columns:1fr}.filter-bar .d-flex{flex-wrap:nowrap;overflow-x:auto;scrollbar-width:none;-webkit-overflow-scrolling:touch}.filter-bar .d-flex::-webkit-scrollbar{display:none}}
        .art-card{background:white;border-radius:18px;overflow:hidden;box-shadow:0 2px 16px rgba(38,6,50,.07);transition:transform .25s,box-shadow .25s;display:flex;flex-direction:column;text-decoration:none;color:inherit}
        .art-card:hover{transform:translateY(-5px);box-shadow:0 12px 36px rgba(104,17,126,.14);color:inherit}
        .art-thumb{height:200px;overflow:hidden;flex-shrink:0;position:relative}
        .art-thumb img{width:100%;height:100%;object-fit:cover;transition:transform .5s}
        .art-card:hover .art-thumb img{transform:scale(1.06)}
        .art-kat{position:absolute;top:12px;left:12px;font-size:.7rem;font-weight:700;padding:3px 11px;border-radius:50px;color:white}
        .art-body{padding:1.25rem;flex:1;display:flex;flex-direction:column}
        .art-title{font-family:var(--font-display);font-size:1rem;font-weight:800;color:var(--deep);line-height:1.35;margin-bottom:.5rem;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
        .art-desc{font-size:.83rem;color:#6b5878;line-height:1.65;flex:1;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden}
        .art-meta{display:flex;align-items:center;gap:1rem;margin-top:.875rem;padding-top:.75rem;border-top:1px solid #f0e6f5;font-size:.75rem;color:#9c8aaa}
        /* PAGINATION */
        .page-link{color:var(--deep);border-radius:8px!important;margin:0 2px;border-color:#e5e7eb}
        .page-item.active .page-link{background:var(--deep);border-color:var(--deep)}
        /* EMPTY */
        .empty-state{text-align:center;padding:5rem 2rem;color:#9c8aaa}
        /* FOOTER MINI */
        .mini-footer{background:var(--deep);color:rgba(255,255,255,.5);text-align:center;padding:1.5rem;font-size:.8rem;margin-top:4rem}
    </style>
</head>
<body>
{{-- NAV --}}
<nav class="lp-nav">
    <div class="nav-inner">
        <a href="{{ url('/') }}" class="nav-brand">
            <div class="nav-brand-icon"><i class="bi bi-mortarboard-fill"></i></div>
            <div class="nav-brand-text">Smart Center<small>Indonesia</small></div>
        </a>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ url('/') }}" class="btn-nav-login"><i class="bi bi-house me-1"></i>Beranda</a>
            <a href="{{ route('login') }}" class="btn-nav-register">Masuk</a>
        </div>
    </div>
</nav>

{{-- HERO --}}
<section class="artikel-hero">
    <div class="container">
        <div class="text-center text-white">
            <span style="display:inline-flex;align-items:center;gap:6px;font-size:.8rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;opacity:.8;margin-bottom:1rem"><i class="bi bi-newspaper"></i> Blog & Artikel</span>
            <h1 style="font-family:var(--font-display);font-size:clamp(2rem,5vw,3rem);font-weight:900;line-height:1.15;margin-bottom:1rem">Tips, Berita & <em style="font-style:normal;color:#e8a0f5">Inspirasi Belajar</em></h1>
            <p style="opacity:.75;max-width:520px;margin:0 auto;font-size:.95rem;line-height:1.7">Temukan artikel seputar tips belajar efektif, berita pendidikan, dan info program Smart Center Indonesia.</p>
        </div>
    </div>
</section>

{{-- FILTER BAR --}}
<div class="filter-bar">
    <div class="container">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('articles.index') }}" class="kat-pill {{ !request('kategori') ? 'active' : '' }}">Semua</a>
            @foreach(['tips'=>'Tips & Trik','berita'=>'Berita','akademik'=>'Akademik','promo'=>'Promo','lainnya'=>'Lainnya'] as $k => $l)
            <a href="{{ route('articles.index', ['kategori' => $k]) }}" class="kat-pill {{ request('kategori') === $k ? 'active' : '' }}">{{ $l }}</a>
            @endforeach
        </div>
    </div>
</div>

{{-- CONTENT --}}
<div class="container" style="padding-top:2.5rem;padding-bottom:2rem">

    @if($articles->isEmpty())
    <div class="empty-state">
        <i class="bi bi-newspaper" style="font-size:3.5rem;opacity:.25"></i>
        <p class="mt-3 fw-semibold">Belum ada artikel yang dipublikasikan</p>
        <a href="{{ url('/') }}" class="btn btn-outline-secondary mt-2 rounded-pill">Kembali ke Beranda</a>
    </div>
    @else
    <div class="articles-grid">
        @foreach($articles as $a)
        <a href="{{ route('articles.show', $a->slug) }}" class="art-card">
            <div class="art-thumb">
                <img src="{{ $a->thumbnail_url }}" alt="{{ $a->judul }}" loading="lazy">
                <span class="art-kat" style="background:{{ $katColor[$a->kategori] }}">{{ $katLabel[$a->kategori] }}</span>
            </div>
            <div class="art-body">
                <div class="art-title">{{ $a->judul }}</div>
                @if($a->ringkasan)
                <div class="art-desc">{{ $a->ringkasan }}</div>
                @endif
                <div class="art-meta">
                    <span><i class="bi bi-person me-1"></i>{{ $a->penulis?->name ?? '–' }}</span>
                    <span><i class="bi bi-calendar3 me-1"></i>{{ $a->published_at?->translatedFormat('d M Y') ?? '–' }}</span>
                    <span><i class="bi bi-eye me-1"></i>{{ $a->views }}</span>
                </div>
            </div>
        </a>
        @endforeach
    </div>

    {{-- PAGINATION --}}
    @if($articles->hasPages())
    <div class="d-flex justify-content-center mt-5">{{ $articles->appends(request()->query())->links() }}</div>
    @endif
    @endif
</div>

<div class="mini-footer">&copy; {{ date('Y') }} Smart Center Indonesia. All Rights Reserved.</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
