<?php $__env->startSection('title', 'Modul Akademik'); ?>
<?php $__env->startSection('page-title', 'Modul Akademik'); ?>

<?php $__env->startSection('content'); ?>
<div class="fade-up">


<div class="dashboard-card mb-4" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div class="row align-items-center g-3" style="position:relative">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-3 mb-2">
                <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px">
                    <i class="bi bi-journal-text"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0" style="color:white">Modul Akademik</h5>
                    <span style="font-size:12px;opacity:.8">Daftar silabus, bab materi, dan referensi modul belajar</span>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="<?php echo e(route('owner.module.create')); ?>" class="btn fw-semibold px-4"
               style="background:rgba(255,255,255,.2);color:white;border:1px solid rgba(255,255,255,.3);border-radius:10px;backdrop-filter:blur(10px)">
                <i class="bi bi-plus-lg me-2"></i>Tambah Modul
            </a>
        </div>
    </div>
</div>


<div class="row g-3 mb-4">
    <div class="col-4 fade-up">
        <div class="stat-card" style="border-top:3px solid #c84ddf">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="stat-title">Total Modul</div><div class="stat-value"><?php echo e($stats['total']); ?></div></div>
                <div class="stat-icon bg-primary-soft" style="color:white"><i class="bi bi-journal-text"></i></div>
            </div>
        </div>
    </div>
    <div class="col-4 fade-up" style="animation-delay:.05s">
        <div class="stat-card" style="border-top:3px solid #10b981">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="stat-title">Aktif</div><div class="stat-value text-success"><?php echo e($stats['aktif']); ?></div></div>
                <div class="stat-icon bg-success-soft" style="color:white"><i class="bi bi-check-circle-fill"></i></div>
            </div>
        </div>
    </div>
    <div class="col-4 fade-up" style="animation-delay:.10s">
        <div class="stat-card" style="border-top:3px solid #f6af23">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="stat-title">Nonaktif</div><div class="stat-value text-warning"><?php echo e($stats['review']); ?></div></div>
                <div class="stat-icon bg-warning-soft" style="color:white"><i class="bi bi-hourglass-split"></i></div>
            </div>
        </div>
    </div>
</div>


<div class="dashboard-card mb-4 fade-up">
    <form method="GET" action="<?php echo e(route('owner.module.index')); ?>">
        <div class="row g-2 align-items-end">
            <div class="col-md-5">
                <label class="form-label fw-semibold" style="font-size:12px">Cari Modul</label>
                <div class="input-group">
                    <span class="input-group-text" style="background:var(--input-bg);border-color:var(--card-border)"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Kode modul atau judul..."
                        class="form-control" style="border-color:var(--card-border);background:var(--input-bg)">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold" style="font-size:12px">Mata Pelajaran</label>
                <select name="mata_pelajaran_id" class="form-select" style="border-color:var(--card-border);background:var(--input-bg)">
                    <option value="">Semua Mapel</option>
                    <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($c->id); ?>" <?php echo e(request('mata_pelajaran_id')==$c->id?'selected':''); ?>><?php echo e($c->nama); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold" style="font-size:12px">Status</label>
                <select name="status" class="form-select" style="border-color:var(--card-border);background:var(--input-bg)">
                    <option value="">Semua</option>
                    <option value="aktif"    <?php echo e(request('status')=='aktif'    ?'selected':''); ?>>Aktif</option>
                    <option value="nonaktif" <?php echo e(request('status')=='nonaktif' ?'selected':''); ?>>Nonaktif</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill fw-semibold">Filter</button>
                <a href="<?php echo e(route('owner.module.index')); ?>" class="btn btn-outline-secondary fw-semibold">Reset</a>
            </div>
        </div>
    </form>
</div>


<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show mb-3"><i class="bi bi-check-circle me-2"></i><?php echo e(session('success')); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>


<div class="dashboard-card fade-up">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr style="background:var(--input-bg)">
                    <th class="fw-semibold" style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px">Kode Modul</th>
                    <th class="fw-semibold" style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px">Judul Modul</th>
                    <th class="fw-semibold" style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px">Mata Pelajaran</th>
                    <th class="fw-semibold" style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px">Deskripsi</th>
                    <th class="fw-semibold" style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px">Jenis</th>
                    <th class="fw-semibold" style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px">File Modul atau Link Modul</th>
                    <th class="fw-semibold text-center" style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px">Status</th>
                    <th class="fw-semibold text-center" style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $modules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $isAktif = $m->status === 'aktif';
                ?>
                <tr>
                    <td>
                        <code style="background:var(--soft-primary);color:#461256;padding:3px 8px;border-radius:6px;font-size:13px;font-weight:600">
                            <?php echo e($m->kode_modul ?: '—'); ?>

                        </code>
                    </td>
                    <td>
                        <div class="fw-semibold"><?php echo e($m->judul); ?></div>
                    </td>
                    <td>
                        <?php if($m->mataPelajaran): ?>
                            <span style="background:var(--soft-info);color:#0369a1;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:500">
                                <?php echo e($m->mataPelajaran->nama); ?>

                            </span>
                        <?php else: ?>
                            <span class="text-muted">—</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="text-muted" style="font-size:12px"><?php echo e($m->deskripsi ? Str::limit($m->deskripsi, 80) : '—'); ?></span>
                    </td>
                    <td>
                        <span class="text-capitalize"><?php echo e($m->jenis ?: '—'); ?></span>
                    </td>
                    <td>
                        <?php
                            $moduleFileUrl = null;
                            if (!empty($m->file_path)) {
                                if (Storage::disk('public')->exists($m->file_path)) {
                                    $moduleFileUrl = Storage::disk('public')->url($m->file_path);
                                } elseif (file_exists(public_path($m->file_path))) {
                                    $moduleFileUrl = asset($m->file_path);
                                }
                            }
                        ?>
                        <?php if($moduleFileUrl): ?>
                            <a href="<?php echo e($moduleFileUrl); ?>" target="_blank" class="btn btn-sm btn-outline-primary">Lihat File</a>
                        <?php elseif($m->file_url): ?>
                            <a href="<?php echo e($m->file_url); ?>" target="_blank" class="btn btn-sm btn-outline-info">Lihat Link</a>
                        <?php else: ?>
                            <span class="text-muted">—</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-center">
                        <?php if($isAktif): ?>
                            <span style="background:var(--soft-success);color:#059669;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600">
                                <i class="bi bi-check-circle-fill me-1"></i>Aktif
                            </span>
                        <?php else: ?>
                            <span style="background:var(--soft-warning);color:#d97706;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600">
                                <i class="bi bi-x-circle-fill me-1"></i>Nonaktif
                            </span>
                        <?php endif; ?>
                    </td>
                    <td class="text-center">
                        <a href="<?php echo e(route('owner.module.show', $m)); ?>" class="btn btn-sm btn-outline-primary me-1" title="Detail">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="<?php echo e(route('owner.module.edit', $m)); ?>" class="btn btn-sm btn-outline-secondary me-1" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form method="POST" action="<?php echo e(route('owner.module.destroy', $m)); ?>" class="d-inline"
                              onsubmit="return confirm('Hapus modul ini?')">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="8" class="text-center py-5">
                        <div style="font-size:40px;opacity:.3;margin-bottom:8px"><i class="bi bi-journal-x"></i></div>
                        <div class="text-muted">Belum ada modul akademik</div>
                        <a href="<?php echo e(route('owner.module.create')); ?>" class="btn btn-sm btn-primary mt-2">Tambah Modul</a>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($modules->hasPages()): ?>
        <div class="p-3 border-top"><?php echo e($modules->links()); ?></div>
    <?php endif; ?>
</div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/owner/academic-module/index.blade.php ENDPATH**/ ?>