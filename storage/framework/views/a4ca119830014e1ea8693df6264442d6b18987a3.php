<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('page-title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>

<?php
    $user = auth()->user();
    $role = $user->getRoleNames()->first() ?? 'user';
    $isOwnerAdmin = in_array($role, ['owner','admin']);

    $branchId = ($role === 'admin') ? $user->branch_id : null;

    $totalStudents  = \App\Models\Student::when($branchId, fn($q) => $q->where('branch_id', $branchId))->count();
    $activeStudents = \App\Models\Student::where('status','aktif')->when($branchId, fn($q) => $q->where('branch_id', $branchId))->count();
    $totalBranches  = \App\Models\Branch::count();
    $activeBranches = \App\Models\Branch::where('status','active')->count();
    $totalTeachers  = \App\Models\Teacher::when($branchId, fn($q) => $q->where('branch_id', $branchId))->count();
    $activeTeachers = \App\Models\Teacher::where('status','aktif')->when($branchId, fn($q) => $q->where('branch_id', $branchId))->count();

    // Monthly enrollment (last 6 months)
    $months = collect(range(5,0))->map(function($i) {
        return \Carbon\Carbon::now()->subMonths($i);
    });
    $monthLabels = $months->map(fn($m) => $m->locale('id')->isoFormat('MMM'))->toArray();
    $monthCounts = $months->map(fn($m) =>
        \App\Models\Student::whereYear('created_at',$m->year)->whereMonth('created_at',$m->month)
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->count()
    )->toArray();

    // Gender & status for charts
    $male    = \App\Models\Student::where('gender','L')->when($branchId, fn($q) => $q->where('branch_id', $branchId))->count();
    $female  = \App\Models\Student::where('gender','P')->when($branchId, fn($q) => $q->where('branch_id', $branchId))->count();
    $aktif   = \App\Models\Student::where('status','aktif')->when($branchId, fn($q) => $q->where('branch_id', $branchId))->count();
    $nonaktif= \App\Models\Student::where('status','nonaktif')->when($branchId, fn($q) => $q->where('branch_id', $branchId))->count();
    $lulus   = \App\Models\Student::where('status','lulus')->when($branchId, fn($q) => $q->where('branch_id', $branchId))->count();

    // Recent registrations from public /register form
    $recentRegistrations = \App\Models\StudentRegistration::latest()->limit(10)->get();

    // Invoice revenue
    $revenueThisMonthInvoice = \App\Models\Payment::where('status', 'verified')
        ->whereYear('tanggal_pembayaran', now()->year)
        ->whereMonth('tanggal_pembayaran', now()->month)
        ->when($branchId, fn($q) => $q->where('cabang_id', $branchId))
        ->sum('jumlah');

    $revenueTotalInvoice = \App\Models\Payment::where('status', 'verified')
        ->when($branchId, fn($q) => $q->where('cabang_id', $branchId))
        ->sum('jumlah');

    // Course payment revenue
    $revenueThisMonthCourse = \App\Models\StudentCoursePayment::where('status', 'verified')
        ->whereYear('created_at', now()->year)
        ->whereMonth('created_at', now()->month)
        ->when($branchId, function($q) use ($branchId) {
            $q->whereHas('student', fn($sq) => $sq->where('branch_id', $branchId));
        })
        ->sum('amount');

    $revenueTotalCourse = \App\Models\StudentCoursePayment::where('status', 'verified')
        ->when($branchId, function($q) use ($branchId) {
            $q->whereHas('student', fn($sq) => $sq->where('branch_id', $branchId));
        })
        ->sum('amount');

    // Combined revenue
    $revenueThisMonth = $revenueThisMonthInvoice + $revenueThisMonthCourse;
    $revenueTotal = $revenueTotalInvoice + $revenueTotalCourse;
?>


<div class="dashboard-card mb-4 fade-up"
     style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-60px;top:-60px;width:220px;height:220px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;right:40px;bottom:-70px;width:180px;height:180px;background:rgba(255,255,255,.04);border-radius:50%;pointer-events:none"></div>
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3" style="position:relative">
        <div>
            <div style="font-size:12px;opacity:.65;margin-bottom:5px;letter-spacing:.05em;text-transform:uppercase">
                <?php echo e(\Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y')); ?>

            </div>
            <h3 style="font-size:clamp(18px,2.5vw,26px);font-weight:800;margin-bottom:8px;color:white;letter-spacing:-.02em">
                Halo, <?php echo e(explode(' ',$user->name)[0]); ?>! 👋
            </h3>
            <p style="opacity:.75;margin:0;font-size:14px;line-height:1.6">
                <?php if($role==='owner'): ?> Anda login sebagai <strong>Owner</strong> — akses penuh ke semua cabang dan laporan.
                <?php elseif($role==='admin'): ?> Anda login sebagai <strong>Admin Cabang</strong> — kelola siswa, guru, dan keuangan.
                <?php elseif($role==='guru'): ?> Anda login sebagai <strong>Guru</strong> — lihat jadwal dan input nilai siswa.
                <?php elseif($role==='siswa'): ?> Anda login sebagai <strong>Siswa</strong> — cek jadwal dan status pembayaran.
                <?php else: ?> Selamat bekerja hari ini!
                <?php endif; ?>
            </p>
        </div>
        <div style="font-size:80px;opacity:.1;line-height:1;flex-shrink:0">
            <i class="bi bi-mortarboard-fill"></i>
        </div>
    </div>
</div>


<div class="row g-3 mb-4">

    <div class="col-6 col-xl-3 fade-up">
        <div class="stat-card" style="border-top:3px solid #c84ddf">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Total Siswa</div>
                    <div class="stat-value text-primary count-up" data-target="<?php echo e($totalStudents); ?>">0</div>
                    <div class="stat-growth text-success">
                        <i class="bi bi-person-check-fill"></i>
                        <span class="count-up" data-target="<?php echo e($activeStudents); ?>">0</span> aktif
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
                    <div class="stat-value text-success count-up" data-target="<?php echo e($totalTeachers); ?>">0</div>
                    <div class="stat-growth text-muted">
                        <i class="bi bi-person-badge-fill"></i>
                        <span class="count-up" data-target="<?php echo e($activeTeachers); ?>">0</span> aktif
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
                    <div class="stat-value text-warning count-up" data-target="<?php echo e($activeBranches); ?>">0</div>
                    <div class="stat-growth text-muted">
                        <i class="bi bi-building"></i>
                        dari <span class="count-up" data-target="<?php echo e($totalBranches); ?>">0</span> cabang
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
                    <div class="stat-value text-success" style="font-size:<?php echo e(strlen('Rp '.number_format($revenueThisMonth,0,',','.')) > 14 ? '16px' : '20px'); ?>">
                        Rp <?php echo e(number_format($revenueThisMonth, 0, ',', '.')); ?>

                    </div>
                    <div class="stat-growth text-muted">
                        <i class="bi bi-calendar-month"></i>
                        Total: Rp <?php echo e(number_format($revenueTotal/1000000, 1, ',', '.')); ?>Jt
                    </div>
                </div>
                <div class="stat-icon bg-success-soft" style="color:white">
                    <i class="bi bi-cash-coin"></i>
                </div>
            </div>
        </div>
    </div>

</div>


<?php if($isOwnerAdmin): ?>
<div class="row g-3 mb-4">

    
    <div class="col-lg-7 fade-up">
        <div class="dashboard-card h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h6 class="fw-bold mb-0">
                    <i class="bi bi-graph-up-arrow text-primary me-2"></i>Tren Pendaftaran Siswa
                </h6>
                <span class="badge" style="background:var(--soft-primary-bg);color:var(--soft-primary-text);border:1px solid var(--soft-primary-border);font-size:11px">6 Bulan Terakhir</span>
            </div>
            <div id="chartTrend" style="min-height:200px"></div>
        </div>
    </div>

    
    <div class="col-lg-5 fade-up" style="animation-delay:.05s">
        <div class="dashboard-card h-100">
            <h6 class="fw-bold mb-3">
                <i class="bi bi-pie-chart-fill me-2" style="color:#c84ddf"></i>Distribusi Gender
            </h6>
            <div id="chartGender" style="min-height:200px"></div>
        </div>
    </div>

</div>


<div class="row g-3 mb-4">

    
    <div class="col-md-5 fade-up" style="animation-delay:.05s">
        <div class="dashboard-card h-100">
            <h6 class="fw-bold mb-3">
                <i class="bi bi-bar-chart-fill text-success me-2"></i>Status Siswa
            </h6>
            <div id="chartStatus" style="min-height:200px"></div>
        </div>
    </div>

    
    <div class="col-md-7 fade-up" style="animation-delay:.10s">
        <div class="dashboard-card h-100">
            <h6 class="fw-bold mb-3">
                <i class="bi bi-lightning-fill text-warning me-2"></i>Aksi Cepat
            </h6>
            <div class="row g-2">
                <div class="col-6">
                    <a href="<?php echo e(route('admin.students.index')); ?>"
                       class="d-flex align-items-center gap-2 p-3 rounded-3 text-decoration-none quick-dash"
                       style="background:var(--soft-primary-bg);border:1px solid var(--soft-primary-border)">
                        <i class="bi bi-person-plus-fill" style="font-size:1.4rem;color:#c84ddf"></i>
                        <div>
                            <div class="fw-semibold" style="font-size:12.5px;color:var(--text-primary)">Tambah Siswa</div>
                            <div style="font-size:11px;color:var(--text-muted)">Daftarkan siswa baru</div>
                        </div>
                    </a>
                </div>
                <div class="col-6">
                    <a href="<?php echo e(route('admin.teachers.index')); ?>"
                       class="d-flex align-items-center gap-2 p-3 rounded-3 text-decoration-none quick-dash"
                       style="background:var(--soft-primary-bg);border:1px solid var(--soft-primary-border)">
                        <i class="bi bi-person-workspace" style="font-size:1.4rem;color:#c84ddf"></i>
                        <div>
                            <div class="fw-semibold" style="font-size:12.5px;color:var(--text-primary)">Kelola Guru</div>
                            <div style="font-size:11px;color:var(--text-muted)">Manajemen pengajar</div>
                        </div>
                    </a>
                </div>
                <div class="col-6">
                    <a href="<?php echo e(route('admin.schedules.index')); ?>"
                       class="d-flex align-items-center gap-2 p-3 rounded-3 text-decoration-none quick-dash"
                       style="background:var(--soft-primary-bg);border:1px solid var(--soft-primary-border)">
                        <i class="bi bi-calendar-week" style="font-size:1.4rem;color:#c84ddf"></i>
                        <div>
                            <div class="fw-semibold" style="font-size:12.5px;color:var(--text-primary)">Lihat Jadwal</div>
                            <div style="font-size:11px;color:var(--text-muted)">Kalender akademik</div>
                        </div>
                    </a>
                </div>
                <?php if($role === 'owner'): ?>
                <div class="col-6">
                    <a href="<?php echo e(route('owner.branches.index')); ?>"
                       class="d-flex align-items-center gap-2 p-3 rounded-3 text-decoration-none quick-dash"
                       style="background:var(--soft-warning-bg);border:1px solid var(--soft-warning-border)">
                        <i class="bi bi-building" style="font-size:1.4rem;color:#e09000"></i>
                        <div>
                            <div class="fw-semibold" style="font-size:12.5px;color:var(--text-primary)">Monitor Cabang</div>
                            <div style="font-size:11px;color:var(--text-muted)">Semua cabang</div>
                        </div>
                    </a>
                </div>
                <?php endif; ?>
                <div class="col-6">
                    <a href="<?php echo e(route('admin.tryouts.index')); ?>"
                       class="d-flex align-items-center gap-2 p-3 rounded-3 text-decoration-none quick-dash"
                       style="background:var(--soft-primary-bg);border:1px solid var(--soft-primary-border)">
                        <i class="bi bi-ui-checks-grid" style="font-size:1.4rem;color:#c84ddf"></i>
                        <div>
                            <div class="fw-semibold" style="font-size:12.5px;color:var(--text-primary)">Tryout Online</div>
                            <div style="font-size:11px;color:var(--text-muted)">CBT & penilaian</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>
<?php endif; ?>


<?php if($isOwnerAdmin): ?>
<div class="dashboard-card fade-up">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h6 class="fw-bold mb-0">
            <i class="bi bi-clock-history text-primary me-2"></i>Siswa Terbaru Mendaftar
        </h6>
        <?php $pendingCount = $recentRegistrations->where('status','pending')->count(); ?>
        <?php if($pendingCount > 0): ?>
        <span class="badge" style="background:var(--soft-warning-bg);color:var(--soft-warning-text);font-size:.73rem;padding:5px 12px;border-radius:20px">
            <i class="bi bi-clock me-1"></i><?php echo e($pendingCount); ?> Menunggu
        </span>
        <?php endif; ?>
    </div>

    <?php $__empty_1 = true; $__currentLoopData = $recentRegistrations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $reg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <?php
        $avatar = 'https://ui-avatars.com/api/?name='.urlencode($reg->name).'&background='.($reg->gender==='P'?'ec4899':'c84ddf').'&color=fff&size=40';
        $regStatusColors = [
            'pending'  => ['var(--soft-warning-bg)','var(--soft-warning-text)'],
            'verified' => ['var(--soft-success-bg)','var(--soft-success-text)'],
            'rejected' => ['var(--soft-danger-bg)','var(--soft-danger-text)'],
        ];
        $rsc   = $regStatusColors[$reg->status] ?? ['var(--soft-muted-bg)','var(--text-muted)'];
        $accId = 'reg-acc-'.$reg->id;
        $interests = $reg->interests ?? [];
    ?>
    <div class="<?php echo e($idx > 0 ? 'mt-2' : ''); ?>" style="border:1px solid var(--card-border);border-radius:12px;overflow:hidden;background:var(--card-bg)">

        
        <div class="d-flex align-items-center gap-3 px-3 py-3 reg-acc-toggle"
             style="cursor:pointer;user-select:none;transition:background .15s"
             onclick="toggleRegAcc('<?php echo e($accId); ?>')">

            
            <img src="<?php echo e($avatar); ?>" class="rounded-circle flex-shrink-0" width="38" height="38"
                 style="object-fit:cover;border:2px solid var(--card-border)">

            
            <div class="flex-grow-1 min-width-0">
                <div class="fw-semibold" style="font-size:.9rem"><?php echo e($reg->name); ?></div>
                <div class="text-muted" style="font-size:.72rem;line-height:1.4">
                    <code style="background:var(--input-bg);padding:1px 5px;border-radius:4px;font-size:.68rem"><?php echo e($reg->no_reg); ?></code>
                    <?php if($reg->phone): ?> &nbsp;·&nbsp; <i class="bi bi-telephone" style="font-size:.65rem"></i> <?php echo e($reg->phone); ?> <?php endif; ?>
                    <?php if($reg->branch): ?> &nbsp;·&nbsp; <i class="bi bi-building" style="font-size:.65rem"></i> <?php echo e($reg->branch); ?> <?php endif; ?>
                    &nbsp;·&nbsp; <?php echo e($reg->created_at->format('d M Y')); ?>

                </div>
            </div>

            
            <div class="d-flex align-items-center gap-2 flex-shrink-0">
                <span class="badge" style="background:<?php echo e($rsc[0]); ?>;color:<?php echo e($rsc[1]); ?>;padding:4px 10px;border-radius:20px;font-size:.7rem">
                    <?php echo e($reg->status === 'pending' ? 'Menunggu' : ($reg->status === 'verified' ? 'Terverifikasi' : ucfirst($reg->status))); ?>

                </span>
                <?php if(count($interests) > 0): ?>
                <span class="badge d-none d-sm-inline-block" style="background:var(--soft-primary-bg);color:var(--soft-primary-text);padding:4px 9px;border-radius:20px;font-size:.7rem">
                    <i class="bi bi-journal-bookmark me-1"></i><?php echo e(count($interests)); ?> Minat
                </span>
                <?php endif; ?>
                <i class="bi bi-chevron-down reg-acc-chevron" id="chevron-<?php echo e($accId); ?>"
                   style="font-size:.8rem;color:var(--text-muted);transition:transform .22s"></i>
            </div>
        </div>

        
        <div id="<?php echo e($accId); ?>" style="display:none;border-top:1px solid var(--card-border)">

            
            <div class="px-3 pt-3 pb-2">
                <div class="row g-3">
                    
                    <div class="col-md-6">
                        <div class="p-3 rounded-3" style="background:var(--input-bg);border:1px solid var(--card-border)">
                            <div class="fw-semibold mb-2" style="font-size:.78rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em">Info Program</div>
                            <div class="d-flex flex-column gap-1" style="font-size:.82rem">
                                <div><span class="text-muted" style="min-width:110px;display:inline-block">Program</span> <strong><?php echo e($reg->program ?? '–'); ?></strong></div>
                                <div><span class="text-muted" style="min-width:110px;display:inline-block">Sistem</span> <?php echo e($reg->system ?? '–'); ?></div>
                                <div><span class="text-muted" style="min-width:110px;display:inline-block">Tempat Belajar</span> <?php echo e($reg->learning_place ?? '–'); ?></div>
                                <div><span class="text-muted" style="min-width:110px;display:inline-block">Cabang</span> <?php echo e($reg->branch ?? '–'); ?></div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="col-md-6">
                        <div class="p-3 rounded-3" style="background:var(--input-bg);border:1px solid var(--card-border)">
                            <div class="fw-semibold mb-2" style="font-size:.78rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em">Data Pribadi</div>
                            <div class="d-flex flex-column gap-1" style="font-size:.82rem">
                                <div><span class="text-muted" style="min-width:110px;display:inline-block">Jenis Kelamin</span> <?php echo e($reg->gender === 'L' ? '👦 Laki-laki' : ($reg->gender === 'P' ? '👧 Perempuan' : ($reg->gender ?? '–'))); ?></div>
                                <?php if($reg->birth_date): ?><div><span class="text-muted" style="min-width:110px;display:inline-block">Tgl Lahir</span> <?php echo e($reg->birth_date->format('d M Y')); ?></div><?php endif; ?>
                                <?php if($reg->address): ?><div><span class="text-muted" style="min-width:110px;display:inline-block">Alamat</span> <span style="white-space:pre-line"><?php echo e($reg->address); ?></span></div><?php endif; ?>
                                <?php if($reg->parent_name): ?><div><span class="text-muted" style="min-width:110px;display:inline-block">Orang Tua</span> <?php echo e($reg->parent_name); ?></div><?php endif; ?>
                                <?php if($reg->parent_phone): ?><div><span class="text-muted" style="min-width:110px;display:inline-block">HP Ortu</span> <?php echo e($reg->parent_phone); ?></div><?php endif; ?>
                            </div>
                        </div>
                    </div>

                    
                    <?php if(count($interests) > 0): ?>
                    <div class="col-12">
                        <div class="p-3 rounded-3" style="background:var(--input-bg);border:1px solid var(--card-border)">
                            <div class="fw-semibold mb-2" style="font-size:.78rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em">Program yang Diminati</div>
                            <div class="d-flex flex-wrap gap-2">
                                <?php $__currentLoopData = $interests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $interest): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="badge" style="background:var(--soft-primary-bg);color:var(--soft-primary-text);padding:5px 11px;border-radius:20px;font-size:.75rem;font-weight:500">
                                    <i class="bi bi-check2 me-1"></i><?php echo e($interest); ?>

                                </span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    
                    <?php if(($reg->day_preferences && count($reg->day_preferences) > 0) || $reg->schedule_time || $reg->start_date): ?>
                    <div class="col-12">
                        <div class="p-3 rounded-3" style="background:var(--input-bg);border:1px solid var(--card-border)">
                            <div class="fw-semibold mb-2" style="font-size:.78rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em">Jadwal Belajar</div>
                            <div class="d-flex flex-wrap gap-2 align-items-center" style="font-size:.82rem">
                                <?php if($reg->day_preferences && count($reg->day_preferences) > 0): ?>
                                    <?php $__currentLoopData = $reg->day_preferences; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span class="badge" style="background:var(--soft-info-bg);color:var(--soft-info-text);padding:4px 10px;border-radius:20px;font-size:.73rem"><?php echo e($day); ?></span>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                                <?php if($reg->schedule_time): ?> <span class="text-muted">·</span> <span>🕐 <?php echo e($reg->schedule_time); ?></span> <?php endif; ?>
                                <?php if($reg->start_date): ?> <span class="text-muted">·</span> <span>📅 Mulai <?php echo e($reg->start_date->format('d M Y')); ?></span> <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    
                    <?php if($reg->notes): ?>
                    <div class="col-12">
                        <div class="p-3 rounded-3" style="background:var(--soft-warning-bg);border:1px solid rgba(245,158,11,.15)">
                            <div class="fw-semibold mb-1" style="font-size:.78rem;color:var(--soft-warning-text);text-transform:uppercase;letter-spacing:.05em"><i class="bi bi-chat-text me-1"></i>Catatan</div>
                            <div style="font-size:.82rem;color:var(--text-primary)"><?php echo e($reg->notes); ?></div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            
            <div class="px-3 pb-3 d-flex gap-2 justify-content-end">
                <button type="button" class="btn btn-sm btn-outline-secondary"
                        onclick="openRegDetail(<?php echo e($reg->id); ?>)"
                        style="border-radius:8px;font-size:.78rem;padding:5px 14px">
                    <i class="bi bi-eye me-1"></i>Detail
                </button>
                <?php if($reg->status === 'pending'): ?>
                <a href="<?php echo e(route('admin.registration-list.process', $reg->id)); ?>"
                   class="btn btn-sm btn-outline-success"
                   style="border-radius:8px;font-size:.78rem;padding:5px 14px">
                    <i class="bi bi-check-circle me-1"></i>Verifikasi
                </a>
                <?php else: ?>
                <button type="button" class="btn btn-sm btn-outline-success" disabled
                        style="border-radius:8px;font-size:.78rem;padding:5px 14px;opacity:.5">
                    <i class="bi bi-check-circle-fill me-1"></i>Terverifikasi
                </button>
                <?php endif; ?>
                <button type="button" class="btn btn-sm btn-outline-danger"
                        onclick="deleteReg(<?php echo e($reg->id); ?>, this)"
                        style="border-radius:8px;font-size:.78rem;padding:5px 14px">
                    <i class="bi bi-trash me-1"></i>Hapus
                </button>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="text-center py-5">
        <i class="bi bi-inbox d-block mb-3" style="font-size:3rem;opacity:.2"></i>
        <div class="fw-semibold mb-1">Belum ada pendaftar</div>
        <p class="text-muted" style="font-size:.83rem">Data akan muncul ketika calon siswa mengisi form pendaftaran di halaman /register</p>
    </div>
    <?php endif; ?>
</div>


<div class="modal fade" id="regDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content" style="border-radius:16px;border:1px solid var(--card-border);background:var(--card-bg)">
            <div class="modal-header" style="background:linear-gradient(135deg,#260632,#461256,#c84ddf);border-radius:16px 16px 0 0;border:none">
                <h5 class="modal-title text-white fw-bold" style="font-size:1rem"><i class="bi bi-person-vcard me-2"></i>Detail Pendaftaran</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="regDetailBody">
                <div class="text-center py-5"><div class="spinner-border text-primary" role="status"></div></div>
            </div>
        </div>
    </div>
</div>

<style>
.reg-acc-toggle:hover { background: rgba(104,17,126,.04); }
[data-theme="dark"] .reg-acc-toggle:hover { background: rgba(200,77,223,.06); }
.reg-acc-chevron.open { transform: rotate(180deg); }
</style>
<script>
const _regDetailUrl = '<?php echo e(url("admin/student-registrations")); ?>';
const _regVerifyUrl = '<?php echo e(url("admin/student-registrations")); ?>';
const _regCsrf      = '<?php echo e(csrf_token()); ?>';

function toggleRegAcc(id) {
    const body    = document.getElementById(id);
    const chevron = document.getElementById('chevron-' + id);
    const isOpen  = body.style.display !== 'none';
    body.style.display = isOpen ? 'none' : 'block';
    if (chevron) chevron.classList.toggle('open', !isOpen);
}

function openRegDetail(id) {
    const modal = new bootstrap.Modal(document.getElementById('regDetailModal'));
    document.getElementById('regDetailBody').innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"></div></div>';
    modal.show();
    fetch(`${_regDetailUrl}/${id}`, { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': _regCsrf } })
        .then(r => { if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
        .then(d => {
            document.getElementById('regDetailBody').innerHTML = `
            <div class="row g-3">
                <div class="col-md-6"><h6 class="text-muted fw-semibold" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.06em">Data Diri</h6>
                <table class="table table-sm table-borderless" style="font-size:.83rem">
                    <tr><td class="text-muted" style="width:120px">No. Registrasi</td><td><code>${d.no_reg||'–'}</code></td></tr>
                    <tr><td class="text-muted">Nama</td><td>${d.name||'–'}</td></tr>
                    <tr><td class="text-muted">No. HP</td><td>${d.phone||'–'}</td></tr>
                    <tr><td class="text-muted">Jenis Kelamin</td><td>${d.gender==='L'?'Laki-laki':d.gender==='P'?'Perempuan':(d.gender||'–')}</td></tr>
                    <tr><td class="text-muted">Tempat Lahir</td><td>${d.birth_place||'–'}</td></tr>
                    <tr><td class="text-muted">Tgl Lahir</td><td>${d.birth_date||'–'}</td></tr>
                    <tr><td class="text-muted">Alamat</td><td>${d.address||'–'}</td></tr>
                    <tr><td class="text-muted">Orang Tua</td><td>${d.parent_name||'–'}</td></tr>
                    <tr><td class="text-muted">HP Ortu</td><td>${d.parent_phone||'–'}</td></tr>
                </table></div>
                <div class="col-md-6"><h6 class="text-muted fw-semibold" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.06em">Info Program</h6>
                <table class="table table-sm table-borderless" style="font-size:.83rem">
                    <tr><td class="text-muted" style="width:120px">Program</td><td><strong>${d.program||'–'}</strong></td></tr>
                    <tr><td class="text-muted">Sistem</td><td>${d.system||'–'}</td></tr>
                    <tr><td class="text-muted">Tempat</td><td>${d.learning_place||'–'}</td></tr>
                    <tr><td class="text-muted">Cabang</td><td>${d.branch||'–'}</td></tr>
                    <tr><td class="text-muted">Hari</td><td>${(d.day_preferences||[]).join(', ')||'–'}</td></tr>
                    <tr><td class="text-muted">Jam</td><td>${d.schedule_time||'–'}</td></tr>
                    <tr><td class="text-muted">Tgl Mulai</td><td>${d.start_date||'–'}</td></tr>
                    <tr><td class="text-muted">Status</td><td><span class="badge bg-${d.status==='verified'?'success':d.status==='rejected'?'danger':'warning'}">${d.status}</span></td></tr>
                    <tr><td class="text-muted">Mendaftar</td><td>${d.created_at||'–'}</td></tr>
                </table></div>
                ${(d.interests||[]).length ? `<div class="col-12"><h6 class="text-muted fw-semibold" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.06em">Program yang Diminati</h6><div class="d-flex flex-wrap gap-2">${(d.interests||[]).map(i=>`<span class="badge" style="background:var(--soft-primary-bg);color:var(--soft-primary-text);padding:5px 12px;border-radius:20px">${i}</span>`).join('')}</div></div>` : ''}
                ${d.notes ? `<div class="col-12"><h6 class="text-muted fw-semibold" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.06em">Catatan</h6><p style="font-size:.85rem">${d.notes}</p></div>` : ''}
            </div>`;
        })
        .catch(() => {
            document.getElementById('regDetailBody').innerHTML = '<div class="text-center py-4 text-danger"><i class="bi bi-x-circle-fill d-block mb-2" style="font-size:2rem"></i>Gagal memuat data.</div>';
        });
}

function deleteReg(id, btn) {
    confirmAction('Data pendaftaran ini akan <strong>dihapus permanen</strong>. Lanjutkan?', function() {
        btn.disabled = true;
        fetch(`${_regVerifyUrl}/${id}`, {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': _regCsrf }
        })
        .then(r => { if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
        .then(d => {
            if (d.success) { showToast(d.message, 'success'); setTimeout(() => location.reload(), 800); }
            else { showToast(d.message || 'Gagal menghapus.', 'error'); btn.disabled = false; }
        })
        .catch(() => { showToast('Terjadi kesalahan.', 'error'); btn.disabled = false; });
    }, null, { title: 'Hapus Pendaftaran', okText: '<i class="bi bi-trash me-1"></i>Hapus', btnClass: 'btn-danger' });
}
</script>
<?php endif; ?>


<?php if($role === 'guru'): ?>
<?php $teacher = \App\Models\Teacher::where('user_id', auth()->id())->first(); ?>
<div class="row g-3">
    <div class="col-12 fade-up">
        <div class="dashboard-card" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
            <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
            <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3" style="position:relative">
                <div class="d-flex align-items-center gap-4">
                    <?php if($teacher?->photo): ?>
                        <img src="<?php echo e(asset('storage/'.$teacher->photo)); ?>" alt="Foto Guru"
                             style="width:72px;height:72px;border-radius:18px;object-fit:cover;border:3px solid rgba(255,255,255,.3);flex-shrink:0">
                    <?php else: ?>
                        <div style="width:72px;height:72px;border-radius:18px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:28px;flex-shrink:0">
                            <i class="bi bi-person-workspace"></i>
                        </div>
                    <?php endif; ?>
                    <div>
                        <div style="font-size:11px;opacity:.6;text-transform:uppercase;letter-spacing:.08em;margin-bottom:4px">Portal Guru</div>
                        <h4 class="fw-bold mb-1" style="color:white;font-size:clamp(16px,2vw,22px)">Selamat datang, <?php echo e(explode(' ',$user->name)[0]); ?>!</h4>
                        <p style="opacity:.7;margin:0;font-size:13px"><?php echo e($teacher ? 'NIG: '.$teacher->nig.' · '.(is_array($teacher->subjects) ? implode(', ', $teacher->subjects) : ($teacher->subjects ?? 'Semua Mapel')) : 'Profil guru belum dilengkapi'); ?></p>
                    </div>
                </div>
                <div class="d-flex flex-column gap-2 flex-shrink-0">
                    <a href="<?php echo e(route('guru.dashboard')); ?>" class="btn fw-semibold px-4" style="background:rgba(255,255,255,.2);color:white;border:1px solid rgba(255,255,255,.3);border-radius:10px;backdrop-filter:blur(10px)">
                        <i class="bi bi-grid-fill me-2"></i>Buka Portal Guru
                    </a>
                    <a href="<?php echo e(route('guru.classes.index')); ?>" class="btn fw-semibold px-4" style="background:rgba(255,255,255,.1);color:white;border:1px solid rgba(255,255,255,.2);border-radius:10px">
                        <i class="bi bi-diagram-3 me-2"></i>Kelas Saya
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <?php
        $guruSchedulesToday = $teacher ? \App\Models\Schedule::where('guru_id', $teacher->id)->whereDate('tanggal', today())->count() : 0;
        $guruSchedulesWeek  = $teacher ? \App\Models\Schedule::where('guru_id', $teacher->id)->whereBetween('tanggal',[now()->startOfWeek(), now()->endOfWeek()])->count() : 0;
    ?>
    <div class="col-6 col-md-3 fade-up" style="animation-delay:.05s">
        <div class="stat-card" style="border-top:3px solid #c84ddf">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Sesi Hari Ini</div>
                    <div class="stat-value text-primary"><?php echo e($guruSchedulesToday); ?></div>
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
                    <div class="stat-value text-success"><?php echo e($guruSchedulesWeek); ?></div>
                    <div class="stat-growth text-muted"><i class="bi bi-calendar-week me-1"></i>Total sesi</div>
                </div>
                <div class="stat-icon bg-success-soft" style="color:white"><i class="bi bi-calendar-week"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3 fade-up" style="animation-delay:.15s">
        <div class="stat-card" style="border-top:3px solid #f6af23">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Jadwal Hari Ini</div>
                    <div class="stat-value text-warning" style="font-size:16px">Lihat Jadwal</div>
                    <div class="stat-growth text-muted"><i class="bi bi-calendar-check me-1"></i>Pertemuan</div>
                </div>
                <div class="stat-icon bg-warning-soft" style="color:white"><i class="bi bi-calendar-week-fill"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3 fade-up" style="animation-delay:.20s">
        <div class="stat-card" style="border-top:3px solid #68117e">
            <a href="<?php echo e(route('profile.edit')); ?>" class="text-decoration-none d-block">
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
<?php endif; ?>


<?php if($role === 'siswa'): ?>
<?php $student = \App\Models\Student::where('user_id', auth()->id())->first(); ?>
<div class="row g-3">
    <div class="col-12 fade-up">
        <div class="dashboard-card" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
            <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
            <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3" style="position:relative">
                <div class="d-flex align-items-center gap-4">
                    <?php if($student?->photo): ?>
                        <img src="<?php echo e(asset('storage/'.$student->photo)); ?>" alt="Foto Siswa"
                             style="width:72px;height:72px;border-radius:18px;object-fit:cover;border:3px solid rgba(255,255,255,.3);flex-shrink:0">
                    <?php else: ?>
                        <div style="width:72px;height:72px;border-radius:18px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:28px;flex-shrink:0">
                            <i class="bi bi-mortarboard-fill"></i>
                        </div>
                    <?php endif; ?>
                    <div>
                        <div style="font-size:11px;opacity:.6;text-transform:uppercase;letter-spacing:.08em;margin-bottom:4px">Portal Siswa</div>
                        <h4 class="fw-bold mb-1" style="color:white;font-size:clamp(16px,2vw,22px)">Halo, <?php echo e(explode(' ',$user->name)[0]); ?>! 👋</h4>
                        <p style="opacity:.7;margin:0;font-size:13px"><?php echo e($student ? 'NIS: '.$student->nis.' · Kelas: '.($student->grade ?? '-').' · '.$student->branch?->name : 'Profil siswa belum tersambung ke akun ini'); ?></p>
                    </div>
                </div>
                <div class="d-flex flex-column gap-2 flex-shrink-0">
                    <a href="<?php echo e(route('siswa.dashboard')); ?>" class="btn fw-semibold px-4" style="background:rgba(255,255,255,.2);color:white;border:1px solid rgba(255,255,255,.3);border-radius:10px;backdrop-filter:blur(10px)">
                        <i class="bi bi-grid-fill me-2"></i>Buka Portal Siswa
                    </a>
                    <a href="<?php echo e(route('siswa.dashboard')); ?>" class="btn fw-semibold px-4" style="background:rgba(255,255,255,.1);color:white;border:1px solid rgba(255,255,255,.2);border-radius:10px">
                        <i class="bi bi-calendar-event me-2"></i>Jadwal (diakses dari Portal)
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <?php
        $jadwalHariIni = $student ? \App\Models\Schedule::where('cabang_id', $student->branch_id)->whereDate('tanggal', today())->count() : 0;
        $jadwalMingguIni = $student ? \App\Models\Schedule::where('cabang_id', $student->branch_id)->whereBetween('tanggal',[now()->startOfWeek(), now()->endOfWeek()])->count() : 0;
        $sertifikatCount = $student ? \App\Models\Certificate::where('student_id', $student->id)->count() : 0;
        $invoiceBelum = $student ? \App\Models\Invoice::where('siswa_id', $student->id)->where('status','belum_bayar')->count() : 0;
    ?>
    <div class="col-6 col-md-3 fade-up" style="animation-delay:.05s">
        <div class="stat-card" style="border-top:3px solid #c84ddf">
            <a href="<?php echo e(route('siswa.dashboard')); ?>" class="text-decoration-none d-block">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-title">Jadwal Hari Ini</div>
                        <div class="stat-value text-primary"><?php echo e($jadwalHariIni); ?></div>
                        <div class="stat-growth text-muted"><i class="bi bi-calendar-day me-1"></i>Sesi belajar</div>
                    </div>
                    <div class="stat-icon bg-primary-soft" style="color:white"><i class="bi bi-calendar-day"></i></div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-6 col-md-3 fade-up" style="animation-delay:.10s">
        <div class="stat-card" style="border-top:3px solid #10b981">
            <a href="<?php echo e(route('siswa.dashboard')); ?>" class="text-decoration-none d-block">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-title">Jadwal Minggu Ini</div>
                        <div class="stat-value text-success"><?php echo e($jadwalMingguIni); ?></div>
                        <div class="stat-growth text-muted"><i class="bi bi-calendar-week me-1"></i>Total sesi</div>
                    </div>
                    <div class="stat-icon bg-success-soft" style="color:white"><i class="bi bi-calendar-week"></i></div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-6 col-md-3 fade-up" style="animation-delay:.15s">
        <div class="stat-card" style="border-top:3px solid #f6af23">
            <a href="<?php echo e(route('siswa.certificates.index')); ?>" class="text-decoration-none d-block">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-title">Sertifikat</div>
                        <div class="stat-value text-warning"><?php echo e($sertifikatCount); ?></div>
                        <div class="stat-growth text-muted"><i class="bi bi-award me-1"></i>Diperoleh</div>
                    </div>
                    <div class="stat-icon bg-warning-soft" style="color:white"><i class="bi bi-award"></i></div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-6 col-md-3 fade-up" style="animation-delay:.20s">
        <div class="stat-card" style="<?php echo e($invoiceBelum > 0 ? 'border-top:3px solid #ef4444' : 'border-top:3px solid #10b981'); ?>">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Tagihan Belum Bayar</div>
                    <div class="stat-value <?php echo e($invoiceBelum > 0 ? 'text-danger' : 'text-success'); ?>"><?php echo e($invoiceBelum); ?></div>
                    <div class="stat-growth <?php echo e($invoiceBelum > 0 ? 'text-danger' : 'text-success'); ?>">
                        <i class="bi <?php echo e($invoiceBelum > 0 ? 'bi-exclamation-circle' : 'bi-check-circle'); ?> me-1"></i>
                        <?php echo e($invoiceBelum > 0 ? 'Harap segera dibayar' : 'Semua lunas!'); ?>

                    </div>
                </div>
                <div class="stat-icon <?php echo e($invoiceBelum > 0 ? 'bg-danger-soft' : 'bg-success-soft'); ?>" style="color:white">
                    <i class="bi <?php echo e($invoiceBelum > 0 ? 'bi-exclamation-circle-fill' : 'bi-check-circle-fill'); ?>"></i>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>


<?php if(!in_array($role, ['owner','admin','guru','siswa'])): ?>
<div class="row g-3">
    <div class="col-12 fade-up">
        <div class="dashboard-card text-center py-5">
            <div style="width:80px;height:80px;border-radius:24px;background:linear-gradient(135deg,#68117e,#c84ddf);display:flex;align-items:center;justify-content:center;margin:0 auto 20px;box-shadow:0 12px 32px rgba(200,77,223,.4)">
                <i class="bi bi-mortarboard-fill text-white" style="font-size:36px"></i>
            </div>
            <h5 class="fw-bold mb-2">Halo, <?php echo e($user->name); ?>!</h5>
            <p class="text-muted mb-4" style="max-width:340px;margin:0 auto">
                Selamat datang di Smart Center Indonesia.
            </p>
            <div class="d-flex gap-2 justify-content-center flex-wrap">
                <a href="<?php echo e(route('profile.edit')); ?>" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-person me-1"></i>Edit Profil
                </a>
                <form method="POST" action="<?php echo e(route('logout')); ?>" class="d-inline">
                    <?php echo csrf_field(); ?>
                    <button class="btn btn-outline-danger btn-sm" type="submit">
                        <i class="bi bi-box-arrow-left me-1"></i>Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php $__env->stopSection(); ?>


<?php $__env->startPush('scripts'); ?>
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
                data: <?php echo json_encode(array_values($monthCounts)); ?>

            }],
            xaxis: {
                categories: <?php echo json_encode(array_values($monthLabels)); ?>,
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
            series: [<?php echo e($male); ?>, <?php echo e($female); ?>],
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
                            formatter: () => '<?php echo e($male + $female); ?>'
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
            series: [{ name: 'Siswa', data: [<?php echo e($aktif); ?>, <?php echo e($nonaktif); ?>, <?php echo e($lulus); ?>] }],
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
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/dashboard.blade.php ENDPATH**/ ?>