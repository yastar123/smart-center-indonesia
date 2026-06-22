@extends('layouts.app')

@section('title', 'Tinjau Pembayaran')

@section('content')
<div class="container-fluid py-4">

    {{-- BREADCRUMB --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb" style="font-size:13px">
            <li class="breadcrumb-item"><a href="{{ route('admin.verifikasi-pembayaran.index') }}">Verifikasi Pembayaran</a></li>
            <li class="breadcrumb-item active">{{ $payment->nomor_pembayaran ?? 'Detail' }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        {{-- LEFT: Payment Info --}}
        <div class="col-12 col-lg-7">
            <div class="dashboard-card mb-4">
                <h6 class="fw-bold mb-4" style="font-size:14px;text-transform:uppercase;letter-spacing:.05em;color:var(--text-muted)">
                    <i class="bi bi-receipt me-2 text-primary"></i>Informasi Pembayaran
                </h6>
                <div class="row g-3">
                    <div class="col-6">
                        <div class="text-muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.05em">No Pembayaran</div>
                        <div class="fw-semibold" style="font-size:13px;font-family:monospace">{{ $payment->nomor_pembayaran ?? '—' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.05em">Status</div>
                        @php
                            $sc = ['pending'=>['rgba(245,158,11,.1)','#f59e0b','Menunggu'],'verified'=>['rgba(16,185,129,.1)','#10b981','Disetujui'],'rejected'=>['rgba(239,68,68,.1)','#ef4444','Ditolak']][$payment->status] ?? ['rgba(100,116,139,.1)','#64748b',$payment->status];
                        @endphp
                        <span style="background:{{ $sc[0] }};color:{{ $sc[1] }};padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600">{{ $sc[2] }}</span>
                    </div>
                    <div class="col-6">
                        <div class="text-muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.05em">Jumlah</div>
                        <div class="fw-bold" style="font-size:20px;color:var(--primary)">Rp {{ number_format($payment->jumlah, 0, ',', '.') }}</div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.05em">Tanggal Bayar</div>
                        <div class="fw-semibold" style="font-size:13px">{{ $payment->tanggal_pembayaran ? \Carbon\Carbon::parse($payment->tanggal_pembayaran)->isoFormat('D MMMM YYYY') : '—' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.05em">Metode</div>
                        <div class="fw-semibold" style="font-size:13px;text-transform:capitalize">{{ $payment->metode ?? '—' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.05em">Bank / Rek</div>
                        <div style="font-size:13px">{{ $payment->nama_bank ?? '—' }} {{ $payment->nomor_rekening ? '('.$payment->nomor_rekening.')' : '' }}</div>
                    </div>
                    @if($payment->catatan)
                    <div class="col-12">
                        <div class="text-muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.05em">Catatan Siswa</div>
                        <div style="font-size:13px">{{ $payment->catatan }}</div>
                    </div>
                    @endif
                    @if($payment->alasan_penolakan)
                    <div class="col-12">
                        <div class="text-muted mb-1" style="font-size:11px;text-transform:uppercase;letter-spacing:.05em">Alasan Penolakan</div>
                        <div class="p-3 rounded-3" style="background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.2);font-size:13px;color:#ef4444">
                            {{ $payment->alasan_penolakan }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- STUDENT INFO --}}
            <div class="dashboard-card">
                <h6 class="fw-bold mb-3" style="font-size:14px;text-transform:uppercase;letter-spacing:.05em;color:var(--text-muted)">
                    <i class="bi bi-person me-2 text-primary"></i>Data Siswa
                </h6>
                <div class="d-flex align-items-center gap-3">
                    @php $s = $payment->siswa; @endphp
                    @if($s)
                    <img src="{{ $s->photo ? asset('storage/'.$s->photo) : 'https://ui-avatars.com/api/?name='.urlencode($s->name).'&background=c84ddf&color=fff' }}"
                        style="width:52px;height:52px;border-radius:50%;object-fit:cover" alt="{{ $s->name }}">
                    <div>
                        <div class="fw-semibold" style="font-size:15px">{{ $s->name }}</div>
                        <div class="text-muted" style="font-size:12px">
                            {{ $s->email ?? ($s->user?->email ?? '—') }}
                            &nbsp;·&nbsp; {{ $payment->cabang?->name ?? 'Pusat' }}
                        </div>
                        @if($payment->invoice)
                        <div class="text-muted mt-1" style="font-size:11px">Invoice: <span class="fw-semibold">{{ $payment->invoice->nomor_invoice ?? '#'.$payment->invoice_id }}</span></div>
                        @endif
                    </div>
                    @else
                    <div class="text-muted">Data siswa tidak ditemukan.</div>
                    @endif
                </div>
            </div>
        </div>

        {{-- RIGHT: Bukti + Actions --}}
        <div class="col-12 col-lg-5">
            {{-- BUKTI PEMBAYARAN --}}
            <div class="dashboard-card mb-4">
                <h6 class="fw-bold mb-3" style="font-size:14px;text-transform:uppercase;letter-spacing:.05em;color:var(--text-muted)">
                    <i class="bi bi-image me-2 text-primary"></i>Bukti Pembayaran
                </h6>
                @if($payment->bukti_pembayaran)
                    @php $ext = pathinfo($payment->bukti_pembayaran, PATHINFO_EXTENSION); @endphp
                    @if(in_array(strtolower($ext), ['jpg','jpeg','png','webp']))
                    <a href="{{ asset('storage/'.$payment->bukti_pembayaran) }}" target="_blank">
                        <img src="{{ asset('storage/'.$payment->bukti_pembayaran) }}" alt="Bukti"
                            style="width:100%;border-radius:12px;object-fit:contain;max-height:320px;background:var(--input-bg)">
                    </a>
                    @else
                    <a href="{{ asset('storage/'.$payment->bukti_pembayaran) }}" target="_blank"
                        class="btn btn-outline-primary w-100" style="border-radius:10px">
                        <i class="bi bi-file-earmark-pdf me-2"></i>Lihat Dokumen Bukti
                    </a>
                    @endif
                @else
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-image" style="font-size:36px;opacity:.2;display:block;margin-bottom:8px"></i>
                    Tidak ada bukti pembayaran diunggah
                </div>
                @endif
            </div>

            {{-- ACTIONS --}}
            @if($payment->status === 'pending')
            <div class="dashboard-card">
                <h6 class="fw-bold mb-3" style="font-size:14px;text-transform:uppercase;letter-spacing:.05em;color:var(--text-muted)">
                    <i class="bi bi-lightning me-2 text-primary"></i>Tindakan
                </h6>
                <form method="POST" action="{{ route('admin.verifikasi-pembayaran.approve', $payment) }}" class="mb-3">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label fw-semibold" style="font-size:12px">Catatan Persetujuan (opsional)</label>
                        <textarea name="catatan" class="form-control form-control-sm" rows="2" placeholder="Catatan tambahan..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-success w-100 fw-semibold"
                        onclick="return confirm('Setujui pembayaran ini?')">
                        <i class="bi bi-check-lg me-2"></i>Setujui Pembayaran
                    </button>
                </form>

                <form method="POST" action="{{ route('admin.verifikasi-pembayaran.reject', $payment) }}">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label fw-semibold" style="font-size:12px">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea name="alasan_penolakan" class="form-control form-control-sm" rows="2"
                            placeholder="Tuliskan alasan..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-danger w-100 fw-semibold"
                        onclick="return confirm('Tolak pembayaran ini?')">
                        <i class="bi bi-x-lg me-2"></i>Tolak Pembayaran
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
