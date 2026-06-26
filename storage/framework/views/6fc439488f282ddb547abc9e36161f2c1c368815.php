<?php $__env->startSection('title', 'Portal Siswa'); ?>
<?php $__env->startSection('page-title', 'Portal Siswa'); ?>

<?php $__env->startSection('content'); ?>

<?php
use App\Models\Student;
use App\Models\Invoice;
use App\Models\Schedule;
use App\Models\SchoolClass;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

$student  = Student::where('user_id', auth()->id())->first();
$classIds = $student
    ? DB::table('class_students')->where('student_id', $student->id)->pluck('class_id')
    : collect();

// Next upcoming schedule
$nextClass = $classIds->isNotEmpty()
    ? Schedule::with(['kelas.mataPelajaran', 'guru'])
        ->whereIn('kelas_id', $classIds)
        ->where('tanggal', '>=', now()->toDateString())
        ->where('status', '!=', 'dibatalkan')
        ->orderBy('tanggal')->orderBy('jam_mulai')
        ->first()
    : null;

// Pending absensi for the next class
$nextPending = null;
if ($nextClass && $student) {
    $nextPending = DB::table('absensi_siswas')
        ->where('jadwal_id', $nextClass->id)
        ->where('siswa_id', $student->id)
        ->where('guru_hadir', true)
        ->whereNull('siswa_konfirmasi_at')
        ->first();
}

// Active class (first non-draft class student is enrolled in)
$activeKelas = $classIds->isNotEmpty()
    ? SchoolClass::with(['mataPelajaran', 'jadwal'])
        ->whereIn('id', $classIds)
        ->where('status', '!=', 'draft')
        ->orderBy('nama_kelas')
        ->first()
    : null;

$sesiSelesai  = 0;
$sisaKuota    = 0;
$totalSesi    = 0;
$progresKelas = 0;
if ($activeKelas) {
    $done        = $activeKelas->jadwal->where('status', 'selesai')->count();
    $totalSesi   = $activeKelas->jumlah_pertemuan ?: $activeKelas->jadwal->count();
    $sesiSelesai = $done;
    $sisaKuota   = max(0, $totalSesi - $done);
    $progresKelas = $totalSesi > 0 ? round($done / $totalSesi * 100) : 0;
}

// All subjects from enrolled classes
$allKelas     = $classIds->isNotEmpty()
    ? SchoolClass::with(['mataPelajaran'])->whereIn('id', $classIds)->where('status','!=','draft')->get()
    : collect();
$subjectNames = $allKelas->pluck('mataPelajaran.nama')->filter()->unique()->values();

// Last confirmed attendance
$lastAttendance = $student ? DB::table('absensi_siswas as ab')
    ->join('schedules as s', 's.id', '=', 'ab.jadwal_id')
    ->join('school_classes as sc', 'sc.id', '=', 's.kelas_id')
    ->leftJoin('courses as c', 'c.id', '=', 'sc.mata_pelajaran_id')
    ->where('ab.siswa_id', $student->id)
    ->where('ab.status', 'hadir')
    ->orderByDesc('s.tanggal')
    ->select('s.tanggal', 'c.nama as mapel')
    ->first() : null;

// Last invoice
$lastInvoice = $student ? Invoice::where('siswa_id', $student->id)->latest()->first() : null;

// Simple referral code from student name
$referralCode = $student
    ? strtoupper(preg_replace('/[^A-Z0-9]/i','',explode(' ',$student->name)[0])) . ($student->nis ? substr($student->nis,-4) : '2026')
    : 'SCI2026';
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
                <div style="width:64px;height:64px;border-radius:16px;background:rgba(255,255,255,.18);display:flex;align-items:center;justify-content:center;font-size:28px;flex-shrink:0">
                    <i class="bi bi-person-fill"></i>
                </div>
            <?php endif; ?>
            <div>
                <div style="font-size:11px;opacity:.6;margin-bottom:4px;text-transform:uppercase;letter-spacing:.08em">Portal Siswa</div>
                <h4 style="font-weight:800;margin-bottom:4px;color:white;letter-spacing:-.02em">
                    Halo, <?php echo e(explode(' ', auth()->user()->name)[0]); ?>! 👋
                </h4>
                <p style="opacity:.7;margin:0;font-size:13px">
                    <?php echo e($student?->branch?->name ?? 'SCI'); ?>

                    <?php if($student?->grade): ?> · Kelas <?php echo e($student->grade); ?> <?php endif; ?>
                </p>
            </div>
        </div>
        <div style="font-size:64px;opacity:.07;line-height:1;flex-shrink:0">
            <i class="bi bi-mortarboard"></i>
        </div>
    </div>
</div>


<div class="dashboard-card mb-4 fade-up" style="padding-bottom:20px">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h6 class="fw-bold mb-0" style="font-size:14px">
            <i class="bi bi-stars text-warning me-2"></i>Penawaran Spesial HQ
        </h6>
        <a href="<?php echo e(route('siswa.courses.fees')); ?>" class="text-primary fw-semibold" style="font-size:12px;text-decoration:none">
            Lihat Semua <i class="bi bi-chevron-right" style="font-size:10px"></i>
        </a>
    </div>
    <div class="d-flex gap-3 overflow-auto pb-2" style="scrollbar-width:thin">

        
        <div class="flex-shrink-0 p-3 rounded-3 position-relative overflow-hidden"
             style="min-width:240px;max-width:260px;background:linear-gradient(135deg,#461256,#c84ddf);color:white">
            <div style="position:absolute;right:-20px;top:-20px;width:100px;height:100px;background:rgba(255,255,255,.07);border-radius:50%"></div>
            <span class="badge mb-2" style="background:rgba(255,255,255,.25);color:white;font-size:10px;border-radius:6px">
                🔥 Hemat 20%
            </span>
            <div class="fw-bold mb-1" style="font-size:13px;line-height:1.3">Upgrade Paket Intensif SNBT 2026</div>
            <div style="font-size:11px;opacity:.7;margin-bottom:12px">Berlaku s/d 30 April 2026</div>
            <a href="<?php echo e(route('siswa.courses.fees')); ?>" class="btn btn-sm fw-semibold"
               style="background:rgba(255,255,255,.2);color:white;border:1px solid rgba(255,255,255,.35);border-radius:8px;font-size:11px">
                Klaim Promo
            </a>
        </div>

        
        <div class="flex-shrink-0 p-3 rounded-3 position-relative overflow-hidden"
             style="min-width:240px;max-width:260px;background:var(--input-bg);border:1.5px solid var(--card-border)">
            <span class="badge mb-2" style="background:var(--soft-success-bg);color:var(--soft-success-text);font-size:10px;border-radius:6px">
                🎁 Beli 5 Sesi Gratis 1
            </span>
            <div class="fw-bold mb-1" style="font-size:13px;line-height:1.3;color:var(--text-primary)">Extra Class: Private Khusus IPA</div>
            <div class="text-muted mb-3" style="font-size:11px">Beli via Portal Siswa Sekarang</div>
            <a href="<?php echo e(route('siswa.schedule-agreements.index')); ?>" class="btn btn-sm btn-outline-primary fw-semibold"
               style="border-radius:8px;font-size:11px">
                Detail
            </a>
        </div>

        
        <div class="flex-shrink-0 p-3 rounded-3 position-relative overflow-hidden"
             style="min-width:240px;max-width:260px;background:linear-gradient(135deg,rgba(246,175,35,.12),rgba(200,77,223,.08));border:1.5px solid rgba(246,175,35,.3)">
            <span class="badge mb-2" style="background:rgba(246,175,35,.2);color:#92600a;font-size:10px;border-radius:6px">
                👥 Free Diagnostic Test
            </span>
            <div class="fw-bold mb-1" style="font-size:13px;line-height:1.3;color:var(--text-primary)">Ajak Teman &amp; Dapatkan Bonus</div>
            <div class="text-muted mb-1" style="font-size:11px">Gunakan Kode Referral:</div>
            <div class="fw-bold mb-3" style="font-size:14px;color:#c84ddf;letter-spacing:.05em;font-family:monospace"><?php echo e($referralCode); ?></div>
            <button onclick="navigator.clipboard.writeText('<?php echo e($referralCode); ?>').then(()=>showToast('Kode disalin!','success'))"
                    class="btn btn-sm fw-semibold"
                    style="background:rgba(246,175,35,.2);color:#92600a;border:1px solid rgba(246,175,35,.4);border-radius:8px;font-size:11px">
                <i class="bi bi-share me-1"></i>Bagikan
            </button>
        </div>

    </div>
</div>


<div class="row g-4 mb-4">

    
    <div class="col-lg-6 fade-up">

        
        <div class="row g-3 mb-3">
            <div class="col-6">
                <div class="stat-card" style="border-top:3px solid #c84ddf">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="stat-title">Sisa Kuota</div>
                            <div class="stat-value text-primary" data-auto-count="<?php echo e($sisaKuota); ?>"><?php echo e($sisaKuota); ?></div>
                            <div class="text-muted" style="font-size:11px">sesi tersisa</div>
                        </div>
                        <div class="stat-icon bg-primary-soft" style="color:white"><i class="bi bi-collection-fill"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="stat-card" style="border-top:3px solid #10b981">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="stat-title">Sesi Selesai</div>
                            <div class="stat-value text-success" data-auto-count="<?php echo e($sesiSelesai); ?>"><?php echo e($sesiSelesai); ?></div>
                            <div class="text-muted" style="font-size:11px">dari <?php echo e($totalSesi ?: '?'); ?> total</div>
                        </div>
                        <div class="stat-icon bg-success-soft" style="color:white"><i class="bi bi-check-circle-fill"></i></div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="dashboard-card h-auto">
            <h6 class="fw-bold mb-3" style="font-size:14px">
                <i class="bi bi-alarm text-primary me-2"></i>Kelas Terdekat
            </h6>
            <?php if($nextClass): ?>
            <?php
                $nc      = $nextClass;
                $kelasNm = $nc->kelas?->nama_kelas ?? $nc->topik ?? 'Sesi Belajar';
                $guru    = $nc->guru ?? $nc->kelas?->guru;
                $guruNm  = $guru?->name ?? '—';
                $room    = $nc->ruangan ?? ($nc->jenis === 'online' ? 'Online' : 'Ruangan');
                $isToday = $nc->tanggal?->isToday();
                $tglStr  = $isToday ? 'Hari Ini' : $nc->tanggal?->locale('id')->isoFormat('ddd, D MMM');
            ?>
            <div class="p-3 rounded-3 mb-3" style="background:linear-gradient(135deg,rgba(200,77,223,.07),rgba(70,18,86,.05));border:1.5px solid rgba(200,77,223,.18)">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <div class="fw-bold" style="font-size:14px;color:var(--text-primary)"><?php echo e($kelasNm); ?></div>
                        <div class="text-muted" style="font-size:12px"><?php echo e($guruNm); ?></div>
                    </div>
                    <?php if($isToday): ?>
                    <span class="badge" style="background:var(--soft-primary-bg);color:var(--soft-primary-text);font-size:10px;border-radius:6px">Hari Ini</span>
                    <?php else: ?>
                    <span class="badge" style="background:var(--soft-muted-bg);color:var(--soft-muted-text);font-size:10px;border-radius:6px"><?php echo e($tglStr); ?></span>
                    <?php endif; ?>
                </div>
                <div class="d-flex align-items-center gap-3" style="font-size:12px;color:var(--text-muted)">
                    <span><i class="bi bi-clock me-1"></i><?php echo e(substr($nc->jam_mulai,0,5)); ?> – <?php echo e(substr($nc->jam_selesai,0,5)); ?></span>
                    <span>·</span>
                    <span><i class="bi bi-geo-alt me-1"></i><?php echo e($room); ?></span>
                </div>
            </div>

            <?php if($nextPending): ?>
            <div class="d-flex gap-2 mb-3">
                <form method="POST" action="<?php echo e(route('siswa.attendance.confirm', $nextClass->id)); ?>" class="flex-fill">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="status" value="hadir">
                    <button type="submit" class="btn btn-success fw-bold w-100" style="border-radius:10px;font-size:13px">
                        <i class="bi bi-check-lg me-1"></i>HADIR
                    </button>
                </form>
                <form method="POST" action="<?php echo e(route('siswa.attendance.confirm', $nextClass->id)); ?>" class="flex-fill">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="status" value="izin">
                    <button type="submit" class="btn btn-outline-secondary fw-bold w-100" style="border-radius:10px;font-size:13px">
                        <i class="bi bi-x-lg me-1"></i>TDK HADIR
                    </button>
                </form>
            </div>
            <div class="text-muted" style="font-size:12px"><i class="bi bi-info-circle me-1 text-warning"></i>Konfirmasi kehadiran diperlukan.</div>
            <?php else: ?>
            <div class="d-flex gap-2 mb-3">
                <a href="<?php echo e(route('siswa.attendance')); ?>" class="btn btn-success fw-bold flex-fill" style="border-radius:10px;font-size:13px;opacity:.45" disabled>
                    <i class="bi bi-check-lg me-1"></i>HADIR
                </a>
                <a href="<?php echo e(route('siswa.attendance')); ?>" class="btn btn-outline-secondary fw-bold flex-fill" style="border-radius:10px;font-size:13px;opacity:.45">
                    <i class="bi bi-x-lg me-1"></i>TDK HADIR
                </a>
            </div>
            <div class="text-muted" style="font-size:12px"><i class="bi bi-info-circle me-1"></i>Pastikan Anda hadir tepat waktu.</div>
            <?php endif; ?>
            <?php else: ?>
            <div class="text-center py-3">
                <i class="bi bi-calendar-x" style="font-size:36px;color:var(--text-muted);opacity:.4;display:block;margin-bottom:8px"></i>
                <div class="text-muted" style="font-size:13px">Tidak ada kelas terdekat</div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="col-lg-6 fade-up" style="animation-delay:.05s">

        
        <div class="dashboard-card mb-3">
            <h6 class="fw-bold mb-3" style="font-size:14px">
                <i class="bi bi-bookmark-fill text-primary me-2"></i>Paket Aktif
            </h6>
            <?php if($activeKelas): ?>
            <div class="d-flex align-items-start gap-3 mb-3">
                <div style="width:44px;height:44px;border-radius:12px;background:linear-gradient(135deg,#461256,#c84ddf);color:white;display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:700;flex-shrink:0">
                    <?php echo e(strtoupper(mb_substr(preg_replace('/[^A-Za-z]/','', $activeKelas->mataPelajaran?->nama ?? $activeKelas->nama_kelas),0,2)) ?: 'KL'); ?>

                </div>
                <div style="flex:1;min-width:0">
                    <div class="fw-bold text-truncate" style="font-size:14px;color:var(--text-primary)"><?php echo e($activeKelas->nama_kelas); ?></div>
                    <div class="text-muted" style="font-size:12px">
                        <?php echo e($subjectNames->take(3)->join(' & ')); ?>

                        <?php if($subjectNames->count() > 3): ?> <span style="color:var(--soft-primary-text)">+<?php echo e($subjectNames->count()-3); ?> lainnya</span> <?php endif; ?>
                    </div>
                </div>
                <span style="background:var(--soft-success-bg);color:var(--soft-success-text);padding:4px 10px;border-radius:8px;font-size:11px;font-weight:600;flex-shrink:0;white-space:nowrap">Aktif</span>
            </div>
            <div class="mb-1 d-flex justify-content-between align-items-center" style="font-size:12px">
                <span class="fw-semibold" style="color:var(--text-muted)">Progres Belajar</span>
                <span class="fw-bold" style="color:#c84ddf"><?php echo e($progresKelas); ?>%</span>
            </div>
            <div style="height:8px;background:var(--card-border);border-radius:99px;overflow:hidden;margin-bottom:6px">
                <div style="height:100%;width:<?php echo e($progresKelas); ?>%;background:linear-gradient(90deg,#461256,#c84ddf);border-radius:99px;transition:width .5s"></div>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted" style="font-size:12px"><?php echo e($sesiSelesai); ?> dari <?php echo e($totalSesi ?: '?'); ?> Pertemuan Selesai</div>
                <a href="<?php echo e(route('siswa.kelas.index')); ?>" class="btn btn-sm btn-primary fw-semibold" style="border-radius:8px;font-size:11px">
                    <i class="bi bi-info-circle me-1"></i>Detail
                </a>
            </div>
            <?php else: ?>
            <div class="text-center py-3">
                <i class="bi bi-journal-x" style="font-size:36px;color:var(--text-muted);opacity:.4;display:block;margin-bottom:8px"></i>
                <div class="text-muted" style="font-size:13px">Belum ada paket aktif</div>
            </div>
            <?php endif; ?>
        </div>

        
        <div class="dashboard-card">
            <h6 class="fw-bold mb-3" style="font-size:14px">
                <i class="bi bi-clock-history text-primary me-2"></i>Riwayat Terakhir
            </h6>
            <div class="d-flex flex-column gap-2">
                <?php if($lastAttendance): ?>
                <div class="d-flex align-items-center gap-3 p-3 rounded-3" style="background:var(--input-bg);border:1px solid var(--card-border)">
                    <div style="width:36px;height:36px;border-radius:10px;background:var(--soft-success-bg);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        <i class="bi bi-check2-circle" style="color:var(--soft-success-text);font-size:16px"></i>
                    </div>
                    <div style="flex:1;min-width:0">
                        <div class="fw-semibold" style="font-size:13px;color:var(--text-primary)">Hadir: <?php echo e($lastAttendance->mapel ?? 'Kelas'); ?></div>
                        <div class="text-muted" style="font-size:11px">
                            <?php echo e($lastAttendance->tanggal ? \Carbon\Carbon::parse($lastAttendance->tanggal)->locale('id')->isoFormat('dddd, D MMM Y') : '—'); ?>

                        </div>
                    </div>
                </div>
                <?php else: ?>
                <div class="d-flex align-items-center gap-3 p-3 rounded-3" style="background:var(--input-bg);border:1px solid var(--card-border)">
                    <div style="width:36px;height:36px;border-radius:10px;background:var(--soft-muted-bg);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        <i class="bi bi-calendar3" style="color:var(--soft-muted-text);font-size:15px"></i>
                    </div>
                    <div class="text-muted" style="font-size:13px">Belum ada riwayat kehadiran</div>
                </div>
                <?php endif; ?>

                <?php if($lastInvoice): ?>
                <div class="d-flex align-items-center gap-3 p-3 rounded-3" style="background:var(--input-bg);border:1px solid var(--card-border)">
                    <div style="width:36px;height:36px;border-radius:10px;background:var(--soft-warning-bg);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        <i class="bi bi-receipt" style="color:var(--soft-warning-text);font-size:15px"></i>
                    </div>
                    <div style="flex:1;min-width:0">
                        <div class="fw-semibold" style="font-size:13px;color:var(--text-primary)">Tagihan Diterbitkan</div>
                        <div class="text-muted" style="font-size:11px">
                            <?php echo e($lastInvoice->nomor_invoice); ?> · Rp <?php echo e(number_format($lastInvoice->total, 0, ',', '.')); ?>

                        </div>
                    </div>
                    <a href="<?php echo e(route('siswa.billing.index')); ?>" class="btn btn-sm btn-outline-primary flex-shrink-0" style="font-size:11px;border-radius:8px">Lihat</a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>

<?php if(!$student): ?>
<div class="alert alert-warning d-flex gap-3 align-items-start mt-2 fade-up" style="border-radius:14px;border:none">
    <i class="bi bi-exclamation-triangle-fill text-warning mt-1" style="font-size:18px;flex-shrink:0"></i>
    <div>
        <div class="fw-bold mb-1">Profil Siswa Belum Terhubung</div>
        <div style="font-size:13px">Akun Anda belum terhubung ke data siswa. Hubungi administrator cabang Anda.</div>
    </div>
</div>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/siswa/dashboard.blade.php ENDPATH**/ ?>