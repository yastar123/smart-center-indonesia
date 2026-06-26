<?php $__env->startSection('title', 'Edit Siswa'); ?>
<?php $__env->startSection('page-title', 'Edit Siswa'); ?>

<?php $__env->startSection('content'); ?>
<div class="fade-up" style="margin:0;border-radius:0;box-shadow:none;background:transparent;padding:0;">
    <?php if($errors->any()): ?>
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <ul class="mb-0 ps-3">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <form action="<?php echo e(route('admin.students.update', $student)); ?>" method="POST" enctype="multipart/form-data" style="width:100%;">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        <div class="row g-4 align-items-start">
            <div class="col-md-4 text-center" style="padding-right:24px;">
                <div class="mb-3">
                    <img id="photoPreview"
                         src="<?php echo e($student->photo ? Storage::url($student->photo) : 'https://ui-avatars.com/api/?name='.urlencode($student->name).'&background=68117e&color=fff&size=120'); ?>"
                         class="rounded-circle" width="120" height="120"
                         style="object-fit:cover;border:3px solid #c84ddf">
                </div>
                <label class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-camera me-1"></i>Pilih Foto
                    <input type="file" name="photo" class="d-none" accept="image/*" onchange="previewPhoto(this)">
                </label>

                <?php
                    $studentPackages = isset($student) && $student->relationLoaded('package')
                        ? collect([$student->package])->filter()
                        : collect();
                ?>

                <div class="mt-4 p-3 rounded-3 border text-start" style="background:var(--input-bg);border-color:var(--card-border);">
                    <div class="fw-semibold small mb-2">Paket yang Diambil</div>
                    <?php if($studentPackages->isNotEmpty()): ?>
                        <?php $__currentLoopData = $studentPackages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pkg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="small text-muted mb-1">
                                <strong><?php echo e($pkg->nama ?? '-'); ?></strong>
                            </div>
                            <?php if(!empty($pkg->deskripsi)): ?>
                                <div class="small text-muted mb-1"><?php echo e($pkg->deskripsi); ?></div>
                            <?php endif; ?>
                            <div class="small text-muted">
                                <?php if(!empty($pkg->cabang->name)): ?>
                                    <?php echo e($pkg->cabang->name); ?>

                                <?php endif; ?>
                                <?php if(!empty($pkg->guru->name)): ?>
                                    · <?php echo e($pkg->guru->name); ?>

                                <?php endif; ?>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <div class="small text-muted mb-0">Belum sama sekali siswa ini memiliki paket</div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-8" style="padding-left:8px;">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label small fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name" value="<?php echo e(old('name', $student->name)); ?>" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Jenis Kelamin <span class="text-danger">*</span></label>
                        <select name="gender" class="form-select form-select-sm" required>
                            <option value="">Pilih...</option>
                            <option value="L" <?php echo e(old('gender', $student->gender) == 'L' ? 'selected' : ''); ?>>Laki-laki</option>
                            <option value="P" <?php echo e(old('gender', $student->gender) == 'P' ? 'selected' : ''); ?>>Perempuan</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Tempat Lahir</label>
                        <input type="text" name="birth_place" value="<?php echo e(old('birth_place', $student->birth_place)); ?>" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Tanggal Lahir</label>
                        <input type="date" name="birth_date" value="<?php echo e(old('birth_date', optional($student->birth_date)->format('Y-m-d'))); ?>" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Cabang <span class="text-danger">*</span></label>
                        <select name="branch_id" class="form-select form-select-sm" required>
                            <option value="">Pilih Cabang</option>
                            <option value="pusat" <?php echo e(old('branch_id', $student->branch_id ?? 'pusat') == 'pusat' ? 'selected' : ''); ?>>Pusat</option>
                            <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($b->id); ?>" <?php echo e(old('branch_id', $student->branch_id) == $b->id ? 'selected' : ''); ?>><?php echo e($b->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Kategori Peserta Didik</label>
                        <select name="kategori_peserta_didik" class="form-select form-select-sm">
                            <option value="">Pilih kategori...</option>
                            <option value="Pra Sekolah (PAUD/TK)" <?php echo e(old('kategori_peserta_didik', $student->kategori_peserta_didik) == 'Pra Sekolah (PAUD/TK)' ? 'selected' : ''); ?>>Pra Sekolah (PAUD/TK)</option>
                            <option value="Sekolah Dasar (SD)" <?php echo e(old('kategori_peserta_didik', $student->kategori_peserta_didik) == 'Sekolah Dasar (SD)' ? 'selected' : ''); ?>>Sekolah Dasar (SD)</option>
                            <option value="Sekolah Menengah Pertama (SMP)" <?php echo e(old('kategori_peserta_didik', $student->kategori_peserta_didik) == 'Sekolah Menengah Pertama (SMP)' ? 'selected' : ''); ?>>Sekolah Menengah Pertama (SMP)</option>
                            <option value="Sekolah Menengah Atas/Kejuruan (SMA/SMK)" <?php echo e(old('kategori_peserta_didik', $student->kategori_peserta_didik) == 'Sekolah Menengah Atas/Kejuruan (SMA/SMK)' ? 'selected' : ''); ?>>Sekolah Menengah Atas/Kejuruan (SMA/SMK)</option>
                            <option value="Mahasiswa" <?php echo e(old('kategori_peserta_didik', $student->kategori_peserta_didik) == 'Mahasiswa' ? 'selected' : ''); ?>>Mahasiswa</option>
                            <option value="Umum" <?php echo e(old('kategori_peserta_didik', $student->kategori_peserta_didik) == 'Umum' ? 'selected' : ''); ?>>Umum</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select form-select-sm" required>
                            <option value="aktif"       <?php echo e(old('status', $student->status) == 'aktif'       ? 'selected' : ''); ?>>Aktif</option>
                            <option value="cuti"        <?php echo e(old('status', $student->status) == 'cuti'        ? 'selected' : ''); ?>>Cuti</option>
                            <option value="tidak_aktif" <?php echo e(old('status', $student->status) == 'tidak_aktif' || old('status', $student->status) == 'nonaktif' ? 'selected' : ''); ?>>Tidak Aktif</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">No. HP Siswa</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">+62</span>
                            <input type="text" name="phone" value="<?php echo e(old('phone', $student->phone)); ?>" class="form-control" placeholder="8xxxxxxxxxx">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label small fw-semibold">Alamat</label>
                        <textarea name="address" class="form-control form-control-sm" rows="2"><?php echo e(old('address', $student->address)); ?></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Email Akun <span class="text-danger">*</span></label>
                        <input type="email" name="email" value="<?php echo e(old('email', $student->user?->email)); ?>" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control form-control-sm" minlength="8" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Nama Orang Tua / Wali</label>
                        <input type="text" name="parent_name" value="<?php echo e(old('parent_name', $student->parent_name)); ?>" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">HP Orang Tua</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">+62</span>
                            <input type="text" name="parent_phone" value="<?php echo e(old('parent_phone', $student->parent_phone)); ?>" class="form-control" placeholder="8xxxxxxxxxx">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4 pt-3 border-top text-end" style="border-color:var(--card-border);">
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-check-lg me-1"></i>Simpan Siswa
            </button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function previewPhoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('photoPreview').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/admin/students/edit.blade.php ENDPATH**/ ?>