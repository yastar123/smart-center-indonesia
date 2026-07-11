<?php $__env->startSection('title', 'Tryout Online'); ?>
<?php $__env->startSection('page-title', 'Tryout Online'); ?>

<?php $__env->startSection('content'); ?>


<div class="dashboard-card mb-4 fade-up"
     style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-40px;top:-40px;width:200px;height:200px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;right:80px;bottom:-60px;width:140px;height:140px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3" style="position:relative">
        <div>
            <div style="font-size:11px;opacity:.6;margin-bottom:6px;text-transform:uppercase;letter-spacing:.1em">
                <i class="bi bi-pencil-square me-1"></i>Computer Based Test
            </div>
            <h4 style="font-weight:800;margin-bottom:6px;color:white;letter-spacing:-.02em">Tryout Online</h4>
            <p style="opacity:.7;margin:0;font-size:13px;max-width:480px">
                Uji kemampuanmu dengan soal-soal pilihan. Hasil langsung tersedia setelah selesai mengerjakan.
            </p>
        </div>
        <div style="font-size:72px;opacity:.08;line-height:1;flex-shrink:0">
            <i class="bi bi-mortarboard"></i>
        </div>
    </div>
</div>

<?php if(session('error')): ?>
<div class="alert alert-danger alert-dismissible fade show fade-up" role="alert">
    <i class="bi bi-exclamation-triangle me-2"></i><?php echo e(session('error')); ?>

    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php
$totalAttempts   = $attempts->flatten()->count();
$passedAttempts  = $attempts->flatten()->where('nilai', '>=', 60)->count();
$avgScore        = $attempts->flatten()->where('status','selesai')->avg('nilai');
?>


<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3 fade-up">
        <div class="stat-card" style="border-top:3px solid #c84ddf">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Total Tryout</div>
                    <div class="stat-value"><?php echo e($tryouts->count()); ?></div>
                    <div class="stat-label">tersedia</div>
                </div>
                <div class="stat-icon bg-primary-soft" style="color:white"><i class="bi bi-journal-check"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.05s">
        <div class="stat-card" style="border-top:3px solid #10b981">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Percobaan</div>
                    <div class="stat-value"><?php echo e($totalAttempts); ?></div>
                    <div class="stat-label">total dikerjakan</div>
                </div>
                <div class="stat-icon bg-success-soft" style="color:white"><i class="bi bi-pencil-fill"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.1s">
        <div class="stat-card" style="border-top:3px solid #f59e0b">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Lulus</div>
                    <div class="stat-value"><?php echo e($passedAttempts); ?></div>
                    <div class="stat-label">kali lulus</div>
                </div>
                <div class="stat-icon bg-warning-soft" style="color:white"><i class="bi bi-patch-check-fill"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.15s">
        <div class="stat-card" style="border-top:3px solid #6366f1">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Rata-rata Nilai</div>
                    <div class="stat-value"><?php echo e($avgScore ? number_format($avgScore, 1) : '—'); ?></div>
                    <div class="stat-label">dari semua percobaan</div>
                </div>
                <div class="stat-icon bg-info-soft" style="color:white"><i class="bi bi-graph-up-arrow"></i></div>
            </div>
        </div>
    </div>
</div>


<?php if($tryouts->isEmpty()): ?>
<div class="dashboard-card text-center py-5 fade-up">
    <div style="font-size:3.5rem;margin-bottom:16px;opacity:.3"><i class="bi bi-journal-x"></i></div>
    <h5 style="font-weight:700;margin-bottom:8px">Belum Ada Tryout</h5>
    <p class="text-muted" style="font-size:13px;margin:0">Tryout aktif akan muncul di sini. Pantau terus ya!</p>
</div>
<?php else: ?>
<div class="row g-3">
    <?php $__currentLoopData = $tryouts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php
        $myAttempts  = $attempts->get($t->id, collect());
        $doneCount   = $myAttempts->where('status','selesai')->count();
        $bestAttempt = $myAttempts->where('status','selesai')->sortByDesc('nilai')->first();
        $activeAttempt = $myAttempts->where('status','berlangsung')->first();
        $maxReached  = $t->maksimal_percobaan && $doneCount >= $t->maksimal_percobaan;
        $bestNilai   = $bestAttempt ? (float) $bestAttempt->nilai : null;
        $lulus       = $bestNilai !== null && (!$t->nilai_kelulusan || $bestNilai >= (float)$t->nilai_kelulusan);
        $diffColor   = ['mudah'=>'success','sedang'=>'warning','sulit'=>'danger'];
    ?>
    <div class="col-12 col-md-6 col-xl-4 fade-up">
        <div class="dashboard-card h-100 d-flex flex-column" style="border-radius:16px;overflow:hidden;padding:0">
            
            <div style="background:linear-gradient(135deg,#260632,#461256,#c84ddf);padding:18px 20px 14px;color:white;position:relative;overflow:hidden">
                <div style="position:absolute;right:-20px;top:-20px;width:80px;height:80px;background:rgba(255,255,255,.07);border-radius:50%"></div>
                <div class="d-flex align-items-start justify-content-between gap-2" style="position:relative">
                    <div>
                        <div style="font-size:10px;opacity:.6;margin-bottom:4px;text-transform:uppercase;letter-spacing:.08em">
                            <?php echo e($t->kategori); ?>

                        </div>
                        <div style="font-weight:700;font-size:15px;line-height:1.3;margin-bottom:6px"><?php echo e($t->judul); ?></div>
                    </div>
                    <?php if($lulus): ?>
                    <span class="badge" style="background:rgba(16,185,129,.25);color:#6ee7b7;font-size:10px;flex-shrink:0;border:1px solid rgba(16,185,129,.3)">
                        <i class="bi bi-patch-check-fill me-1"></i>Lulus
                    </span>
                    <?php elseif($doneCount > 0): ?>
                    <span class="badge" style="background:rgba(239,68,68,.2);color:#fca5a5;font-size:10px;flex-shrink:0;border:1px solid rgba(239,68,68,.3)">
                        <i class="bi bi-x-circle-fill me-1"></i>Belum Lulus
                    </span>
                    <?php elseif($activeAttempt): ?>
                    <span class="badge" style="background:rgba(245,158,11,.25);color:#fcd34d;font-size:10px;flex-shrink:0;border:1px solid rgba(245,158,11,.3)">
                        <i class="bi bi-play-circle-fill me-1"></i>Berlangsung
                    </span>
                    <?php else: ?>
                    <span class="badge" style="background:rgba(255,255,255,.15);color:rgba(255,255,255,.8);font-size:10px;flex-shrink:0">
                        <i class="bi bi-circle me-1"></i>Belum Dikerjakan
                    </span>
                    <?php endif; ?>
                </div>
                <?php if($t->deskripsi): ?>
                <p style="font-size:12px;opacity:.65;margin:0;line-height:1.5"><?php echo e(Str::limit($t->deskripsi, 80)); ?></p>
                <?php endif; ?>
            </div>
            
            <div style="padding:14px 20px;flex:1">
                <div class="row g-2 mb-3">
                    <div class="col-4">
                        <div style="font-size:11px;color:var(--text-muted);margin-bottom:2px">Durasi</div>
                        <div style="font-size:13px;font-weight:600"><i class="bi bi-clock me-1 text-primary"></i><?php echo e($t->durasi_menit); ?> mnt</div>
                    </div>
                    <div class="col-4">
                        <div style="font-size:11px;color:var(--text-muted);margin-bottom:2px">Soal</div>
                        <div style="font-size:13px;font-weight:600"><i class="bi bi-list-check me-1 text-primary"></i><?php echo e($t->soal_count); ?></div>
                    </div>
                    <div class="col-4">
                        <div style="font-size:11px;color:var(--text-muted);margin-bottom:2px">Percobaan</div>
                        <div style="font-size:13px;font-weight:600">
                            <?php echo e($doneCount); ?>/<?php echo e($t->maksimal_percobaan ?: '∞'); ?>

                        </div>
                    </div>
                </div>
                <?php if($t->nilai_kelulusan): ?>
                <div style="font-size:11px;color:var(--text-muted);margin-bottom:6px">
                    <i class="bi bi-target me-1"></i>Nilai kelulusan: <strong><?php echo e((int)$t->nilai_kelulusan); ?></strong>
                </div>
                <?php endif; ?>
                <?php if($bestNilai !== null): ?>
                <div class="mb-2">
                    <div class="d-flex justify-content-between align-items-center mb-1" style="font-size:11px;color:var(--text-muted)">
                        <span>Nilai terbaik</span>
                        <span class="fw-bold" style="color:<?php echo e($lulus ? '#10b981' : '#ef4444'); ?>"><?php echo e(number_format($bestNilai, 1)); ?></span>
                    </div>
                    <div style="height:4px;background:var(--card-border);border-radius:4px;overflow:hidden">
                        <div style="height:100%;width:<?php echo e(min($bestNilai, 100)); ?>%;background:<?php echo e($lulus ? '#10b981' : '#ef4444'); ?>;border-radius:4px;transition:width .5s ease"></div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            
            <div style="padding:12px 20px;border-top:1px solid var(--card-border)">
                <?php if($activeAttempt): ?>
                <a href="<?php echo e(route('siswa.tryout.show', $t->id)); ?>" class="btn btn-warning w-100" style="border-radius:8px;font-weight:600;font-size:13px">
                    <i class="bi bi-play-fill me-2"></i>Lanjutkan Tryout
                </a>
                <?php elseif($maxReached): ?>
                <?php if($bestAttempt): ?>
                <a href="<?php echo e(route('siswa.tryout.result', [$t->id, $bestAttempt->id])); ?>" class="btn btn-outline-primary w-100" style="border-radius:8px;font-weight:600;font-size:13px">
                    <i class="bi bi-bar-chart-line me-2"></i>Lihat Hasil Terbaik
                </a>
                <?php else: ?>
                <button class="btn btn-secondary w-100" disabled style="border-radius:8px;font-size:13px">
                    <i class="bi bi-lock-fill me-2"></i>Batas Percobaan Tercapai
                </button>
                <?php endif; ?>
                <?php elseif($t->soal_count === 0): ?>
                <button class="btn btn-outline-secondary w-100" disabled style="border-radius:8px;font-size:13px">
                    <i class="bi bi-journal-x me-2"></i>Belum Ada Soal
                </button>
                <?php else: ?>
                <div class="d-flex gap-2">
                    <?php if($bestAttempt): ?>
                    <a href="<?php echo e(route('siswa.tryout.result', [$t->id, $bestAttempt->id])); ?>" class="btn btn-outline-primary flex-shrink-0" style="border-radius:8px;font-size:13px;padding:8px 12px" title="Lihat hasil">
                        <i class="bi bi-bar-chart-line"></i>
                    </a>
                    <?php endif; ?>
                    <a href="<?php echo e(route('siswa.tryout.show', $t->id)); ?>" class="btn btn-primary flex-fill" style="border-radius:8px;font-weight:600;font-size:13px">
                        <i class="bi bi-play-fill me-2"></i><?php echo e($doneCount > 0 ? 'Coba Lagi' : 'Mulai Tryout'); ?>

                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/siswa/tryouts/index.blade.php ENDPATH**/ ?>