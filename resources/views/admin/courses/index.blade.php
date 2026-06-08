@extends('layouts.app')

@section('title', 'Mata Pelajaran')
@section('page-title', 'Mata Pelajaran')

@section('content')
<div>

    {{-- HEADER BANNER --}}
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
                <button onclick="openCategoryManager()" class="btn" style="background:rgba(255,255,255,.1);color:white;border:1px solid rgba(255,255,255,.2);border-radius:10px">
                    <i class="bi bi-tags me-1"></i>Kategori
                </button>
            </div>
        </div>
    </div>

    {{-- STAT CARDS --}}
    <div class="row g-3 mb-4">
        @php
            $statCards = [
                ['label'=>'Total Mapel', 'value'=>$stats['total'],   'icon'=>'bi-book-fill',      'topColor'=>'#10b981', 'textColor'=>'text-success', 'iconBg'=>'bg-success-soft'],
                ['label'=>'Mapel Aktif', 'value'=>$stats['aktif'],   'icon'=>'bi-check-circle-fill','topColor'=>'#c84ddf','textColor'=>'text-primary', 'iconBg'=>'bg-primary-soft'],
                ['label'=>'Tidak Aktif', 'value'=>$stats['nonaktif'],'icon'=>'bi-x-circle-fill',  'topColor'=>'#ef4444', 'textColor'=>'text-danger',  'iconBg'=>'bg-danger-soft'],
            ];
        @endphp
        @foreach($statCards as $i => $sc)
        <div class="col-6 col-lg-4 fade-up" style="animation-delay:{{ $i * 0.05 }}s">
            <div class="stat-card" style="border-top:3px solid {{ $sc['topColor'] }}">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-title">{{ $sc['label'] }}</div>
                        <div class="stat-value {{ $sc['textColor'] }} count-up" data-target="{{ $sc['value'] }}">{{ $sc['value'] }}</div>
                    </div>
                    <div class="stat-icon {{ $sc['iconBg'] }}" style="color:white">
                        <i class="bi {{ $sc['icon'] }}"></i>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- FILTERS --}}
    <div class="dashboard-card mb-4 fade-up">
        <div>
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-12 col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Cari kode atau nama mata pelajaran…" value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <select name="cabang_id" class="form-select">
                        <option value="">Semua Cabang</option>
                        @foreach($branches as $b)
                            <option value="{{ $b->id }}" {{ request('cabang_id')==$b->id?'selected':'' }}>{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ request('status')=='aktif'?'selected':'' }}>Aktif</option>
                        <option value="nonaktif" {{ request('status')=='nonaktif'?'selected':'' }}>Nonaktif</option>
                    </select>
                </div>
                <div class="col-12 col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">Filter</button>
                    @if(request()->hasAny(['search','cabang_id','status']))
                        <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x"></i></a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="dashboard-card fade-up">
        <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="thead-modern">
                        <tr>
                            <th class="px-4">#</th>
                            <th>Kode</th>
                            <th>Nama Mata Pelajaran</th>
                            <th class="d-none d-md-table-cell">Kategori</th>
                            <th class="d-none d-md-table-cell">Cabang</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($courses as $i => $course)
                        <tr style="transition:background .15s;">
                            <td class="px-4 text-muted" style="font-size:.85rem;">{{ $courses->firstItem() + $i }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @php $color = $course->warna ?: '#10b981'; @endphp
                                    <div style="width:36px;height:36px;border-radius:10px;background:{{ $color }}22;display:flex;align-items:center;justify-content:center;flex-shrink:0;overflow:hidden;">
                                        @if($course->icon)
                                            <img src="{{ asset('storage/'.$course->icon) }}" alt="" style="width:100%;height:100%;object-fit:cover;">
                                        @else
                                            <i class="bi bi-book" style="color:{{ $color }};font-size:.95rem;"></i>
                                        @endif
                                    </div>
                                    <span class="fw-semibold" style="font-size:.85rem;font-family:monospace;">{{ $course->kode }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold" style="font-size:.9rem;">{{ $course->nama }}</div>
                                @if($course->deskripsi)
                                <div class="text-muted" style="font-size:.78rem;">{{ Str::limit($course->deskripsi, 50) }}</div>
                                @endif
                            </td>
                            <td class="d-none d-md-table-cell">
                                @if($course->kategori)
                                <span class="badge rounded-pill" style="background:rgba(16,185,129,.15);color:#059669;font-size:.75rem;font-weight:600;">{{ $course->kategori }}</span>
                                @else
                                <span class="text-muted" style="font-size:.82rem;">—</span>
                                @endif
                            </td>
                            <td class="d-none d-md-table-cell">
                                <span style="font-size:.85rem;">{{ $course->cabang?->name ?? 'Semua Cabang' }}</span>
                            </td>
                            <td>
                                @if($course->status === 'aktif')
                                    <span class="badge rounded-pill" style="background:rgba(16,185,129,.15);color:#059669;font-size:.75rem;font-weight:600;padding:.35em .75em;">Aktif</span>
                                @else
                                    <span class="badge rounded-pill" style="background:rgba(239,68,68,.15);color:#dc2626;font-size:.75rem;font-weight:600;padding:.35em .75em;">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex gap-1 justify-content-end">
                                    <button class="btn btn-sm btn-act-edit" onclick="editCourse({{ $course->id }})" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-act-del" onclick="deleteCourse({{ $course->id }}, '{{ addslashes($course->nama) }}')" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div style="opacity:.5;">
                                    <i class="bi bi-book" style="font-size:2.5rem;display:block;margin-bottom:.5rem;color:#10b981;"></i>
                                    <div class="fw-semibold">Belum ada mata pelajaran</div>
                                    <small class="text-muted">Tambahkan mata pelajaran pertama Anda</small>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($courses->hasPages())
            <div class="mt-4 pt-3 d-flex justify-content-center" style="border-top:1px solid var(--card-border)">{{ $courses->links() }}</div>
            @endif
    </div>
</div>

{{-- MODAL: ADD / EDIT --}}
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
                    @csrf
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
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Kategori</label>
                            <select class="form-select" id="kategori">
                                <option value="">-- Pilih Kategori --</option>
                                <option value="Sains">Sains</option>
                                <option value="Bahasa">Bahasa</option>
                                <option value="Matematika">Matematika</option>
                                <option value="Sosial">Sosial</option>
                                <option value="Teknologi">Teknologi</option>
                                <option value="Seni">Seni</option>
                                <option value="Olahraga">Olahraga</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Cabang</label>
                            <select class="form-select" id="cabang_id">
                                <option value="">Semua Cabang</option>
                                @foreach($branches as $b)
                                <option value="{{ $b->id }}">{{ $b->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Gambar Mata Pelajaran</label>
                            <div class="d-flex align-items-center gap-2">
                                <div style="width:48px;height:48px;border-radius:8px;overflow:hidden;background:var(--soft-muted-bg);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <img id="iconPreviewImg" src="" alt="preview" style="width:100%;height:100%;object-fit:cover;display:none;">
                                    <i id="iconPreviewPlaceholder" class="bi bi-book text-muted" style="font-size:1.1rem"></i>
                                </div>
                                <input type="file" class="form-control" id="iconFile" accept="image/*">
                            </div>
                            <div class="form-text">Unggah gambar dari komputer (jpg, png, webp). Kosongkan untuk tidak mengganti.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Warna</label>
                            <div class="input-group">
                                <input type="color" class="form-control form-control-color" id="warna" value="#10b981" style="max-width:60px;">
                                <input type="text" class="form-control" id="warnaHex" placeholder="#10b981" oninput="document.getElementById('warna').value=this.value">
                            </div>
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

{{-- CATEGORY MANAGER MODAL --}}
<div class="modal fade" id="categoryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:20px;border:none">
            <div class="modal-header border-0 p-4" style="background:linear-gradient(135deg,#260632,#461256,#c84ddf);border-radius:20px 20px 0 0">
                <h5 class="modal-title fw-bold text-white"><i class="bi bi-tags me-2"></i>Kelola Kategori</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 d-flex gap-2">
                    <input type="text" id="catName" class="form-control" placeholder="Nama kategori baru...">
                    <button class="btn btn-primary px-3" id="catAddBtn" onclick="saveCategory()"><i class="bi bi-plus-lg"></i></button>
                </div>
                <div id="categoriesList">
                    <div class="text-center text-muted py-3"><div class="spinner-border spinner-border-sm text-primary me-2"></div>Memuat...</div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const modal = new bootstrap.Modal(document.getElementById('courseModal'));

function openModal() {
    document.getElementById('modalTitle').textContent = 'Tambah Mata Pelajaran';
    document.getElementById('courseId').value = '';
    document.getElementById('courseForm').reset();
    document.getElementById('warna').value = '#10b981';
    document.getElementById('warnaHex').value = '#10b981';
    // reset image preview
    const img = document.getElementById('iconPreviewImg');
    const ph = document.getElementById('iconPreviewPlaceholder');
    img.src = '';
    img.style.display = 'none';
    ph.style.display = 'inline-block';
    document.getElementById('iconFile').value = null;
    loadCategoriesForSelect().then(() => modal.show());
}

function editCourse(id) {
    document.getElementById('modalTitle').textContent = 'Edit Mata Pelajaran';
    document.getElementById('saveBtn').disabled = true;
    // Ensure categories are loaded into the select first, then load course
    loadCategoriesForSelect().then(() => {
        fetch(`/admin/courses/${id}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => { if (!r.ok) throw new Error(r.status); return r.json(); })
            .then(d => {
                document.getElementById('courseId').value = d.id;
                document.getElementById('kode').value = d.kode || '';
                document.getElementById('nama').value = d.nama || '';
                document.getElementById('kategori').value = d.kategori || '';
                document.getElementById('cabang_id').value = d.cabang_id || '';
                document.getElementById('iconFile').value = null;
                const img = document.getElementById('iconPreviewImg');
                const ph = document.getElementById('iconPreviewPlaceholder');
                if (d.icon_url) {
                    img.src = d.icon_url;
                    img.style.display = 'block';
                    ph.style.display = 'none';
                } else {
                    img.src = '';
                    img.style.display = 'none';
                    ph.style.display = 'inline-block';
                }
                const w = d.warna || '#10b981';
                document.getElementById('warna').value = w;
                document.getElementById('warnaHex').value = w;
                document.getElementById('deskripsi').value = d.deskripsi || '';
                document.getElementById('status').value = d.status || 'aktif';
                document.getElementById('saveBtn').disabled = false;
                modal.show();
            }).catch(() => {
                document.getElementById('saveBtn').disabled = false;
                showToast('Gagal memuat data mata pelajaran', 'error');
            });
    });
}

function loadCategoriesForSelect() {
    return new Promise((resolve) => {
        const sel = document.getElementById('kategori');
        // keep current placeholder
        sel.innerHTML = '<option value="">-- Pilih Kategori --</option>';
        fetch('/admin/categories', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.json())
            .then(d => {
                const items = d.data || [];
                if (!items.length) {
                    // fallback to existing static options
                    const fallback = ['Sains','Bahasa','Matematika','Sosial','Teknologi','Seni','Olahraga','Lainnya'];
                    fallback.forEach(f => {
                        const opt = document.createElement('option'); opt.value = f; opt.textContent = f; sel.appendChild(opt);
                    });
                    return resolve();
                }
                items.forEach(c => {
                    const opt = document.createElement('option');
                    opt.value = c.name;
                    opt.textContent = c.name;
                    sel.appendChild(opt);
                });
                resolve();
            }).catch(() => {
                // network error — keep static fallback
                const fallback = ['Sains','Bahasa','Matematika','Sosial','Teknologi','Seni','Olahraga','Lainnya'];
                fallback.forEach(f => {
                    const opt = document.createElement('option'); opt.value = f; opt.textContent = f; sel.appendChild(opt);
                });
                resolve();
            });
    });
}

document.getElementById('warna').addEventListener('input', function() {
    document.getElementById('warnaHex').value = this.value;
});

// preview selected image
document.getElementById('iconFile').addEventListener('change', function() {
    const file = this.files[0];
    const img = document.getElementById('iconPreviewImg');
    const ph = document.getElementById('iconPreviewPlaceholder');
    if (!file) {
        img.src = '';
        img.style.display = 'none';
        ph.style.display = 'inline-block';
        return;
    }
    const url = URL.createObjectURL(file);
    img.src = url;
    img.style.display = 'block';
    ph.style.display = 'none';
});

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
    formData.append('kategori', document.getElementById('kategori').value);
    formData.append('cabang_id', document.getElementById('cabang_id').value || '');
    formData.append('warna', document.getElementById('warna').value);
    formData.append('deskripsi', document.getElementById('deskripsi').value);
    formData.append('status', document.getElementById('status').value);
    const file = document.getElementById('iconFile').files[0];
    if (file) formData.append('icon', file);
    // CSRF
    formData.append('_token', '{{ csrf_token() }}');
    if (isEdit) formData.append('_method', 'PUT');

    fetch(url, {
        method: 'POST',
        body: formData,
    })
    .then(r => r.json())
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
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
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

// ---- CATEGORY MANAGER ----
const catModal = new bootstrap.Modal(document.getElementById('categoryModal'));
function openCategoryManager() {
    document.getElementById('catName').value = '';
    catModal.show();
    loadCategories();
}

function loadCategories() {
    const el = document.getElementById('categoriesList');
    el.innerHTML = '<div class="text-center text-muted py-3">Memuat...</div>';
    fetch('/admin/categories', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json())
        .then(d => {
            const items = d.data || [];
            if (!items.length) return el.innerHTML = '<div class="text-center text-muted py-3">Belum ada kategori</div>';
            el.innerHTML = items.map(c => `
                <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
                    <div>${c.name}</div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-secondary" onclick="editCategory(${c.id}, '${c.name.replace(/'/g,"\\'")}')">Edit</button>
                        <button class="btn btn-sm btn-outline-danger" onclick="removeCategory(${c.id})">Hapus</button>
                    </div>
                </div>
            `).join('');
        })
        .catch(() => el.innerHTML = '<div class="text-danger text-center py-3">Gagal memuat kategori</div>');
}

let editingCatId = null;
function saveCategory() {
    const name = document.getElementById('catName').value.trim();
    if (!name) return showToast('Nama kategori tidak boleh kosong.', 'warning');
    const btn = document.getElementById('catAddBtn');
    btn.disabled = true;
    const url = editingCatId ? `/admin/categories/${editingCatId}` : '/admin/categories';
    const method = editingCatId ? 'PUT' : 'POST';
    fetch(url, {
        method,
        headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept':'application/json' },
        body: JSON.stringify({ name })
    }).then(r => r.json())
    .then(res => {
        btn.disabled = false;
        if (res.success) {
            document.getElementById('catName').value = '';
            editingCatId = null;
            loadCategories();
            window.showToast && window.showToast(res.message, 'success');
        } else {
            showToast(res.message || 'Terjadi kesalahan', 'error');
        }
    }).catch(() => { btn.disabled = false; showToast('Tidak dapat menghubungi server.', 'error'); });
}

function editCategory(id, name) {
    editingCatId = id;
    document.getElementById('catName').value = name;
}

function removeCategory(id) {
    confirmAction('Hapus kategori ini? Data tidak dapat dikembalikan.', function() {
        fetch(`/admin/categories/${id}`, { method:'DELETE', headers:{ 'X-CSRF-TOKEN':'{{ csrf_token() }}', 'Accept':'application/json' } })
            .then(r => r.json())
            .then(res => { if (res.success) { loadCategories(); showToast(res.message, 'success'); } });
    }, null, {title:'Hapus Kategori', okText:'Ya, Hapus'});
}
</script>
@endpush
@endsection
