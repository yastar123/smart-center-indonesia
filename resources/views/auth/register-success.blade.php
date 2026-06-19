<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Berhasil | Ayo Kursus</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #260632 0%, #461256 40%, #461256 75%, #c84ddf 100%);
            font-family: 'Inter', sans-serif;
            display: flex; align-items: center; justify-content: center;
            padding: 2rem 1rem;
        }
        body::before {
            content:''; position:fixed; width:500px; height:500px;
            background: radial-gradient(circle, rgba(200,77,223,.2) 0%, transparent 70%);
            top:-150px; right:-150px; border-radius:50%; pointer-events:none;
        }
        .card-wrap {
            width: min(620px, 100%); background: white; border-radius: 24px;
            overflow: hidden; box-shadow: 0 40px 80px rgba(0,0,0,.45);
            position: relative; z-index: 1;
            animation: slideUp .5s cubic-bezier(.22,1,.36,1) both;
        }
        @keyframes slideUp { from{opacity:0;transform:translateY(28px);}to{opacity:1;transform:translateY(0);} }
        .card-header-band {
            background: linear-gradient(135deg, #461256, #68117e, #c84ddf);
            padding: 2rem 2rem 1.5rem; color: white; text-align: center;
        }
        .success-icon {
            width: 72px; height: 72px; border-radius: 50%;
            background: rgba(255,255,255,.2); display: flex; align-items: center;
            justify-content: center; font-size: 32px; margin: 0 auto 12px;
            border: 2px solid rgba(255,255,255,.3);
        }
        .card-header-band h4 { font-size:22px; font-weight:800; margin:0; font-family:'Plus Jakarta Sans',sans-serif; }
        .card-header-band p  { font-size:13px; opacity:.75; margin:4px 0 0; }
        .card-body { padding: 2rem; }
        .wa-card {
            display: flex; align-items: center; gap: 14px;
            padding: 14px 18px; border-radius: 14px; border: 1.5px solid #e5e7eb;
            text-decoration: none; transition: all .2s; background: #fafafa; margin-bottom: 10px;
        }
        .wa-card:hover { border-color: #25d366; background: #f0fff4; transform: translateY(-2px); }
        .wa-icon { width:44px; height:44px; border-radius:12px; background:#25d366; display:flex; align-items:center; justify-content:center; color:white; font-size:22px; flex-shrink:0; }
        .wa-label { font-weight:700; font-size:14px; color:#111; }
        .wa-desc  { font-size:12px; color:#6b7280; }
        .wa-number { font-size:12px; color:#25d366; font-weight:600; font-family:monospace; }
        .badge-primary { background:#fdf4ff; color:#461256; border:1px solid #e9d5ff; padding:2px 8px; border-radius:20px; font-size:11px; font-weight:700; }
    </style>
</head>
<body>

<div class="card-wrap">
    <div class="card-header-band">
        <div class="success-icon"><i class="bi bi-check-circle-fill"></i></div>
        <h4>Formulir Terkirim!</h4>
        <p>Data pendaftaran {{ $studentName }} telah kami terima</p>
    </div>

    <div class="card-body">
        <div class="text-center mb-4">
            <p class="text-muted" style="font-size:14px">
                Langkah selanjutnya: hubungi kami via <strong>WhatsApp</strong> untuk konfirmasi jadwal dan informasi lebih lanjut.
            </p>
        </div>

        @if($waNumbers->count() > 0)
        <h6 class="fw-bold mb-3" style="font-size:13px;text-transform:uppercase;letter-spacing:.05em;color:#461256">
            <i class="bi bi-telephone-fill me-2"></i>Pilih Cabang untuk Dihubungi
        </h6>

        @foreach($waNumbers as $wa)
        @php
            $number = preg_replace('/\D/', '', $wa->number);
            if (!str_starts_with($number, '62')) $number = '62' . ltrim($number, '0');
            $waUrl = 'https://wa.me/' . $number . '?text=' . urlencode('Halo, saya ' . $studentName . ' ingin konfirmasi pendaftaran kursus.');
        @endphp
        <a href="{{ $waUrl }}" target="_blank" class="wa-card">
            <div class="wa-icon"><i class="bi bi-whatsapp"></i></div>
            <div class="flex-grow-1">
                <div class="wa-label">
                    {{ $wa->label }}
                    @if($wa->is_primary) <span class="badge-primary ms-1">Pusat</span> @endif
                </div>
                @if($wa->description) <div class="wa-desc">{{ $wa->description }}</div> @endif
                <div class="wa-number">{{ $wa->number }}</div>
            </div>
            <i class="bi bi-chevron-right text-muted"></i>
        </a>
        @endforeach

        @else
        <div class="text-center py-4">
            <i class="bi bi-whatsapp" style="font-size:3rem;color:#25d366"></i>
            <p class="mt-3 text-muted">Silakan hubungi kami untuk informasi selengkapnya.</p>
        </div>
        @endif

        <div class="text-center mt-4 pt-3 border-top">
            <a href="{{ url('/') }}" class="btn btn-outline-secondary btn-sm px-4" style="border-radius:10px">
                <i class="bi bi-house me-1"></i>Kembali ke Beranda
            </a>
        </div>
    </div>
</div>

</body>
</html>
