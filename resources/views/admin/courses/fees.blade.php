@extends('layouts.app')
@section('title','Biaya Mata Pelajaran')
@section('page-title','Biaya Mata Pelajaran')

@section('content')

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="dashboard-card mb-4 fade-up"
     style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h5 class="fw-bold mb-0" style="color:white">Kelola Biaya Mata Pelajaran</h5>
            <p class="mb-0 mt-1" style="opacity:.75;font-size:13px">Atur harga per mata pelajaran (CRUD)</p>
        </div>
        <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addFeeModal">
            <i class="bi bi-plus-lg me-1"></i>Tambah Biaya
        </button>
    </div>
</div>

<div class="dashboard-card fade-up">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="thead-modern">
                <tr>
                    <th>Mata Pelajaran</th>
                    <th>Cabang</th>
                    <th>Biaya (Rp)</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($courses as $c)
                <tr>
                    <td class="fw-semibold">{{ $c->nama }}</td>
                    <td>{{ $c->cabang->name ?? 'Pusat' }}</td>
                    <td>
                        <form action="{{ route('admin.courses.fees.update', $c->id) }}" method="POST" class="d-flex gap-2 align-items-center">
                            @csrf
                            <input type="number" name="amount" value="{{ $c->fee->amount ?? 0 }}" min="0" step="1000"
                                   class="form-control form-control-sm" style="width:160px">
                            <button class="btn btn-sm btn-primary">Simpan</button>
                        </form>
                    </td>
                    <td class="text-end">
                        @if($c->fee)
                        <form action="{{ route('admin.courses.fees.destroy', $c->fee->id) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Hapus biaya untuk mapel ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                        @else
                        <span class="text-muted small">Belum diatur</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="addFeeModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('admin.courses.fees.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h6 class="modal-title">Tambah Biaya Mapel</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Mata Pelajaran</label>
                    <select name="course_id" class="form-select" required>
                        <option value="">— Pilih —</option>
                        @foreach($courses->filter(fn($c) => ! $c->fee) as $c)
                        <option value="{{ $c->id }}">{{ $c->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Biaya (Rp)</label>
                    <input type="number" name="amount" class="form-control" min="0" step="1000" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

@endsection
