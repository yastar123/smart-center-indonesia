<?php $__env->startSection('title', 'Edit Kurikulum & Silabus'); ?>
<?php $__env->startSection('page-title', 'Edit Kurikulum & Silabus'); ?>

<?php $__env->startSection('content'); ?>

<div class="mb-4 fade-up">
    <a href="<?php echo e(route('owner.curriculum.index')); ?>" class="btn btn-sm"
       style="background:var(--input-bg);border:1px solid var(--card-border);color:var(--text-muted);border-radius:9px;font-size:13px">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
</div>

<form action="<?php echo e(route('owner.curriculum.update', $curriculum->id)); ?>" method="POST" enctype="multipart/form-data">
<?php echo csrf_field(); ?>

<div class="row g-4">

    
    <div class="col-lg-4">
        <div class="dashboard-card fade-up">
            <h6 class="fw-bold mb-4" style="color:var(--text-primary)">
                <i class="bi bi-journal-bookmark text-primary me-2"></i>Informasi Kurikulum
            </h6>

            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:13px">Mata Pelajaran <span class="text-danger">*</span></label>
                <select name="course_id" class="form-select <?php $__errorArgs = ['course_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                    <option value="">— Pilih Mata Pelajaran —</option>
                    <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($c->id); ?>" <?php echo e($curriculum->course_id == $c->id ? 'selected' : ''); ?>><?php echo e($c->nama); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['course_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:13px">Lingkup Kurikulum <span class="text-danger">*</span></label>
                <div class="d-flex gap-3">
                    <label class="d-flex align-items-center gap-2 p-3 flex-1"
                           style="border:1.5px solid var(--card-border);border-radius:12px;cursor:pointer;flex:1;transition:.15s"
                           id="lbl-global">
                        <input type="radio" name="scope" value="global" <?php echo e($curriculum->scope === 'global' ? 'checked' : ''); ?>

                               onchange="toggleBranch(this.value)" class="d-none">
                        <i class="bi bi-globe" style="font-size:18px;color:#c84ddf"></i>
                        <div>
                            <div class="fw-semibold" style="font-size:13px">Global</div>
                            <div class="text-muted" style="font-size:11px">Semua cabang</div>
                        </div>
                    </label>
                    <label class="d-flex align-items-center gap-2 p-3 flex-1"
                           style="border:1.5px solid var(--card-border);border-radius:12px;cursor:pointer;flex:1;transition:.15s"
                           id="lbl-lokal">
                        <input type="radio" name="scope" value="lokal" <?php echo e($curriculum->scope === 'lokal' ? 'checked' : ''); ?>

                               onchange="toggleBranch(this.value)" class="d-none">
                        <i class="bi bi-geo-alt" style="font-size:18px;color:#0284c7"></i>
                        <div>
                            <div class="fw-semibold" style="font-size:13px">Lokal</div>
                            <div class="text-muted" style="font-size:11px">Satu cabang</div>
                        </div>
                    </label>
                </div>
            </div>

            <div class="mb-3" id="branch-row" style="<?php echo e($curriculum->scope === 'lokal' ? '' : 'display:none'); ?>">
                <label class="form-label fw-semibold" style="font-size:13px">Cabang</label>
                <select name="cabang_id" class="form-select <?php $__errorArgs = ['cabang_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <option value="">— Pilih Cabang —</option>
                    <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($b->id); ?>" <?php echo e($curriculum->cabang_id == $b->id ? 'selected' : ''); ?>><?php echo e($b->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['cabang_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>
    </div>

    
    <div class="col-lg-8">
        <div class="dashboard-card fade-up" style="animation-delay:.05s">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="fw-bold mb-0" style="color:var(--text-primary)">
                    <i class="bi bi-list-ol text-primary me-2"></i>Bab / Unit
                </h6>
                <button type="button" class="btn btn-sm btn-outline-primary" style="border-radius:9px;font-size:12px" onclick="addChapter()">
                    <i class="bi bi-plus me-1"></i>Tambah Bab
                </button>
            </div>

            <div id="chapters-container" class="d-flex flex-column gap-3">
                <?php $__currentLoopData = $curriculum->chapters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $ch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="chapter-row p-3" style="border:1.5px solid var(--card-border);border-radius:14px;background:var(--input-bg)"
                     data-existing-id="<?php echo e($ch->id); ?>">
                    <input type="hidden" name="chapter_ids[<?php echo e($i); ?>]" value="<?php echo e($ch->id); ?>">
                    <input type="hidden" name="chapter_pdf_existing[<?php echo e($i); ?>]" value="<?php echo e($ch->pdf_path); ?>">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="chapter-num fw-bold" style="width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,#461256,#c84ddf);color:white;display:flex;align-items:center;justify-content:center;font-size:13px;flex-shrink:0"><?php echo e($i + 1); ?></div>
                        <div class="fw-semibold" style="font-size:13px;flex:1">Bab <?php echo e($i + 1); ?></div>
                        <button type="button" class="btn btn-sm" onclick="removeChapter(this)"
                                style="background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.2);color:#dc2626;border-radius:8px;padding:3px 8px">
                            <i class="bi bi-trash" style="font-size:12px"></i>
                        </button>
                    </div>
                    <div class="row g-3">
                        <div class="col-8">
                            <label class="form-label" style="font-size:12px;font-weight:600">Judul Bab <span class="text-danger">*</span></label>
                            <input type="text" name="chapters[<?php echo e($i); ?>][judul]" class="form-control" value="<?php echo e($ch->judul); ?>" required>
                        </div>
                        <div class="col-4">
                            <label class="form-label" style="font-size:12px;font-weight:600">Jumlah Sesi</label>
                            <input type="number" name="chapters[<?php echo e($i); ?>][jumlah_sesi]" class="form-control" value="<?php echo e($ch->jumlah_sesi); ?>" min="1" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label" style="font-size:12px;font-weight:600">PDF Silabus</label>
                            <?php if($ch->pdf_path): ?>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <a href="<?php echo e(asset('storage/'.$ch->pdf_path)); ?>" target="_blank"
                                   class="btn btn-sm d-flex align-items-center gap-1"
                                   style="background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.2);color:#dc2626;border-radius:8px;font-size:12px">
                                    <i class="bi bi-file-pdf-fill"></i> Lihat PDF
                                </a>
                                <span class="text-muted" style="font-size:11px">Upload baru untuk mengganti</span>
                            </div>
                            <?php endif; ?>
                            <input type="file" name="chapters[<?php echo e($i); ?>][pdf]" class="form-control" accept=".pdf">
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <div class="mt-3">
                <button type="button" class="btn btn-outline-primary w-100" style="border-radius:10px;border-style:dashed;font-size:13px" onclick="addChapter()">
                    <i class="bi bi-plus me-1"></i>Tambah Bab
                </button>
            </div>
        </div>

        <div class="d-flex gap-3 mt-4 fade-up" style="animation-delay:.1s">
            <button type="submit" class="btn btn-primary fw-semibold px-5" style="border-radius:12px">
                <i class="bi bi-save me-2"></i>Simpan Perubahan
            </button>
            <a href="<?php echo e(route('owner.curriculum.index')); ?>" class="btn" style="border-radius:12px;border:1px solid var(--card-border);color:var(--text-muted)">
                Batal
            </a>
        </div>
    </div>

</div>
</form>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
let chapIdx = <?php echo e($curriculum->chapters->count()); ?>;

function addChapter() {
    const container = document.getElementById('chapters-container');
    const num = container.children.length + 1;
    const html = `
    <div class="chapter-row p-3" style="border:1.5px solid var(--card-border);border-radius:14px;background:var(--input-bg)">
        <input type="hidden" name="chapter_ids[${chapIdx}]" value="">
        <input type="hidden" name="chapter_pdf_existing[${chapIdx}]" value="">
        <div class="d-flex align-items-center gap-2 mb-3">
            <div class="chapter-num fw-bold" style="width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,#461256,#c84ddf);color:white;display:flex;align-items:center;justify-content:center;font-size:13px;flex-shrink:0">${num}</div>
            <div class="fw-semibold" style="font-size:13px;flex:1">Bab ${num}</div>
            <button type="button" class="btn btn-sm" onclick="removeChapter(this)"
                    style="background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.2);color:#dc2626;border-radius:8px;padding:3px 8px">
                <i class="bi bi-trash" style="font-size:12px"></i>
            </button>
        </div>
        <div class="row g-3">
            <div class="col-8">
                <label class="form-label" style="font-size:12px;font-weight:600">Judul Bab <span class="text-danger">*</span></label>
                <input type="text" name="chapters[${chapIdx}][judul]" class="form-control" placeholder="cth. Bab ${num}: ..." required>
            </div>
            <div class="col-4">
                <label class="form-label" style="font-size:12px;font-weight:600">Jumlah Sesi</label>
                <input type="number" name="chapters[${chapIdx}][jumlah_sesi]" class="form-control" value="2" min="1" required>
            </div>
            <div class="col-12">
                <label class="form-label" style="font-size:12px;font-weight:600">PDF Silabus <span class="text-muted fw-normal">(opsional)</span></label>
                <input type="file" name="chapters[${chapIdx}][pdf]" class="form-control" accept=".pdf">
            </div>
        </div>
    </div>`;
    container.insertAdjacentHTML('beforeend', html);
    chapIdx++;
    reorderNumbers();
}

function removeChapter(btn) {
    const rows = document.querySelectorAll('.chapter-row');
    if (rows.length <= 1) { alert('Minimal harus ada 1 bab.'); return; }
    btn.closest('.chapter-row').remove();
    reorderNumbers();
}

function reorderNumbers() {
    document.querySelectorAll('.chapter-row').forEach((row, i) => {
        row.querySelector('.chapter-num').textContent = i + 1;
    });
}

function toggleBranch(val) {
    document.getElementById('branch-row').style.display = val === 'lokal' ? '' : 'none';
    highlightScope(val);
}

function highlightScope(val) {
    document.getElementById('lbl-global').style.borderColor = val === 'global' ? '#c84ddf' : 'var(--card-border)';
    document.getElementById('lbl-lokal').style.borderColor  = val === 'lokal'  ? '#0284c7' : 'var(--card-border)';
    document.getElementById('lbl-global').style.background  = val === 'global' ? 'rgba(200,77,223,.06)' : '';
    document.getElementById('lbl-lokal').style.background   = val === 'lokal'  ? 'rgba(2,132,199,.06)'  : '';
}

document.addEventListener('DOMContentLoaded', () => {
    const checked = document.querySelector('input[name="scope"]:checked');
    if (checked) highlightScope(checked.value);
    document.querySelectorAll('input[name="scope"]').forEach(r => {
        r.addEventListener('change', () => highlightScope(r.value));
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/owner/curriculum/edit.blade.php ENDPATH**/ ?>