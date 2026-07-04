<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password | Smart Center Indonesia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
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
            width:min(520px,100%);background:white;border-radius:28px;overflow:hidden;
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
        .section-label{
            font-size:.72rem;font-weight:800;text-transform:uppercase;letter-spacing:.09em;
            color:#461256;margin-bottom:.9rem;display:flex;align-items:center;gap:8px;
        }
        .section-label::before{content:'';width:3px;height:16px;background:linear-gradient(135deg,#461256,#c84ddf);border-radius:4px;flex-shrink:0;}
        .wa-card{
            display:flex;align-items:center;gap:14px;
            padding:14px 18px;border-radius:16px;border:1.5px solid #e5e7eb;
            text-decoration:none;transition:all .2s;background:#fafafa;margin-bottom:10px;
        }
        .wa-card:hover{border-color:#25d366;background:#f0fff4;transform:translateY(-2px);box-shadow:0 6px 20px rgba(37,211,102,.15);}
        .wa-icon{width:46px;height:46px;border-radius:13px;background:#25d366;display:flex;align-items:center;justify-content:center;color:white;font-size:22px;flex-shrink:0;}
        .wa-label{font-weight:700;font-size:14px;color:#111;}
        .wa-desc{font-size:12px;color:#6b7280;}
        .wa-number{font-size:12px;color:#25d366;font-weight:600;font-family:monospace;}
        .badge-primary{background:#fdf4ff;color:#461256;border:1px solid #e9d5ff;padding:2px 8px;border-radius:20px;font-size:11px;font-weight:700;}
        .info-box{background:#f8f7ff;border:1.5px solid #e9d5ff;border-radius:14px;padding:1rem 1.1rem;}
        @media(max-width:480px){.card-body-inner,.card-header-band{padding:1.5rem;}}
    </style>
</head>
<body>
<div class="card-wrap">
    <div class="card-header-band">
        <div class="brand-icon"><i class="bi bi-key-fill"></i></div>
        <h5 style="font-weight:800;margin:0;letter-spacing:-.01em">Lupa Password?</h5>
        <p style="opacity:.75;font-size:13px;margin-top:4px">Hubungi admin cabang kami melalui WhatsApp untuk reset password.</p>
    </div>

    <div class="card-body-inner">

        <div class="info-box mb-4 d-flex align-items-start gap-2">
            <i class="bi bi-info-circle-fill flex-shrink-0 mt-1" style="color:#c84ddf"></i>
            <div style="font-size:13px;color:#4b5563;line-height:1.6">
                Untuk keamanan akun, reset password dilakukan oleh <strong>admin</strong>.
                Pilih cabang terdekat Anda dan hubungi via WhatsApp dengan menyebutkan <strong>nama lengkap</strong> dan <strong>email terdaftar</strong>.
            </div>
        </div>

        <?php if($waNumbers->count() > 0): ?>
        <div class="section-label"><i class="bi bi-whatsapp"></i> Hubungi Admin Cabang</div>

        <?php $__currentLoopData = $waNumbers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $number = preg_replace('/\D/', '', $wa->number);
            if (!str_starts_with($number, '62')) $number = '62' . ltrim($number, '0');
            $msg = urlencode('Halo admin, saya lupa password akun bimbel saya dan ingin melakukan reset password.');
            $waUrl = 'https://wa.me/' . $number . '?text=' . $msg;
        ?>
        <a href="<?php echo e($waUrl); ?>" target="_blank" class="wa-card">
            <div class="wa-icon"><i class="bi bi-whatsapp"></i></div>
            <div class="flex-grow-1">
                <div class="wa-label">
                    <?php echo e($wa->label); ?>

                    <?php if($wa->is_primary): ?><span class="badge-primary ms-1">Pusat</span><?php endif; ?>
                </div>
                <?php if($wa->description): ?><div class="wa-desc"><?php echo e($wa->description); ?></div><?php endif; ?>
                <div class="wa-number"><?php echo e($wa->number); ?></div>
            </div>
            <i class="bi bi-box-arrow-up-right text-muted" style="font-size:13px"></i>
        </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <?php else: ?>
        <div class="text-center py-4">
            <div style="font-size:3rem">📱</div>
            <p class="mt-3 fw-semibold" style="color:#374151">Hubungi Admin</p>
            <p class="text-muted" style="font-size:13px">Silakan hubungi admin untuk mendapatkan bantuan reset password.</p>
        </div>
        <?php endif; ?>

        <div class="text-center mt-4 pt-3 border-top">
            <a href="<?php echo e(route('login')); ?>" class="d-inline-flex align-items-center gap-1 fw-semibold text-decoration-none" style="color:#c84ddf;font-size:.875rem">
                <i class="bi bi-arrow-left"></i> Kembali ke Login
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php /**PATH /home/runner/workspace/resources/views/auth/forgot-password.blade.php ENDPATH**/ ?>