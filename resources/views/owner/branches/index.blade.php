```blade
@extends('layouts.app')

@section('content')

<div class="container-fluid py-3">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">Monitoring Cabang</h3>
            <small class="text-muted">Owner Panel - Semua Cabang Akademi</small>
        </div>

        <button class="btn btn-primary shadow-sm"
            data-bs-toggle="modal"
            data-bs-target="#addModal">
            <i class="bi bi-plus-circle"></i> Tambah Cabang
        </button>
    </div>

    {{-- STATISTIK --}}
    <div class="row g-3 mb-4">

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <small class="text-muted">Total Cabang</small>
                    <h2 class="fw-bold">{{ $total }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <small class="text-muted">Cabang Aktif</small>
                    <h2 class="fw-bold text-success">{{ $active }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <small class="text-muted">Total Siswa</small>
                    <h2 class="fw-bold text-primary">{{ $students }}</h2>
                </div>
            </div>
        </div>

    </div>

    {{-- TABLE --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body table-responsive">

            <table class="table align-middle table-hover">

                <thead class="table-light">
                    <tr>
                        <th>Nama Cabang</th>
                        <th>Kota</th>
                        <th>Siswa</th>
                        <th>Email</th>
                        <th>Fitur</th>
                        <th>Status</th>
                        <th width="260">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($branches as $branch)

                    <tr>

                        <td class="fw-semibold">
                            {{ $branch->name }}
                        </td>

                        <td>{{ $branch->city }}</td>

                        <td>
                            <span class="badge bg-primary">
                                {{ $branch->students->count() }} Siswa
                            </span>
                        </td>

                        <td>{{ $branch->email }}</td>

                        {{-- FITUR --}}
                        <td>

                            @if($branch->can_students)
                                <span class="badge rounded-pill bg-primary">
                                    Siswa
                                </span>
                            @endif

                            @if($branch->can_teachers)
                                <span class="badge rounded-pill bg-success">
                                    Guru
                                </span>
                            @endif

                            @if($branch->can_schedules)
                                <span class="badge rounded-pill bg-info">
                                    Jadwal
                                </span>
                            @endif

                            @if($branch->can_payments)
                                <span class="badge rounded-pill bg-warning text-dark">
                                    Keuangan
                                </span>
                            @endif

                            @if($branch->can_tryouts)
                                <span class="badge rounded-pill bg-dark">
                                    Tryout
                                </span>
                            @endif

                        </td>

                        {{-- STATUS --}}
                        <td>

                            @if($branch->status == 'active')
                                <span class="badge bg-success">
                                    Active
                                </span>
                            @else
                                <span class="badge bg-danger">
                                    Inactive
                                </span>
                            @endif

                        </td>

                        {{-- AKSI --}}
                        <td class="d-flex gap-2 flex-wrap">

                            {{-- EDIT --}}
                            <button class="btn btn-warning btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#editModal{{ $branch->id }}">
                                <i class="bi bi-pencil-square"></i>
                            </button>

                            {{-- RESET PASSWORD --}}
                            <button class="btn btn-info btn-sm text-white"
                                data-bs-toggle="modal"
                                data-bs-target="#resetModal{{ $branch->id }}">
                                <i class="bi bi-key"></i>
                            </button>

                            {{-- DELETE --}}
                            <form method="POST"
                                action="{{ route('owner.branches.destroy', $branch) }}"
                                onsubmit="return confirm('Hapus cabang ini ?')">

                                @csrf
                                @method('DELETE')

                                <button class="btn btn-danger btn-sm">
                                    <i class="bi bi-trash"></i>
                                </button>

                            </form>

                        </td>

                    </tr>

                    {{-- EDIT MODAL --}}
                    <div class="modal fade" id="editModal{{ $branch->id }}">
                        <div class="modal-dialog">
                            <div class="modal-content border-0 rounded-4">

                                <div class="modal-header">
                                    <h5>Edit Cabang</h5>

                                    <button class="btn-close"
                                        data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">

                                    <form method="POST"
                                        action="{{ route('owner.branches.update', $branch) }}">

                                        @csrf
                                        @method('PUT')

                                        <label class="mb-1">Nama Cabang</label>
                                        <input type="text"
                                            class="form-control mb-3"
                                            name="name"
                                            value="{{ $branch->name }}">

                                        <label class="mb-1">Kota</label>
                                        <input type="text"
                                            class="form-control mb-3"
                                            name="city"
                                            value="{{ $branch->city }}">

                                        <label class="mb-1">Status</label>

                                        <select name="status"
                                            class="form-select mb-3">

                                            <option value="active"
                                                {{ $branch->status=='active'?'selected':'' }}>
                                                Active
                                            </option>

                                            <option value="inactive"
                                                {{ $branch->status=='inactive'?'selected':'' }}>
                                                Inactive
                                            </option>

                                        </select>

                                        <button class="btn btn-success w-100">
                                            Update Cabang
                                        </button>

                                    </form>

                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- RESET PASSWORD MODAL --}}
                    <div class="modal fade" id="resetModal{{ $branch->id }}">
                        <div class="modal-dialog">
                            <div class="modal-content border-0 rounded-4">

                                <div class="modal-header">
                                    <h5>Reset Password</h5>

                                    <button class="btn-close"
                                        data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">

                                    <form method="POST"
                                        action="{{ route('owner.branches.resetPassword', $branch) }}">

                                        @csrf

                                        <label>Password Baru</label>

                                        <input type="password"
                                            class="form-control mb-3"
                                            name="password"
                                            placeholder="Masukkan Password Baru">

                                        <button class="btn btn-primary w-100">
                                            Reset Password
                                        </button>

                                    </form>

                                </div>

                            </div>
                        </div>
                    </div>

                    @endforeach

                </tbody>

            </table>

        </div>
    </div>

</div>

{{-- ADD MODAL --}}
<div class="modal fade" id="addModal">

    <div class="modal-dialog">

        <div class="modal-content border-0 rounded-4">

            <div class="modal-header">
                <h5>Tambah Cabang</h5>

                <button class="btn-close"
                    data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <form method="POST"
                    action="{{ route('owner.branches.store') }}">

                    @csrf

                    <label>Nama Cabang</label>
                    <input type="text"
                        class="form-control mb-3"
                        name="name">

                    <label>Kota</label>
                    <input type="text"
                        class="form-control mb-3"
                        name="city">

                    <label>Kabupaten</label>
                    <input type="text"
                        class="form-control mb-3"
                        name="regency">

                    <hr>

                    <h6 class="fw-bold">
                        Akun Login Cabang
                    </h6>

                    <label>Email</label>
                    <input type="email"
                        class="form-control mb-3"
                        name="email">

                    <label>Password</label>
                    <input type="password"
                        class="form-control mb-3"
                        name="password">

                    <hr>

                    <h6 class="fw-bold mb-3">
                        Fitur Akses
                    </h6>

                    {{-- SWITCH --}}
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input"
                            type="checkbox"
                            name="can_students"
                            checked>

                        <label class="form-check-label">
                            Siswa
                        </label>
                    </div>

                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input"
                            type="checkbox"
                            name="can_teachers"
                            checked>

                        <label class="form-check-label">
                            Guru
                        </label>
                    </div>

                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input"
                            type="checkbox"
                            name="can_schedules"
                            checked>

                        <label class="form-check-label">
                            Jadwal
                        </label>
                    </div>

                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input"
                            type="checkbox"
                            name="can_payments"
                            checked>

                        <label class="form-check-label">
                            Keuangan
                        </label>
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input"
                            type="checkbox"
                            name="can_tryouts"
                            checked>

                        <label class="form-check-label">
                            Tryout
                        </label>
                    </div>

                    <button class="btn btn-primary w-100">
                        Simpan Cabang
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection
```
