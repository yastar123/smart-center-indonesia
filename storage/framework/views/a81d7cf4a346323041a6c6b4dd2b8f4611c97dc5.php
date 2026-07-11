<?php $__env->startSection('title', 'Manajemen Kelas'); ?>
<?php $__env->startSection('page-title', 'Kelas'); ?>

<?php $__env->startSection('content'); ?>
<div>

    
    <div class="dashboard-card mb-4 fade-up" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
        <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
        <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
        <div class="row align-items-center g-3" style="position:relative">
            <div class="col-md-8">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0">
                        <i class="bi bi-building-fill"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0" style="color:white">Manajemen Kelas</h5>
                        <span style="font-size:12px;opacity:.8">Atur kelas belajar, kapasitas, dan penugasan guru di semua cabang</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-md-end">
                <button onclick="openModal()" class="btn fw-semibold px-4" style="background:rgba(255,255,255,.2);color:white;border:1px solid rgba(255,255,255,.3);border-radius:10px;backdrop-filter:blur(10px)">
                    <i class="bi bi-plus-lg me-2"></i>Tambah Kelas
                </button>
            </div>
        </div>
    </div>

    
    <div class="row g-3 mb-4">
        <?php
            $statCards = [
                ['label'=>'Total Kelas',  'value'=>$stats['total'],  'icon'=>'bi-building',      'color'=>'text-primary', 'topColor'=>'#c84ddf', 'iconBg'=>'bg-primary-soft'],
                ['label'=>'Kelas Aktif',  'value'=>$stats['aktif'],  'icon'=>'bi-check-circle',  'color'=>'text-success', 'topColor'=>'#10b981', 'iconBg'=>'bg-success-soft'],
                ['label'=>'Kelas Online', 'value'=>$stats['online'], 'icon'=>'bi-wifi',           'color'=>'text-primary', 'topColor'=>'#c84ddf', 'iconBg'=>'bg-primary-soft'],
                ['label'=>'Kelas Offline','value'=>$stats['offline'],'icon'=>'bi-geo-alt-fill',  'color'=>'text-warning', 'topColor'=>'#f6af23', 'iconBg'=>'bg-warning-soft'],
            ];
        ?>
        <?php $__currentLoopData = $statCards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $sc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-6 col-lg-3 fade-up" style="animation-delay:<?php echo e($i * 0.05); ?>s">
            <div class="stat-card" style="border-top:3px solid <?php echo e($sc['topColor']); ?>">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-title"><?php echo e($sc['label']); ?></div>
                        <div class="stat-value <?php echo e($sc['color']); ?> count-up" data-target="<?php echo e($sc['value']); ?>"><?php echo e($sc['value']); ?></div>
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
                <div class="col-12 col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Cari nama kelas…" value="<?php echo e(request('search')); ?>">
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <select name="jenis" class="form-select">
                        <option value="">Semua Jenis</option>
                        <option value="online"  <?php echo e(request('jenis')=='online'?'selected':''); ?>>Online</option>
                        <option value="offline" <?php echo e(request('jenis')=='offline'?'selected':''); ?>>Offline</option>
                        <option value="private"  <?php echo e(request('jenis')=='private'?'selected':''); ?>>Private</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="aktif"    <?php echo e(request('status')=='aktif'?'selected':''); ?>>Aktif</option>
                        <option value="nonaktif" <?php echo e(request('status')=='nonaktif'?'selected':''); ?>>Nonaktif</option>
                        <option value="penuh"    <?php echo e(request('status')=='penuh'?'selected':''); ?>>Penuh</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select name="cabang_id" class="form-select">
                        <option value="">Semua Cabang</option>
                        <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($b->id); ?>" <?php echo e(request('cabang_id')==$b->id?'selected':''); ?>><?php echo e($b->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-6 col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">Filter</button>
                    <?php if(request()->hasAny(['search','jenis','status','cabang_id'])): ?>
                        <a href="<?php echo e(route('admin.classes.index')); ?>" class="btn btn-outline-secondary"><i class="bi bi-x"></i></a>
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
                            <th>Nama Kelas</th>
                            <th class="d-none d-md-table-cell">Mata Pelajaran</th>
                            <th class="d-none d-md-table-cell">Guru</th>
                            <th class="d-none d-lg-table-cell">Pertemuan</th>
                            <th>Jenis</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="px-4 text-muted" style="font-size:.85rem;"><?php echo e($classes->firstItem() + $i); ?></td>
                            <td>
                                <div class="fw-semibold" style="font-size:.9rem;"><?php echo e($class->nama_kelas); ?></div>
                                <div class="text-muted" style="font-size:.78rem;"><?php echo e($class->cabang?->name ?? 'Pusat'); ?></div>
                            </td>
                            <td class="d-none d-md-table-cell" style="font-size:.85rem;">
                                <?php echo e($class->mataPelajaran?->nama ?? '—'); ?>

                            </td>
                            <td class="d-none d-md-table-cell" style="font-size:.85rem;">
                                <?php echo e($class->guru?->name ?? '—'); ?>

                            </td>
                            <td class="d-none d-lg-table-cell">
                                <?php if($class->jumlah_pertemuan): ?>
                                <span style="font-size:.85rem;font-weight:600;"><?php echo e($class->jumlah_pertemuan); ?></span>
                                <small class="text-muted"> sesi</small>
                                <?php else: ?>
                                <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php $jeниsBadge = ['online'=>['#c84ddf','rgba(200,77,223,.15)'],'offline'=>['#f6af23','rgba(245,158,11,.15)'],'private'=>['#68117e','rgba(104,17,126,.15)']] ?>
                                <?php $j = $jeниsBadge[$class->jenis] ?? ['#6b7280','rgba(107,114,128,.15)'] ?>
                                <span class="badge rounded-pill" style="background:<?php echo e($j[1]); ?>;color:<?php echo e($j[0]); ?>;font-size:.75rem;font-weight:600;padding:.35em .75em;text-transform:capitalize;"><?php echo e($class->jenis); ?></span>
                            </td>
                            <td>
                                <?php $sb = ['aktif'=>['#10b981','rgba(16,185,129,.15)'],'nonaktif'=>['#ef4444','rgba(239,68,68,.15)'],'penuh'=>['#f6af23','rgba(245,158,11,.15)']] ?>
                                <?php $s = $sb[$class->status] ?? ['#6b7280','rgba(107,114,128,.15)'] ?>
                                <span class="badge rounded-pill" style="background:<?php echo e($s[1]); ?>;color:<?php echo e($s[0]); ?>;font-size:.75rem;font-weight:600;padding:.35em .75em;text-transform:capitalize;"><?php echo e($class->status); ?></span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex gap-1 justify-content-end">
                                    <button class="btn btn-sm btn-act-edit" onclick="editClass(<?php echo e($class->id); ?>)">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-act-del" onclick="deleteClass(<?php echo e($class->id); ?>, '<?php echo e(addslashes($class->nama_kelas)); ?>')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div style="opacity:.5;">
                                    <i class="bi bi-building text-primary" style="font-size:2.5rem;display:block;margin-bottom:.5rem"></i>
                                    <div class="fw-semibold">Belum ada kelas</div>
                                    <small class="text-muted">Tambahkan kelas pertama Anda</small>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php if($classes->hasPages()): ?>
            <div class="mt-4 pt-3 d-flex justify-content-center" style="border-top:1px solid var(--card-border)"><?php echo e($classes->links()); ?></div>
            <?php endif; ?>
    </div>
</div>


<div class="modal fade" id="classModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0" style="border-radius:20px;box-shadow:0 20px 60px rgba(0,0,0,.15);">
            <div class="modal-header border-0 p-4" style="background:linear-gradient(135deg,#68117e,#c84ddf);border-radius:20px 20px 0 0">
                <div>
                    <h5 class="modal-title fw-bold mb-0 text-white" id="classModalTitle"><i class="bi bi-building me-2"></i>Tambah Kelas</h5>
                    <div style="font-size:12px;opacity:.75;color:white;margin-top:3px">Isi data kelas belajar di bawah ini</div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4 py-3">
                <form id="classForm">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" id="classId">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Cabang <span class="text-danger">*</span></label>
                            <select class="form-select" id="cls_cabang_id" required>
                                <option value="">-- Pilih Cabang --</option>
                                <option value="pusat">Pusat</option>
                                <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($b->id); ?>"><?php echo e($b->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Guru Pengajar</label>
                            <select class="form-select" id="guru_id" onchange="loadTeacherCourses()">
                                <option value="">-- Pilih Guru --</option>
                                <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($t->id); ?>"><?php echo e($t->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Mata Pelajaran</label>
                            <select class="form-select" id="mata_pelajaran_id">
                                <option value="">-- Pilih Guru Terlebih Dahulu --</option>
                            </select>
                            <small class="text-muted" style="font-size:11px">Mata pelajaran akan muncul berdasarkan guru yang dipilih</small>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Kapasitas</label>
                            <input type="number" class="form-control" id="kapasitas" placeholder="30" min="1" max="200">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Jumlah Pertemuan <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="jumlah_pertemuan" placeholder="cth: 12" min="1" max="200" required>
                            <div class="form-text">Berapa sesi/pertemuan kelas ini berlangsung.</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Jenis <span class="text-danger">*</span></label>
                            <select class="form-select" id="jenis" required onchange="toggleZoom()">
                                <option value="offline">Offline</option>
                                <option value="online">Online</option>
                                <option value="private">Private</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Status <span class="text-danger">*</span></label>
                            <select class="form-select" id="cls_status" required>
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Nonaktif</option>
                                <option value="penuh">Penuh</option>
                            </select>
                        </div>
                        <div class="col-md-6" id="zoomField" style="display:none;">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Link Zoom</label>
                            <input type="url" class="form-control" id="link_zoom" placeholder="https://zoom.us/j/...">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 px-4 pb-4 pt-2 gap-2">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn px-4 fw-semibold" onclick="saveClass()" id="classSaveBtn" style="background:linear-gradient(135deg,#68117e,#461256);color:#fff;">
                    <i class="bi bi-check-circle me-2"></i>Simpan
                </button>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
const classModal = new bootstrap.Modal(document.getElementById('classModal'));

function toggleZoom() {
    const jenis = document.getElementById('jenis').value;
    document.getElementById('zoomField').style.display = jenis === 'online' ? 'block' : 'none';
}

function loadTeacherCourses() {
    const guruId = document.getElementById('guru_id').value;
    const courseSelect = document.getElementById('mata_pelajaran_id');
    
    // Reset course dropdown
    courseSelect.innerHTML = '<option value="">-- Pilih Mata Pelajaran --</option>';
    
    if (!guruId) {
        courseSelect.innerHTML = '<option value="">-- Pilih Guru Terlebih Dahulu --</option>';
        return Promise.resolve();
    }
    
    // Fetch courses for selected teacher
    return fetch(`/admin/teachers/${guruId}/courses`)
        .then(r => r.json())
        .then(data => {
            if (data.courses && data.courses.length > 0) {
                data.courses.forEach(course => {
                    const option = document.createElement('option');
                    option.value = course.id;
                    option.textContent = `${course.kode} — ${course.nama}`;
                    courseSelect.appendChild(option);
                });
            } else {
                const option = document.createElement('option');
                option.value = '';
                option.textContent = '-- Tidak ada mata pelajaran untuk guru ini --';
                courseSelect.appendChild(option);
            }
        })
        .catch(err => {
            console.error('Error loading teacher courses:', err);
            courseSelect.innerHTML = '<option value="">-- Gagal memuat mata pelajaran --</option>';
        });
}

function openModal() {
    document.getElementById('classModalTitle').textContent = 'Tambah Kelas';
    document.getElementById('classId').value = '';
    document.getElementById('classForm').reset();
    document.getElementById('mata_pelajaran_id').innerHTML = '<option value="">-- Pilih Guru Terlebih Dahulu --</option>';
    toggleZoom();
    classModal.show();
}

function editClass(id) {
    document.getElementById('classModalTitle').textContent = 'Edit Kelas';
    document.getElementById('classSaveBtn').disabled = true;
    fetch(`/admin/classes/${id}`)
        .then(r => { if (!r.ok) throw new Error('HTTP '+r.status); return r.json(); })
        .then(d => {
            document.getElementById('classId').value = d.id;
            const cabangVal = (d.cabang_id === null) ? 'pusat' : (d.cabang_id || '');
            document.getElementById('cls_cabang_id').value = cabangVal;
            document.getElementById('guru_id').value = d.guru_id || '';
            document.getElementById('kapasitas').value = d.kapasitas || '';
            document.getElementById('jumlah_pertemuan').value = d.jumlah_pertemuan || '';
            document.getElementById('jenis').value = d.jenis || 'offline';
            document.getElementById('cls_status').value = d.status || 'aktif';
            document.getElementById('link_zoom').value = d.link_zoom || '';
            toggleZoom();
            
            // Load courses for the selected teacher
            if (d.guru_id) {
                loadTeacherCourses().then(() => {
                    document.getElementById('mata_pelajaran_id').value = d.mata_pelajaran_id || '';
                });
            } else {
                document.getElementById('mata_pelajaran_id').innerHTML = '<option value="">-- Pilih Guru Terlebih Dahulu --</option>';
            }
            
            document.getElementById('classSaveBtn').disabled = false;
            classModal.show();
        }).catch(() => {
            document.getElementById('classSaveBtn').disabled = false;
            window.showToast && window.showToast('Gagal memuat data kelas.', 'error');
        });
}

function saveClass() {
    const id = document.getElementById('classId').value;
    const isEdit = id !== '';
    const btn = document.getElementById('classSaveBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan…';

    fetch(isEdit ? `/admin/classes/${id}` : '/admin/classes', {
        method: isEdit ? 'PUT' : 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>', 'Accept': 'application/json' },
        body: JSON.stringify({
            cabang_id:         document.getElementById('cls_cabang_id').value,
            mata_pelajaran_id: document.getElementById('mata_pelajaran_id').value || null,
            guru_id:           document.getElementById('guru_id').value || null,
            kapasitas:         document.getElementById('kapasitas').value || null,
            jumlah_pertemuan:  document.getElementById('jumlah_pertemuan').value || null,
            jenis:             document.getElementById('jenis').value,
            status:            document.getElementById('cls_status').value,
            link_zoom:         document.getElementById('link_zoom').value || null,
        })
    })
    .then(r => { if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
    .then(res => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-check-circle me-2"></i>Simpan';
        if (res.success) {
            classModal.hide();
            window.showToast && window.showToast(res.message, 'success');
            setTimeout(() => location.reload(), 600);
        } else {
            const errs = res.errors ? Object.values(res.errors).flat().join(' | ') : res.message;
            window.showToast && window.showToast(errs || 'Terjadi kesalahan.', 'error');
        }
    })
    .catch(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-check-circle me-2"></i>Simpan';
        window.showToast && window.showToast('Gagal menghubungi server.', 'error');
    });
}

function deleteClass(id, nama) {
    confirmAction(`Hapus kelas "${nama}"? Data tidak dapat dikembalikan.`, function() {
        fetch(`/admin/classes/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>', 'Accept': 'application/json' }
        })
        .then(r => { if (!r.ok) throw new Error('HTTP '+r.status); return r.json(); })
        .then(res => {
            if (res.success) {
                window.showToast && window.showToast(res.message, 'success');
                setTimeout(() => location.reload(), 600);
            } else {
                window.showToast && window.showToast(res.message || 'Gagal menghapus kelas.', 'error');
            }
        }).catch(() => {
            window.showToast && window.showToast('Gagal menghubungi server.', 'error');
        });
    });
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/admin/classes/index.blade.php ENDPATH**/ ?>