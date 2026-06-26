<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email | Smart Center Indonesia</title>
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
            width:min(480px,100%);background:white;border-radius:28px;overflow:hidden;
            box-shadow:0 40px 80px rgba(0,0,0,.45),0 0 0 1px rgba(255,255,255,.08);
            animation:slideUp .5s cubic-bezier(.22,1,.36,1) both;position:relative;z-index:1;
        }
        @keyframes slideUp{from{opacity:0;transform:translateY(28px);}to{opacity:1;transform:translateY(0);}}
        .card-header-band{
            background:linear-gradient(135deg,#0369a1,#0284c7,#38bdf8);
            padding:2rem 2.5rem 1.5rem;color:white;text-align:center;
        }
        .brand-icon{
            width:64px;height:64px;border-radius:50%;
            background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;
            font-size:28px;margin:0 auto 14px;border:2px solid rgba(255,255,255,.3);
        }
        .card-body-inner{padding:2rem 2.5rem;}
        h5,h6{font-family:'Plus Jakarta Sans','Inter',sans-serif;}
        .btn-verify{
            background:linear-gradient(135deg,#0369a1,#0284c7 50%,#38bdf8);background-size:200% auto;
            border:none;border-radius:12px;padding:.85rem;font-size:.95rem;font-weight:700;
            color:white;width:100%;transition:.4s;
        }
        .btn-verify:hover{background-position:right center;transform:translateY(-2px);box-shadow:0 10px 28px rgba(2,132,199,.45);color:white;}
        @media(max-width:480px){.card-body-inner,.card-header-band{padding:1.5rem;}}
    </style>
</head>
<body>
<div class="card-wrap">
    <div class="card-header-band">
        <div class="brand-icon"><i class="bi bi-envelope-check-fill"></i></div>
        <h5 style="font-weight:800;margin:0;letter-spacing:-.01em">Verifikasi Email Anda</h5>
        <p style="opacity:.75;font-size:13px;margin-top:4px">Satu langkah lagi untuk memulai!</p>
    </div>

    <div class="card-body-inner">

        <?php if(session('status') == 'verification-link-sent'): ?>
        <div class="d-flex align-items-start gap-2 mb-4 p-3 rounded-3" style="background:#f0fdf4;border:1.5px solid #bbf7d0;color:#15803d">
            <i class="bi bi-check-circle-fill flex-shrink-0 mt-1"></i>
            <div>
                <div class="fw-semibold mb-1" style="font-size:.9rem">Email verifikasi terkirim!</div>
                <div style="font-size:.83rem">Link verifikasi baru telah dikirimkan ke alamat email Anda.</div>
            </div>
        </div>
        <?php endif; ?>

        <div class="text-center mb-4">
            <div style="font-size:4rem;line-height:1;margin-bottom:1rem;">📧</div>
            <p style="color:#374151;font-size:.92rem;line-height:1.7">
                Terima kasih telah mendaftar! Sebelum memulai, harap verifikasi alamat email Anda dengan mengklik link yang telah kami kirimkan.
            </p>
            <p style="color:#6b7280;font-size:.82rem;margin-top:.75rem">
                Tidak menerima email? Periksa folder <strong>Spam/Junk</strong> terlebih dahulu.
            </p>
        </div>

        <form method="POST" action="<?php echo e(route('verification.send')); ?>" id="resendForm">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-verify" id="resendBtn">
                <span id="resendText"><i class="bi bi-send me-2"></i>Kirim Ulang Email Verifikasi</span>
                <span id="resendLoading" class="d-none">
                    <span class="spinner-border spinner-border-sm me-2"></span>Mengirim...
                </span>
            </button>
        </form>

        <div class="d-flex align-items-center gap-2 mt-4 pt-3" style="border-top:1px solid #f3f4f6">
            <form method="POST" action="<?php echo e(route('logout')); ?>" class="w-100">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn btn-outline-secondary w-100" style="border-radius:10px;font-weight:600;font-size:.875rem">
                    <i class="bi bi-box-arrow-right me-2"></i>Keluar dari Akun
                </button>
            </form>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('resendForm').addEventListener('submit', function() {
    document.getElementById('resendText').classList.add('d-none');
    document.getElementById('resendLoading').classList.remove('d-none');
    document.getElementById('resendBtn').disabled = true;
});
</script>
</body>
</html>
<?php /**PATH /home/runner/workspace/resources/views/auth/verify-email.blade.php ENDPATH**/ ?>