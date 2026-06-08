<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>419 — Sesi Kedaluwarsa | Smart Center</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *{margin:0;padding:0;box-sizing:border-box;}
        body{
            min-height:100vh;
            background:linear-gradient(135deg,#260632 0%,#461256 40%,#461256 75%,#c84ddf 100%);
            display:flex;align-items:center;justify-content:center;
            font-family:'Inter','Segoe UI',sans-serif;
            padding:2rem 1rem;position:relative;overflow:hidden;
        }
        body::before{
            content:'';position:fixed;width:500px;height:500px;
            background:radial-gradient(circle,rgba(200,77,223,.25) 0%,transparent 70%);
            top:-150px;right:-150px;border-radius:50%;
            animation:orb 8s ease-in-out infinite alternate;pointer-events:none;
        }
        @keyframes orb{from{transform:translate(0,0);}to{transform:translate(30px,20px);}}

        .error-card{
            width:min(520px,100%);
            background:white;border-radius:28px;overflow:hidden;
            box-shadow:0 40px 80px rgba(0,0,0,.45);
            animation:slideUp .5s cubic-bezier(.22,1,.36,1) both;
            position:relative;z-index:1;
        }
        @keyframes slideUp{from{opacity:0;transform:translateY(28px);}to{opacity:1;transform:translateY(0);}}

        .error-band{
            background:linear-gradient(135deg,#461256,#68117e,#c84ddf);
            padding:2rem 2.5rem 1.5rem;
            text-align:center;color:white;position:relative;overflow:hidden;
        }
        .error-icon-wrap{
            width:64px;height:64px;border-radius:20px;
            background:rgba(255,255,255,.2);
            display:flex;align-items:center;justify-content:center;
            font-size:28px;margin:0 auto 14px;
            border:1.5px solid rgba(255,255,255,.28);
        }
        .error-body{padding:2rem 2.5rem;}
        .btn-refresh{
            background:linear-gradient(135deg,#68117e,#c84ddf 50%,#c84ddf);
            background-size:200% auto;border:none;border-radius:12px;
            padding:.85rem 2rem;font-size:.95rem;font-weight:700;
            color:white;transition:.4s;text-decoration:none;display:inline-flex;
            align-items:center;gap:8px;
        }
        .btn-refresh:hover{background-position:right center;transform:translateY(-2px);box-shadow:0 10px 28px rgba(200,77,223,.45);color:white;}
        @media(max-width:480px){.error-body,.error-band{padding:1.5rem;}}
    </style>
</head>
<body>
<div class="error-card">
    <div class="error-band">
        <div class="error-icon-wrap">
            <i class="bi bi-clock-history"></i>
        </div>
        <h5 style="font-weight:800;margin:0;font-family:'Plus Jakarta Sans',sans-serif;letter-spacing:-.01em">
            Sesi Kedaluwarsa
        </h5>
        <p style="opacity:.75;font-size:13px;margin-top:6px">
            Halaman ini telah kedaluwarsa karena tidak ada aktivitas
        </p>
    </div>
    <div class="error-body text-center">
        <p style="color:#6b7280;font-size:14px;line-height:1.7;margin-bottom:2rem">
            Token keamanan halaman ini sudah tidak valid. Muat ulang halaman dan coba lagi.
        </p>
        <a href="javascript:location.reload()" class="btn-refresh">
            <i class="bi bi-arrow-clockwise"></i> Muat Ulang Halaman
        </a>
        <div class="mt-3">
            <a href="{{ url('/login') }}" style="color:#c84ddf;font-size:13px;text-decoration:none;font-weight:600">
                <i class="bi bi-box-arrow-in-right me-1"></i>Login ulang
            </a>
        </div>
        <div class="mt-4 pt-3" style="border-top:1px solid #f3f4f6;">
            <p style="color:#9ca3af;font-size:12px;margin:0">Error 419 · Smart Center Indonesia</p>
        </div>
    </div>
</div>
</body>
</html>
