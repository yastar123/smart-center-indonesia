@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

@php
    $user = auth()->user();
    $role = $user->getRoleNames()->first() ?? 'user';

    // Real stats from DB
    $totalStudents = \App\Models\Student::count();
    $activeStudents = \App\Models\Student::where('status','aktif')->count();
    $totalBranches = \App\Models\Branch::count();
    $activeBranches = \App\Models\Branch::where('status','active')->count();
@endphp

{{-- WELCOME BANNER --}}
<div class="dashboard-card mb-4 fade-up"
     style="background:linear-gradient(135deg,#1e3a5f 0%,#2563eb 60%,#6366f1 100%);color:white;border:none">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
            <div style="font-size:13px;opacity:.7;margin-bottom:4px">
                {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
            </div>
            <h3 style="font-size:clamp(18px,2.5vw,24px);font-weight:800;margin-bottom:6px;color:white">
                Selamat Datang, {{ explode(' ',$user->name)[0] }}! 👋
            </h3>
            <p style="opacity:.75;margin:0;font-size:14px">
                @if($role==='owner') Anda login sebagai <strong>Owner</strong> — akses penuh ke semua cabang dan laporan.
                @elseif($role==='admin') Anda login sebagai <strong>Admin Cabang</strong> — kelola siswa, guru, dan keuangan.
                @elseif($role==='guru') Anda login sebagai <strong>Guru</strong> — lihat jadwal dan input nilai siswa.
                @elseif($role==='siswa') Anda login sebagai <strong>Siswa</strong> — cek jadwal dan status pembayaran.
                @else Selamat bekerja hari ini!
                @endif
            </p>
        </div>
        <div style="font-size:64px;opacity:.2;line-height:1">
            <i class="bi bi-mortarboard-fill"></i>
        </div>
    </div>
</div>

{{-- STAT CARDS --}}
<div class="row g-3 mb-4">

    <div class="col-6 col-lg-3 fade-up">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Total Siswa</div>
                    <div class="stat-value text-primary" id="count-students">{{ $totalStudents }}</div>
                    <div class="stat-growth text-success">
                        <i class="bi bi-person-check-fill"></i>
                        {{ $activeStudents }} aktif
                    </div>
                </div>
                <div class="stat-icon bg-primary-soft" style="color:white">
                    <i class="bi bi-people-fill"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.05s">
        @php
            $totalTeachers = \App\Models\Teacher::count();
            $activeTeachers = \App\Models\Teacher::where('status','aktif')->count();
        @endphp
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Total Guru</div>
                    <div class="stat-value text-success">{{ $totalTeachers }}</div>
                    <div class="stat-growth text-success">
                        <i class="bi bi-person-badge-fill"></i>
                        {{ $activeTeachers }} aktif
                    </div>
                </div>
                <div class="stat-icon bg-success-soft" style="color:white">
                    <i class="bi bi-person-badge-fill"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.10s">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Cabang Aktif</div>
                    <div class="stat-value text-warning">{{ $activeBranches }}</div>
                    <div class="stat-growth text-muted">
                        <i class="bi bi-building"></i>
                        dari {{ $totalBranches }} cabang
                    </div>
                </div>
                <div class="stat-icon bg-warning-soft" style="color:white">
                    <i class="bi bi-building-fill-check"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.15s">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Pendapatan</div>
                    <div class="stat-value text-danger" style="font-size:22px">Rp 0</div>
                    <div class="stat-growth text-muted">
                        <i class="bi bi-calendar-month"></i> Bulan ini
                    </div>
                </div>
                <div class="stat-icon bg-danger-soft" style="color:white">
                    <i class="bi bi-cash-coin"></i>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- CHARTS ROW --}}
@role('owner|admin')
<div class="row g-3 mb-4">

    {{-- GENDER DONUT --}}
    <div class="col-md-4 fade-up">
        <div class="dashboard-card h-100">
            <h6 class="fw-bold mb-3" style="color:var(--text-primary)">
                <i class="bi bi-pie-chart-fill text-primary me-2"></i>Gender Siswa
            </h6>
            <div id="chartGender" style="min-height:220px"></div>
        </div>
    </div>

    {{-- STATUS STUDENTS --}}
    <div class="col-md-4 fade-up" style="animation-delay:.05s">
        <div class="dashboard-card h-100">
            <h6 class="fw-bold mb-3" style="color:var(--text-primary)">
                <i class="bi bi-bar-chart-fill text-success me-2"></i>Status Siswa
            </h6>
            <div id="chartStatus" style="min-height:220px"></div>
        </div>
    </div>

    {{-- QUICK ACTIONS --}}
    <div class="col-md-4 fade-up" style="animation-delay:.10s">
        <div class="dashboard-card h-100">
            <h6 class="fw-bold mb-3" style="color:var(--text-primary)">
                <i class="bi bi-lightning-fill text-warning me-2"></i>Aksi Cepat
            </h6>
            <div class="d-grid gap-2">
                <a href="{{ route('admin.students.index') }}" class="btn btn-primary btn-sm text-start">
                    <i class="bi bi-person-plus me-2"></i>Tambah Siswa Baru
                </a>
                <a href="{{ route('admin.teachers.index') }}" class="btn btn-success btn-sm text-start">
                    <i class="bi bi-person-workspace me-2"></i>Kelola Guru
                </a>
                <a href="{{ route('admin.schedules.index') }}" class="btn btn-outline-primary btn-sm text-start">
                    <i class="bi bi-calendar-week me-2"></i>Lihat Jadwal
                </a>
                <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-success btn-sm text-start">
                    <i class="bi bi-wallet2 me-2"></i>Cek Pembayaran
                </a>
                @role('owner')
                <a href="{{ route('owner.branches.index') }}" class="btn btn-outline-warning btn-sm text-start">
                    <i class="bi bi-building me-2"></i>Monitor Cabang
                </a>
                @endrole
                <a href="{{ route('admin.tryouts.index') }}" class="btn btn-outline-secondary btn-sm text-start">
                    <i class="bi bi-ui-checks-grid me-2"></i>Tryout Online
                </a>
            </div>
        </div>
    </div>

</div>
@endrole

{{-- RECENT STUDENTS --}}
@role('owner|admin')
<div class="dashboard-card fade-up">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold mb-0" style="color:var(--text-primary)">
            <i class="bi bi-clock-history text-primary me-2"></i>Siswa Terbaru
        </h6>
        <a href="{{ route('admin.students.index') }}" class="btn btn-sm btn-outline-primary" style="border-radius:8px;font-size:12px">
            Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead style="background:var(--input-bg)">
                <tr>
                    <th class="small text-muted fw-semibold py-2">SISWA</th>
                    <th class="small text-muted fw-semibold py-2">NIS</th>
                    <th class="small text-muted fw-semibold py-2">CABANG</th>
                    <th class="small text-muted fw-semibold py-2">STATUS</th>
                    <th class="small text-muted fw-semibold py-2">BERGABUNG</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $recentStudents = \App\Models\Student::with('branch')->latest()->limit(5)->get();
                @endphp
                @forelse($recentStudents as $s)
                @php
                    $avatar = 'https://ui-avatars.com/api/?name='.urlencode($s->name).'&background='.($s->gender==='P'?'ec4899':'4f8ef7').'&color=fff&size=40';
                    $statusColors = ['aktif'=>['#dcfce7','#15803d'],'nonaktif'=>['#f3f4f6','#6b7280'],'lulus'=>['#dbeafe','#1d4ed8']];
                    $sc = $statusColors[$s->status] ?? ['#f3f4f6','#6b7280'];
                @endphp
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ $s->photo ? Storage::url($s->photo) : $avatar }}"
                                 class="rounded-circle" width="36" height="36" style="object-fit:cover">
                            <div>
                                <div class="fw-semibold small">{{ $s->name }}</div>
                                <div class="text-muted" style="font-size:.72rem">{{ $s->gender === 'L' ? '👦' : '👧' }} {{ $s->grade ?? '-' }}</div>
                            </div>
                        </div>
                    </td>
                    <td><code class="small">{{ $s->nis }}</code></td>
                    <td><span class="badge bg-light text-dark small">{{ $s->branch->name ?? '-' }}</span></td>
                    <td>
                        <span class="badge" style="background:{{ $sc[0] }};color:{{ $sc[1] }}">
                            {{ ucfirst($s->status) }}
                        </span>
                    </td>
                    <td class="small text-muted">{{ $s->created_at->format('d M Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">
                        <i class="bi bi-inbox d-block mb-2" style="font-size:2rem;opacity:.4"></i>
                        Belum ada data siswa
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endrole

{{-- GURU/SISWA WELCOME --}}
@role('guru|siswa|karyawan')
<div class="row g-3">
    <div class="col-12 fade-up">
        <div class="dashboard-card text-center py-5">
            <i class="bi bi-mortarboard-fill text-primary mb-3" style="font-size:3rem"></i>
            <h5 class="fw-bold">Halo, {{ auth()->user()->name }}!</h5>
            <p class="text-muted">Panel Anda sedang dalam pengembangan. Fitur lengkap akan segera hadir.</p>
        </div>
    </div>
</div>
@endrole

@endsection

@push('scripts')
<script>
// Stats loaded server-side via Blade

// Charts
@php
    $male   = \App\Models\Student::where('gender','L')->count();
    $female = \App\Models\Student::where('gender','P')->count();
    $aktif   = \App\Models\Student::where('status','aktif')->count();
    $nonaktif = \App\Models\Student::where('status','nonaktif')->count();
    $lulus   = \App\Models\Student::where('status','lulus')->count();
@endphp

// Gender donut
new ApexCharts(document.getElementById('chartGender'), {
    chart: { type: 'donut', height: 220, fontFamily: 'Inter, sans-serif' },
    series: [{{ $male }}, {{ $female }}],
    labels: ['Laki-laki', 'Perempuan'],
    colors: ['#3b82f6', '#ec4899'],
    legend: { position: 'bottom', fontSize: '12px' },
    plotOptions: { pie: { donut: { size: '65%' } } },
    dataLabels: { style: { fontSize: '12px' } },
    responsive: [{ breakpoint: 480, options: { chart: { height: 200 } } }]
}).render();

// Status bar
new ApexCharts(document.getElementById('chartStatus'), {
    chart: { type: 'bar', height: 220, fontFamily: 'Inter, sans-serif', toolbar: { show: false } },
    series: [{ name: 'Siswa', data: [{{ $aktif }}, {{ $nonaktif }}, {{ $lulus }}] }],
    xaxis: { categories: ['Aktif', 'Nonaktif', 'Lulus'], labels: { style: { fontSize: '12px' } } },
    colors: ['#10b981', '#94a3b8', '#3b82f6'],
    plotOptions: { bar: { borderRadius: 6, distributed: true } },
    legend: { show: false },
    dataLabels: { enabled: false },
    grid: { borderColor: 'rgba(0,0,0,.06)' }
}).render();
</script>
@endpush
