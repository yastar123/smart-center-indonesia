<?php $__env->startSection('title', 'Edit Modul Akademik'); ?>
<?php $__env->startSection('page-title', 'Edit Modul Akademik'); ?>

<?php $__env->startSection('content'); ?>
<div class="fade-up">

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.module.index')); ?>">Modul Akademik</a></li>
        <li class="breadcrumb-item active">Edit Modul</li>
    </ol>
</nav>

<?php if($errors->any()): ?>
    <div class="alert alert-danger mb-3">
        <ul class="mb-0"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
    </div>
<?php endif; ?>

<div class="row g-4">
    <div class="col-lg-8">
        <form method="POST" action="<?php echo e(route('admin.module.update', $module)); ?>" enctype="multipart/form-data">
        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
        <div class="dashboard-card">
            <h6 class="fw-bold mb-4 pb-2 border-bottom">Edit Modul: <?php echo e($module->judul); ?></h6>

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Kode Modul</label>
                    <input type="text" name="kode_modul" class="form-control <?php $__errorArgs = ['kode_modul'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           value="<?php echo e(old('kode_modul', $module->kode_modul)); ?>" placeholder="MOD-MAT-01" style="font-family:monospace">
                    <?php $__errorArgs = ['kode_modul'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="col-md-8">
                    <label class="form-label fw-semibold">Judul Modul <span class="text-danger">*</span></label>
                    <input type="text" name="judul" class="form-control <?php $__errorArgs = ['judul'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           value="<?php echo e(old('judul', $module->judul)); ?>" required>
                    <?php $__errorArgs = ['judul'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Mata Pelajaran <span class="text-danger">*</span></label>
                    <select name="mata_pelajaran_id" class="form-select <?php $__errorArgs = ['mata_pelajaran_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                        <option value="">— Pilih Mata Pelajaran —</option>
                        <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($c->id); ?>" <?php echo e(old('mata_pelajaran_id', $module->mata_pelajaran_id)==$c->id?'selected':''); ?>><?php echo e($c->nama); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['mata_pelajaran_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Jenis</label>
                    <select name="jenis" class="form-select">
                        <option value="materi" <?php echo e(old('jenis', $module->jenis)=='materi'?'selected':''); ?>>Materi</option>
                        <option value="pdf"    <?php echo e(old('jenis', $module->jenis)=='pdf'   ?'selected':''); ?>>PDF</option>
                        <option value="video"  <?php echo e(old('jenis', $module->jenis)=='video' ?'selected':''); ?>>Video</option>
                        <option value="link"   <?php echo e(old('jenis', $module->jenis)=='link'  ?'selected':''); ?>>Link</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                        <option value="aktif"  <?php echo e(old('status', $module->status)=='aktif'  ?'selected':''); ?>>Aktif</option>
                        <option value="review" <?php echo e(old('status', $module->status)=='review' ?'selected':''); ?>>Review</option>
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
                    <label class="form-label fw-semibold">Upload File Modul (PDF / DOC / DOCX)</label>
                    <input type="file" name="module_file"
                           class="form-control <?php $__errorArgs = ['module_file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           accept=".pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                    <div class="form-text">Kosongkan jika tidak ingin mengganti file. Wajib salah satu: file atau link video.</div>
                    <?php $__errorArgs = ['module_file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Link Video Modul</label>
                    <input type="url" name="video_url" value="<?php echo e(old('video_url', $module->file_url)); ?>"
                           class="form-control <?php $__errorArgs = ['video_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           placeholder="https://www.youtube.com/watch?v=...">
                    <div class="form-text">Isi jika modul menggunakan video pembelajaran.</div>
                    <?php $__errorArgs = ['video_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Deskripsi / Silabus</label>
                    <textarea name="deskripsi" rows="4" class="form-control" placeholder="Isi silabus, bab, atau deskripsi singkat modul..."><?php echo e(old('deskripsi', $module->deskripsi)); ?></textarea>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary fw-semibold px-4">
                    <i class="bi bi-check-lg me-2"></i>Perbarui Modul
                </button>
                <a href="<?php echo e(route('admin.module.show', $module)); ?>" class="btn btn-outline-secondary fw-semibold px-4">
                    <i class="bi bi-arrow-left me-2"></i>Batal
                </a>
            </div>
        </div>
        </form>
    </div>

    <div class="col-lg-4">
        <div class="dashboard-card">
            <h6 class="fw-bold mb-3">Info Modul</h6>
            <div class="d-flex flex-column gap-2">
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted" style="font-size:13px">Kode Saat Ini</span>
                    <code style="font-size:13px"><?php echo e($module->kode_modul ?: '—'); ?></code>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted" style="font-size:13px">Status Saat Ini</span>
                    <span class="fw-semibold text-capitalize" style="font-size:13px"><?php echo e(ucfirst($module->status)); ?></span>
                </div>
                <div class="d-flex justify-content-between py-2">
                    <span class="text-muted" style="font-size:13px">Terakhir Diubah</span>
                    <span style="font-size:13px"><?php echo e($module->updated_at->format('d M Y')); ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/admin/academic-module/edit.blade.php ENDPATH**/ ?>