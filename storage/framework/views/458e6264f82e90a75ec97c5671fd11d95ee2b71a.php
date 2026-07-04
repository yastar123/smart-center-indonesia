<?php $__env->startSection('title','Fasilitas Ruangan'); ?>
<?php $__env->startSection('page-title','Fasilitas Ruangan'); ?>

<?php $__env->startSection('content'); ?>
<div class="fade-up">


<div class="dashboard-card mb-4" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
    <div class="row align-items-center g-3" style="position:relative">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-3 mb-2">
                <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0">
                    <i class="bi bi-door-open-fill"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0" style="color:white">Fasilitas Ruangan Cabang</h5>
                    <span style="font-size:12px;opacity:.8">Manajemen kapasitas dan nama ruangan belajar.</span>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="<?php echo e(route('admin.rooms.create')); ?>" class="btn fw-semibold px-4" style="background:rgba(255,255,255,.2);color:white;border:1px solid rgba(255,255,255,.3);border-radius:10px">
                <i class="bi bi-plus-lg me-2"></i>Tambah Ruangan
            </a>
        </div>
    </div>
</div>


<div class="row g-3 mb-4">
    <div class="col-6 col-lg-4">
        <div class="stat-card" style="border-top:3px solid #c84ddf">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Total Ruangan</div>
                    <div class="stat-value"><?php echo e($stats['total']); ?></div>
                </div>
                <div class="stat-icon bg-primary-soft" style="color:white"><i class="bi bi-door-open"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-4">
        <div class="stat-card" style="border-top:3px solid #10b981">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Bisa Digunakan</div>
                    <div class="stat-value"><?php echo e($stats['aktif']); ?></div>
                </div>
                <div class="stat-icon bg-success-soft" style="color:white"><i class="bi bi-check-circle"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-4">
        <div class="stat-card" style="border-top:3px solid #f6af23">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Maintenance</div>
                    <div class="stat-value"><?php echo e($stats['maintenance']); ?></div>
                </div>
                <div class="stat-icon bg-warning-soft" style="color:white"><i class="bi bi-tools"></i></div>
            </div>
        </div>
    </div>
</div>


<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
        <i class="bi bi-check-circle me-2"></i><?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>


<div class="dashboard-card">
    <div class="table-responsive">
        <table class="table table-hover table-modern align-middle mb-0">
            <thead class="thead-modern">
                <tr>
                    <th>Cabang</th>
                    <th>Nama Ruangan</th>
                    <th>Kapasitas Maksimal</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#461256,#c84ddf);display:flex;align-items:center;justify-content:center;color:white;font-size:14px;flex-shrink:0">
                                <i class="bi bi-building"></i>
                            </div>
                            <span class="fw-semibold"><?php echo e($room->branch->name ?? '–'); ?></span>
                        </div>
                    </td>
                    <td>
                        <div class="fw-semibold"><?php echo e($room->nama_ruangan); ?></div>
                        <?php if($room->keterangan): ?>
                            <div class="text-muted" style="font-size:12px"><?php echo e(Str::limit($room->keterangan, 50)); ?></div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="badge" style="background:var(--soft-primary);color:#461256;font-size:13px;padding:6px 12px">
                            <?php echo e($room->kapasitas); ?> Siswa
                        </span>
                    </td>
                    <td>
                        <?php if($room->status === 'aktif'): ?>
                            <span class="badge" style="background:var(--soft-success);color:#10b981;font-size:12px;padding:5px 12px">
                                <i class="bi bi-check-circle me-1"></i>Bisa Digunakan
                            </span>
                        <?php else: ?>
                            <span class="badge" style="background:var(--soft-warning);color:#b45309;font-size:12px;padding:5px 12px">
                                <i class="bi bi-tools me-1"></i>Maintenance
                            </span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="d-flex gap-1 justify-content-center">
                            <a href="<?php echo e(route('admin.rooms.show', $room)); ?>" class="btn btn-sm btn-outline-info" title="Detail">
                                Detail
                            </a>
                            <a href="<?php echo e(route('admin.rooms.edit', $room)); ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                                Edit
                            </a>
                            <form action="<?php echo e(route('admin.rooms.destroy', $room)); ?>" method="POST" class="d-inline"
                                  onsubmit="return confirm('Hapus ruangan ini?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted">
                        <i class="bi bi-door-open" style="font-size:2.5rem;display:block;margin-bottom:10px;opacity:.4"></i>
                        Belum ada ruangan. <a href="<?php echo e(route('admin.rooms.create')); ?>">Tambah sekarang</a>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($rooms->hasPages()): ?>
        <div class="mt-3 d-flex justify-content-center">
            <?php echo e($rooms->links()); ?>

        </div>
    <?php endif; ?>
</div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/admin/rooms/index.blade.php ENDPATH**/ ?>