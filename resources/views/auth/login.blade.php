<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login | Smart Center Indonesia</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #260632 0%, #461256 40%, #68117e 75%, #c84ddf 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', 'Segoe UI', sans-serif;
            padding: 1rem;
            position: relative;
            overflow: hidden;
        }

        /* Animated background orbs */
        body::before {
            content: '';
            position: fixed;
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(200,77,223,0.25) 0%, transparent 70%);
            top: -200px; right: -200px;
            border-radius: 50%;
            animation: orb1 8s ease-in-out infinite alternate;
            pointer-events: none;
        }
        body::after {
            content: '';
            position: fixed;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(246,175,35,0.15) 0%, transparent 70%);
            bottom: -150px; left: -150px;
            border-radius: 50%;
            animation: orb2 10s ease-in-out infinite alternate;
            pointer-events: none;
        }
        @keyframes orb1 { from{transform:translate(0,0) scale(1);} to{transform:translate(40px,30px) scale(1.1);} }
        @keyframes orb2 { from{transform:translate(0,0) scale(1);} to{transform:translate(-30px,-40px) scale(1.15);} }

        .login-wrapper {
            display: flex;
            width: min(960px, 100%);
            min-height: 560px;
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 40px 80px rgba(0,0,0,0.45), 0 0 0 1px rgba(255,255,255,0.08);
            position: relative;
            z-index: 1;
            animation: slideUp 0.6s cubic-bezier(0.22,1,0.36,1) both;
        }
        @keyframes slideUp {
            from { opacity:0; transform: translateY(32px); }
            to   { opacity:1; transform: translateY(0); }
        }

        /* ===== LEFT PANEL ===== */
        .login-left {
            flex: 1.1;
            background: linear-gradient(160deg, #260632 0%, #461256 50%, #68117e 100%);
            padding: 3rem 2.5rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            color: white;
            position: relative;
            overflow: hidden;
        }
        .login-left::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .brand-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 50px;
            padding: 8px 18px 8px 8px;
            margin-bottom: 2rem;
            width: fit-content;
        }
        .brand-badge-icon {
            width: 36px; height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #c84ddf, #f6af23);
            display: flex; align-items: center; justify-content: center;
            font-size: 18px;
            animation: float 3s ease-in-out infinite;
        }
        @keyframes float {
            0%,100%{transform:translateY(0)} 50%{transform:translateY(-6px)}
        }
        .brand-badge span { font-size: 13px; font-weight: 600; color: #e2e8f0; }

        .login-left h1 {
            font-size: clamp(1.8rem, 3vw, 2.4rem);
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1rem;
            letter-spacing: -0.02em;
        }
        .login-left .lead {
            font-size: .95rem;
            opacity: .75;
            line-height: 1.7;
            margin-bottom: 2rem;
        }

        .feature-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-top: .5rem;
        }
        .feature-item {
            background: rgba(255,255,255,0.07);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 14px;
            padding: 14px;
            transition: .3s;
        }
        .feature-item:hover {
            background: rgba(255,255,255,0.12);
            transform: translateY(-2px);
        }
        .feature-item i { font-size: 20px; margin-bottom: 6px; display: block; }
        .feature-item span { font-size: 12px; font-weight: 500; color: #cbd5e1; }

        .left-footer {
            margin-top: auto;
            padding-top: 2rem;
            font-size: 12px;
            color: rgba(255,255,255,0.35);
        }

        /* ===== RIGHT PANEL ===== */
        .login-right {
            flex: 1;
            background: #fff;
            padding: clamp(2rem, 5vw, 3.5rem);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-right .greeting {
            font-size: clamp(1.4rem, 2.5vw, 1.8rem);
            font-weight: 800;
            color: #260632;
            margin-bottom: .3rem;
            letter-spacing: -0.01em;
        }
        .login-right .subtitle {
            color: #64748b;
            font-size: .9rem;
            margin-bottom: 2rem;
        }

        .form-label {
            font-weight: 600;
            font-size: .82rem;
            color: #374151;
            margin-bottom: .4rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .form-control {
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: .75rem 1rem;
            font-size: .9rem;
            transition: border-color .25s, box-shadow .25s, background .25s;
            background: #fafafa;
        }
        .form-control:focus {
            border-color: #c84ddf;
            box-shadow: 0 0 0 4px rgba(200,77,223,.12);
            background: #fff;
            outline: none;
        }
        .form-control.is-invalid { border-color: #ef4444; }

        .input-group .form-control { border-right: none; border-radius: 12px 0 0 12px; }
        .input-group-text {
            background: #fafafa;
            border: 2px solid #e5e7eb;
            border-left: none;
            border-radius: 0 12px 12px 0;
            cursor: pointer;
            color: #6b7280;
            transition: .2s;
        }
        .input-group-text:hover { color: #374151; background: #f3f4f6; }
        .form-control:focus + .input-group-text,
        .form-control:focus ~ .input-group-text {
            border-color: #c84ddf;
        }

        .btn-login {
            background: linear-gradient(135deg, #68117e, #c84ddf 50%, #f6af23);
            background-size: 200% auto;
            border: none;
            border-radius: 12px;
            padding: .85rem;
            font-size: .95rem;
            font-weight: 700;
            color: white;
            width: 100%;
            margin-top: .5rem;
            transition: background-position .4s, transform .2s, box-shadow .2s;
            letter-spacing: 0.02em;
        }
        .btn-login:hover {
            background-position: right center;
            transform: translateY(-2px);
            box-shadow: 0 10px 28px rgba(200,77,223,.45);
            color: white;
        }
        .btn-login:active { transform: translateY(0); }
        .btn-login .spinner-border { width: 1rem; height: 1rem; border-width: 2px; }

        .divider {
            text-align: center;
            color: #9ca3af;
            font-size: .8rem;
            margin: 1.25rem 0;
            position: relative;
        }
        .divider::before, .divider::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 42%;
            height: 1px;
            background: #e5e7eb;
        }
        .divider::before { left: 0; }
        .divider::after  { right: 0; }

        .alert-error {
            background: #fef2f2;
            border: 1.5px solid #fecaca;
            color: #dc2626;
            border-radius: 12px;
            padding: .8rem 1rem;
            font-size: .85rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .demo-credentials {
            background: #fdf4ff;
            border: 1.5px solid #e8b4f5;
            border-radius: 12px;
            padding: 12px 14px;
            font-size: .78rem;
            color: #68117e;
            margin-top: 1.25rem;
        }
        .demo-credentials strong { display: block; margin-bottom: 4px; color: #461256; }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .login-wrapper { flex-direction: column; border-radius: 20px; }
            .login-left { padding: 2rem; min-height: auto; }
            .feature-grid { grid-template-columns: 1fr 1fr; }
            .left-footer { display: none; }
        }
        @media (max-width: 480px) {
            .login-wrapper { border-radius: 16px; }
            .feature-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<div class="login-wrapper">

    {{-- ===== LEFT PANEL ===== --}}
    <div class="login-left">

        <div class="brand-badge">
            <div class="brand-badge-icon">
                <i class="bi bi-mortarboard-fill" style="color:#fff"></i>
            </div>
            <span>Smart Center Indonesia</span>
        </div>

        <h1>Platform Manajemen<br>Bimbel <span style="background:linear-gradient(90deg,#f6af23,#c84ddf);-webkit-background-clip:text;-webkit-text-fill-color:transparent">Terpadu</span></h1>

        <p class="lead">Kelola siswa, guru, keuangan, dan jadwal belajar seluruh cabang dalam satu platform cerdas dan terintegrasi.</p>

        <div class="feature-grid">
            <div class="feature-item">
                <i class="bi bi-people-fill" style="color:#c84ddf"></i>
                <span>Manajemen Siswa & Guru</span>
            </div>
            <div class="feature-item">
                <i class="bi bi-calendar-week-fill" style="color:#ab8db2"></i>
                <span>Jadwal & Kehadiran</span>
            </div>
            <div class="feature-item">
                <i class="bi bi-cash-stack" style="color:#f6af23"></i>
                <span>Keuangan & Invoice</span>
            </div>
            <div class="feature-item">
                <i class="bi bi-ui-checks-grid" style="color:#e8b4f5"></i>
                <span>Tryout CBT Online</span>
            </div>
            <div class="feature-item">
                <i class="bi bi-building-fill-check" style="color:#c84ddf"></i>
                <span>Multi Cabang</span>
            </div>
            <div class="feature-item">
                <i class="bi bi-graph-up-arrow" style="color:#f6af23"></i>
                <span>Laporan & Analitik</span>
            </div>
        </div>

        <div class="left-footer">
            &copy; {{ date('Y') }} Smart Center Indonesia. All rights reserved.
        </div>

    </div>

    {{-- ===== RIGHT PANEL ===== --}}
    <div class="login-right">

        <div class="greeting">Selamat Datang 👋</div>
        <p class="subtitle">Masuk ke akun Anda untuk melanjutkan</p>

        {{-- Error --}}
        @if ($errors->any())
        <div class="alert-error">
            <i class="bi bi-exclamation-triangle-fill flex-shrink-0"></i>
            <span>{{ $errors->first() }}</span>
        </div>
        @endif

        @if (session('status'))
        <div class="alert alert-success rounded-3 py-2 px-3 small mb-3 border-0" style="background:#f0fdf4;color:#15803d">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('status') }}
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}" id="loginForm">
            @csrf

            {{-- Email --}}
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input
                    type="email"
                    name="email"
                    class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}"
                    placeholder="contoh@email.com"
                    required autofocus autocomplete="email">
            </div>

            {{-- Password --}}
            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <input
                        type="password"
                        name="password"
                        id="passwordInput"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="Masukkan password"
                        required autocomplete="current-password">
                    <span class="input-group-text" onclick="togglePassword()" title="Tampilkan password">
                        <i class="bi bi-eye" id="eyeIcon"></i>
                    </span>
                </div>
            </div>

            {{-- Remember Me --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label small text-muted" for="remember">Ingat saya</label>
                </div>
                @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="small text-decoration-none fw-semibold" style="color:#c84ddf">
                    Lupa password?
                </a>
                @endif
            </div>

            <button type="submit" class="btn btn-login" id="loginBtn">
                <span id="btnText"><i class="bi bi-box-arrow-in-right me-2"></i>Masuk</span>
                <span id="btnLoading" class="d-none">
                    <span class="spinner-border me-2" role="status"></span>Memproses...
                </span>
            </button>

            <div class="divider">atau</div>

            <div class="text-center small text-muted">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-decoration-none fw-semibold" style="color:#c84ddf">
                    Daftar sekarang
                </a>
            </div>
        </form>

        <div class="demo-credentials">
            <strong><i class="bi bi-info-circle me-1"></i> Akun Demo</strong>
            Owner: adminpusatsci@akademi.com / password<br>
            Admin: admincabangsci@akademi.com / password
        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function togglePassword() {
        const input = document.getElementById('passwordInput');
        const icon  = document.getElementById('eyeIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'bi bi-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'bi bi-eye';
        }
    }

    document.getElementById('loginForm').addEventListener('submit', function() {
        document.getElementById('btnText').classList.add('d-none');
        document.getElementById('btnLoading').classList.remove('d-none');
        document.getElementById('loginBtn').disabled = true;
    });
</script>
</body>
</html>
