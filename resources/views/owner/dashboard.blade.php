@extends('layouts.app')
@section('title', 'Owner Dashboard')
@section('page-title', 'Dashboard Owner')

@section('content')

@php
    $totalBranches  = \App\Models\Branch::count();
    $activeBranches = \App\Models\Branch::where('status','active')->count();
    $totalStudents  = \App\Models\Student::count();
    $activeStudents = \App\Models\Student::where('status','aktif')->count();
    $totalTeachers  = \App\Models\Teacher::count();
    $activeTeachers = \App\Models\Teacher::where('status','aktif')->count();
    $branches       = \App\Models\Branch::withCount('students')->latest()->limit(6)->get();
    $totalRevenue   = \App\Models\Payment::where('status','verified')->sum('jumlah');
    $monthRevenue   = \App\Models\Payment::where('status','verified')
                        ->whereMonth('tanggal_pembayaran', now()->month)
                        ->whereYear('tanggal_pembayaran',  now()->year)
                        ->sum('jumlah');
@endphp

{{-- WELCOME BANNER --}}
<div class="dashboard-card mb-4 fade-up"
     style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-40px;top:-40px;width:200px;height:200px;background:rgba(255,255,255,.04);border-radius:50%"></div>
    <div style="position:absolute;right:40px;bottom:-60px;width:150px;height:150px;background:rgba(255,255,255,.04);border-radius:50%"></div>
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3" style="position:relative">
        <div>
            <div style="font-size:12px;opacity:.6;margin-bottom:4px;text-transform:uppercase;letter-spacing:.05em">
                {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
            </div>
            <h3 style="font-size:clamp(18px,2.5vw,24px);font-weight:800;margin-bottom:6px;color:white;letter-spacing:-.02em">
                Selamat Datang, {{ explode(' ', auth()->user()->name)[0] }}! 👋
            </h3>
            <p style="opacity:.65;margin:0;font-size:13.5px;line-height:1.5">
                Dashboard monitoring seluruh cabang Smart Center Indonesia.
            </p>
        </div>
        <div style="font-size:72px;opacity:.08;line-height:1;flex-shrink:0">
            <i class="bi bi-building-fill"></i>
        </div>
    </div>
</div>

{{-- STAT CARDS --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3 fade-up">
        <div class="stat-card" style="border-top:3px solid #c84ddf">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Cabang Aktif</div>
                    <div class="stat-value" style="color:#68117e">{{ $activeBranches }}</div>
                    <div class="stat-label" style="font-size:11px;color:#6b7280">
                        <i class="bi bi-building me-1"></i>dari {{ $totalBranches }} total
                    </div>
                </div>
                <div class="stat-icon" style="background:linear-gradient(135deg,#68117e,#c84ddf)">
                    <i class="bi bi-building-fill-check"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.05s">
        <div class="stat-card" style="border-top:3px solid #10b981">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Total Siswa</div>
                    <div class="stat-value" style="color:#059669">{{ $totalStudents }}</div>
                    <div class="stat-label" style="font-size:11px;color:#059669">
                        <i class="bi bi-person-check me-1"></i>{{ $activeStudents }} aktif
                    </div>
                </div>
                <div class="stat-icon" style="background:linear-gradient(135deg,#059669,#10b981)">
                    <i class="bi bi-people-fill"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.10s">
        <div class="stat-card" style="border-top:3px solid #f6af23">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Total Guru</div>
                    <div class="stat-value" style="color:#e09000">{{ $totalTeachers }}</div>
                    <div class="stat-label" style="font-size:11px;color:#6b7280">
                        <i class="bi bi-person-badge me-1"></i>{{ $activeTeachers }} aktif
                    </div>
                </div>
                <div class="stat-icon" style="background:linear-gradient(135deg,#e09000,#f6af23)">
                    <i class="bi bi-person-badge-fill"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.15s">
        <div class="stat-card" style="border-top:3px solid #c84ddf">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Pendapatan Bulan Ini</div>
                    <div class="stat-value" style="color:#68117e;font-size:20px">
                        Rp {{ number_format($monthRevenue, 0, ',', '.') }}
                    </div>
                    <div class="stat-label" style="font-size:11px;color:#6b7280">
                        <i class="bi bi-arrow-up-circle me-1 text-success"></i>
                        Total: Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                    </div>
                </div>
                <div class="stat-icon" style="background:linear-gradient(135deg,#68117e,#c84ddf)">
                    <i class="bi bi-cash-coin" style="color:white"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">

    {{-- QUICK ACCESS --}}
    <div class="col-lg-4 fade-up" style="animation-delay:.05s">
        <div class="dashboard-card h-100">
            <h6 class="fw-bold mb-4" style="font-size:12px;text-transform:uppercase;letter-spacing:.06em;color:var(--text-muted)">
                <i class="bi bi-lightning-fill text-warning me-2"></i>Akses Cepat
            </h6>
            <div class="d-grid gap-2">
                @php
                $links = [
                    ['route'=>'owner.branches.index','icon'=>'bi-building-fill','color'=>'#68117e','bg'=>'#fdf4ff','border'=>'#e8b4f5','label'=>'Monitoring Cabang','sub'=>'Pantau semua cabang','arrowColor'=>'#e8b4f5'],
                    ['route'=>'admin.students.index','icon'=>'bi-people-fill','color'=>'#059669','bg'=>'#f0fdf4','border'=>'#bbf7d0','label'=>'Data Siswa','sub'=>'Kelola seluruh siswa','arrowColor'=>'#6ee7b7'],
                    ['route'=>'admin.teachers.index','icon'=>'bi-person-badge-fill','color'=>'#e09000','bg'=>'#fffbeb','border'=>'#fcd34d','label'=>'Data Guru','sub'=>'Manajemen pengajar','arrowColor'=>'#fcd34d'],
                    ['route'=>'admin.payments.index','icon'=>'bi-cash-stack','color'=>'#db2777','bg'=>'#fdf2f8','border'=>'#fbcfe8','label'=>'Keuangan','sub'=>'Laporan & invoice','arrowColor'=>'#f9a8d4'],
                    ['route'=>'admin.tryouts.index','icon'=>'bi-journal-check','color'=>'#c84ddf','bg'=>'#f5f3ff','border'=>'#e8b4f5','label'=>'Tryout CBT','sub'=>'Monitoring ujian','arrowColor'=>'#e8b4f5'],
                ];
                @endphp
                @foreach($links as $lnk)
                <a href="{{ route($lnk['route']) }}"
                   class="d-flex align-items-center gap-3 p-3 rounded-3 text-decoration-none quick-owner-link"
                   style="background:{{ $lnk['bg'] }};border:1px solid {{ $lnk['border'] }}">
                    <div style="width:38px;height:38px;border-radius:10px;background:{{ $lnk['color'] }};display:flex;align-items:center;justify-content:center;color:white;flex-shrink:0">
                        <i class="bi {{ $lnk['icon'] }}"></i>
                    </div>
                    <div style="min-width:0">
                        <div class="fw-semibold" style="font-size:13px;color:{{ $lnk['color'] }}">{{ $lnk['label'] }}</div>
                        <div style="font-size:11px;color:{{ $lnk['arrowColor'] }}">{{ $lnk['sub'] }}</div>
                    </div>
                    <i class="bi bi-chevron-right ms-auto flex-shrink-0" style="color:{{ $lnk['arrowColor'] }};font-size:12px"></i>
                </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- BRANCH TABLE --}}
    <div class="col-lg-8 fade-up" style="animation-delay:.10s">
        <div class="dashboard-card h-100">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h6 class="fw-bold mb-0" style="font-size:12px;text-transform:uppercase;letter-spacing:.06em;color:var(--text-muted)">
                    <i class="bi bi-building text-primary me-2"></i>Ringkasan Cabang
                </h6>
                <a href="{{ route('owner.branches.index') }}" class="btn btn-sm btn-outline-primary" style="border-radius:8px;font-size:11px;padding:4px 12px">
                    Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>

            @if($branches->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-building d-block mb-3" style="font-size:3.5rem;opacity:.12"></i>
                <div class="fw-semibold mb-1">Belum Ada Cabang</div>
                <p class="text-muted small mb-3">Tambahkan cabang pertama untuk memulai</p>
                <a href="{{ route('owner.branches.index') }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-circle me-1"></i>Tambah Cabang
                </a>
            </div>
            @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size:13px">
                    <thead>
                        <tr style="background:var(--input-bg)">
                            <th class="small fw-semibold py-3" style="color:var(--text-muted)">CABANG</th>
                            <th class="small fw-semibold py-3 text-center" style="color:var(--text-muted)">SISWA</th>
                            <th class="small fw-semibold py-3 text-center d-none d-md-table-cell" style="color:var(--text-muted)">KOTA</th>
                            <th class="small fw-semibold py-3 text-center" style="color:var(--text-muted)">STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($branches as $b)
                        <tr>
                            <td class="py-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#68117e,#c84ddf);display:flex;align-items:center;justify-content:center;color:white;font-size:14px;flex-shrink:0">
                                        <i class="bi bi-building"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $b->name }}</div>
                                        <div class="text-muted d-md-none" style="font-size:11px">{{ $b->city ?? '–' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge" style="background:#fdf4ff;color:#68117e;font-weight:600;font-size:12px;padding:4px 10px;border-radius:20px">
                                    {{ $b->students_count }}
                                </span>
                            </td>
                            <td class="text-center d-none d-md-table-cell text-muted">{{ $b->city ?? '–' }}</td>
                            <td class="text-center">
                                @if($b->status === 'active')
                                <span class="badge" style="background:#dcfce7;color:#15803d;font-size:11px;padding:4px 10px;border-radius:20px">
                                    <i class="bi bi-circle-fill me-1" style="font-size:6px"></i>Aktif
                                </span>
                                @else
                                <span class="badge" style="background:#fee2e2;color:#991b1b;font-size:11px;padding:4px 10px;border-radius:20px">
                                    <i class="bi bi-circle-fill me-1" style="font-size:6px"></i>Nonaktif
                                </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>

</div>

@endsection

@push('scripts')
@endpush