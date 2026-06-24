<?php $__env->startSection('title', 'Tambah Modul Akademik'); ?>
<?php $__env->startSection('page-title', 'Tambah Modul Akademik'); ?>

<?php $__env->startSection('content'); ?>
<div class="fade-up">

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.module.index')); ?>">Modul Akademik</a></li>
        <li class="breadcrumb-item active">Tambah Modul</li>
    </ol>
</nav>

<?php if($errors->any()): ?>
    <div class="alert alert-danger mb-3">
        <ul class="mb-0"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
    </div>
<?php endif; ?>

<div class="row g-4">
    <div class="col-lg-8">
        <form method="POST" action="<?php echo e(route('admin.module.store')); ?>" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <div class="dashboard-card">
            <h6 class="fw-bold mb-4 pb-2 border-bottom">Informasi Modul Akademik</h6>

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
                           value="<?php echo e(old('kode_modul')); ?>" placeholder="MOD-MAT-01" style="font-family:monospace">
                    <div class="form-text">Format: MOD-[MAPEL]-[NOMOR]</div>
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
                           value="<?php echo e(old('judul')); ?>" placeholder="Misal: Aljabar Linear Lanjut" required>
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
                            <option value="<?php echo e($c->id); ?>" <?php echo e(old('mata_pelajaran_id')==$c->id?'selected':''); ?>><?php echo e($c->nama); ?></option>
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
                        <option value="materi" <?php echo e(old('jenis','materi')=='materi'?'selected':''); ?>>Materi</option>
                        <option value="video"  <?php echo e(old('jenis')=='video' ?'selected':''); ?>>Video</option>
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
                        <option value="aktif"   <?php echo e(old('status','aktif')=='aktif'   ?'selected':''); ?>>Aktif</option>
                        <option value="nonaktif" <?php echo e(old('status')=='nonaktif'?'selected':''); ?>>Nonaktif</option>
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
                    <div class="form-text">Wajib isi salah satu: upload file PDF/DOC/DOCX atau link video di bawah.</div>
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
                    <input type="url" name="video_url" value="<?php echo e(old('video_url')); ?>"
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
                    <textarea name="deskripsi" rows="4" class="form-control" placeholder="Isi silabus, bab, atau deskripsi singkat modul..."><?php echo e(old('deskripsi')); ?></textarea>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary fw-semibold px-4">
                    <i class="bi bi-check-lg me-2"></i>Simpan Modul
                </button>
                <a href="<?php echo e(route('admin.module.index')); ?>" class="btn btn-outline-secondary fw-semibold px-4">
                    <i class="bi bi-arrow-left me-2"></i>Batal
                </a>
            </div>
        </div>
        </form>
    </div>

    <div class="col-lg-4">
        <div class="dashboard-card">
            <h6 class="fw-bold mb-3">Panduan Kode Modul</h6>
            <div class="text-muted" style="font-size:13px">
                <p>Format kode modul yang disarankan:</p>
                <div class="d-flex flex-column gap-2">
                    <code style="background:var(--soft-primary);color:#461256;padding:4px 10px;border-radius:6px">MOD-MAT-01</code>
                    <code style="background:var(--soft-primary);color:#461256;padding:4px 10px;border-radius:6px">MOD-FIS-05</code>
                    <code style="background:var(--soft-primary);color:#461256;padding:4px 10px;border-radius:6px">MOD-ING-02</code>
                </div>
                <ul class="mt-3 ps-3">
                    <li>Prefix <strong>MOD-</strong> wajib</li>
                    <li>Kode mapel 2–4 huruf kapital</li>
                    <li>Nomor urut 2 digit</li>
                    <li>Harus unik di seluruh sistem</li>
                </ul>
            </div>
        </div>
    </div>
</div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/admin/academic-module/create.blade.php ENDPATH**/ ?>