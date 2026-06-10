@extends('layouts.app')
@section('title','Biaya Mata Pelajaran')
@section('page-title','Biaya Mata Pelajaran')

@section('content')

@if(session('success'))
<div class="alert alert-dismissible d-flex align-items-center gap-2 mb-4 fade show"
     style="border-radius:12px;border:none;background:rgba(16,185,129,.1);border-left:4px solid #10b981 !important">
    <i class="bi bi-check-circle-fill text-success"></i>
    <span>{{ session('success') }}</span>
    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="dashboard-card mb-4 fade-up"
     style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
    <div class="row align-items-center g-3" style="position:relative">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-3">
                <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0">
                    <i class="bi bi-cash-coin"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0" style="color:white">Kelola Biaya Mata Pelajaran</h5>
                    <p class="mb-0 mt-1" style="opacity:.75;font-size:13px">Atur harga per mata pelajaran yang harus dibayar siswa</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-md-end">
            <button class="btn fw-semibold px-4" data-bs-toggle="modal" data-bs-target="#addFeeModal"
                    style="background:rgba(255,255,255,.2);color:white;border:1px solid rgba(255,255,255,.3);border-radius:10px;backdrop-filter:blur(10px)">
                <i class="bi bi-plus-lg me-2"></i>Tambah Biaya
            </button>
        </div>
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
                        <form action="{{ route('admin.courses.fees.destroy', $c->fee->id) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="button" class="btn btn-sm btn-outline-danger"
                                    onclick="confirmAction('Hapus biaya mata pelajaran ini?', () => this.closest(\'form\').submit(), null, {title:\'Hapus Biaya\', okText:\'Ya, Hapus\'})">
                                <i class="bi bi-trash"></i>
                            </button>
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
            <div class="modal-header border-0 p-4" style="background:linear-gradient(135deg,#260632,#68117e);color:white">
                <h6 class="modal-title fw-bold" style="color:white"><i class="bi bi-cash-coin me-2"></i>Tambah Biaya Mapel</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
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
