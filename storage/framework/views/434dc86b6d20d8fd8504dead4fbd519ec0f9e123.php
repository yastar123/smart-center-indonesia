<?php $__env->startSection('title', 'Mata Pelajaran'); ?>
<?php $__env->startSection('page-title', 'Mata Pelajaran'); ?>

<?php $__env->startSection('content'); ?>
<div>


<div class="dashboard-card mb-4 fade-up" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
    <div class="row align-items-center g-3" style="position:relative">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-3 mb-2">
                <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0">
                    <i class="bi bi-journal-bookmark-fill"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0" style="color:white">Mata Pelajaran</h5>
                    <span style="font-size:12px;opacity:.8">Daftar master mata pelajaran yang ditawarkan kepada siswa</span>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-md-end d-flex justify-content-md-end gap-2">
            <a href="<?php echo e(route('owner.subject.create')); ?>" class="btn fw-semibold px-4" style="background:rgba(255,255,255,.2);color:white;border:1px solid rgba(255,255,255,.3);border-radius:10px;backdrop-filter:blur(10px)">
                <i class="bi bi-plus-lg me-2"></i>Tambah Mapel
            </a>
        </div>
    </div>
</div>


<div class="row g-3 mb-4">
    <?php
        $statCards = [
            ['label'=>'Total Mapel',  'value'=>$stats['total'],    'icon'=>'bi-book-fill',        'topColor'=>'#10b981', 'textColor'=>'text-success', 'iconBg'=>'bg-success-soft'],
            ['label'=>'Mapel Aktif',  'value'=>$stats['aktif'],    'icon'=>'bi-check-circle-fill','topColor'=>'#c84ddf', 'textColor'=>'text-primary', 'iconBg'=>'bg-primary-soft'],
            ['label'=>'Tidak Aktif',  'value'=>$stats['nonaktif'], 'icon'=>'bi-x-circle-fill',   'topColor'=>'#ef4444', 'textColor'=>'text-danger',  'iconBg'=>'bg-danger-soft'],
        ];
    ?>
    <?php $__currentLoopData = $statCards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $sc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="col-6 col-lg fade-up" style="animation-delay:<?php echo e($i * 0.05); ?>s">
        <div class="stat-card" style="border-top:3px solid <?php echo e($sc['topColor']); ?>">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title"><?php echo e($sc['label']); ?></div>
                    <div class="stat-value <?php echo e($sc['textColor']); ?> count-up" data-target="<?php echo e($sc['value']); ?>"><?php echo e($sc['value']); ?></div>
                </div>
                <div class="stat-icon <?php echo e($sc['iconBg']); ?>" style="color:white">
                    <i class="bi <?php echo e($sc['icon']); ?>"></i>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>


<div class="dashboard-card mb-4 fade-up">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-12 col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Cari nama atau kode mapel…" value="<?php echo e(request('search')); ?>">
        </div>
        <div class="col-6 col-md-3">
            <select name="status" class="form-select">
                <option value="">Semua Status</option>
                <option value="aktif"    <?php echo e(request('status')=='aktif'   ?'selected':''); ?>>Aktif</option>
                <option value="nonaktif" <?php echo e(request('status')=='nonaktif'?'selected':''); ?>>Tidak Aktif</option>
            </select>
        </div>
        <div class="col-6 col-md-3">
            <select name="cabang_id" class="form-select">
                <option value="">Semua Cabang</option>
                <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($b->id); ?>" <?php echo e(request('cabang_id')==$b->id?'selected':''); ?>><?php echo e($b->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
    </form>
</div>


<div class="dashboard-card fade-up">
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            <i class="bi bi-check-circle me-2"></i><?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-hover table-modern align-middle mb-0">
            <thead class="thead-modern">
                <tr>
                    <th>Kode</th>
                    <th>Nama Pelajaran</th>
                    <th>Deskripsi</th>
                    <th>Cabang</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><span class="badge bg-secondary"><?php echo e($s->kode); ?></span></td>
                    <td>
                        <div class="fw-semibold"><?php echo e($s->nama); ?></div>
                    </td>
                    <td><?php echo e(Str::limit($s->deskripsi, 60)); ?></td>
                    <td><?php echo e($s->cabang_id ? ($s->cabang->name ?? '—') : 'Semua Cabang'); ?></td>
                    <td>
                        <?php if($s->status === 'aktif'): ?>
                            <span class="badge" style="background:rgba(16,185,129,.15);color:#059669">Aktif</span>
                        <?php else: ?>
                            <span class="badge" style="background:rgba(239,68,68,.15);color:#dc2626">Tidak Aktif</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-center">
                        <div class="d-flex gap-1 justify-content-center">
                            <a href="<?php echo e(route('owner.subject.edit', $s)); ?>" class="btn btn-sm btn-outline-secondary" title="Edit">Edit</a>
                            <form method="POST" action="<?php echo e(route('owner.subject.destroy', $s)); ?>" class="d-inline" onsubmit="return confirm('Hapus mata pelajaran ini?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="btn btn-sm btn-outline-danger">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6" class="text-center py-4 text-muted">
                    <i class="bi bi-journal-x fs-3 d-block mb-2"></i>Belum ada mata pelajaran.
                </td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if($subjects->hasPages()): ?>
    <div class="d-flex justify-content-center mt-3">
        <?php echo e($subjects->links()); ?>

    </div>
    <?php endif; ?>
</div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/owner/subject/index.blade.php ENDPATH**/ ?>