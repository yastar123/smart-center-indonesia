@extends('layouts.app')
@section('page-title', 'Kelola Cabang Landing')

@section('content')
<div class="container-fluid px-0">

    {{-- HEADER --}}
    <div class="dashboard-card mb-4 fade-up" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
        <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
        <div class="row align-items-center g-3" style="position:relative">
            <div class="col-md-8">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0" style="color:white">Kelola Cabang Landing Page</h5>
                        <span style="font-size:12px;opacity:.8">Tambah, edit, hapus cabang &amp; atur konten landing per cabang</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-md-end d-flex gap-2 justify-content-md-end">
                <a href="{{ route('admin.landing.index') }}" class="btn fw-semibold px-3"
                   style="background:rgba(255,255,255,.15);color:white;border:1px solid rgba(255,255,255,.25);border-radius:10px">
                    <i class="bi bi-arrow-left me-1"></i>Landing Utama
                </a>
                <button class="btn fw-semibold px-3"
                        style="background:rgba(255,255,255,.9);color:#260632;border:none;border-radius:10px"
                        onclick="openCreateModal()">
                    <i class="bi bi-plus-circle me-1"></i>Tambah Cabang
                </button>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ $errors->first() }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Info strip --}}
    <div class="alert border-0 mb-4 d-flex align-items-start gap-3" style="background:rgba(200,77,223,.06);border-left:4px solid #c84ddf !important;border-radius:12px">
        <i class="bi bi-info-circle mt-1" style="color:var(--bs-primary);font-size:1.2rem;flex-shrink:0"></i>
        <div class="small text-muted">
            Data cabang ditampilkan di <strong>Landing Page</strong> bagian Cabang SCI Indonesia (foto + nama + tombol Lihat Detail).
            Upload foto untuk setiap cabang agar tampil menarik di halaman publik.<br>
            Klik <strong>Edit Landing</strong> untuk mengatur konten detail halaman cabang (hero, harga, FAQ, dsb).
        </div>
    </div>

    {{-- Branch grid --}}
    <div class="row g-3" id="branchGrid">
        @forelse($branches as $branch)
        @php
            $city = $branch->city ?: $branch->name;
            $isAktif = in_array($branch->status, ['aktif','active']);
        @endphp
        <div class="col-md-6 col-xl-4">
            <div class="card h-100" style="border:1px solid rgba(200,77,223,.12);border-radius:16px;box-shadow:0 2px 12px rgba(38,6,50,.05);overflow:hidden">

                {{-- Photo preview --}}
                <div style="height:180px;background:#1a0828;position:relative;overflow:hidden">
                    @if($branch->photo)
                    <img src="{{ str_starts_with($branch->photo,'http') ? $branch->photo : asset($branch->photo) }}"
                         alt="{{ $branch->name }}"
                         style="width:100%;height:100%;object-fit:cover;opacity:.9">
                    @else
                    <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#260632,#461256)">
                        <div style="text-align:center;color:rgba(255,255,255,.3)">
                            <i class="bi bi-image" style="font-size:2.5rem;display:block;margin-bottom:6px"></i>
                            <span style="font-size:12px">Belum ada foto</span>
                        </div>
                    </div>
                    @endif
                    <div style="position:absolute;top:10px;right:10px">
                        <span style="font-size:.65rem;font-weight:700;padding:3px 9px;border-radius:50px;background:{{ $isAktif ? 'rgba(16,185,129,.85)' : 'rgba(107,114,128,.85)' }};color:white;backdrop-filter:blur(4px)">
                            {{ $isAktif ? 'AKTIF' : 'NONAKTIF' }}
                        </span>
                    </div>
                </div>

                {{-- Card header --}}
                <div style="background:linear-gradient(135deg,#260632 0%,#461256 70%,#c84ddf 100%);padding:.9rem 1.25rem;display:flex;align-items:center;justify-content:space-between">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width:32px;height:32px;border-radius:8px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:.9rem;flex-shrink:0">
                            <i class="bi bi-building" style="color:white"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-white" style="font-size:.9rem;line-height:1.2">{{ $branch->name }}</div>
                            <div style="font-size:.7rem;color:rgba(255,255,255,.65)">{{ $city }}</div>
                        </div>
                    </div>
                </div>

                <div class="card-body d-flex flex-column gap-2 p-3">
                    {{-- Meta --}}
                    <div class="d-flex flex-column gap-1">
                        @if($branch->phone)
                        <div class="d-flex align-items-center gap-2 small text-muted">
                            <i class="bi bi-telephone-fill" style="color:var(--bs-primary);font-size:.75rem;width:14px"></i>
                            {{ $branch->phone }}
                        </div>
                        @endif
                        @if($branch->email)
                        <div class="d-flex align-items-center gap-2 small text-muted">
                            <i class="bi bi-envelope-fill" style="color:var(--bs-primary);font-size:.75rem;width:14px"></i>
                            {{ $branch->email }}
                        </div>
                        @endif
                        @if($branch->address)
                        <div class="d-flex align-items-start gap-2 small text-muted">
                            <i class="bi bi-geo-alt-fill" style="color:var(--bs-primary);font-size:.75rem;width:14px;margin-top:2px"></i>
                            <span>{{ Str::limit($branch->address, 60) }}</span>
                        </div>
                        @endif
                    </div>

                    {{-- Actions --}}
                    <div class="d-flex gap-2 mt-auto flex-wrap">
                        <a href="{{ route('admin.landing.cabang.show', $branch) }}"
                           class="btn btn-primary btn-sm flex-fill fw-semibold">
                            <i class="bi bi-pencil-square me-1"></i>Edit Landing
                        </a>
                        <button class="btn btn-sm fw-semibold"
                                style="background:rgba(200,77,223,.08);color:var(--bs-primary);border:1px solid rgba(200,77,223,.2)"
                                onclick="openEditModal({{ $branch->id }}, {{ json_encode($branch->name) }}, {{ json_encode($branch->city ?? '') }}, {{ json_encode($branch->address ?? '') }}, {{ json_encode($branch->phone ?? '') }}, {{ json_encode($branch->email ?? '') }}, {{ json_encode($branch->status ?? 'aktif') }}, {{ json_encode($branch->photo ?? '') }})">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <a href="{{ route('cabang.show', $branch) }}" target="_blank"
                           class="btn btn-sm fw-semibold"
                           style="background:rgba(16,185,129,.08);color:#059669;border:1px solid rgba(16,185,129,.2)">
                            <i class="bi bi-eye"></i>
                        </a>
                        <button class="btn btn-sm fw-semibold"
                                style="background:rgba(239,68,68,.08);color:#dc2626;border:1px solid rgba(239,68,68,.2)"
                                onclick="confirmDelete({{ $branch->id }}, {{ json_encode($branch->name) }})">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5 text-muted">
                <i class="bi bi-buildings" style="font-size:3rem;opacity:.3"></i>
                <p class="mt-2">Belum ada cabang. Klik <strong>Tambah Cabang</strong> untuk memulai.</p>
            </div>
        </div>
        @endforelse
    </div>

</div>

{{-- ── CREATE MODAL ──────────────────────────────────────────────────────── --}}
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius:16px;border:none">
            <div class="modal-header" style="background:linear-gradient(135deg,#260632,#461256);color:white;border-radius:16px 16px 0 0;border:none">
                <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle me-2"></i>Tambah Cabang Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.landing.cabang.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:13px">Nama Cabang <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="SCI Jakarta Selatan" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:13px">Kota</label>
                            <input type="text" name="city" class="form-control" placeholder="Jakarta Selatan">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:13px">No. HP / WhatsApp</label>
                            <input type="text" name="phone" class="form-control" placeholder="08123456789">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:13px">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="cabang@akademi.com">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold" style="font-size:13px">Alamat</label>
                            <textarea name="address" class="form-control" rows="2" placeholder="Jl. Contoh No. 1, Jakarta Selatan"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:13px">Status</label>
                            <select name="status" class="form-select">
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Nonaktif</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:13px">Foto Cabang (untuk Landing Page)</label>
                            <input type="file" name="photo" class="form-control" accept="image/*" onchange="previewImg(this,'createPreview')">
                        </div>
                        <div class="col-12">
                            <div id="createPreview" style="display:none;margin-top:8px">
                                <img id="createPreviewImg" src="" alt="Preview" style="max-height:180px;border-radius:10px;object-fit:cover;width:100%">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-semibold px-4">
                        <i class="bi bi-plus-circle me-1"></i>Tambah Cabang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ── EDIT MODAL ────────────────────────────────────────────────────────── --}}
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius:16px;border:none">
            <div class="modal-header" style="background:linear-gradient(135deg,#260632,#461256);color:white;border-radius:16px 16px 0 0;border:none">
                <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Edit Cabang</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editForm" action="" method="POST" enctype="multipart/form-data">
                @csrf
                @method('POST')
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:13px">Nama Cabang <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="editName" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:13px">Kota</label>
                            <input type="text" name="city" id="editCity" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:13px">No. HP / WhatsApp</label>
                            <input type="text" name="phone" id="editPhone" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:13px">Email</label>
                            <input type="email" name="email" id="editEmail" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold" style="font-size:13px">Alamat</label>
                            <textarea name="address" id="editAddress" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:13px">Status</label>
                            <select name="status" id="editStatus" class="form-select">
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Nonaktif</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:13px">Ganti Foto Cabang</label>
                            <input type="file" name="photo" class="form-control" accept="image/*" onchange="previewImg(this,'editPreview')">
                        </div>
                        <div class="col-12">
                            <div id="editPreview" style="margin-top:8px">
                                <img id="editPreviewImg" src="" alt="Preview foto saat ini" style="max-height:180px;border-radius:10px;object-fit:cover;width:100%;display:none">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-semibold px-4">
                        <i class="bi bi-check-circle me-1"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ── DELETE MODAL ──────────────────────────────────────────────────────── --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content" style="border-radius:16px;border:none">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-danger"><i class="bi bi-trash me-2"></i>Hapus Cabang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small mb-0">Hapus cabang <strong id="deleteName"></strong>? Seluruh data terkait (siswa, guru, jadwal, dll) akan ikut dihapus. Tindakan ini <strong class="text-danger">tidak dapat diurungkan</strong>.</p>
            </div>
            <div class="modal-footer border-0 pt-0">
                <form id="deleteForm" action="" method="POST" class="w-100 d-flex gap-2">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary flex-fill" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger flex-fill fw-semibold">
                        <i class="bi bi-trash me-1"></i>Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openCreateModal() {
    new bootstrap.Modal(document.getElementById('createModal')).show();
}

function openEditModal(id, name, city, address, phone, email, status, photo) {
    const form = document.getElementById('editForm');
    form.action = '/admin/landing/cabang/' + id + '/info';
    document.getElementById('editName').value    = name;
    document.getElementById('editCity').value    = city;
    document.getElementById('editAddress').value = address;
    document.getElementById('editPhone').value   = phone;
    document.getElementById('editEmail').value   = email;
    document.getElementById('editStatus').value  = status;

    const img = document.getElementById('editPreviewImg');
    if (photo) {
        img.src = photo;
        img.style.display = 'block';
    } else {
        img.style.display = 'none';
    }

    new bootstrap.Modal(document.getElementById('editModal')).show();
}

function confirmDelete(id, name) {
    document.getElementById('deleteName').textContent = name;
    document.getElementById('deleteForm').action = '/admin/landing/cabang/' + id;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

function previewImg(input, containerId) {
    const container = document.getElementById(containerId);
    const imgId = containerId === 'createPreview' ? 'createPreviewImg' : 'editPreviewImg';
    const img = document.getElementById(imgId);
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            img.src = e.target.result;
            img.style.display = 'block';
            container.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
@endsection
