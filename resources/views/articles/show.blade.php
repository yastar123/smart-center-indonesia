@php
    $katLabel = ['tips'=>'Tips & Trik','berita'=>'Berita','akademik'=>'Akademik','promo'=>'Promo','lainnya'=>'Lainnya'];
    $katColor = ['tips'=>'#c84ddf','berita'=>'#2563eb','akademik'=>'#10b981','promo'=>'#f59e0b','lainnya'=>'#6b7280'];
    $lsAll    = isset($lsAll) ? $lsAll : \App\Models\LandingSetting::all()->keyBy('key');
    $ls       = fn(string $k, string $d='') => $lsAll[$k]->value ?? $d;
    $waMain   = \App\Models\LandingWaNumber::primaryNumber($ls('footer.wa_number', '628001234567'));
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $article->judul }} | Smart Center Indonesia</title>
    <meta name="description" content="{{ $article->ringkasan ?: Str::limit(strip_tags($article->konten), 160) }}">
    <meta property="og:title" content="{{ $article->judul }}">
    <meta property="og:description" content="{{ $article->ringkasan ?: Str::limit(strip_tags($article->konten), 160) }}">
    <meta property="og:image" content="{{ $article->thumbnail_url }}">
    <meta property="og:type" content="article">
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
        /* HERO IMAGE */
        .article-hero{margin-top:64px;height:420px;position:relative;overflow:hidden;background:#260632}
        .article-hero img{width:100%;height:100%;object-fit:cover;opacity:.75}
        .article-hero-overlay{position:absolute;inset:0;background:linear-gradient(to top,rgba(38,6,50,.85) 0%,rgba(38,6,50,.2) 60%,transparent 100%)}
        /* ARTICLE BODY */
        .article-wrap{max-width:780px;margin:0 auto;padding:0 1.25rem}
        /* Normal article layout: no floating/modal card */
        .article-card{background:transparent;border-radius:0;box-shadow:none;margin-top:1.5rem;position:relative;z-index:1;padding:0}
        .article-title{font-family:var(--font-display);font-size:clamp(1.5rem,4vw,2.25rem);font-weight:900;color:var(--deep);line-height:1.2;margin-bottom:1.25rem}
        .article-meta{display:flex;align-items:center;gap:1.25rem;flex-wrap:wrap;font-size:.82rem;color:#9c8aaa;padding-bottom:1.25rem;border-bottom:2px solid #f0e6f5;margin-bottom:1.75rem}
        .article-meta strong{color:var(--deep)}
        .article-content{font-size:1rem;line-height:1.85;color:#374151}
        .article-content h2,.article-content h3,.article-content h4{font-family:var(--font-display);color:var(--deep);margin:1.75rem 0 .75rem;font-weight:800}
        .article-content h2{font-size:1.45rem}.article-content h3{font-size:1.2rem}.article-content h4{font-size:1.05rem}
        .article-content p{margin-bottom:1.1rem}
        .article-content ul,.article-content ol{margin:0 0 1.1rem 1.5rem}
        .article-content li{margin-bottom:.4rem}
        .article-content blockquote{border-left:4px solid var(--primary);background:#fdf5ff;border-radius:0 10px 10px 0;padding:1rem 1.25rem;margin:1.5rem 0;color:#461256;font-style:italic}
        .article-content a{color:var(--primary-dark);text-decoration:underline}
        .article-content img{max-width:100%;border-radius:12px;margin:1rem 0;height:auto}
        /* RELATED */
        .related-card{background:white;border-radius:16px;overflow:hidden;box-shadow:0 2px 16px rgba(38,6,50,.07);transition:transform .25s,box-shadow .25s;text-decoration:none;color:inherit;display:flex;flex-direction:column}
        .related-card:hover{transform:translateY(-4px);box-shadow:0 10px 30px rgba(104,17,126,.12);color:inherit}
        .related-thumb{height:150px;overflow:hidden;flex-shrink:0}
        .related-thumb img{width:100%;height:100%;object-fit:cover;transition:transform .4s}
        .related-card:hover .related-thumb img{transform:scale(1.05)}
        /* SHARE */
        .share-btn{display:inline-flex;align-items:center;gap:6px;padding:.45rem 1rem;border-radius:50px;font-size:.82rem;font-weight:600;text-decoration:none;border:1.5px solid;transition:.2s}
        .share-btn:hover{transform:translateY(-2px)}
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
        @media (max-width: 900px){.footer-grid{grid-template-columns:1fr 1fr;gap:2rem}}
        @media (max-width: 700px){.article-card{padding:0;margin-top:1rem}.article-hero{height:260px}.article-meta{gap:.75rem}.article-wrap{padding:0 .9rem}}
        @media (max-width: 580px){.nav-inner{padding:0 1rem}.footer-grid{grid-template-columns:1fr;gap:1.5rem}.footer-bottom{flex-direction:column;text-align:center}.article-content ul,.article-content ol{margin-left:1rem}}
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

{{-- HERO IMAGE --}}
<div class="article-hero">
    <img src="{{ $article->thumbnail_url }}" alt="{{ $article->judul }}">
    <div class="article-hero-overlay"></div>
</div>

{{-- ARTICLE --}}
<div style="padding-bottom:4rem">
    <div class="article-wrap">

        {{-- Breadcrumb --}}
        <nav aria-label="breadcrumb" style="padding:1rem 0;font-size:.8rem;color:#9c8aaa">
            <a href="{{ url('/') }}" style="color:#9c8aaa;text-decoration:none">Beranda</a>
            <span class="mx-1">/</span>
            <a href="{{ route('articles.index') }}" style="color:#9c8aaa;text-decoration:none">Artikel</a>
            <span class="mx-1">/</span>
            <span style="color:var(--deep);font-weight:600">{{ Str::limit($article->judul, 40) }}</span>
        </nav>

        <div class="article-card">
            {{-- Kategori badge --}}
            <div class="mb-3">
                <span style="font-size:.75rem;font-weight:700;padding:4px 14px;border-radius:50px;background:{{ $katColor[$article->kategori] }}22;color:{{ $katColor[$article->kategori] }}">
                    {{ $katLabel[$article->kategori] }}
                </span>
            </div>

            <h1 class="article-title">{{ $article->judul }}</h1>

            <div class="article-meta">
                <span><i class="bi bi-person-fill me-1"></i><strong>{{ $article->penulis?->name ?? 'Admin' }}</strong></span>
                <span><i class="bi bi-calendar3 me-1"></i>{{ $article->published_at?->translatedFormat('d F Y') ?? '–' }}</span>
                <span><i class="bi bi-eye me-1"></i>{{ $article->views }} views</span>
                <span><i class="bi bi-clock me-1"></i>~{{ max(1, (int) ceil(str_word_count(strip_tags($article->konten)) / 200)) }} menit baca</span>
            </div>

            @if($article->ringkasan)
            <blockquote class="article-content">{{ $article->ringkasan }}</blockquote>
            @endif

            <div class="article-content">{!! $article->konten !!}</div>

            {{-- Share --}}
            <div style="margin-top:2rem;padding-top:1.5rem;border-top:2px solid #f0e6f5">
                <p style="font-size:.82rem;font-weight:700;color:#9c8aaa;text-transform:uppercase;letter-spacing:.08em;margin-bottom:.875rem">Bagikan Artikel</p>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="https://wa.me/?text={{ urlencode($article->judul . ' - ' . url()->current()) }}" target="_blank" rel="noopener" class="share-btn" style="color:#25d366;border-color:#25d366">
                        <i class="bi bi-whatsapp"></i> WhatsApp
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" rel="noopener" class="share-btn" style="color:#1877f2;border-color:#1877f2">
                        <i class="bi bi-facebook"></i> Facebook
                    </a>
                    <button onclick="copyLink()" class="share-btn" style="color:#6b5878;border-color:#e5e7eb;background:none;cursor:pointer">
                        <i class="bi bi-link-45deg"></i> Salin Link
                    </button>
                </div>
            </div>
        </div>

        {{-- Kembali --}}
        <div class="mt-4">
            <a href="{{ route('articles.index') }}" style="display:inline-flex;align-items:center;gap:6px;font-size:.87rem;font-weight:600;color:var(--primary-dark);text-decoration:none">
                <i class="bi bi-arrow-left"></i> Kembali ke Semua Artikel
            </a>
        </div>

        {{-- Related --}}
        @if($related->isNotEmpty())
        <div style="margin-top:3rem">
            <h3 style="font-family:'Plus Jakarta Sans',sans-serif;font-size:1.15rem;font-weight:800;color:var(--deep);margin-bottom:1.25rem">
                <i class="bi bi-bookmark me-2" style="color:var(--primary)"></i>Artikel Terkait
            </h3>
            <div class="row g-3">
                @foreach($related as $r)
                <div class="col-12 col-sm-4">
                    <a href="{{ route('articles.show', $r->slug) }}" class="related-card">
                        <div class="related-thumb"><img src="{{ $r->thumbnail_url }}" alt="{{ $r->judul }}" loading="lazy"></div>
                        <div class="p-3">
                            <span style="font-size:.68rem;font-weight:700;padding:2px 9px;border-radius:50px;background:{{ $katColor[$r->kategori] }}22;color:{{ $katColor[$r->kategori] }}">{{ $katLabel[$r->kategori] }}</span>
                            <p style="font-family:'Plus Jakarta Sans',sans-serif;font-size:.87rem;font-weight:700;color:var(--deep);margin:.5rem 0 0;line-height:1.35;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden">{{ $r->judul }}</p>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>
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
function copyLink() {
    navigator.clipboard.writeText(window.location.href).then(() => {
        alert('Link berhasil disalin!');
    }).catch(() => {
        const el = document.createElement('input');
        el.value = window.location.href;
        document.body.appendChild(el);
        el.select();
        document.execCommand('copy');
        document.body.removeChild(el);
        alert('Link berhasil disalin!');
    });
}
</script>
</body>
</html>
