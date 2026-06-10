@extends('layouts.app')
@section('title','Tagihan Saya')
@section('page-title','Tagihan Saya')

@section('content')

{{-- HEADER BANNER --}}
<div class="dashboard-card mb-4 fade-up"
     style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3" style="position:relative">
        <div class="d-flex align-items-center gap-3">
            <div style="width:52px;height:52px;border-radius:16px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:24px;flex-shrink:0">
                <i class="bi bi-wallet2"></i>
            </div>
            <div>
                <div style="font-size:11px;opacity:.6;margin-bottom:3px;text-transform:uppercase;letter-spacing:.08em">
                    Tagihan &amp; Pembayaran
                </div>
                <h4 style="font-weight:800;margin-bottom:3px;color:white;letter-spacing:-.02em">
                    Tagihan Saya
                </h4>
                <p style="opacity:.65;margin:0;font-size:13px">
                    Lihat tagihan dan unggah bukti pembayaran mata pelajaran
                </p>
            </div>
        </div>
        <div style="font-size:64px;opacity:.08;line-height:1;flex-shrink:0">
            <i class="bi bi-receipt-cutoff"></i>
        </div>
    </div>
</div>

{{-- STATS --}}
@php
    $totalCourses  = $courses->count();
    $paidCount     = collect($payments)->filter(fn($p) => $p->status === 'verified')->count();
    $pendingCount  = collect($payments)->filter(fn($p) => $p->status === 'pending')->count();
    $unpaidCount   = $totalCourses - collect($payments)->count();
@endphp

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3 fade-up">
        <div class="stat-card" style="border-top:3px solid #c84ddf">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Total Mata Pelajaran</div>
                    <div class="stat-value text-primary">{{ $totalCourses }}</div>
                    <div class="stat-label text-muted" style="font-size:11px">terdaftar</div>
                </div>
                <div class="stat-icon bg-primary-soft" style="color:white">
                    <i class="bi bi-journal-bookmark-fill"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.05s">
        <div class="stat-card" style="border-top:3px solid #10b981">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Sudah Lunas</div>
                    <div class="stat-value text-success">{{ $paidCount }}</div>
                    <div class="stat-label" style="font-size:11px;color:#10b981">
                        <i class="bi bi-check-circle-fill me-1"></i>Terverifikasi
                    </div>
                </div>
                <div class="stat-icon bg-success-soft" style="color:white">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.10s">
        <div class="stat-card" style="border-top:3px solid #f6af23">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Menunggu Verifikasi</div>
                    <div class="stat-value text-warning">{{ $pendingCount }}</div>
                    <div class="stat-label text-warning" style="font-size:11px">
                        <i class="bi bi-hourglass-split me-1"></i>Sedang diproses
                    </div>
                </div>
                <div class="stat-icon bg-warning-soft" style="color:white">
                    <i class="bi bi-hourglass-split"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.15s">
        <div class="stat-card" style="border-top:3px solid #ef4444">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Belum Dibayar</div>
                    <div class="stat-value" style="color:#dc2626">{{ $unpaidCount }}</div>
                    <div class="stat-label" style="font-size:11px;color:#ef4444">
                        <i class="bi bi-exclamation-circle me-1"></i>Segera bayar
                    </div>
                </div>
                <div class="stat-icon bg-danger-soft" style="color:white">
                    <i class="bi bi-exclamation-circle-fill"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- BILLING CARDS --}}
<div class="dashboard-card fade-up">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h6 class="fw-bold mb-1" style="color:var(--text-primary)">
                <i class="bi bi-receipt text-primary me-2"></i>Daftar Tagihan
            </h6>
            <p class="text-muted mb-0" style="font-size:12px">Unggah bukti pembayaran untuk setiap mata pelajaran</p>
        </div>
    </div>

    @if($courses->isEmpty())
    <div class="text-center py-5">
        <div style="width:80px;height:80px;border-radius:50%;background:var(--input-bg);display:flex;align-items:center;justify-content:center;margin:0 auto 16px">
            <i class="bi bi-journal-x text-muted" style="font-size:2rem;opacity:.5"></i>
        </div>
        <h6 class="fw-semibold mb-2" style="color:var(--text-primary)">Belum Ada Tagihan</h6>
        <p class="text-muted mb-0" style="font-size:13px">Anda belum terdaftar di mata pelajaran apapun.</p>
    </div>
    @else
    <div class="row g-3">
        @foreach($courses as $course)
        @php
            $payment  = $payments[$course->id] ?? null;
            $fee      = $fees[$course->id] ?? 0;
            $isSelected = $selectedCourse && $selectedCourse->id === $course->id;
            $statusMap = [
                'verified' => ['bg'=>'var(--soft-success-bg)','color'=>'var(--soft-success-text)','icon'=>'bi-check-circle-fill','label'=>'Lunas'],
                'pending'  => ['bg'=>'var(--soft-warning-bg)','color'=>'var(--soft-warning-text)','icon'=>'bi-hourglass-split','label'=>'Menunggu Verifikasi'],
                'rejected' => ['bg'=>'var(--soft-danger-bg)','color'=>'var(--soft-danger-text)','icon'=>'bi-x-circle-fill','label'=>'Ditolak'],
            ];
            $badge = $payment ? ($statusMap[$payment->status] ?? $statusMap['pending']) : null;
        @endphp
        <div class="col-md-6">
            <div class="p-4 rounded-3 h-100" id="billing-card-{{ $course->id }}"
                 style="background:var(--input-bg);border:1.5px solid {{ $isSelected ? '#c84ddf' : 'var(--card-border)' }};{{ $isSelected ? 'box-shadow:0 0 0 3px rgba(200,77,223,.15)' : '' }}">
                <div class="d-flex align-items-start justify-content-between gap-3 mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:44px;height:44px;border-radius:12px;background:rgba(200,77,223,.12);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                            <i class="bi bi-journal-bookmark-fill text-primary" style="font-size:18px"></i>
                        </div>
                        <div>
                            <div class="fw-bold" style="font-size:14px;color:var(--text-primary)">{{ $course->nama }}</div>
                            <div class="text-muted" style="font-size:12px">
                                @if($course->deskripsi)
                                    {{ Str::limit($course->deskripsi, 40) }}
                                @else
                                    Mata pelajaran
                                @endif
                            </div>
                        </div>
                    </div>
                    @if($badge)
                    <span class="badge flex-shrink-0"
                          style="background:{{ $badge['bg'] }};color:{{ $badge['color'] }};padding:5px 11px;border-radius:8px;font-size:11px;font-weight:600;white-space:nowrap">
                        <i class="bi {{ $badge['icon'] }} me-1"></i>{{ $badge['label'] }}
                    </span>
                    @else
                    <span class="badge flex-shrink-0"
                          style="background:var(--soft-danger-bg);color:var(--soft-danger-text);padding:5px 11px;border-radius:8px;font-size:11px;font-weight:600;white-space:nowrap">
                        <i class="bi bi-clock me-1"></i>Belum Bayar
                    </span>
                    @endif
                </div>

                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted" style="font-size:11px;margin-bottom:2px">Biaya</div>
                        <div class="fw-bold" style="font-size:16px;color:{{ $payment && $payment->status==='verified' ? '#059669' : 'var(--text-primary)' }}">
                            @if($fee > 0)
                                Rp {{ number_format($fee, 0, ',', '.') }}
                            @else
                                <span class="text-success">Gratis</span>
                            @endif
                        </div>
                    </div>

                    @if(!$payment || $payment->status === 'rejected')
                        <button class="btn btn-primary btn-sm px-3"
                                data-bs-toggle="modal"
                                data-bs-target="#payModal{{ $course->id }}"
                                style="border-radius:10px;font-size:12.5px">
                            <i class="bi bi-upload me-1"></i>
                            {{ $payment && $payment->status==='rejected' ? 'Upload Ulang' : 'Bayar Sekarang' }}
                        </button>
                    @elseif($payment->status === 'pending')
                        <button class="btn btn-sm" disabled
                                style="background:var(--soft-warning-bg);color:var(--soft-warning-text);border:none;border-radius:10px;font-size:12.5px">
                            <i class="bi bi-hourglass me-1"></i>Menunggu
                        </button>
                    @else
                        <button class="btn btn-sm" disabled
                                style="background:var(--soft-success-bg);color:var(--soft-success-text);border:none;border-radius:10px;font-size:12.5px">
                            <i class="bi bi-check2 me-1"></i>Lunas
                        </button>
                    @endif
                </div>

                @if($payment && $payment->status === 'rejected')
                <div class="mt-2 p-2 rounded-2" style="background:var(--soft-danger-bg);border:1px solid var(--soft-danger-border)">
                    <div style="font-size:11.5px;color:var(--soft-danger-text)">
                        <i class="bi bi-info-circle me-1"></i>Bukti pembayaran ditolak. Silakan upload ulang.
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- PAYMENT MODAL --}}
        <div class="modal fade" id="payModal{{ $course->id }}" tabindex="-1" aria-labelledby="payLabel{{ $course->id }}">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="border-radius:20px;border:none;overflow:hidden">
                    <div class="modal-header border-0 p-4"
                         style="background:linear-gradient(135deg,#260632,#68117e);color:white">
                        <div class="d-flex align-items-center gap-3">
                            <div style="width:40px;height:40px;border-radius:10px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center">
                                <i class="bi bi-upload"></i>
                            </div>
                            <div>
                                <h6 class="modal-title fw-bold mb-0" id="payLabel{{ $course->id }}">Upload Bukti Pembayaran</h6>
                                <div style="font-size:12px;opacity:.75">{{ $course->nama }}</div>
                            </div>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('siswa.billing.pay', $course->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body p-4">
                            <div class="p-3 rounded-3 mb-4"
                                 style="background:var(--soft-primary-bg);border:1px solid var(--soft-primary-border)">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span style="font-size:13px;color:var(--soft-primary-text);font-weight:600">
                                        <i class="bi bi-tag me-1"></i>Total Pembayaran
                                    </span>
                                    <span style="font-size:16px;font-weight:800;color:var(--primary)">
                                        @if($fee > 0) Rp {{ number_format($fee, 0, ',', '.') }}
                                        @else <span class="text-success">Gratis</span>
                                        @endif
                                    </span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold" style="font-size:13px">
                                    Catatan Transfer <span class="text-muted">(opsional)</span>
                                </label>
                                <input type="text" name="catatan" class="form-control" placeholder="Nama pengirim / bank / no. ref"
                                       style="border-radius:10px;border-color:var(--card-border);background:var(--input-bg)">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold" style="font-size:13px">
                                    Bukti Pembayaran <span class="text-danger">*</span>
                                </label>
                                <input type="file" name="proof" class="form-control" required
                                       accept=".jpg,.jpeg,.png,.pdf"
                                       style="border-radius:10px;border-color:var(--card-border);background:var(--input-bg)">
                                <div class="form-text">Format: JPG, PNG, atau PDF. Maks 5MB.</div>
                            </div>

                            <div class="p-3 rounded-3" style="background:var(--input-bg);border:1px solid var(--card-border)">
                                <div style="font-size:12px;color:var(--text-muted);line-height:1.6">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Bukti pembayaran akan diverifikasi oleh admin dalam 1×24 jam.
                                    Pastikan foto/scan jelas dan terbaca.
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0 p-4 pt-0 gap-2">
                            <button type="button" class="btn btn-outline-secondary"
                                    data-bs-dismiss="modal" style="border-radius:10px">
                                <i class="bi bi-x me-1"></i>Batal
                            </button>
                            <button type="submit" class="btn btn-primary px-4" style="border-radius:10px">
                                <i class="bi bi-upload me-2"></i>Kirim Bukti
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

</div>

@if($selectedCourse)
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const el = document.getElementById('billing-card-{{ $selectedCourse->id }}');
    if (el) el.scrollIntoView({ behavior: 'smooth', block: 'center' });
});
</script>
@endpush
@endif

@endsection
