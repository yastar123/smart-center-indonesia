<?php $__env->startSection('title','Gaji Guru'); ?>
<?php $__env->startSection('page-title','Manajemen Gaji Guru'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-4 col-lg-3">
        <div class="dashboard-card mb-4">
            <h6 class="fw-bold mb-2">Daftar Guru</h6>
            <div class="list-group" id="teacherList" style="max-height:70vh;overflow:auto">
                <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <button type="button" class="list-group-item list-group-item-action d-flex align-items-start justify-content-between" onclick="openTeacher(<?php echo e($t->id); ?>)">
                    <div>
                        <div class="fw-semibold"><?php echo e($t->name); ?></div>
                        <div class="text-muted small"><?php echo e($t->subjects ? (is_array($t->subjects) ? implode(', ', $t->subjects) : $t->subjects) : ($t->courses->pluck('nama')->implode(', ') ?? '-')); ?></div>
                    </div>
                    <span class="badge bg-secondary align-self-center"><?php echo e($t->branch?->name ?? 'Pusat'); ?></span>
                </button>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
    <div class="col-md-8 col-lg-9">


<div class="dashboard-card mb-4 fade-up" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
    <div class="row align-items-center g-3" style="position:relative">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-3 mb-2">
                <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0"><i class="bi bi-cash-stack"></i></div>
                <div>
                    <h5 class="fw-bold mb-0" style="color:white">Manajemen Gaji Guru</h5>
                    <span style="font-size:12px;opacity:.8">Kelola pembayaran gaji, bonus, dan cetak slip gaji</span>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="<?php echo e(route('admin.salaries.create')); ?>" class="btn fw-semibold px-4" style="background:rgba(255,255,255,.2);color:white;border:1px solid rgba(255,255,255,.3);border-radius:10px">
                <i class="bi bi-plus-lg me-2"></i>Input Gaji
            </a>
        </div>
    </div>
</div>


<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3"><div class="stat-card" style="border-top:3px solid #c84ddf"><div class="d-flex justify-content-between"><div><div class="stat-title">Total Record</div><div class="stat-value" id="statTotal">–</div></div><div class="stat-icon bg-primary-soft" style="color:white"><i class="bi bi-receipt"></i></div></div></div></div>
    <div class="col-6 col-lg-3"><div class="stat-card" style="border-top:3px solid #10b981"><div class="d-flex justify-content-between"><div><div class="stat-title">Sudah Dibayar</div><div class="stat-value" id="statDibayar">–</div></div><div class="stat-icon bg-success-soft" style="color:white"><i class="bi bi-check-circle"></i></div></div></div></div>
    <div class="col-6 col-lg-3"><div class="stat-card" style="border-top:3px solid #f6af23"><div class="d-flex justify-content-between"><div><div class="stat-title">Pending</div><div class="stat-value" id="statPending">–</div></div><div class="stat-icon bg-warning-soft" style="color:white"><i class="bi bi-clock"></i></div></div></div></div>
    <div class="col-6 col-lg-3"><div class="stat-card" style="border-top:3px solid #10b981"><div class="d-flex justify-content-between"><div><div class="stat-title">Total Dibayarkan</div><div class="stat-value text-success" id="statNominal" style="font-size:16px">–</div></div><div class="stat-icon bg-success-soft" style="color:white"><i class="bi bi-cash"></i></div></div></div></div>
</div>


<div class="dashboard-card mb-4">
    <div class="row g-2">
        <div class="col-12 col-md-3"><div class="input-group"><span class="input-group-text"><i class="bi bi-search"></i></span><input type="text" id="searchInput" class="form-control" placeholder="Cari nama guru..."></div></div>
        <div class="col-6 col-md-2"><select id="filterStatus" class="form-select"><option value="">Semua Status</option><option value="dibayar">Dibayar</option><option value="pending">Pending</option><option value="batal">Batal</option></select></div>
        <div class="col-6 col-md-2"><input type="month" id="filterPeriode" class="form-control"></div>
        <div class="col-6 col-md-2"><select id="filterCabang" class="form-select"><option value="">Semua Cabang</option><?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($b->id); ?>"><?php echo e($b->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
        <div class="col-4 col-md-2"><button onclick="loadData()" class="btn btn-primary w-100"><i class="bi bi-funnel me-1"></i>Filter</button></div>
        <div class="col-2 col-md-1"><button onclick="resetFilter()" class="btn btn-outline-secondary w-100" title="Reset filter" aria-label="Reset filter"><i class="bi bi-x-lg"></i></button></div>
    </div>
</div>


<div class="dashboard-card">
    <div class="table-responsive">
        <table class="table table-hover table-modern align-middle mb-0">
            <thead class="thead-modern"><tr><th>Guru</th><th>Periode</th><th>Tipe</th><th>Gaji Pokok</th><th>Total Gaji</th><th>Status</th><th class="text-center">Aksi</th></tr></thead>
            <tbody id="tableBody">
                <?php for($i=0;$i<5;$i++): ?>
                <tr class="skeleton-row"><td><div class="skeleton-cell" style="width:75%"></div></td><td><div class="skeleton-cell" style="width:70px"></div></td><td><div class="skeleton-cell" style="width:60px"></div></td><td><div class="skeleton-cell" style="width:80px"></div></td><td><div class="skeleton-cell" style="width:90px"></div></td><td><div class="skeleton-cell" style="width:60px"></div></td><td><div class="skeleton-cell" style="width:90px;margin:0 auto"></div></td></tr>
                <?php endfor; ?>
            </tbody>
        </table>
    </div>
    <div id="paginationLinks" class="mt-3 d-flex justify-content-center"></div>
</div>
</div>
</div>


<div class="modal fade" id="salaryModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius:20px;border:none">
            <div class="modal-header border-0 p-4" style="background:linear-gradient(135deg,#260632,#461256,#c84ddf);border-radius:20px 20px 0 0">
                <h5 class="modal-title fw-bold text-white" id="modalTitle"><i class="bi bi-cash-stack me-2"></i>Input Gaji Guru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="salaryForm" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <input type="hidden" id="salId">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Guru <span class="text-danger">*</span></label>
                            <select name="guru_id" class="form-select" required><option value="">Pilih Guru</option><?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($t->id); ?>"><?php echo e($t->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Periode <span class="text-danger">*</span></label>
                            <input type="month" name="periode" class="form-control" required value="<?php echo e(date('Y-m')); ?>">
                        </div>
                        <div class="col-md-4"><label class="form-label fw-semibold">Gaji Pokok (Rp)</label><input type="number" name="gaji_pokok" class="form-control" value="0" min="0" required></div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Tipe Gaji</label>
                            <select name="tipe_gaji" class="form-select">
                                <option value="bulanan">Gaji Bulanan</option>
                                <option value="freelance">Gaji Freelance</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select"><option value="pending">Pending</option><option value="dibayar">Dibayar</option><option value="batal">Batal</option></select>
                        </div>
                        <div class="col-md-6"><label class="form-label fw-semibold">Metode Pembayaran</label><select name="metode_pembayaran" class="form-select"><option value="">Pilih</option><option>Transfer Bank</option><option>Tunai</option><option>E-Wallet</option></select></div>
                        <div class="col-md-6"><label class="form-label fw-semibold">Tanggal Pembayaran</label><input type="date" name="tanggal_pembayaran" class="form-control"></div>
                        <div class="col-md-6"><label class="form-label fw-semibold">Nama Bank</label><input type="text" name="nama_bank" class="form-control" placeholder="BCA, Mandiri, dll"></div>
                        <div class="col-md-6"><label class="form-label fw-semibold">Nomor Rekening</label><input type="text" name="nomor_rekening" class="form-control"></div>
                        <!-- Catatan dihilangkan sesuai permintaan -->
                        <div class="col-12">
                            <label class="form-label fw-semibold">Bukti Pembayaran (jpg,png,pdf)</label>
                            <input type="file" name="bukti_pembayaran" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                            <div class="form-text">Opsional. Unggah bukti pembayaran yang dapat diunduh oleh guru.</div>
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


<div class="modal fade" id="teacherModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius:14px;border:none">
            <div class="modal-header border-0 p-3">
                <h5 class="modal-title fw-bold" id="teacherModalTitle"><i class="bi bi-person-badge me-2"></i>Detail Guru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="teacherModalBody">
                <!-- filled by JS -->
            </div>
            <div class="modal-footer border-0 p-3">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="openUploadBtn">Upload Bukti Pembayaran</button>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
let currentPage = 1;

function loadData(page=1, guru_id=null) {
    currentPage = page;
    const params = new URLSearchParams();
    params.append('page', page);
    const search = document.getElementById('searchInput').value;
    if (search) params.append('search', search);
    const status = document.getElementById('filterStatus').value;
    if (status) params.append('status', status);
    const periode = document.getElementById('filterPeriode').value;
    if (periode) params.append('periode', periode);
    const cabang = document.getElementById('filterCabang').value;
    if (cabang) params.append('cabang_id', cabang);
    if (guru_id) params.append('guru_id', guru_id);
    fetch(`<?php echo e(route('admin.salaries.index')); ?>?${params.toString()}`, { headers:{'X-Requested-With':'XMLHttpRequest'} })
        .then(r => { if (!r.ok) throw new Error('HTTP '+r.status); return r.json(); })
        .then(data => {
            countUpValue(document.getElementById('statTotal'),   data.stats.total);
            countUpValue(document.getElementById('statDibayar'), data.stats.dibayar);
            countUpValue(document.getElementById('statPending'), data.stats.pending);
            document.getElementById('statNominal').textContent = 'Rp ' + parseInt(data.stats.total_nominal||0).toLocaleString('id-ID');
            renderTable(data.data);
            renderPagination(data);
        })
        .catch(() => {
            document.getElementById('tableBody').innerHTML = `<tr><td colspan="8" class="text-center py-5"><i class="bi bi-wifi-off" style="font-size:2rem;color:#ef4444;display:block;margin-bottom:10px"></i><div class="fw-semibold mb-2">Gagal memuat data</div><button onclick="loadData(${page})" class="btn btn-sm btn-outline-primary"><i class="bi bi-arrow-clockwise me-1"></i>Coba lagi</button></td></tr>`;
        });
}

function renderTable(rows) {
    const statusMap = { dibayar:'<span class="badge bg-success">Dibayar</span>', pending:'<span class="badge bg-warning text-dark">Pending</span>', batal:'<span class="badge bg-danger">Batal</span>' };
    if (!rows.length) { document.getElementById('tableBody').innerHTML = '<tr><td colspan="7" class="text-center py-5 text-muted">Belum ada data gaji</td></tr>'; return; }
    document.getElementById('tableBody').innerHTML = rows.map(s => {
        const tipe = s.tipe_gaji === 'freelance' ? 'Freelance' : 'Bulanan';
        return `
        <tr>
            <td><div class="fw-semibold">${s.guru?.name||'-'}</div></td>
            <td>${s.periode}</td>
            <td>${tipe}</td>
            <td>Rp ${parseInt(s.gaji_pokok||0).toLocaleString('id-ID')}</td>
            <td class="fw-bold text-success">Rp ${parseInt(s.total_gaji||0).toLocaleString('id-ID')}</td>
            <td>${statusMap[s.status]||s.status}</td>
            <td><div class="d-flex gap-1">
                <a href="<?php echo e(url('admin/salaries')); ?>/${s.id}/slip" target="_blank" class="btn btn-sm btn-outline-primary" title="Cetak Slip"><i class="bi bi-printer"></i></a>
                <button onclick="editSalary(${s.id})" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></button>
                <button onclick="deleteSalary(${s.id})" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
            </div></td>
        </tr>`;
    }).join('');
}

function renderPagination(data) {
    const el = document.getElementById('paginationLinks');
    if (data.last_page <= 1) { el.innerHTML=''; return; }
    let h = '<nav><ul class="pagination pagination-sm mb-0">';
    h += `<li class="page-item ${data.current_page==1?'disabled':''}"><a class="page-link" href="#" onclick="loadData(${data.current_page-1});return false">‹</a></li>`;
    for(let i=1;i<=data.last_page;i++) h += `<li class="page-item ${i==data.current_page?'active':''}"><a class="page-link" href="#" onclick="loadData(${i});return false">${i}</a></li>`;
    h += `<li class="page-item ${data.current_page==data.last_page?'disabled':''}"><a class="page-link" href="#" onclick="loadData(${data.current_page+1});return false">›</a></li></ul></nav>`;
    el.innerHTML = h;
}

function openModal(reset=true) {
    if(reset){ document.getElementById('salaryForm').reset(); document.getElementById('salId').value=''; document.getElementById('modalTitle').textContent='Input Gaji Guru'; }
    new bootstrap.Modal(document.getElementById('salaryModal')).show();
}

function editSalary(id) {
    window.location.href = '<?php echo e(url('admin/salaries')); ?>/' + id + '/edit';
}

function deleteSalary(id) {
    confirmAction('Hapus data gaji ini? Data tidak dapat dikembalikan.', function() {
        fetch(`<?php echo e(url('admin/salaries')); ?>/${id}`, {method:'DELETE',headers:{'X-CSRF-TOKEN':'<?php echo e(csrf_token()); ?>','X-Requested-With':'XMLHttpRequest'}})
            .then(r=>{ if(!r.ok) throw new Error('HTTP '+r.status); return r.json(); })
            .then(d => { showToast(d.message, d.success?'success':'error'); if(d.success) loadData(currentPage); })
            .catch(()=>showToast('Gagal menghubungi server.', 'error'));
    }, null, {title:'Hapus Data Gaji', okText:'Ya, Hapus'});
}

document.getElementById('salaryForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const id = document.getElementById('salId').value;
    const url = id ? `<?php echo e(url('admin/salaries')); ?>/${id}` : `<?php echo e(route('admin.salaries.store')); ?>`;
    const fd = new FormData(this);
    if(id) fd.append('_method','PUT');
    document.getElementById('submitBtn').disabled = true;
    fetch(url, {method:'POST',body:fd,headers:{'X-CSRF-TOKEN':'<?php echo e(csrf_token()); ?>','X-Requested-With':'XMLHttpRequest'}})
        .then(r=>{ if(!r.ok) throw new Error('HTTP '+r.status); return r.json(); })
        .then(d => {
            document.getElementById('submitBtn').disabled = false;
            showToast(d.message, d.success?'success':'error');
            if(d.success){ bootstrap.Modal.getInstance(document.getElementById('salaryModal')).hide(); loadData(currentPage); }
        }).catch(()=>{ document.getElementById('submitBtn').disabled = false; showToast('Gagal menyimpan. Coba lagi.', 'error'); });
});

function resetFilter() {
    document.getElementById('searchInput').value = '';
    document.getElementById('filterStatus').value = '';
    document.getElementById('filterPeriode').value = '';
    document.getElementById('filterCabang').value = '';
    loadData(1);
}

let st; document.getElementById('searchInput').addEventListener('input', ()=>{ clearTimeout(st); st=setTimeout(()=>loadData(1),400); });
document.addEventListener('DOMContentLoaded', ()=>loadData());

// Open teacher detail modal and prepare upload action
function openTeacher(id){
    // load salary list filtered by selected teacher
    loadData(1, id);
    fetch(`<?php echo e(url('admin/teachers')); ?>/${id}`, { headers:{'X-Requested-With':'XMLHttpRequest'} })
        .then(r=>{ if(!r.ok) throw new Error('HTTP '+r.status); return r.json(); })
        .then(res=>{
            const t = res.data;
            const subjects = (t.subjects && t.subjects.length) ? (Array.isArray(t.subjects) ? t.subjects : [t.subjects]) : (t.courses ? t.courses.map(c=>c.nama) : []);
            const photo = t.photo ? (t.photo.startsWith('http') ? t.photo : ('/storage/'+t.photo)) : (`https://ui-avatars.com/api/?name=${encodeURIComponent(t.name)}&background=68117e&color=fff`);
            document.getElementById('teacherModalTitle').textContent = t.name;
            document.getElementById('teacherModalBody').innerHTML = `
                <div class="row">
                    <div class="col-md-8">
                        <div class="fw-semibold mb-1">${t.name}</div>
                        <div class="text-muted small mb-3">${t.branch? t.branch.name : 'Pusat'}</div>
                        <div><strong>Mapel / Subjects</strong><ul>${subjects.map(s=>`<li>${s}</li>`).join('') || '<li>-</li>'}</ul></div>
                    </div>
                    <div class="col-md-4 text-end"><img src="${photo}" alt="Foto" style="width:96px;height:96px;border-radius:8px;object-fit:cover"></div>
                </div>
            `;

            // open modal
            new bootstrap.Modal(document.getElementById('teacherModal')).show();

            // wire upload button
            const btn = document.getElementById('openUploadBtn');
            btn.onclick = function(){
                bootstrap.Modal.getInstance(document.getElementById('teacherModal')).hide();
                window.location.href = `<?php echo e(route('admin.salaries.create')); ?>?guru_id=${t.id}`;
            };
        }).catch(()=>showToast('Gagal memuat data guru.', 'error'));
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Edu Juanda Pratama\Downloads\smart-center-indonesia-2\smart-center-indonesia-2\resources\views/admin/salaries/index.blade.php ENDPATH**/ ?>