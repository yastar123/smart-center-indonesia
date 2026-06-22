@extends('layouts.app')

@section('title', 'Detail Invoice')

@section('content')
<div class="container-fluid py-4">

    {{-- BREADCRUMB --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb" style="font-size:13px">
            <li class="breadcrumb-item"><a href="{{ route('siswa.billing.index') }}">Tagihan Saya</a></li>
            <li class="breadcrumb-item active">Detail Invoice</li>
        </ol>
    </nav>

    @if(session('success'))
    <div class="alert alert-success d-flex align-items-center gap-2 mb-4" style="border-radius:12px">
        <i class="bi bi-check-circle-fill"></i>{{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger d-flex align-items-center gap-2 mb-4" style="border-radius:12px">
        <i class="bi bi-exclamation-circle-fill"></i>{{ session('error') }}
    </div>
    @endif

    <div class="row g-4">
        {{-- LEFT: Invoice Detail --}}
        <div class="col-12 col-lg-7">
            {{-- Invoice Info --}}
            <div class="dashboard-card mb-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h6 class="fw-bold mb-0" style="font-size:14px;text-transform:uppercase;letter-spacing:.05em;color:var(--text-muted)">
                        <i class="bi bi-receipt me-2 text-primary"></i>Invoice
                    </h6>
                    @php
                        $statusColors = [
                            'belum_bayar'=> ['rgba(239,68,68,.1)','#ef4444','Belum Bayar'],
                            'sebagian'   => ['rgba(245,158,11,.1)','#f59e0b','Sebagian'],
                            'lunas'      => ['rgba(16,185,129,.1)','#10b981','Lunas'],
                        ];
                        $sc = $statusColors[$invoice->status] ?? ['rgba(100,116,139,.1)','#64748b',$invoice->status];
                    @endphp
                    <span style="background:{{ $sc[0] }};color:{{ $sc[1] }};padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600">
                        {{ $sc[2] }}
                    </span>
                </div>
                <div class="row g-3">
                    <div class="col-6">
                        <div class="text-muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.05em">Nomor Invoice</div>
                        <div class="fw-semibold" style="font-size:13px;font-family:monospace">{{ $invoice->nomor_invoice ?? '#'.$invoice->id }}</div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.05em">Jatuh Tempo</div>
                        <div class="fw-semibold" style="font-size:13px;{{ $invoice->jatuh_tempo && now()->toDateString() > $invoice->jatuh_tempo && $invoice->status !== 'lunas' ? 'color:#ef4444' : '' }}">
                            {{ $invoice->jatuh_tempo ? \Carbon\Carbon::parse($invoice->jatuh_tempo)->isoFormat('D MMMM YYYY') : '—' }}
                            @if($invoice->jatuh_tempo && now()->toDateString() > $invoice->jatuh_tempo && $invoice->status !== 'lunas')
                            <span style="font-size:10px;background:rgba(239,68,68,.1);color:#ef4444;padding:1px 6px;border-radius:10px;margin-left:4px">Terlambat</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.05em">Total Tagihan</div>
                        <div class="fw-bold" style="font-size:20px;color:var(--primary)">Rp {{ number_format($invoice->total ?? 0, 0, ',', '.') }}</div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.05em">Sudah Dibayar</div>
                        <div class="fw-bold" style="font-size:20px;color:#10b981">Rp {{ number_format($invoice->jumlah_terbayar, 0, ',', '.') }}</div>
                    </div>
                    @php $sisa = ($invoice->total ?? 0) - $invoice->jumlah_terbayar; @endphp
                    @if($sisa > 0)
                    <div class="col-12">
                        <div class="p-3 rounded-3" style="background:rgba(239,68,68,.06);border:1px solid rgba(239,68,68,.15)">
                            <div class="text-muted mb-1" style="font-size:11px;text-transform:uppercase;letter-spacing:.05em">Sisa Tagihan</div>
                            <div class="fw-bold" style="font-size:22px;color:#ef4444">Rp {{ number_format($sisa, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    @endif
                    @if($invoice->keterangan)
                    <div class="col-12">
                        <div class="text-muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.05em">Keterangan</div>
                        <div style="font-size:13px">{{ $invoice->keterangan }}</div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Kelas Info --}}
            @if($invoice->schoolClass)
            <div class="dashboard-card mb-4">
                <h6 class="fw-bold mb-3" style="font-size:14px;text-transform:uppercase;letter-spacing:.05em;color:var(--text-muted)">
                    <i class="bi bi-journal-bookmark me-2 text-primary"></i>Kelas
                </h6>
                <div class="d-flex align-items-center gap-3">
                    <div style="width:44px;height:44px;border-radius:12px;background:linear-gradient(135deg,#260632,#c84ddf);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        <i class="bi bi-mortarboard-fill" style="color:white;font-size:18px"></i>
                    </div>
                    <div>
                        <div class="fw-semibold" style="font-size:14px">{{ $invoice->schoolClass->nama_kelas }}</div>
                        <div class="text-muted" style="font-size:12px">
                            {{ $invoice->schoolClass->mataPelajaran?->nama ?? '—' }}
                            @if($invoice->schoolClass->cabang)
                            &nbsp;·&nbsp; {{ $invoice->schoolClass->cabang->name }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Payment History --}}
            <div class="dashboard-card">
                <h6 class="fw-bold mb-3" style="font-size:14px;text-transform:uppercase;letter-spacing:.05em;color:var(--text-muted)">
                    <i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Pembayaran
                </h6>
                @if($payments->isEmpty())
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-receipt" style="font-size:36px;opacity:.2;display:block;margin-bottom:8px"></i>
                    Belum ada pembayaran yang diunggah.
                </div>
                @else
                <div class="d-flex flex-column gap-3">
                    @foreach($payments as $pay)
                    @php
                        $psc = ['pending'=>['rgba(245,158,11,.1)','#f59e0b','Menunggu'],'verified'=>['rgba(16,185,129,.1)','#10b981','Disetujui'],'rejected'=>['rgba(239,68,68,.1)','#ef4444','Ditolak']][$pay->status] ?? ['rgba(100,116,139,.1)','#64748b',$pay->status];
                    @endphp
                    <div class="p-3 rounded-3" style="border:1px solid var(--card-border);background:var(--input-bg)">
                        <div class="d-flex align-items-start justify-content-between mb-2">
                            <div>
                                <div class="fw-semibold" style="font-size:14px">Rp {{ number_format($pay->jumlah, 0, ',', '.') }}</div>
                                <div class="text-muted" style="font-size:12px">
                                    {{ $pay->tanggal_pembayaran ? \Carbon\Carbon::parse($pay->tanggal_pembayaran)->isoFormat('D MMM YYYY') : '—' }}
                                    &nbsp;·&nbsp; {{ ucfirst($pay->metode ?? '—') }}
                                    @if($pay->nama_bank) &nbsp;·&nbsp; {{ $pay->nama_bank }} @endif
                                </div>
                            </div>
                            <span style="background:{{ $psc[0] }};color:{{ $psc[1] }};padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;flex-shrink:0">
                                {{ $psc[2] }}
                            </span>
                        </div>
                        @if($pay->alasan_penolakan)
                        <div class="mt-2 p-2 rounded-2" style="background:rgba(239,68,68,.06);font-size:12px;color:#ef4444">
                            <i class="bi bi-info-circle me-1"></i>{{ $pay->alasan_penolakan }}
                        </div>
                        @endif
                        @if($pay->bukti_pembayaran)
                        <div class="mt-2">
                            <a href="{{ asset('storage/'.$pay->bukti_pembayaran) }}" target="_blank"
                                style="font-size:12px;color:var(--primary)" class="text-decoration-none">
                                <i class="bi bi-image me-1"></i>Lihat Bukti
                            </a>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        {{-- RIGHT: Upload Form --}}
        <div class="col-12 col-lg-5">
            @if($invoice->status !== 'lunas')
            <div class="dashboard-card">
                <h6 class="fw-bold mb-4" style="font-size:14px;text-transform:uppercase;letter-spacing:.05em;color:var(--text-muted)">
                    <i class="bi bi-upload me-2 text-primary"></i>Upload Bukti Pembayaran
                </h6>
                <form method="POST" action="{{ route('siswa.billing.invoice-upload', $invoice) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:13px">Jumlah Dibayar <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="jumlah" class="form-control" required min="1000"
                                value="{{ old('jumlah', $sisa > 0 ? $sisa : '') }}"
                                placeholder="0">
                        </div>
                        @error('jumlah')<div class="text-danger" style="font-size:12px">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:13px">Metode Pembayaran <span class="text-danger">*</span></label>
                        <select name="metode" class="form-select" required>
                            <option value="">— Pilih Metode —</option>
                            <option value="transfer" {{ old('metode')=='transfer' ? 'selected' : '' }}>Transfer Bank</option>
                            <option value="cash" {{ old('metode')=='cash' ? 'selected' : '' }}>Cash / Tunai</option>
                            <option value="qris" {{ old('metode')=='qris' ? 'selected' : '' }}>QRIS</option>
                            <option value="lainnya" {{ old('metode')=='lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('metode')<div class="text-danger" style="font-size:12px">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:13px">Nama Bank / E-Wallet</label>
                        <input type="text" name="nama_bank" class="form-control" value="{{ old('nama_bank') }}"
                            placeholder="Contoh: BCA, Mandiri, OVO...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:13px">Nomor Rekening / Akun</label>
                        <input type="text" name="nomor_rekening" class="form-control" value="{{ old('nomor_rekening') }}"
                            placeholder="Nomor rekening pengirim">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:13px">Bukti Pembayaran</label>
                        <input type="file" name="bukti_pembayaran" class="form-control" accept="image/*,.pdf">
                        <div class="form-text">JPG, PNG, atau PDF. Maks 5MB.</div>
                        @error('bukti_pembayaran')<div class="text-danger" style="font-size:12px">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold" style="font-size:13px">Catatan</label>
                        <textarea name="catatan" class="form-control" rows="2" placeholder="Catatan tambahan (opsional)">{{ old('catatan') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-semibold" style="border-radius:10px">
                        <i class="bi bi-upload me-2"></i>Kirim Bukti Pembayaran
                    </button>
                </form>
            </div>
            @else
            <div class="dashboard-card text-center py-5">
                <i class="bi bi-check-circle-fill" style="font-size:48px;color:#10b981;display:block;margin-bottom:12px"></i>
                <div class="fw-bold mb-1" style="font-size:16px">Invoice Lunas</div>
                <div class="text-muted" style="font-size:13px">Pembayaran untuk invoice ini sudah selesai.</div>
                <a href="{{ route('siswa.billing.index') }}" class="btn btn-outline-primary mt-3">
                    <i class="bi bi-arrow-left me-1"></i>Kembali ke Tagihan
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
