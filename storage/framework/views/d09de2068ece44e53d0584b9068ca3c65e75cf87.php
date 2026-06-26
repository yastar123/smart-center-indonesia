<?php $__env->startSection('title', 'Mata Pelajaran'); ?>
<?php $__env->startSection('page-title', 'Mata Pelajaran'); ?>

<?php $__env->startSection('content'); ?>
<div>

    
    <div class="dashboard-card mb-4 fade-up" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
        <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
        <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
        <div class="row align-items-center g-3" style="position:relative">
            <div class="col-md-8">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0">
                        <i class="bi bi-book-half"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0" style="color:white">Mata Pelajaran</h5>
                        <span style="font-size:12px;opacity:.8">Kelola semua mata pelajaran dan subjek yang tersedia di semua cabang</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-md-end d-flex justify-content-md-end gap-2">
                <button onclick="openModal()" class="btn fw-semibold px-4" style="background:rgba(255,255,255,.2);color:white;border:1px solid rgba(255,255,255,.3);border-radius:10px;backdrop-filter:blur(10px)">
                    <i class="bi bi-plus-lg me-2"></i>Tambah Mapel
                </button>

            </div>
        </div>
    </div>

    
    <div class="row g-3 mb-4">
        <?php
            $statCards = [
                ['label'=>'Total Mapel', 'value'=>$stats['total'],   'icon'=>'bi-book-fill',      'topColor'=>'#10b981', 'textColor'=>'text-success', 'iconBg'=>'bg-success-soft'],
                ['label'=>'Mapel Aktif', 'value'=>$stats['aktif'],   'icon'=>'bi-check-circle-fill','topColor'=>'#c84ddf','textColor'=>'text-primary', 'iconBg'=>'bg-primary-soft'],
                ['label'=>'Tidak Aktif', 'value'=>$stats['nonaktif'],'icon'=>'bi-x-circle-fill',  'topColor'=>'#ef4444', 'textColor'=>'text-danger',  'iconBg'=>'bg-danger-soft'],
            ];
        ?>
        <?php $__currentLoopData = $statCards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $sc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-6 col-lg-4 fade-up" style="animation-delay:<?php echo e($i * 0.05); ?>s">
            <div class="stat-card" style="border-top:3px solid <?php echo e($sc['topColor']); ?>">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-title"><?php echo e($sc['label']); ?></div>
                        <div class="stat-value <?php echo e($sc['textColor']); ?> count-up" data-target="<?php echo e($sc['value']); ?>"><?php echo e($sc['value']); ?></div>
                    </div>
                    <div class="stat-icon <?php echo e($sc['iconBg']); ?>" style="color:white">
                        <i class="bi <?php echo e($sc['icon']); ?>"></i>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <div class="dashboard-card mb-4 fade-up">
        <div>
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-12 col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Cari kode atau nama mata pelajaran…" value="<?php echo e(request('search')); ?>">
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <select name="cabang_id" class="form-select">
                        <option value="">Semua Cabang</option>
                        <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($b->id); ?>" <?php echo e(request('cabang_id')==$b->id?'selected':''); ?>><?php echo e($b->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="aktif" <?php echo e(request('status')=='aktif'?'selected':''); ?>>Aktif</option>
                        <option value="nonaktif" <?php echo e(request('status')=='nonaktif'?'selected':''); ?>>Nonaktif</option>
                    </select>
                </div>
                <div class="col-12 col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">Filter</button>
                    <?php if(request()->hasAny(['search','cabang_id','status'])): ?>
                        <a href="<?php echo e(route('admin.courses.index')); ?>" class="btn btn-outline-secondary"><i class="bi bi-x"></i></a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    
    <div class="dashboard-card fade-up">
        <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="thead-modern">
                        <tr>
                            <th class="px-4">#</th>
                            <th>Kode</th>
                            <th>Nama Mata Pelajaran</th>
                            <th class="d-none d-md-table-cell">Cabang</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr style="transition:background .15s;">
                            <td class="px-4 text-muted" style="font-size:.85rem;"><?php echo e($courses->firstItem() + $i); ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div style="width:36px;height:36px;border-radius:10px;background:var(--soft-primary-bg);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i class="bi bi-book" style="color:var(--soft-primary-text);font-size:.95rem;"></i>
                                    </div>
                                    <span class="fw-semibold" style="font-size:.85rem;font-family:monospace;"><?php echo e($course->kode); ?></span>
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold" style="font-size:.9rem;"><?php echo e($course->nama); ?></div>
                                <?php if($course->deskripsi): ?>
                                <div class="text-muted" style="font-size:.78rem;"><?php echo e(Str::limit($course->deskripsi, 50)); ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="d-none d-md-table-cell">
                                <span style="font-size:.85rem;"><?php echo e($course->cabang?->name ?? 'Semua Cabang'); ?></span>
                            </td>
                            <td>
                                <?php if($course->status === 'aktif'): ?>
                                    <span class="badge rounded-pill" style="background:rgba(16,185,129,.15);color:#059669;font-size:.75rem;font-weight:600;padding:.35em .75em;">Aktif</span>
                                <?php else: ?>
                                    <span class="badge rounded-pill" style="background:rgba(239,68,68,.15);color:#dc2626;font-size:.75rem;font-weight:600;padding:.35em .75em;">Nonaktif</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex gap-1 justify-content-end">
                                    <button class="btn btn-sm btn-act-edit" onclick="editCourse(<?php echo e($course->id); ?>)" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-act-del" onclick="deleteCourse(<?php echo e($course->id); ?>, '<?php echo e(addslashes($course->nama)); ?>')" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div style="opacity:.5;">
                                    <i class="bi bi-book" style="font-size:2.5rem;display:block;margin-bottom:.5rem;color:#10b981;"></i>
                                    <div class="fw-semibold">Belum ada mata pelajaran</div>
                                    <small class="text-muted">Tambahkan mata pelajaran pertama Anda</small>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php if($courses->hasPages()): ?>
            <div class="mt-4 pt-3 d-flex justify-content-center" style="border-top:1px solid var(--card-border)"><?php echo e($courses->links()); ?></div>
            <?php endif; ?>
    </div>
</div>


<div class="modal fade" id="courseModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0" style="border-radius:20px;box-shadow:0 20px 60px rgba(0,0,0,.15);">
            <div class="modal-header border-0 p-4" style="background:linear-gradient(135deg,#059669,#10b981);border-radius:20px 20px 0 0">
                <div>
                    <h5 class="modal-title fw-bold mb-0 text-white" id="modalTitle"><i class="bi bi-book me-2"></i>Tambah Mata Pelajaran</h5>
                    <div style="font-size:12px;opacity:.75;color:white;margin-top:3px">Isi data mata pelajaran di bawah ini</div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4 py-3">
                <form id="courseForm">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" id="courseId">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Kode <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="kode" placeholder="cth: MTK, IPA, BIG" required>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Nama Mata Pelajaran <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama" placeholder="cth: Matematika Dasar" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Cabang</label>
                            <select class="form-select" id="cabang_id">
                                <option value="">Semua Cabang</option>
                                <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($b->id); ?>"><?php echo e($b->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi" rows="3" placeholder="Deskripsi singkat mata pelajaran…"></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Status <span class="text-danger">*</span></label>
                            <select class="form-select" id="status" required>
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Nonaktif</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 px-4 pb-4 pt-2 gap-2">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success px-4 fw-semibold" onclick="saveCourse()" id="saveBtn">
                    <i class="bi bi-check-circle me-2"></i>Simpan
                </button>
            </div>
        </div>
    </div>
</div>


<?php $__env->startPush('scripts'); ?>
<script>
const modal = new bootstrap.Modal(document.getElementById('courseModal'));

function openModal() {
    document.getElementById('modalTitle').textContent = 'Tambah Mata Pelajaran';
    document.getElementById('courseId').value = '';
    document.getElementById('courseForm').reset();
    modal.show();
}

function editCourse(id) {
    document.getElementById('modalTitle').textContent = 'Edit Mata Pelajaran';
    document.getElementById('saveBtn').disabled = true;
    fetch(`/admin/courses/${id}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => { if (!r.ok) throw new Error(r.status); return r.json(); })
            .then(d => {
                document.getElementById('courseId').value = d.id;
                document.getElementById('kode').value = d.kode || '';
                document.getElementById('nama').value = d.nama || '';
                document.getElementById('cabang_id').value = d.cabang_id || '';
                document.getElementById('deskripsi').value = d.deskripsi || '';
                document.getElementById('status').value = d.status || 'aktif';
                document.getElementById('saveBtn').disabled = false;
                modal.show();
            }).catch(() => {
                document.getElementById('saveBtn').disabled = false;
                showToast('Gagal memuat data mata pelajaran', 'error');
            });
}


function saveCourse() {
    const id = document.getElementById('courseId').value;
    const isEdit = id !== '';
    const url  = isEdit ? `/admin/courses/${id}` : '/admin/courses';
    const btn = document.getElementById('saveBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan…';

    const formData = new FormData();
    formData.append('kode', document.getElementById('kode').value);
    formData.append('nama', document.getElementById('nama').value);
    formData.append('cabang_id', document.getElementById('cabang_id').value || '');
    formData.append('deskripsi', document.getElementById('deskripsi').value);
    formData.append('status', document.getElementById('status').value);
    formData.append('_token', '<?php echo e(csrf_token()); ?>');
    if (isEdit) formData.append('_method', 'PUT');

    fetch(url, {
        method: 'POST',
        body: formData,
    })
    .then(r => { if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
    .then(res => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-check-circle me-2"></i>Simpan';
        if (res.success) {
            modal.hide();
            window.showToast && window.showToast(res.message, 'success');
            setTimeout(() => location.reload(), 600);
        } else {
            window.showToast && window.showToast(res.message || 'Terjadi kesalahan.', 'error');
        }
    }).catch(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-check-circle me-2"></i>Simpan';
        window.showToast && window.showToast('Gagal menghubungi server.', 'error');
    });
}

function deleteCourse(id, nama) {
    confirmAction(`Hapus mata pelajaran "${nama}"? Data tidak dapat dikembalikan.`, function() {
        fetch(`/admin/courses/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => { if (!r.ok) throw new Error(r.status); return r.json(); })
        .then(res => {
            if (res.success) {
                window.showToast && window.showToast(res.message, 'success');
                setTimeout(() => location.reload(), 600);
            } else {
                window.showToast && window.showToast(res.message || 'Gagal menghapus', 'error');
            }
        })
        .catch(() => { window.showToast && window.showToast('Gagal menghapus mata pelajaran', 'error'); });
    }, null, {title:'Hapus Mata Pelajaran', okText:'Ya, Hapus'});
}

</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/admin/courses/index.blade.php ENDPATH**/ ?>