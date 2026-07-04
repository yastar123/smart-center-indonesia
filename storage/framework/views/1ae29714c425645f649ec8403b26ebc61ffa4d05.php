<?php $__env->startSection('title','Gaji Guru'); ?>
<?php $__env->startSection('page-title','Manajemen Gaji Guru'); ?>

<?php $__env->startSection('content'); ?>
<div class="fade-up">


<div class="dashboard-card mb-4" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
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
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h6 class="fw-bold mb-0"><i class="bi bi-people-fill text-primary me-2"></i>Daftar Guru</h6>
        <span class="text-muted" style="font-size:12px">Klik nama guru untuk filter tabel di bawah</span>
    </div>
    <div class="d-flex flex-wrap gap-2" id="teacherList">
        <button type="button" class="btn btn-sm btn-primary" onclick="clearTeacherFilter()" id="btnAllTeachers">
            <i class="bi bi-grid me-1"></i>Semua Guru
        </button>
        <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <button type="button" class="btn btn-sm btn-outline-secondary teacher-btn" data-id="<?php echo e($t->id); ?>" onclick="filterByTeacher(<?php echo e($t->id); ?>, this)">
            <i class="bi bi-person me-1"></i><?php echo e($t->name); ?>

            <span class="badge bg-secondary ms-1" style="font-size:10px"><?php echo e($t->branch?->name ?? 'Pusat'); ?></span>
        </button>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <div class="mt-4 pt-3 border-top">
        
        <div class="row g-2 mb-3">
            <div class="col-12 col-md-3"><div class="input-group input-group-sm"><span class="input-group-text"><i class="bi bi-search"></i></span><input type="text" id="searchInput" class="form-control" placeholder="Cari nama guru..."></div></div>
            <div class="col-6 col-md-2"><select id="filterStatus" class="form-select form-select-sm"><option value="">Semua Status</option><option value="dibayar">Dibayar</option><option value="pending">Pending</option><option value="batal">Batal</option></select></div>
            <div class="col-6 col-md-2"><input type="month" id="filterPeriode" class="form-control form-control-sm"></div>
            <div class="col-6 col-md-2"><select id="filterCabang" class="form-select form-select-sm"><option value="">Semua Cabang</option><?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($b->id); ?>"><?php echo e($b->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
            <div class="col-4 col-md-2"><button onclick="loadData()" class="btn btn-primary btn-sm w-100"><i class="bi bi-funnel me-1"></i>Filter</button></div>
            <div class="col-2 col-md-1"><button onclick="resetFilter()" class="btn btn-outline-secondary btn-sm w-100" title="Reset" aria-label="Reset"><i class="bi bi-x-lg"></i></button></div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-modern align-middle mb-0">
                <thead class="thead-modern">
                    <tr>
                        <th>Guru</th>
                        <th>Periode</th>
                        <th>Tipe</th>
                        <th>Gaji Pokok</th>
                        <th>Total Gaji</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php for($i=0;$i<5;$i++): ?>
                    <tr class="skeleton-row">
                        <td><div class="skeleton-cell" style="width:75%"></div></td>
                        <td><div class="skeleton-cell" style="width:70px"></div></td>
                        <td><div class="skeleton-cell" style="width:60px"></div></td>
                        <td><div class="skeleton-cell" style="width:80px"></div></td>
                        <td><div class="skeleton-cell" style="width:90px"></div></td>
                        <td><div class="skeleton-cell" style="width:60px"></div></td>
                        <td><div class="skeleton-cell" style="width:90px;margin:0 auto"></div></td>
                    </tr>
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
                        <div class="col-12">
                            <label class="form-label fw-semibold">Bukti Pembayaran (jpg,png,pdf)</label>
                            <input type="file" name="bukti_pembayaran" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
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
let currentPage    = 1;
let activeGuruId   = null;

function loadData(page = 1, guru_id = null) {
    currentPage = page;
    if (guru_id !== null) activeGuruId = guru_id;
    const params = new URLSearchParams();
    params.append('page', page);
    const search  = document.getElementById('searchInput').value;
    if (search)       params.append('search', search);
    const status  = document.getElementById('filterStatus').value;
    if (status)       params.append('status', status);
    const periode = document.getElementById('filterPeriode').value;
    if (periode)      params.append('periode', periode);
    const cabang  = document.getElementById('filterCabang').value;
    if (cabang)       params.append('cabang_id', cabang);
    if (activeGuruId) params.append('guru_id', activeGuruId);

    fetch(`<?php echo e(route('admin.salaries.index')); ?>?${params.toString()}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => { if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
        .then(data => {
            countUpValue(document.getElementById('statTotal'),   data.stats.total);
            countUpValue(document.getElementById('statDibayar'), data.stats.dibayar);
            countUpValue(document.getElementById('statPending'), data.stats.pending);
            document.getElementById('statNominal').textContent = 'Rp ' + parseInt(data.stats.total_nominal || 0).toLocaleString('id-ID');
            renderTable(data.data);
            renderPagination(data);
        })
        .catch(() => {
            document.getElementById('tableBody').innerHTML = `<tr><td colspan="7" class="text-center py-5"><i class="bi bi-wifi-off" style="font-size:2rem;color:#ef4444;display:block;margin-bottom:10px"></i><div class="fw-semibold mb-2">Gagal memuat data</div><button onclick="loadData(${currentPage})" class="btn btn-sm btn-outline-primary">Coba lagi</button></td></tr>`;
        });
}

function renderTable(rows) {
    const statusMap = {
        dibayar: '<span class="badge bg-success">Dibayar</span>',
        pending: '<span class="badge bg-warning text-dark">Pending</span>',
        batal:   '<span class="badge bg-danger">Batal</span>'
    };
    if (!rows.length) {
        document.getElementById('tableBody').innerHTML = '<tr><td colspan="7" class="text-center py-5 text-muted">Belum ada data gaji</td></tr>';
        return;
    }
    document.getElementById('tableBody').innerHTML = rows.map(s => {
        const tipe = s.tipe_gaji === 'freelance' ? 'Freelance' : 'Bulanan';
        return `<tr>
            <td><div class="fw-semibold">${s.guru?.name || '-'}</div><div class="text-muted" style="font-size:11px">${s.guru?.branch?.name || 'Pusat'}</div></td>
            <td>${s.periode}</td>
            <td><span class="badge bg-secondary-subtle text-secondary">${tipe}</span></td>
            <td>Rp ${parseInt(s.gaji_pokok || 0).toLocaleString('id-ID')}</td>
            <td class="fw-bold text-success">Rp ${parseInt(s.total_gaji || 0).toLocaleString('id-ID')}</td>
            <td>${statusMap[s.status] || s.status}</td>
            <td><div class="d-flex gap-1 justify-content-center">
                <a href="<?php echo e(url('admin/salaries')); ?>/${s.id}/slip" target="_blank" class="btn btn-sm btn-outline-primary" title="Cetak Slip"><i class="bi bi-printer"></i></a>
                <a href="<?php echo e(url('admin/salaries')); ?>/${s.id}/edit" class="btn btn-sm btn-outline-secondary" title="Edit"><i class="bi bi-pencil"></i></a>
                <button onclick="deleteSalary(${s.id})" class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
            </div></td>
        </tr>`;
    }).join('');
}

function renderPagination(data) {
    const el = document.getElementById('paginationLinks');
    if (data.last_page <= 1) { el.innerHTML = ''; return; }
    let h = '<nav><ul class="pagination pagination-sm mb-0">';
    h += `<li class="page-item ${data.current_page == 1 ? 'disabled' : ''}"><a class="page-link" href="#" onclick="loadData(${data.current_page - 1});return false">‹</a></li>`;
    for (let i = 1; i <= data.last_page; i++)
        h += `<li class="page-item ${i == data.current_page ? 'active' : ''}"><a class="page-link" href="#" onclick="loadData(${i});return false">${i}</a></li>`;
    h += `<li class="page-item ${data.current_page == data.last_page ? 'disabled' : ''}"><a class="page-link" href="#" onclick="loadData(${data.current_page + 1});return false">›</a></li></ul></nav>`;
    el.innerHTML = h;
}

function filterByTeacher(id, btn) {
    activeGuruId = id;
    document.querySelectorAll('.teacher-btn').forEach(b => b.classList.replace('btn-primary', 'btn-outline-secondary'));
    document.getElementById('btnAllTeachers').classList.replace('btn-primary', 'btn-outline-secondary');
    if (btn) { btn.classList.replace('btn-outline-secondary', 'btn-primary'); }
    loadData(1);
}

function clearTeacherFilter() {
    activeGuruId = null;
    document.querySelectorAll('.teacher-btn').forEach(b => b.classList.replace('btn-primary', 'btn-outline-secondary'));
    document.getElementById('btnAllTeachers').classList.replace('btn-outline-secondary', 'btn-primary');
    loadData(1);
}

function deleteSalary(id) {
    confirmAction('Hapus data gaji ini? Data tidak dapat dikembalikan.', function () {
        fetch(`<?php echo e(url('admin/salaries')); ?>/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>', 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => { if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
            .then(d => { showToast(d.message, d.success ? 'success' : 'error'); if (d.success) loadData(currentPage); })
            .catch(() => showToast('Gagal menghubungi server.', 'error'));
    }, null, { title: 'Hapus Data Gaji', okText: 'Ya, Hapus' });
}

function resetFilter() {
    document.getElementById('searchInput').value  = '';
    document.getElementById('filterStatus').value = '';
    document.getElementById('filterPeriode').value = '';
    document.getElementById('filterCabang').value = '';
    clearTeacherFilter();
}

let st;
document.getElementById('searchInput').addEventListener('input', () => { clearTimeout(st); st = setTimeout(() => loadData(1), 400); });
document.addEventListener('DOMContentLoaded', () => loadData());
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/admin/salaries/index.blade.php ENDPATH**/ ?>