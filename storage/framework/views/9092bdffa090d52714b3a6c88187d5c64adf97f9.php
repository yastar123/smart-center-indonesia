<?php $__env->startSection('title','Sertifikat Saya'); ?>
<?php $__env->startSection('page-title','Sertifikat Saya'); ?>

<?php $__env->startSection('content'); ?>


<div class="dashboard-card mb-4 fade-up" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
    <div class="row align-items-center g-3" style="position:relative">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-3">
                <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0"><i class="bi bi-award"></i></div>
                <div>
                    <h5 class="fw-bold mb-0" style="color:white">Sertifikat Saya</h5>
                    <span style="font-size:12px;opacity:.85">Sertifikat yang diterbitkan admin untuk mata pelajaran Anda</span>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-md-end">
            <div style="font-size:28px;font-weight:800;color:white"><?php echo e($certificates->count()); ?></div>
            <div style="font-size:12px;opacity:.7">Sertifikat Tersedia</div>
        </div>
    </div>
</div>

<?php if($certificates->count() > 0): ?>
<h6 class="fw-bold mb-3" style="font-size:14px;text-transform:uppercase;letter-spacing:.05em;color:var(--text-muted)">
    <i class="bi bi-award text-warning me-2"></i>Sertifikat
</h6>
<?php $otherCerts = $certificates; ?>
<div class="row g-3 fade-up">
    <?php $__currentLoopData = $otherCerts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cert): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php
        $colors = ['kompetensi'=>['#c84ddf','rgba(200,77,223,.1)','bi-patch-check'], 'kelulusan'=>['#10b981','rgba(16,185,129,.1)','bi-mortarboard'], 'prestasi'=>['#f59e0b','rgba(245,158,11,.1)','bi-trophy'], 'partisipasi'=>['#6366f1','rgba(99,102,241,.1)','bi-star']];
        $c = $colors[$cert->jenis] ?? ['#64748b','rgba(100,116,139,.1)','bi-award'];
    ?>
    <div class="col-md-6 col-lg-4">
        <div class="dashboard-card h-100" style="border-top:4px solid <?php echo e($c[0]); ?>">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div style="width:44px;height:44px;border-radius:12px;background:<?php echo e($c[1]); ?>;display:flex;align-items:center;justify-content:center">
                    <i class="bi <?php echo e($c[2]); ?>" style="font-size:20px;color:<?php echo e($c[0]); ?>"></i>
                </div>
                <span class="badge" style="background:<?php echo e($c[1]); ?>;color:<?php echo e($c[0]); ?>;border:1px solid <?php echo e($c[0]); ?>44;text-transform:capitalize"><?php echo e($cert->jenis); ?></span>
            </div>
            <h6 class="fw-bold mb-1"><?php echo e($cert->judul); ?></h6>
            <div class="d-flex align-items-center gap-2 mb-3" style="font-size:12px;color:var(--text-muted)">
                <i class="bi bi-calendar3"></i>
                <span><?php echo e($cert->tanggal_terbit ? $cert->tanggal_terbit->format('d M Y') : '–'); ?></span>
            </div>
            <div class="pt-2 border-top d-flex justify-content-between align-items-center">
                <code style="font-size:10px;color:var(--text-muted)"><?php echo e($cert->nomor_sertifikat); ?></code>
                <a href="<?php echo e(route('siswa.certificates.download', $cert)); ?>" class="btn btn-sm btn-primary" target="_blank">
                    <i class="bi bi-download me-1"></i>Unduh
                </a>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php endif; ?>

<?php if($certificates->isEmpty()): ?>
<div class="dashboard-card fade-up">
    <div class="text-center py-5">
        <i class="bi bi-award" style="font-size:56px;opacity:.2;display:block;margin-bottom:16px;color:var(--primary)"></i>
        <div class="fw-bold mb-1" style="font-size:16px">Belum Ada Sertifikat</div>
        <div class="text-muted" style="font-size:13px">Sertifikat akan muncul setelah admin menerbitkannya untuk Anda.</div>
    </div>
</div>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Edu Juanda Pratama\Downloads\smart-center-indonesia\smart-center-indonesia\resources\views/siswa/certificates.blade.php ENDPATH**/ ?>