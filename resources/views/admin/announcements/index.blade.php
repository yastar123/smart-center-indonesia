@extends('layouts.app')
@section('title','Pengumuman')
@section('page-title','Dashboard Pengumuman')

@section('content')
<div class="fade-up">

{{-- HEADER --}}
<div class="dashboard-card mb-4" style="background:linear-gradient(135deg,#7c2d12 0%,#c2410c 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%"></div>
    <div class="row align-items-center g-3" style="position:relative">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-3 mb-2">
                <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0"><i class="bi bi-megaphone"></i></div>
                <div>
                    <h5 class="fw-bold mb-0" style="color:white">Dashboard Pengumuman</h5>
                    <span style="font-size:12px;opacity:.8">Buat dan kelola pengumuman, banner promo, dan informasi penting</span>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-md-end">
            <button onclick="openModal()" class="btn fw-semibold px-4" style="background:rgba(255,255,255,.2);color:white;border:1px solid rgba(255,255,255,.3);border-radius:10px">
                <i class="bi bi-plus-lg me-2"></i>Buat Pengumuman
            </button>
        </div>
    </div>
</div>

{{-- STATS --}}
<div class="row g-3 mb-4">
    <div class="col-4"><div class="stat-card"><div class="d-flex justify-content-between"><div><div class="stat-title">Total</div><div class="stat-value" id="statTotal">–</div></div><div class="stat-icon" style="background:#c2410c22;color:#c2410c"><i class="bi bi-megaphone"></i></div></div></div></div>
    <div class="col-4"><div class="stat-card"><div class="d-flex justify-content-between"><div><div class="stat-title">Aktif</div><div class="stat-value" id="statAktif">–</div></div><div class="stat-icon" style="background:#10b98122;color:#10b981"><i class="bi bi-check-circle"></i></div></div></div></div>
    <div class="col-4"><div class="stat-card"><div class="d-flex justify-content-between"><div><div class="stat-title">Disematkan</div><div class="stat-value" id="statPinned">–</div></div><div class="stat-icon" style="background:#6366f122;color:#6366f1"><i class="bi bi-pin"></i></div></div></div></div>
</div>

{{-- FILTERS --}}
<div class="dashboard-card mb-4">
    <div class="row g-2">
        <div class="col-md-4"><div class="input-group"><span class="input-group-text"><i class="bi bi-search"></i></span><input type="text" id="searchInput" class="form-control" placeholder="Cari pengumuman..."></div></div>
        <div class="col-md-3"><select id="filterJenis" class="form-select"><option value="">Semua Jenis</option><option value="info">Info</option><option value="promo">Promo</option><option value="penting">Penting</option><option value="update">Update Aplikasi</option></select></div>
        <div class="col-md-3"><select id="filterStatus" class="form-select"><option value="">Semua Status</option><option value="aktif">Aktif</option><option value="draft">Draft</option><option value="arsip">Arsip</option></select></div>
        <div class="col-md-2"><button onclick="loadData()" class="btn btn-primary w-100"><i class="bi bi-funnel me-1"></i>Filter</button></div>
    </div>
</div>

{{-- CARDS --}}
<div id="announcementsGrid" class="row g-3 mb-3">
    <div class="col-12 text-center py-5"><div class="spinner-border text-primary"></div></div>
</div>
<div id="paginationLinks" class="d-flex justify-content-center mb-4"></div>

</div>

{{-- MODAL --}}
<div class="modal fade" id="annModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius:20px;border:none">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalTitle">Buat Pengumuman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="annForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="annId">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12"><label class="form-label fw-semibold">Judul <span class="text-danger">*</span></label><input type="text" name="judul" class="form-control" required placeholder="Judul pengumuman..."></div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Jenis</label>
                            <select name="jenis" class="form-select"><option value="info">Info</option><option value="promo">Promo</option><option value="penting">Penting</option><option value="update">Update Aplikasi</option></select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Target Audiens</label>
                            <select name="target" class="form-select"><option value="semua">Semua</option><option value="admin">Admin</option><option value="guru">Guru</option><option value="siswa">Siswa</option></select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select"><option value="aktif">Aktif</option><option value="draft">Draft</option><option value="arsip">Arsip</option></select>
                        </div>
                        <div class="col-md-6"><label class="form-label fw-semibold">Tanggal Mulai</label><input type="date" name="tanggal_mulai" class="form-control"></div>
                        <div class="col-md-6"><label class="form-label fw-semibold">Tanggal Selesai</label><input type="date" name="tanggal_selesai" class="form-control"></div>
                        <div class="col-12"><label class="form-label fw-semibold">Konten <span class="text-danger">*</span></label><textarea name="konten" class="form-control" rows="5" required placeholder="Isi pengumuman lengkap..."></textarea></div>
                        <div class="col-12"><label class="form-label fw-semibold">Lampiran (opsional)</label><input type="file" name="file" class="form-control" accept="image/*,.pdf"></div>
                        <div class="col-12"><div class="form-check form-switch"><input class="form-check-input" type="checkbox" name="is_pinned" id="isPinned" value="1"><label class="form-check-label" for="isPinned"><i class="bi bi-pin me-1"></i>Sematkan pengumuman ini di bagian atas</label></div></div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4" id="submitBtn"><i class="bi bi-send me-2"></i>Publikasikan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentPage = 1;
const jenisColor = { info:'#1a56db', promo:'#059669', penting:'#ef4444', update:'#c84ddf' };
const jenisIcon  = { info:'bi-info-circle', promo:'bi-tag', penting:'bi-exclamation-triangle', update:'bi-arrow-clockwise' };

function loadData(page=1) {
    currentPage = page;
    const params = new URLSearchParams({ page, search:document.getElementById('searchInput').value, jenis:document.getElementById('filterJenis').value, status:document.getElementById('filterStatus').value });
    fetch(`{{ route('admin.announcements.index') }}?${params}`, { headers:{'X-Requested-With':'XMLHttpRequest'} })
        .then(r=>r.json()).then(data => {
            document.getElementById('statTotal').textContent  = data.stats.total;
            document.getElementById('statAktif').textContent  = data.stats.aktif;
            document.getElementById('statPinned').textContent = data.stats.pinned;
            renderCards(data.data);
            renderPagination(data);
        });
}

function renderCards(rows) {
    const grid = document.getElementById('announcementsGrid');
    if (!rows.length) { grid.innerHTML = '<div class="col-12 text-center py-5 text-muted"><i class="bi bi-megaphone" style="font-size:2.5rem;display:block;margin-bottom:8px"></i>Belum ada pengumuman</div>'; return; }
    grid.innerHTML = rows.map(a => {
        const color = jenisColor[a.jenis] || '#666';
        const icon  = jenisIcon[a.jenis]  || 'bi-info-circle';
        return `<div class="col-md-6 col-lg-4">
            <div class="dashboard-card h-100" style="border-left:4px solid ${color}">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi ${icon}" style="color:${color};font-size:1.2rem"></i>
                        <span class="badge" style="background:${color}22;color:${color};text-transform:capitalize">${a.jenis}</span>
                        ${a.is_pinned ? '<i class="bi bi-pin-fill text-warning" title="Disematkan"></i>' : ''}
                    </div>
                    <span class="badge ${a.status==='aktif'?'bg-success':a.status==='draft'?'bg-warning text-dark':'bg-secondary'}">${a.status}</span>
                </div>
                <h6 class="fw-bold mb-2">${a.judul}</h6>
                <p class="text-muted mb-3" style="font-size:12px;line-height:1.5">${a.konten.substring(0,100)}${a.konten.length>100?'...':''}</p>
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted"><i class="bi bi-people me-1"></i>${a.target==='semua'?'Semua':a.target}</small>
                    <div class="d-flex gap-1">
                        <button onclick="editAnn(${a.id})" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></button>
                        <button onclick="deleteAnn(${a.id},'${a.judul}')" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </div>
                </div>
                <div class="mt-2 pt-2 border-top" style="font-size:11px;color:var(--text-muted)">
                    ${a.tanggal_mulai ? '<i class="bi bi-calendar me-1"></i>'+a.tanggal_mulai : ''}
                    ${a.tanggal_mulai && a.tanggal_selesai ? ' – '+a.tanggal_selesai : ''}
                </div>
            </div>
        </div>`;
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
    if(reset){ document.getElementById('annForm').reset(); document.getElementById('annId').value=''; document.getElementById('modalTitle').textContent='Buat Pengumuman'; }
    new bootstrap.Modal(document.getElementById('annModal')).show();
}

function editAnn(id) {
    fetch(`{{ url('admin/announcements') }}/${id}`, { headers:{'X-Requested-With':'XMLHttpRequest'} })
        .then(r=>r.json()).then(res => {
            const a=res.data, f=document.getElementById('annForm');
            f.querySelector('[name=judul]').value=a.judul||'';
            f.querySelector('[name=konten]').value=a.konten||'';
            f.querySelector('[name=jenis]').value=a.jenis||'info';
            f.querySelector('[name=target]').value=a.target||'semua';
            f.querySelector('[name=status]').value=a.status||'aktif';
            f.querySelector('[name=tanggal_mulai]').value=a.tanggal_mulai||'';
            f.querySelector('[name=tanggal_selesai]').value=a.tanggal_selesai||'';
            f.querySelector('[name=is_pinned]').checked=!!a.is_pinned;
            document.getElementById('annId').value=id;
            document.getElementById('modalTitle').textContent='Edit Pengumuman';
            openModal(false);
        });
}

function deleteAnn(id, title) {
    Swal.fire({title:'Hapus Pengumuman?',text:title,icon:'warning',showCancelButton:true,confirmButtonColor:'#ef4444',confirmButtonText:'Hapus'})
        .then(r => { if(!r.isConfirmed) return;
            fetch(`{{ url('admin/announcements') }}/${id}`, {method:'DELETE',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','X-Requested-With':'XMLHttpRequest'}})
                .then(r=>r.json()).then(d => { showToast(d.message,d.success?'success':'error'); if(d.success) loadData(currentPage); });
        });
}

document.getElementById('annForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const id = document.getElementById('annId').value;
    const url = id ? `{{ url('admin/announcements') }}/${id}` : `{{ route('admin.announcements.store') }}`;
    const fd = new FormData(this);
    if(id) fd.append('_method','PUT');
    document.getElementById('submitBtn').disabled = true;
    fetch(url, {method:'POST',body:fd,headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','X-Requested-With':'XMLHttpRequest'}})
        .then(r=>r.json()).then(d => {
            document.getElementById('submitBtn').disabled = false;
            showToast(d.message,d.success?'success':'error');
            if(d.success){ bootstrap.Modal.getInstance(document.getElementById('annModal')).hide(); loadData(currentPage); }
        }).catch(()=>{ document.getElementById('submitBtn').disabled = false; });
});

let st; document.getElementById('searchInput').addEventListener('input', ()=>{ clearTimeout(st); st=setTimeout(()=>loadData(1),400); });
document.addEventListener('DOMContentLoaded', ()=>loadData());
</script>
@endpush
