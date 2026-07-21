<?php $__env->startSection('title', 'Laporan Keuangan'); ?>
<?php $__env->startSection('page-title', 'Laporan Keuangan'); ?>

<?php $__env->startSection('content'); ?>

<?php
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Salary;
use Carbon\Carbon;

$activeTab = request('tab', 'pendapatan');

$courseRevTotal = \App\Models\StudentCoursePayment::where('status','verified')->sum('amount');
$totalRevenue   = Payment::where('status','verified')->sum('jumlah') + $courseRevTotal;
$monthRevenue   = Payment::where('status','verified')
                    ->whereMonth('tanggal_pembayaran', now()->month)
                    ->whereYear('tanggal_pembayaran',  now()->year)
                    ->sum('jumlah');
$pendingCount   = Invoice::where('status','belum_bayar')->count();
$pendingTotal   = Invoice::where('status','belum_bayar')->sum('total');
$overdueCount   = Invoice::where('status','belum_bayar')
                    ->where('jatuh_tempo','<', now()->toDateString())->count();

// Invoice status counts (used in charts)
$lunas    = Invoice::where('status','lunas')->count();
$belum    = Invoice::where('status','belum_bayar')->count();
$sebagian = Invoice::where('status','sebagian')->count();

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

// Salary (pengeluaran) stats — compute for tab 'gaji'
$totalSalaryPaid = Salary::where('status','dibayar')->sum('total_gaji');
$monthSalaryPaid = Salary::where('status','dibayar')
                    ->whereMonth('tanggal_pembayaran', now()->month)
                    ->whereYear('tanggal_pembayaran', now()->year)
                    ->sum('total_gaji');
$salaryPaidCount = Salary::where('status','dibayar')->count();
$salaryPendingCount = Salary::where('status','pending')->count();
$salaryCanceledCount = Salary::where('status','batal')->count();

$salaryMonthlyLabels = $monthlyLabels;
$salaryMonthlyData = [];
for ($i = 5; $i >= 0; $i--) {
    $d = now()->subMonths($i);
    $salaryMonthlyData[] = (int) Salary::where('status','dibayar')
                                ->whereMonth('tanggal_pembayaran', $d->month)
                                ->whereYear('tanggal_pembayaran',  $d->year)
                                ->sum('total_gaji');
}

$recentSalaries = Salary::with(['guru','cabang'])->where('status','dibayar')->latest()->limit(10)->get();
$outstandingSalaries = Salary::with(['guru'])->where('status','pending')->orderBy('periode')->limit(10)->get();
?>


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


<div class="mb-3 fade-up">
    <div class="d-flex align-items-center gap-2">
        <a href="<?php echo e(request()->fullUrlWithQuery(['tab'=>'pendapatan'])); ?>" class="btn btn-sm <?php echo e($activeTab == 'pendapatan' ? 'btn-primary' : 'btn-outline-primary'); ?>" style="border-radius:8px;padding:6px 12px;font-size:13px">
            <i class="bi bi-cash-stack me-1"></i> Pendapatan dari Siswa
        </a>
        <a href="<?php echo e(request()->fullUrlWithQuery(['tab'=>'gaji'])); ?>" class="btn btn-sm <?php echo e($activeTab == 'gaji' ? 'btn-primary' : 'btn-outline-primary'); ?>" style="border-radius:8px;padding:6px 12px;font-size:13px">
            <i class="bi bi-wallet2 me-1"></i> Pengeluaran Gaji Guru
        </a>
    </div>
</div>

<?php if($activeTab == 'pendapatan'): ?>


<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3 fade-up">
        <div class="stat-card" style="border-top:3px solid #10b981">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Total Pendapatan</div>
                    <div class="stat-value" style="color:#059669;font-size:20px">
                        Rp <?php echo e(number_format($totalRevenue, 0, ',', '.')); ?>

                    </div>
                    <div class="stat-label" style="font-size:11px">
                        <i class="bi bi-check-circle-fill text-success me-1"></i>Semua waktu
                    </div>
                </div>
                <div class="stat-icon bg-success-soft" style="color:white">
                    <i class="bi bi-cash-stack"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.05s">
        <div class="stat-card" style="border-top:3px solid #c84ddf">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Bulan Ini</div>
                    <div class="stat-value text-primary" style="font-size:20px">
                        Rp <?php echo e(number_format($monthRevenue, 0, ',', '.')); ?>

                    </div>
                    <div class="stat-label" style="font-size:11px">
                        <i class="bi bi-calendar-month me-1 text-primary"></i><?php echo e(now()->locale('id')->isoFormat('MMMM Y')); ?>

                    </div>
                </div>
                <div class="stat-icon bg-primary-soft" style="color:white">
                    <i class="bi bi-calendar-check"></i>
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
                        Rp <?php echo e(number_format($pendingTotal, 0, ',', '.')); ?>

                    </div>
                    <div class="stat-label" style="font-size:11px">
                        <i class="bi bi-hourglass-split text-warning me-1"></i><?php echo e($pendingCount); ?> invoice
                    </div>
                </div>
                <div class="stat-icon bg-warning-soft" style="color:white">
                    <i class="bi bi-receipt"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.15s">
        <div class="stat-card" style="border-top:3px solid #ef4444">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Jatuh Tempo</div>
                    <div class="stat-value" style="color:#dc2626;font-size:28px"><?php echo e($overdueCount); ?></div>
                    <div class="stat-label" style="font-size:11px">
                        <i class="bi bi-exclamation-triangle text-danger me-1"></i>Invoice terlambat
                    </div>
                </div>
                <div class="stat-icon bg-danger-soft" style="color:white">
                    <i class="bi bi-exclamation-circle"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">

    
    <div class="col-lg-8 fade-up">
        <div class="dashboard-card h-100">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h6 class="fw-bold mb-1" style="font-size:14px">Tren Pendapatan</h6>
                    <p class="text-muted mb-0" style="font-size:12px">6 bulan terakhir (pembayaran terverifikasi)</p>
                </div>
                <span class="badge" style="background:var(--soft-success-bg);color:var(--soft-success-text);font-size:12px;padding:5px 12px;border-radius:8px">
                    <i class="bi bi-graph-up me-1"></i>Live
                </span>
            </div>
            <div id="chartRevenue"></div>
        </div>
    </div>

    
    <div class="col-lg-4 fade-up" style="animation-delay:.05s">
        <div class="dashboard-card h-100">
            <h6 class="fw-bold mb-4" style="font-size:14px">
                <i class="bi bi-pie-chart-fill text-primary me-2"></i>Status Invoice
            </h6>
            <div id="chartInvoiceStatus"></div>
            <?php $totalInv = max($lunas + $belum + $sebagian, 1); ?>
            <div class="mt-3 d-flex flex-column gap-2">
                <?php $__currentLoopData = [['Lunas','#10b981',$lunas],['Belum Lunas','#f6af23',$belum],['Sebagian','#c84ddf',$sebagian]]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$lbl,$clr,$val]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="d-flex align-items-center justify-content-between" style="font-size:12.5px">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width:10px;height:10px;border-radius:3px;background:<?php echo e($clr); ?>"></div>
                        <span><?php echo e($lbl); ?></span>
                    </div>
                    <span class="fw-bold"><?php echo e($val); ?></span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>

</div>


<div class="row g-4">

    
    <div class="col-lg-7 fade-up">
        <div class="dashboard-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0" style="font-size:14px">
                    <i class="bi bi-check-circle-fill text-success me-2"></i>Pembayaran Terverifikasi
                </h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-modern align-middle mb-0">
                    <thead class="thead-modern">
                        <tr>
                            <th>Siswa</th>
                            <th class="d-none d-md-table-cell">Metode</th>
                            <th>Jumlah</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $recentPayments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="py-3">
                                <div class="fw-semibold" style="font-size:13px"><?php echo e($p->invoice?->siswa?->name ?? '—'); ?></div>
                                <div class="text-muted" style="font-size:11px"><?php echo e($p->invoice?->cabang?->name ?? '—'); ?></div>
                            </td>
                            <td class="py-3 d-none d-md-table-cell">
                                <span class="badge" style="background:var(--input-bg);color:var(--text-muted);font-size:11px;padding:3px 8px;border-radius:6px">
                                    <?php echo e(ucwords(str_replace('_',' ', $p->metode ?? '-'))); ?>

                                </span>
                            </td>
                            <td class="py-3 fw-bold text-success" style="font-size:13px">
                                Rp <?php echo e(number_format($p->jumlah, 0, ',', '.')); ?>

                            </td>
                            <td class="py-3 text-muted" style="font-size:11.5px">
                                <?php echo e(optional($p->created_at)->diffForHumans()); ?>

                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted" style="font-size:13px">
                                <i class="bi bi-inbox d-block mb-2" style="font-size:2rem;opacity:.3"></i>
                                Belum ada pembayaran terverifikasi
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    
    <div class="col-lg-5 fade-up" style="animation-delay:.05s">
        <div class="dashboard-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0" style="font-size:14px">
                    <i class="bi bi-hourglass-split text-warning me-2"></i>Tagihan Belum Lunas
                </h6>
                <span class="badge" style="background:var(--soft-warning-bg);color:var(--soft-warning-text);font-size:11px"><?php echo e($pendingCount); ?> item</span>
            </div>
            <div class="d-flex flex-column gap-2">
                <?php $__empty_1 = true; $__currentLoopData = $outstanding; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $overdue = $inv->jatuh_tempo && \Carbon\Carbon::parse($inv->jatuh_tempo)->isPast();
                ?>
                <div class="d-flex align-items-center justify-content-between p-3 rounded-3"
                     style="background:<?php echo e($overdue ? 'var(--overdue-bg,#fef2f2)' : 'var(--input-bg)'); ?>;border:1px solid <?php echo e($overdue ? 'var(--overdue-border,#fecaca)' : 'var(--card-border)'); ?>">
                    <div style="min-width:0">
                        <div class="fw-semibold text-truncate" style="font-size:12.5px;color:<?php echo e($overdue ? '#dc2626' : 'var(--text-primary)'); ?>">
                            <?php echo e($inv->siswa?->name ?? '—'); ?>

                        </div>
                        <div style="font-size:11px;color:var(--text-muted)">
                            <?php if($overdue): ?>
                                <i class="bi bi-exclamation-triangle-fill text-danger me-1"></i>Terlambat
                            <?php else: ?>
                                <i class="bi bi-calendar3 me-1"></i><?php echo e(optional($inv->jatuh_tempo)->diffForHumans()); ?>

                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="text-end flex-shrink-0">
                        <div class="fw-bold" style="font-size:12.5px;color:<?php echo e($overdue ? '#dc2626' : '#e09000'); ?>">
                            Rp <?php echo e(number_format($inv->total, 0, ',', '.')); ?>

                        </div>
                        <div style="font-size:10px;color:var(--text-muted)"><?php echo e($inv->nomor_invoice); ?></div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-center py-4">
                    <i class="bi bi-check-circle-fill text-success d-block mb-2" style="font-size:2.5rem;opacity:.4"></i>
                    <p class="text-muted mb-0" style="font-size:13px">Semua tagihan sudah lunas!</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>

<?php else: ?>


<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3 fade-up">
        <div class="stat-card" style="border-top:3px solid #ef4444">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Total Pengeluaran Gaji</div>
                    <div class="stat-value" style="color:#dc2626;font-size:20px">
                        Rp <?php echo e(number_format($totalSalaryPaid, 0, ',', '.')); ?>

                    </div>
                    <div class="stat-label" style="font-size:11px">
                        <i class="bi bi-wallet2 text-danger me-1"></i>Semua waktu
                    </div>
                </div>
                <div class="stat-icon bg-danger-soft" style="color:white">
                    <i class="bi bi-cash-stack"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.05s">
        <div class="stat-card" style="border-top:3px solid #c84ddf">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Bulan Ini</div>
                    <div class="stat-value text-primary" style="font-size:20px">
                        Rp <?php echo e(number_format($monthSalaryPaid, 0, ',', '.')); ?>

                    </div>
                    <div class="stat-label" style="font-size:11px">
                        <i class="bi bi-calendar-month me-1 text-primary"></i><?php echo e(now()->locale('id')->isoFormat('MMMM Y')); ?>

                    </div>
                </div>
                <div class="stat-icon bg-primary-soft" style="color:white">
                    <i class="bi bi-calendar-check"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.10s">
        <div class="stat-card" style="border-top:3px solid #10b981">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Gaji Dibayar</div>
                    <div class="stat-value" style="color:#059669;font-size:20px"><?php echo e($salaryPaidCount); ?></div>
                    <div class="stat-label" style="font-size:11px">
                        <i class="bi bi-check-circle-fill text-success me-1"></i>Transaksi dibayar
                    </div>
                </div>
                <div class="stat-icon bg-success-soft" style="color:white">
                    <i class="bi bi-people"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.15s">
        <div class="stat-card" style="border-top:3px solid #f6af23">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Gaji Pending</div>
                    <div class="stat-value" style="color:#e09000;font-size:28px"><?php echo e($salaryPendingCount); ?></div>
                    <div class="stat-label" style="font-size:11px">
                        <i class="bi bi-hourglass-split text-warning me-1"></i>Belum dibayar
                    </div>
                </div>
                <div class="stat-icon bg-warning-soft" style="color:white">
                    <i class="bi bi-clock-history"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">

    
    <div class="col-lg-8 fade-up">
        <div class="dashboard-card h-100">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h6 class="fw-bold mb-1" style="font-size:14px">Tren Pengeluaran Gaji</h6>
                    <p class="text-muted mb-0" style="font-size:12px">6 bulan terakhir (gaji dibayar)</p>
                </div>
                <span class="badge" style="background:var(--soft-danger-bg);color:var(--soft-danger-text);font-size:12px;padding:5px 12px;border-radius:8px">
                    <i class="bi bi-graph-down me-1"></i>Live
                </span>
            </div>
            <div id="chartSalary"></div>
        </div>
    </div>

    
    <div class="col-lg-4 fade-up" style="animation-delay:.05s">
        <div class="dashboard-card h-100">
            <h6 class="fw-bold mb-4" style="font-size:14px">
                <i class="bi bi-pie-chart-fill text-danger me-2"></i>Status Gaji
            </h6>
            <div id="chartSalaryStatus"></div>
            <div class="mt-3 d-flex flex-column gap-2">
                <?php $__currentLoopData = [['Dibayar','#10b981',$salaryPaidCount],['Pending','#f6af23',$salaryPendingCount],['Batal','#ef4444',$salaryCanceledCount]]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$lbl,$clr,$val]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="d-flex align-items-center justify-content-between" style="font-size:12.5px">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width:10px;height:10px;border-radius:3px;background:<?php echo e($clr); ?>"></div>
                        <span><?php echo e($lbl); ?></span>
                    </div>
                    <span class="fw-bold"><?php echo e($val); ?></span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>

</div>


<div class="row g-4">

    
    <div class="col-lg-7 fade-up">
        <div class="dashboard-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0" style="font-size:14px">
                    <i class="bi bi-cash-stack text-danger me-2"></i>Gaji Terbayar
                </h6>
                <a href="<?php echo e(route('admin.salaries.index')); ?>" class="btn btn-sm btn-outline-primary" style="border-radius:8px;font-size:11px;padding:4px 12px">
                    Semua <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-modern align-middle mb-0">
                    <thead class="thead-modern">
                        <tr>
                            <th>Guru</th>
                            <th class="d-none d-md-table-cell">Cabang</th>
                            <th>Jumlah</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $recentSalaries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="py-3">
                                <div class="fw-semibold" style="font-size:13px"><?php echo e($s->guru?->name ?? '—'); ?></div>
                                <div class="text-muted" style="font-size:11px"><?php echo e($s->guru?->branch?->name ?? ($s->cabang?->name ?? '—')); ?></div>
                            </td>
                            <td class="py-3 d-none d-md-table-cell">
                                <span class="badge" style="background:var(--input-bg);color:var(--text-muted);font-size:11px;padding:3px 8px;border-radius:6px">
                                    <?php echo e($s->cabang?->name ?? '-'); ?>

                                </span>
                            </td>
                            <td class="py-3 fw-bold text-danger" style="font-size:13px">
                                Rp <?php echo e(number_format($s->total_gaji, 0, ',', '.')); ?>

                            </td>
                            <td class="py-3 text-muted" style="font-size:11.5px">
                                <?php echo e(optional($s->tanggal_pembayaran)->format('d M Y') ?? optional($s->created_at)->diffForHumans()); ?>

                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted" style="font-size:13px">
                                <i class="bi bi-inbox d-block mb-2" style="font-size:2rem;opacity:.3"></i>
                                Belum ada pembayaran gaji
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    
    <div class="col-lg-5 fade-up" style="animation-delay:.05s">
        <div class="dashboard-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0" style="font-size:14px">
                    <i class="bi bi-hourglass-split text-warning me-2"></i>Gaji Belum Dibayar
                </h6>
                <span class="badge" style="background:var(--soft-warning-bg);color:var(--soft-warning-text);font-size:11px"><?php echo e($salaryPendingCount); ?> item</span>
            </div>
            <div class="d-flex flex-column gap-2">
                <?php $__empty_1 = true; $__currentLoopData = $outstandingSalaries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="d-flex align-items-center justify-content-between p-3 rounded-3" style="background:var(--input-bg);border:1px solid var(--card-border)">
                    <div style="min-width:0">
                        <div class="fw-semibold text-truncate" style="font-size:12.5px;"><?php echo e($s->guru?->name ?? '—'); ?></div>
                        <div style="font-size:11px;color:var(--text-muted)">Periode: <?php echo e($s->periode); ?></div>
                    </div>
                    <div class="text-end flex-shrink-0">
                        <div class="fw-bold" style="font-size:12.5px;color:#e09000">Rp <?php echo e(number_format($s->total_gaji, 0, ',', '.')); ?></div>
                        <div style="font-size:10px;color:var(--text-muted)"><?php echo e($s->id ? 'ID#'.$s->id : ''); ?></div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-center py-4">
                    <i class="bi bi-check-circle-fill text-success d-block mb-2" style="font-size:2.5rem;opacity:.4"></i>
                    <p class="text-muted mb-0" style="font-size:13px">Tidak ada gaji tertunda</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>

<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
let _chartA = null, _chartB = null;

function renderRevenueCharts(isDark, textColor, gridColor) {
    if (_chartA) _chartA.destroy();
    if (_chartB) _chartB.destroy();

    _chartA = new ApexCharts(document.getElementById('chartRevenue'), {
        chart: { type:'area', height:200, toolbar:{show:false}, background:'transparent',
                 fontFamily:'Inter, sans-serif', animations:{enabled:true,speed:800} },
        series: [{ name:'Pendapatan', data: <?php echo json_encode($monthlyData); ?> }],
        xaxis: { categories: <?php echo json_encode($monthlyLabels); ?>, labels:{style:{colors:textColor,fontSize:'11px'}}, axisBorder:{show:false}, axisTicks:{show:false} },
        yaxis: { labels:{ style:{colors:textColor,fontSize:'11px'}, formatter: v => 'Rp '+Intl.NumberFormat('id').format(v) } },
        colors: ['#10b981'], fill: { type:'gradient', gradient:{shadeIntensity:1,opacityFrom:.4,opacityTo:.02,stops:[0,100]} },
        stroke: { curve:'smooth', width:2.5 }, dataLabels: { enabled:false }, grid: { borderColor:gridColor, strokeDashArray:4 },
        tooltip: { theme:isDark?'dark':'light', y:{ formatter: v => 'Rp '+Intl.NumberFormat('id').format(v) } },
        markers: { size:4, strokeWidth:2, strokeColors: isDark ? '#2d0a3e' : '#fff', colors:['#10b981'] }
    });
    _chartA.render();

    _chartB = new ApexCharts(document.getElementById('chartInvoiceStatus'), {
        chart: { type:'donut', height:160, fontFamily:'Inter, sans-serif', background:'transparent' },
        series: [<?php echo e($lunas); ?>, <?php echo e($belum); ?>, <?php echo e($sebagian); ?>],
        labels: ['Lunas','Belum Lunas','Sebagian'],
        colors: ['#10b981','#f6af23','#c84ddf'], legend: { show:false },
        plotOptions: { pie:{ donut:{ size:'70%', labels:{ show:true, total:{ show:true, label:'Total', color:textColor, fontSize:'12px', fontWeight:600, formatter: () => '<?php echo e($lunas + $belum + $sebagian); ?>' } } } } },
        stroke: { show:false }, dataLabels:{ enabled:false }, tooltip: { theme:isDark?'dark':'light' }
    });
    _chartB.render();
}

function renderSalaryCharts(isDark, textColor, gridColor) {
    if (_chartA) _chartA.destroy();
    if (_chartB) _chartB.destroy();

    _chartA = new ApexCharts(document.getElementById('chartSalary'), {
        chart: { type:'area', height:200, toolbar:{show:false}, background:'transparent', fontFamily:'Inter, sans-serif' },
        series: [{ name:'Pengeluaran', data: <?php echo json_encode($salaryMonthlyData); ?> }],
        xaxis: { categories: <?php echo json_encode($salaryMonthlyLabels); ?>, labels:{style:{colors:textColor,fontSize:'11px'}}, axisBorder:{show:false}, axisTicks:{show:false} },
        yaxis: { labels:{ style:{colors:textColor,fontSize:'11px'}, formatter: v => 'Rp '+Intl.NumberFormat('id').format(v) } },
        colors: ['#ef4444'], fill: { type:'gradient', gradient:{shadeIntensity:1,opacityFrom:.35,opacityTo:.02,stops:[0,100]} },
        stroke: { curve:'smooth', width:2.5 }, dataLabels: { enabled:false }, grid: { borderColor:gridColor, strokeDashArray:4 },
        tooltip: { theme:isDark?'dark':'light', y:{ formatter: v => 'Rp '+Intl.NumberFormat('id').format(v) } },
        markers: { size:4, strokeWidth:2, strokeColors: isDark ? '#2d0a3e' : '#fff', colors:['#ef4444'] }
    });
    _chartA.render();

    _chartB = new ApexCharts(document.getElementById('chartSalaryStatus'), {
        chart: { type:'donut', height:160, fontFamily:'Inter, sans-serif', background:'transparent' },
        series: [<?php echo e($salaryPaidCount); ?>, <?php echo e($salaryPendingCount); ?>, <?php echo e($salaryCanceledCount); ?>],
        labels: ['Dibayar','Pending','Batal'], colors: ['#10b981','#f6af23','#ef4444'], legend: { show:false },
        plotOptions: { pie:{ donut:{ size:'70%', labels:{ show:true, total:{ show:true, label:'Total', color:textColor, fontSize:'12px', fontWeight:600, formatter: () => '<?php echo e($salaryPaidCount + $salaryPendingCount + $salaryCanceledCount); ?>' } } } } },
        stroke: { show:false }, dataLabels:{ enabled:false }, tooltip: { theme:isDark?'dark':'light' }
    });
    _chartB.render();
}

function initReportCharts() {
    const isDark    = document.documentElement.getAttribute('data-theme') === 'dark';
    const textColor = isDark ? '#94a3b8' : '#64748b';
    const gridColor = isDark ? 'rgba(255,255,255,.06)' : 'rgba(0,0,0,.05)';
    const active    = '<?php echo e($activeTab); ?>';

    if (active === 'pendapatan') {
        renderRevenueCharts(isDark, textColor, gridColor);
    } else {
        renderSalaryCharts(isDark, textColor, gridColor);
    }
}

document.addEventListener('DOMContentLoaded', initReportCharts);
document.addEventListener('themechange', function() { setTimeout(initReportCharts, 60); });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Edu Juanda Pratama\Downloads\smart-center-indonesia-1 (2)\smart-center-indonesia\resources\views/admin/reports/index.blade.php ENDPATH**/ ?>