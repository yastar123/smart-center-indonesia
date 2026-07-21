@extends('layouts.app')
@section('title','Artikel')
@section('page-title','Manajemen Artikel')

@section('content')
<div>

{{-- HEADER --}}
<div class="dashboard-card mb-4 fade-up" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
    <div class="row align-items-center g-3" style="position:relative">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-3 mb-2">
                <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0"><i class="bi bi-newspaper"></i></div>
                <div>
                    <h5 class="fw-bold mb-0" style="color:white">Manajemen Artikel</h5>
                    <span style="font-size:12px;opacity:.8">Buat dan kelola konten artikel yang tampil di halaman publik</span>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('admin.articles.create') }}" class="btn fw-semibold px-4" style="background:rgba(255,255,255,.2);color:white;border:1px solid rgba(255,255,255,.3);border-radius:10px">
                <i class="bi bi-plus-lg me-2"></i>Tulis Artikel
            </a>
        </div>
    </div>
</div>

{{-- STATS --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4"><div class="stat-card" style="border-top:3px solid #c84ddf"><div class="d-flex justify-content-between"><div><div class="stat-title">Total</div><div class="stat-value" id="statTotal">–</div></div><div class="stat-icon bg-primary-soft" style="color:white"><i class="bi bi-newspaper"></i></div></div></div></div>
    <div class="col-6 col-md-4"><div class="stat-card" style="border-top:3px solid #10b981"><div class="d-flex justify-content-between"><div><div class="stat-title">Published</div><div class="stat-value" id="statPublished">–</div></div><div class="stat-icon bg-success-soft" style="color:white"><i class="bi bi-check-circle"></i></div></div></div></div>
    <div class="col-12 col-md-4"><div class="stat-card" style="border-top:3px solid #f59e0b"><div class="d-flex justify-content-between"><div><div class="stat-title">Draft</div><div class="stat-value" id="statDraft">–</div></div><div class="stat-icon bg-warning-soft" style="color:white"><i class="bi bi-pencil-square"></i></div></div></div></div>
</div>

{{-- FILTERS --}}
<div class="dashboard-card mb-4">
    <div class="row g-2">
        <div class="col-12 col-md-4"><div class="input-group"><span class="input-group-text"><i class="bi bi-search"></i></span><input type="text" id="searchInput" class="form-control" placeholder="Cari artikel..."></div></div>
        <div class="col-6 col-md-3"><select id="filterKategori" class="form-select"><option value="">Semua Kategori</option><option value="tips">Tips & Trik</option><option value="berita">Berita</option><option value="akademik">Akademik</option><option value="promo">Promo</option><option value="lainnya">Lainnya</option></select></div>
        <div class="col-6 col-md-2"><select id="filterStatus" class="form-select"><option value="">Semua Status</option><option value="published">Published</option><option value="draft">Draft</option></select></div>
        <div class="col-6 col-md-2"><button onclick="loadData()" class="btn btn-primary w-100"><i class="bi bi-funnel me-1"></i>Filter</button></div>
        <div class="col-6 col-md-1"><button onclick="resetFilter()" class="btn btn-outline-secondary w-100" title="Reset" aria-label="Reset filter"><i class="bi bi-x-lg"></i></button></div>
    </div>
</div>

{{-- GRID --}}
<div id="articlesGrid" class="row g-3 mb-3">
    <div class="col-12 text-center py-5"><div class="spinner-border text-primary"></div></div>
</div>
<div id="paginationLinks" class="d-flex justify-content-center mb-4"></div>

</div>

{{-- ── MODAL CREATE / EDIT ── --}}
<div class="modal fade" id="articleModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content" style="border-radius:20px;border:none">
            <div class="modal-header border-0 pb-0" style="background:linear-gradient(135deg,#260632,#461256,#c84ddf);border-radius:20px 20px 0 0;padding:20px 24px">
                <h5 class="modal-title fw-bold text-white" id="modalTitle"><i class="bi bi-newspaper me-2"></i>Tulis Artikel</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="articleForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="articleId">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        {{-- Judul --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">Judul Artikel <span class="text-danger">*</span></label>
                            <input type="text" name="judul" id="fieldJudul" class="form-control form-control-lg" placeholder="Tulis judul artikel yang menarik..." required>
                        </div>
                        {{-- Kategori + Status --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                            <select name="kategori" id="fieldKategori" class="form-select">
                                <option value="berita">Berita</option>
                                <option value="tips">Tips & Trik</option>
                                <option value="akademik">Akademik</option>
                                <option value="promo">Promo</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <select name="status" id="fieldStatus" class="form-select">
                                <option value="draft">Draft</option>
                                <option value="published">Published</option>
                            </select>
                        </div>
                        {{-- Ringkasan --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">Ringkasan <small class="text-muted fw-normal">(tampil di listing)</small></label>
                            <textarea name="ringkasan" id="fieldRingkasan" class="form-control" rows="2" placeholder="Deskripsi singkat artikel (max 500 karakter)..." maxlength="500"></textarea>
                        </div>
                        {{-- Konten --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">Konten Artikel <span class="text-danger">*</span></label>
                            <textarea name="konten" id="fieldKonten" class="form-control" rows="14" placeholder="Tulis isi artikel di sini..." required></textarea>
                            <div class="form-text">Mendukung HTML dasar: &lt;b&gt;, &lt;i&gt;, &lt;p&gt;, &lt;ul&gt;, &lt;ol&gt;, &lt;h2&gt;–&lt;h4&gt;, &lt;blockquote&gt;, &lt;a&gt;</div>
                        </div>
                        {{-- Thumbnail --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">Thumbnail</label>
                            <input type="file" name="thumbnail" id="fieldThumbnail" class="form-control" accept="image/*">
                            <div id="thumbPreviewWrap" class="mt-2 d-none">
                                <img id="thumbPreview" src="" alt="Preview" style="height:120px;border-radius:10px;object-fit:cover;border:2px solid #e5e7eb">
                                <button type="button" onclick="clearThumb()" class="btn btn-sm btn-outline-danger ms-2">Hapus</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 gap-2">
                    <a id="btnPreviewPublic" href="#" target="_blank" class="btn btn-outline-secondary d-none"><i class="bi bi-eye me-1"></i>Lihat Publik</a>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i>Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ── MODAL DETAIL ── --}}
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content" style="border-radius:20px;border:none">
            <div class="modal-header border-0 pb-0" style="background:linear-gradient(135deg,#260632,#461256,#c84ddf);border-radius:20px 20px 0 0;padding:20px 24px">
                <h5 class="modal-title fw-bold text-white" id="detailTitle"><i class="bi bi-eye me-2"></i>Detail Artikel</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" id="detailBody">
                <div class="text-center py-4"><div class="spinner-border text-primary"></div></div>
            </div>
            <div class="modal-footer border-0 px-4 pb-4">
                <a id="detailPublicLink" href="#" target="_blank" class="btn btn-outline-primary"><i class="bi bi-box-arrow-up-right me-1"></i>Lihat di Publik</a>
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const BASE = '{{ route("admin.articles.index") }}';
let editingId = null;

const katLabel = { tips:'Tips & Trik', berita:'Berita', akademik:'Akademik', promo:'Promo', lainnya:'Lainnya' };
const katColor = { tips:'#c84ddf', berita:'#2563eb', akademik:'#10b981', promo:'#f59e0b', lainnya:'#6b7280' };

/* ── Load data ── */
function loadData(page = 1) {
    const search   = document.getElementById('searchInput').value;
    const kategori = document.getElementById('filterKategori').value;
    const status   = document.getElementById('filterStatus').value;
    const params   = new URLSearchParams({ page, search, kategori, status });

    fetch(`${BASE}?${params}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => { if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
        .then(d => {
            document.getElementById('statTotal').textContent     = d.stats.total;
            document.getElementById('statPublished').textContent = d.stats.published;
            document.getElementById('statDraft').textContent     = d.stats.draft;
            renderGrid(d.data);
            renderPagination(d);
        })
        .catch(() => showToast('Gagal memuat data', 'error'));
}

function renderGrid(items) {
    const el = document.getElementById('articlesGrid');
    if (!items.length) {
        el.innerHTML = `<div class="col-12 text-center py-5 text-muted"><i class="bi bi-newspaper" style="font-size:3rem;opacity:.3"></i><p class="mt-3">Belum ada artikel</p></div>`;
        return;
    }
    el.innerHTML = items.map(a => {
        const badge = `<span style="font-size:.7rem;font-weight:700;padding:2px 10px;border-radius:50px;background:${katColor[a.kategori]}22;color:${katColor[a.kategori]}">${katLabel[a.kategori]}</span>`;
        const statusBadge = a.status === 'published'
            ? `<span class="badge" style="background:#10b98122;color:#10b981;font-size:.68rem">Published</span>`
            : `<span class="badge" style="background:#f59e0b22;color:#f59e0b;font-size:.68rem">Draft</span>`;
        const thumb = a.thumbnail
            ? `/storage/${a.thumbnail}`
            : 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?auto=format&fit=crop&w=400&q=50';
        const dateStr = a.published_at ? new Date(a.published_at).toLocaleDateString('id-ID',{day:'2-digit',month:'short',year:'numeric'}) : '–';
        return `
        <div class="col-md-6 col-xl-4">
            <div class="dashboard-card h-100 d-flex flex-column p-0 overflow-hidden" style="border-radius:16px">
                <div style="height:160px;overflow:hidden;flex-shrink:0;position:relative">
                    <img src="${thumb}" alt="" style="width:100%;height:100%;object-fit:cover">
                    <div style="position:absolute;top:10px;left:10px;display:flex;gap:6px">${badge} ${statusBadge}</div>
                </div>
                <div class="p-3 d-flex flex-column flex-grow-1">
                    <h6 class="fw-bold mb-1" style="line-height:1.3;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden">${a.judul}</h6>
                    <p class="text-muted small mb-2 flex-grow-1" style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden">${a.ringkasan || '–'}</p>
                    <div class="d-flex align-items-center justify-content-between mt-auto pt-2 border-top">
                        <small class="text-muted"><i class="bi bi-calendar3 me-1"></i>${dateStr} &nbsp;<i class="bi bi-eye me-1"></i>${a.views}</small>
                        <div class="d-flex gap-1">
                            <button onclick="showDetail(${a.id})" class="btn btn-sm" style="background:#c84ddf22;color:#c84ddf;border:none;border-radius:8px" title="Detail"><i class="bi bi-eye"></i></button>
                            <button onclick="openEdit(${a.id})" class="btn btn-sm btn-outline-primary" style="border-radius:8px" title="Edit"><i class="bi bi-pencil"></i></button>
                            <button onclick="deleteArticle(${a.id},'${a.judul.replace(/'/g,"\\'")}')" class="btn btn-sm btn-outline-danger" style="border-radius:8px" title="Hapus"><i class="bi bi-trash"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;
    }).join('');
}

function renderPagination(d) {
    if (d.last_page <= 1) { document.getElementById('paginationLinks').innerHTML = ''; return; }
    let html = '<nav><ul class="pagination pagination-sm mb-0">';
    for (let p = 1; p <= d.last_page; p++) {
        html += `<li class="page-item ${p === d.current_page ? 'active' : ''}"><button class="page-link" onclick="loadData(${p})">${p}</button></li>`;
    }
    html += '</ul></nav>';
    document.getElementById('paginationLinks').innerHTML = html;
}

/* ── Modal open / edit ── */
function openModal() {
    editingId = null;
    document.getElementById('modalTitle').innerHTML = '<i class="bi bi-newspaper me-2"></i>Tulis Artikel';
    document.getElementById('articleForm').reset();
    document.getElementById('articleId').value = '';
    document.getElementById('thumbPreviewWrap').classList.add('d-none');
    document.getElementById('btnPreviewPublic').classList.add('d-none');
    new bootstrap.Modal(document.getElementById('articleModal')).show();
}

function openEdit(id) {
    editingId = id;
    document.getElementById('modalTitle').innerHTML = '<i class="bi bi-pencil me-2"></i>Edit Artikel';
    fetch(`${BASE}/${id}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => { if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
        .then(d => {
            const a = d.data;
            document.getElementById('articleId').value       = a.id;
            document.getElementById('fieldJudul').value      = a.judul;
            document.getElementById('fieldRingkasan').value  = a.ringkasan || '';
            document.getElementById('fieldKonten').value     = a.konten;
            document.getElementById('fieldKategori').value   = a.kategori;
            document.getElementById('fieldStatus').value     = a.status;
            if (a.thumbnail) {
                document.getElementById('thumbPreview').src = `/storage/${a.thumbnail}`;
                document.getElementById('thumbPreviewWrap').classList.remove('d-none');
            } else {
                document.getElementById('thumbPreviewWrap').classList.add('d-none');
            }
            const pubLink = document.getElementById('btnPreviewPublic');
            pubLink.href = `/artikel/${a.slug}`;
            pubLink.classList.remove('d-none');
            new bootstrap.Modal(document.getElementById('articleModal')).show();
        })
        .catch(() => showToast('Gagal memuat data artikel', 'error'));
}

/* ── Submit ── */
document.getElementById('articleForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const id  = document.getElementById('articleId').value;
    const url = id ? `${BASE}/${id}` : BASE;
    const fd  = new FormData(this);
    if (id) fd.append('_method', 'PUT');

    fetch(url, { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
        .then(r => { if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
        .then(d => {
            if (d.success) {
                showToast(d.message, 'success');
                bootstrap.Modal.getInstance(document.getElementById('articleModal'))?.hide();
                loadData();
            } else {
                showToast(d.message || 'Terjadi kesalahan', 'error');
            }
        })
        .catch(() => showToast('Gagal menyimpan artikel', 'error'));
});

/* ── Detail ── */
function showDetail(id) {
    document.getElementById('detailBody').innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary"></div></div>';
    const m = new bootstrap.Modal(document.getElementById('detailModal'));
    m.show();
    fetch(`${BASE}/${id}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => { if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
        .then(d => {
            const a = d.data;
            const thumb = a.thumbnail ? `/storage/${a.thumbnail}` : 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?auto=format&fit=crop&w=800&q=60';
            const dateStr = a.published_at ? new Date(a.published_at).toLocaleDateString('id-ID',{day:'2-digit',month:'long',year:'numeric'}) : 'Belum dipublish';
            document.getElementById('detailTitle').innerHTML = `<i class="bi bi-eye me-2"></i>${a.judul}`;
            document.getElementById('detailPublicLink').href = `/artikel/${a.slug}`;
            document.getElementById('detailBody').innerHTML = `
                <img src="${thumb}" alt="" style="width:100%;height:220px;object-fit:cover;border-radius:12px;margin-bottom:1.25rem">
                <div class="d-flex gap-2 flex-wrap mb-3">
                    <span style="font-size:.75rem;font-weight:700;padding:3px 12px;border-radius:50px;background:${katColor[a.kategori]}22;color:${katColor[a.kategori]}">${katLabel[a.kategori]}</span>
                    <span class="badge ${a.status==='published' ? 'bg-success' : 'bg-warning text-dark'}">${a.status === 'published' ? 'Published' : 'Draft'}</span>
                </div>
                <div class="d-flex gap-4 text-muted small mb-3">
                    <span><i class="bi bi-person me-1"></i>${a.penulis?.name || '–'}</span>
                    <span><i class="bi bi-calendar3 me-1"></i>${dateStr}</span>
                    <span><i class="bi bi-eye me-1"></i>${a.views} views</span>
                </div>
                ${a.ringkasan ? `<div class="alert alert-light border" style="border-radius:10px;font-size:.9rem;font-style:italic">${a.ringkasan}</div>` : ''}
                <hr>
                <div style="line-height:1.8;font-size:.95rem">${a.konten}</div>`;
        })
        .catch(() => showToast('Gagal memuat detail artikel', 'error'));
}

/* ── Delete ── */
function deleteArticle(id, judul) {
    if (!confirm(`Hapus artikel "${judul}"? Tindakan ini tidak bisa dibatalkan.`)) return;
    fetch(`${BASE}/${id}`, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/x-www-form-urlencoded' }, body: '_method=DELETE' })
        .then(r => { if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
        .then(d => { if (d.success) { showToast(d.message, 'success'); loadData(); } })
        .catch(() => showToast('Gagal menghapus artikel', 'error'));
}

/* ── Thumbnail preview ── */
document.getElementById('fieldThumbnail').addEventListener('change', function() {
    if (!this.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('thumbPreview').src = e.target.result;
        document.getElementById('thumbPreviewWrap').classList.remove('d-none');
    };
    reader.readAsDataURL(this.files[0]);
});
function clearThumb() {
    document.getElementById('fieldThumbnail').value = '';
    document.getElementById('thumbPreviewWrap').classList.add('d-none');
}

function resetFilter() {
    document.getElementById('searchInput').value = '';
    document.getElementById('filterKategori').value = '';
    document.getElementById('filterStatus').value = '';
    loadData();
}

document.getElementById('searchInput').addEventListener('keydown', e => { if (e.key === 'Enter') loadData(); });

loadData();
</script>
@endpush
