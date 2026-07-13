<?php $__env->startSection('title', 'Tambah Guru'); ?>
<?php $__env->startSection('page-title', 'Tambah Guru'); ?>

<?php $__env->startSection('content'); ?>
<div class="fade-up" style="margin:0;border-radius:0;box-shadow:none;background:transparent;padding:0;">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo e(route('admin.teachers.index')); ?>">Data Guru</a></li>
            <li class="breadcrumb-item active">Tambah Guru</li>
        </ol>
    </nav>

    <div class="w-100">
        <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
            <div style="width:48px;height:48px;border-radius:14px;background:linear-gradient(135deg,#461256,#c84ddf);display:flex;align-items:center;justify-content:center;color:white;font-size:22px;flex-shrink:0">
                <i class="bi bi-person-plus"></i>
            </div>
            <div>
                <h5 class="fw-bold mb-0">Tambah Guru Baru</h5>
                <p class="text-muted mb-0" style="font-size:13px">Isi seluruh data guru beserta akun login pada halaman penuh ini</p>
            </div>
        </div>

        <?php if($errors->any()): ?>
            <div class="alert alert-danger mb-3">
                <ul class="mb-0">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?php echo e(route('admin.teachers.store')); ?>" method="POST" enctype="multipart/form-data" style="width:100%;">
            <?php echo csrf_field(); ?>

            <div class="row g-4 align-items-start">
                <div class="col-lg-3 text-center">
                    <div class="mb-3">
                        <img id="photoPreview"
                             src="https://ui-avatars.com/api/?name=Guru&background=68117e&color=fff&size=140"
                             class="rounded-circle"
                             width="140"
                             height="140"
                             style="object-fit:cover;border:3px solid #c84ddf;box-shadow:0 10px 24px rgba(104,17,126,.18)">
                    </div>
                    <label class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-camera me-1"></i>Pilih Foto
                        <input type="file" name="photo" id="photoInput" class="d-none" accept="image/*">
                    </label>
                    <div class="text-muted mt-2" style="font-size:12px">Opsional</div>
                </div>

                <div class="col-lg-9">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control form-control-sm" value="<?php echo e(old('name')); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">NIG <span class="text-danger">*</span></label>
                            <input type="text" name="nig" class="form-control form-control-sm" value="<?php echo e(old('nig')); ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select name="gender" class="form-select form-select-sm" required>
                                <option value="">Pilih...</option>
                                <option value="L" <?php echo e(old('gender') == 'L' ? 'selected' : ''); ?>>Laki-laki</option>
                                <option value="P" <?php echo e(old('gender') == 'P' ? 'selected' : ''); ?>>Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Tanggal Lahir</label>
                            <input type="date" name="birth_date" class="form-control form-control-sm" value="<?php echo e(old('birth_date')); ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Pendidikan</label>
                            <select name="education" class="form-select form-select-sm">
                                <option value="">Pilih...</option>
                                <option value="D3" <?php echo e(old('education') == 'D3' ? 'selected' : ''); ?>>D3</option>
                                <option value="S1" <?php echo e(old('education') == 'S1' ? 'selected' : ''); ?>>S1</option>
                                <option value="S2" <?php echo e(old('education') == 'S2' ? 'selected' : ''); ?>>S2</option>
                                <option value="S3" <?php echo e(old('education') == 'S3' ? 'selected' : ''); ?>>S3</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Cabang <span class="text-danger">*</span></label>
                            <select name="branch_id" class="form-select form-select-sm" required>
                                <option value="">Pilih Cabang</option>
                                <option value="pusat" <?php echo e(old('branch_id') == 'pusat' ? 'selected' : ''); ?>>Pusat</option>
                                <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($branch->id); ?>" <?php echo e(old('branch_id') == $branch->id ? 'selected' : ''); ?>><?php echo e($branch->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">No. HP</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text">+62</span>
                                <input type="text" name="phone" class="form-control" placeholder="8xxxxxxxxxx" value="<?php echo e(old('phone')); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Jenis Guru <span class="text-danger">*</span></label>
                            <select name="jenis_guru" class="form-select form-select-sm" required>
                                <option value="">Pilih...</option>
                                <option value="kontrak" <?php echo e(old('jenis_guru') == 'kontrak' ? 'selected' : ''); ?>>Kontrak</option>
                                <option value="freelance" <?php echo e(old('jenis_guru') == 'freelance' ? 'selected' : ''); ?>>Freelance</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Mata Pelajaran</label>
                            <div class="border rounded p-2" style="min-height:52px;background:var(--input-bg,#fff)">
                                <div id="selectedCourses" class="d-flex flex-wrap gap-1 mb-2">
                                    <span class="text-muted small" id="emptyNotice">Belum ada mata pelajaran dipilih</span>
                                </div>
                                <div class="d-flex gap-2">
                                    <select id="courseSelect" class="form-select form-select-sm" style="max-width:300px">
                                        <option value="">-- Pilih mata pelajaran --</option>
                                        <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($course->id); ?>" data-nama="<?php echo e($course->nama); ?>"><?php echo e($course->nama); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="addCourse()">
                                        <i class="bi bi-plus-lg"></i> Tambah
                                    </button>
                                </div>
                            </div>
                            <div id="courseHiddenInputs"></div>
                            <div class="form-text">
                                Pilih dari daftar mata pelajaran aktif. Data dikelola di
                                <a href="<?php echo e(route('owner.subject.index')); ?>" target="_blank" class="text-primary">halaman Mata Pelajaran</a>.
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Upload CV</label>
                            <input type="file" name="cv" class="form-control form-control-sm" accept=".pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Alamat</label>
                            <textarea name="address" class="form-control form-control-sm" rows="2" placeholder="Alamat lengkap"><?php echo e(old('address')); ?></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Email Akun <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control form-control-sm" value="<?php echo e(old('email')); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control form-control-sm" minlength="8" required>
                        </div>

                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4 pt-3 border-top">
                <button type="submit" class="btn btn-success px-4">
                    <i class="bi bi-check-lg me-2"></i>Simpan Guru
                </button>
                <a href="<?php echo e(route('admin.teachers.index')); ?>" class="btn btn-outline-secondary px-4">
                    <i class="bi bi-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.getElementById('photoInput')?.addEventListener('change', function () {
    if (this.files && this.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('photoPreview').src = e.target.result;
        };
        reader.readAsDataURL(this.files[0]);
    }
});

// ── Mata Pelajaran tag-picker ──────────────────────────────────────────────
let _selected = {};   // { courseId: courseName }

function _renderCourses() {
    const chips  = document.getElementById('selectedCourses');
    const hidden = document.getElementById('courseHiddenInputs');
    const notice = document.getElementById('emptyNotice');
    const ids    = Object.keys(_selected);

    Array.from(chips.children).forEach(el => { if (el !== notice) el.remove(); });
    hidden.innerHTML = '';

    if (ids.length === 0) {
        notice.style.display = '';
    } else {
        notice.style.display = 'none';
        ids.forEach(id => {
            const nama = _selected[id];

            const chip = document.createElement('span');
            chip.className = 'badge d-inline-flex align-items-center gap-1 px-2 py-1';
            chip.style.cssText = 'background:var(--soft-primary,#f3e0fb);color:var(--bs-primary,#c84ddf);font-size:12px;font-weight:500;border:1px solid var(--bs-primary,#c84ddf)40';
            chip.innerHTML = `${nama}&nbsp;<button type="button" onclick="_removeCourse(${id})" style="background:none;border:none;padding:0;line-height:1;color:inherit;opacity:.7;cursor:pointer" title="Hapus"><i class="bi bi-x-lg" style="font-size:10px"></i></button>`;
            chips.insertBefore(chip, notice);

            const inp = document.createElement('input');
            inp.type  = 'hidden';
            inp.name  = 'course_ids[]';
            inp.value = id;
            hidden.appendChild(inp);
        });
    }

    const sel = document.getElementById('courseSelect');
    Array.from(sel.options).forEach(opt => {
        if (opt.value) opt.hidden = !!_selected[opt.value];
    });
}

function addCourse() {
    const sel  = document.getElementById('courseSelect');
    const id   = sel.value;
    const nama = sel.options[sel.selectedIndex]?.dataset?.nama;
    if (!id) return;
    _selected[id] = nama;
    sel.value = '';
    _renderCourses();
}

function _removeCourse(id) {
    delete _selected[id];
    _renderCourses();
}

document.getElementById('courseSelect')?.addEventListener('keydown', e => {
    if (e.key === 'Enter') { e.preventDefault(); addCourse(); }
});

_renderCourses();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/admin/teachers/create.blade.php ENDPATH**/ ?>