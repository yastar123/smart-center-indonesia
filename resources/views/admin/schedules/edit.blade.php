@extends('layouts.app')
@section('title','Edit Jadwal Sesi')
@section('page-title','Edit Jadwal Sesi')

@section('content')
<div class="fade-up">

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.schedules.index') }}">Jadwal Sesi</a></li>
        <li class="breadcrumb-item active">Edit Jadwal</li>
    </ol>
</nav>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="dashboard-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h5 class="fw-bold mb-1"><i class="bi bi-pencil-square me-2 text-primary"></i>Edit Jadwal Sesi</h5>
                    <div class="text-muted small">Perbarui jadwal: <strong>{{ $schedule->paket?->nama ?? '—' }}</strong> — Sesi ke-{{ $schedule->pertemuan_ke }}</div>
                </div>
                <a href="{{ route('admin.schedules.index') }}" class="btn btn-outline-secondary" style="border-radius:10px">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
            </div>

            <form action="{{ route('admin.schedules.update', $schedule) }}" method="POST">
                @csrf
                @method('PUT')

                @if($errors->any())
                <div class="alert alert-danger mb-3">
                    <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
                @endif

                <div class="row g-3">
                    {{-- PAKET BELAJAR --}}
                    <div class="col-12">
                        <label class="form-label fw-semibold">Paket Belajar <span class="text-danger">*</span></label>
                        <select name="paket_id" id="paket_id" class="form-select" required onchange="onPaketChange(this.value)">
                            <option value="">— Pilih Paket —</option>
                            @foreach($pakets as $p)
                            <option value="{{ $p->id }}" {{ old('paket_id', $schedule->paket_id) == $p->id ? 'selected' : '' }}>
                                {{ $p->nama }}
                                @if($p->guru) — {{ $p->guru->name }}@endif
                                @if($p->cabang) ({{ $p->cabang->name }})@endif
                            </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- DETAIL PAKET --}}
                    <div class="col-12" id="paketDetailBox" style="display:none">
                        <div class="p-3 rounded-3" style="background:var(--soft-primary-bg);border:1.5px solid var(--soft-primary-border)">
                            <div class="fw-semibold mb-2" style="color:var(--soft-primary-text)"><i class="bi bi-box-seam me-2"></i>Detail Paket</div>
                            <div class="row g-2 small" id="paketDetailContent"></div>
                        </div>
                    </div>

                    {{-- SESI KE --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Sesi Ke- <span class="text-danger">*</span></label>
                        <select name="pertemuan_ke" id="pertemuan_ke" class="form-select" required>
                            <option value="">— Pilih dulu paket —</option>
                        </select>
                    </div>

                    {{-- TANGGAL --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal" id="tanggal" class="form-control"
                               value="{{ old('tanggal', $schedule->tanggal?->format('Y-m-d')) }}" required>
                    </div>

                    {{-- JAM --}}
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Jam Mulai <span class="text-danger">*</span></label>
                        <input type="time" name="jam_mulai" class="form-control"
                               value="{{ old('jam_mulai', substr($schedule->jam_mulai ?? '', 0, 5)) }}" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Jam Selesai <span class="text-danger">*</span></label>
                        <input type="time" name="jam_selesai" class="form-control"
                               value="{{ old('jam_selesai', substr($schedule->jam_selesai ?? '', 0, 5)) }}" required>
                    </div>

                    {{-- STATUS --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            @foreach(['dijadwalkan'=>'Dijadwalkan','berlangsung'=>'Berlangsung','selesai'=>'Selesai','dibatalkan'=>'Dibatalkan'] as $val => $lbl)
                            <option value="{{ $val }}" {{ old('status', $schedule->status) == $val ? 'selected' : '' }}>{{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- TOPIK --}}
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Topik / Materi</label>
                        <input type="text" name="topik" class="form-control"
                               value="{{ old('topik', $schedule->topik) }}" placeholder="Contoh: Persamaan Kuadrat, Past Tense, dll">
                    </div>

                    {{-- RUANGAN --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Ruangan</label>
                        <input type="text" name="ruangan" class="form-control"
                               value="{{ old('ruangan', $schedule->ruangan) }}" placeholder="Opsional">
                    </div>

                    {{-- LINK MEETING --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Link Meeting</label>
                        <input type="url" name="link_meeting" class="form-control"
                               value="{{ old('link_meeting', $schedule->link_meeting) }}" placeholder="https://zoom.us/...">
                    </div>

                    {{-- CATATAN --}}
                    <div class="col-12">
                        <label class="form-label fw-semibold">Catatan</label>
                        <textarea name="catatan" class="form-control" rows="2">{{ old('catatan', $schedule->catatan) }}</textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                    <a href="{{ route('admin.schedules.index') }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary px-4 fw-semibold">
                        <i class="bi bi-check-lg me-1"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="dashboard-card" id="paketInfoSidebar">
            <h6 class="fw-bold mb-3"><i class="bi bi-info-circle me-2 text-primary"></i>Info Paket Terpilih</h6>
            <div id="paketInfoSidebarContent">
                @if($schedule->paket)
                <div class="mb-2 p-2 rounded" style="background:var(--input-bg)"><span class="text-muted small">Nama Paket</span><div class="fw-semibold">{{ $schedule->paket->nama }}</div></div>
                <div class="mb-2 p-2 rounded" style="background:var(--input-bg)"><span class="text-muted small">Guru Pengajar</span><div class="fw-semibold">{{ $schedule->paket->guru?->name ?? '—' }}</div></div>
                <div class="mb-2 p-2 rounded" style="background:var(--input-bg)"><span class="text-muted small">Cabang</span><div class="fw-semibold">{{ $schedule->paket->cabang?->name ?? 'Pusat' }}</div></div>
                <div class="mb-2 p-2 rounded" style="background:var(--input-bg)"><span class="text-muted small">Jenis Paket</span><div class="fw-semibold">{{ $schedule->paket->jenis ?? '—' }}</div></div>
                <div class="mb-2 p-2 rounded" style="background:var(--input-bg)"><span class="text-muted small">Total Sesi</span><div class="fw-bold text-primary" style="font-size:1.3rem">{{ $schedule->paket->jumlah_pertemuan ?? '—' }}</div></div>
                <div class="mb-2 p-2 rounded" style="background:var(--input-bg)"><span class="text-muted small">Mata Pelajaran</span><div class="fw-semibold">{{ $schedule->paket->mataPelajaran->pluck('nama')->join(', ') ?: '—' }}</div></div>
                <div class="mb-2 p-2 rounded" style="background:var(--input-bg)"><span class="text-muted small">Harga Dasar</span><div class="fw-semibold">Rp {{ number_format($schedule->paket->harga ?? 0,0,',','.') }}</div></div>
                @else
                <div class="text-muted small text-center py-3">Pilih paket untuk melihat detail</div>
                @endif
            </div>
        </div>
    </div>
</div>

</div>
@endsection

@php
$paketsJson = $pakets->map(function ($p) {
    return [
        'id' => $p->id,
        'nama' => $p->nama,
        'jenis' => $p->jenis,
        'jumlah_pertemuan' => $p->jumlah_pertemuan,
        'metode_absensi' => $p->metode_absensi,
        'tipe_kelas' => $p->tipe_kelas,
        'harga' => $p->harga,
        'deskripsi' => $p->deskripsi,
        'status' => $p->status,
        'cabang' => $p->cabang?->name,
        'guru' => $p->guru?->name,
        'mata_pelajaran' => $p->mataPelajaran->pluck('nama'),
    ];
});
@endphp

@push('scripts')
<script>
const pakets = @json($paketsJson);

const currentSesi = {{ $schedule->pertemuan_ke ?? 'null' }};

function onPaketChange(paketId, sesiKe) {
    const detailBox = document.getElementById('paketDetailBox');
    const detailContent = document.getElementById('paketDetailContent');
    const sesiSelect = document.getElementById('pertemuan_ke');
    const sidebarContent = document.getElementById('paketInfoSidebarContent');

    if (!paketId) {
        detailBox.style.display = 'none';
        sesiSelect.innerHTML = '<option value="">— Pilih dulu paket —</option>';
        return;
    }

    const pkg = pakets.find(p => p.id == paketId);
    if (!pkg) return;

    detailBox.style.display = 'block';
    detailContent.innerHTML = `
        <div class="col-6"><strong>Nama:</strong> ${pkg.nama || '—'}</div>
        <div class="col-6"><strong>Jenis:</strong> ${pkg.jenis || '—'}</div>
        <div class="col-6"><strong>Jumlah Sesi:</strong> ${pkg.jumlah_pertemuan || '—'}</div>
        <div class="col-6"><strong>Metode Absensi:</strong> ${pkg.metode_absensi || '—'}</div>
        <div class="col-6"><strong>Tipe Kelas:</strong> ${pkg.tipe_kelas || '—'}</div>
        <div class="col-6"><strong>Harga:</strong> Rp ${pkg.harga ? parseInt(pkg.harga).toLocaleString('id-ID') : '—'}</div>
        <div class="col-6"><strong>Cabang:</strong> ${pkg.cabang || 'Pusat'}</div>
        <div class="col-6"><strong>Guru:</strong> ${pkg.guru || '—'}</div>
        <div class="col-12"><strong>Mata Pelajaran:</strong> ${pkg.mata_pelajaran?.join(', ') || '—'}</div>
    `;

    sidebarContent.innerHTML = `
        <div class="mb-2 p-2 rounded" style="background:var(--input-bg)"><span class="text-muted small">Guru Pengajar</span><div class="fw-semibold">${pkg.guru || '—'}</div></div>
        <div class="mb-2 p-2 rounded" style="background:var(--input-bg)"><span class="text-muted small">Cabang</span><div class="fw-semibold">${pkg.cabang || 'Pusat'}</div></div>
        <div class="mb-2 p-2 rounded" style="background:var(--input-bg)"><span class="text-muted small">Jenis Paket</span><div class="fw-semibold">${pkg.jenis || '—'}</div></div>
        <div class="mb-2 p-2 rounded" style="background:var(--input-bg)"><span class="text-muted small">Total Sesi</span><div class="fw-bold text-primary" style="font-size:1.3rem">${pkg.jumlah_pertemuan || '—'}</div></div>
        <div class="mb-2 p-2 rounded" style="background:var(--input-bg)"><span class="text-muted small">Harga Dasar</span><div class="fw-semibold">Rp ${pkg.harga ? parseInt(pkg.harga).toLocaleString('id-ID') : '—'}</div></div>
        <div class="mb-2 p-2 rounded" style="background:var(--input-bg)"><span class="text-muted small">Mata Pelajaran</span><div class="fw-semibold">${pkg.mata_pelajaran?.join(', ') || '—'}</div></div>
    `;

    const total = parseInt(pkg.jumlah_pertemuan) || 0;
    const selected = sesiKe ?? currentSesi;
    let opts = '<option value="">— Pilih Sesi —</option>';
    for (let i = 1; i <= total; i++) {
        opts += `<option value="${i}" ${i == selected ? 'selected' : ''}>Sesi ke-${i}</option>`;
    }
    sesiSelect.innerHTML = opts;
}

// Init on page load with current paket_id
const initPaket = document.getElementById('paket_id').value;
if (initPaket) onPaketChange(initPaket);
</script>
@endpush
