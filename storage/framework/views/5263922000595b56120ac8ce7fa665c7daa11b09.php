<?php $__env->startSection('title', 'Edit Paket Belajar'); ?>
<?php $__env->startSection('page-title', 'Edit Paket Belajar'); ?>

<?php $__env->startSection('content'); ?>
<div class="fade-up">

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo e(route('owner.course-package.index')); ?>">Paket Belajar</a></li>
        <li class="breadcrumb-item active">Edit — <?php echo e($coursePackage->nama); ?></li>
    </ol>
</nav>

<div class="dashboard-card mb-4" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none">
    <div class="d-flex align-items-center gap-3">
        <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0">
            <i class="bi bi-pencil-square"></i>
        </div>
        <div>
            <h5 class="fw-bold mb-0" style="color:white">Edit Paket Belajar</h5>
            <p class="mb-0" style="font-size:12px;opacity:.8">Perbarui konfigurasi, mata pelajaran, dan guru pengajar</p>
        </div>
    </div>
</div>

<?php if($errors->any()): ?>
    <div class="alert alert-danger mb-3"><ul class="mb-0"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul></div>
<?php endif; ?>

<?php
    $selectedCourseIds   = old('course_ids', $coursePackage->mataPelajaran->pluck('id')->toArray());
    $existingCTMap       = $coursePackage->courseTeachers->groupBy('course_id');
?>

<form method="POST" action="<?php echo e(route('owner.course-package.update', $coursePackage)); ?>" id="pkgForm">
    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

    
    <div class="dashboard-card mb-4">
        <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
            <div style="width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,#461256,#c84ddf);display:flex;align-items:center;justify-content:center;color:white;font-size:13px;font-weight:700">1</div>
            <h6 class="fw-bold mb-0">Informasi Dasar Paket</h6>
        </div>
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
            <div class="col-md-4">
                <label class="form-label fw-semibold">Jenis Paket <span class="text-danger">*</span></label>
                <select name="jenis" class="form-select" required>
                    <?php $__currentLoopData = ['reguler'=>'Reguler','intensif'=>'Intensif','privat'=>'Privat (1 Siswa)','online'=>'Online']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val=>$label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($val); ?>" <?php echo e(old('jenis',$coursePackage->jenis)==$val?'selected':''); ?>><?php echo e($label); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Metode Absensi <span class="text-danger">*</span></label>
                <select name="metode_absensi" class="form-select" required>
                    <option value="manual"   <?php echo e(old('metode_absensi',$coursePackage->metode_absensi??'manual')=='manual'  ?'selected':''); ?>>Manual</option>
                    <option value="otomatis" <?php echo e(old('metode_absensi',$coursePackage->metode_absensi??'')=='otomatis'       ?'selected':''); ?>>Otomatis</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Tipe Kelas <span class="text-danger">*</span></label>
                <select name="tipe_kelas" class="form-select" required>
                    <option value="offline" <?php echo e(old('tipe_kelas',$coursePackage->tipe_kelas??'offline')=='offline'?'selected':''); ?>>Offline</option>
                    <option value="online"  <?php echo e(old('tipe_kelas',$coursePackage->tipe_kelas??'')=='online'         ?'selected':''); ?>>Online</option>
                    <option value="private" <?php echo e(old('tipe_kelas',$coursePackage->tipe_kelas??'')=='private'        ?'selected':''); ?>>Private</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Harga Dasar (Rp) <span class="text-danger">*</span></label>
                <input type="number" name="harga" class="form-control" value="<?php echo e(old('harga', $coursePackage->harga)); ?>" min="0" required>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Jumlah Sesi <span class="text-danger">*</span></label>
                <input type="number" name="jumlah_pertemuan" class="form-control" value="<?php echo e(old('jumlah_pertemuan', $coursePackage->jumlah_pertemuan)); ?>" min="1" required>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Durasi (bulan)</label>
                <input type="number" name="durasi_bulan" class="form-control" value="<?php echo e(old('durasi_bulan', $coursePackage->durasi_bulan)); ?>" min="1">
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
            <div class="col-md-3">
                <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                <select name="status" class="form-select" required>
                    <option value="aktif"    <?php echo e(old('status',$coursePackage->status)=='aktif'   ?'selected':''); ?>>Aktif</option>
                    <option value="nonaktif" <?php echo e(old('status',$coursePackage->status)=='nonaktif'?'selected':''); ?>>Non Aktif</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end pb-1">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_unggulan" id="isUnggulan" value="1"
                           <?php echo e(old('is_unggulan', $coursePackage->is_unggulan) ? 'checked' : ''); ?>>
                    <label class="form-check-label" for="isUnggulan">Paket Unggulan ⭐</label>
                </div>
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="2"><?php echo e(old('deskripsi', $coursePackage->deskripsi)); ?></textarea>
            </div>
        </div>
    </div>

    
    <div class="dashboard-card mb-4">
        <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
            <div style="width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,#461256,#c84ddf);display:flex;align-items:center;justify-content:center;color:white;font-size:13px;font-weight:700">2</div>
            <h6 class="fw-bold mb-0">Mata Pelajaran & Guru Pengajar</h6>
        </div>
        <p class="text-muted mb-3" style="font-size:13px">
            <i class="bi bi-info-circle me-1"></i>
            Centang mata pelajaran yang termasuk dalam paket ini, lalu pilih guru yang mengajar setiap mata pelajaran.
        </p>

        <?php if($courses->isEmpty()): ?>
            <div class="alert alert-warning mb-0">Belum ada mata pelajaran aktif.</div>
        <?php else: ?>
        <?php
            $jenisLabels = [
                'komputer'  => 'Kursus Komputer',
                'bahasa'    => 'Kursus Bahasa Asing',
                'mapel'     => 'Mata Pelajaran',
                'kedinasan' => 'Program Kedinasan',
                'akpol'     => 'AKPOL / AKMIL / BINTARA',
                'cpns'      => 'CPNS',
                'bumn'      => 'BUMN',
                'lainnya'   => 'Lainnya',
            ];
        ?>
        <div id="courseTeacherRows">
            <?php $__currentLoopData = $coursesGrouped; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jenis => $groupCourses): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="mb-2 px-1 py-1 rounded-2" style="background:linear-gradient(135deg,#f8f5ff,#f3eeff);border:1px solid #e9d5ff;">
                <div style="font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.07em;color:#68117e;padding:6px 10px 4px">
                    <i class="bi bi-folder2-open me-1"></i><?php echo e($jenisLabels[$jenis] ?? ucfirst($jenis)); ?>

                </div>
            </div>
            <?php $__currentLoopData = $groupCourses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $isChecked   = in_array($c->id, $selectedCourseIds);
                $ctForCourse = $existingCTMap->get($c->id, collect())->pluck('teacher_id')->toArray();
                $oldTeachers = old("course_teachers.{$c->id}", $ctForCourse);
            ?>
            <div class="mb-3 rounded-3" style="border:1.5px solid <?php echo e($isChecked ? '#c84ddf' : 'var(--card-border)'); ?>;overflow:hidden;transition:.2s" id="card-<?php echo e($c->id); ?>">
                <div class="d-flex align-items-center gap-3 px-3 py-2" style="background:var(--input-bg);cursor:pointer"
                     onclick="document.getElementById('chk-<?php echo e($c->id); ?>').click()">
                    <input class="form-check-input course-check" type="checkbox"
                           name="course_ids[]" value="<?php echo e($c->id); ?>"
                           id="chk-<?php echo e($c->id); ?>"
                           <?php echo e($isChecked ? 'checked' : ''); ?>

                           onchange="toggleTeacherSection(<?php echo e($c->id); ?>, this.checked)"
                           onclick="event.stopPropagation()">
                    <div class="flex-fill fw-semibold" style="font-size:14px">
                        <i class="bi bi-book text-primary me-2"></i><?php echo e($c->nama); ?>

                    </div>
                    <div id="badge-<?php echo e($c->id); ?>">
                        <?php if($isChecked): ?>
                            <span class="badge bg-success-subtle text-success">Dipilih</span>
                        <?php else: ?>
                            <span class="badge bg-secondary-subtle text-muted">Tidak dipilih</span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="px-3 py-3 teacher-section border-top" id="teachers-<?php echo e($c->id); ?>"
                     style="<?php echo e($isChecked ? '' : 'display:none'); ?>">
                    <div class="text-muted mb-2" style="font-size:12px">
                        <i class="bi bi-person-badge me-1 text-primary"></i>
                        Guru yang mengajar <strong><?php echo e($c->nama); ?></strong>:
                    </div>
                    <div class="row g-2">
                        <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-md-4 col-sm-6">
                            <div class="form-check p-2 rounded-2" style="border:1px solid var(--card-border);background:var(--bs-body-bg)">
                                <input class="form-check-input" type="checkbox"
                                       name="course_teachers[<?php echo e($c->id); ?>][]"
                                       value="<?php echo e($t->id); ?>"
                                       id="ct_<?php echo e($c->id); ?>_<?php echo e($t->id); ?>"
                                       <?php echo e(is_array($oldTeachers) && in_array($t->id, $oldTeachers) ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="ct_<?php echo e($c->id); ?>_<?php echo e($t->id); ?>" style="font-size:12px;cursor:pointer">
                                    <span class="fw-semibold"><?php echo e($t->name); ?></span>
                                    <?php if($t->nig): ?><br><span class="text-muted" style="font-size:11px">NIG: <?php echo e($t->nig); ?></span><?php endif; ?>
                                </label>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php endif; ?>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary px-4">
            <i class="bi bi-check-lg me-2"></i>Simpan Perubahan
        </button>
        <a href="<?php echo e(route('owner.course-package.show', $coursePackage)); ?>" class="btn btn-outline-secondary px-4">
            <i class="bi bi-arrow-left me-2"></i>Batal
        </a>
    </div>
</form>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function toggleTeacherSection(courseId, checked) {
    const section = document.getElementById('teachers-' + courseId);
    const card    = document.getElementById('card-' + courseId);
    const badge   = document.getElementById('badge-' + courseId);
    if (section) section.style.display = checked ? '' : 'none';
    if (card)    card.style.borderColor = checked ? '#c84ddf' : 'var(--card-border)';
    if (badge)   badge.innerHTML = checked
        ? '<span class="badge bg-success-subtle text-success">Dipilih</span>'
        : '<span class="badge bg-secondary-subtle text-muted">Tidak dipilih</span>';
    if (!checked && section) {
        section.querySelectorAll('input[type=checkbox]').forEach(cb => cb.checked = false);
    }
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/owner/course-package/edit.blade.php ENDPATH**/ ?>