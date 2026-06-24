<?php $__env->startSection('title', 'Portal Guru'); ?>
<?php $__env->startSection('page-title', 'Portal Guru'); ?>

<?php $__env->startSection('content'); ?>

<?php
use App\Models\Teacher;
use App\Models\Schedule;
use App\Models\SchoolClass;
use Carbon\Carbon;

$teacher = Teacher::where('user_id', auth()->id())->first();

$todaySchedules = $teacher
    ? Schedule::with('kelas')
        ->where('guru_id', $teacher->id)
        ->whereDate('tanggal', today())
        ->orderBy('jam_mulai')
        ->get()
    : collect();

$weekSchedules = $teacher
    ? Schedule::with('kelas')
        ->where('guru_id', $teacher->id)
        ->whereBetween('tanggal', [now()->startOfWeek(), now()->endOfWeek()])
        ->orderBy('tanggal')->orderBy('jam_mulai')
        ->get()
    : collect();

$monthTotal = $teacher
    ? Schedule::where('guru_id', $teacher->id)
        ->whereMonth('tanggal', now()->month)
        ->whereYear('tanggal', now()->year)
        ->count()
    : 0;

$classCount = $teacher
    ? SchoolClass::where('cabang_id', $teacher->branch_id)->count()
    : 0;

$nextSchedule = $todaySchedules->first(fn($s) =>
    \Carbon\Carbon::parse($s->jam_selesai)->isFuture()
);
?>


<div class="dashboard-card mb-4 fade-up"
     style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative;padding:28px 28px">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;left:0;top:0;right:0;height:1px;background:linear-gradient(90deg,transparent,rgba(255,255,255,.15),transparent)"></div>

    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3" style="position:relative">
        <div class="d-flex align-items-center gap-4">
            <?php if($teacher?->photo): ?>
                <img src="<?php echo e(asset('storage/'.$teacher->photo)); ?>" alt="Foto"
                     style="width:68px;height:68px;border-radius:18px;object-fit:cover;border:2px solid rgba(255,255,255,.25);flex-shrink:0;box-shadow:0 8px 24px rgba(0,0,0,.2)">
            <?php else: ?>
                <div style="width:68px;height:68px;border-radius:18px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:30px;flex-shrink:0;border:1.5px solid rgba(255,255,255,.12)">
                    <i class="bi bi-person-badge-fill"></i>
                </div>
            <?php endif; ?>
            <div>
                <div style="font-size:11px;opacity:.55;margin-bottom:5px;text-transform:uppercase;letter-spacing:.1em;font-family:var(--font-sans)">
                    <i class="bi bi-calendar3 me-1"></i><?php echo e(now()->locale('id')->isoFormat('dddd, D MMMM Y')); ?>

                </div>
                <h4 style="font-weight:800;margin-bottom:5px;color:white;letter-spacing:-.03em;font-size:clamp(18px,2.5vw,26px)">
                    Selamat datang, <?php echo e(explode(' ', auth()->user()->name)[0]); ?>! 👋
                </h4>
                <div class="d-flex flex-wrap align-items-center gap-2" style="font-size:12.5px;opacity:.75">
                    <?php if($teacher): ?>
                        <span><i class="bi bi-person-badge me-1"></i><?php echo e($teacher->name); ?></span>
                        <?php if($teacher->branch): ?>
                            <span style="opacity:.5">·</span>
                            <span><i class="bi bi-building me-1"></i><?php echo e($teacher->branch->name); ?></span>
                        <?php endif; ?>
                        <?php
                            $subjectsArray = [];
                            if ($teacher->subjects) {
                                if (is_array($teacher->subjects)) {
                                    $subjectsArray = $teacher->subjects;
                                } elseif (is_string($teacher->subjects)) {
                                    // Try to decode JSON first
                                    $decoded = json_decode($teacher->subjects, true);
                                    if (is_array($decoded)) {
                                        $subjectsArray = $decoded;
                                    } else {
                                        // If not JSON, try to explode by comma
                                        $subjectsArray = explode(',', $teacher->subjects);
                                    }
                                }
                            }
                        ?>
                        <?php if(!empty($subjectsArray)): ?>
                            <span style="opacity:.5">·</span>
                            <span><i class="bi bi-book me-1"></i><?php echo e(implode(', ', array_slice($subjectsArray, 0, 2))); ?></span>
                        <?php endif; ?>
                    <?php else: ?>
                        <span>Smart Center Indonesia</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="d-flex flex-column align-items-end gap-2">
            <?php if($nextSchedule): ?>
            <div style="background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.2);border-radius:12px;padding:10px 16px;text-align:right;backdrop-filter:blur(4px)">
                <div style="font-size:10px;opacity:.7;text-transform:uppercase;letter-spacing:.08em;margin-bottom:3px">Sesi Selanjutnya Hari Ini</div>
                <div style="font-weight:700;font-size:14px"><?php echo e(\Carbon\Carbon::parse($nextSchedule->jam_mulai)->format('H:i')); ?> – <?php echo e(\Carbon\Carbon::parse($nextSchedule->jam_selesai)->format('H:i')); ?></div>
                <div style="font-size:11px;opacity:.75"><?php echo e($nextSchedule->topik ?? ($nextSchedule->kelas?->nama_kelas ?? 'Sesi Belajar')); ?></div>
            </div>
            <?php elseif($todaySchedules->count() > 0): ?>
            <div style="background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.15);border-radius:12px;padding:10px 16px;text-align:right">
                <div style="font-size:11px;opacity:.75">
                    <i class="bi bi-check-circle-fill me-1" style="color:#4ade80"></i><?php echo e($todaySchedules->count()); ?> sesi selesai hari ini
                </div>
            </div>
            <?php else: ?>
            <div style="background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.12);border-radius:12px;padding:10px 16px;text-align:right">
                <div style="font-size:11px;opacity:.7">
                    <i class="bi bi-moon-stars me-1"></i>Tidak ada sesi hari ini
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>


<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3 fade-up">
        <div class="stat-card" style="border-top:3px solid #c84ddf">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Sesi Hari Ini</div>
                    <div class="stat-value text-primary"><?php echo e($todaySchedules->count()); ?></div>
                    <div class="stat-label" style="font-size:11px">
                        <?php if($todaySchedules->count() > 0): ?>
                            <i class="bi bi-check2 text-success me-1"></i>Terjadwal
                        <?php else: ?>
                            <span class="text-muted">Tidak ada sesi</span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="stat-icon bg-primary-soft" style="color:white">
                    <i class="bi bi-calendar-day"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.06s">
        <div class="stat-card" style="border-top:3px solid #68117e">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Minggu Ini</div>
                    <div class="stat-value" style="color:#c84ddf"><?php echo e($weekSchedules->count()); ?></div>
                    <div class="stat-label" style="font-size:11px;color:var(--text-muted)">jadwal aktif</div>
                </div>
                <div class="stat-icon bg-primary-soft" style="color:white">
                    <i class="bi bi-calendar-week"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.12s">
        <div class="stat-card" style="border-top:3px solid #10b981">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Bulan Ini</div>
                    <div class="stat-value" style="color:#059669"><?php echo e($monthTotal); ?></div>
                    <div class="stat-label" style="font-size:11px;color:var(--text-muted)">total sesi</div>
                </div>
                <div class="stat-icon bg-success-soft" style="color:white">
                    <i class="bi bi-calendar-month"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.18s">
        <div class="stat-card" style="border-top:3px solid #f6af23">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Kelas Cabang</div>
                    <div class="stat-value" style="color:#e09000"><?php echo e($classCount); ?></div>
                    <div class="stat-label" style="font-size:11px;color:var(--text-muted)">kelas tersedia</div>
                </div>
                <div class="stat-icon bg-warning-soft" style="color:white">
                    <i class="bi bi-diagram-3"></i>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="fade-up mb-4" style="animation-delay:.08s">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h6 class="fw-bold mb-0 section-title">
            Akses Cepat
        </h6>
    </div>
    <div class="row g-3">
        <div class="col-6 col-lg-3">
            <a href="<?php echo e(route('guru.classes.index')); ?>" class="quick-action-card text-decoration-none d-flex align-items-center gap-3"
               style="border-radius:16px;padding:16px 18px;background:var(--card-bg);border:1.5px solid var(--card-border);transition:all .2s;display:flex">
                <div class="quick-action-icon" style="background:var(--soft-primary-bg);color:#c84ddf;width:46px;height:46px;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0">
                    <i class="bi bi-diagram-3-fill"></i>
                </div>
                <div>
                    <div class="fw-bold" style="font-size:13px;color:var(--text-primary)">Kelas Saya</div>
                    <div style="font-size:11px;color:var(--text-muted)">Lihat semua kelas</div>
                </div>
            </a>
        </div>
        <div class="col-6 col-lg-3">
            <a href="<?php echo e(route('guru.messages.index')); ?>" class="quick-action-card text-decoration-none d-flex align-items-center gap-3"
               style="border-radius:16px;padding:16px 18px;background:var(--card-bg);border:1.5px solid var(--card-border);transition:all .2s;display:flex">
                <div class="quick-action-icon" style="background:var(--soft-info-bg);color:#0284c7;width:46px;height:46px;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0">
                    <i class="bi bi-chat-dots-fill"></i>
                </div>
                <div>
                    <div class="fw-bold" style="font-size:13px;color:var(--text-primary)">Pesan</div>
                    <div style="font-size:11px;color:var(--text-muted)">Konsultasi siswa</div>
                </div>
            </a>
        </div>
        <div class="col-6 col-lg-3">
            <a href="<?php echo e(route('guru.announcements')); ?>" class="quick-action-card text-decoration-none d-flex align-items-center gap-3"
               style="border-radius:16px;padding:16px 18px;background:var(--card-bg);border:1.5px solid var(--card-border);transition:all .2s;display:flex">
                <div class="quick-action-icon" style="background:var(--soft-success-bg);color:#059669;width:46px;height:46px;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0">
                    <i class="bi bi-megaphone-fill"></i>
                </div>
                <div>
                    <div class="fw-bold" style="font-size:13px;color:var(--text-primary)">Pengumuman</div>
                    <div style="font-size:11px;color:var(--text-muted)">Info dari lembaga</div>
                </div>
            </a>
        </div>
    </div>
</div>


<div class="row g-4 fade-up" style="animation-delay:.12s">

    
    <div class="col-lg-5">
        <div class="dashboard-card h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h6 class="fw-bold mb-0 section-title" style="margin-bottom:0!important">
                    <i class="bi bi-calendar-check text-primary me-2"></i>Jadwal Hari Ini
                </h6>
                <span class="badge" style="background:var(--soft-primary-bg);color:var(--soft-primary-text);font-size:10.5px;padding:4px 10px;border-radius:7px">
                    <?php echo e(now()->locale('id')->isoFormat('ddd, D MMM')); ?>

                </span>
            </div>

            <?php if($todaySchedules->isEmpty()): ?>
            <div class="d-flex flex-column align-items-center justify-content-center py-4 text-center" style="min-height:140px">
                <div style="width:56px;height:56px;border-radius:16px;background:var(--soft-muted-bg);display:flex;align-items:center;justify-content:center;margin-bottom:12px">
                    <i class="bi bi-calendar3" style="font-size:1.8rem;opacity:.3;color:var(--text-muted)"></i>
                </div>
                <div class="fw-semibold" style="font-size:13.5px;color:var(--text-primary);margin-bottom:4px">Tidak ada jadwal hari ini</div>
                <div style="font-size:12px;color:var(--text-muted)">Nikmati hari Anda! 🌟</div>
            </div>
            <?php else: ?>
            <div class="d-flex flex-column gap-2">
                <?php $__currentLoopData = $todaySchedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $now = now();
                    $start = \Carbon\Carbon::parse($s->jam_mulai);
                    $end   = \Carbon\Carbon::parse($s->jam_selesai);
                    $isNow     = $now->between($start, $end);
                    $isPast    = $now->isAfter($end);
                    $accColor  = $isNow ? '#10b981' : ($isPast ? '#94a3b8' : '#c84ddf');
                    $accBg     = $isNow ? 'var(--soft-success-bg)' : ($isPast ? 'var(--soft-muted-bg)' : 'rgba(200,77,223,.07)');
                    $accBorder = $isNow ? 'rgba(16,185,129,.3)' : ($isPast ? 'var(--card-border)' : 'rgba(200,77,223,.2)');
                ?>
                <div class="d-flex align-items-stretch gap-3 p-3 rounded-3"
                     style="background:<?php echo e($accBg); ?>;border:1px solid <?php echo e($accBorder); ?>;position:relative;overflow:hidden">
                    
                    <div class="flex-shrink-0 text-center" style="min-width:44px">
                        <div style="font-size:12px;font-weight:700;color:<?php echo e($accColor); ?>;line-height:1.2">
                            <?php echo e($start->format('H:i')); ?>

                        </div>
                        <div style="width:1px;height:16px;background:<?php echo e($accColor); ?>;opacity:.3;margin:3px auto"></div>
                        <div style="font-size:10.5px;color:var(--text-muted);line-height:1.2">
                            <?php echo e($end->format('H:i')); ?>

                        </div>
                    </div>
                    <div style="flex:1;min-width:0">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="fw-semibold text-truncate" style="font-size:13px;color:var(--text-primary)">
                                <?php echo e($s->topik ?? 'Sesi Belajar'); ?>

                            </span>
                            <?php if($isNow): ?>
                                <span class="badge flex-shrink-0" style="background:#dcfce7;color:#16a34a;font-size:9px;padding:2px 7px;border-radius:20px;animation:pulseDot 2s ease-in-out infinite">
                                    <i class="bi bi-circle-fill me-1" style="font-size:6px"></i>Live
                                </span>
                            <?php elseif($isPast): ?>
                                <span class="badge flex-shrink-0" style="background:var(--soft-muted-bg);color:var(--text-muted);font-size:9px;padding:2px 7px;border-radius:20px">Selesai</span>
                            <?php endif; ?>
                        </div>
                        <div style="font-size:11.5px;color:var(--text-muted)">
                            <?php if($s->kelas): ?>
                                <i class="bi bi-diagram-3 me-1"></i><?php echo e($s->kelas->nama_kelas); ?>

                            <?php endif; ?>
                            <?php if($s->jenis === 'online'): ?>
                                <span class="ms-2"><i class="bi bi-camera-video me-1" style="color:#c84ddf"></i>Online</span>
                            <?php elseif($s->ruangan): ?>
                                <span class="ms-2"><i class="bi bi-building me-1"></i><?php echo e($s->ruangan); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="col-lg-7">
        <div class="dashboard-card h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h6 class="fw-bold mb-0 section-title" style="margin-bottom:0!important">
                    <i class="bi bi-calendar-week text-primary me-2"></i>Jadwal Minggu Ini
                </h6>
                <span class="text-muted" style="font-size:11.5px">
                    <?php echo e(now()->startOfWeek()->locale('id')->isoFormat('D MMM')); ?> – <?php echo e(now()->endOfWeek()->locale('id')->isoFormat('D MMM')); ?>

                </span>
            </div>

            <?php if($weekSchedules->isEmpty()): ?>
            <div class="d-flex flex-column align-items-center justify-content-center py-4 text-center" style="min-height:140px">
                <div style="width:56px;height:56px;border-radius:16px;background:var(--soft-muted-bg);display:flex;align-items:center;justify-content:center;margin-bottom:12px">
                    <i class="bi bi-calendar-x" style="font-size:1.8rem;opacity:.3;color:var(--text-muted)"></i>
                </div>
                <div class="fw-semibold" style="font-size:13.5px;color:var(--text-primary);margin-bottom:4px">Tidak ada jadwal minggu ini</div>
                <div style="font-size:12px;color:var(--text-muted)">Jadwal akan muncul di sini</div>
            </div>
            <?php else: ?>
            <?php
                $groupedByDay = $weekSchedules->groupBy(fn($s) => \Carbon\Carbon::parse($s->tanggal)->format('Y-m-d'));
            ?>
            <div class="d-flex flex-column gap-3">
                <?php $__currentLoopData = $groupedByDay; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dateStr => $daySchedules): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $date    = \Carbon\Carbon::parse($dateStr);
                    $isToday = $date->isToday();
                ?>
                <div>
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <div style="width:30px;height:30px;border-radius:9px;background:<?php echo e($isToday ? 'linear-gradient(135deg,#68117e,#c84ddf)' : 'var(--soft-muted-bg)'); ?>;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                            <span style="font-size:11px;font-weight:700;color:<?php echo e($isToday ? 'white' : 'var(--text-muted)'); ?>"><?php echo e($date->format('d')); ?></span>
                        </div>
                        <span style="font-size:12px;font-weight:<?php echo e($isToday ? '700' : '600'); ?>;color:<?php echo e($isToday ? 'var(--primary)' : 'var(--text-muted)'); ?>">
                            <?php echo e($date->locale('id')->isoFormat('dddd')); ?>

                            <?php if($isToday): ?> <span style="font-size:10px;background:var(--soft-primary-bg);color:var(--soft-primary-text);padding:1px 6px;border-radius:6px;margin-left:4px">Hari ini</span> <?php endif; ?>
                        </span>
                        <div style="flex:1;height:1px;background:var(--card-border);margin-left:4px"></div>
                        <span style="font-size:10.5px;color:var(--text-muted);flex-shrink:0"><?php echo e($daySchedules->count()); ?> sesi</span>
                    </div>
                    <div class="d-flex flex-column gap-1 ms-1">
                        <?php $__currentLoopData = $daySchedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="d-flex align-items-center gap-3 px-3 py-2 rounded-3"
                             style="background:<?php echo e($isToday ? 'rgba(200,77,223,.05)' : 'var(--input-bg)'); ?>;border:1px solid <?php echo e($isToday ? 'rgba(200,77,223,.15)' : 'var(--card-border)'); ?>">
                            <div style="font-size:11.5px;font-weight:600;color:var(--text-muted);min-width:76px;flex-shrink:0">
                                <?php echo e(\Carbon\Carbon::parse($s->jam_mulai)->format('H:i')); ?>–<?php echo e(\Carbon\Carbon::parse($s->jam_selesai)->format('H:i')); ?>

                            </div>
                            <div style="flex:1;min-width:0">
                                <div class="text-truncate fw-semibold" style="font-size:12.5px;color:var(--text-primary)">
                                    <?php echo e($s->topik ?? 'Sesi Belajar'); ?>

                                </div>
                                <?php if($s->kelas): ?>
                                <div style="font-size:11px;color:var(--text-muted)"><?php echo e($s->kelas->nama_kelas); ?></div>
                                <?php endif; ?>
                            </div>
                            <?php if($s->jenis === 'online'): ?>
                            <span style="font-size:10px;color:#c84ddf;flex-shrink:0"><i class="bi bi-camera-video"></i></span>
                            <?php else: ?>
                            <span style="font-size:10px;color:var(--text-muted);flex-shrink:0"><i class="bi bi-building"></i></span>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php endif; ?>

            <?php if($weekSchedules->count() > 0): ?>
            <div class="mt-3 pt-2" style="border-top:1px solid var(--card-border)">
                <a href="<?php echo e(route('guru.classes.index')); ?>" class="btn btn-sm btn-outline-primary w-100" style="border-radius:10px;font-size:12.5px">
                    <i class="bi bi-diagram-3 me-2"></i>Kelola Semua Kelas
                    <i class="bi bi-arrow-right ms-auto"></i>
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<?php if(!$teacher): ?>
<div class="alert alert-warning d-flex gap-3 align-items-start mt-4 fade-up" style="border-radius:14px;border:none">
    <i class="bi bi-exclamation-triangle-fill text-warning mt-1" style="font-size:18px;flex-shrink:0"></i>
    <div>
        <div class="fw-bold mb-1">Profil Guru Belum Terhubung</div>
        <div style="font-size:13px">
            Akun Anda belum terhubung ke profil guru. Minta administrator untuk menghubungkan akun ini ke data guru yang sesuai.
        </div>
    </div>
</div>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<style>
@keyframes pulseDot {
    0%, 100% { opacity: 1; }
    50%       { opacity: .5; }
}
.quick-action-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 24px rgba(200,77,223,.12);
    border-color: rgba(200,77,223,.3) !important;
    text-decoration: none;
}
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/guru/dashboard.blade.php ENDPATH**/ ?>