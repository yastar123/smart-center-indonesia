@extends('layouts.app')
@section('title', 'Detail Absensi Sesi')
@section('page-title', 'Detail Absensi Sesi')

@section('content')
<div class="fade-up">

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.attendance.index') }}">Absensi</a></li>
        <li class="breadcrumb-item active">Detail Sesi #{{ $schedule->id }}</li>
    </ol>
</nav>

{{-- HEADER --}}
@php
    $statusColor = match($schedule->status) {
        'selesai'     => '#10b981',
        'berlangsung' => '#f6af23',
        'dibatalkan'  => '#ef4444',
        default       => '#0ea5e9',
    };
    $statusLabel = match($schedule->status) {
        'selesai'     => 'Selesai',
        'berlangsung' => 'Berlangsung',
        'dibatalkan'  => 'Dibatalkan',
        default       => 'Dijadwalkan',
    };
@endphp

<div class="dashboard-card mb-4" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-20px;top:-20px;width:160px;height:160px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3" style="position:relative">
        <div class="d-flex align-items-center gap-3">
            <div style="width:52px;height:52px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:24px;flex-shrink:0">
                <i class="bi bi-clipboard2-check-fill"></i>
            </div>
            <div>
                <div style="font-size:12px;opacity:.7;margin-bottom:2px">Detail Absensi Sesi #{{ $schedule->id }}</div>
                <h5 class="fw-bold mb-0" style="color:white">
                    {{ $schedule->paket?->nama ?? $schedule->kelas?->nama_kelas ?? 'Tanpa Paket' }}
                    <span style="font-size:14px;font-weight:400;opacity:.85">— Sesi ke-{{ $schedule->pertemuan_ke }}</span>
                </h5>
                <div style="font-size:12px;opacity:.8;margin-top:3px">
                    {{ $schedule->cabang?->name ?? '—' }}
                    · {{ $schedule->tanggal ? \Carbon\Carbon::parse($schedule->tanggal)->translatedFormat('d F Y') : '—' }}
                    · {{ $schedule->jam_mulai ? substr($schedule->jam_mulai,0,5) : '—' }}–{{ $schedule->jam_selesai ? substr($schedule->jam_selesai,0,5) : '—' }}
                </div>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <span style="background:{{ $statusColor }};color:white;padding:6px 14px;border-radius:20px;font-size:13px;font-weight:600">
                {{ $statusLabel }}
            </span>
            <a href="{{ route('admin.schedules.show', $schedule) }}" class="btn btn-sm"
               style="background:rgba(255,255,255,.15);color:white;border:1px solid rgba(255,255,255,.3)">
                <i class="bi bi-calendar3 me-1"></i>Detail Jadwal
            </a>
            <a href="{{ route('admin.attendance.sessions') }}" class="btn btn-sm"
               style="background:rgba(255,255,255,.15);color:white;border:1px solid rgba(255,255,255,.3)">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show mb-4">
    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row g-4">

    {{-- KOLOM KIRI: ABSENSI --}}
    <div class="col-lg-8">
        <div class="dashboard-card">
            <div class="d-flex align-items-center gap-2 mb-4 pb-3 border-bottom">
                <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#10b981,#047857);display:flex;align-items:center;justify-content:center;color:white;font-size:14px;flex-shrink:0">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div>
                    <div class="fw-bold" style="font-size:15px">Data Absensi Siswa</div>
                    <div class="text-muted" style="font-size:12px">Rekap kehadiran siswa pada sesi ini</div>
                </div>
            </div>

            @if($absensi->isEmpty())
                <div class="text-center py-5">
                    <div style="font-size:48px;margin-bottom:12px">📋</div>
                    <div class="fw-semibold" style="font-size:15px">Belum ada data absensi</div>
                    <div class="text-muted mt-2" style="font-size:13px">
                        @if($schedule->status === 'dijadwalkan')
                            Guru belum mengisi absensi untuk sesi ini.
                        @elseif($schedule->status === 'berlangsung')
                            Sesi sedang berlangsung — guru dapat mengisi absensi sekarang.
                        @else
                            Tidak ada data absensi yang tercatat untuk sesi ini.
                        @endif
                    </div>
                    @if($schedule->guru)
                    <div class="mt-3 p-3 rounded-3 d-inline-block" style="background:var(--soft-info-bg);border:1px solid var(--soft-info-border)">
                        <div class="text-muted" style="font-size:12px"><i class="bi bi-info-circle me-1"></i>Guru pengajar: <strong>{{ $schedule->guru->name }}</strong> mengisi absensi melalui menu Kelas di portal guru.</div>
                    </div>
                    @endif
                </div>
            @else
                @php
                    $countHadir = $absensi->where('status','hadir')->count();
                    $countIzin  = $absensi->where('status','izin')->count();
                    $countSakit = $absensi->where('status','sakit')->count();
                    $countAlpa  = $absensi->whereIn('status',['alpa','tidak_hadir'])->count();
                    $total      = $absensi->count();
                @endphp

                {{-- Summary bar --}}
                <div class="row g-2 mb-4">
                    <div class="col-3">
                        <div class="text-center p-3 rounded-3" style="background:#d1fae5;color:#065f46">
                            <div class="fw-bold" style="font-size:22px">{{ $countHadir }}</div>
                            <div style="font-size:11px">✅ Hadir</div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="text-center p-3 rounded-3" style="background:#bfdbfe;color:#1e40af">
                            <div class="fw-bold" style="font-size:22px">{{ $countIzin }}</div>
                            <div style="font-size:11px">📋 Izin</div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="text-center p-3 rounded-3" style="background:#fef3c7;color:#92400e">
                            <div class="fw-bold" style="font-size:22px">{{ $countSakit }}</div>
                            <div style="font-size:11px">🏥 Sakit</div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="text-center p-3 rounded-3" style="background:#fee2e2;color:#991b1b">
                            <div class="fw-bold" style="font-size:22px">{{ $countAlpa }}</div>
                            <div style="font-size:11px">❌ Alpha</div>
                        </div>
                    </div>
                </div>

                {{-- Progress kehadiran --}}
                @if($total > 0)
                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-1" style="font-size:12px">
                        <span class="text-muted">Tingkat kehadiran</span>
                        <span class="fw-semibold">{{ round(($countHadir / $total) * 100) }}%</span>
                    </div>
                    <div class="progress" style="height:8px;border-radius:4px">
                        <div class="progress-bar" style="width:{{ round(($countHadir / $total) * 100) }}%;background:linear-gradient(90deg,#10b981,#047857);border-radius:4px"></div>
                    </div>
                </div>
                @endif

                {{-- Tabel absensi --}}
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0" style="font-size:13px">
                        <thead>
                            <tr style="background:var(--input-bg)">
                                <th class="fw-semibold py-2" style="color:var(--text-muted)">#</th>
                                <th class="fw-semibold py-2" style="color:var(--text-muted)">Siswa</th>
                                <th class="fw-semibold py-2 text-center" style="color:var(--text-muted)">Guru Tandai</th>
                                <th class="fw-semibold py-2 text-center" style="color:var(--text-muted)">Siswa Konfirmasi</th>
                                <th class="fw-semibold py-2 text-center" style="color:var(--text-muted)">Status</th>
                                <th class="fw-semibold py-2" style="color:var(--text-muted)">Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($absensi as $i => $ab)
                            @php
                                $badge = match($ab->status) {
                                    'hadir'            => ['#d1fae5','#065f46','✅ Hadir'],
                                    'izin'             => ['#bfdbfe','#1e40af','📋 Izin'],
                                    'sakit'            => ['#fef3c7','#92400e','🏥 Sakit'],
                                    'alpa','tidak_hadir'=> ['#fee2e2','#991b1b','❌ Alpha'],
                                    'menunggu_konfirmasi'=> ['#fef9c3','#713f12','⏳ Menunggu'],
                                    default            => ['#f3f4f6','#374151','—'],
                                };
                            @endphp
                            <tr>
                                <td class="text-muted" style="font-size:11px">{{ $i+1 }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $ab->siswa?->name ?? '—' }}</div>
                                    <div class="text-muted" style="font-size:11px">{{ $ab->siswa?->nis ?? '' }}</div>
                                </td>
                                <td class="text-center">
                                    @if($ab->guru_hadir)
                                        <i class="bi bi-check-circle-fill text-success"></i>
                                    @else
                                        <i class="bi bi-x-circle text-muted"></i>
                                    @endif
                                </td>
                                <td class="text-center" style="font-size:11px">
                                    @if($ab->siswa_konfirmasi_at)
                                        <span class="text-success"><i class="bi bi-check2-circle me-1"></i>{{ \Carbon\Carbon::parse($ab->siswa_konfirmasi_at)->format('H:i') }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span style="background:{{ $badge[0] }};color:{{ $badge[1] }};padding:3px 10px;border-radius:12px;font-size:11px;font-weight:600;white-space:nowrap">
                                        {{ $badge[2] }}
                                    </span>
                                </td>
                                <td class="text-muted" style="font-size:11px">{{ $ab->catatan ?? '—' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- KOLOM KANAN: INFO SESI --}}
    <div class="col-lg-4">

        {{-- Info Guru --}}
        <div class="dashboard-card mb-4">
            <div class="fw-bold mb-3" style="font-size:14px"><i class="bi bi-person-badge text-primary me-2"></i>Guru Pengajar</div>
            @if($schedule->guru)
            <div class="d-flex align-items-center gap-3">
                <img src="{{ $schedule->guru->photo_url }}" alt="{{ $schedule->guru->name }}"
                     style="width:44px;height:44px;border-radius:50%;object-fit:cover;border:2px solid var(--card-border)">
                <div>
                    <div class="fw-semibold" style="font-size:13px">{{ $schedule->guru->name }}</div>
                    <div class="text-muted" style="font-size:11px">NIG: {{ $schedule->guru->nig ?? '—' }}</div>
                    @if($schedule->guru->subjects)
                    <div class="text-muted" style="font-size:11px">{{ implode(', ', $schedule->guru->subjects) }}</div>
                    @endif
                </div>
            </div>
            @else
            <div class="text-muted" style="font-size:13px">Belum ada guru pengajar</div>
            @endif
        </div>

        {{-- Info Paket/Kelas --}}
        <div class="dashboard-card mb-4">
            <div class="fw-bold mb-3" style="font-size:14px"><i class="bi bi-box-seam text-primary me-2"></i>
                {{ $schedule->paket ? 'Paket Belajar' : 'Kelas' }}
            </div>
            @if($schedule->paket)
            <div class="fw-semibold" style="font-size:13px">{{ $schedule->paket->nama }}</div>
            <div class="text-muted" style="font-size:12px;margin-top:2px">{{ $schedule->paket->deskripsi }}</div>
            <div class="d-flex justify-content-between mt-3" style="font-size:12px">
                <span class="text-muted">Sesi ini</span>
                <span class="fw-semibold">ke-{{ $schedule->pertemuan_ke }} / {{ $schedule->paket->jumlah_pertemuan }}</span>
            </div>
            @elseif($schedule->kelas)
            <div class="fw-semibold" style="font-size:13px">{{ $schedule->kelas->nama_kelas }}</div>
            <div class="text-muted" style="font-size:12px">{{ $schedule->kelas->mataPelajaran?->nama ?? '—' }}</div>
            @else
            <div class="text-muted" style="font-size:13px">—</div>
            @endif
        </div>

        {{-- Waktu --}}
        <div class="dashboard-card mb-4">
            <div class="fw-bold mb-3" style="font-size:14px"><i class="bi bi-clock text-primary me-2"></i>Waktu & Lokasi</div>
            <div class="d-flex flex-column gap-2" style="font-size:12px">
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Tanggal</span>
                    <span class="fw-semibold">{{ $schedule->tanggal ? \Carbon\Carbon::parse($schedule->tanggal)->translatedFormat('d F Y') : '—' }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Jam</span>
                    <span class="fw-semibold">{{ $schedule->jam_mulai ? substr($schedule->jam_mulai,0,5) : '—' }} – {{ $schedule->jam_selesai ? substr($schedule->jam_selesai,0,5) : '—' }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Metode</span>
                    <span class="fw-semibold">
                        @if($schedule->jenis === 'online') 💻 Online
                        @elseif($schedule->jenis === 'private') 👤 Private
                        @else 🏫 Offline @endif
                    </span>
                </div>
                @if($schedule->ruangan)
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Ruangan</span>
                    <span class="fw-semibold">{{ $schedule->ruangan }}</span>
                </div>
                @endif
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Cabang</span>
                    <span class="fw-semibold">{{ $schedule->cabang?->name ?? '—' }}</span>
                </div>
            </div>
        </div>

        {{-- Aksi --}}
        <div class="dashboard-card">
            <div class="fw-bold mb-3" style="font-size:14px">Aksi</div>
            <div class="d-flex flex-column gap-2">
                <a href="{{ route('admin.schedules.show', $schedule) }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-calendar3 me-2"></i>Lihat Detail Jadwal
                </a>
                <a href="{{ route('admin.attendance.sessions') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-list-ul me-2"></i>Semua Sesi Absensi
                </a>
                @if($absensi->isNotEmpty())
                <a href="{{ route('admin.attendance-history.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-clock-history me-2"></i>Riwayat Absensi
                </a>
                @endif
            </div>
        </div>

    </div>
</div>

</div>
@endsection
