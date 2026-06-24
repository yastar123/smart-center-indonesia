<?php $__env->startSection('title', 'Detail Mata Pelajaran'); ?>
<?php $__env->startSection('page-title', 'Detail Mata Pelajaran'); ?>

<?php $__env->startSection('content'); ?>
<div class="fade-up">

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.subject.index')); ?>">Mata Pelajaran</a></li>
        <li class="breadcrumb-item active"><?php echo e($subject->nama); ?></li>
    </ol>
</nav>

<div class="row g-3">
    
    <div class="col-md-4">
        <div class="dashboard-card h-100">
            <div class="text-center mb-3">
                <div style="width:80px;height:80px;border-radius:20px;background:linear-gradient(135deg,#260632,#c84ddf);display:flex;align-items:center;justify-content:center;font-size:36px;color:white;margin:0 auto 12px">
                    <i class="bi bi-book-half"></i>
                </div>
                <h5 class="fw-bold mb-1"><?php echo e($subject->nama); ?></h5>
                <code class="text-muted"><?php echo e($subject->kode); ?></code>
            </div>

            <div class="d-flex justify-content-center gap-2 mb-3">
                <?php if($subject->kategori === 'academic'): ?>
                    <span class="badge px-3 py-2" style="background:rgba(59,130,246,.15);color:#3b82f6">
                        <i class="bi bi-mortarboard me-1"></i>Academic
                    </span>
                <?php else: ?>
                    <span class="badge px-3 py-2" style="background:rgba(246,175,35,.15);color:#e09000">
                        <i class="bi bi-lightning me-1"></i>Skill / Soft-Skill
                    </span>
                <?php endif; ?>
                <?php if($subject->status === 'aktif'): ?>
                    <span class="badge px-3 py-2" style="background:rgba(16,185,129,.15);color:#059669">Aktif</span>
                <?php else: ?>
                    <span class="badge px-3 py-2" style="background:rgba(239,68,68,.15);color:#dc2626">Tidak Aktif</span>
                <?php endif; ?>
            </div>

            <?php if($subject->deskripsi): ?>
            <p class="text-muted text-center" style="font-size:13px"><?php echo e($subject->deskripsi); ?></p>
            <?php endif; ?>

            <div class="border-top pt-3 mt-2">
                <div class="d-flex justify-content-between mb-1"><span class="text-muted" style="font-size:13px">Cabang</span><span class="fw-semibold" style="font-size:13px"><?php echo e($subject->cabang->name ?? 'Semua'); ?></span></div>
                <div class="d-flex justify-content-between mb-1"><span class="text-muted" style="font-size:13px">Total Kelas</span><span class="fw-semibold" style="font-size:13px"><?php echo e($subject->kelas->count()); ?></span></div>
                <div class="d-flex justify-content-between mb-1"><span class="text-muted" style="font-size:13px">Total Guru</span><span class="fw-semibold" style="font-size:13px"><?php echo e($subject->guru->count()); ?></span></div>
                <div class="d-flex justify-content-between"><span class="text-muted" style="font-size:13px">Total Modul</span><span class="fw-semibold" style="font-size:13px"><?php echo e($subject->modul->count()); ?></span></div>
            </div>

            <div class="d-grid gap-2 mt-3">
                <a href="<?php echo e(route('admin.subject.edit', $subject)); ?>" class="btn btn-primary">
                    <i class="bi bi-pencil me-2"></i>Edit Mata Pelajaran
                </a>
                <a href="<?php echo e(route('admin.subject.index')); ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>

    
    <div class="col-md-8">
        <div class="dashboard-card mb-3">
            <h6 class="fw-bold mb-3"><i class="bi bi-box-seam me-2 text-primary"></i>Paket Belajar Terkait</h6>
            <?php $__empty_1 = true; $__currentLoopData = $subject->paket; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
                    <div>
                        <div class="fw-semibold"><?php echo e($p->nama); ?></div>
                        <div class="text-muted" style="font-size:12px"><?php echo e($p->jumlah_pertemuan); ?> sesi · <?php echo e($p->jenis); ?></div>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold text-primary">Rp<?php echo e(number_format($p->harga, 0, ',', '.')); ?></div>
                        <?php if($p->status === 'aktif'): ?>
                            <span class="badge" style="background:rgba(16,185,129,.15);color:#059669;font-size:10px">Aktif</span>
                        <?php else: ?>
                            <span class="badge" style="background:rgba(246,175,35,.15);color:#e09000;font-size:10px">Draft</span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-muted mb-0" style="font-size:13px">Belum ada paket yang menggunakan mapel ini.</p>
            <?php endif; ?>
        </div>

        <div class="dashboard-card">
            <h6 class="fw-bold mb-3"><i class="bi bi-diagram-3 me-2 text-success"></i>Kelas Aktif</h6>
            <?php $__empty_1 = true; $__currentLoopData = $subject->kelas->where('status','aktif'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
                    <div>
                        <div class="fw-semibold"><?php echo e($k->nama_kelas); ?></div>
                        <div class="text-muted" style="font-size:12px"><?php echo e($k->guru->name ?? '—'); ?> · <?php echo e($k->jenis); ?></div>
                    </div>
                    <span class="badge" style="background:rgba(16,185,129,.15);color:#059669">Aktif</span>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-muted mb-0" style="font-size:13px">Belum ada kelas untuk mapel ini.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/admin/subject/detail.blade.php ENDPATH**/ ?>