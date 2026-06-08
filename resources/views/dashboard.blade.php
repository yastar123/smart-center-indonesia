@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

@php
    $user = auth()->user();
    $role = $user->getRoleNames()->first() ?? 'user';
    $isOwnerAdmin = in_array($role, ['owner','admin']);

    $totalStudents  = \App\Models\Student::count();
    $activeStudents = \App\Models\Student::where('status','aktif')->count();
    $totalBranches  = \App\Models\Branch::count();
    $activeBranches = \App\Models\Branch::where('status','active')->count();
    $totalTeachers  = \App\Models\Teacher::count();
    $activeTeachers = \App\Models\Teacher::where('status','aktif')->count();

    // Monthly enrollment (last 6 months)
    $months = collect(range(5,0))->map(function($i) {
        return \Carbon\Carbon::now()->subMonths($i);
    });
    $monthLabels = $months->map(fn($m) => $m->locale('id')->isoFormat('MMM'))->toArray();
    $monthCounts = $months->map(fn($m) =>
        \App\Models\Student::whereYear('created_at',$m->year)->whereMonth('created_at',$m->month)->count()
    )->toArray();

    // Gender & status for charts
    $male    = \App\Models\Student::where('gender','L')->count();
    $female  = \App\Models\Student::where('gender','P')->count();
    $aktif   = \App\Models\Student::where('status','aktif')->count();
    $nonaktif= \App\Models\Student::where('status','nonaktif')->count();
    $lulus   = \App\Models\Student::where('status','lulus')->count();

    // Recent students
    $recentStudents = \App\Models\Student::with('branch')->latest()->limit(5)->get();

    // Revenue (current month, verified payments)
    $revenueThisMonth = \App\Models\Payment::where('status', 'verified')
        ->whereYear('tanggal_pembayaran', now()->year)
        ->whereMonth('tanggal_pembayaran', now()->month)
        ->sum('jumlah');
    $revenueTotal = \App\Models\Payment::where('status', 'verified')->sum('jumlah');
@endphp

{{-- WELCOME BANNER --}}
<div class="dashboard-card mb-4 fade-up"
     style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-60px;top:-60px;width:220px;height:220px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;right:40px;bottom:-70px;width:180px;height:180px;background:rgba(255,255,255,.04);border-radius:50%;pointer-events:none"></div>
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3" style="position:relative">
        <div>
            <div style="font-size:12px;opacity:.65;margin-bottom:5px;letter-spacing:.05em;text-transform:uppercase">
                {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
            </div>
            <h3 style="font-size:clamp(18px,2.5vw,26px);font-weight:800;margin-bottom:8px;color:white;letter-spacing:-.02em">
                Halo, {{ explode(' ',$user->name)[0] }}! 👋
            </h3>
            <p style="opacity:.75;margin:0;font-size:14px;line-height:1.6">
                @if($role==='owner') Anda login sebagai <strong>Owner</strong> — akses penuh ke semua cabang dan laporan.
                @elseif($role==='admin') Anda login sebagai <strong>Admin Cabang</strong> — kelola siswa, guru, dan keuangan.
                @elseif($role==='guru') Anda login sebagai <strong>Guru</strong> — lihat jadwal dan input nilai siswa.
                @elseif($role==='siswa') Anda login sebagai <strong>Siswa</strong> — cek jadwal dan status pembayaran.
                @else Selamat bekerja hari ini!
                @endif
            </p>
        </div>
        <div style="font-size:80px;opacity:.1;line-height:1;flex-shrink:0">
            <i class="bi bi-mortarboard-fill"></i>
        </div>
    </div>
</div>

{{-- STAT CARDS --}}
<div class="row g-3 mb-4">

    <div class="col-6 col-xl-3 fade-up">
        <div class="stat-card" style="border-top:3px solid #c84ddf">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Total Siswa</div>
                    <div class="stat-value text-primary count-up" data-target="{{ $totalStudents }}">0</div>
                    <div class="stat-growth text-success">
                        <i class="bi bi-person-check-fill"></i>
                        <span class="count-up" data-target="{{ $activeStudents }}">0</span> aktif
                    </div>
                </div>
                <div class="stat-icon bg-primary-soft" style="color:white">
                    <i class="bi bi-people-fill"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-xl-3 fade-up" style="animation-delay:.05s">
        <div class="stat-card" style="border-top:3px solid #10b981">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Total Guru</div>
                    <div class="stat-value text-success count-up" data-target="{{ $totalTeachers }}">0</div>
                    <div class="stat-growth text-muted">
                        <i class="bi bi-person-badge-fill"></i>
                        <span class="count-up" data-target="{{ $activeTeachers }}">0</span> aktif
                    </div>
                </div>
                <div class="stat-icon bg-success-soft" style="color:white">
                    <i class="bi bi-person-badge-fill"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-xl-3 fade-up" style="animation-delay:.10s">
        <div class="stat-card" style="border-top:3px solid #f6af23">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Cabang Aktif</div>
                    <div class="stat-value text-warning count-up" data-target="{{ $activeBranches }}">0</div>
                    <div class="stat-growth text-muted">
                        <i class="bi bi-building"></i>
                        dari <span class="count-up" data-target="{{ $totalBranches }}">0</span> cabang
                    </div>
                </div>
                <div class="stat-icon bg-warning-soft" style="color:white">
                    <i class="bi bi-building-fill-check"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-xl-3 fade-up" style="animation-delay:.15s">
        <div class="stat-card" style="border-top:3px solid #10b981">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Pendapatan Bulan Ini</div>
                    <div class="stat-value text-success" style="font-size:{{ strlen('Rp '.number_format($revenueThisMonth,0,',','.')) > 14 ? '16px' : '20px' }}">
                        Rp {{ number_format($revenueThisMonth, 0, ',', '.') }}
                    </div>
                    <div class="stat-growth text-muted">
                        <i class="bi bi-calendar-month"></i>
                        Total: Rp {{ number_format($revenueTotal/1000000, 1, ',', '.') }}Jt
                    </div>
                </div>
                <div class="stat-icon" style="background:linear-gradient(135deg,#059669,#10b981);color:white">
                    <i class="bi bi-cash-coin"></i>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- CHARTS ROW (owner & admin only) --}}
@if($isOwnerAdmin)
<div class="row g-3 mb-4">

    {{-- TREND LINE CHART --}}
    <div class="col-lg-7 fade-up">
        <div class="dashboard-card h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h6 class="fw-bold mb-0">
                    <i class="bi bi-graph-up-arrow text-primary me-2"></i>Tren Pendaftaran Siswa
                </h6>
                <span class="badge" style="background:#fdf4ff;color:#68117e;border:1px solid #e8b4f5;font-size:11px">6 Bulan Terakhir</span>
            </div>
            <div id="chartTrend" style="min-height:200px"></div>
        </div>
    </div>

    {{-- GENDER DONUT --}}
    <div class="col-lg-5 fade-up" style="animation-delay:.05s">
        <div class="dashboard-card h-100">
            <h6 class="fw-bold mb-3">
                <i class="bi bi-pie-chart-fill me-2" style="color:#c84ddf"></i>Distribusi Gender
            </h6>
            <div id="chartGender" style="min-height:200px"></div>
        </div>
    </div>

</div>

{{-- SECOND CHARTS ROW --}}
<div class="row g-3 mb-4">

    {{-- STATUS BAR --}}
    <div class="col-md-5 fade-up" style="animation-delay:.05s">
        <div class="dashboard-card h-100">
            <h6 class="fw-bold mb-3">
                <i class="bi bi-bar-chart-fill text-success me-2"></i>Status Siswa
            </h6>
            <div id="chartStatus" style="min-height:200px"></div>
        </div>
    </div>

    {{-- QUICK ACTIONS --}}
    <div class="col-md-7 fade-up" style="animation-delay:.10s">
        <div class="dashboard-card h-100">
            <h6 class="fw-bold mb-3">
                <i class="bi bi-lightning-fill text-warning me-2"></i>Aksi Cepat
            </h6>
            <div class="row g-2">
                <div class="col-6">
                    <a href="{{ route('admin.students.index') }}"
                       class="d-flex align-items-center gap-2 p-3 rounded-3 text-decoration-none quick-dash"
                       style="background:linear-gradient(135deg,#fdf4ff,#f3d6fa);border:1px solid #e8b4f5">
                        <i class="bi bi-person-plus-fill" style="font-size:1.4rem;color:#68117e"></i>
                        <div>
                            <div class="fw-semibold" style="font-size:12.5px;color:#461256">Tambah Siswa</div>
                            <div style="font-size:11px;color:#e8b4f5">Daftarkan siswa baru</div>
                        </div>
                    </a>
                </div>
                <div class="col-6">
                    <a href="{{ route('admin.teachers.index') }}"
                       class="d-flex align-items-center gap-2 p-3 rounded-3 text-decoration-none quick-dash"
                       style="background:linear-gradient(135deg,#fdf4ff,#fae8ff);border:1px solid #e9d5ff">
                        <i class="bi bi-person-workspace" style="font-size:1.4rem;color:#68117e"></i>
                        <div>
                            <div class="fw-semibold" style="font-size:12.5px;color:#461256">Kelola Guru</div>
                            <div style="font-size:11px;color:#c084fc">Manajemen pengajar</div>
                        </div>
                    </a>
                </div>
                <div class="col-6">
                    <a href="{{ route('admin.schedules.index') }}"
                       class="d-flex align-items-center gap-2 p-3 rounded-3 text-decoration-none quick-dash"
                       style="background:linear-gradient(135deg,#fdf4ff,#fae8ff);border:1px solid #e9d5ff">
                        <i class="bi bi-calendar-week" style="font-size:1.4rem;color:#c84ddf"></i>
                        <div>
                            <div class="fw-semibold" style="font-size:12.5px;color:#461256">Lihat Jadwal</div>
                            <div style="font-size:11px;color:#e8b4f5">Kalender akademik</div>
                        </div>
                    </a>
                </div>
                <div class="col-6">
                    <a href="{{ route('admin.payments.index') }}"
                       class="d-flex align-items-center gap-2 p-3 rounded-3 text-decoration-none quick-dash"
                       style="background:linear-gradient(135deg,#fdf4ff,#fae8ff);border:1px solid #e9d5ff">
                        <i class="bi bi-wallet2" style="font-size:1.4rem;color:#c84ddf"></i>
                        <div>
                            <div class="fw-semibold" style="font-size:12.5px;color:#461256">Cek Pembayaran</div>
                            <div style="font-size:11px;color:#c084fc">Tagihan & invoice</div>
                        </div>
                    </a>
                </div>
                @if($role === 'owner')
                <div class="col-6">
                    <a href="{{ route('owner.branches.index') }}"
                       class="d-flex align-items-center gap-2 p-3 rounded-3 text-decoration-none quick-dash"
                       style="background:linear-gradient(135deg,#fffbeb,#fef3c7);border:1px solid #fcd34d">
                        <i class="bi bi-building" style="font-size:1.4rem;color:#e09000"></i>
                        <div>
                            <div class="fw-semibold" style="font-size:12.5px;color:#78350f">Monitor Cabang</div>
                            <div style="font-size:11px;color:#d97706">Semua cabang</div>
                        </div>
                    </a>
                </div>
                @endif
                <div class="col-6">
                    <a href="{{ route('admin.tryouts.index') }}"
                       class="d-flex align-items-center gap-2 p-3 rounded-3 text-decoration-none quick-dash"
                       style="background:linear-gradient(135deg,#fdf4ff,#f3d6fa);border:1px solid #e8b4f5">
                        <i class="bi bi-ui-checks-grid" style="font-size:1.4rem;color:#68117e"></i>
                        <div>
                            <div class="fw-semibold" style="font-size:12.5px;color:#461256">Tryout Online</div>
                            <div style="font-size:11px;color:#c084fc">CBT & penilaian</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>
@endif

{{-- RECENT STUDENTS (owner & admin) --}}
@if($isOwnerAdmin)
<div class="dashboard-card fade-up">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h6 class="fw-bold mb-0">
            <i class="bi bi-clock-history text-primary me-2"></i>Siswa Terbaru Mendaftar
        </h6>
        <a href="{{ route('admin.students.index') }}" class="btn btn-sm btn-outline-primary" style="border-radius:8px;font-size:12px;padding:4px 14px">
            Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover table-modern align-middle mb-0">
            <thead class="thead-modern">
                <tr>
                    <th>Siswa</th>
                    <th>NIS</th>
                    <th class="d-none d-md-table-cell">Cabang</th>
                    <th class="d-none d-lg-table-cell">Kelas</th>
                    <th>Status</th>
                    <th class="d-none d-md-table-cell">Bergabung</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentStudents as $s)
                @php
                    $avatar = 'https://ui-avatars.com/api/?name='.urlencode($s->name).'&background='.($s->gender==='P'?'ec4899':'c84ddf').'&color=fff&size=40';
                    $statusColors = ['aktif'=>['#dcfce7','#15803d'],'nonaktif'=>['#f3f4f6','#6b7280'],'lulus'=>['#f3d6fa','#461256']];
                    $sc = $statusColors[$s->status] ?? ['#f3f4f6','#6b7280'];
                @endphp
                <tr>
                    <td class="py-3">
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ $s->photo ? Storage::url($s->photo) : $avatar }}"
                                 class="rounded-circle flex-shrink-0" width="36" height="36"
                                 style="object-fit:cover;border:2px solid #e2e8f0">
                            <div>
                                <div class="fw-semibold">{{ $s->name }}</div>
                                <div class="text-muted" style="font-size:.72rem">
                                    {{ $s->gender === 'L' ? '👦' : ($s->gender === 'P' ? '👧' : '') }}
                                    {{ $s->grade ?? '–' }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td><code class="small" style="background:var(--input-bg);padding:2px 7px;border-radius:5px">{{ $s->nis }}</code></td>
                    <td class="d-none d-md-table-cell">
                        <span class="badge" style="background:var(--input-bg);color:var(--text-muted);font-weight:500;border:1px solid var(--card-border)">
                            {{ $s->branch->name ?? '–' }}
                        </span>
                    </td>
                    <td class="d-none d-lg-table-cell text-muted small">{{ $s->grade ?? '–' }}</td>
                    <td>
                        <span class="badge" style="background:{{ $sc[0] }};color:{{ $sc[1] }};padding:4px 10px;border-radius:20px">
                            {{ ucfirst($s->status) }}
                        </span>
                    </td>
                    <td class="d-none d-md-table-cell small text-muted">{{ $s->created_at->format('d M Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <i class="bi bi-inbox d-block mb-3" style="font-size:3rem;opacity:.2"></i>
                        <div class="fw-semibold mb-1">Belum ada siswa terdaftar</div>
                        <a href="{{ route('admin.students.index') }}" class="btn btn-sm btn-primary mt-1">
                            <i class="bi bi-plus-circle me-1"></i>Tambah Siswa
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- GURU REDIRECT CARD --}}
@if($role === 'guru')
@php $teacher = \App\Models\Teacher::where('user_id', auth()->id())->first(); @endphp
<div class="row g-3">
    <div class="col-12 fade-up">
        <div class="dashboard-card" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
            <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
            <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3" style="position:relative">
                <div class="d-flex align-items-center gap-4">
                    @if($teacher?->photo)
                        <img src="{{ asset('storage/'.$teacher->photo) }}" alt="Foto Guru"
                             style="width:72px;height:72px;border-radius:18px;object-fit:cover;border:3px solid rgba(255,255,255,.3);flex-shrink:0">
                    @else
                        <div style="width:72px;height:72px;border-radius:18px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:28px;flex-shrink:0">
                            <i class="bi bi-person-workspace"></i>
                        </div>
                    @endif
                    <div>
                        <div style="font-size:11px;opacity:.6;text-transform:uppercase;letter-spacing:.08em;margin-bottom:4px">Portal Guru</div>
                        <h4 class="fw-bold mb-1" style="color:white;font-size:clamp(16px,2vw,22px)">Selamat datang, {{ explode(' ',$user->name)[0] }}!</h4>
                        <p style="opacity:.7;margin:0;font-size:13px">{{ $teacher ? 'NIP: '.$teacher->nip.' · '.$teacher->bidang_studi : 'Profil guru belum dilengkapi' }}</p>
                    </div>
                </div>
                <div class="d-flex flex-column gap-2 flex-shrink-0">
                    <a href="{{ route('guru.dashboard') }}" class="btn fw-semibold px-4" style="background:rgba(255,255,255,.2);color:white;border:1px solid rgba(255,255,255,.3);border-radius:10px;backdrop-filter:blur(10px)">
                        <i class="bi bi-grid-fill me-2"></i>Buka Portal Guru
                    </a>
                    <a href="{{ route('guru.attendance') }}" class="btn fw-semibold px-4" style="background:rgba(255,255,255,.1);color:white;border:1px solid rgba(255,255,255,.2);border-radius:10px">
                        <i class="bi bi-calendar-check me-2"></i>Input Absensi
                    </a>
                </div>
            </div>
        </div>
    </div>
    {{-- QUICK STATS for guru --}}
    @php
        $guruSchedulesToday = $teacher ? \App\Models\Schedule::where('guru_id', $teacher->id)->whereDate('tanggal', today())->count() : 0;
        $guruSchedulesWeek  = $teacher ? \App\Models\Schedule::where('guru_id', $teacher->id)->whereBetween('tanggal',[now()->startOfWeek(), now()->endOfWeek()])->count() : 0;
    @endphp
    <div class="col-6 col-md-3 fade-up" style="animation-delay:.05s">
        <div class="stat-card" style="border-top:3px solid #c84ddf">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Sesi Hari Ini</div>
                    <div class="stat-value text-primary">{{ $guruSchedulesToday }}</div>
                    <div class="stat-growth text-muted"><i class="bi bi-calendar-day me-1"></i>Jadwal mengajar</div>
                </div>
                <div class="stat-icon bg-primary-soft" style="color:white"><i class="bi bi-calendar-day"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3 fade-up" style="animation-delay:.10s">
        <div class="stat-card" style="border-top:3px solid #10b981">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Sesi Minggu Ini</div>
                    <div class="stat-value text-success">{{ $guruSchedulesWeek }}</div>
                    <div class="stat-growth text-muted"><i class="bi bi-calendar-week me-1"></i>Total sesi</div>
                </div>
                <div class="stat-icon bg-success-soft" style="color:white"><i class="bi bi-calendar-week"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3 fade-up" style="animation-delay:.15s">
        <div class="stat-card" style="border-top:3px solid #f6af23">
            <a href="{{ route('guru.grades') }}" class="text-decoration-none d-block">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-title">Input Nilai</div>
                        <div class="stat-value text-warning" style="font-size:16px">Input Sekarang</div>
                        <div class="stat-growth text-muted"><i class="bi bi-bar-chart me-1"></i>Nilai siswa</div>
                    </div>
                    <div class="stat-icon bg-warning-soft" style="color:white"><i class="bi bi-bar-chart-fill"></i></div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-6 col-md-3 fade-up" style="animation-delay:.20s">
        <div class="stat-card" style="border-top:3px solid #68117e">
            <a href="{{ route('profile.edit') }}" class="text-decoration-none d-block">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-title">Profil Saya</div>
                        <div class="stat-value" style="font-size:16px;color:#68117e">Edit Profil</div>
                        <div class="stat-growth text-muted"><i class="bi bi-person me-1"></i>Data akun</div>
                    </div>
                    <div class="stat-icon bg-primary-soft" style="color:white"><i class="bi bi-person-fill"></i></div>
                </div>
            </a>
        </div>
    </div>
</div>
@endif

{{-- SISWA REDIRECT CARD --}}
@if($role === 'siswa')
@php $student = \App\Models\Student::where('user_id', auth()->id())->first(); @endphp
<div class="row g-3">
    <div class="col-12 fade-up">
        <div class="dashboard-card" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
            <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
            <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3" style="position:relative">
                <div class="d-flex align-items-center gap-4">
                    @if($student?->photo)
                        <img src="{{ asset('storage/'.$student->photo) }}" alt="Foto Siswa"
                             style="width:72px;height:72px;border-radius:18px;object-fit:cover;border:3px solid rgba(255,255,255,.3);flex-shrink:0">
                    @else
                        <div style="width:72px;height:72px;border-radius:18px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:28px;flex-shrink:0">
                            <i class="bi bi-mortarboard-fill"></i>
                        </div>
                    @endif
                    <div>
                        <div style="font-size:11px;opacity:.6;text-transform:uppercase;letter-spacing:.08em;margin-bottom:4px">Portal Siswa</div>
                        <h4 class="fw-bold mb-1" style="color:white;font-size:clamp(16px,2vw,22px)">Halo, {{ explode(' ',$user->name)[0] }}! 👋</h4>
                        <p style="opacity:.7;margin:0;font-size:13px">{{ $student ? 'NIS: '.$student->nis.' · Kelas: '.($student->grade ?? '-').' · '.$student->branch?->name : 'Profil siswa belum tersambung ke akun ini' }}</p>
                    </div>
                </div>
                <div class="d-flex flex-column gap-2 flex-shrink-0">
                    <a href="{{ route('siswa.dashboard') }}" class="btn fw-semibold px-4" style="background:rgba(255,255,255,.2);color:white;border:1px solid rgba(255,255,255,.3);border-radius:10px;backdrop-filter:blur(10px)">
                        <i class="bi bi-grid-fill me-2"></i>Buka Portal Siswa
                    </a>
                    <a href="{{ route('siswa.schedule') }}" class="btn fw-semibold px-4" style="background:rgba(255,255,255,.1);color:white;border:1px solid rgba(255,255,255,.2);border-radius:10px">
                        <i class="bi bi-calendar-event me-2"></i>Lihat Jadwal
                    </a>
                </div>
            </div>
        </div>
    </div>
    {{-- QUICK STATS for siswa --}}
    @php
        $jadwalHariIni = $student ? \App\Models\Schedule::where('cabang_id', $student->branch_id)->whereDate('tanggal', today())->count() : 0;
        $jadwalMingguIni = $student ? \App\Models\Schedule::where('cabang_id', $student->branch_id)->whereBetween('tanggal',[now()->startOfWeek(), now()->endOfWeek()])->count() : 0;
        $sertifikatCount = $student ? \App\Models\Certificate::where('student_id', $student->id)->count() : 0;
        $invoiceBelum = $student ? \App\Models\Invoice::where('siswa_id', $student->id)->where('status','belum_bayar')->count() : 0;
    @endphp
    <div class="col-6 col-md-3 fade-up" style="animation-delay:.05s">
        <div class="stat-card" style="border-top:3px solid #c84ddf">
            <a href="{{ route('siswa.schedule') }}" class="text-decoration-none d-block">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-title">Jadwal Hari Ini</div>
                        <div class="stat-value text-primary">{{ $jadwalHariIni }}</div>
                        <div class="stat-growth text-muted"><i class="bi bi-calendar-day me-1"></i>Sesi belajar</div>
                    </div>
                    <div class="stat-icon bg-primary-soft" style="color:white"><i class="bi bi-calendar-day"></i></div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-6 col-md-3 fade-up" style="animation-delay:.10s">
        <div class="stat-card" style="border-top:3px solid #10b981">
            <a href="{{ route('siswa.schedule') }}" class="text-decoration-none d-block">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-title">Jadwal Minggu Ini</div>
                        <div class="stat-value text-success">{{ $jadwalMingguIni }}</div>
                        <div class="stat-growth text-muted"><i class="bi bi-calendar-week me-1"></i>Total sesi</div>
                    </div>
                    <div class="stat-icon bg-success-soft" style="color:white"><i class="bi bi-calendar-week"></i></div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-6 col-md-3 fade-up" style="animation-delay:.15s">
        <div class="stat-card" style="border-top:3px solid #f6af23">
            <a href="{{ route('siswa.certificates.index') }}" class="text-decoration-none d-block">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-title">Sertifikat</div>
                        <div class="stat-value text-warning">{{ $sertifikatCount }}</div>
                        <div class="stat-growth text-muted"><i class="bi bi-award me-1"></i>Diperoleh</div>
                    </div>
                    <div class="stat-icon bg-warning-soft" style="color:white"><i class="bi bi-award"></i></div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-6 col-md-3 fade-up" style="animation-delay:.20s">
        <div class="stat-card" style="{{ $invoiceBelum > 0 ? 'border-top:3px solid #ef4444' : 'border-top:3px solid #10b981' }}">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Tagihan Belum Bayar</div>
                    <div class="stat-value {{ $invoiceBelum > 0 ? 'text-danger' : 'text-success' }}">{{ $invoiceBelum }}</div>
                    <div class="stat-growth {{ $invoiceBelum > 0 ? 'text-danger' : 'text-success' }}">
                        <i class="bi {{ $invoiceBelum > 0 ? 'bi-exclamation-circle' : 'bi-check-circle' }} me-1"></i>
                        {{ $invoiceBelum > 0 ? 'Harap segera dibayar' : 'Semua lunas!' }}
                    </div>
                </div>
                <div class="stat-icon {{ $invoiceBelum > 0 ? 'bg-danger-soft' : 'bg-success-soft' }}" style="color:white">
                    <i class="bi {{ $invoiceBelum > 0 ? 'bi-exclamation-circle-fill' : 'bi-check-circle-fill' }}"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

{{-- OTHER ROLES --}}
@if(!in_array($role, ['owner','admin','guru','siswa']))
<div class="row g-3">
    <div class="col-12 fade-up">
        <div class="dashboard-card text-center py-5">
            <div style="width:80px;height:80px;border-radius:24px;background:linear-gradient(135deg,#68117e,#c84ddf);display:flex;align-items:center;justify-content:center;margin:0 auto 20px;box-shadow:0 12px 32px rgba(200,77,223,.4)">
                <i class="bi bi-mortarboard-fill text-white" style="font-size:36px"></i>
            </div>
            <h5 class="fw-bold mb-2">Halo, {{ $user->name }}!</h5>
            <p class="text-muted mb-4" style="max-width:340px;margin:0 auto">
                Selamat datang di Smart Center Indonesia.
            </p>
            <div class="d-flex gap-2 justify-content-center flex-wrap">
                <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-person me-1"></i>Edit Profil
                </a>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button class="btn btn-outline-danger btn-sm" type="submit">
                        <i class="bi bi-box-arrow-left me-1"></i>Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

@endsection


@push('scripts')
<script>
// ── Counter Animation ──────────────────────────────────────────────────────
function animateCount(el) {
    const target = parseInt(el.dataset.target) || 0;
    if (target === 0) { el.textContent = '0'; return; }
    const duration = 1200;
    const start = performance.now();
    const easeOut = t => 1 - Math.pow(1 - t, 3);
    function step(now) {
        const progress = Math.min((now - start) / duration, 1);
        el.textContent = Math.round(easeOut(progress) * target).toLocaleString('id');
        if (progress < 1) requestAnimationFrame(step);
    }
    requestAnimationFrame(step);
}

// Trigger counter when stat cards come into view
const countEls = document.querySelectorAll('.count-up');
if (countEls.length) {
    const countIO = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.querySelectorAll ? null : animateCount(entry.target);
                animateCount(entry.target);
                countIO.unobserve(entry.target);
            }
        });
    }, { threshold: 0.3 });
    countEls.forEach(el => countIO.observe(el));
}

// ── Charts (owner/admin only) ──────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function() {

    const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
    const textColor  = isDark ? '#94a3b8' : '#64748b';
    const gridColor  = isDark ? 'rgba(255,255,255,.06)' : 'rgba(0,0,0,.05)';
    const bgTooltip  = isDark ? '#2d0a3e' : '#fff';

    // ── Trend Line ─────────────────────────────────────────────────────────
    const trendEl = document.getElementById('chartTrend');
    if (trendEl) {
        new ApexCharts(trendEl, {
            chart: {
                type: 'area', height: 200,
                fontFamily: 'Inter, sans-serif',
                toolbar: { show: false },
                background: 'transparent',
                animations: { enabled: true, speed: 800 }
            },
            series: [{
                name: 'Siswa Baru',
                data: {!! json_encode(array_values($monthCounts)) !!}
            }],
            xaxis: {
                categories: {!! json_encode(array_values($monthLabels)) !!},
                labels: { style: { colors: textColor, fontSize: '12px' } },
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: {
                labels: { style: { colors: textColor, fontSize: '12px' } },
                min: 0
            },
            colors: ['#c84ddf'],
            fill: {
                type: 'gradient',
                gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05, stops: [0, 100] }
            },
            stroke: { curve: 'smooth', width: 2.5 },
            dataLabels: { enabled: false },
            grid: { borderColor: gridColor, strokeDashArray: 4 },
            tooltip: {
                theme: isDark ? 'dark' : 'light',
                style: { fontFamily: 'Inter, sans-serif', fontSize: '12px' }
            },
            markers: { size: 4, strokeWidth: 2, strokeColors: '#fff', colors: ['#c84ddf'] }
        }).render();
    }

    // ── Gender Donut ───────────────────────────────────────────────────────
    const genderEl = document.getElementById('chartGender');
    if (genderEl) {
        new ApexCharts(genderEl, {
            chart: {
                type: 'donut', height: 200,
                fontFamily: 'Inter, sans-serif',
                background: 'transparent',
                animations: { enabled: true, speed: 800 }
            },
            series: [{{ $male }}, {{ $female }}],
            labels: ['Laki-laki', 'Perempuan'],
            colors: ['#c84ddf', '#ec4899'],
            legend: {
                position: 'bottom', fontSize: '12px',
                labels: { colors: textColor }
            },
            plotOptions: {
                pie: {
                    donut: { size: '68%', labels: {
                        show: true,
                        total: {
                            show: true, label: 'Total',
                            color: textColor, fontSize: '13px', fontWeight: 600,
                            formatter: () => '{{ $male + $female }}'
                        }
                    }}
                }
            },
            dataLabels: { style: { fontSize: '12px' } },
            tooltip: { theme: isDark ? 'dark' : 'light' },
            stroke: { show: false }
        }).render();
    }

    // ── Status Bar ─────────────────────────────────────────────────────────
    const statusEl = document.getElementById('chartStatus');
    if (statusEl) {
        new ApexCharts(statusEl, {
            chart: {
                type: 'bar', height: 200,
                fontFamily: 'Inter, sans-serif',
                toolbar: { show: false },
                background: 'transparent',
                animations: { enabled: true, speed: 800 }
            },
            series: [{ name: 'Siswa', data: [{{ $aktif }}, {{ $nonaktif }}, {{ $lulus }}] }],
            xaxis: {
                categories: ['Aktif', 'Nonaktif', 'Lulus'],
                labels: { style: { colors: textColor, fontSize: '12px' } },
                axisBorder: { show: false }, axisTicks: { show: false }
            },
            yaxis: { labels: { style: { colors: textColor, fontSize: '12px' } }, min: 0 },
            colors: ['#10b981', '#94a3b8', '#c84ddf'],
            plotOptions: { bar: { borderRadius: 7, distributed: true, columnWidth: '55%' } },
            legend: { show: false },
            dataLabels: { enabled: false },
            grid: { borderColor: gridColor, strokeDashArray: 4 },
            tooltip: { theme: isDark ? 'dark' : 'light' }
        }).render();
    }

    // Re-render on dark mode toggle
    window.__dashboardRerenderOnTheme = true;
});

// Re-render charts when dark mode toggles
const _origToggleDark = window.toggleDark;
window.toggleDark = function() {
    if (typeof _origToggleDark === 'function') _origToggleDark();
    // Small delay to let CSS vars update, then reload page for fresh charts
    // (ApexCharts doesn't hot-swap themes; reload is the cleanest approach)
    setTimeout(() => location.reload(), 150);
};
</script>
@endpush
