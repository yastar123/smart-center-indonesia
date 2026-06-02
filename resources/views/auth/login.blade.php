<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login | Smart Center Indonesia</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #1e3a5f 0%, #4f8ef7 50%, #6c63ff 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }

        .login-wrapper {
            display: flex;
            width: 900px;
            min-height: 520px;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 25px 60px rgba(0,0,0,0.3);
        }

        /* Panel Kiri */
        .login-left {
            flex: 1;
            background: linear-gradient(160deg, #1e3a5f, #6c63ff);
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: white;
        }

        .login-left .brand-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .login-left h1 { font-size: 1.8rem; font-weight: 700; margin-bottom: .5rem; }
        .login-left p { font-size: .95rem; opacity: .8; line-height: 1.6; }

        .feature-list {
            list-style: none;
            margin-top: 1.5rem;
            text-align: left;
            width: 100%;
        }

        .feature-list li {
            padding: .4rem 0;
            font-size: .88rem;
            opacity: .85;
        }

        .feature-list li i { margin-right: .5rem; color: #a8d8ff; }

        /* Panel Kanan */
        .login-right {
            flex: 1;
            background: #fff;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-right h2 {
            font-size: 1.6rem;
            font-weight: 700;
            color: #1e3a5f;
            margin-bottom: .3rem;
        }

        .login-right p.subtitle {
            color: #6b7280;
            font-size: .9rem;
            margin-bottom: 2rem;
        }

        .form-label {
            font-weight: 600;
            font-size: .85rem;
            color: #374151;
            margin-bottom: .3rem;
        }

        .form-control {
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            padding: .7rem 1rem;
            font-size: .9rem;
            transition: border-color .2s, box-shadow .2s;
        }

        .form-control:focus {
            border-color: #4f8ef7;
            box-shadow: 0 0 0 3px rgba(79,142,247,.15);
        }

        .input-group .form-control { border-right: none; }

        .input-group-text {
            background: #fff;
            border: 1.5px solid #e5e7eb;
            border-left: none;
            border-radius: 0 10px 10px 0;
            cursor: pointer;
            color: #6b7280;
        }

        .btn-login {
            background: linear-gradient(135deg, #4f8ef7, #6c63ff);
            border: none;
            border-radius: 10px;
            padding: .75rem;
            font-size: 1rem;
            font-weight: 600;
            color: white;
            width: 100%;
            margin-top: .5rem;
            transition: transform .2s, box-shadow .2s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(79,142,247,.4);
            color: white;
        }

        .divider {
            text-align: center;
            color: #9ca3af;
            font-size: .82rem;
            margin: 1rem 0;
            position: relative;
        }

        .divider::before, .divider::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 40%;
            height: 1px;
            background: #e5e7eb;
        }

        .divider::before { left: 0; }
        .divider::after { right: 0; }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            border-radius: 10px;
            padding: .75rem 1rem;
            font-size: .85rem;
            margin-bottom: 1rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .login-wrapper { flex-direction: column; width: 95%; }
            .login-left { padding: 2rem; }
            .login-right { padding: 2rem; }
        }
    </style>
</head>
<body>

<div class="login-wrapper">



    {{-- Panel Kanan --}}
    <div class="login-right">
        <h2>Selamat Datang 👋</h2>
        <p class="subtitle">Masuk ke akun Anda untuk melanjutkan</p>

        {{-- Error --}}
        @if ($errors->any())
        <div class="alert-error">
            <i class="bi bi-exclamation-circle me-1"></i>
            {{ $errors->first() }}
        </div>
        @endif

        @if (session('status'))
        <div class="alert alert-success rounded-3 py-2 px-3 small mb-3">
            {{ session('status') }}
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
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
                    required autofocus>
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
                        required>
                    <span class="input-group-text" onclick="togglePassword()">
                        <i class="bi bi-eye" id="eyeIcon"></i>
                    </span>
                </div>
            </div>

            {{-- Remember Me --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label small text-muted" for="remember">Ingat saya</label>
                </div>
                @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="small text-decoration-none" style="color:#4f8ef7">
                    Lupa password?
                </a>
                @endif
            </div>

            <button type="submit" class="btn btn-login">
                <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
            </button>

            <div class="divider mt-3">atau</div>

            <div class="text-center small text-muted">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-decoration-none fw-semibold" style="color:#4f8ef7">
                    Daftar sekarang
                </a>
            </div>
        </form>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function togglePassword() {
        const input = document.getElementById('passwordInput');
        const icon = document.getElementById('eyeIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'bi bi-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'bi bi-eye';
        }
    }
</script>

</body>
</html>