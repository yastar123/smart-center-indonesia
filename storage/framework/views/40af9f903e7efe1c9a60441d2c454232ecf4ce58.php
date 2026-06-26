<?php $__env->startSection('title', 'Riwayat Sesi Guru'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $pageGradient = 'linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%)';
?>

<div class="container-fluid py-4">
    
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <h4 class="fw-bold mb-1" style="font-size:22px">Riwayat Sesi Guru</h4>
            <p class="text-muted mb-0" style="font-size:13px">Rekap sesi mengajar per guru berdasarkan jadwal yang telah selesai.</p>
        </div>
    </div>

    
    <div class="dashboard-card mb-4">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-12 col-md-5">
                <label class="form-label fw-semibold" style="font-size:12px">Cari Guru</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Nama guru..." value="<?php echo e(request('search')); ?>">
            </div>
            <?php if(auth()->user()->hasRole('owner')): ?>
            <div class="col-12 col-md-4">
                <label class="form-label fw-semibold" style="font-size:12px">Cabang</label>
                <select name="branch_id" class="form-select form-select-sm">
                    <option value="">Semua Cabang</option>
                    <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($b->id); ?>" <?php echo e(request('branch_id') == $b->id ? 'selected' : ''); ?>><?php echo e($b->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <?php endif; ?>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search me-1"></i>Cari</button>
                <a href="<?php echo e(route('admin.riwayat-sesi.index')); ?>" class="btn btn-outline-secondary btn-sm ms-1">Reset</a>
            </div>
        </form>
    </div>

    
    <div class="dashboard-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr style="background:var(--input-bg);color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.05em">
                        <th class="px-3 py-3">#</th>
                        <th class="py-3">Guru</th>
                        <th class="py-3">Cabang</th>
                        <th class="py-3 text-center">Total Sesi</th>
                        <th class="py-3 text-center">Selesai</th>
                        <th class="py-3 text-center">Terjadwal</th>
                        <th class="py-3 text-end pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $total    = $teacher->sesi_total ?? 0;
                        $selesai  = $teacher->sesi_selesai ?? 0;
                        $pct      = $total > 0 ? round($selesai/$total*100) : 0;
                    ?>
                    <tr>
                        <td class="px-3" style="font-size:12px;color:var(--text-muted)"><?php echo e($teachers->firstItem() + $i); ?></td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <img src="<?php echo e($teacher->photo_url); ?>" alt="<?php echo e($teacher->name); ?>"
                                    style="width:38px;height:38px;border-radius:50%;object-fit:cover">
                                <div>
                                    <div class="fw-semibold" style="font-size:14px"><?php echo e($teacher->name); ?></div>
                                    <div class="text-muted" style="font-size:11px"><?php echo e(implode(', ', $teacher->subjects ?? [])); ?></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span style="font-size:12px;color:var(--text-muted)"><?php echo e($teacher->branch?->name ?? 'Pusat'); ?></span>
                        </td>
                        <td class="text-center">
                            <span class="fw-bold" style="font-size:16px"><?php echo e($total); ?></span>
                        </td>
                        <td class="text-center">
                            <span class="fw-bold" style="font-size:16px;color:#10b981"><?php echo e($selesai); ?></span>
                        </td>
                        <td class="text-center" style="min-width:120px">
                            <div class="d-flex align-items-center gap-2 justify-content-center">
                                <div style="flex:1;height:6px;border-radius:3px;background:var(--card-border);overflow:hidden">
                                    <div style="width:<?php echo e($pct); ?>%;height:100%;background:linear-gradient(90deg,#461256,#c84ddf);border-radius:3px"></div>
                                </div>
                                <span style="font-size:11px;color:var(--text-muted);white-space:nowrap"><?php echo e($pct); ?>%</span>
                            </div>
                        </td>
                        <td class="text-end pe-3">
                            <a href="<?php echo e(route('admin.riwayat-sesi.show', $teacher)); ?>"
                                class="btn btn-sm btn-outline-primary" style="border-radius:8px;font-size:12px">
                                <i class="bi bi-eye me-1"></i>Detail
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-clock-history" style="font-size:36px;opacity:.2;display:block;margin-bottom:12px"></i>
                            Tidak ada data guru ditemukan.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($teachers->hasPages()): ?>
        <div class="px-3 pt-3 border-top">
            <?php echo e($teachers->withQueryString()->links()); ?>

        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/admin/riwayat-sesi/index.blade.php ENDPATH**/ ?>