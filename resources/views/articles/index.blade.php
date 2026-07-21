@php
    $pageTitle = 'Artikel & Blog';
    // inherit landing page globals if available
    $lsAll    = isset($lsAll) ? $lsAll : \App\Models\LandingSetting::all()->keyBy('key');
    $ls       = fn(string $k, string $d='') => $lsAll[$k]->value ?? $d;
    $waMain   = \App\Models\LandingWaNumber::primaryNumber($ls('footer.wa_number', '628001234567'));
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
        .container{max-width:1280px}
        .container-lp{max-width:1280px;margin:0 auto;padding:0 1.2rem}
        /* NAV */
        .lp-nav{position:fixed;top:0;left:0;right:0;z-index:1000;background:white;border-bottom:1px solid rgba(0,0,0,.06);box-shadow:0 2px 16px rgba(0,0,0,.06)}
        .nav-inner{display:flex;align-items:center;justify-content:space-between;max-width:1280px;margin:0 auto;padding:0 2rem;height:64px;gap:1rem}
        .nav-brand{display:flex;align-items:center;gap:10px;text-decoration:none;flex-shrink:0}
        .nav-brand-icon{width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,var(--primary-dark),var(--primary));display:flex;align-items:center;justify-content:center;font-size:14px;color:white;font-weight:900;flex-shrink:0;box-shadow:0 4px 12px rgba(200,77,223,.35)}
        .nav-brand-text{font-family:var(--font-display);font-weight:800;font-size:1rem;color:var(--deep);letter-spacing:-.02em;line-height:1.15}
        .nav-brand-text small{display:block;font-size:.62rem;font-weight:500;color:#6b7280;letter-spacing:.02em}
        .nav-links{display:flex;align-items:center;gap:.15rem;list-style:none;margin:0;padding:0}
        .nav-link-item{color:#374151;text-decoration:none;font-size:.87rem;font-weight:500;padding:.45rem .9rem;border-radius:8px;transition:color .2s, background .2s}
        .nav-link-item:hover{color:var(--primary-dark);background:rgba(200,77,223,.07)}
        .nav-link-item.active{color:var(--primary-dark);font-weight:700}
        .nav-cta{display:flex;align-items:center;gap:.6rem}
        .btn-nav-login{padding:.45rem 1.2rem;border-radius:10px;font-size:.87rem;font-weight:600;color:var(--deep);border:1.5px solid rgba(38,6,50,.15);background:transparent;text-decoration:none;transition:.2s}
        .btn-nav-login:hover{color:var(--primary-dark);border-color:var(--primary);background:rgba(200,77,223,.06)}
        .btn-nav-register{padding:.48rem 1.25rem;border-radius:10px;font-size:.87rem;font-weight:700;color:white;background:linear-gradient(135deg,var(--primary-dark),var(--primary));text-decoration:none;border:none;display:inline-block;box-shadow:0 4px 14px rgba(200,77,223,.35)}
        .nav-toggle{display:none;flex-direction:column;gap:5px;cursor:pointer;padding:6px;background:none;border:none}
        .nav-toggle span{display:block;width:24px;height:2px;background:var(--deep);border-radius:2px;transition:.3s cubic-bezier(.4,0,.2,1)}
        .nav-toggle.open span:nth-child(1){transform:translateY(7px) rotate(45deg)}
        .nav-toggle.open span:nth-child(2){opacity:0;transform:scaleX(0)}
        .nav-toggle.open span:nth-child(3){transform:translateY(-7px) rotate(-45deg)}
        .mobile-menu{display:flex;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(38,6,50,.97);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);z-index:999;flex-direction:column;align-items:center;justify-content:center;gap:1.5rem;opacity:0;transform:scale(.96);visibility:hidden;pointer-events:none;transition:opacity .35s var(--ease-out), transform .35s var(--ease-out), visibility .35s}
        .mobile-menu.open{opacity:1;transform:scale(1);visibility:visible;pointer-events:auto}
        .mobile-menu a{text-decoration:none;color:rgba(255,255,255,.85);font-size:1.35rem;font-family:var(--font-display);font-weight:700;letter-spacing:-.02em}
        .mobile-menu .mobile-close{position:absolute;top:1.5rem;right:1.5rem;width:42px;height:42px;border-radius:50%;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.12);color:white;display:flex;align-items:center;justify-content:center}
        .mobile-divider{width:40px;height:2px;background:rgba(255,255,255,.1);border-radius:2px;margin:.25rem 0}
        /* HERO */
        .artikel-hero{background:linear-gradient(135deg,#260632 0%,#461256 55%,#c84ddf 100%);padding:7rem 0 4rem;margin-top:64px;position:relative;overflow:hidden}
        .artikel-hero::after{content:'';position:absolute;right:-100px;top:-100px;width:400px;height:400px;border-radius:50%;background:rgba(255,255,255,.04);pointer-events:none}
        /* FILTER BAR */
        .filter-bar{background:white;border-bottom:1px solid #f0e6f5;position:sticky;top:64px;z-index:100;padding:.875rem 0}
        .filter-bar .d-flex{gap:.5rem;flex-wrap:wrap}
        .kat-pill{display:inline-flex;align-items:center;gap:5px;padding:.35rem 1rem;border-radius:50px;font-size:.82rem;font-weight:600;background:#f5f0fa;color:#6b5878;border:2px solid transparent;text-decoration:none;transition:.2s;cursor:pointer;white-space:nowrap}
        .kat-pill:hover,.kat-pill.active{background:var(--deep);color:white;border-color:var(--deep)}
        /* CARDS */
        .articles-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:1.75rem}
        .art-card{background:white;border-radius:18px;overflow:hidden;box-shadow:0 2px 16px rgba(38,6,50,.07);transition:transform .25s,box-shadow .25s;display:flex;flex-direction:column;text-decoration:none;color:inherit}
        .art-card:hover{transform:translateY(-5px);box-shadow:0 12px 36px rgba(104,17,126,.14);color:inherit}
        .art-thumb{height:200px;overflow:hidden;flex-shrink:0;position:relative}
        .art-thumb img{width:100%;height:100%;object-fit:cover;transition:transform .5s}
        .art-card:hover .art-thumb img{transform:scale(1.06)}
        .art-kat{position:absolute;top:12px;left:12px;font-size:.7rem;font-weight:700;padding:3px 11px;border-radius:50px;color:white}
        .art-body{padding:1.25rem;flex:1;display:flex;flex-direction:column}
        .art-title{font-family:var(--font-display);font-size:1rem;font-weight:800;color:var(--deep);line-height:1.35;margin-bottom:.5rem;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
        .art-desc{font-size:.83rem;color:#6b5878;line-height:1.65;flex:1;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden}
        .art-meta{display:flex;align-items:center;gap:1rem;margin-top:.875rem;padding-top:.75rem;border-top:1px solid #f0e6f5;font-size:.75rem;color:#9c8aaa;flex-wrap:wrap}
        /* PAGINATION */
        .page-link{color:var(--deep);border-radius:8px!important;margin:0 2px;border-color:#e5e7eb}
        .page-item.active .page-link{background:var(--deep);border-color:var(--deep)}
        /* EMPTY */
        .empty-state{text-align:center;padding:5rem 2rem;color:#9c8aaa}
        /* FOOTER */
        .footer{background:linear-gradient(180deg,#260632 0%,#1a0425 100%);color:rgba(255,255,255,.6);margin-top:4rem}
        .footer-grid{display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:3rem;padding:4rem 0 2rem;border-bottom:1px solid rgba(255,255,255,.07)}
        .footer-brand-desc{font-size:.85rem;line-height:1.7;color:rgba(255,255,255,.5);margin-top:1rem;max-width:280px}
        .footer-social{display:flex;gap:8px;margin-top:1.25rem}
        .footer-social a{width:36px;height:36px;border-radius:10px;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.08);display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,.6);font-size:.95rem;text-decoration:none;transition:.2s}
        .footer-social a:hover{background:var(--primary);border-color:var(--primary);color:white}
        .footer-col-title{font-family:var(--font-display);font-size:.85rem;font-weight:700;color:white;margin-bottom:1rem;letter-spacing:-.01em}
        .footer-links{list-style:none;display:flex;flex-direction:column;gap:8px;padding:0;margin:0}
        .footer-links a{font-size:.82rem;color:rgba(255,255,255,.5);text-decoration:none;transition:color .2s}
        .footer-links a:hover{color:var(--primary)}
        .footer-bottom{padding:1.5rem 0 2rem;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem}
        .footer-bottom p{font-size:.78rem;margin:0}
        .footer-bottom-links{display:flex;gap:1.5rem}
        .footer-bottom-links a{font-size:.78rem;color:rgba(255,255,255,.4);text-decoration:none;transition:color .2s}
        .footer-bottom-links a:hover{color:var(--primary)}
        @media (max-width: 1100px){.nav-links{display:none}.nav-cta .btn-nav-register:nth-child(2),.nav-cta .btn-nav-register:first-child{display:none}.nav-toggle{display:flex !important}}
        @media (max-width: 900px){.articles-grid{grid-template-columns:repeat(2,1fr)}.footer-grid{grid-template-columns:1fr 1fr;gap:2rem}}
        @media (max-width: 580px){.nav-inner{padding:0 1rem}.articles-grid{grid-template-columns:1fr}.filter-bar .d-flex{flex-wrap:nowrap;overflow-x:auto;scrollbar-width:none;-webkit-overflow-scrolling:touch}.filter-bar .d-flex::-webkit-scrollbar{display:none}.footer-grid{grid-template-columns:1fr;gap:1.5rem}.footer-bottom{flex-direction:column;text-align:center}.artikel-hero{padding:6rem 0 3rem}.art-meta{gap:.5rem}}
    </style>
</head>
<body>
{{-- NAV --}}
<nav class="lp-nav" id="navbar">
    <div class="nav-inner">
        <a href="{{ url('/') }}" class="nav-brand">
            <div class="nav-brand-icon"><i class="bi bi-mortarboard-fill"></i></div>
            <div class="nav-brand-text">Smart Center<small>Indonesia</small></div>
        </a>

        <ul class="nav-links">
            <li><a href="{{ url('/') }}#tentang" class="nav-link-item">Tentang</a></li>
            <li><a href="{{ url('/') }}#program" class="nav-link-item">Program</a></li>
            <li><a href="{{ url('/') }}#mengapa-sci" class="nav-link-item">Keunggulan</a></li>
            <li><a href="{{ url('/') }}#testimonials" class="nav-link-item">Testimoni</a></li>
            <li><a href="{{ url('/') }}#tutor" class="nav-link-item">Tutor</a></li>
            <li><a href="{{ url('/') }}#cabang" class="nav-link-item">Cabang</a></li>
            <li><a href="{{ route('articles.index') }}" class="nav-link-item active">Artikel</a></li>
        </ul>

        <div class="nav-cta">
            <a href="{{ route('login') }}" class="btn-nav-login">Masuk</a>
            <a href="{{ route('public.teacher-registration.create') }}" class="btn-nav-register">Daftar Guru</a>
            <a href="{{ route('register') }}" class="btn-nav-register">Daftar Sekarang</a>
        </div>

        <button class="nav-toggle" id="navToggle" aria-label="Menu">
            <span></span><span></span><span></span>
        </button>
    </div>
</nav>

<div class="mobile-menu" id="mobileMenu" aria-hidden="true">
    <button class="mobile-close" onclick="closeMobile()" aria-label="Tutup menu"><i class="bi bi-x-lg"></i></button>
    <a href="{{ url('/') }}#tentang" onclick="closeMobile()">Tentang</a>
    <a href="{{ url('/') }}#program" onclick="closeMobile()">Program</a>
    <a href="{{ url('/') }}#mengapa-sci" onclick="closeMobile()">Keunggulan</a>
    <a href="{{ url('/') }}#testimonials" onclick="closeMobile()">Testimoni</a>
    <a href="{{ url('/') }}#tutor" onclick="closeMobile()">Tutor</a>
    <a href="{{ url('/') }}#cabang" onclick="closeMobile()">Cabang</a>
    <a href="{{ route('articles.index') }}" onclick="closeMobile()">Artikel</a>
    <div class="mobile-divider"></div>
    <a href="{{ route('login') }}" onclick="closeMobile()" style="color:rgba(255,255,255,.65);font-size:1.05rem;font-weight:600"><i class="bi bi-box-arrow-in-right" style="font-size:.9rem"></i> Masuk</a>
    <a href="{{ route('public.teacher-registration.create') }}" onclick="closeMobile()" style="background:linear-gradient(135deg,#461256,#c84ddf);padding:.8rem 2.5rem;border-radius:14px;font-size:1rem;color:white">Daftar Guru</a>
    <a href="{{ route('register') }}" onclick="closeMobile()" style="background:linear-gradient(135deg,var(--primary-dark),var(--primary));padding:.8rem 2.5rem;border-radius:14px;font-size:1rem;color:white">Daftar Sekarang</a>
</div>

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

{{-- FOOTER --}}
<footer class="footer">
    <div class="container-lp">
        <div class="footer-grid">
            <div>
                <a href="{{ url('/') }}" class="nav-brand" style="text-decoration:none">
                    <div class="nav-brand-icon"><i class="bi bi-mortarboard-fill"></i></div>
                    <div class="nav-brand-text" style="color:white">Smart Center<small style="color:rgba(255,255,255,.5)">Indonesia</small></div>
                </a>
                <p style="font-size:.82rem;color:rgba(255,255,255,.5);margin-top:1rem;line-height:1.75;max-width:260px">Wujudkan Mimpi, Raih Prestasi</p>
                <p class="footer-brand-desc">Platform pendidikan modern untuk semua jenjang. Dari TK hingga profesional — kami selalu ada untuk mendukung perjalanan belajar Anda.</p>
                <div class="footer-social">
                    <a href="{{ $ls('footer.facebook','#') }}" title="Facebook"><i class="bi bi-facebook"></i></a>
                    <a href="{{ $ls('footer.instagram','#') }}" title="Instagram"><i class="bi bi-instagram"></i></a>
                    <a href="{{ $ls('footer.youtube','#') }}" title="YouTube"><i class="bi bi-youtube"></i></a>
                    <a href="https://wa.me/{{ $waMain }}" title="WhatsApp"><i class="bi bi-whatsapp"></i></a>
                </div>
            </div>

            <div>
                <div class="footer-col-title">Navigasi</div>
                <ul class="footer-links">
                    <li><a href="{{ url('/') }}#home">Beranda</a></li>
                    <li><a href="{{ url('/') }}#tentang">Tentang Kami</a></li>
                    <li><a href="{{ url('/') }}#program-unggulan">Program</a></li>
                    <li><a href="{{ url('/') }}#mengapa-sci">Keunggulan</a></li>
                    <li><a href="{{ url('/') }}#testimonials">Testimoni</a></li>
                </ul>
            </div>

            <div>
                <div class="footer-col-title">Layanan</div>
                <ul class="footer-links">
                    <li><a href="{{ url('/') }}#galeri">Galeri</a></li>
                    <li><a href="{{ url('/') }}#tutor">Tutor</a></li>
                    <li><a href="{{ url('/') }}#bantuan">FAQ</a></li>
                    <li><a href="{{ url('/') }}#cabang">Cabang</a></li>
                    <li><a href="{{ url('/') }}#bantuan">Kontak</a></li>
                </ul>
            </div>

            <div>
                <div class="footer-col-title">Kontak</div>
                <ul class="footer-links">
                    <li><a href="tel:+6285333399210"><i class="bi bi-telephone-fill" style="color:var(--primary);margin-right:6px"></i>+62 853-3339-9210</a></li>
                    <li><a href="mailto:smartcenterindonesia@gmail.com"><i class="bi bi-envelope-fill" style="color:var(--primary);margin-right:6px"></i>smartcenterindonesia@gmail.com</a></li>
                    <li style="color:rgba(255,255,255,.5);font-size:.82rem"><i class="bi bi-clock-fill" style="color:var(--primary);margin-right:6px"></i>Senin–Sabtu (08.00–20.00)</li>
                    <li style="color:rgba(255,255,255,.5);font-size:.82rem;margin-top:2px"><i class="bi bi-geo-alt-fill" style="color:var(--primary);margin-right:6px"></i>150+ Cabang di Indonesia</li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} Smart Center Indonesia (SCI). All Rights Reserved.</p>
            <div class="footer-bottom-links">
                <a href="{{ url('/') }}">Beranda</a>
                <a href="{{ route('articles.index') }}">Artikel</a>
                <a href="{{ route('login') }}">Masuk</a>
            </div>
        </div>
    </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
const navToggle = document.getElementById('navToggle');
const mobileMenu = document.getElementById('mobileMenu');
function openMobile() {
    mobileMenu.classList.add('open');
    navToggle.classList.add('open');
}
function closeMobile() {
    mobileMenu.classList.remove('open');
    navToggle.classList.remove('open');
}
navToggle?.addEventListener('click', () => {
    if (mobileMenu.classList.contains('open')) {
        closeMobile();
    } else {
        openMobile();
    }
});
</script>
</body>
</html>
