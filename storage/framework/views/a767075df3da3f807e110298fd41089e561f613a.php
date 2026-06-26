<?php $__env->startSection('title','Modul Belajar'); ?>
<?php $__env->startSection('page-title','Modul Belajar'); ?>

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
                    <h5 class="fw-bold mb-0" style="color:white">Kelola Modul Belajar</h5>
                    <span style="font-size:12px;opacity:.8">Upload dan kelola materi pembelajaran per mata pelajaran</span>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-md-end">
            <button onclick="openModal()" class="btn fw-semibold px-4" style="background:rgba(255,255,255,.2);color:white;border:1px solid rgba(255,255,255,.3);border-radius:10px">
                <i class="bi bi-plus-lg me-2"></i>Tambah Modul
            </button>
        </div>
    </div>
</div>


<div class="row g-3 mb-4" id="statsRow">
    <?php $__currentLoopData = [
        ['id'=>'statTotal','title'=>'Total Modul','icon'=>'bi-book','cls'=>'bg-info-soft','top'=>'#0284c7'],
        ['id'=>'statPdf','title'=>'PDF','icon'=>'bi-file-pdf','cls'=>'bg-danger-soft','top'=>'#ef4444'],
        ['id'=>'statVideo','title'=>'Video','icon'=>'bi-play-circle','cls'=>'bg-success-soft','top'=>'#10b981'],
        ['id'=>'statGratis','title'=>'Gratis','icon'=>'bi-gift','cls'=>'bg-warning-soft','top'=>'#f6af23']
    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="border-top:3px solid <?php echo e($s['top']); ?>">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title"><?php echo e($s['title']); ?></div>
                    <div class="stat-value" id="<?php echo e($s['id']); ?>">–</div>
                </div>
                <div class="stat-icon <?php echo e($s['cls']); ?>" style="color:white"><i class="bi <?php echo e($s['icon']); ?>"></i></div>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>


<div class="dashboard-card mb-4">
    <div class="row g-2 align-items-center">
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" id="searchInput" class="form-control" placeholder="Cari modul...">
            </div>
        </div>
        <div class="col-md-3">
            <select id="filterJenis" class="form-select">
                <option value="">Semua Jenis</option>
                <option value="pdf">PDF</option>
                <option value="video">Video</option>
                <option value="link">Link</option>
            </select>
        </div>
        <div class="col-md-3">
            <select id="filterMapel" class="form-select">
                <option value="">Semua Mata Pelajaran</option>
                <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($c->id); ?>"><?php echo e($c->nama); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-6 col-md-2">
            <button onclick="loadData()" class="btn btn-primary w-100"><i class="bi bi-funnel me-1"></i>Filter</button>
        </div>
        <div class="col-6 col-md-1">
            <button onclick="resetFilter()" class="btn btn-outline-secondary w-100" title="Reset filter" aria-label="Reset filter"><i class="bi bi-x-lg"></i></button>
        </div>
    </div>
</div>


<div class="dashboard-card">
    <div class="table-responsive">
        <table class="table table-hover table-modern align-middle mb-0">
            <thead class="thead-modern"><tr><th>Modul</th><th>Deskripsi</th><th>Mata Pelajaran</th><th>Jenis</th><th>Sumber</th><th>Akses</th><th>Status</th><th class="text-center">Aksi</th></tr></thead>
            <tbody id="tableBody">
                <?php for($i=0;$i<5;$i++): ?>
                <tr class="skeleton-row"><td><div class="d-flex align-items-center gap-2"><div class="skeleton-cell" style="width:36px;height:36px;border-radius:10px;flex-shrink:0"></div><div><div class="skeleton-cell mb-1" style="width:120px"></div><div class="skeleton-cell" style="width:80px;height:10px"></div></div></div></td><td><div class="skeleton-cell" style="width:120px"></div></td><td><div class="skeleton-cell" style="width:90px"></div></td><td><div class="skeleton-cell" style="width:55px"></div></td><td><div class="skeleton-cell" style="width:80px"></div></td><td><div class="skeleton-cell" style="width:50px"></div></td><td><div class="skeleton-cell" style="width:55px"></div></td><td><div class="skeleton-cell" style="width:80px;margin:0 auto"></div></td></tr>
                <?php endfor; ?>
            </tbody>
        </table>
    </div>
    <div id="paginationLinks" class="mt-3 d-flex justify-content-center"></div>
</div>

</div>


<div class="modal fade" id="moduleModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius:20px;border:none">
            <div class="modal-header border-0 p-4" style="background:linear-gradient(135deg,#260632,#461256,#c84ddf);border-radius:20px 20px 0 0">
                <h5 class="modal-title fw-bold text-white" id="modalTitle"><i class="bi bi-file-earmark-text me-2"></i>Tambah Modul</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="moduleForm" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <input type="hidden" name="moduleId" id="moduleId">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Judul Modul <span class="text-danger">*</span></label>
                            <input type="text" name="judul" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Jenis <span class="text-danger">*</span></label>
                            <select name="jenis" class="form-select" required id="jenisSelect">
                                <option value="pdf">PDF</option>
                                <option value="video">Video</option>
                                <option value="link">Link External</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Mata Pelajaran <span class="text-danger">*</span></label>
                            <select name="mata_pelajaran_id" class="form-select" required>
                                <option value="">Pilih Mata Pelajaran</option>
                                <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($c->id); ?>"><?php echo e($c->nama); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select">
                                <option value="aktif">Aktif</option>
                                <option value="draft">Draft</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Upload File PDF/Video</label>
                            <input type="file" name="file" id="fileInput" class="form-control" accept=".pdf,.mp4,.mov,.avi">
                            <div class="form-text">Maks 50 MB. Isi upload file atau link, pilih salah satu.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Link Modul</label>
                            <input type="url" name="file_url" id="fileUrlInput" class="form-control" placeholder="https://...">
                            <div class="form-text">Opsional jika tidak upload file.</div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="3" placeholder="Deskripsi singkat modul..."></textarea>
                        </div>
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_gratis" id="isGratis" value="1">
                                <label class="form-check-label" for="isGratis">Modul Gratis (dapat diakses semua siswa)</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4" id="submitBtn"><i class="bi bi-floppy me-2"></i>Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
let currentPage = 1;

function loadData(page = 1) {
    currentPage = page;
    const params = new URLSearchParams({
        page,
        search: document.getElementById('searchInput').value,
        jenis: document.getElementById('filterJenis').value,
        mata_pelajaran_id: document.getElementById('filterMapel').value,
    });
    fetch(`<?php echo e(route('admin.modules.index')); ?>?${params}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => { if (!r.ok) throw new Error('HTTP '+r.status); return r.json(); })
        .then(data => {
            countUpValue(document.getElementById('statTotal'),  data.stats.total);
            countUpValue(document.getElementById('statPdf'),    data.stats.pdf);
            countUpValue(document.getElementById('statVideo'),  data.stats.video);
            countUpValue(document.getElementById('statGratis'), data.stats.gratis);
            renderTable(data.data);
            renderPagination(data);
        })
        .catch(() => {
            document.getElementById('tableBody').innerHTML = `<tr><td colspan="8" class="text-center py-5"><i class="bi bi-wifi-off" style="font-size:2rem;color:#ef4444;display:block;margin-bottom:10px"></i><div class="fw-semibold mb-2">Gagal memuat data</div><button onclick="loadData(${page})" class="btn btn-sm btn-outline-primary"><i class="bi bi-arrow-clockwise me-1"></i>Coba lagi</button></td></tr>`;
        });
}

function renderTable(rows) {
    const jenisMap = { pdf:'<span class="badge" style="background:#ef444422;color:#ef4444">PDF</span>', video:'<span class="badge" style="background:#10b98122;color:#10b981">Video</span>', link:'<span class="badge" style="background:#f6af2322;color:#b45309">Link</span>' };
    if (!rows.length) {
        document.getElementById('tableBody').innerHTML = '<tr><td colspan="8" class="text-center py-5 text-muted"><i class="bi bi-book" style="font-size:2rem;display:block;margin-bottom:8px"></i>Belum ada modul</td></tr>';
        return;
    }
    document.getElementById('tableBody').innerHTML = rows.map(m => {
        const sourceUrl = m.file_path ? `/storage/${m.file_path}` : m.file_url;
        const sourceLabel = m.file_path
            ? `${m.ukuran_file ? (m.ukuran_file / 1024 / 1024).toFixed(1) + ' MB' : 'File'}`
            : (m.file_url || '–');
        return `
        <tr>
            <td><div class="fw-semibold">${m.judul}</div></td>
            <td><span class="text-muted" style="font-size:12px;max-width:220px;display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">${m.deskripsi || '–'}</span></td>
            <td><span class="badge bg-primary-subtle text-primary">${m.mata_pelajaran?.nama || '-'}</span></td>
            <td>${jenisMap[m.jenis] || m.jenis}</td>
            <td>${sourceUrl ? `<a href="${sourceUrl}" target="_blank" class="text-decoration-none" style="font-size:12px;max-width:180px;display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis"><i class="bi bi-box-arrow-up-right me-1"></i>${sourceLabel}</a>` : '<span class="text-muted">–</span>'}</td>
            <td>${m.is_gratis ? '<span class="badge bg-success-subtle text-success">Gratis</span>' : '<span class="badge bg-secondary-subtle text-secondary">Berbayar</span>'}</td>
            <td>${m.status === 'aktif' ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-warning text-dark">Draft</span>'}</td>
            <td>
                <div class="d-flex gap-1">
                    ${sourceUrl ? `<a href="${sourceUrl}" target="_blank" class="btn btn-sm btn-outline-info" title="Lihat"><i class="bi bi-eye"></i></a>` : ''}
                    <button onclick="editModule(${m.id})" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></button>
                    <button onclick="deleteModule(${m.id},'${String(m.judul).replace(/'/g, "\\'")}')" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                </div>
            </td>
        </tr>`;
    }).join('');
}

function renderPagination(data) {
    const el = document.getElementById('paginationLinks');
    if (data.last_page <= 1) { el.innerHTML = ''; return; }
    let html = `<nav><ul class="pagination pagination-sm mb-0">`;
    html += `<li class="page-item ${data.current_page==1?'disabled':''}"><a class="page-link" href="#" onclick="loadData(${data.current_page-1});return false">‹</a></li>`;
    for (let i = 1; i <= data.last_page; i++) {
        html += `<li class="page-item ${i==data.current_page?'active':''}"><a class="page-link" href="#" onclick="loadData(${i});return false">${i}</a></li>`;
    }
    html += `<li class="page-item ${data.current_page==data.last_page?'disabled':''}"><a class="page-link" href="#" onclick="loadData(${data.current_page+1});return false">›</a></li>`;
    html += `</ul></nav>`;
    el.innerHTML = html;
}

function openModal(reset = true) {
    if (reset) {
        document.getElementById('moduleForm').reset();
        document.getElementById('moduleId').value = '';
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('modalTitle').textContent = 'Tambah Modul';
    }
    new bootstrap.Modal(document.getElementById('moduleModal')).show();
}

function editModule(id) {
    fetch(`<?php echo e(url('admin/modules')); ?>/${id}`, { headers:{'X-Requested-With':'XMLHttpRequest'} })
        .then(r=>{ if(!r.ok) throw new Error('HTTP '+r.status); return r.json(); })
        .then(res => {
            const m = res.data;
            const f = document.getElementById('moduleForm');
            f.querySelector('[name=judul]').value = m.judul || '';
            f.querySelector('[name=jenis]').value = m.jenis || 'pdf';
            f.querySelector('[name=mata_pelajaran_id]').value = m.mata_pelajaran_id || '';
            f.querySelector('[name=status]').value = m.status || 'aktif';
            f.querySelector('[name=file_url]').value = m.file_url || '';
            f.querySelector('[name=file]').value = '';
            f.querySelector('[name=deskripsi]').value = m.deskripsi || '';
            f.querySelector('[name=is_gratis]').checked = !!m.is_gratis;
            document.getElementById('moduleId').value = id;
            document.getElementById('formMethod').value = 'PUT';
            document.getElementById('modalTitle').textContent = 'Edit Modul';
            openModal(false);
        }).catch(()=>showToast('Gagal memuat data modul.', 'error'));
}

function deleteModule(id, name) {
    confirmAction(`Hapus modul "${name}"? Data tidak dapat dikembalikan.`, function() {
        fetch(`<?php echo e(url('admin/modules')); ?>/${id}`, { method:'DELETE', headers:{'X-CSRF-TOKEN':'<?php echo e(csrf_token()); ?>','X-Requested-With':'XMLHttpRequest'} })
            .then(r=>{ if(!r.ok) throw new Error('HTTP '+r.status); return r.json(); })
            .then(d => { showToast(d.message, d.success?'success':'error'); if(d.success) loadData(currentPage); })
            .catch(()=>showToast('Gagal menghubungi server.', 'error'));
    }, null, {title:'Hapus Modul', okText:'Ya, Hapus'});
}



document.getElementById('moduleForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const id = document.getElementById('moduleId').value;
    const method = id ? 'PUT' : 'POST';
    const url = id ? `<?php echo e(url('admin/modules')); ?>/${id}` : `<?php echo e(route('admin.modules.store')); ?>`;
    const fd = new FormData(this);
    if (id) fd.append('_method', 'PUT');
    document.getElementById('submitBtn').disabled = true;
    fetch(url, { method:'POST', body:fd, headers:{'X-CSRF-TOKEN':'<?php echo e(csrf_token()); ?>','X-Requested-With':'XMLHttpRequest'} })
        .then(r=>{ if(!r.ok) throw new Error('HTTP '+r.status); return r.json(); })
        .then(d => {
            document.getElementById('submitBtn').disabled = false;
            showToast(d.message, d.success?'success':'error');
            if (d.success) { bootstrap.Modal.getInstance(document.getElementById('moduleModal')).hide(); loadData(currentPage); }
        }).catch(()=>{ document.getElementById('submitBtn').disabled = false; showToast('Gagal menyimpan. Coba lagi.', 'error'); });
});

function resetFilter() {
    document.getElementById('searchInput').value = '';
    document.getElementById('filterJenis').value = '';
    document.getElementById('filterMapel').value = '';
    loadData(1);
}

let st; document.getElementById('searchInput').addEventListener('input', () => { clearTimeout(st); st = setTimeout(()=>loadData(1), 400); });
document.addEventListener('DOMContentLoaded', () => loadData());
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/admin/modules/index.blade.php ENDPATH**/ ?>