<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Password | Smart Center Indonesia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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
            top:-150px;right:-150px;border-radius:50%;
            animation:orb 8s ease-in-out infinite alternate;pointer-events:none;
        }
        @keyframes orb{from{transform:translate(0,0);}to{transform:translate(30px,20px);}}
        .card-wrap{
            width:min(460px,100%);background:white;border-radius:28px;overflow:hidden;
            box-shadow:0 40px 80px rgba(0,0,0,.45),0 0 0 1px rgba(255,255,255,.08);
            animation:slideUp .5s cubic-bezier(.22,1,.36,1) both;position:relative;z-index:1;
        }
        @keyframes slideUp{from{opacity:0;transform:translateY(28px);}to{opacity:1;transform:translateY(0);}}
        .card-header-band{
            background:linear-gradient(135deg,#461256,#68117e,#c84ddf);
            padding:2rem 2.5rem 1.5rem;color:white;text-align:center;
        }
        .brand-icon{
            width:56px;height:56px;border-radius:16px;
            background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;
            font-size:26px;margin:0 auto 12px;border:1px solid rgba(255,255,255,.25);
        }
        .card-body-inner{padding:2rem 2.5rem;}
        h5,h6{font-family:'Plus Jakarta Sans','Inter',sans-serif;}
        .form-label{font-weight:600;font-size:.8rem;color:#374151;text-transform:uppercase;letter-spacing:.04em;margin-bottom:.4rem;}
        .form-control{
            border:2px solid #e5e7eb;border-radius:11px;padding:.72rem 1rem;font-size:.9rem;
            background:#fafafa;transition:.25s;
        }
        .form-control:focus{border-color:#c84ddf;box-shadow:0 0 0 4px rgba(200,77,223,.12);background:#fff;outline:none;}
        .form-control.is-invalid{border-color:#ef4444;}
        .input-group .form-control{border-right:none;border-radius:11px 0 0 11px;}
        .input-group-text{background:#fafafa;border:2px solid #e5e7eb;border-left:none;border-radius:0 11px 11px 0;cursor:pointer;color:#6b7280;transition:.2s;}
        .form-control:focus ~ .input-group-text,.form-control:focus + .input-group-text{border-color:#c84ddf;}
        .btn-confirm{
            background:linear-gradient(135deg,#68117e,#c84ddf 50%,#c84ddf);background-size:200% auto;
            border:none;border-radius:12px;padding:.85rem;font-size:.95rem;font-weight:700;
            color:white;width:100%;margin-top:.5rem;transition:.4s;
        }
        .btn-confirm:hover{background-position:right center;transform:translateY(-2px);box-shadow:0 10px 28px rgba(200,77,223,.45);color:white;}
        @media(max-width:480px){.card-body-inner,.card-header-band{padding:1.5rem;}}
    </style>
</head>
<body>
<div class="card-wrap">
    <div class="card-header-band">
        <div class="brand-icon"><i class="bi bi-shield-lock-fill"></i></div>
        <h5 style="font-weight:800;margin:0;letter-spacing:-.01em">Area Aman</h5>
        <p style="opacity:.75;font-size:13px;margin-top:4px">Konfirmasi password Anda untuk melanjutkan.</p>
    </div>

    <div class="card-body-inner">

        @if ($errors->any())
        <div class="d-flex align-items-center gap-2 mb-3 p-3 rounded-3" style="background:#fef2f2;border:1.5px solid #fecaca;color:#dc2626">
            <i class="bi bi-exclamation-triangle-fill flex-shrink-0"></i>
            <span style="font-size:.85rem">{{ $errors->first() }}</span>
        </div>
        @endif

        <div class="d-flex align-items-start gap-2 mb-4 p-3 rounded-3" style="background:#fdf4ff;border:1.5px solid rgba(200,77,223,.2);color:#68117e">
            <i class="bi bi-info-circle-fill flex-shrink-0 mt-1"></i>
            <div style="font-size:.84rem;line-height:1.6">
                Ini adalah area yang dilindungi. Harap konfirmasi password Anda sebelum melanjutkan.
            </div>
        </div>

        <form method="POST" action="{{ route('password.confirm') }}" id="confirmForm">
            @csrf

            <div class="mb-4">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" name="password" id="confirmPwd"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="Masukkan password Anda"
                           required autocomplete="current-password" autofocus>
                    <span class="input-group-text" onclick="togglePwd()">
                        <i class="bi bi-eye" id="eyeIcon"></i>
                    </span>
                </div>
                @error('password')
                <div class="mt-1" style="font-size:.8rem;color:#ef4444">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-confirm" id="confirmBtn">
                <span id="confirmText"><i class="bi bi-shield-check me-2"></i>Konfirmasi & Lanjutkan</span>
                <span id="confirmLoading" class="d-none">
                    <span class="spinner-border spinner-border-sm me-2"></span>Memverifikasi...
                </span>
            </button>
        </form>

        <div class="text-center mt-4" style="font-size:.875rem;color:#6b7280">
            <a href="{{ route('login') }}" class="d-inline-flex align-items-center gap-1 fw-semibold text-decoration-none" style="color:#c84ddf">
                <i class="bi bi-arrow-left"></i> Kembali ke Login
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function togglePwd() {
    const input = document.getElementById('confirmPwd');
    const icon  = document.getElementById('eyeIcon');
    input.type = input.type === 'password' ? 'text' : 'password';
    icon.className = input.type === 'text' ? 'bi bi-eye-slash' : 'bi bi-eye';
}

document.getElementById('confirmForm').addEventListener('submit', function() {
    document.getElementById('confirmText').classList.add('d-none');
    document.getElementById('confirmLoading').classList.remove('d-none');
    document.getElementById('confirmBtn').disabled = true;
});
</script>
</body>
</html>
