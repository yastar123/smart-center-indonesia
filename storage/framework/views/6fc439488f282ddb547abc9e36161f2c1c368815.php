<?php $__env->startSection('title', 'Portal Siswa'); ?>
<?php $__env->startSection('page-title', 'Portal Siswa'); ?>

<?php $__env->startSection('content'); ?>

<?php
use App\Models\Student;
use App\Models\Invoice;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

$student = Student::where('user_id', auth()->id())->first();

$invoices = $student
    ? Invoice::where('siswa_id', $student->id)
        ->latest()
        ->limit(5)
        ->get()
    : collect();

$totalTagihan = $student
    ? Invoice::where('siswa_id', $student->id)->sum('total')
    : 0;
$totalLunas   = $student
    ? Invoice::where('siswa_id', $student->id)->where('status','lunas')->sum('total')
    : 0;
$sisaTunggakan = $totalTagihan - $totalLunas;

// Schedules for classes student is enrolled in this week
$classIds = $student
    ? DB::table('class_students')->where('student_id', $student->id)->pluck('class_id')
    : collect();
$weekSchedules = $classIds->isNotEmpty()
    ? Schedule::with(['kelas.mataPelajaran', 'kelas.guru'])
        ->whereIn('kelas_id', $classIds)
        ->whereBetween('tanggal', [now()->startOfWeek(), now()->endOfWeek()])
        ->where('status','!=','dibatalkan')
        ->orderBy('tanggal')->orderBy('jam_mulai')
        ->limit(10)->get()
    : collect();

// Pending attendance confirmations (guru marked hadir, student hasn't confirmed)
$pendingAttendances = $student ? DB::table('absensi_siswas as ab')
    ->join('schedules as s', 's.id', '=', 'ab.jadwal_id')
    ->join('school_classes as sc', 'sc.id', '=', 's.kelas_id')
    ->leftJoin('courses as c', 'c.id', '=', 'sc.mata_pelajaran_id')
    ->where('ab.siswa_id', $student->id)
    ->where('ab.guru_hadir', true)
    ->whereNull('ab.siswa_konfirmasi_at')
    ->select('ab.jadwal_id', 's.tanggal', 's.jam_mulai', 's.jam_selesai', 's.pertemuan_ke', 'sc.nama_kelas', 'c.nama as mapel')
    ->orderByDesc('s.tanggal')
    ->limit(5)
    ->get() : collect();
?>


<div class="dashboard-card mb-4 fade-up"
     style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3" style="position:relative">
        <div class="d-flex align-items-center gap-4">
            <?php if($student?->photo): ?>
                <img src="<?php echo e(asset('storage/'.$student->photo)); ?>" alt="Foto"
                     style="width:64px;height:64px;border-radius:16px;object-fit:cover;border:2px solid rgba(255,255,255,.2);flex-shrink:0">
            <?php else: ?>
                <div style="width:64px;height:64px;border-radius:16px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:28px;flex-shrink:0">
                    <i class="bi bi-mortarboard-fill"></i>
                </div>
            <?php endif; ?>
            <div>
                <div style="font-size:11px;opacity:.6;margin-bottom:4px;text-transform:uppercase;letter-spacing:.08em">
                    Portal Siswa · <?php echo e(now()->locale('id')->isoFormat('dddd, D MMMM Y')); ?>

                </div>
                <h4 style="font-weight:800;margin-bottom:4px;color:white;letter-spacing:-.02em">
                    Halo, <?php echo e(explode(' ', auth()->user()->name)[0]); ?>! 👋
                </h4>
                <p style="opacity:.65;margin:0;font-size:13px">
                    <?php if($student): ?>
                        NIS: <?php echo e($student->nis ?? '-'); ?> · <?php echo e($student->branch?->name ?? 'N/A'); ?>

                        <?php if($student->grade): ?> · Kelas <?php echo e($student->grade); ?> <?php endif; ?>
                    <?php else: ?>
                        Selamat belajar di Smart Center Indonesia
                    <?php endif; ?>
                </p>
            </div>
        </div>
        <div style="font-size:64px;opacity:.08;line-height:1;flex-shrink:0">
            <i class="bi bi-mortarboard"></i>
        </div>
    </div>
</div>


<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3 fade-up">
        <div class="stat-card" style="border-top:3px solid #c84ddf">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Total Tagihan</div>
                    <div class="stat-value text-primary" style="font-size:20px">
                        Rp <?php echo e(number_format($totalTagihan, 0, ',', '.')); ?>

                    </div>
                    <div class="stat-label" style="font-size:11px"><?php echo e($invoices->count()); ?> invoice</div>
                </div>
                <div class="stat-icon bg-primary-soft" style="color:white">
                    <i class="bi bi-receipt"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.05s">
        <div class="stat-card" style="border-top:3px solid #10b981">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Sudah Dibayar</div>
                    <div class="stat-value" style="color:#059669;font-size:20px">
                        Rp <?php echo e(number_format($totalLunas, 0, ',', '.')); ?>

                    </div>
                    <div class="stat-label" style="font-size:11px;color:#10b981">
                        <i class="bi bi-check-circle-fill me-1"></i>Terverifikasi
                    </div>
                </div>
                <div class="stat-icon bg-success-soft" style="color:white">
                    <i class="bi bi-check-circle"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.10s">
        <div class="stat-card" style="border-top:3px solid <?php echo e($sisaTunggakan > 0 ? '#ef4444' : '#10b981'); ?>">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Sisa Tunggakan</div>
                    <div class="stat-value" style="color:<?php echo e($sisaTunggakan > 0 ? '#dc2626' : '#059669'); ?>;font-size:20px">
                        Rp <?php echo e(number_format($sisaTunggakan, 0, ',', '.')); ?>

                    </div>
                    <div class="stat-label" style="font-size:11px">
                        <?php if($sisaTunggakan > 0): ?>
                            <i class="bi bi-exclamation-circle text-danger me-1"></i>Belum lunas
                        <?php else: ?>
                            <i class="bi bi-check2-all text-success me-1"></i>Lunas semua
                        <?php endif; ?>
                    </div>
                </div>
                <div class="stat-icon <?php echo e($sisaTunggakan > 0 ? 'bg-danger-soft' : 'bg-success-soft'); ?>" style="color:white">
                    <i class="bi bi-wallet2"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.15s">
        <div class="stat-card" style="border-top:3px solid #68117e">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Jadwal Minggu Ini</div>
                    <div class="stat-value" style="color:#c84ddf"><?php echo e($weekSchedules->count()); ?></div>
                    <div class="stat-label" style="font-size:11px">sesi belajar</div>
                </div>
                <div class="stat-icon bg-primary-soft" style="color:white">
                    <i class="bi bi-calendar-week"></i>
                </div>
            </div>
        </div>
    </div>
</div>


<?php if($pendingAttendances->isNotEmpty()): ?>
<div class="dashboard-card mb-4 fade-up" style="border-left:4px solid #f6af23;background:var(--soft-warning-bg)">
    <div class="d-flex align-items-start gap-3">
        <div style="width:40px;height:40px;border-radius:12px;background:#f6af231a;display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <i class="bi bi-hand-thumbs-up-fill" style="color:#f6af23;font-size:18px"></i>
        </div>
        <div style="flex:1;min-width:0">
            <div class="fw-bold mb-1" style="font-size:14px;color:var(--text-primary)">
                <?php echo e($pendingAttendances->count()); ?> Kehadiran Menunggu Konfirmasimu
            </div>
            <p class="text-muted mb-2" style="font-size:12.5px">
                Guru telah menandai kamu hadir pada sesi berikut. Konfirmasi kehadiranmu agar status menjadi <strong>Hadir</strong>.
            </p>
            <div class="d-flex flex-column gap-1 mb-2">
                <?php $__currentLoopData = $pendingAttendances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="d-flex align-items-center gap-2" style="font-size:12px;color:var(--text-muted)">
                    <i class="bi bi-calendar3" style="color:#f6af23;flex-shrink:0"></i>
                    <span class="fw-semibold" style="color:var(--text-primary)"><?php echo e($pa->mapel ?? 'Kelas'); ?></span>
                    <span>·</span>
                    <?php if($pa->pertemuan_ke): ?>
                    <span>Pertemuan ke-<?php echo e($pa->pertemuan_ke); ?></span>
                    <span>·</span>
                    <?php endif; ?>
                    <span><?php echo e(\Carbon\Carbon::parse($pa->tanggal)->locale('id')->isoFormat('D MMM Y')); ?></span>
                    <span>·</span>
                    <span><?php echo e(\Carbon\Carbon::parse($pa->jam_mulai)->format('H:i')); ?></span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <a href="<?php echo e(route('siswa.attendance')); ?>" class="btn btn-sm" style="border-radius:9px;background:#f6af23;color:white;border:none;font-weight:600;font-size:12.5px">
                <i class="bi bi-hand-thumbs-up me-1"></i>Konfirmasi Sekarang
            </a>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="row g-4" id="pembayaran">

    
    <div class="col-lg-5 fade-up">
        <div class="dashboard-card h-100">
            <h6 class="fw-bold mb-3" style="font-size:14px">
                <i class="bi bi-credit-card text-primary me-2"></i>Tagihan Saya
            </h6>
            <?php $__empty_1 = true; $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                $stClr = ['lunas'=>'#10b981','belum_bayar'=>'#f6af23','sebagian'=>'#c84ddf'][$inv->status] ?? '#94a3b8';
                $stBg  = ['lunas'=>'var(--soft-success-bg)','belum_bayar'=>'var(--soft-warning-bg)','sebagian'=>'var(--soft-primary-bg)'][$inv->status] ?? 'var(--soft-muted-bg)';
                $stLbl = ['lunas'=>'Lunas','belum_bayar'=>'Belum Bayar','sebagian'=>'Sebagian'][$inv->status] ?? $inv->status;
                $overdue = $inv->status !== 'lunas' && $inv->jatuh_tempo && \Carbon\Carbon::parse($inv->jatuh_tempo)->isPast();
            ?>
            <div class="d-flex align-items-center justify-content-between p-3 rounded-3 mb-2 <?php echo e($overdue ? 'row-overdue' : ''); ?>"
                 style="background:<?php echo e($overdue ? 'var(--overdue-bg,#fef2f2)' : 'var(--input-bg)'); ?>;border:1px solid <?php echo e($overdue ? 'var(--overdue-border,#fecaca)' : 'var(--card-border)'); ?>">
                <div style="min-width:0">
                    <div class="fw-semibold" style="font-size:13px"><?php echo e($inv->nomor_invoice); ?></div>
                    <div style="font-size:11px;color:var(--text-muted)">
                        <?php if($overdue): ?>
                            <i class="bi bi-exclamation-triangle-fill text-danger me-1"></i>Jatuh tempo: terlambat
                        <?php elseif($inv->jatuh_tempo): ?>
                            <i class="bi bi-calendar3 me-1"></i><?php echo e(\Carbon\Carbon::parse($inv->jatuh_tempo)->locale('id')->isoFormat('D MMMM Y')); ?>

                        <?php endif; ?>
                    </div>
                </div>
                <div class="text-end flex-shrink-0 ms-2">
                    <div class="fw-bold" style="font-size:13.5px">Rp <?php echo e(number_format($inv->total, 0, ',', '.')); ?></div>
                    <span class="badge mt-1" style="background:<?php echo e($stBg); ?>;color:<?php echo e($stClr); ?>;font-size:10px;border-radius:6px;padding:2px 8px">
                        <?php echo e($stLbl); ?>

                    </span>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="empty-state" style="padding:2rem">
                <i class="bi bi-receipt-cutoff" style="font-size:2.5rem;color:#cbd5e1;display:block;margin-bottom:8px"></i>
                <p class="text-muted mb-0" style="font-size:13px">
                    <?php if(!$student): ?> Akun belum terhubung ke data siswa. <?php else: ?> Belum ada tagihan. <?php endif; ?>
                </p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="col-lg-7 fade-up" id="jadwal" style="animation-delay:.05s">
        <div class="dashboard-card h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0" style="font-size:14px">
                    <i class="bi bi-calendar-week text-primary me-2"></i>Jadwal Belajar Minggu Ini
                </h6>
                <span class="text-muted" style="font-size:11.5px">
                    <?php echo e(now()->startOfWeek()->locale('id')->isoFormat('D MMM')); ?> – <?php echo e(now()->endOfWeek()->locale('id')->isoFormat('D MMM')); ?>

                </span>
            </div>

            <?php if($weekSchedules->isEmpty()): ?>
            <div class="empty-state" style="padding:2rem">
                <i class="bi bi-calendar3" style="font-size:2.5rem;color:#cbd5e1;display:block;margin-bottom:8px"></i>
                <p class="text-muted mb-0" style="font-size:13px">
                    <?php if(!$student): ?> Profil siswa belum dikonfigurasi. <?php else: ?> Tidak ada jadwal minggu ini. <?php endif; ?>
                </p>
            </div>
            <?php else: ?>
            <div class="d-flex flex-column gap-2">
                <?php $__currentLoopData = $weekSchedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $isToday = $sch->tanggal->isToday();
                    $statusClr = ['dijadwalkan'=>'#c84ddf','berlangsung'=>'#10b981','selesai'=>'#94a3b8'][$sch->status] ?? '#94a3b8';
                ?>
                <div class="d-flex gap-3 align-items-center p-3 rounded-3"
                     style="background:<?php echo e($isToday ? 'rgba(200,77,223,.07)' : 'var(--input-bg)'); ?>;border:1px solid <?php echo e($isToday ? '#e8b4f5' : 'var(--card-border)'); ?>">
                    <div class="text-center flex-shrink-0" style="min-width:48px">
                        <div class="fw-bold" style="font-size:13px;color:<?php echo e($isToday ? '#68117e' : 'var(--text-primary)'); ?>">
                            <?php echo e($sch->tanggal->locale('id')->isoFormat('ddd')); ?>

                        </div>
                        <div style="font-size:11px;color:var(--text-muted)">
                            <?php echo e(\Carbon\Carbon::parse($sch->jam_mulai)->format('H:i')); ?>

                        </div>
                    </div>
                    <div style="flex:1;min-width:0">
                        <div class="fw-semibold text-truncate" style="font-size:13px">
                            <?php echo e($sch->topik ?? 'Sesi Belajar'); ?>

                            <?php if($isToday): ?>
                                <span class="badge ms-1" style="background:var(--soft-primary-bg);color:var(--soft-primary-text);font-size:9px">Hari Ini</span>
                            <?php endif; ?>
                        </div>
                        <div style="font-size:11px;color:var(--text-muted)">
                            <?php echo e(\Carbon\Carbon::parse($sch->jam_mulai)->format('H:i')); ?>–<?php echo e(\Carbon\Carbon::parse($sch->jam_selesai)->format('H:i')); ?>

                            <?php if($sch->jenis === 'online'): ?>
                                · <i class="bi bi-camera-video me-1" style="color:#c84ddf"></i>Online
                            <?php else: ?>
                                · <i class="bi bi-building me-1"></i><?php echo e($sch->ruangan ?? 'Kelas'); ?>

                            <?php endif; ?>
                        </div>
                    </div>
                    <span class="badge flex-shrink-0" style="background:<?php echo e($statusClr); ?>20;color:<?php echo e($statusClr); ?>;font-size:10px;border-radius:6px">
                        <?php echo e(ucfirst($sch->status)); ?>

                    </span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<?php if(!$student): ?>
<div class="alert alert-warning d-flex gap-3 align-items-start mt-4 fade-up" style="border-radius:14px;border:none">
    <i class="bi bi-exclamation-triangle-fill text-warning mt-1" style="font-size:18px;flex-shrink:0"></i>
    <div>
        <div class="fw-bold mb-1">Profil Siswa Belum Terhubung</div>
        <div style="font-size:13px">
            Akun Anda belum terhubung ke data siswa. Hubungi administrator cabang Anda untuk menghubungkan akun ini.
        </div>
    </div>
</div>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/siswa/dashboard.blade.php ENDPATH**/ ?>