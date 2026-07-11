<?php $__env->startSection('title','Ajukan Cuti'); ?>
<?php $__env->startSection('page-title','Ajukan Cuti'); ?>

<?php $__env->startSection('content'); ?>
<div class="fade-up">

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo e(route('siswa.leave.index')); ?>">Manajemen Cuti</a></li>
        <li class="breadcrumb-item active">Form Pengajuan Cuti</li>
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
                <div style="width:44px;height:44px;border-radius:12px;background:linear-gradient(135deg,#461256,#c84ddf);display:flex;align-items:center;justify-content:center;color:white;font-size:18px;flex-shrink:0">
                    <i class="bi bi-snow2"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0">Form Pengajuan Cuti</h6>
                    <span class="text-muted" style="font-size:13px">Isi data paket yang ingin di-freeze sementara</span>
                </div>
            </div>

            
            <div class="alert mb-4" style="background:var(--soft-warning);border:1px solid rgba(246,175,35,.3);border-radius:12px;color:#92400e">
                <div class="d-flex align-items-start gap-2">
                    <i class="bi bi-exclamation-triangle-fill" style="font-size:18px;flex-shrink:0;color:#b45309;margin-top:2px"></i>
                    <div style="font-size:13px">
                        <div class="fw-bold mb-1">Pengajuan Cuti / Freeze Hanya untuk Kelas PRIVAT</div>
                        Sistem memblokir pengajuan dari paket Reguler. Sesi absen pada kelas reguler otomatis <strong>HANGUS</strong> — tidak bisa di-freeze atau dijadwal ulang.
                    </div>
                </div>
            </div>

            <?php if($privateClasses->isEmpty()): ?>
            <div class="text-center py-4" style="color:var(--text-muted)">
                <i class="bi bi-person-x" style="font-size:3rem;display:block;margin-bottom:12px;opacity:.4"></i>
                <div class="fw-semibold mb-2">Tidak Ada Kelas Privat</div>
                <p style="font-size:13px">Anda belum terdaftar di kelas privat manapun. Cuti & Freeze hanya tersedia untuk siswa kelas privat.</p>
                <a href="<?php echo e(route('siswa.leave.index')); ?>" class="btn btn-outline-secondary mt-2">
                    <i class="bi bi-arrow-left me-2"></i>Kembali
                </a>
            </div>
            <?php else: ?>
            <form method="POST" action="<?php echo e(route('siswa.leave.store')); ?>">
                <?php echo csrf_field(); ?>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold">Pilih Paket Belajar (Khusus Paket Privat) <span class="text-danger">*</span></label>
                        <select name="school_class_id" class="form-select <?php $__errorArgs = ['school_class_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                            <option value="">-- Pilih Paket Privat Anda --</option>
                            <?php $__currentLoopData = $privateClasses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($class->id); ?>" <?php echo e(old('school_class_id') == $class->id ? 'selected' : ''); ?>>
                                <?php echo e($class->nama_kelas); ?>

                                <?php if($class->mataPelajaran): ?> — <?php echo e($class->mataPelajaran->nama); ?><?php endif; ?>
                                <?php if($class->guru): ?> (<?php echo e($class->guru->name); ?>)<?php endif; ?>
                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <div class="form-text">Hanya paket bertipe Privat yang ditampilkan. Paket Reguler tidak bisa diajukan freeze.</div>
                        <?php $__errorArgs = ['school_class_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tanggal Mulai <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_mulai"
                               class="form-control <?php $__errorArgs = ['tanggal_mulai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               value="<?php echo e(old('tanggal_mulai')); ?>"
                               min="<?php echo e(date('Y-m-d')); ?>" required>
                        <?php $__errorArgs = ['tanggal_mulai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tanggal Selesai <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_selesai"
                               class="form-control <?php $__errorArgs = ['tanggal_selesai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               value="<?php echo e(old('tanggal_selesai')); ?>"
                               min="<?php echo e(date('Y-m-d', strtotime('+1 day'))); ?>" required>
                        <?php $__errorArgs = ['tanggal_selesai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Alasan Cuti <span class="text-danger">*</span></label>
                        <textarea name="alasan" rows="4"
                                  class="form-control <?php $__errorArgs = ['alasan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                  placeholder="Jelaskan alasan cuti anda secara singkat..." required
                                  minlength="10" maxlength="1000"><?php echo e(old('alasan')); ?></textarea>
                        <?php $__errorArgs = ['alasan'];
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
                    <a href="<?php echo e(route('siswa.leave.index')); ?>" class="btn btn-outline-secondary fw-semibold px-4">
                        <i class="bi bi-arrow-left me-2"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-primary fw-semibold px-4">
                        <i class="bi bi-send me-2"></i>Kirim Pengajuan
                    </button>
                </div>
            </form>
            <?php endif; ?>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="dashboard-card mb-3">
            <h6 class="fw-bold mb-3"><i class="bi bi-info-circle me-2 text-primary"></i>Panduan Cuti</h6>
            <ul class="text-muted ps-3" style="font-size:13px">
                <li class="mb-2">Siswa berhak mengajukan freeze paket maksimal <strong>14 hari</strong> per paket.</li>
                <li class="mb-2">Pengajuan harus dilakukan <strong>H-3</strong> sebelum tanggal efektif.</li>
                <li class="mb-2">Jika pengajuan ditolak, absen dianggap alpa dan <strong>sesi hangus</strong>.</li>
            </ul>
        </div>
        <div class="dashboard-card">
            <h6 class="fw-bold mb-3"><i class="bi bi-clock-history me-2 text-primary"></i>Estimasi Verifikasi</h6>
            <div class="p-3 rounded-3" style="background:var(--soft-primary)">
                <div style="font-size:13px;color:#461256;font-style:italic">
                    "Admin biasanya memberikan keputusan dalam waktu 1×24 jam hari kerja."
                </div>
            </div>
        </div>
    </div>
</div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/siswa/leave/create.blade.php ENDPATH**/ ?>