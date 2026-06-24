<?php $__env->startSection('title', 'Edit Paket Belajar'); ?>
<?php $__env->startSection('page-title', 'Edit Paket Belajar'); ?>

<?php $__env->startSection('content'); ?>
<div class="fade-up">

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.course-package.index')); ?>">Paket Belajar</a></li>
        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.course-package.show', $coursePackage)); ?>"><?php echo e($coursePackage->nama); ?></a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>
</nav>

<div class="w-100">
    <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
        <div style="width:48px;height:48px;border-radius:14px;background:linear-gradient(135deg,#461256,#c84ddf);display:flex;align-items:center;justify-content:center;color:white;font-size:22px;flex-shrink:0">
            <i class="bi bi-pencil-square"></i>
        </div>
        <div>
            <h5 class="fw-bold mb-0">Edit Paket Belajar</h5>
            <p class="text-muted mb-0" style="font-size:13px">Perbarui konfigurasi paket kursus</p>
        </div>
    </div>

    <?php if($errors->any()): ?>
        <div class="alert alert-danger mb-3">
            <ul class="mb-0"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('admin.course-package.update', $coursePackage)); ?>">
        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

        <div class="row g-3">
            <div class="col-12">
                <label class="form-label fw-semibold">Nama Paket <span class="text-danger">*</span></label>
                <input type="text" name="nama" class="form-control <?php $__errorArgs = ['nama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       value="<?php echo e(old('nama', $coursePackage->nama)); ?>" required maxlength="150">
                <?php $__errorArgs = ['nama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Jenis Paket <span class="text-danger">*</span></label>
                <select name="jenis" class="form-select" required id="jenisSelect">
                    <?php $__currentLoopData = ['reguler','intensif','privat','online']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $j): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($j); ?>" <?php echo e(old('jenis',$coursePackage->jenis)==$j?'selected':''); ?>><?php echo e(ucfirst($j)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Jumlah Sesi <span class="text-danger">*</span></label>
                <input type="number" name="jumlah_pertemuan" class="form-control"
                       value="<?php echo e(old('jumlah_pertemuan', $coursePackage->jumlah_pertemuan)); ?>" min="1" required>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Metode Absensi <span class="text-danger">*</span></label>
                <select name="metode_absensi" class="form-select" required>
                    <option value="manual" <?php echo e(old('metode_absensi', $coursePackage->metode_absensi ?? 'manual') == 'manual' ? 'selected' : ''); ?>>Manual</option>
                    <option value="otomatis" <?php echo e(old('metode_absensi', $coursePackage->metode_absensi ?? '') == 'otomatis' ? 'selected' : ''); ?>>Otomatis</option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Tipe Kelas <span class="text-danger">*</span></label>
                <select name="tipe_kelas" class="form-select" required>
                    <option value="offline" <?php echo e(old('tipe_kelas', $coursePackage->tipe_kelas ?? 'offline') == 'offline' ? 'selected' : ''); ?>>Offline</option>
                    <option value="online" <?php echo e(old('tipe_kelas', $coursePackage->tipe_kelas ?? '') == 'online' ? 'selected' : ''); ?>>Online</option>
                    <option value="private" <?php echo e(old('tipe_kelas', $coursePackage->tipe_kelas ?? '') == 'private' ? 'selected' : ''); ?>>Private</option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Harga Dasar (Rp) <span class="text-danger">*</span></label>
                <input type="number" name="harga" class="form-control"
                       value="<?php echo e(old('harga', $coursePackage->harga)); ?>" min="0" required>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Cabang</label>
                <select name="cabang_id" class="form-select">
                    <option value="">Semua Cabang</option>
                    <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($b->id); ?>" <?php echo e(old('cabang_id',$coursePackage->cabang_id)==$b->id?'selected':''); ?>><?php echo e($b->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                <select name="status" class="form-select" required>
                    <option value="aktif" <?php echo e(old('status', $coursePackage->status) == 'aktif' ? 'selected' : ''); ?>>Aktif</option>
                    <option value="nonaktif" <?php echo e(old('status', $coursePackage->status) == 'nonaktif' ? 'selected' : ''); ?>>Non Aktif</option>
                </select>
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold">Mata Pelajaran</label>
                <?php $selected = $coursePackage->mataPelajaran->pluck('id')->toArray(); ?>
                <div class="p-3 rounded-3" style="background:var(--input-bg);border:1.5px solid var(--card-border)">
                    <div class="row g-2">
                        <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-6 col-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="course_ids[]"
                                       value="<?php echo e($c->id); ?>" id="cp_course_<?php echo e($c->id); ?>"
                                       <?php echo e(in_array($c->id, old('course_ids', $selected)) ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="cp_course_<?php echo e($c->id); ?>" style="font-size:13px"><?php echo e($c->nama); ?></label>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <?php if($courses->isEmpty()): ?>
                        <div class="text-muted" style="font-size:13px">Belum ada mata pelajaran aktif.</div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="3"><?php echo e(old('deskripsi', $coursePackage->deskripsi)); ?></textarea>
            </div>
        </div>

        <div class="d-flex gap-2 mt-4 pt-3 border-top">
            <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-lg me-2"></i>Simpan Perubahan</button>
            <a href="<?php echo e(route('admin.course-package.show', $coursePackage)); ?>" class="btn btn-outline-secondary px-4"><i class="bi bi-arrow-left me-2"></i>Batal</a>
        </div>
    </form>
</div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/admin/course-package/edit.blade.php ENDPATH**/ ?>