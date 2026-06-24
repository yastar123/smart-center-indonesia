<?php $__env->startSection('title', 'Sertifikat Siswa'); ?>
<?php $__env->startSection('page-title', 'Sertifikat Siswa'); ?>

<?php $__env->startSection('content'); ?>
<div class="fade-up">


<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.certificates.index')); ?>">Sertifikat</a></li>
        <li class="breadcrumb-item active"><?php echo e($student->user?->name ?? 'Siswa'); ?></li>
    </ol>
</nav>

<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show mb-3"><i class="bi bi-check-circle me-2"></i><?php echo e(session('success')); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>
<?php if(session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show mb-3"><i class="bi bi-x-circle me-2"></i><?php echo e(session('error')); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>


<div class="dashboard-card mb-4" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none">
    <div class="d-flex align-items-center gap-3">
        <div style="width:56px;height:56px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:24px;flex-shrink:0">
            <i class="bi bi-person-fill"></i>
        </div>
        <div class="flex-fill">
            <h5 class="fw-bold mb-0" style="color:white"><?php echo e($student->user?->name ?? 'Siswa'); ?></h5>
            <div style="font-size:13px;opacity:.85">
                <span class="me-3"><i class="bi bi-building me-1"></i><?php echo e($student->branch?->name ?? '—'); ?></span>
                <span class="me-3"><i class="bi bi-box-seam me-1"></i><?php echo e($student->package?->nama ?? 'Belum ada paket'); ?></span>
                <span><i class="bi bi-credit-card me-1"></i>NIS: <?php echo e($student->nis ?? '—'); ?></span>
            </div>
        </div>
        <a href="<?php echo e(route('admin.certificates.index')); ?>" class="btn btn-sm" style="background:rgba(255,255,255,.2);color:white;border:1px solid rgba(255,255,255,.3)">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>
</div>

<div class="row g-4">

    
    <div class="col-lg-7">
        <div class="dashboard-card">
            <h6 class="fw-bold mb-3"><i class="bi bi-journal-bookmark text-primary me-2"></i>Mata Pelajaran yang Diambil</h6>

            <?php if($courses->isEmpty()): ?>
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-journal-x" style="font-size:2rem;display:block;opacity:.3;margin-bottom:.5rem"></i>
                    Siswa ini belum terdaftar di mata pelajaran manapun.
                </div>
            <?php else: ?>
                <div class="row g-2 mb-4">
                    <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-6 col-md-4">
                        <div class="p-2 rounded-2 border text-center" style="background:var(--input-bg);border-color:var(--card-border)">
                            <i class="bi bi-book-fill text-primary" style="font-size:1.2rem"></i>
                            <div class="fw-semibold mt-1" style="font-size:12px"><?php echo e($c->nama); ?></div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>

            <hr class="my-3">
            <h6 class="fw-bold mb-3"><i class="bi bi-upload text-warning me-2"></i>Upload Sertifikat Baru</h6>

            <form method="POST" action="<?php echo e(route('admin.certificates.student.upload', $student->id)); ?>" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label fw-semibold" style="font-size:.85rem">Judul Sertifikat <span class="text-danger">*</span></label>
                        <input type="text" name="judul" class="form-control <?php $__errorArgs = ['judul'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               placeholder="cth: Sertifikat Kompetensi Matematika" value="<?php echo e(old('judul')); ?>" required>
                        <?php $__errorArgs = ['judul'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold" style="font-size:.85rem">Jenis <span class="text-danger">*</span></label>
                        <select name="jenis" class="form-select" required>
                            <option value="kompetensi" <?php echo e(old('jenis')=='kompetensi'?'selected':''); ?>>Kompetensi</option>
                            <option value="kelulusan"  <?php echo e(old('jenis')=='kelulusan' ?'selected':''); ?>>Kelulusan</option>
                            <option value="prestasi"   <?php echo e(old('jenis')=='prestasi'  ?'selected':''); ?>>Prestasi</option>
                            <option value="partisipasi"<?php echo e(old('jenis')=='partisipasi'?'selected':''); ?>>Partisipasi</option>
                        </select>
                    </div>
                    <?php if($courses->isNotEmpty()): ?>
                    <div class="col-12">
                        <label class="form-label fw-semibold" style="font-size:.85rem">Mata Pelajaran Terkait <span class="text-muted fw-normal">(opsional)</span></label>
                        <select name="course_id" class="form-select">
                            <option value="">— Pilih mata pelajaran —</option>
                            <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($c->id); ?>" <?php echo e(old('course_id')==$c->id?'selected':''); ?>><?php echo e($c->nama); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <?php endif; ?>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:.85rem">Tanggal Terbit <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_terbit" class="form-control" value="<?php echo e(old('tanggal_terbit', date('Y-m-d'))); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:.85rem">Tanggal Expired <span class="text-muted fw-normal">(opsional)</span></label>
                        <input type="date" name="tanggal_expired" class="form-control" value="<?php echo e(old('tanggal_expired')); ?>">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold" style="font-size:.85rem">Diterbitkan Oleh</label>
                        <input type="text" name="diterbitkan_oleh" class="form-control" placeholder="cth: Kepala Cabang / Direktur" value="<?php echo e(old('diterbitkan_oleh')); ?>">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold" style="font-size:.85rem">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="2" placeholder="Keterangan tambahan…"><?php echo e(old('deskripsi')); ?></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold" style="font-size:.85rem">File Sertifikat <span class="text-muted fw-normal">(PDF / Gambar, maks 10MB)</span></label>
                        <input type="file" name="file_sertifikat" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary fw-semibold px-4">
                            <i class="bi bi-award me-2"></i>Terbitkan Sertifikat
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    
    <div class="col-lg-5">
        <div class="dashboard-card">
            <h6 class="fw-bold mb-3">
                <i class="bi bi-award-fill text-warning me-2"></i>Sertifikat Diterbitkan
                <span class="badge ms-1" style="background:var(--soft-warning-bg);color:var(--soft-warning-text);font-size:11px"><?php echo e($certificates->count()); ?></span>
            </h6>

            <?php $__empty_1 = true; $__currentLoopData = $certificates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cert): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                $jenisMap = [
                    'kompetensi' =>['var(--soft-primary-bg)','var(--soft-primary-text)'],
                    'kelulusan'  =>['var(--soft-success-bg)','var(--soft-success-text)'],
                    'prestasi'   =>['var(--soft-warning-bg)','var(--soft-warning-text)'],
                    'partisipasi'=>['var(--soft-info-bg)','var(--soft-info-text)'],
                ];
                $jc = $jenisMap[$cert->jenis] ?? ['var(--soft-muted-bg)','var(--text-muted)'];
            ?>
            <div class="p-3 mb-2 rounded-3 border" style="background:var(--input-bg);border-color:var(--card-border)">
                <div class="d-flex align-items-start justify-content-between gap-2">
                    <div class="flex-fill" style="min-width:0">
                        <div class="fw-semibold text-truncate" style="font-size:13px"><?php echo e($cert->judul); ?></div>
                        <code style="font-size:10px;color:var(--text-muted)"><?php echo e($cert->nomor_sertifikat); ?></code>
                        <div class="d-flex align-items-center gap-2 mt-1">
                            <span style="background:<?php echo e($jc[0]); ?>;color:<?php echo e($jc[1]); ?>;padding:2px 7px;border-radius:6px;font-size:10px;font-weight:600;text-transform:capitalize"><?php echo e($cert->jenis); ?></span>
                            <span class="text-muted" style="font-size:11px"><?php echo e($cert->tanggal_terbit?->format('d M Y') ?? '—'); ?></span>
                        </div>
                    </div>
                    <div class="d-flex gap-1 flex-shrink-0">
                        <?php if($cert->file_sertifikat): ?>
                        <a href="<?php echo e(asset('storage/'.$cert->file_sertifikat)); ?>" target="_blank"
                           class="btn btn-sm btn-outline-success" style="border-radius:7px;font-size:11px" title="Lihat">
                            <i class="bi bi-eye"></i>
                        </a>
                        <?php endif; ?>
                        <button onclick="deleteCert(<?php echo e($cert->id); ?>, '<?php echo e(addslashes($cert->judul)); ?>')"
                                class="btn btn-sm btn-outline-danger" style="border-radius:7px;font-size:11px" title="Hapus">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="text-center py-4 text-muted">
                <i class="bi bi-award" style="font-size:2rem;display:block;opacity:.25;margin-bottom:.5rem"></i>
                Belum ada sertifikat untuk siswa ini.
            </div>
            <?php endif; ?>
        </div>
    </div>

</div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function deleteCert(id, judul) {
    if (!confirm('Hapus sertifikat "' + judul + '"?')) return;
    fetch('/admin/certificates/' + id, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>', 'Accept': 'application/json' }
    })
    .then(r => { if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
    .then(res => {
        if (res.success) {
            window.showToast && window.showToast(res.message, 'success');
            setTimeout(() => location.reload(), 600);
        } else {
            window.showToast && window.showToast(res.message || 'Gagal menghapus.', 'error');
        }
    })
    .catch(() => window.showToast && window.showToast('Gagal menghubungi server.', 'error'));
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/admin/certificates/student.blade.php ENDPATH**/ ?>