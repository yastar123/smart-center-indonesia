@extends('layouts.app')
@section('title','Gaji Guru')
@section('page-title','Manajemen Gaji Guru')

@section('content')
<div class="fade-up">

{{-- HEADER --}}
<div class="dashboard-card mb-4" style="background:linear-gradient(135deg,#1e1b4b 0%,#4338ca 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%"></div>
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
            <button onclick="openModal()" class="btn fw-semibold px-4" style="background:rgba(255,255,255,.2);color:white;border:1px solid rgba(255,255,255,.3);border-radius:10px">
                <i class="bi bi-plus-lg me-2"></i>Input Gaji
            </button>
        </div>
    </div>
</div>

{{-- STATS --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3"><div class="stat-card"><div class="d-flex justify-content-between"><div><div class="stat-title">Total Record</div><div class="stat-value" id="statTotal">–</div></div><div class="stat-icon" style="background:#4338ca22;color:#4338ca"><i class="bi bi-receipt"></i></div></div></div></div>
    <div class="col-6 col-lg-3"><div class="stat-card"><div class="d-flex justify-content-between"><div><div class="stat-title">Sudah Dibayar</div><div class="stat-value" id="statDibayar">–</div></div><div class="stat-icon" style="background:#10b98122;color:#10b981"><i class="bi bi-check-circle"></i></div></div></div></div>
    <div class="col-6 col-lg-3"><div class="stat-card"><div class="d-flex justify-content-between"><div><div class="stat-title">Pending</div><div class="stat-value" id="statPending">–</div></div><div class="stat-icon" style="background:#f6af2322;color:#b45309"><i class="bi bi-clock"></i></div></div></div></div>
    <div class="col-6 col-lg-3"><div class="stat-card"><div class="d-flex justify-content-between"><div><div class="stat-title">Total Dibayarkan</div><div class="stat-value text-success" id="statNominal" style="font-size:16px">–</div></div><div class="stat-icon" style="background:#10b98122;color:#10b981"><i class="bi bi-cash"></i></div></div></div></div>
</div>

{{-- FILTERS --}}
<div class="dashboard-card mb-4">
    <div class="row g-2">
        <div class="col-md-3"><div class="input-group"><span class="input-group-text"><i class="bi bi-search"></i></span><input type="text" id="searchInput" class="form-control" placeholder="Cari nama guru..."></div></div>
        <div class="col-md-2"><select id="filterStatus" class="form-select"><option value="">Semua Status</option><option value="dibayar">Dibayar</option><option value="pending">Pending</option><option value="batal">Batal</option></select></div>
        <div class="col-md-3"><input type="month" id="filterPeriode" class="form-control"></div>
        <div class="col-md-2"><select id="filterCabang" class="form-select"><option value="">Semua Cabang</option>@foreach($branches as $b)<option value="{{ $b->id }}">{{ $b->name }}</option>@endforeach</select></div>
        <div class="col-md-2"><button onclick="loadData()" class="btn btn-primary w-100"><i class="bi bi-funnel me-1"></i>Filter</button></div>
    </div>
</div>

{{-- TABLE --}}
<div class="dashboard-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead><tr style="background:rgba(200,77,223,.05)"><th>Guru</th><th>Periode</th><th>Gaji Pokok</th><th>Jam Mengajar</th><th>Bonus</th><th>Total Gaji</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody id="tableBody"><tr><td colspan="8" class="text-center py-4"><div class="spinner-border spinner-border-sm text-primary me-2"></div>Memuat...</td></tr></tbody>
        </table>
    </div>
    <div id="paginationLinks" class="mt-3 d-flex justify-content-center"></div>
</div>
</div>

{{-- MODAL --}}
<div class="modal fade" id="salaryModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius:20px;border:none">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalTitle">Input Gaji Guru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="salaryForm">
                @csrf
                <input type="hidden" id="salId">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Guru <span class="text-danger">*</span></label>
                            <select name="guru_id" class="form-select" required><option value="">Pilih Guru</option>@foreach($teachers as $t)<option value="{{ $t->id }}">{{ $t->name }}</option>@endforeach</select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Periode <span class="text-danger">*</span></label>
                            <input type="month" name="periode" class="form-control" required value="{{ date('Y-m') }}">
                        </div>
                        <div class="col-md-4"><label class="form-label fw-semibold">Gaji Pokok (Rp)</label><input type="number" name="gaji_pokok" class="form-control" value="0" min="0" required></div>
                        <div class="col-md-4"><label class="form-label fw-semibold">Jam Mengajar</label><input type="number" name="jam_mengajar" class="form-control" value="0" min="0" step="0.5"></div>
                        <div class="col-md-4"><label class="form-label fw-semibold">Tarif per Jam (Rp)</label><input type="number" name="tarif_per_jam" class="form-control" value="0" min="0"></div>
                        <div class="col-md-4"><label class="form-label fw-semibold">Bonus (Rp)</label><input type="number" name="bonus" class="form-control" value="0" min="0"></div>
                        <div class="col-md-4"><label class="form-label fw-semibold">Potongan (Rp)</label><input type="number" name="potongan" class="form-control" value="0" min="0"></div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select"><option value="pending">Pending</option><option value="dibayar">Dibayar</option><option value="batal">Batal</option></select>
                        </div>
                        <div class="col-md-6"><label class="form-label fw-semibold">Metode Pembayaran</label><select name="metode_pembayaran" class="form-select"><option value="">Pilih</option><option>Transfer Bank</option><option>Tunai</option><option>E-Wallet</option></select></div>
                        <div class="col-md-6"><label class="form-label fw-semibold">Tanggal Pembayaran</label><input type="date" name="tanggal_pembayaran" class="form-control"></div>
                        <div class="col-md-6"><label class="form-label fw-semibold">Nama Bank</label><input type="text" name="nama_bank" class="form-control" placeholder="BCA, Mandiri, dll"></div>
                        <div class="col-md-6"><label class="form-label fw-semibold">Nomor Rekening</label><input type="text" name="nomor_rekening" class="form-control"></div>
                        <div class="col-12"><label class="form-label fw-semibold">Catatan</label><textarea name="catatan" class="form-control" rows="2"></textarea></div>
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
@endsection

@push('scripts')
<script>
let currentPage = 1;

function loadData(page=1) {
    currentPage = page;
    const params = new URLSearchParams({ page, search: document.getElementById('searchInput').value, status: document.getElementById('filterStatus').value, periode: document.getElementById('filterPeriode').value, cabang_id: document.getElementById('filterCabang').value });
    fetch(`{{ route('admin.salaries.index') }}?${params}`, { headers:{'X-Requested-With':'XMLHttpRequest'} })
        .then(r=>r.json()).then(data => {
            document.getElementById('statTotal').textContent   = data.stats.total;
            document.getElementById('statDibayar').textContent = data.stats.dibayar;
            document.getElementById('statPending').textContent = data.stats.pending;
            document.getElementById('statNominal').textContent = 'Rp ' + parseInt(data.stats.total_nominal||0).toLocaleString('id-ID');
            renderTable(data.data);
            renderPagination(data);
        });
}

function renderTable(rows) {
    const statusMap = { dibayar:'<span class="badge bg-success">Dibayar</span>', pending:'<span class="badge bg-warning text-dark">Pending</span>', batal:'<span class="badge bg-danger">Batal</span>' };
    if (!rows.length) { document.getElementById('tableBody').innerHTML = '<tr><td colspan="8" class="text-center py-5 text-muted">Belum ada data gaji</td></tr>'; return; }
    document.getElementById('tableBody').innerHTML = rows.map(s => `
        <tr>
            <td><div class="fw-semibold">${s.guru?.name||'-'}</div></td>
            <td>${s.periode}</td>
            <td>Rp ${parseInt(s.gaji_pokok||0).toLocaleString('id-ID')}</td>
            <td>${s.jam_mengajar||0} jam</td>
            <td>${s.bonus ? 'Rp '+parseInt(s.bonus).toLocaleString('id-ID') : '-'}</td>
            <td class="fw-bold text-success">Rp ${parseInt(s.total_gaji||0).toLocaleString('id-ID')}</td>
            <td>${statusMap[s.status]||s.status}</td>
            <td><div class="d-flex gap-1">
                <a href="{{ url('admin/salaries') }}/${s.id}/slip" target="_blank" class="btn btn-sm btn-outline-primary" title="Cetak Slip"><i class="bi bi-printer"></i></a>
                <button onclick="editSalary(${s.id})" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></button>
                <button onclick="deleteSalary(${s.id})" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
            </div></td>
        </tr>`).join('');
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
    fetch(`{{ url('admin/salaries') }}/${id}`, { headers:{'X-Requested-With':'XMLHttpRequest'} })
        .then(r=>r.json()).then(res => {
            const s=res.data, f=document.getElementById('salaryForm');
            ['guru_id','periode','gaji_pokok','jam_mengajar','tarif_per_jam','bonus','potongan','status','metode_pembayaran','tanggal_pembayaran','nama_bank','nomor_rekening','catatan'].forEach(k => { if(f.querySelector(`[name=${k}]`)) f.querySelector(`[name=${k}]`).value=s[k]||''; });
            document.getElementById('salId').value = id;
            document.getElementById('modalTitle').textContent = 'Edit Data Gaji';
            openModal(false);
        });
}

function deleteSalary(id) {
    Swal.fire({title:'Hapus data gaji?',icon:'warning',showCancelButton:true,confirmButtonColor:'#ef4444',confirmButtonText:'Hapus'})
        .then(r => { if(!r.isConfirmed) return;
            fetch(`{{ url('admin/salaries') }}/${id}`, {method:'DELETE',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','X-Requested-With':'XMLHttpRequest'}})
                .then(r=>r.json()).then(d => { showToast(d.message, d.success?'success':'error'); if(d.success) loadData(currentPage); });
        });
}

document.getElementById('salaryForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const id = document.getElementById('salId').value;
    const url = id ? `{{ url('admin/salaries') }}/${id}` : `{{ route('admin.salaries.store') }}`;
    const fd = new FormData(this);
    if(id) fd.append('_method','PUT');
    document.getElementById('submitBtn').disabled = true;
    fetch(url, {method:'POST',body:fd,headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','X-Requested-With':'XMLHttpRequest'}})
        .then(r=>r.json()).then(d => {
            document.getElementById('submitBtn').disabled = false;
            showToast(d.message, d.success?'success':'error');
            if(d.success){ bootstrap.Modal.getInstance(document.getElementById('salaryModal')).hide(); loadData(currentPage); }
        }).catch(()=>{ document.getElementById('submitBtn').disabled = false; });
});

let st; document.getElementById('searchInput').addEventListener('input', ()=>{ clearTimeout(st); st=setTimeout(()=>loadData(1),400); });
document.addEventListener('DOMContentLoaded', ()=>loadData());
</script>
@endpush
