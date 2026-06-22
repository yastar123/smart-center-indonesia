@extends('layouts.app')
@section('title', 'Detail Tagihan Siswa')
@section('page-title', 'Detail Tagihan Siswa')

@section('content')
<div class="fade-up">

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.tagihan-siswa.index') }}">Tagihan Siswa</a></li>
        <li class="breadcrumb-item active">{{ $kelas->nama_kelas }}</li>
    </ol>
</nav>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-3"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-3"><i class="bi bi-x-circle me-2"></i>{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

{{-- HEADER --}}
<div class="dashboard-card mb-4" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none">
    <div class="row g-3 align-items-center">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-3">
                <div style="width:52px;height:52px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:24px;flex-shrink:0">
                    <i class="bi bi-wallet2"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0" style="color:white">{{ $kelas->nama_kelas }}</h5>
                    <div style="font-size:13px;opacity:.85">
                        <span class="me-3"><i class="bi bi-building me-1"></i>{{ $kelas->cabang?->name ?? '—' }}</span>
                        <span class="me-3"><i class="bi bi-person-badge me-1"></i>{{ $kelas->guru?->name ?? '—' }}</span>
                        <span><i class="bi bi-credit-card me-1"></i>{{ $kelas->billing_mode === 'postpaid' ? 'Pascabayar' : 'Cicilan' }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('admin.tagihan-siswa.index') }}" class="btn btn-sm"
               style="background:rgba(255,255,255,.2);color:white;border:1px solid rgba(255,255,255,.3)">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>
</div>

<div class="row g-4">

    {{-- LEFT: STUDENT INFO + INVOICE LIST --}}
    <div class="col-lg-7">

        {{-- STUDENT CARD --}}
        @if($student)
        <div class="dashboard-card mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-person-fill text-primary me-2"></i>Informasi Siswa</h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="p-3 rounded-3" style="background:var(--input-bg)">
                        <div class="text-muted" style="font-size:11px">Nama Siswa</div>
                        <div class="fw-semibold">{{ $student->user?->name ?? $student->name ?? '—' }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 rounded-3" style="background:var(--input-bg)">
                        <div class="text-muted" style="font-size:11px">Paket Belajar</div>
                        <div class="fw-semibold">{{ $student->package?->nama ?? '—' }}</div>
                        @if($student->package)
                        <div class="text-muted" style="font-size:11px">Rp {{ number_format($student->package->harga, 0, ',', '.') }}</div>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 rounded-3" style="background:var(--input-bg)">
                        <div class="text-muted" style="font-size:11px">Cabang</div>
                        <div class="fw-semibold">{{ $student->branch?->name ?? '—' }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 rounded-3" style="background:var(--input-bg)">
                        <div class="text-muted" style="font-size:11px">Status Siswa</div>
                        <div class="fw-semibold">{{ ucfirst($student->status ?? '—') }}</div>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="dashboard-card mb-4">
            <div class="text-center py-4 text-muted">
                <i class="bi bi-person-x" style="font-size:2rem;opacity:.3;display:block;margin-bottom:8px"></i>
                Belum ada siswa terdaftar di kelas ini.
            </div>
        </div>
        @endif

        {{-- INVOICE LIST --}}
        <div class="dashboard-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-receipt text-primary me-2"></i>Riwayat Invoice</h6>
                <span class="badge bg-primary rounded-pill">{{ $invoices->count() }} invoice</span>
            </div>

            @if($invoices->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-receipt" style="font-size:2rem;opacity:.3;display:block;margin-bottom:8px"></i>
                    Belum ada invoice untuk siswa ini.
                </div>
            @else
            @php
                $statusMap = [
                    'belum_bayar' => ['bg'=>'var(--soft-danger-bg)','fg'=>'#dc2626','label'=>'Belum Bayar'],
                    'sebagian'    => ['bg'=>'rgba(59,130,246,.12)','fg'=>'#2563eb','label'=>'Sebagian'],
                    'lunas'       => ['bg'=>'var(--soft-success-bg)','fg'=>'#059669','label'=>'Lunas'],
                ];
            @endphp
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr style="background:var(--input-bg)">
                            <th style="font-size:11px;color:var(--text-muted);text-transform:uppercase">No. Invoice</th>
                            <th style="font-size:11px;color:var(--text-muted);text-transform:uppercase">Deskripsi</th>
                            <th class="text-end" style="font-size:11px;color:var(--text-muted);text-transform:uppercase">Total</th>
                            <th class="text-center" style="font-size:11px;color:var(--text-muted);text-transform:uppercase">Status</th>
                            <th style="font-size:11px;color:var(--text-muted);text-transform:uppercase">Jatuh Tempo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoices as $inv)
                        @php $st = $statusMap[$inv->status] ?? ['bg'=>'var(--soft-muted-bg)','fg'=>'#6b7280','label'=>ucfirst($inv->status)]; @endphp
                        <tr>
                            <td style="font-size:12px;font-weight:600">{{ $inv->nomor_invoice }}</td>
                            <td style="font-size:12px">{{ $inv->deskripsi }}</td>
                            <td class="text-end fw-semibold" style="font-size:13px">Rp {{ number_format($inv->total,0,',','.') }}</td>
                            <td class="text-center">
                                <span style="background:{{ $st['bg'] }};color:{{ $st['fg'] }};padding:2px 10px;border-radius:20px;font-size:11px;font-weight:600">
                                    {{ $st['label'] }}
                                </span>
                            </td>
                            <td style="font-size:12px">
                                {{ $inv->jatuh_tempo ? \Carbon\Carbon::parse($inv->jatuh_tempo)->format('d M Y') : '—' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>

    {{-- RIGHT: CREATE INVOICE FORM --}}
    @if($student)
    <div class="col-lg-5">
        <div class="dashboard-card" style="position:sticky;top:80px">
            <div style="background:linear-gradient(135deg,#260632,#c84ddf);margin:-16px -16px 20px;padding:16px;border-radius:12px 12px 0 0;color:white">
                <h6 class="fw-bold mb-0"><i class="bi bi-plus-circle me-2"></i>Buat Invoice Baru</h6>
                <div style="font-size:12px;opacity:.8">Invoice akan muncul di billing siswa</div>
            </div>

            <form method="POST" action="{{ route('admin.tagihan-siswa.generate-invoice', $kelas->id) }}">
                @csrf
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold">Siswa <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" value="{{ $student->user?->name ?? $student->name }}" readonly
                               style="background:var(--input-bg)">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Deskripsi Invoice <span class="text-danger">*</span></label>
                        <input type="text" name="deskripsi" class="form-control" required
                               value="{{ old('deskripsi', 'Tagihan Sesi – '.$kelas->nama_kelas) }}"
                               placeholder="cth: Tagihan Sesi Bulan Juli">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Nominal (Rp) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="total" class="form-control" required min="1000"
                                   value="{{ old('total', $student->package?->harga ?? '') }}"
                                   placeholder="500000">
                        </div>
                        @if($student->package?->harga)
                        <div class="form-text">Harga paket: Rp {{ number_format($student->package->harga, 0, ',', '.') }}</div>
                        @endif
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Jatuh Tempo <span class="text-danger">*</span></label>
                        <input type="date" name="jatuh_tempo" class="form-control" required
                               min="{{ date('Y-m-d') }}"
                               value="{{ old('jatuh_tempo', date('Y-m-d', strtotime('+7 days'))) }}">
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary w-100 fw-semibold">
                            <i class="bi bi-receipt me-2"></i>Terbitkan Invoice
                        </button>
                    </div>
                </div>
            </form>

            @if($invoices->isNotEmpty())
            <div class="mt-4 pt-3 border-top">
                <div class="text-muted" style="font-size:12px">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Total invoice</span>
                        <span class="fw-semibold">{{ $invoices->count() }}x</span>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span>Total tagihan</span>
                        <span class="fw-semibold">Rp {{ number_format($invoices->sum('total'), 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Sudah lunas</span>
                        <span class="fw-semibold text-success">Rp {{ number_format($invoices->where('status','lunas')->sum('total'), 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif

</div>
</div>
@endsection
