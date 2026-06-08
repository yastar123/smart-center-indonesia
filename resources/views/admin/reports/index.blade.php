@extends('layouts.app')
@section('title', 'Laporan Keuangan')
@section('page-title', 'Laporan Keuangan')

@section('content')

@php
use App\Models\Payment;
use App\Models\Invoice;
use Carbon\Carbon;

$totalRevenue   = Payment::where('status','verified')->sum('jumlah');
$monthRevenue   = Payment::where('status','verified')
                    ->whereMonth('tanggal_pembayaran', now()->month)
                    ->whereYear('tanggal_pembayaran',  now()->year)
                    ->sum('jumlah');
$pendingCount   = Invoice::where('status','belum_bayar')->count();
$pendingTotal   = Invoice::where('status','belum_bayar')->sum('total');
$overdueCount   = Invoice::where('status','belum_bayar')
                    ->where('jatuh_tempo','<', now()->toDateString())->count();

// Monthly revenue — last 6 months
$monthlyLabels = [];
$monthlyData   = [];
for ($i = 5; $i >= 0; $i--) {
    $d = now()->subMonths($i);
    $monthlyLabels[] = $d->locale('id')->isoFormat('MMM YYYY');
    $monthlyData[]   = (int) Payment::where('status','verified')
                            ->whereMonth('tanggal_pembayaran', $d->month)
                            ->whereYear('tanggal_pembayaran',  $d->year)
                            ->sum('jumlah');
}

// Recent verified payments
$recentPayments = Payment::with(['invoice.siswa','invoice.cabang'])
                    ->where('status','verified')
                    ->latest()
                    ->limit(10)
                    ->get();

// Outstanding invoices
$outstanding = Invoice::with(['siswa','cabang'])
                ->where('status','belum_bayar')
                ->orderBy('jatuh_tempo')
                ->limit(10)
                ->get();
@endphp

{{-- HEADER --}}
<div class="dashboard-card mb-4 fade-up"
     style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.04);border-radius:50%"></div>
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3" style="position:relative">
        <div>
            <div style="font-size:11px;opacity:.6;margin-bottom:4px;text-transform:uppercase;letter-spacing:.08em">
                <i class="bi bi-bar-chart-fill me-1"></i>Laporan & Analitik
            </div>
            <h4 style="font-weight:800;margin-bottom:6px;color:white;letter-spacing:-.02em">
                Laporan Keuangan
            </h4>
            <p style="opacity:.65;margin:0;font-size:13px">
                Rekap pendapatan, pembayaran, dan tagihan tertunggak seluruh cabang.
            </p>
        </div>
        <div style="font-size:64px;opacity:.08;line-height:1;flex-shrink:0">
            <i class="bi bi-cash-coin"></i>
        </div>
    </div>
</div>

{{-- STATS --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3 fade-up">
        <div class="stat-card" style="border-top:3px solid #10b981">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Total Pendapatan</div>
                    <div class="stat-value" style="color:#059669;font-size:20px">
                        Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                    </div>
                    <div class="stat-label" style="font-size:11px">
                        <i class="bi bi-check-circle-fill text-success me-1"></i>Semua waktu
                    </div>
                </div>
                <div class="stat-icon" style="background:linear-gradient(135deg,#059669,#10b981)">
                    <i class="bi bi-cash-stack" style="color:white"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.05s">
        <div class="stat-card" style="border-top:3px solid #c84ddf">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Bulan Ini</div>
                    <div class="stat-value" style="color:#68117e;font-size:20px">
                        Rp {{ number_format($monthRevenue, 0, ',', '.') }}
                    </div>
                    <div class="stat-label" style="font-size:11px">
                        <i class="bi bi-calendar-month me-1 text-primary"></i>{{ now()->locale('id')->isoFormat('MMMM Y') }}
                    </div>
                </div>
                <div class="stat-icon" style="background:linear-gradient(135deg,#68117e,#c84ddf)">
                    <i class="bi bi-calendar-check" style="color:white"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.10s">
        <div class="stat-card" style="border-top:3px solid #f6af23">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Tagihan Pending</div>
                    <div class="stat-value" style="color:#e09000;font-size:20px">
                        Rp {{ number_format($pendingTotal, 0, ',', '.') }}
                    </div>
                    <div class="stat-label" style="font-size:11px">
                        <i class="bi bi-hourglass-split text-warning me-1"></i>{{ $pendingCount }} invoice
                    </div>
                </div>
                <div class="stat-icon" style="background:linear-gradient(135deg,#e09000,#f6af23)">
                    <i class="bi bi-receipt" style="color:white"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.15s">
        <div class="stat-card" style="border-top:3px solid #ef4444">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Jatuh Tempo</div>
                    <div class="stat-value" style="color:#dc2626;font-size:28px">{{ $overdueCount }}</div>
                    <div class="stat-label" style="font-size:11px">
                        <i class="bi bi-exclamation-triangle text-danger me-1"></i>Invoice terlambat
                    </div>
                </div>
                <div class="stat-icon" style="background:linear-gradient(135deg,#dc2626,#ef4444)">
                    <i class="bi bi-exclamation-circle" style="color:white"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">

    {{-- REVENUE CHART --}}
    <div class="col-lg-8 fade-up">
        <div class="dashboard-card h-100">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h6 class="fw-bold mb-1" style="font-size:14px">Tren Pendapatan</h6>
                    <p class="text-muted mb-0" style="font-size:12px">6 bulan terakhir (pembayaran terverifikasi)</p>
                </div>
                <span class="badge" style="background:#ecfdf5;color:#059669;font-size:12px;padding:5px 12px;border-radius:8px">
                    <i class="bi bi-graph-up me-1"></i>Live
                </span>
            </div>
            <div id="chartRevenue"></div>
        </div>
    </div>

    {{-- PAYMENT METHODS --}}
    <div class="col-lg-4 fade-up" style="animation-delay:.05s">
        <div class="dashboard-card h-100">
            <h6 class="fw-bold mb-4" style="font-size:14px">
                <i class="bi bi-pie-chart-fill text-primary me-2"></i>Status Invoice
            </h6>
            <div id="chartInvoiceStatus"></div>
            @php
                $lunas    = Invoice::where('status','lunas')->count();
                $belum    = Invoice::where('status','belum_bayar')->count();
                $sebagian = Invoice::where('status','sebagian')->count();
                $totalInv = max($lunas + $belum + $sebagian, 1);
            @endphp
            <div class="mt-3 d-flex flex-column gap-2">
                @foreach([['Lunas','#10b981',$lunas],['Belum Lunas','#f6af23',$belum],['Sebagian','#c84ddf',$sebagian]] as [$lbl,$clr,$val])
                <div class="d-flex align-items-center justify-content-between" style="font-size:12.5px">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width:10px;height:10px;border-radius:3px;background:{{$clr}}"></div>
                        <span>{{ $lbl }}</span>
                    </div>
                    <span class="fw-bold">{{ $val }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

</div>

{{-- TABLES ROW --}}
<div class="row g-4">

    {{-- RECENT PAYMENTS --}}
    <div class="col-lg-7 fade-up">
        <div class="dashboard-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0" style="font-size:14px">
                    <i class="bi bi-check-circle-fill text-success me-2"></i>Pembayaran Terverifikasi
                </h6>
                <a href="{{ route('admin.payments.index') }}" class="btn btn-sm btn-outline-primary" style="border-radius:8px;font-size:11px;padding:4px 12px">
                    Semua <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size:12.5px">
                    <thead>
                        <tr style="background:var(--input-bg)">
                            <th class="py-2" style="color:var(--text-muted);font-weight:600;font-size:11px;text-transform:uppercase">Siswa</th>
                            <th class="py-2 d-none d-md-table-cell" style="color:var(--text-muted);font-weight:600;font-size:11px;text-transform:uppercase">Metode</th>
                            <th class="py-2" style="color:var(--text-muted);font-weight:600;font-size:11px;text-transform:uppercase">Jumlah</th>
                            <th class="py-2" style="color:var(--text-muted);font-weight:600;font-size:11px;text-transform:uppercase">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentPayments as $p)
                        <tr>
                            <td class="py-3">
                                <div class="fw-semibold" style="font-size:13px">{{ $p->invoice?->siswa?->name ?? '—' }}</div>
                                <div class="text-muted" style="font-size:11px">{{ $p->invoice?->cabang?->name ?? '—' }}</div>
                            </td>
                            <td class="py-3 d-none d-md-table-cell">
                                <span class="badge" style="background:var(--input-bg);color:var(--text-muted);font-size:11px;padding:3px 8px;border-radius:6px">
                                    {{ ucwords(str_replace('_',' ', $p->metode ?? '-')) }}
                                </span>
                            </td>
                            <td class="py-3 fw-bold text-success" style="font-size:13px">
                                Rp {{ number_format($p->jumlah, 0, ',', '.') }}
                            </td>
                            <td class="py-3 text-muted" style="font-size:11.5px">
                                {{ optional($p->created_at)->diffForHumans() }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted" style="font-size:13px">
                                <i class="bi bi-inbox d-block mb-2" style="font-size:2rem;opacity:.3"></i>
                                Belum ada pembayaran terverifikasi
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- OUTSTANDING INVOICES --}}
    <div class="col-lg-5 fade-up" style="animation-delay:.05s">
        <div class="dashboard-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0" style="font-size:14px">
                    <i class="bi bi-hourglass-split text-warning me-2"></i>Tagihan Belum Lunas
                </h6>
                <span class="badge" style="background:#fef3c7;color:#92400e;font-size:11px">{{ $pendingCount }} item</span>
            </div>
            <div class="d-flex flex-column gap-2">
                @forelse($outstanding as $inv)
                @php
                    $overdue = $inv->jatuh_tempo && \Carbon\Carbon::parse($inv->jatuh_tempo)->isPast();
                @endphp
                <div class="d-flex align-items-center justify-content-between p-3 rounded-3"
                     style="background:{{ $overdue ? 'var(--overdue-bg,#fef2f2)' : 'var(--input-bg)' }};border:1px solid {{ $overdue ? 'var(--overdue-border,#fecaca)' : 'var(--card-border)' }}">
                    <div style="min-width:0">
                        <div class="fw-semibold text-truncate" style="font-size:12.5px;color:{{ $overdue ? '#dc2626' : 'var(--text-primary)' }}">
                            {{ $inv->siswa?->name ?? '—' }}
                        </div>
                        <div style="font-size:11px;color:var(--text-muted)">
                            @if($overdue)
                                <i class="bi bi-exclamation-triangle-fill text-danger me-1"></i>Terlambat
                            @else
                                <i class="bi bi-calendar3 me-1"></i>{{ optional($inv->jatuh_tempo)->diffForHumans() }}
                            @endif
                        </div>
                    </div>
                    <div class="text-end flex-shrink-0">
                        <div class="fw-bold" style="font-size:12.5px;color:{{ $overdue ? '#dc2626' : '#e09000' }}">
                            Rp {{ number_format($inv->total, 0, ',', '.') }}
                        </div>
                        <div style="font-size:10px;color:var(--text-muted)">{{ $inv->nomor_invoice }}</div>
                    </div>
                </div>
                @empty
                <div class="text-center py-4">
                    <i class="bi bi-check-circle-fill text-success d-block mb-2" style="font-size:2.5rem;opacity:.4"></i>
                    <p class="text-muted mb-0" style="font-size:13px">Semua tagihan sudah lunas!</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
let _chartRevenue = null, _chartStatus = null;

function initReportCharts() {
    const isDark    = document.documentElement.getAttribute('data-theme') === 'dark';
    const textColor = isDark ? '#94a3b8' : '#64748b';
    const gridColor = isDark ? 'rgba(255,255,255,.06)' : 'rgba(0,0,0,.05)';

    if (_chartRevenue) { _chartRevenue.destroy(); }
    if (_chartStatus)  { _chartStatus.destroy();  }

    _chartRevenue = new ApexCharts(document.getElementById('chartRevenue'), {
        chart: { type:'area', height:200, toolbar:{show:false}, background:'transparent',
                 fontFamily:'Inter, sans-serif', animations:{enabled:true,speed:800} },
        series: [{ name:'Pendapatan', data: {!! json_encode($monthlyData) !!} }],
        xaxis: { categories: {!! json_encode($monthlyLabels) !!},
                 labels:{style:{colors:textColor,fontSize:'11px'}},
                 axisBorder:{show:false}, axisTicks:{show:false} },
        yaxis: { labels:{ style:{colors:textColor,fontSize:'11px'},
                           formatter: v => 'Rp '+Intl.NumberFormat('id').format(v) } },
        colors: ['#10b981'],
        fill: { type:'gradient', gradient:{shadeIntensity:1,opacityFrom:.4,opacityTo:.02,stops:[0,100]} },
        stroke: { curve:'smooth', width:2.5 },
        dataLabels: { enabled:false },
        grid: { borderColor:gridColor, strokeDashArray:4 },
        tooltip: { theme:isDark?'dark':'light', y:{ formatter: v => 'Rp '+Intl.NumberFormat('id').format(v) } },
        markers: { size:4, strokeWidth:2, strokeColors: isDark ? '#2d0a3e' : '#fff', colors:['#10b981'] }
    });
    _chartRevenue.render();

    _chartStatus = new ApexCharts(document.getElementById('chartInvoiceStatus'), {
        chart: { type:'donut', height:160, fontFamily:'Inter, sans-serif', background:'transparent' },
        series: [{{ $lunas }}, {{ $belum }}, {{ $sebagian }}],
        labels: ['Lunas','Belum Lunas','Sebagian'],
        colors: ['#10b981','#f6af23','#c84ddf'],
        legend: { show:false },
        plotOptions: { pie:{ donut:{ size:'70%', labels:{
            show:true, total:{ show:true, label:'Total', color:textColor, fontSize:'12px', fontWeight:600,
            formatter: () => '{{ $lunas + $belum + $sebagian }}' }
        }}}},
        stroke: { show:false },
        dataLabels:{ enabled:false },
        tooltip: { theme:isDark?'dark':'light' }
    });
    _chartStatus.render();
}

document.addEventListener('DOMContentLoaded', initReportCharts);
document.addEventListener('themechange', function() { setTimeout(initReportCharts, 60); });
</script>
@endpush
