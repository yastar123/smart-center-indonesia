<?php $__env->startSection('title','Biaya Mata Pelajaran'); ?>
<?php $__env->startSection('page-title','Biaya Mata Pelajaran'); ?>

<?php $__env->startSection('content'); ?>

<?php if(session('success')): ?>
<div class="alert alert-dismissible d-flex align-items-center gap-2 mb-4 fade show"
     style="border-radius:12px;border:none;background:rgba(16,185,129,.1);border-left:4px solid #10b981 !important">
    <i class="bi bi-check-circle-fill text-success"></i>
    <span><?php echo e(session('success')); ?></span>
    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="dashboard-card mb-4 fade-up"
     style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
    <div class="row align-items-center g-3" style="position:relative">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-3">
                <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0">
                    <i class="bi bi-cash-coin"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0" style="color:white">Kelola Biaya Mata Pelajaran</h5>
                    <p class="mb-0 mt-1" style="opacity:.75;font-size:13px">Atur harga per mata pelajaran untuk setiap kelas</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="dashboard-card fade-up">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="thead-modern">
                <tr>
                    <th>Nama Kelas</th>
                    <th>Mata Pelajaran</th>
                    <th>Guru</th>
                    <th>Cabang</th>
                    <th>Biaya (Rp)</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="fw-semibold"><?php echo e($class->nama_kelas); ?></td>
                    <td><?php echo e($class->mataPelajaran->nama ?? '—'); ?></td>
                    <td><?php echo e($class->guru->name ?? '—'); ?></td>
                    <td><?php echo e($class->cabang->name ?? 'Pusat'); ?></td>
                    <td>
                        <?php if($class->mataPelajaran): ?>
                        <form action="<?php echo e(route('admin.courses.fees.update', $class->mataPelajaran->id)); ?>" method="POST" class="d-flex gap-2 align-items-center fee-form">
                            <?php echo csrf_field(); ?>
                            <input type="text" class="form-control form-control-sm fee-display" style="width:160px" placeholder="Rp 0"
                                   data-raw="<?php echo e($class->mataPelajaran->fee->amount ?? 0); ?>"
                                   value="Rp <?php echo e(number_format($class->mataPelajaran->fee->amount ?? 0, 0, ',', '.')); ?>">
                            <input type="hidden" name="amount" class="fee-raw" value="<?php echo e($class->mataPelajaran->fee->amount ?? 0); ?>">
                            <button class="btn btn-sm btn-primary">Simpan</button>
                        </form>
                        <?php else: ?>
                        <span class="text-muted small">—</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-end">
                        <?php if($class->mataPelajaran && $class->mataPelajaran->fee): ?>
                        <form action="<?php echo e(route('admin.courses.fees.destroy', $class->mataPelajaran->fee->id)); ?>" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button type="button" class="btn btn-sm btn-outline-danger"
                                    onclick="confirmAction('Hapus biaya mata pelajaran ini?', () => this.closest(\'form\').submit(), null, {title:\'Hapus Biaya\', okText:\'Ya, Hapus\'})">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                        <?php else: ?>
                        <span class="text-muted small">—</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <div style="opacity:.5;">
                            <i class="bi bi-building text-primary" style="font-size:2.5rem;display:block;margin-bottom:.5rem"></i>
                            <div class="fw-semibold">Belum ada kelas</div>
                            <small class="text-muted">Tambahkan kelas terlebih dahulu</small>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.querySelectorAll('.fee-display').forEach(function(input) {
    input.addEventListener('focus', function() {
        var raw = parseInt(this.dataset.raw) || 0;
        this.value = raw || '';
        this.select();
    });
    input.addEventListener('input', function() {
        var raw = parseInt(this.value.replace(/[^0-9]/g, '')) || 0;
        this.dataset.raw = raw;
        this.closest('.fee-form').querySelector('.fee-raw').value = raw;
    });
    input.addEventListener('blur', function() {
        var raw = parseInt(this.dataset.raw) || 0;
        this.value = 'Rp ' + raw.toLocaleString('id-ID');
    });
    input.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            this.closest('.fee-form').querySelector('button[type="submit"], button:not([type])').click();
        }
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/admin/courses/fees.blade.php ENDPATH**/ ?>