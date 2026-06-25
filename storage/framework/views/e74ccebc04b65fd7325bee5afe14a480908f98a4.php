<?php $__env->startSection('title', 'Paket Belajar'); ?>
<?php $__env->startSection('page-title', 'Paket Belajar & Harga'); ?>

<?php $__env->startSection('content'); ?>
<div>


<div class="dashboard-card mb-4 fade-up" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
    <div class="row align-items-center g-3" style="position:relative">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-3 mb-2">
                <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0">
                    <i class="bi bi-box-seam"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0" style="color:white">Paket Belajar & Harga</h5>
                    <span style="font-size:12px;opacity:.8">Daftar paket kursus yang tersedia, jenjang, dan harga</span>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="<?php echo e(route('admin.course-package.create')); ?>" class="btn fw-semibold px-4" style="background:rgba(255,255,255,.2);color:white;border:1px solid rgba(255,255,255,.3);border-radius:10px;backdrop-filter:blur(10px)">
                <i class="bi bi-plus-lg me-2"></i>Tambah Paket
            </a>
        </div>
    </div>
</div>


<div class="row g-3 mb-4">
    <?php
        $statCards = [
            ['label'=>'Total Paket',   'value'=>$stats['total'],  'icon'=>'bi-box-seam',          'topColor'=>'#10b981','textColor'=>'text-success','iconBg'=>'bg-success-soft'],
            ['label'=>'Paket Aktif',   'value'=>$stats['aktif'],  'icon'=>'bi-check-circle-fill', 'topColor'=>'#c84ddf','textColor'=>'text-primary','iconBg'=>'bg-primary-soft'],
            ['label'=>'Draft',         'value'=>$stats['draft'],  'icon'=>'bi-hourglass-split',   'topColor'=>'#f6af23','textColor'=>'text-warning','iconBg'=>'bg-warning-soft'],
            ['label'=>'Privat',        'value'=>$stats['privat'], 'icon'=>'bi-person-check-fill', 'topColor'=>'#3b82f6','textColor'=>'text-info',   'iconBg'=>'bg-info-soft'],
        ];
    ?>
    <?php $__currentLoopData = $statCards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $sc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:<?php echo e($i * 0.05); ?>s">
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
            <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Cari nama paket…" value="<?php echo e(request('search')); ?>">
            </div>
        </div>
        <div class="col-6 col-md-2">
            <select name="jenis" class="form-select">
                <option value="">Semua Jenis</option>
                <option value="reguler"  <?php echo e(request('jenis')=='reguler' ?'selected':''); ?>>Reguler</option>
                <option value="intensif" <?php echo e(request('jenis')=='intensif'?'selected':''); ?>>Intensif</option>
                <option value="privat"   <?php echo e(request('jenis')=='privat'  ?'selected':''); ?>>Privat</option>
                <option value="online"   <?php echo e(request('jenis')=='online'  ?'selected':''); ?>>Online</option>
            </select>
        </div>
        <div class="col-6 col-md-2">
            <select name="status" class="form-select">
                <option value="">Semua Status</option>
                <option value="aktif"    <?php echo e(request('status')=='aktif'   ?'selected':''); ?>>Aktif</option>
                <option value="nonaktif" <?php echo e(request('status')=='nonaktif'?'selected':''); ?>>Draft</option>
            </select>
        </div>
        <div class="col-6 col-md-2">
            <select name="cabang_id" class="form-select">
                <option value="">Semua Cabang</option>
                <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($b->id); ?>" <?php echo e(request('cabang_id')==$b->id?'selected':''); ?>><?php echo e($b->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-3 col-md-1">
            <button type="submit" class="btn btn-primary w-100"><i class="bi bi-funnel"></i></button>
        </div>
        <div class="col-3 col-md-1">
            <a href="<?php echo e(route('admin.course-package.index')); ?>" class="btn btn-outline-secondary w-100"><i class="bi bi-x-lg"></i></a>
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
                    <th>Nama Paket</th>
                    <th>Kategori & Jenjang</th>
                    <th>Mata Pelajaran</th>
                    <th class="text-center">Jumlah Sesi</th>
                    <th>Harga Dasar</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $packages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $jenisLabel = match($p->jenis) {
                        'reguler'  => 'Reguler',
                        'privat'   => 'Privat (1 Siswa)',
                        'intensif' => 'Intensif',
                        'online'   => 'Online',
                        default    => ucfirst($p->jenis),
                    };
                    $jenisColor = match($p->jenis) {
                        'privat'   => ['bg'=>'rgba(200,77,223,.15)','fg'=>'#461256'],
                        'intensif' => ['bg'=>'rgba(246,175,35,.15)','fg'=>'#e09000'],
                        'online'   => ['bg'=>'rgba(59,130,246,.15)', 'fg'=>'#3b82f6'],
                        default    => ['bg'=>'rgba(16,185,129,.15)', 'fg'=>'#059669'],
                    };
                ?>
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#461256,#c84ddf);display:flex;align-items:center;justify-content:center;color:white;font-size:14px;flex-shrink:0">
                                <i class="bi bi-box-seam"></i>
                            </div>
                            <div>
                                <div class="fw-semibold"><?php echo e($p->nama); ?></div>
                                <div class="text-muted" style="font-size:11px"><?php echo e($p->durasi_bulan); ?> bulan · <?php echo e($p->cabang->name ?? 'Semua'); ?></div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge" style="background:<?php echo e($jenisColor['bg']); ?>;color:<?php echo e($jenisColor['fg']); ?>;font-size:11px">
                            <?php echo e($jenisLabel); ?>

                        </span>
                    </td>
                    <td>
                        <?php if($p->mataPelajaran->isNotEmpty()): ?>
                            <div class="d-flex flex-wrap gap-1">
                                <?php $__currentLoopData = $p->mataPelajaran; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span class="badge" style="background:rgba(16,185,129,.12);color:#059669;font-size:11px"><?php echo e($course->nama); ?></span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php else: ?>
                            <span class="text-muted" style="font-size:12px">—</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-center">
                        <span class="fw-semibold"><?php echo e($p->jumlah_pertemuan); ?></span>
                        <span class="text-muted" style="font-size:11px"> Sesi</span>
                    </td>
                    <td>
                        <span class="fw-bold text-primary">Rp <?php echo e(number_format($p->harga, 0, ',', '.')); ?></span>
                    </td>
                    <td>
                        <?php if($p->status === 'aktif'): ?>
                            <span class="badge" style="background:rgba(16,185,129,.15);color:#059669">Aktif</span>
                        <?php else: ?>
                            <span class="badge" style="background:rgba(246,175,35,.15);color:#e09000">Draft</span>
                        <?php endif; ?>
                        <?php if($p->is_unggulan): ?>
                            <span class="badge ms-1" style="background:rgba(246,175,35,.2);color:#e09000"><i class="bi bi-star-fill"></i></span>
                        <?php endif; ?>
                    </td>
                    <td class="text-center">
                        <div class="d-flex gap-1 justify-content-center">
                            <?php if($p->status === 'aktif'): ?>
                                <a href="<?php echo e(route('admin.course-package.show', $p)); ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                            <?php endif; ?>
                            <a href="<?php echo e(route('admin.course-package.edit', $p)); ?>" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <form method="POST" action="<?php echo e(route('admin.course-package.destroy', $p)); ?>" class="d-inline" onsubmit="return confirm('Hapus paket ini?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="7" class="text-center py-4 text-muted">
                    <i class="bi bi-box-seam fs-3 d-block mb-2"></i>Belum ada paket belajar.
                </td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if($packages->hasPages()): ?>
    <div class="d-flex justify-content-center mt-3">
        <?php echo e($packages->links()); ?>

    </div>
    <?php endif; ?>
</div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/admin/course-package/index.blade.php ENDPATH**/ ?>