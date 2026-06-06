<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | Smart Center Indonesia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *{margin:0;padding:0;box-sizing:border-box;}
        body{
            min-height:100vh;
            background:linear-gradient(135deg,#260632 0%,#461256 40%,#461256 75%,#c84ddf 100%);
            display:flex;align-items:center;justify-content:center;
            font-family:'Inter','Segoe UI',sans-serif;padding:2rem 1rem;
            position:relative;overflow:hidden;
        }
        body::before{
            content:'';position:fixed;width:500px;height:500px;
            background:radial-gradient(circle,rgba(200,77,223,.25) 0%,transparent 70%);
            top:-150px;right:-150px;border-radius:50%;pointer-events:none;
        }
        .card-wrap{
            width:min(460px,100%);background:white;border-radius:28px;overflow:hidden;
            box-shadow:0 40px 80px rgba(0,0,0,.45);
            animation:slideUp .5s cubic-bezier(.22,1,.36,1) both;position:relative;z-index:1;
        }
        @keyframes slideUp{from{opacity:0;transform:translateY(28px);}to{opacity:1;transform:translateY(0);}}
        .card-header-band{
            background:linear-gradient(135deg,#059669,#10b981,#34d399);
            padding:2rem 2.5rem 1.5rem;color:white;text-align:center;
        }
        .brand-icon{
            width:56px;height:56px;border-radius:16px;background:rgba(255,255,255,.2);
            display:flex;align-items:center;justify-content:center;font-size:26px;
            margin:0 auto 12px;border:1px solid rgba(255,255,255,.25);
        }
        .card-body-inner{padding:2rem 2.5rem;}
        .form-label{font-weight:600;font-size:.8rem;color:#374151;text-transform:uppercase;letter-spacing:.04em;margin-bottom:.4rem;}
        .form-control{border:2px solid #e5e7eb;border-radius:11px;padding:.72rem 1rem;font-size:.9rem;background:#fafafa;transition:.25s;}
        .form-control:focus{border-color:#10b981;box-shadow:0 0 0 4px rgba(16,185,129,.12);background:#fff;outline:none;}
        .form-control.is-invalid{border-color:#ef4444;}
        .input-group .form-control{border-right:none;border-radius:11px 0 0 11px;}
        .input-group-text{background:#fafafa;border:2px solid #e5e7eb;border-left:none;border-radius:0 11px 11px 0;cursor:pointer;color:#6b7280;transition:.2s;}
        .form-control:focus + .input-group-text,.form-control:focus ~ .input-group-text{border-color:#10b981;}
        .btn-reset{
            background:linear-gradient(135deg,#059669,#10b981 50%,#34d399);background-size:200% auto;
            border:none;border-radius:12px;padding:.85rem;font-size:.95rem;font-weight:700;
            color:white;width:100%;margin-top:.5rem;transition:.4s;
        }
        .btn-reset:hover{background-position:right center;transform:translateY(-2px);box-shadow:0 10px 28px rgba(16,185,129,.45);color:white;}
        .strength-bar{height:5px;border-radius:10px;background:#e5e7eb;overflow:hidden;margin-top:8px;}
        .strength-fill{height:100%;border-radius:10px;transition:.3s;}
        @media(max-width:480px){.card-body-inner,.card-header-band{padding:1.5rem;}}
    </style>
</head>
<body>
<div class="card-wrap">
    <div class="card-header-band">
        <div class="brand-icon"><i class="bi bi-shield-check-fill"></i></div>
        <h5 style="font-weight:800;margin:0">Buat Password Baru</h5>
        <p style="opacity:.75;font-size:13px;margin-top:4px">Masukkan password baru yang kuat untuk akun Anda</p>
    </div>

    <div class="card-body-inner">

        @if ($errors->any())
        <div class="d-flex align-items-center gap-2 mb-3 p-3 rounded-3" style="background:#fef2f2;border:1.5px solid #fecaca;color:#dc2626">
            <i class="bi bi-exclamation-triangle-fill flex-shrink-0"></i>
            <span style="font-size:.85rem">{{ $errors->first() }}</span>
        </div>
        @endif

        <form method="POST" action="{{ route('password.store') }}" id="resetForm">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email"
                       class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email', $request->email) }}"
                       readonly style="background:#f3f4f6;cursor:not-allowed">
            </div>

            <div class="mb-3">
                <label class="form-label">Password Baru</label>
                <div class="input-group">
                    <input type="password" name="password" id="newPwd"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="Min. 8 karakter" required autocomplete="new-password">
                    <span class="input-group-text" onclick="togglePwd('newPwd','eye1')">
                        <i class="bi bi-eye" id="eye1"></i>
                    </span>
                </div>
                <div class="strength-bar mt-2" id="strengthBar" style="display:none">
                    <div class="strength-fill" id="strengthFill"></div>
                </div>
                <div id="strengthLabel" class="mt-1" style="font-size:11px;font-weight:600"></div>
                @error('password')<div style="font-size:.8rem;color:#ef4444;margin-top:4px">{{ $message }}</div>@enderror
            </div>

            <div class="mb-4">
                <label class="form-label">Konfirmasi Password</label>
                <div class="input-group">
                    <input type="password" name="password_confirmation" id="confirmPwd"
                           class="form-control" placeholder="Ulangi password baru" required>
                    <span class="input-group-text" onclick="togglePwd('confirmPwd','eye2')">
                        <i class="bi bi-eye" id="eye2"></i>
                    </span>
                </div>
            </div>

            <button type="submit" class="btn btn-reset" id="resetBtn">
                <span id="resetText"><i class="bi bi-shield-lock me-2"></i>Reset Password</span>
                <span id="resetLoading" class="d-none">
                    <span class="spinner-border spinner-border-sm me-2"></span>Memproses...
                </span>
            </button>
        </form>

        <div class="text-center mt-4">
            <a href="{{ route('login') }}"
               class="d-inline-flex align-items-center gap-1 fw-semibold text-decoration-none"
               style="color:#10b981;font-size:.875rem">
                <i class="bi bi-arrow-left"></i> Kembali ke Login
            </a>
        </div>
    </div>
</div>

<script>
function togglePwd(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon  = document.getElementById(iconId);
    input.type = input.type === 'password' ? 'text' : 'password';
    icon.className = input.type === 'text' ? 'bi bi-eye-slash' : 'bi bi-eye';
}

document.getElementById('newPwd').addEventListener('input', function() {
    const val = this.value;
    const bar = document.getElementById('strengthBar');
    const fill = document.getElementById('strengthFill');
    const label = document.getElementById('strengthLabel');
    if (!val) { bar.style.display='none'; label.textContent=''; return; }
    bar.style.display='block';
    let s=0;
    if(val.length>=8)s++; if(/[A-Z]/.test(val))s++;
    if(/[0-9]/.test(val))s++; if(/[^A-Za-z0-9]/.test(val))s++;
    const cfgs=[
        {p:'25%',c:'#ef4444',t:'Lemah'},{p:'50%',c:'#f6af23',t:'Cukup'},
        {p:'75%',c:'#c84ddf',t:'Kuat'},{p:'100%',c:'#10b981',t:'Sangat Kuat'}
    ];
    const cfg=cfgs[s-1]||cfgs[0];
    fill.style.width=cfg.p; fill.style.background=cfg.c;
    label.textContent=cfg.t; label.style.color=cfg.c;
});

document.getElementById('resetForm').addEventListener('submit', function() {
    document.getElementById('resetText').classList.add('d-none');
    document.getElementById('resetLoading').classList.remove('d-none');
    document.getElementById('resetBtn').disabled = true;
});
</script>
</body>
</html>
