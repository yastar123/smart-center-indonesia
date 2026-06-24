<?php
    $loginStats = [
        'students' => \App\Models\Student::where('status','aktif')->count(),
        'teachers' => \App\Models\Teacher::where('status','aktif')->count(),
        'branches' => \App\Models\Branch::count(),
        'schedules'=> \App\Models\Schedule::count(),
    ];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Login | Smart Center Indonesia</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            min-height: 100vh;
            width: 100%;
            background: linear-gradient(135deg, #260632 0%, #461256 40%, #68117e 75%, #c84ddf 100%);
            display: flex;
            align-items: stretch;
            justify-content: stretch;
            font-family: 'Inter', 'Segoe UI', sans-serif;
            padding: 0;
            position: relative;
            overflow: hidden;
        }
        h1, h2, h3, h4, h5, h6, .greeting { font-family: 'Plus Jakarta Sans', 'Inter', sans-serif; letter-spacing: -.02em; }

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
            width: 100%;
            min-height: 100vh;
            border-radius: 0;
            overflow: hidden;
            box-shadow: none;
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
            flex: 0 0 50%;
            width: 50%;
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
            gap: 10px;
            margin-top: .5rem;
        }
        .feature-item {
            background: rgba(255,255,255,0.07);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 14px;
            padding: 14px 14px 12px;
            transition: background .3s, transform .3s, border-color .3s, box-shadow .3s;
            cursor: default;
            position: relative;
            overflow: hidden;
        }
        .feature-item::before {
            content:'';
            position:absolute;
            top:0;left:0;right:0;height:2px;
            background:linear-gradient(90deg,rgba(200,77,223,.6),rgba(246,175,35,.4));
            opacity:0;
            transition:opacity .3s;
        }
        .feature-item:hover {
            background: rgba(255,255,255,0.13);
            transform: translateY(-3px);
            border-color: rgba(255,255,255,0.22);
            box-shadow: 0 8px 24px rgba(0,0,0,.25);
        }
        .feature-item:hover::before { opacity:1; }
        .feature-item i { font-size: 19px; margin-bottom: 4px; display: block; }
        .feature-num {
            font-size: 1.35rem;
            font-weight: 800;
            color: white;
            font-family: 'Plus Jakarta Sans', sans-serif;
            letter-spacing: -.03em;
            line-height: 1;
            margin: 2px 0 3px;
        }
        .feature-item span { font-size: 11px; font-weight: 500; color: rgba(203,213,225,.85); line-height: 1.3; display:block; }

        .left-footer {
            margin-top: auto;
            padding-top: 2rem;
            font-size: 12px;
            color: rgba(255,255,255,0.35);
        }

        /* ===== RIGHT PANEL ===== */
        .login-right {
            flex: 0 0 50%;
            width: 50%;
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
            border-radius: 14px;
            padding: 14px 16px;
            font-size: .78rem;
            color: #68117e;
            margin-top: 1.25rem;
        }
        .demo-credentials strong { display: block; margin-bottom: 8px; color: #461256; font-size: .82rem; }
        .demo-grid { display: flex; flex-direction: column; gap: 5px; }
        .demo-row {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: .76rem;
            color: #4b2063;
            cursor: pointer;
            padding: 4px 6px;
            border-radius: 8px;
            margin: 0 -6px;
            transition: background .2s;
        }
        .demo-row:hover {
            background: rgba(200,77,223,.12);
        }
        .demo-row:hover .bi-cursor-fill { opacity: .85 !important; }
        .demo-role {
            font-size: .68rem;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: .04em;
            flex-shrink: 0;
            min-width: 44px;
            text-align: center;
        }
        .demo-role.owner { background: linear-gradient(135deg,#68117e,#c84ddf); color: white; }
        .demo-role.admin { background: linear-gradient(135deg,#461256,#68117e); color: white; }
        .demo-role.guru  { background: linear-gradient(135deg,#059669,#10b981); color: white; }
        .demo-role.siswa { background: linear-gradient(135deg,#d97706,#f6af23); color: white; }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .login-wrapper {
                flex-direction: column;
                min-height: 100vh;
            }
            .login-left,
            .login-right {
                flex: 1 1 auto;
                width: 100%;
            }
            .login-left {
                min-height: 45vh;
            }
            .login-right {
                padding: 2rem 1.5rem;
            }
            .feature-grid { grid-template-columns: 1fr 1fr; }
            .left-footer { display: none; }
        }
        @media (max-width: 480px) {
            .login-wrapper { border-radius: 16px; }
            .login-right { padding: 1.5rem; }
        }
        @media (max-width: 360px) {
            .feature-grid { gap: 6px; }
            .feature-item { padding: 8px; }
            .feature-num { font-size: 1rem; }
        }
    </style>
</head>
<body>

<div class="login-wrapper">

    
    <div class="login-left">

        <a href="<?php echo e(url('/')); ?>" style="display:inline-flex;align-items:center;gap:6px;color:rgba(255,255,255,.6);font-size:.78rem;font-weight:600;text-decoration:none;margin-bottom:1.25rem;transition:color .2s;" onmouseover="this.style.color='rgba(255,255,255,.95)'" onmouseout="this.style.color='rgba(255,255,255,.6)'">
            <i class="bi bi-arrow-left" style="font-size:.8rem"></i> Kembali ke Beranda
        </a>

        <div class="brand-badge">
            <div class="brand-badge-icon">
                <i class="bi bi-mortarboard-fill" style="color:#fff"></i>
            </div>
            <span>Smart Center Indonesia</span>
        </div>

        <h1>Platform Manajemen<br>Bimbel <span style="background:linear-gradient(90deg,#f6af23,#c84ddf);-webkit-background-clip:text;-webkit-text-fill-color:transparent">Terpadu</span></h1>

        <p class="lead">Kelola siswa, guru, keuangan, dan jadwal belajar seluruh cabang dalam satu platform cerdas dan terintegrasi.</p>

        <?php
            $fmtStat = function($n) {
                if ($n <= 0) return '0';
                if ($n >= 1000) return number_format($n/1000, 1).'K';
                return $n;
            };
        ?>
        <div class="feature-grid">
            <div class="feature-item">
                <i class="bi bi-people-fill" style="color:#c84ddf"></i>
                <div class="feature-num"><?php echo e($fmtStat($loginStats['students'])); ?><?php echo e($loginStats['students'] > 0 ? '+' : ''); ?></div>
                <span>Siswa Aktif Terdaftar</span>
            </div>
            <div class="feature-item">
                <i class="bi bi-person-workspace" style="color:#10b981"></i>
                <div class="feature-num"><?php echo e($fmtStat($loginStats['teachers'])); ?><?php echo e($loginStats['teachers'] > 0 ? '+' : ''); ?></div>
                <span>Guru Berpengalaman</span>
            </div>
            <div class="feature-item">
                <i class="bi bi-building-fill-check" style="color:#f6af23"></i>
                <div class="feature-num"><?php echo e($loginStats['branches'] ?: '1'); ?></div>
                <span>Cabang Aktif</span>
            </div>
            <div class="feature-item">
                <i class="bi bi-calendar-check-fill" style="color:#ab8db2"></i>
                <div class="feature-num"><?php echo e($fmtStat($loginStats['schedules'])); ?><?php echo e($loginStats['schedules'] > 0 ? '+' : ''); ?></div>
                <span>Sesi Belajar</span>
            </div>
            <div class="feature-item">
                <i class="bi bi-cash-stack" style="color:#f6af23"></i>
                <div class="feature-num">Rp100M+</div>
                <span>Transaksi Terkelola</span>
            </div>
            <div class="feature-item">
                <i class="bi bi-graph-up-arrow" style="color:#e8b4f5"></i>
                <div class="feature-num">24/7</div>
                <span>Monitoring Real-time</span>
            </div>
        </div>

        <div class="left-footer">
            &copy; <?php echo e(date('Y')); ?> Smart Center Indonesia. All rights reserved.
        </div>

    </div>

    
    <div class="login-right">

        <div class="greeting">Selamat Datang 👋</div>
        <p class="subtitle">Masuk ke akun Anda untuk melanjutkan</p>

        
        <?php if($errors->any()): ?>
        <div class="alert-error">
            <i class="bi bi-exclamation-triangle-fill flex-shrink-0"></i>
            <span><?php echo e($errors->first()); ?></span>
        </div>
        <?php endif; ?>

        <?php if(session('status')): ?>
        <div class="alert alert-success rounded-3 py-2 px-3 small mb-3 border-0" style="background:#f0fdf4;color:#15803d">
            <i class="bi bi-check-circle-fill me-2"></i><?php echo e(session('status')); ?>

        </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('login')); ?>" id="loginForm">
            <?php echo csrf_field(); ?>

            
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input
                    type="email"
                    name="email"
                    class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                    value="<?php echo e(old('email')); ?>"
                    placeholder="contoh@email.com"
                    required autofocus autocomplete="email">
            </div>

            
            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <input
                        type="password"
                        name="password"
                        id="passwordInput"
                        class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        placeholder="Masukkan password"
                        required autocomplete="current-password">
                    <span class="input-group-text" onclick="togglePassword()" title="Tampilkan password">
                        <i class="bi bi-eye" id="eyeIcon"></i>
                    </span>
                </div>
            </div>

            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label small text-muted" for="remember">Ingat saya</label>
                </div>
                <?php if(Route::has('password.request')): ?>
                <a href="<?php echo e(route('password.request')); ?>" class="small text-decoration-none fw-semibold" style="color:#c84ddf">
                    Lupa password?
                </a>
                <?php endif; ?>
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
                <a href="<?php echo e(route('register')); ?>" class="text-decoration-none fw-semibold" style="color:#c84ddf">
                    Daftar sekarang
                </a>
            </div>
        </form>

        <div class="demo-credentials">
            <strong><i class="bi bi-info-circle me-1"></i> Akun Demo</strong>
            <p style="font-size:.72rem;color:#9c6db5;margin:.25rem 0 .5rem;font-weight:400">Klik baris untuk isi otomatis</p>
            <div class="demo-grid mt-1">
                <div class="demo-row" data-email="adminpusatsci@akademi.com" data-password="password" title="Klik untuk isi otomatis">
                    <span class="demo-role owner">Owner</span>
                    <span>adminpusatsci@akademi.com / <b>password</b></span>
                    <i class="bi bi-cursor-fill ms-auto" style="font-size:.65rem;opacity:.4"></i>
                </div>
                <div class="demo-row" data-email="admincabangsci@akademi.com" data-password="password" title="Klik untuk isi otomatis">
                    <span class="demo-role admin">Admin</span>
                    <span>admincabangsci@akademi.com / <b>password</b></span>
                    <i class="bi bi-cursor-fill ms-auto" style="font-size:.65rem;opacity:.4"></i>
                </div>
                <div class="demo-row" data-email="gurusci@gmail.com" data-password="password123" title="Klik untuk isi otomatis">
                    <span class="demo-role guru">Guru</span>
                    <span>gurusci@gmail.com / <b>password123</b></span>
                    <i class="bi bi-cursor-fill ms-auto" style="font-size:.65rem;opacity:.4"></i>
                </div>
                <div class="demo-row" data-email="siswasci@gmail.com" data-password="password12" title="Klik untuk isi otomatis">
                    <span class="demo-role siswa">Siswa</span>
                    <span>siswasci@gmail.com / <b>password12</b></span>
                    <i class="bi bi-cursor-fill ms-auto" style="font-size:.65rem;opacity:.4"></i>
                </div>
            </div>
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

    // Clickable demo credentials — auto-fill the login form
    document.querySelectorAll('.demo-row[data-email]').forEach(function(row) {
        row.addEventListener('click', function() {
            var emailInput    = document.querySelector('[name="email"]');
            var passwordInput = document.getElementById('passwordInput');
            emailInput.value    = row.dataset.email;
            passwordInput.value = row.dataset.password;
            // Make sure password is hidden after fill
            passwordInput.type = 'password';
            document.getElementById('eyeIcon').className = 'bi bi-eye';
            // Visual feedback: briefly highlight the button
            emailInput.focus();
            var btn = document.getElementById('loginBtn');
            btn.style.transform = 'scale(1.02)';
            setTimeout(function(){ btn.style.transform = ''; }, 200);
        });
    });

    // Staggered entrance + count-up for feature items
    document.querySelectorAll('.feature-item').forEach(function(el, i) {
        el.style.opacity = '0';
        el.style.transform = 'translateY(12px)';
        setTimeout(function() {
            el.style.transition = 'opacity .4s ease, transform .4s ease';
            el.style.opacity  = '1';
            el.style.transform = 'translateY(0)';
            // Count-up for numeric feature stats
            var numEl = el.querySelector('.feature-num');
            if (!numEl) return;
            var rawText = numEl.textContent.trim();
            var numMatch = rawText.match(/^([\d,.]+)/);
            if (!numMatch) return; // skip non-numeric (e.g. "Rp100M+", "24/7")
            var target = parseInt(numMatch[1].replace(/[,.]/g, ''), 10);
            if (!target || target < 2) return;
            var suffix = rawText.replace(numMatch[0], '');
            var duration = 900;
            var startTime = null;
            numEl.textContent = '0' + suffix;
            function step(ts) {
                if (!startTime) startTime = ts;
                var progress = Math.min((ts - startTime) / duration, 1);
                var ease = 1 - Math.pow(1 - progress, 3);
                numEl.textContent = Math.round(ease * target).toLocaleString('id') + suffix;
                if (progress < 1) requestAnimationFrame(step);
            }
            requestAnimationFrame(step);
        }, 350 + i * 60);
    });
</script>
<script>
(function(){
    var t = localStorage.getItem('theme');
    if (t === 'dark') {
        document.body.style.background = 'linear-gradient(135deg, #1a0426 0%, #2d0840 40%, #3d0f54 75%, #8b2faa 100%)';
    }
})();
</script>
</body>
</html>
<?php /**PATH /home/runner/workspace/resources/views/auth/login.blade.php ENDPATH**/ ?>