@php
    $katLabel = ['tips'=>'Tips & Trik','berita'=>'Berita','akademik'=>'Akademik','promo'=>'Promo','lainnya'=>'Lainnya'];
    $katColor = ['tips'=>'#c84ddf','berita'=>'#2563eb','akademik'=>'#10b981','promo'=>'#f59e0b','lainnya'=>'#6b7280'];
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
        :root{--primary:#c84ddf;--primary-dark:#68117e;--deep:#260632;--font-sans:'Inter',system-ui,sans-serif;--font-display:'Plus Jakarta Sans','Inter',sans-serif}
        html{scroll-behavior:smooth}
        body{font-family:var(--font-sans);background:#fafafa;color:#1e0828;overflow-x:hidden}
        /* NAV */
        .lp-nav{position:fixed;top:0;left:0;right:0;z-index:1000;background:white;border-bottom:1px solid rgba(0,0,0,.06);box-shadow:0 2px 16px rgba(0,0,0,.06)}
        .nav-inner{display:flex;align-items:center;justify-content:space-between;max-width:1280px;margin:0 auto;padding:0 2rem;height:64px}
        .nav-brand{display:flex;align-items:center;gap:10px;text-decoration:none}
        .nav-brand-icon{width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,var(--primary-dark),var(--primary));display:flex;align-items:center;justify-content:center;font-size:14px;color:white;font-weight:900;flex-shrink:0;box-shadow:0 4px 12px rgba(200,77,223,.35)}
        .nav-brand-text{font-family:var(--font-display);font-weight:800;font-size:1rem;color:var(--deep);letter-spacing:-.02em;line-height:1.15}
        .nav-brand-text small{display:block;font-size:.62rem;font-weight:500;color:#6b7280}
        .btn-nav-login{padding:.45rem 1.2rem;border-radius:10px;font-size:.87rem;font-weight:600;color:var(--deep);border:1.5px solid rgba(38,6,50,.15);background:transparent;text-decoration:none;transition:.2s}
        .btn-nav-register{padding:.48rem 1.25rem;border-radius:10px;font-size:.87rem;font-weight:700;color:white;background:linear-gradient(135deg,var(--primary-dark),var(--primary));text-decoration:none;border:none;display:inline-block}
        /* HERO IMAGE */
        .article-hero{margin-top:64px;height:420px;position:relative;overflow:hidden;background:#260632}
        .article-hero img{width:100%;height:100%;object-fit:cover;opacity:.75}
        .article-hero-overlay{position:absolute;inset:0;background:linear-gradient(to top,rgba(38,6,50,.85) 0%,rgba(38,6,50,.2) 60%,transparent 100%)}
        /* ARTICLE BODY */
        .article-wrap{max-width:780px;margin:0 auto;padding:0 1.25rem}
        .article-card{background:white;border-radius:24px;box-shadow:0 8px 40px rgba(38,6,50,.1);margin-top:-80px;position:relative;z-index:10;padding:2.5rem}
        @media(max-width:600px){.article-card{padding:1.5rem;margin-top:-40px}.article-hero{height:260px}}
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
        .article-content img{max-width:100%;border-radius:12px;margin:1rem 0}
        /* RELATED */
        .related-card{background:white;border-radius:16px;overflow:hidden;box-shadow:0 2px 16px rgba(38,6,50,.07);transition:transform .25s,box-shadow .25s;text-decoration:none;color:inherit;display:flex;flex-direction:column}
        .related-card:hover{transform:translateY(-4px);box-shadow:0 10px 30px rgba(104,17,126,.12);color:inherit}
        .related-thumb{height:150px;overflow:hidden;flex-shrink:0}
        .related-thumb img{width:100%;height:100%;object-fit:cover;transition:transform .4s}
        .related-card:hover .related-thumb img{transform:scale(1.05)}
        /* SHARE */
        .share-btn{display:inline-flex;align-items:center;gap:6px;padding:.45rem 1rem;border-radius:50px;font-size:.82rem;font-weight:600;text-decoration:none;border:1.5px solid;transition:.2s}
        .share-btn:hover{transform:translateY(-2px)}
        /* mini footer */
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
            <a href="{{ route('articles.index') }}" class="btn-nav-login"><i class="bi bi-newspaper me-1"></i>Artikel</a>
            <a href="{{ route('login') }}" class="btn-nav-register">Masuk</a>
        </div>
    </div>
</nav>

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

<div class="mini-footer">&copy; {{ date('Y') }} Smart Center Indonesia. All Rights Reserved.</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
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
