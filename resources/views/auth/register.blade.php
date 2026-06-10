<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar | Smart Center Indonesia</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #260632 0%, #461256 40%, #461256 75%, #c84ddf 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', 'Segoe UI', sans-serif;
            padding: 1.5rem 1rem;
            position: relative;
            overflow-x: hidden;
        }
        body::before {
            content:'';
            position:fixed;
            width:500px;height:500px;
            background:radial-gradient(circle,rgba(200,77,223,.25) 0%,transparent 70%);
            top:-150px;right:-150px;border-radius:50%;
            animation:orb1 8s ease-in-out infinite alternate;pointer-events:none;
        }
        body::after {
            content:'';
            position:fixed;
            width:400px;height:400px;
            background:radial-gradient(circle,rgba(246,175,35,.15) 0%,transparent 70%);
            bottom:-120px;left:-120px;border-radius:50%;
            animation:orb2 10s ease-in-out infinite alternate;pointer-events:none;
        }
        @keyframes orb1{from{transform:translate(0,0) scale(1);}to{transform:translate(30px,20px) scale(1.1);}}
        @keyframes orb2{from{transform:translate(0,0) scale(1);}to{transform:translate(-20px,-30px) scale(1.15);}}

        .register-card {
            width: min(500px, 100%);
            background: white;
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 40px 80px rgba(0,0,0,.45), 0 0 0 1px rgba(255,255,255,.08);
            animation: slideUp .5s cubic-bezier(.22,1,.36,1) both;
            position: relative; z-index: 1;
        }
        @keyframes slideUp{from{opacity:0;transform:translateY(28px);}to{opacity:1;transform:translateY(0);}}

        .card-header-band {
            background: linear-gradient(135deg, #461256, #68117e, #c84ddf);
            padding: 2rem 2.5rem 1.5rem;
            color: white;
            text-align: center;
        }
        .brand-logo {
            width: 52px; height: 52px;
            border-radius: 15px;
            background: rgba(255,255,255,.2);
            display: flex; align-items: center; justify-content: center;
            font-size: 24px;
            margin: 0 auto 12px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,.25);
        }
        .card-header-band h4 { font-size:22px;font-weight:800;margin:0;letter-spacing:-.01em; }
        .card-header-band p { font-size:13px;opacity:.75;margin:4px 0 0; }

        .card-body-inner { padding: 2rem 2.5rem; }

        .form-label { font-weight:600;font-size:.8rem;color:#374151;text-transform:uppercase;letter-spacing:.04em;margin-bottom:.4rem; }
        .form-control {
            border:2px solid #e5e7eb;border-radius:11px;padding:.7rem 1rem;font-size:.9rem;
            background:#fafafa;transition:border-color .25s,box-shadow .25s,background .25s;
        }
        .form-control:focus { border-color:#c84ddf;box-shadow:0 0 0 4px rgba(200,77,223,.12);background:#fff;outline:none; }
        .form-control.is-invalid { border-color:#ef4444; }
        .invalid-feedback { font-size:.8rem;color:#ef4444;margin-top:4px; }

        .input-group .form-control { border-right:none;border-radius:11px 0 0 11px; }
        .input-group-text {
            background:#fafafa;border:2px solid #e5e7eb;border-left:none;
            border-radius:0 11px 11px 0;cursor:pointer;color:#6b7280;transition:.2s;
        }
        .input-group-text:hover { color:#374151;background:#f3f4f6; }
        .form-control:focus + .input-group-text,.form-control:focus ~ .input-group-text { border-color:#c84ddf; }

        .btn-register {
            background:linear-gradient(135deg,#68117e,#c84ddf 50%,#c84ddf);
            background-size:200% auto;border:none;border-radius:12px;padding:.85rem;
            font-size:.95rem;font-weight:700;color:white;width:100%;margin-top:.5rem;
            transition:background-position .4s,transform .2s,box-shadow .2s;
        }
        .btn-register:hover { background-position:right center;transform:translateY(-2px);box-shadow:0 10px 28px rgba(200,77,223,.45);color:white; }
        .btn-register:active { transform:translateY(0); }

        .strength-bar { height:5px;border-radius:10px;background:#e5e7eb;overflow:hidden;margin-top:8px; }
        .strength-fill { height:100%;border-radius:10px;transition:.3s; }

        h1,h2,h3,h4,h5,h6 { font-family:'Plus Jakarta Sans','Inter',sans-serif; letter-spacing:-.02em; }
        @media(max-width:480px){ .card-body-inner{padding:1.5rem;} .card-header-band{padding:1.5rem;} }
    </style>
</head>
<body>

<div style="position:relative;z-index:2;margin-bottom:.75rem">
    <a href="{{ url('/') }}" style="display:inline-flex;align-items:center;gap:6px;color:rgba(255,255,255,.65);font-size:.78rem;font-weight:600;text-decoration:none;transition:color .2s;" onmouseover="this.style.color='rgba(255,255,255,.95)'" onmouseout="this.style.color='rgba(255,255,255,.65)'">
        <i class="bi bi-arrow-left" style="font-size:.8rem"></i> Kembali ke Beranda
    </a>
</div>

<div class="register-card">

    <div class="card-header-band">
        <div class="brand-logo"><i class="bi bi-mortarboard-fill"></i></div>
        <h4>Buat Akun Baru</h4>
        <p>Smart Center Indonesia — Sistem Manajemen Bimbel</p>
    </div>

    <div class="card-body-inner">

        @if ($errors->any())
        <div class="d-flex align-items-center gap-2 mb-3 p-3 rounded-3" style="background:#fef2f2;border:1.5px solid #fecaca;color:#dc2626">
            <i class="bi bi-exclamation-triangle-fill flex-shrink-0"></i>
            <span style="font-size:.85rem">{{ $errors->first() }}</span>
        </div>
        @endif

        <form method="POST" action="{{ route('register') }}" id="registerForm">
            @csrf

            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name') }}" placeholder="Masukkan nama lengkap"
                       required autofocus autocomplete="name">
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email') }}" placeholder="contoh@email.com"
                       required autocomplete="email">
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" name="password" id="password"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="Min. 8 karakter" required autocomplete="new-password">
                    <span class="input-group-text" onclick="togglePwd('password','eye1')">
                        <i class="bi bi-eye" id="eye1"></i>
                    </span>
                </div>
                <div class="strength-bar mt-2" id="strengthBar" style="display:none">
                    <div class="strength-fill" id="strengthFill"></div>
                </div>
                <div id="strengthLabel" class="mt-1" style="font-size:11px;font-weight:600"></div>
                @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            <div class="mb-4">
                <label class="form-label">Konfirmasi Password</label>
                <div class="input-group">
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           class="form-control" placeholder="Ulangi password" required autocomplete="new-password">
                    <span class="input-group-text" onclick="togglePwd('password_confirmation','eye2')">
                        <i class="bi bi-eye" id="eye2"></i>
                    </span>
                </div>
            </div>

            <button type="submit" class="btn btn-register" id="regBtn">
                <span id="regText"><i class="bi bi-person-plus me-2"></i>Daftar Sekarang</span>
                <span id="regLoading" class="d-none">
                    <span class="spinner-border spinner-border-sm me-2"></span>Memproses...
                </span>
            </button>

            <div class="text-center mt-4" style="font-size:.875rem;color:#6b7280">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="fw-semibold text-decoration-none" style="color:#c84ddf">
                    Masuk di sini
                </a>
            </div>

        </form>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function togglePwd(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon  = document.getElementById(iconId);
    input.type = input.type === 'password' ? 'text' : 'password';
    icon.className = input.type === 'text' ? 'bi bi-eye-slash' : 'bi bi-eye';
}

document.getElementById('password').addEventListener('input', function() {
    const val = this.value;
    const bar   = document.getElementById('strengthBar');
    const fill  = document.getElementById('strengthFill');
    const label = document.getElementById('strengthLabel');
    if (!val) { bar.style.display='none'; label.textContent=''; return; }
    bar.style.display='block';
    let s = 0;
    if (val.length >= 8) s++;
    if (/[A-Z]/.test(val)) s++;
    if (/[0-9]/.test(val)) s++;
    if (/[^A-Za-z0-9]/.test(val)) s++;
    const cfgs = [
        {p:'25%',c:'#ef4444',t:'Lemah'},
        {p:'50%',c:'#f6af23',t:'Cukup'},
        {p:'75%',c:'#c84ddf',t:'Kuat'},
        {p:'100%',c:'#10b981',t:'Sangat Kuat'},
    ];
    const cfg = cfgs[s-1] || cfgs[0];
    fill.style.width=cfg.p; fill.style.background=cfg.c;
    label.textContent=cfg.t; label.style.color=cfg.c;
});

document.getElementById('registerForm').addEventListener('submit', function() {
    document.getElementById('regText').classList.add('d-none');
    document.getElementById('regLoading').classList.remove('d-none');
    document.getElementById('regBtn').disabled = true;
});
(function(){
    var t = localStorage.getItem('theme');
    if (t === 'dark') {
        document.body.style.background = 'linear-gradient(135deg, #1a0426 0%, #2d0840 40%, #3d0f54 75%, #8b2faa 100%)';
    }
})();
</script>
</body>
</html>
