<?php $__env->startSection('title', 'Detail Paket Belajar'); ?>
<?php $__env->startSection('page-title', 'Detail Paket Belajar'); ?>

<?php $__env->startSection('content'); ?>
<div class="fade-up">

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo e(route('owner.course-package.index')); ?>">Paket Belajar</a></li>
        <li class="breadcrumb-item active"><?php echo e($coursePackage->nama); ?></li>
    </ol>
</nav>

<div class="row g-3">
    <div class="col-md-4">
        <div class="dashboard-card h-100">
            <div class="text-center mb-3">
                <?php if($coursePackage->is_unggulan): ?>
                <div class="mb-2"><span class="badge" style="background:rgba(246,175,35,.2);color:#e09000;font-size:12px"><i class="bi bi-star-fill me-1"></i>Paket Unggulan</span></div>
                <?php endif; ?>
                <div style="width:80px;height:80px;border-radius:20px;background:linear-gradient(135deg,#260632,#c84ddf);display:flex;align-items:center;justify-content:center;font-size:36px;color:white;margin:0 auto 12px">
                    <i class="bi bi-box-seam"></i>
                </div>
                <h5 class="fw-bold mb-1"><?php echo e($coursePackage->nama); ?></h5>
                <div class="fw-bold text-primary fs-5">Rp <?php echo e(number_format($coursePackage->harga, 0, ',', '.')); ?></div>
            </div>

            <div class="d-flex justify-content-center gap-2 mb-3">
                <span class="badge px-3 py-2" style="background:rgba(200,77,223,.15);color:#461256">
                    <?php echo e(ucfirst($coursePackage->jenis)); ?>

                </span>
                <?php if($coursePackage->status === 'aktif'): ?>
                    <span class="badge px-3 py-2" style="background:rgba(16,185,129,.15);color:#059669">Aktif</span>
                <?php else: ?>
                    <span class="badge px-3 py-2" style="background:rgba(246,175,35,.15);color:#e09000">Draft</span>
                <?php endif; ?>
            </div>

            <div class="border-top pt-3">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted" style="font-size:13px"><i class="bi bi-calendar3 me-1"></i>Jumlah Sesi</span>
                    <span class="fw-semibold"><?php echo e($coursePackage->jumlah_pertemuan); ?> Sesi</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted" style="font-size:13px"><i class="bi bi-clock-history me-1"></i>Durasi</span>
                    <span class="fw-semibold"><?php echo e($coursePackage->durasi_bulan); ?> Bulan</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted" style="font-size:13px"><i class="bi bi-building me-1"></i>Cabang</span>
                    <span class="fw-semibold"><?php echo e($coursePackage->cabang->name ?? 'Semua'); ?></span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted" style="font-size:13px"><i class="bi bi-book me-1"></i>Mata Pelajaran</span>
                    <span class="fw-semibold"><?php echo e($coursePackage->mataPelajaran->count()); ?></span>
                </div>
            </div>

            <?php if($coursePackage->deskripsi): ?>
            <p class="text-muted mt-3" style="font-size:13px"><?php echo e($coursePackage->deskripsi); ?></p>
            <?php endif; ?>

            <div class="d-grid gap-2 mt-3">
                <a href="<?php echo e(route('owner.course-package.edit', $coursePackage)); ?>" class="btn btn-primary">
                    <i class="bi bi-pencil me-2"></i>Edit Paket
                </a>
                <a href="<?php echo e(route('owner.course-package.index')); ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="dashboard-card mb-3">
            <h6 class="fw-bold mb-3"><i class="bi bi-journal-bookmark me-2 text-primary"></i>Mata Pelajaran Termasuk</h6>
            <?php $__empty_1 = true; $__currentLoopData = $coursePackage->mataPelajaran; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="d-flex align-items-center gap-2 py-2 border-bottom">
                    <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#461256,#c84ddf);display:flex;align-items:center;justify-content:center;color:white;font-size:13px;flex-shrink:0">
                        <i class="bi bi-book"></i>
                    </div>
                    <div>
                        <div class="fw-semibold" style="font-size:14px"><?php echo e($c->nama); ?></div>
                        <div class="text-muted" style="font-size:11px"><?php echo e($c->kode); ?> · <?php echo e(ucfirst($c->kategori ?? 'academic')); ?></div>
                    </div>
                    <?php if($c->status === 'aktif'): ?>
                        <span class="badge ms-auto" style="background:rgba(16,185,129,.15);color:#059669;font-size:10px">Aktif</span>
                    <?php endif; ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-muted mb-0" style="font-size:13px">Belum ada mata pelajaran yang terhubung ke paket ini.</p>
            <?php endif; ?>
        </div>

        <?php if($coursePackage->fitur): ?>
        <div class="dashboard-card">
            <h6 class="fw-bold mb-3"><i class="bi bi-list-check me-2 text-success"></i>Fitur Paket</h6>
            <?php $__currentLoopData = $coursePackage->fitur; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <i class="bi bi-check-circle-fill text-success"></i>
                    <span style="font-size:14px"><?php echo e($f); ?></span>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php endif; ?>
    </div>
</div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/owner/course-package/detail.blade.php ENDPATH**/ ?>