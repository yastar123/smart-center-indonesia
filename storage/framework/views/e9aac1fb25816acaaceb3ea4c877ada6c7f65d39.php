<?php $__env->startSection('title','Edit Ruangan'); ?>
<?php $__env->startSection('page-title','Edit Ruangan'); ?>

<?php $__env->startSection('content'); ?>
<div class="fade-up">

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.rooms.index')); ?>">Fasilitas Ruangan</a></li>
        <li class="breadcrumb-item active">Edit: <?php echo e($room->nama_ruangan); ?></li>
    </ol>
</nav>

<?php if($errors->any()): ?>
    <div class="alert alert-danger mb-3">
        <ul class="mb-0"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
    </div>
<?php endif; ?>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="dashboard-card">
            <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                <div style="width:44px;height:44px;border-radius:12px;background:linear-gradient(135deg,#461256,#c84ddf);display:flex;align-items:center;justify-content:center;color:white;font-size:18px">
                    <i class="bi bi-pencil-square"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0">Edit Ruangan</h6>
                    <span class="text-muted" style="font-size:13px">Perbarui data ruangan belajar</span>
                </div>
            </div>

            <form method="POST" action="<?php echo e(route('admin.rooms.update', $room)); ?>">
                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold">Pilih Cabang <span class="text-danger">*</span></label>
                        <select name="branch_id" class="form-select <?php $__errorArgs = ['branch_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                            <option value="">-- Pilih Cabang --</option>
                            <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($branch->id); ?>" <?php echo e((old('branch_id', $room->branch_id) == $branch->id) ? 'selected' : ''); ?>>
                                    <?php echo e($branch->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['branch_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Nama Ruangan <span class="text-danger">*</span></label>
                        <input type="text" name="nama_ruangan"
                               class="form-control <?php $__errorArgs = ['nama_ruangan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               value="<?php echo e(old('nama_ruangan', $room->nama_ruangan)); ?>"
                               placeholder="Contoh: Ruang A1 - VIP" required>
                        <?php $__errorArgs = ['nama_ruangan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Kapasitas Maksimal (Siswa) <span class="text-danger">*</span></label>
                        <input type="number" name="kapasitas"
                               class="form-control <?php $__errorArgs = ['kapasitas'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               value="<?php echo e(old('kapasitas', $room->kapasitas)); ?>"
                               min="1" required>
                        <?php $__errorArgs = ['kapasitas'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                            <option value="aktif"       <?php echo e(old('status', $room->status)=='aktif'       ? 'selected' : ''); ?>>Bisa Digunakan</option>
                            <option value="maintenance" <?php echo e(old('status', $room->status)=='maintenance' ? 'selected' : ''); ?>>Maintenance</option>
                        </select>
                        <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Keterangan</label>
                        <textarea name="keterangan" rows="3"
                                  class="form-control <?php $__errorArgs = ['keterangan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                  placeholder="Fasilitas ruangan, kondisi, catatan khusus..."><?php echo e(old('keterangan', $room->keterangan)); ?></textarea>
                        <?php $__errorArgs = ['keterangan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <a href="<?php echo e(route('admin.rooms.index')); ?>" class="btn btn-outline-secondary fw-semibold px-4">
                        <i class="bi bi-arrow-left me-2"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-primary fw-semibold px-4">
                        <i class="bi bi-floppy me-2"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/admin/rooms/edit.blade.php ENDPATH**/ ?>