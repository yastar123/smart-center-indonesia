@extends('layouts.app')
@section('title', 'Analytics')
@section('page-title', 'Analytics & Insight')

@section('content')

@php
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Branch;
use App\Models\Payment;
use App\Models\Schedule;
use Carbon\Carbon;

$totalStudents  = Student::count();
$newThisMonth   = Student::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();
$totalTeachers  = Teacher::count();
$activeBranches = Branch::where('status','active')->count();
$monthRevenue   = Payment::where('status','verified')->whereMonth('tanggal_pembayaran', now()->month)->whereYear('tanggal_pembayaran', now()->year)->sum('jumlah');
$totalRevenue   = Payment::where('status','verified')->sum('jumlah');

// Monthly student registrations — last 6 months
$studentMonths = [];
$studentData   = [];
for ($i = 5; $i >= 0; $i--) {
    $d = now()->subMonths($i);
    $studentMonths[] = $d->locale('id')->isoFormat('MMM');
    $studentData[]   = (int) Student::whereMonth('created_at', $d->month)->whereYear('created_at', $d->year)->count();
}

// Monthly revenue — last 6 months
$revMonths = [];
$revData   = [];
for ($i = 5; $i >= 0; $i--) {
    $d = now()->subMonths($i);
    $revMonths[] = $d->locale('id')->isoFormat('MMM');
    $revData[]   = (int) Payment::where('status','verified')->whereMonth('tanggal_pembayaran', $d->month)->whereYear('tanggal_pembayaran', $d->year)->sum('jumlah');
}

// Branch performance
$branches = Branch::withCount('students')
    ->withCount(['students as aktif_count' => fn($q) => $q->where('status','aktif')])
    ->orderByDesc('students_count')
    ->limit(8)->get();
@endphp

{{-- HEADER --}}
<div class="dashboard-card mb-4 fade-up"
     style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3" style="position:relative">
        <div>
            <div style="font-size:11px;opacity:.6;margin-bottom:4px;text-transform:uppercase;letter-spacing:.08em">
                <i class="bi bi-graph-up-arrow me-1"></i>Business Intelligence
            </div>
            <h4 style="font-weight:800;margin-bottom:6px;color:white;letter-spacing:-.02em">
                Analytics & Insight
            </h4>
            <p style="opacity:.65;margin:0;font-size:13px">
                Data real-time performa seluruh cabang Smart Center Indonesia.
            </p>
        </div>
        <div style="font-size:64px;opacity:.08;line-height:1;flex-shrink:0">
            <i class="bi bi-graph-up-arrow"></i>
        </div>
    </div>
</div>

{{-- KPI CARDS --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3 fade-up">
        <div class="stat-card" style="border-top:3px solid #c84ddf">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Total Siswa</div>
                    <div class="stat-value" style="color:#68117e">{{ $totalStudents }}</div>
                    <div class="stat-label" style="font-size:11px;color:#10b981">
                        <i class="bi bi-arrow-up-short"></i>+{{ $newThisMonth }} bulan ini
                    </div>
                </div>
                <div class="stat-icon bg-primary-soft" style="color:white">
                    <i class="bi bi-people-fill"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.05s">
        <div class="stat-card" style="border-top:3px solid #10b981">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Total Guru</div>
                    <div class="stat-value" style="color:#059669">{{ $totalTeachers }}</div>
                    <div class="stat-label" style="font-size:11px;color:var(--text-muted)">
                        <i class="bi bi-person-badge me-1"></i>Semua cabang
                    </div>
                </div>
                <div class="stat-icon bg-success-soft" style="color:white">
                    <i class="bi bi-person-badge-fill"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.10s">
        <div class="stat-card" style="border-top:3px solid #68117e">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Cabang Aktif</div>
                    <div class="stat-value" style="color:#c84ddf">{{ $activeBranches }}</div>
                    <div class="stat-label" style="font-size:11px;color:var(--text-muted)">
                        <i class="bi bi-building me-1"></i>Beroperasi
                    </div>
                </div>
                <div class="stat-icon bg-primary-soft" style="color:white">
                    <i class="bi bi-building-fill"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.15s">
        <div class="stat-card" style="border-top:3px solid #f6af23">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Revenue Bulan Ini</div>
                    <div class="stat-value" style="color:#e09000;font-size:18px">
                        Rp {{ number_format($monthRevenue, 0, ',', '.') }}
                    </div>
                    <div class="stat-label" style="font-size:11px;color:var(--text-muted)">
                        <i class="bi bi-cash me-1"></i>Terverifikasi
                    </div>
                </div>
                <div class="stat-icon bg-warning-soft" style="color:white">
                    <i class="bi bi-cash-coin"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- CHARTS ROW --}}
<div class="row g-4 mb-4">
    <div class="col-lg-6 fade-up">
        <div class="dashboard-card h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h6 class="fw-bold mb-1" style="font-size:14px">Pertumbuhan Siswa</h6>
                    <p class="text-muted mb-0" style="font-size:12px">Pendaftaran 6 bulan terakhir</p>
                </div>
                <span class="badge" style="background:#fdf4ff;color:#68117e;padding:4px 10px;font-size:11px;border-radius:7px">
                    +{{ array_sum($studentData) }} total
                </span>
            </div>
            <div id="chartStudentGrowth"></div>
        </div>
    </div>
    <div class="col-lg-6 fade-up" style="animation-delay:.05s">
        <div class="dashboard-card h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h6 class="fw-bold mb-1" style="font-size:14px">Tren Pendapatan</h6>
                    <p class="text-muted mb-0" style="font-size:12px">Revenue 6 bulan terakhir</p>
                </div>
                <span class="badge" style="background:#ecfdf5;color:#059669;padding:4px 10px;font-size:11px;border-radius:7px">
                    Rp {{ number_format(array_sum($revData), 0, ',', '.') }}
                </span>
            </div>
            <div id="chartRevTrend"></div>
        </div>
    </div>
</div>

{{-- BRANCH PERFORMANCE --}}
<div class="dashboard-card fade-up">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h6 class="fw-bold mb-1" style="font-size:14px">
                <i class="bi bi-building text-primary me-2"></i>Performa Cabang
            </h6>
            <p class="text-muted mb-0" style="font-size:12px">Ranking berdasarkan jumlah siswa aktif</p>
        </div>
        <a href="{{ route('owner.branches.index') }}" class="btn btn-sm btn-outline-primary" style="border-radius:8px;font-size:11px;padding:4px 12px">
            Kelola Cabang <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>

    @if($branches->isEmpty())
    <div class="empty-state">
        <i class="bi bi-building-slash"></i>
        <p>Belum ada data cabang</p>
    </div>
    @else
    <div class="row g-3">
        @foreach($branches as $i => $b)
        @php $pct = $branches->first()->students_count > 0 ? round($b->students_count / $branches->first()->students_count * 100) : 0; @endphp
        <div class="col-12 col-md-6">
            <div class="p-3 rounded-3" style="background:var(--input-bg);border:1px solid var(--card-border)">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width:28px;height:28px;border-radius:8px;background:linear-gradient(135deg,#c84ddf,#c84ddf);display:flex;align-items:center;justify-content:center;color:white;font-size:12px;font-weight:700;flex-shrink:0">
                            {{ $i + 1 }}
                        </div>
                        <div>
                            <div class="fw-semibold" style="font-size:13px">{{ $b->name }}</div>
                            <div class="text-muted" style="font-size:11px">{{ $b->city ?? 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold" style="font-size:14px;color:#68117e">{{ $b->students_count }}</div>
                        <div style="font-size:10px;color:var(--text-muted)">siswa</div>
                    </div>
                </div>
                <div style="height:5px;background:var(--card-border);border-radius:10px;overflow:hidden">
                    <div style="width:{{ $pct }}%;height:100%;background:linear-gradient(90deg,#c84ddf,#c84ddf);border-radius:10px;transition:width .8s ease"></div>
                </div>
                <div class="d-flex justify-content-between mt-1" style="font-size:10.5px;color:var(--text-muted)">
                    <span>{{ $b->aktif_count }} aktif</span>
                    <span>{{ $pct }}%</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const isDark    = document.documentElement.getAttribute('data-theme') === 'dark';
    const textColor = isDark ? '#94a3b8' : '#64748b';
    const gridColor = isDark ? 'rgba(255,255,255,.06)' : 'rgba(0,0,0,.05)';
    const barOpts   = {
        chart: { type:'bar', height:180, toolbar:{show:false}, background:'transparent', fontFamily:'Inter, sans-serif',
                 animations:{enabled:true,speed:700} },
        plotOptions: { bar:{ borderRadius:6, columnWidth:'52%' } },
        dataLabels: { enabled:false },
        xaxis: { labels:{style:{colors:textColor,fontSize:'11px'}}, axisBorder:{show:false}, axisTicks:{show:false} },
        yaxis: { labels:{style:{colors:textColor,fontSize:'11px'}} },
        grid:  { borderColor:gridColor, strokeDashArray:4 },
        tooltip: { theme:isDark?'dark':'light' }
    };

    new ApexCharts(document.getElementById('chartStudentGrowth'), {
        ...barOpts,
        series: [{ name:'Siswa Baru', data:{!! json_encode($studentData) !!} }],
        xaxis:  { ...barOpts.xaxis, categories:{!! json_encode($studentMonths) !!} },
        colors: ['#c84ddf'],
    }).render();

    new ApexCharts(document.getElementById('chartRevTrend'), {
        ...barOpts,
        series: [{ name:'Revenue (Rp)', data:{!! json_encode($revData) !!} }],
        xaxis:  { ...barOpts.xaxis, categories:{!! json_encode($revMonths) !!} },
        colors: ['#10b981'],
        yaxis:  { labels:{ style:{colors:textColor,fontSize:'11px'}, formatter: v => 'Rp '+Intl.NumberFormat('id').format(v) } },
        tooltip:{ theme:isDark?'dark':'light', y:{ formatter: v => 'Rp '+Intl.NumberFormat('id').format(v) } },
    }).render();
});
</script>
@endpush
