@extends('layouts.app')

@section('title', 'Reschedule & Availability')
@section('page-title', 'Manajemen Reschedule')

@section('content')
<div>

{{-- HEADER --}}
<div class="dashboard-card mb-4 fade-up" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
    <div class="row align-items-center g-3" style="position:relative">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-3 mb-2">
                <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0">
                    <i class="bi bi-arrow-left-right"></i>
                </div>
                <div>
                    <div style="font-size:11px;opacity:.7;text-transform:uppercase;letter-spacing:.5px">Reschedule & Availability</div>
                    <h5 class="fw-bold mb-0" style="color:white">Manajemen Reschedule</h5>
                    <span style="font-size:12px;opacity:.8">Pengelolaan approval reschedule dan cek ketersediaan guru</span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="row g-2 text-center">
                <div class="col-6">
                    <div style="background:rgba(255,255,255,.1);border-radius:10px;padding:8px">
                        <div style="font-size:22px;font-weight:700">{{ $stats['pending'] }}</div>
                        <div style="font-size:11px;opacity:.8">Menunggu</div>
                    </div>
                </div>
                <div class="col-6">
                    <div style="background:rgba(255,255,255,.1);border-radius:10px;padding:8px">
                        <div style="font-size:22px;font-weight:700">{{ $stats['total'] }}</div>
                        <div style="font-size:11px;opacity:.8">Total Request</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- GUARD RULE BANNER --}}
<div class="dashboard-card mb-4 fade-up" style="border-left:4px solid #f6af23;background:rgba(246,175,35,.05)">
    <div class="d-flex align-items-start gap-3">
        <i class="bi bi-shield-exclamation text-warning fs-4 mt-1"></i>
        <div>
            <h6 class="fw-bold mb-2">Aturan Reschedule</h6>
            <div class="d-flex flex-wrap gap-3" style="font-size:13px">
                <span><i class="bi bi-check-circle-fill text-success me-1"></i>Hanya kelas <strong>PRIVATE</strong> yang bisa di-reschedule</span>
                <span><i class="bi bi-x-circle-fill text-danger me-1"></i>Kelas reguler tidak boleh diproses</span>
                <span><i class="bi bi-pin-fill text-primary me-1"></i>Semua request yang masuk sudah dipastikan valid untuk paket privat</span>
            </div>
        </div>
    </div>
</div>

{{-- STATS --}}
<div class="row g-3 mb-4">
    @php
        $statCards = [
            ['label'=>'Total Request', 'value'=>$stats['total'],    'icon'=>'bi-arrow-left-right','color'=>'#c84ddf','bg'=>'bg-primary-soft'],
            ['label'=>'Menunggu',      'value'=>$stats['pending'],  'icon'=>'bi-hourglass-split', 'color'=>'#f6af23','bg'=>'bg-warning-soft'],
            ['label'=>'Disetujui',     'value'=>$stats['approved'], 'icon'=>'bi-check-circle',    'color'=>'#10b981','bg'=>'bg-success-soft'],
            ['label'=>'Ditolak',       'value'=>$stats['rejected'], 'icon'=>'bi-x-circle',        'color'=>'#ef4444','bg'=>'bg-danger-soft'],
        ];
    @endphp
    @foreach($statCards as $i => $sc)
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:{{ $i * 0.05 }}s">
        <div class="stat-card" style="border-top:3px solid {{ $sc['color'] }}">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">{{ $sc['label'] }}</div>
                    <div class="stat-value" style="color:{{ $sc['color'] }}" data-auto-count="{{ $sc['value'] }}">{{ $sc['value'] }}</div>
                </div>
                <div class="stat-icon {{ $sc['bg'] }}" style="color:white"><i class="bi {{ $sc['icon'] }}"></i></div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- AVAILABILITY GRID --}}
<div class="dashboard-card mb-4 fade-up">
    <h6 class="fw-bold mb-3"><i class="bi bi-person-check me-2 text-primary"></i>Ketersediaan Guru (7 Hari Ke Depan)</h6>
    @if($availability->isEmpty())
        <p class="text-muted mb-0" style="font-size:13px">Belum ada data guru aktif.</p>
    @else
    <div class="table-responsive">
        <table class="table table-bordered align-middle mb-0" style="font-size:12px">
            <thead class="thead-modern">
                <tr>
                    <th style="min-width:160px">Guru</th>
                    <th class="text-center">Sen</th>
                    <th class="text-center">Sel</th>
                    <th class="text-center">Rab</th>
                    <th class="text-center">Kam</th>
                    <th class="text-center">Jum</th>
                    <th class="text-center">Sab</th>
                </tr>
            </thead>
            <tbody>
                @foreach($availability as $t)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,#461256,#c84ddf);display:flex;align-items:center;justify-content:center;color:white;font-size:12px;font-weight:700;flex-shrink:0">
                                {{ strtoupper(substr($t['teacher'], 0, 2)) }}
                            </div>
                            <div>
                                <div class="fw-semibold">{{ $t['teacher'] }}</div>
                                <div class="text-muted" style="font-size:10px">{{ Str::limit($t['subject'], 25) }}</div>
                            </div>
                        </div>
                    </td>
                    @foreach(['mon','tue','wed','thu','fri','sat'] as $day)
                    @php
                        $val = $t[$day] ?? 'OFF';
                        $dayStyle = match(true) {
                            $val === 'OFF'  => 'background:rgba(107,114,128,.1);color:#6b7280',
                            $val === 'FULL' => 'background:rgba(239,68,68,.1);color:#dc2626',
                            default         => 'background:rgba(16,185,129,.1);color:#059669',
                        };
                        $dayIcon = match(true) {
                            $val === 'OFF'  => 'bi-moon-stars-fill',
                            $val === 'FULL' => 'bi-x-circle-fill',
                            default         => 'bi-check-circle-fill',
                        };
                    @endphp
                    <td class="text-center" style="{{ $dayStyle }};border-radius:0">
                        @if($val === 'OFF' || $val === 'FULL')
                            <i class="bi {{ $dayIcon }}"></i><br>
                            <span style="font-size:10px">{{ $val }}</span>
                        @else
                            <i class="bi {{ $dayIcon }}"></i><br>
                            <span style="font-size:10px">{{ $val }}</span>
                        @endif
                    </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

{{-- RESCHEDULE REQUEST TABLE --}}
<div class="dashboard-card fade-up">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold mb-0"><i class="bi bi-list-check me-2 text-primary"></i>Daftar Request Reschedule</h6>
        <div class="d-flex gap-2">
            <form method="GET" class="d-flex gap-2">
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="pending"  {{ request('status')=='pending'  ?'selected':'' }}>Menunggu</option>
                    <option value="approved" {{ request('status')=='approved' ?'selected':'' }}>Disetujui</option>
                    <option value="rejected" {{ request('status')=='rejected' ?'selected':'' }}>Ditolak</option>
                </select>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-3"><i class="bi bi-x-circle me-2"></i>{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <div class="table-responsive">
        <table class="table table-hover table-modern align-middle mb-0">
            <thead class="thead-modern">
                <tr>
                    <th>Guru / Kelas</th>
                    <th>Tipe</th>
                    <th>Jadwal Lama</th>
                    <th>Usulan Baru</th>
                    <th>Diajukan Oleh</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($proposals as $p)
                @php
                    $jenis     = $p->kelas->jenis ?? 'offline';
                    $isPrivat  = $jenis === 'private';
                    $hasConflict = false;
                    $statusBadge = match($p->status) {
                        'approved' => ['bg'=>'rgba(16,185,129,.15)','fg'=>'#059669','label'=>'Disetujui'],
                        'rejected' => ['bg'=>'rgba(239,68,68,.15)',  'fg'=>'#dc2626','label'=>'Ditolak'],
                        default    => $hasConflict
                            ? ['bg'=>'rgba(239,68,68,.15)','fg'=>'#dc2626','label'=>'Menunggu + Bentrok']
                            : ['bg'=>'rgba(246,175,35,.15)','fg'=>'#d97706','label'=>'Menunggu'],
                    };
                @endphp
                <tr>
                    <td>
                        <div class="fw-semibold">{{ $p->kelas->nama_kelas ?? '—' }}</div>
                        <div class="text-muted" style="font-size:11px">{{ $p->kelas->guru->name ?? '—' }}</div>
                    </td>
                    <td>
                        <span class="badge" style="background:rgba(200,77,223,.15);color:#461256;font-size:10px">
                            {{ strtoupper($jenis) }}
                        </span>
                    </td>
                    <td>
                        @if($p->schedule)
                            <div style="font-size:13px">{{ \Carbon\Carbon::parse($p->schedule->tanggal)->format('d M Y') }}</div>
                            <div class="text-muted" style="font-size:11px">{{ $p->schedule->jam_mulai }}–{{ $p->schedule->jam_selesai }}</div>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        <div style="font-size:13px;font-weight:600">{{ $p->tanggal ? $p->tanggal->format('d M Y') : '—' }}</div>
                        <div class="text-muted" style="font-size:11px">{{ $p->jam_mulai }}–{{ $p->jam_selesai }}</div>
                    </td>
                    <td>
                        <span class="badge" style="background:rgba(59,130,246,.1);color:#3b82f6;font-size:11px">
                            {{ ucfirst($p->proposed_by_type ?? '—') }}
                        </span>
                        <div class="text-muted" style="font-size:10px">{{ $p->proposerName() }}</div>
                    </td>
                    <td>
                        <span class="badge px-2 py-1" style="background:{{ $statusBadge['bg'] }};color:{{ $statusBadge['fg'] }};font-size:11px">
                            {{ $statusBadge['label'] }}
                        </span>
                    </td>
                    <td class="text-center">
                        @if($p->status === 'pending')
                        <div class="d-flex gap-1 justify-content-center">
                            <form method="POST" action="{{ route('admin.reschedule.approve', $p) }}">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success" title="Setujui"
                                        onclick="return confirm('Setujui reschedule ini?')">
                                    <i class="bi bi-check-lg"></i> Setujui
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.reschedule.reject', $p) }}">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Tolak"
                                        onclick="return confirm('Tolak reschedule ini?')">
                                    <i class="bi bi-x-lg"></i> Tolak
                                </button>
                            </form>
                        </div>
                        @elseif($p->status === 'approved')
                            <i class="bi bi-check-circle-fill text-success fs-5"></i>
                        @else
                            <i class="bi bi-x-circle-fill text-danger fs-5"></i>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-4 text-muted">
                    <i class="bi bi-calendar-x fs-3 d-block mb-2"></i>Belum ada request reschedule.
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($proposals->hasPages())
    <div class="d-flex justify-content-center mt-3">{{ $proposals->links() }}</div>
    @endif
</div>

</div>
@endsection
