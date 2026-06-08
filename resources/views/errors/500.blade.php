<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 — Kesalahan Server | Smart Center</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *{margin:0;padding:0;box-sizing:border-box;}
        body{
            min-height:100vh;
            background:linear-gradient(135deg,#1c0505 0%,#450a0a 40%,#dc2626 100%);
            display:flex;align-items:center;justify-content:center;
            font-family:'Inter','Segoe UI',sans-serif;
            padding:2rem 1rem;position:relative;overflow:hidden;
        }
        body::before{
            content:'';position:fixed;width:500px;height:500px;
            background:radial-gradient(circle,rgba(239,68,68,.25) 0%,transparent 70%);
            top:-150px;right:-150px;border-radius:50%;
            animation:orb 8s ease-in-out infinite alternate;pointer-events:none;
        }
        @keyframes orb{from{transform:translate(0,0);}to{transform:translate(30px,20px);}}

        .error-card{
            width:min(560px,100%);
            background:white;border-radius:28px;overflow:hidden;
            box-shadow:0 40px 80px rgba(0,0,0,.45);
            animation:slideUp .5s cubic-bezier(.22,1,.36,1) both;
            position:relative;z-index:1;
        }
        @keyframes slideUp{from{opacity:0;transform:translateY(28px);}to{opacity:1;transform:translateY(0);}}

        .error-band{
            background:linear-gradient(135deg,#450a0a,#991b1b,#ef4444);
            padding:2.5rem 2.5rem 2rem;
            text-align:center;color:white;position:relative;overflow:hidden;
        }
        .error-band::before{content:'';position:absolute;right:-40px;top:-40px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;}
        .error-num{
            font-family:'Plus Jakarta Sans',sans-serif;
            font-size:clamp(80px,14vw,120px);font-weight:900;line-height:1;
            letter-spacing:-.04em;color:rgba(255,255,255,.12);
            position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);
            user-select:none;pointer-events:none;
        }
        .error-icon-wrap{
            width:72px;height:72px;border-radius:22px;
            background:rgba(255,255,255,.18);
            display:flex;align-items:center;justify-content:center;
            font-size:32px;margin:0 auto 16px;
            border:1.5px solid rgba(255,255,255,.28);
            position:relative;z-index:1;
        }
        .error-body{padding:2.5rem;}
        .back-btn{
            background:linear-gradient(135deg,#991b1b,#ef4444 50%,#f87171);
            background-size:200% auto;border:none;border-radius:12px;
            padding:.85rem 2rem;font-size:.95rem;font-weight:700;
            color:white;transition:.4s;text-decoration:none;display:inline-flex;
            align-items:center;gap:8px;
        }
        .back-btn:hover{background-position:right center;transform:translateY(-2px);box-shadow:0 10px 28px rgba(239,68,68,.45);color:white;}
        .home-btn{
            background:transparent;border:2px solid #e5e7eb;border-radius:12px;
            padding:.85rem 2rem;font-size:.95rem;font-weight:600;
            color:#991b1b;transition:.25s;text-decoration:none;display:inline-flex;
            align-items:center;gap:8px;
        }
        .home-btn:hover{border-color:#ef4444;color:#ef4444;background:#fef2f2;}
        @media(max-width:480px){.error-body,.error-band{padding:1.5rem;}.error-body .d-flex{flex-direction:column;}}
    </style>
</head>
<body>
<div class="error-card">
    <div class="error-band" style="position:relative">
        <div class="error-num">500</div>
        <div class="error-icon-wrap">
            <i class="bi bi-exclamation-triangle-fill"></i>
        </div>
        <h5 style="font-weight:800;margin:0;font-family:'Plus Jakarta Sans',sans-serif;letter-spacing:-.01em;position:relative;z-index:1">
            Kesalahan Server
        </h5>
        <p style="opacity:.75;font-size:13px;margin-top:6px;position:relative;z-index:1">
            Terjadi kesalahan pada server. Tim kami sedang menangani masalah ini.
        </p>
    </div>
    <div class="error-body text-center">
        <p style="color:#6b7280;font-size:14px;line-height:1.7;margin-bottom:2rem;max-width:380px;margin-left:auto;margin-right:auto">
            Mohon maaf atas ketidaknyamanan ini. Coba muat ulang halaman atau kembali
            ke beranda. Jika masalah berlanjut, hubungi administrator.
        </p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="javascript:location.reload()" class="back-btn">
                <i class="bi bi-arrow-clockwise"></i> Muat Ulang
            </a>
            <a href="{{ url('/dashboard') }}" class="home-btn">
                <i class="bi bi-house-door"></i> Beranda
            </a>
        </div>
        <div class="mt-4 pt-3" style="border-top:1px solid #f3f4f6;">
            <p style="color:#9ca3af;font-size:12px;margin:0">
                Error 500 · Smart Center Indonesia
            </p>
        </div>
    </div>
</div>
</body>
</html>
