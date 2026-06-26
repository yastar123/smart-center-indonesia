<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
    body { font-family: DejaVu Sans, Arial, sans-serif; margin: 0; padding: 0; background: #fff; }
    .page { width: 297mm; height: 210mm; position: relative; overflow: hidden; box-sizing: border-box; }
    .bg { position: absolute; inset: 0; background: linear-gradient(135deg, #260632 0%, #4a1259 40%, #c84ddf 100%); }
    .inner { position: absolute; inset: 15mm; border: 3px solid rgba(255,255,255,.3); border-radius: 8px; padding: 30px 40px; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; }
    .logo-icon { font-size: 48px; margin-bottom: 8px; }
    .org-name { font-size: 13px; font-weight: bold; color: rgba(255,255,255,.7); letter-spacing: 3px; text-transform: uppercase; margin-bottom: 24px; }
    .cert-label { font-size: 11px; font-weight: bold; color: rgba(255,255,255,.6); letter-spacing: 4px; text-transform: uppercase; margin-bottom: 8px; }
    .cert-title { font-size: 36px; font-weight: bold; color: #fff; margin-bottom: 20px; }
    .cert-body { font-size: 13px; color: rgba(255,255,255,.8); line-height: 1.8; margin-bottom: 20px; }
    .student-name { font-size: 28px; font-weight: bold; color: #f6af23; border-bottom: 2px solid #f6af23; padding-bottom: 6px; margin: 12px 0; }
    .cert-number { font-size: 11px; color: rgba(255,255,255,.5); margin-top: 16px; }
    .footer-row { display: flex; justify-content: space-between; width: 100%; margin-top: 20px; }
    .sign-box { text-align: center; }
    .sign-line { border-top: 1px solid rgba(255,255,255,.4); padding-top: 6px; font-size: 11px; color: rgba(255,255,255,.7); margin-top: 48px; }
    .watermark { position: absolute; bottom: 18mm; right: 18mm; font-size: 60px; opacity: .04; color: #fff; transform: rotate(-30deg); }
</style>
</head>
<body>
<div class="page">
    <div class="bg"></div>
    <div class="inner">
        <div class="org-name">Smart Center Indonesia</div>
        <div class="cert-label">Sertifikat</div>
        <div class="cert-title"><?php echo e(strtoupper($certificate->jenis)); ?></div>
        <div class="cert-body">Diberikan kepada:</div>
        <div class="student-name"><?php echo e($student->name ?? $certificate->siswa->user->name ?? 'Peserta'); ?></div>
        <div class="cert-body">Telah berhasil menyelesaikan program <strong><?php echo e($certificate->judul); ?></strong><br>
        <?php if($certificate->deskripsi): ?><?php echo e($certificate->deskripsi); ?><?php endif; ?></div>
        <?php if($certificate->tanggal_terbit): ?>
        <div style="font-size:12px;color:rgba(255,255,255,.65)">Diterbitkan pada <?php echo e($certificate->tanggal_terbit->format('d F Y')); ?></div>
        <?php endif; ?>
        <div class="cert-number">No. <?php echo e($certificate->nomor_sertifikat); ?></div>
        <div class="footer-row">
            <div class="sign-box"><div class="sign-line">Direktur Akademik</div></div>
            <div class="sign-box"><div class="sign-line">Kepala Cabang</div></div>
        </div>
    </div>
    <div class="watermark">✦</div>
</div>
</body>
</html>
<?php /**PATH /home/runner/workspace/resources/views/siswa/certificate-pdf.blade.php ENDPATH**/ ?>