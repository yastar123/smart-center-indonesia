@extends('layouts.app')
@section('title','Tagihan & Pembayaran')
@section('page-title','Tagihan & Pembayaran')

@section('content')

@php
// Eager-load relations on invoices for the table
$invoices->load(['schoolClass.mataPelajaran', 'pembayaran']);

$totalInvoices = $invoices->count();
$lunasCount    = $invoices->where('status','lunas')->count();
$menungguCount = $invoices->where('status','belum_bayar')->count();
$sebagianCount = $invoices->where('status','sebagian')->count();
@endphp

{{-- HEADER --}}
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
                <h4 style="font-weight:800;margin-bottom:3px;color:white;letter-spacing:-.02em">Tagihan &amp; Pembayaran</h4>
                <p style="opacity:.7;margin:0;font-size:13px">Pantau riwayat pembayaran dan unduh kuitansi Anda.</p>
            </div>
        </div>
        <div style="font-size:64px;opacity:.07;line-height:1;flex-shrink:0"><i class="bi bi-receipt-cutoff"></i></div>
    </div>
</div>

{{-- STATS --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3 fade-up">
        <div class="stat-card" style="border-top:3px solid #c84ddf">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Total Invoice</div>
                    <div class="stat-value text-primary" data-auto-count="{{ $totalInvoices }}">{{ $totalInvoices }}</div>
                    <div class="stat-label text-muted" style="font-size:11px">tagihan</div>
                </div>
                <div class="stat-icon bg-primary-soft" style="color:white"><i class="bi bi-receipt"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.05s">
        <div class="stat-card" style="border-top:3px solid #10b981">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Lunas</div>
                    <div class="stat-value text-success" data-auto-count="{{ $lunasCount }}">{{ $lunasCount }}</div>
                    <div class="stat-label" style="font-size:11px;color:#10b981"><i class="bi bi-check-circle-fill me-1"></i>Terverifikasi</div>
                </div>
                <div class="stat-icon bg-success-soft" style="color:white"><i class="bi bi-check-circle-fill"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.10s">
        <div class="stat-card" style="border-top:3px solid #f6af23">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Sebagian Bayar</div>
                    <div class="stat-value text-warning" data-auto-count="{{ $sebagianCount }}">{{ $sebagianCount }}</div>
                    <div class="stat-label text-warning" style="font-size:11px"><i class="bi bi-hourglass-split me-1"></i>Cicilan berjalan</div>
                </div>
                <div class="stat-icon bg-warning-soft" style="color:white"><i class="bi bi-hourglass-split"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.15s">
        <div class="stat-card" style="border-top:3px solid #ef4444">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Belum Dibayar</div>
                    <div class="stat-value" style="color:#dc2626" data-auto-count="{{ $menungguCount }}">{{ $menungguCount }}</div>
                    <div class="stat-label" style="font-size:11px;color:#ef4444"><i class="bi bi-exclamation-circle me-1"></i>Segera bayar</div>
                </div>
                <div class="stat-icon bg-danger-soft" style="color:white"><i class="bi bi-exclamation-circle-fill"></i></div>
            </div>
        </div>
    </div>
</div>

{{-- INVOICE TABLE --}}
<div class="dashboard-card fade-up">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h6 class="fw-bold mb-0" style="font-size:14px">
            <i class="bi bi-receipt text-primary me-2"></i>Daftar Tagihan
            <span class="badge ms-2" style="background:var(--soft-primary-bg);color:var(--soft-primary-text);font-size:11px">{{ $totalInvoices }} invoice</span>
        </h6>
    </div>

    @if($invoices->isEmpty())
    <div class="text-center py-5">
        <i class="bi bi-receipt-cutoff" style="font-size:48px;color:var(--text-muted);opacity:.4;display:block;margin-bottom:12px"></i>
        <div class="fw-semibold mb-1">Belum Ada Tagihan</div>
        <div class="text-muted" style="font-size:13px">Tagihan akan muncul setelah Anda terdaftar di program belajar.</div>
    </div>
    @else
    <div class="table-responsive">
        <table class="table table-modern align-middle mb-0" style="min-width:780px">
            <thead class="thead-modern">
                <tr>
                    <th class="ps-3" style="min-width:180px">No. Invoice / Tanggal</th>
                    <th style="min-width:200px">Paket Belajar</th>
                    <th style="min-width:130px">Nominal</th>
                    <th style="min-width:130px">Status</th>
                    <th class="text-center" style="min-width:130px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoices as $inv)
                @php
                    $latestPayment  = $inv->pembayaran->sortByDesc('created_at')->first();
                    $metodeLabel    = null;
                    if ($latestPayment) {
                        $metodeLabel = match($latestPayment->metode ?? '') {
                            'transfer' => 'Transfer Bank',
                            'cash'     => 'Tunai',
                            'qris'     => 'QRIS',
                            'va'       => 'VA ' . ($latestPayment->nama_bank ?? ''),
                            default    => ucfirst($latestPayment->metode ?? 'Transfer'),
                        };
                        if ($latestPayment->nama_bank) {
                            $metodeLabel = match($latestPayment->metode ?? '') {
                                'transfer' => 'Transfer ' . $latestPayment->nama_bank,
                                default    => 'VA ' . $latestPayment->nama_bank,
                            };
                        }
                    }

                    $paketLabel  = $inv->schoolClass?->nama_kelas ?? $inv->deskripsi ?? 'Program Belajar';
                    $subjectName = $inv->schoolClass?->mataPelajaran?->nama ?? null;

                    $terbayar = (float)$inv->pembayaran->where('status','verified')->sum('jumlah');
                    $sisa     = max(0, (float)$inv->total - $terbayar);

                    $statusInfo = match($inv->status) {
                        'lunas'      => ['label'=>'Lunas',       'bg'=>'var(--soft-success-bg)', 'color'=>'var(--soft-success-text)', 'icon'=>'bi-check-circle-fill'],
                        'sebagian'   => ['label'=>'Sebagian',    'bg'=>'var(--soft-warning-bg)', 'color'=>'var(--soft-warning-text)', 'icon'=>'bi-dash-circle-fill'],
                        default      => ['label'=>'Menunggu',    'bg'=>'var(--soft-danger-bg)',  'color'=>'var(--soft-danger-text)',  'icon'=>'bi-clock-fill'],
                    };

                    $isOverdue   = $inv->status !== 'lunas' && $inv->jatuh_tempo && $inv->jatuh_tempo->isPast();
                    $isPending   = $inv->pembayaran->where('status','pending')->isNotEmpty();
                @endphp
                <tr style="border-bottom:1px solid var(--card-border);vertical-align:middle{{ $isOverdue ? ';background:rgba(239,68,68,.03)' : '' }}">

                    {{-- Invoice No & Date --}}
                    <td class="ps-3">
                        <div class="fw-bold" style="font-size:13px;font-family:monospace;color:var(--text-primary)">
                            {{ $inv->nomor_invoice ?? '—' }}
                        </div>
                        <div class="text-muted mt-1" style="font-size:11px">
                            <i class="bi bi-calendar3 me-1"></i>
                            {{ $inv->created_at?->format('d M Y') ?? '—' }}
                        </div>
                        @if($isOverdue)
                        <div style="font-size:10px;color:#dc2626;margin-top:2px">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i>Jatuh tempo terlewat
                        </div>
                        @elseif($inv->jatuh_tempo && $inv->status !== 'lunas')
                        <div class="text-muted" style="font-size:10px;margin-top:2px">
                            Jatuh tempo: {{ $inv->jatuh_tempo->format('d M Y') }}
                        </div>
                        @endif
                    </td>

                    {{-- Paket Belajar --}}
                    <td>
                        <div class="fw-semibold" style="font-size:13px;color:var(--text-primary)">{{ $paketLabel }}</div>
                        @if($subjectName)
                        <div class="text-muted" style="font-size:11px;margin-top:2px">{{ $subjectName }}</div>
                        @endif
                        @if($metodeLabel)
                        <div class="mt-1">
                            <span style="font-size:10px;background:var(--soft-info-bg);color:var(--soft-info-text);padding:2px 8px;border-radius:5px;font-weight:600">
                                {{ $metodeLabel }}
                            </span>
                        </div>
                        @elseif($inv->status === 'belum_bayar' && !$isPending)
                        <div class="mt-1">
                            <span style="font-size:10px;background:var(--soft-danger-bg);color:var(--soft-danger-text);padding:2px 8px;border-radius:5px;font-weight:600">
                                Menunggu Pembayaran
                            </span>
                        </div>
                        @elseif($isPending)
                        <div class="mt-1">
                            <span style="font-size:10px;background:var(--soft-warning-bg);color:var(--soft-warning-text);padding:2px 8px;border-radius:5px;font-weight:600">
                                Menunggu Verifikasi
                            </span>
                        </div>
                        @endif
                    </td>

                    {{-- Nominal --}}
                    <td>
                        <div class="fw-bold" style="font-size:14px;color:{{ $inv->status==='lunas' ? 'var(--soft-success-text)' : 'var(--text-primary)' }}">
                            Rp {{ number_format($inv->total, 0, ',', '.') }}
                        </div>
                        @if($inv->status === 'sebagian' && $sisa > 0)
                        <div class="text-muted" style="font-size:11px;margin-top:2px">
                            Sisa: Rp {{ number_format($sisa, 0, ',', '.') }}
                        </div>
                        @endif
                    </td>

                    {{-- Status --}}
                    <td>
                        <span style="background:{{ $statusInfo['bg'] }};color:{{ $statusInfo['color'] }};padding:5px 11px;border-radius:8px;font-size:11px;font-weight:600;white-space:nowrap;display:inline-flex;align-items:center;gap:5px">
                            <i class="bi {{ $statusInfo['icon'] }}" style="font-size:11px"></i>{{ $statusInfo['label'] }}
                        </span>
                        @if($inv->status === 'sebagian')
                        @php
                            $totalCicilan = $inv->pembayaran->where('status','verified')->count();
                        @endphp
                        <div class="text-muted mt-1" style="font-size:10px">Cicilan {{ $totalCicilan }}/{{ $inv->pembayaran->count() ?: '?' }}</div>
                        @endif
                    </td>

                    {{-- Aksi --}}
                    <td class="text-center">
                        @if($inv->status === 'lunas')
                            <a href="{{ route('siswa.billing.invoice-detail', $inv->id) }}"
                               class="btn btn-sm fw-semibold"
                               style="font-size:11px;border-radius:8px;background:var(--soft-success-bg);color:var(--soft-success-text);border:1px solid var(--soft-success-border)">
                                <i class="bi bi-file-earmark-check me-1"></i>Lihat Nota
                            </a>
                        @else
                            <a href="{{ route('siswa.billing.invoice-detail', $inv->id) }}"
                               class="btn btn-sm btn-primary fw-semibold"
                               style="font-size:11px;border-radius:8px">
                                <i class="bi bi-eye me-1"></i>Detail Tagihan
                            </a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

{{-- Upload Bukti section for belum_bayar invoices (collapsible) --}}
@php $unpaidInvoices = $invoices->where('status','belum_bayar')->filter(fn($i) => $i->pembayaran->where('status','pending')->isEmpty()); @endphp
@if($unpaidInvoices->isNotEmpty())
<div class="dashboard-card fade-up mt-4">
    <h6 class="fw-bold mb-3" style="font-size:14px">
        <i class="bi bi-upload text-danger me-2"></i>Invoice Memerlukan Pembayaran
    </h6>
    <div class="d-flex flex-column gap-2">
        @foreach($unpaidInvoices as $inv)
        <div class="d-flex align-items-center justify-content-between p-3 rounded-3"
             style="background:var(--input-bg);border:1.5px solid var(--soft-danger-border,#fecaca)">
            <div style="min-width:0">
                <div class="fw-semibold text-truncate" style="font-size:13px">{{ $inv->nomor_invoice }}</div>
                <div class="text-muted" style="font-size:12px">
                    Rp {{ number_format($inv->total, 0, ',', '.') }}
                    @if($inv->jatuh_tempo) · Jatuh tempo {{ $inv->jatuh_tempo->format('d M Y') }} @endif
                </div>
            </div>
            <a href="{{ route('siswa.billing.invoice-detail', $inv->id) }}"
               class="btn btn-sm btn-danger fw-semibold ms-3 flex-shrink-0"
               style="font-size:11px;border-radius:8px">
                <i class="bi bi-upload me-1"></i>Bayar Sekarang
            </a>
        </div>
        @endforeach
    </div>
</div>
@endif

@endsection
