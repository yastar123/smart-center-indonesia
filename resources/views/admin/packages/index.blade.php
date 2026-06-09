@extends('layouts.app')
@section('title','Kelola Paket')
@section('page-title','Kelola Paket Belajar')

@section('content')
<div>

{{-- HEADER --}}
<div class="dashboard-card mb-4 fade-up" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
    <div class="row align-items-center g-3" style="position:relative">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-3 mb-2">
                <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0">
                    <i class="bi bi-box-seam"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0" style="color:white">Kelola Paket Belajar</h5>
                    <span style="font-size:12px;opacity:.8">Atur paket, harga, durasi, dan jumlah pertemuan</span>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-md-end">
            <button onclick="openModal()" class="btn fw-semibold px-4" style="background:rgba(255,255,255,.2);color:white;border:1px solid rgba(255,255,255,.3);border-radius:10px">
                <i class="bi bi-plus-lg me-2"></i>Tambah Paket
            </button>
        </div>
    </div>
</div>

{{-- STATS --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3"><div class="stat-card" style="border-top:3px solid #10b981"><div class="d-flex justify-content-between align-items-start"><div><div class="stat-title">Total Paket</div><div class="stat-value" id="statTotal">–</div></div><div class="stat-icon bg-success-soft" style="color:white"><i class="bi bi-box-seam"></i></div></div></div></div>
    <div class="col-6 col-lg-3"><div class="stat-card" style="border-top:3px solid #10b981"><div class="d-flex justify-content-between align-items-start"><div><div class="stat-title">Paket Aktif</div><div class="stat-value" id="statAktif">–</div></div><div class="stat-icon bg-success-soft" style="color:white"><i class="bi bi-check-circle"></i></div></div></div></div>
    <div class="col-6 col-lg-3"><div class="stat-card" style="border-top:3px solid #f6af23"><div class="d-flex justify-content-between align-items-start"><div><div class="stat-title">Paket Unggulan</div><div class="stat-value" id="statUnggulan">–</div></div><div class="stat-icon bg-warning-soft" style="color:white"><i class="bi bi-star"></i></div></div></div></div>
    <div class="col-6 col-lg-3"><div class="stat-card" style="border-top:3px solid #c84ddf"><div class="d-flex justify-content-between align-items-start"><div><div class="stat-title">Rata-rata Harga</div><div class="stat-value" id="statAvg" style="font-size:18px">–</div></div><div class="stat-icon bg-primary-soft" style="color:white"><i class="bi bi-cash"></i></div></div></div></div>
</div>

{{-- FILTERS --}}
<div class="dashboard-card mb-4">
    <div class="row g-2">
        <div class="col-12 col-md-4"><div class="input-group"><span class="input-group-text"><i class="bi bi-search"></i></span><input type="text" id="searchInput" class="form-control" placeholder="Cari paket..."></div></div>
        <div class="col-6 col-md-3"><select id="filterJenis" class="form-select"><option value="">Semua Jenis</option><option>reguler</option><option>intensif</option><option>privat</option><option>online</option></select></div>
        <div class="col-6 col-md-2"><select id="filterStatus" class="form-select"><option value="">Semua Status</option><option value="aktif">Aktif</option><option value="nonaktif">Nonaktif</option></select></div>
        <div class="col-6 col-md-2"><button onclick="loadData()" class="btn btn-primary w-100"><i class="bi bi-funnel me-1"></i>Filter</button></div>
        <div class="col-6 col-md-1"><button onclick="resetFilter()" class="btn btn-outline-secondary w-100" title="Reset filter" aria-label="Reset filter"><i class="bi bi-x-lg"></i></button></div>
    </div>
</div>

{{-- TABLE --}}
<div class="dashboard-card">
    <div class="table-responsive">
        <table class="table table-hover table-modern align-middle mb-0">
            <thead class="thead-modern"><tr><th>Nama Paket</th><th>Jenis</th><th>Harga</th><th>Durasi</th><th>Pertemuan</th><th class="text-center">Unggulan</th><th>Status</th><th class="text-center">Aksi</th></tr></thead>
            <tbody id="tableBody">
                @for($i=0;$i<5;$i++)
                <tr class="skeleton-row"><td><div class="skeleton-cell" style="width:70%"></div></td><td><div class="skeleton-cell" style="width:60px"></div></td><td><div class="skeleton-cell" style="width:80px"></div></td><td><div class="skeleton-cell" style="width:55px"></div></td><td><div class="skeleton-cell" style="width:55px"></div></td><td><div class="skeleton-cell" style="width:30px;margin:0 auto"></div></td><td><div class="skeleton-cell" style="width:55px"></div></td><td><div class="skeleton-cell" style="width:80px;margin:0 auto"></div></td></tr>
                @endfor
            </tbody>
        </table>
    </div>
    <div id="paginationLinks" class="mt-3 d-flex justify-content-center"></div>
</div>

</div>

{{-- MODAL --}}
<div class="modal fade" id="packageModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius:20px;border:none">
            <div class="modal-header border-0 p-4" style="background:linear-gradient(135deg,#260632,#461256,#c84ddf);border-radius:20px 20px 0 0">
                <h5 class="modal-title fw-bold text-white" id="modalTitle"><i class="bi bi-box-seam me-2"></i>Tambah Paket</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="packageForm">
                @csrf
                <input type="hidden" id="pkgId">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-8"><label class="form-label fw-semibold">Nama Paket <span class="text-danger">*</span></label><input type="text" name="nama" class="form-control" required></div>
                        <div class="col-md-4"><label class="form-label fw-semibold">Jenis <span class="text-danger">*</span></label><select name="jenis" class="form-select" required><option value="reguler">Reguler</option><option value="intensif">Intensif</option><option value="privat">Privat</option><option value="online">Online</option></select></div>
                        <div class="col-md-4"><label class="form-label fw-semibold">Harga (Rp) <span class="text-danger">*</span></label><input type="number" name="harga" class="form-control" required min="0"></div>
                        <div class="col-md-4"><label class="form-label fw-semibold">Durasi (bulan) <span class="text-danger">*</span></label><input type="number" name="durasi_bulan" class="form-control" required min="1" value="3"></div>
                        <div class="col-md-4"><label class="form-label fw-semibold">Jumlah Pertemuan <span class="text-danger">*</span></label><input type="number" name="jumlah_pertemuan" class="form-control" required min="1" value="12"></div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Cabang</label>
                            <select name="cabang_id" class="form-select"><option value="">Semua Cabang</option>@foreach($branches as $b)<option value="{{ $b->id }}">{{ $b->name }}</option>@endforeach</select>
                        </div>
                        <div class="col-md-6"><label class="form-label fw-semibold">Status</label><select name="status" class="form-select"><option value="aktif">Aktif</option><option value="nonaktif">Nonaktif</option></select></div>
                        <div class="col-12"><label class="form-label fw-semibold">Deskripsi</label><textarea name="deskripsi" class="form-control" rows="3"></textarea></div>
                        <div class="col-12"><div class="form-check form-switch"><input class="form-check-input" type="checkbox" name="is_unggulan" id="isUnggulan" value="1"><label class="form-check-label" for="isUnggulan">Tandai sebagai Paket Unggulan</label></div></div>
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

function loadData(page = 1) {
    currentPage = page;
    const params = new URLSearchParams({ page, search: document.getElementById('searchInput').value, jenis: document.getElementById('filterJenis').value, status: document.getElementById('filterStatus').value });
    fetch(`{{ route('admin.packages.index') }}?${params}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => { if (!r.ok) throw new Error('HTTP '+r.status); return r.json(); })
        .then(data => {
            countUpValue(document.getElementById('statTotal'),    data.stats.total);
            countUpValue(document.getElementById('statAktif'),    data.stats.aktif);
            countUpValue(document.getElementById('statUnggulan'), data.stats.unggulan);
            document.getElementById('statAvg').textContent      = 'Rp ' + parseInt(data.stats.avg_price || 0).toLocaleString('id-ID');
            renderTable(data.data);
            renderPagination(data);
        })
        .catch(() => {
            document.getElementById('tableBody').innerHTML = `<tr><td colspan="8" class="text-center py-5"><i class="bi bi-wifi-off" style="font-size:2rem;color:#ef4444;display:block;margin-bottom:10px"></i><div class="fw-semibold mb-2">Gagal memuat data</div><button onclick="loadData(${page})" class="btn btn-sm btn-outline-primary"><i class="bi bi-arrow-clockwise me-1"></i>Coba lagi</button></td></tr>`;
        });
}

function renderTable(rows) {
    if (!rows.length) { document.getElementById('tableBody').innerHTML = '<tr><td colspan="8" class="text-center py-5 text-muted">Belum ada paket</td></tr>'; return; }
    document.getElementById('tableBody').innerHTML = rows.map(p => `
        <tr>
            <td><div class="fw-semibold">${p.nama}</div><small class="text-muted">${p.deskripsi ? p.deskripsi.substring(0,50)+'...' : ''}</small></td>
            <td><span class="badge bg-primary-subtle text-primary text-capitalize">${p.jenis}</span></td>
            <td class="fw-semibold">Rp ${parseInt(p.harga).toLocaleString('id-ID')}</td>
            <td>${p.durasi_bulan} bln</td>
            <td>${p.jumlah_pertemuan}x</td>
            <td>${p.is_unggulan ? '<i class="bi bi-star-fill text-warning"></i>' : '<i class="bi bi-star text-muted"></i>'}</td>
            <td>${p.status === 'aktif' ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-secondary">Nonaktif</span>'}</td>
            <td><div class="d-flex gap-1">
                <button onclick="editPkg(${p.id})" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></button>
                <button onclick="deletePkg(${p.id},'${p.nama}')" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
            </div></td>
        </tr>`).join('');
}

function renderPagination(data) {
    const el = document.getElementById('paginationLinks');
    if (data.last_page <= 1) { el.innerHTML = ''; return; }
    let html = '<nav><ul class="pagination pagination-sm mb-0">';
    html += `<li class="page-item ${data.current_page==1?'disabled':''}"><a class="page-link" href="#" onclick="loadData(${data.current_page-1});return false">‹</a></li>`;
    for (let i=1; i<=data.last_page; i++) html += `<li class="page-item ${i==data.current_page?'active':''}"><a class="page-link" href="#" onclick="loadData(${i});return false">${i}</a></li>`;
    html += `<li class="page-item ${data.current_page==data.last_page?'disabled':''}"><a class="page-link" href="#" onclick="loadData(${data.current_page+1});return false">›</a></li></ul></nav>`;
    el.innerHTML = html;
}

function openModal(reset=true) {
    if (reset) { document.getElementById('packageForm').reset(); document.getElementById('pkgId').value = ''; document.getElementById('modalTitle').textContent = 'Tambah Paket'; }
    new bootstrap.Modal(document.getElementById('packageModal')).show();
}

function editPkg(id) {
    fetch(`{{ url('admin/packages') }}/${id}`, { headers:{'X-Requested-With':'XMLHttpRequest'} })
        .then(r=>{ if(!r.ok) throw new Error('HTTP '+r.status); return r.json(); })
        .then(res => {
            const p = res.data, f = document.getElementById('packageForm');
            f.querySelector('[name=nama]').value = p.nama||'';
            f.querySelector('[name=jenis]').value = p.jenis||'reguler';
            f.querySelector('[name=harga]').value = p.harga||0;
            f.querySelector('[name=durasi_bulan]').value = p.durasi_bulan||3;
            f.querySelector('[name=jumlah_pertemuan]').value = p.jumlah_pertemuan||12;
            f.querySelector('[name=status]').value = p.status||'aktif';
            f.querySelector('[name=deskripsi]').value = p.deskripsi||'';
            f.querySelector('[name=is_unggulan]').checked = !!p.is_unggulan;
            if (f.querySelector('[name=cabang_id]')) f.querySelector('[name=cabang_id]').value = p.cabang_id||'';
            document.getElementById('pkgId').value = id;
            document.getElementById('modalTitle').textContent = 'Edit Paket';
            openModal(false);
        }).catch(()=>showToast('Gagal memuat data paket.', 'error'));
}

function deletePkg(id, name) {
    confirmAction(`Hapus paket "${name}"? Data tidak dapat dikembalikan.`, function() {
        fetch(`{{ url('admin/packages') }}/${id}`, { method:'DELETE', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','X-Requested-With':'XMLHttpRequest'} })
            .then(r=>{ if(!r.ok) throw new Error('HTTP '+r.status); return r.json(); })
            .then(d => { showToast(d.message, d.success?'success':'error'); if(d.success) loadData(currentPage); })
            .catch(()=>showToast('Gagal menghubungi server.', 'error'));
    }, null, {title:'Hapus Paket', okText:'Ya, Hapus'});
}

document.getElementById('packageForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const id = document.getElementById('pkgId').value;
    const url = id ? `{{ url('admin/packages') }}/${id}` : `{{ route('admin.packages.store') }}`;
    const fd = new FormData(this);
    if (id) fd.append('_method','PUT');
    document.getElementById('submitBtn').disabled = true;
    fetch(url, { method:'POST', body:fd, headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','X-Requested-With':'XMLHttpRequest'} })
        .then(r=>r.json()).then(d => {
            document.getElementById('submitBtn').disabled = false;
            showToast(d.message, d.success?'success':'error');
            if(d.success){ bootstrap.Modal.getInstance(document.getElementById('packageModal')).hide(); loadData(currentPage); }
        }).catch(()=>{ document.getElementById('submitBtn').disabled = false; });
});

function resetFilter() {
    document.getElementById('searchInput').value = '';
    document.getElementById('filterJenis').value = '';
    document.getElementById('filterStatus').value = '';
    loadData(1);
}

let st; document.getElementById('searchInput').addEventListener('input', ()=>{ clearTimeout(st); st=setTimeout(()=>loadData(1),400); });
document.addEventListener('DOMContentLoaded', ()=>loadData());
</script>
@endpush
