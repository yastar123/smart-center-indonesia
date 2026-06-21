@extends('layouts.app')
@section('title', $title)
@section('page-title', $title)

@section('content')
<div class="container-fluid px-0">
    <div class="dashboard-card border-0 shadow-none rounded-0 mb-0">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
            <div>
                <div class="text-muted small fw-semibold">Owner / Monitoring Cabang</div>
                <h5 class="fw-bold mb-0">{{ $title }}</h5>
            </div>
            <a href="{{ route('owner.branches.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
        </div>

        <form method="POST" action="{{ $branch ? route('owner.branches.update', $branch) : route('owner.branches.store') }}">
            @csrf
            @if($branch)
                @method('PUT')
            @endif

            <div class="row g-4">
                <div class="col-12">
                    <div class="border rounded-0 p-4" style="background:var(--input-bg);border-color:var(--card-border)!important">
                        <h6 class="fw-bold mb-3 text-muted" style="font-size:12px;text-transform:uppercase;letter-spacing:.06em">
                            <i class="bi bi-building me-2 text-primary"></i>Info Cabang
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Nama Cabang <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" name="name" placeholder="Contoh: Cabang Jakarta" value="{{ old('name', $branch->name ?? '') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Kota</label>
                                <input type="text" class="form-control form-control-sm" name="city" placeholder="Jakarta" value="{{ old('city', $branch->city ?? '') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Kabupaten / Kecamatan</label>
                                <input type="text" class="form-control form-control-sm" name="regency" placeholder="Kebayoran Baru" value="{{ old('regency', $branch->regency ?? '') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Telepon</label>
                                <input type="text" class="form-control form-control-sm" name="phone" placeholder="021-xxxxxxxx" value="{{ old('phone', $branch->phone ?? '') }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-semibold">Alamat</label>
                                <input type="text" class="form-control form-control-sm" name="address" placeholder="Alamat lengkap cabang" value="{{ old('address', $branch->address ?? '') }}">
                            </div>
                            @if($branch)
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Status</label>
                                <select name="status" class="form-select form-select-sm">
                                    <option value="active" {{ old('status', $branch->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                                    <option value="inactive" {{ old('status', $branch->status) == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="border rounded-0 p-4" style="background:var(--input-bg);border-color:var(--card-border)!important">
                        <h6 class="fw-bold mb-3 text-muted" style="font-size:12px;text-transform:uppercase;letter-spacing:.06em">
                            <i class="bi bi-person-badge me-2 text-success"></i>Akun Login Cabang
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Nama Admin</label>
                                <input type="text" class="form-control form-control-sm" name="admin_name" placeholder="Nama admin cabang" value="{{ old('admin_name', optional(optional($branch)->admin)->name ?? '') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Username (opsional)</label>
                                <input type="text" class="form-control form-control-sm" name="admin_username" placeholder="admin.jakarta" value="{{ old('admin_username', optional(optional($branch)->admin)->username ?? '') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Email</label>
                                <input type="email" class="form-control form-control-sm" name="email" placeholder="admin@cabang.com" value="{{ old('email', optional(optional($branch)->admin)->email ?? optional($branch)->email ?? '') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Password {{ $branch ? '(kosongkan jika tidak diubah)' : '' }}</label>
                                <input type="password" class="form-control form-control-sm" name="password" placeholder="{{ $branch ? 'Kosongkan untuk tidak merubah' : 'Min. 8 karakter' }}" {{ $branch ? '' : 'required minlength=8' }}>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="border rounded-0 p-4" style="background:var(--input-bg);border-color:var(--card-border)!important">
                        <h6 class="fw-bold mb-3 text-muted" style="font-size:12px;text-transform:uppercase;letter-spacing:.06em">
                            <i class="bi bi-toggles me-2 text-warning"></i>Fitur Akses
                        </h6>
                        <div class="row g-2">
                            @php
                                $branchAllowed = optional($branch)->allowed_pages ?? [];
                                if (! is_array($branchAllowed) || empty($branchAllowed)) {
                                    $branchAllowed = [];
                                    if (!empty($branch) && $branch->can_students) $branchAllowed[] = 'student';
                                    if (!empty($branch) && $branch->can_teachers) $branchAllowed[] = 'teacher';
                                    if (!empty($branch) && $branch->can_schedules) $branchAllowed[] = 'schedule';
                                    if (!empty($branch) && $branch->can_payments) $branchAllowed[] = 'payment';
                                    if (!empty($branch) && $branch->can_tryouts) $branchAllowed[] = 'tryout';
                                }
                            @endphp

                            @if(!empty($menuStructure) && count($menuStructure))
                                @foreach($menuStructure as $section)
                                    <div class="col-12 mb-1"><strong class="small text-muted">{{ $section['section'] }}</strong></div>
                                    @foreach($section['items'] as $item)
                                        @php $checked = in_array($item['key'], (array)$branchAllowed); @endphp
                                        <div class="col-md-6">
                                            <div class="form-check p-3 rounded-0 d-flex align-items-center justify-content-between" style="background:var(--surface);border:1px solid var(--card-border);">
                                                <div class="d-flex align-items-center">
                                                    <input class="form-check-input me-2" type="checkbox" name="pages[]" id="page-{{ $item['key'] }}-{{ $branch->id ?? 'new' }}" value="{{ $item['key'] }}" {{ $checked ? 'checked' : '' }}>
                                                    <label class="form-check-label fw-semibold small mb-0" for="page-{{ $item['key'] }}-{{ $branch->id ?? 'new' }}">
                                                        <a href="{{ $item['url'] }}" target="_blank" class="text-decoration-none">{{ $item['label'] }}</a>
                                                    </label>
                                                </div>
                                                <span class="badge bg-secondary">{{ $item['count'] ?? '-' }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                @endforeach
                            @else
                                @foreach([
                                    ['student','Manajemen Siswa','people','primary'],
                                    ['teacher','Manajemen Guru','person-workspace','success'],
                                    ['schedule','Jadwal & Kelas','calendar-week','info'],
                                    ['payment','Keuangan','wallet2','warning'],
                                    ['tryout','Tryout CBT','ui-checks-grid','purple'],
                                ] as [$key, $label, $icon, $color])
                                    @php $checked = in_array($key, (array)$branchAllowed); @endphp
                                    <div class="col-md-6">
                                        <div class="form-check p-3 rounded-0" style="background:var(--surface);border:1px solid var(--card-border);">
                                            <input class="form-check-input" type="checkbox" name="pages[]" id="page-{{ $key }}-{{ $branch->id ?? 'new' }}" value="{{ $key }}" {{ $checked ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold small" for="page-{{ $key }}-{{ $branch->id ?? 'new' }}">
                                                <i class="bi bi-{{ $icon }} me-1 text-{{ $color==='purple'?'primary':$color }}"></i>{{ $label }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4 d-flex justify-content-end">
                <button class="btn btn-primary fw-semibold px-4">
                    <i class="bi bi-check-lg me-2"></i>{{ $branch ? 'Simpan Perubahan' : 'Simpan Cabang' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection