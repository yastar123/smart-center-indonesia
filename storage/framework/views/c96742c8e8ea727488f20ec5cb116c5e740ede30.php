<?php $__env->startSection('title', 'Hasil Tryout'); ?>
<?php $__env->startSection('page-title', 'Hasil Tryout'); ?>

<?php $__env->startPush('styles'); ?>
<style>
.score-ring { position:relative;display:inline-flex;align-items:center;justify-content:center }
.score-ring svg { transform:rotate(-90deg) }
.score-ring .score-text { position:absolute;text-align:center }
.review-correct   { border-left:4px solid #10b981!important }
.review-incorrect { border-left:4px solid #ef4444!important }
.review-unanswered{ border-left:4px solid #94a3b8!important }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<?php
$nilai        = (float) $attempt->nilai;
$kelulusan    = (float) ($tryout->nilai_kelulusan ?? 60);
$lulus        = $nilai >= $kelulusan;
$totalSoal    = $soal->count();
$benar        = $attempt->jawaban_benar;
$salah        = $attempt->jawaban_salah;
$tidakDijawab = $attempt->tidak_dijawab;
$durasi       = $attempt->waktu_mulai && $attempt->waktu_selesai
    ? $attempt->waktu_mulai->diffInMinutes($attempt->waktu_selesai) : null;
$jawabanSiswa = $attempt->jawaban ?? [];
$opts         = ['A','B','C','D','E','F'];
?>


<div class="dashboard-card mb-4 fade-up text-center"
     style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;border-radius:20px;padding:36px 24px;overflow:hidden;position:relative">
    <div style="position:absolute;left:-60px;top:-60px;width:200px;height:200px;background:rgba(255,255,255,.04);border-radius:50%"></div>
    <div style="position:absolute;right:-40px;bottom:-40px;width:160px;height:160px;background:rgba(255,255,255,.04);border-radius:50%"></div>
    <div style="position:relative">
        <div style="font-size:11px;opacity:.6;text-transform:uppercase;letter-spacing:.1em;margin-bottom:8px">
            <?php echo e($tryout->kategori); ?> · Percobaan ke-<?php echo e($attempt->percobaan_ke); ?>

        </div>
        <h3 style="font-weight:800;color:white;margin-bottom:4px;letter-spacing:-.02em"><?php echo e($tryout->judul); ?></h3>
        <p style="opacity:.65;font-size:13px;margin-bottom:24px">
            <?php if($attempt->waktu_selesai): ?>
            Selesai <?php echo e($attempt->waktu_selesai->locale('id')->isoFormat('D MMM YYYY, HH:mm')); ?>

            <?php endif; ?>
        </p>

        
        <div class="score-ring mx-auto mb-4" style="width:160px;height:160px">
            <svg width="160" height="160" viewBox="0 0 160 160">
                <circle cx="80" cy="80" r="68" fill="none" stroke="rgba(255,255,255,.15)" stroke-width="10"/>
                <circle cx="80" cy="80" r="68" fill="none"
                    stroke="<?php echo e($lulus ? '#10b981' : '#ef4444'); ?>"
                    stroke-width="10"
                    stroke-dasharray="<?php echo e(round(2 * 3.14159 * 68, 2)); ?>"
                    stroke-dashoffset="<?php echo e(round(2 * 3.14159 * 68 * (1 - $nilai / 100), 2)); ?>"
                    stroke-linecap="round"/>
            </svg>
            <div class="score-text">
                <div style="font-size:32px;font-weight:900;color:white;line-height:1"><?php echo e(number_format($nilai, 1)); ?></div>
                <div style="font-size:11px;opacity:.6">dari 100</div>
            </div>
        </div>

        <div class="mb-3">
            <?php if($lulus): ?>
            <span style="background:rgba(16,185,129,.25);color:#6ee7b7;border:1px solid rgba(16,185,129,.4);padding:8px 20px;border-radius:30px;font-size:14px;font-weight:700">
                <i class="bi bi-patch-check-fill me-2"></i>LULUS
            </span>
            <?php else: ?>
            <span style="background:rgba(239,68,68,.25);color:#fca5a5;border:1px solid rgba(239,68,68,.4);padding:8px 20px;border-radius:30px;font-size:14px;font-weight:700">
                <i class="bi bi-x-circle-fill me-2"></i>BELUM LULUS
                <span style="font-size:11px;opacity:.7;font-weight:400"> (min. <?php echo e((int)$kelulusan); ?>)</span>
            </span>
            <?php endif; ?>
        </div>
    </div>
</div>


<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3 fade-up">
        <div class="stat-card text-center" style="border-top:3px solid #10b981">
            <div class="stat-icon bg-success-soft mx-auto mb-2" style="color:white"><i class="bi bi-check-circle-fill"></i></div>
            <div class="stat-value" style="color:#10b981"><?php echo e($benar); ?></div>
            <div class="stat-title">Benar</div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.05s">
        <div class="stat-card text-center" style="border-top:3px solid #ef4444">
            <div class="stat-icon bg-danger-soft mx-auto mb-2" style="color:white"><i class="bi bi-x-circle-fill"></i></div>
            <div class="stat-value" style="color:#ef4444"><?php echo e($salah); ?></div>
            <div class="stat-title">Salah</div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.1s">
        <div class="stat-card text-center" style="border-top:3px solid #94a3b8">
            <div class="stat-icon bg-muted-bg mx-auto mb-2" style="color:var(--text-muted)"><i class="bi bi-dash-circle-fill"></i></div>
            <div class="stat-value" style="color:#94a3b8"><?php echo e($tidakDijawab); ?></div>
            <div class="stat-title">Tidak Dijawab</div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.15s">
        <div class="stat-card text-center" style="border-top:3px solid #c84ddf">
            <div class="stat-icon bg-primary-soft mx-auto mb-2" style="color:white"><i class="bi bi-clock-fill"></i></div>
            <div class="stat-value"><?php echo e($durasi !== null ? $durasi.' mnt' : '—'); ?></div>
            <div class="stat-title">Durasi Pengerjaan</div>
        </div>
    </div>
</div>


<div class="d-flex flex-wrap gap-2 mb-4 fade-up">
    <a href="<?php echo e(route('siswa.tryout')); ?>" class="btn btn-outline-primary" style="border-radius:8px">
        <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar
    </a>
    <?php
    $doneCount = \App\Models\TryoutAttempt::where('tryout_id',$tryout->id)->where('siswa_id',$attempt->siswa_id)->where('status','selesai')->count();
    $canRetry  = !$tryout->maksimal_percobaan || $doneCount < $tryout->maksimal_percobaan;
    ?>
    <?php if($canRetry && $tryout->status === 'aktif' && $tryout->soal()->count() > 0): ?>
    <a href="<?php echo e(route('siswa.tryout.show', $tryout->id)); ?>" class="btn btn-primary" style="border-radius:8px">
        <i class="bi bi-arrow-repeat me-2"></i>Coba Lagi
    </a>
    <?php endif; ?>
</div>


<?php if($tryout->tampilkan_kunci_jawaban || $tryout->tampilkan_hasil_langsung): ?>
<div class="dashboard-card fade-up" style="border-radius:16px">
    <div class="d-flex align-items-center gap-2 mb-4">
        <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#461256,#c84ddf);display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <i class="bi bi-journal-text text-white"></i>
        </div>
        <div>
            <div style="font-weight:700;font-size:15px">Review Jawaban</div>
            <div style="font-size:12px;color:var(--text-muted)"><?php echo e($totalSoal); ?> soal</div>
        </div>
    </div>

    <?php $__currentLoopData = $soal; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php
        $jawabanUser = $jawabanSiswa[$s->id] ?? null;
        $kunci       = $s->kunci_jawaban;
        $isIsi       = $s->jenis === 'isian';
        $isBenSal    = $s->jenis === 'benar_salah';

        if ($jawabanUser === null || $jawabanUser === '') {
            $status = 'unanswered';
        } elseif ($kunci !== null && $kunci !== '' && (string)$jawabanUser === (string)$kunci) {
            $status = 'correct';
        } elseif ($kunci === null || $kunci === '') {
            $status = 'unanswered';
        } else {
            $status = 'incorrect';
        }

        $statusClass = ['correct'=>'review-correct','incorrect'=>'review-incorrect','unanswered'=>'review-unanswered'][$status];
        $statusIcon  = ['correct'=>'bi-check-circle-fill text-success','incorrect'=>'bi-x-circle-fill text-danger','unanswered'=>'bi-dash-circle text-muted'][$status];
        $statusLabel = ['correct'=>'Benar','incorrect'=>'Salah','unanswered'=>'Tidak Dijawab'][$status];
    ?>

    <div class="dashboard-card mb-3 <?php echo e($statusClass); ?>" style="background:var(--body-bg);border:1px solid var(--card-border);border-radius:12px">
        <div class="d-flex align-items-start justify-content-between gap-3 mb-3">
            <div class="d-flex align-items-center gap-2">
                <span style="background:linear-gradient(135deg,#461256,#c84ddf);color:white;width:28px;height:28px;border-radius:7px;display:inline-flex;align-items:center;justify-content:center;font-weight:700;font-size:12px;flex-shrink:0">
                    <?php echo e($i + 1); ?>

                </span>
                <span style="font-size:14px;font-weight:500;line-height:1.5;color:var(--text-main)">
                    <?php echo nl2br(e($s->teks_pertanyaan)); ?>

                </span>
            </div>
            <div class="d-flex align-items-center gap-1 flex-shrink-0">
                <i class="bi <?php echo e($statusIcon); ?>" style="font-size:16px"></i>
                <span style="font-size:11px;font-weight:600;color:var(--text-muted)"><?php echo e($statusLabel); ?></span>
            </div>
        </div>

        <?php if(!$isIsi): ?>
        <div class="row g-2 mb-2">
            <?php $__currentLoopData = ($s->pilihan_jawaban ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pi => $pteks): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($pteks): ?>
            <?php
                $isUserAns  = (string)$jawabanUser === (string)$pi;
                $isCorrectA = $tryout->tampilkan_kunci_jawaban && (string)$kunci === (string)$pi;
                $optBg      = $isCorrectA ? 'rgba(16,185,129,.12)' : ($isUserAns && $status==='incorrect' ? 'rgba(239,68,68,.08)' : 'var(--body-bg)');
                $optBorder  = $isCorrectA ? '#10b981' : ($isUserAns && $status==='incorrect' ? '#ef4444' : 'var(--card-border)');
                $bsSal      = $isBenSal ? ($pi===0?'B':'S') : ($opts[$pi]??$pi);
            ?>
            <div class="col-12 col-sm-6">
                <div style="padding:8px 12px;border-radius:8px;border:1.5px solid <?php echo e($optBorder); ?>;background:<?php echo e($optBg); ?>;font-size:13px;display:flex;align-items:center;gap:8px">
                    <span style="font-weight:700;width:20px;flex-shrink:0;color:var(--text-muted)"><?php echo e($bsSal); ?></span>
                    <span style="flex:1"><?php echo e($pteks); ?></span>
                    <?php if($isCorrectA): ?>
                    <i class="bi bi-check-circle-fill text-success" style="flex-shrink:0"></i>
                    <?php elseif($isUserAns && $status==='incorrect'): ?>
                    <i class="bi bi-x-circle-fill text-danger" style="flex-shrink:0"></i>
                    <?php elseif($isUserAns): ?>
                    <i class="bi bi-check-circle-fill text-success" style="flex-shrink:0"></i>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php else: ?>
        <div class="mb-2">
            <div style="font-size:12px;color:var(--text-muted);margin-bottom:4px">Jawaban kamu:</div>
            <div style="padding:8px 12px;border-radius:8px;background:var(--body-bg);border:1.5px solid <?php echo e($status==='correct'?'#10b981':($jawabanUser?'#ef4444':'var(--card-border)')); ?>;font-size:13px">
                <?php echo e($jawabanUser ?: '(tidak dijawab)'); ?>

            </div>
            <?php if($tryout->tampilkan_kunci_jawaban && $kunci): ?>
            <div style="font-size:12px;color:var(--text-muted);margin-top:6px;margin-bottom:4px">Jawaban benar:</div>
            <div style="padding:8px 12px;border-radius:8px;background:rgba(16,185,129,.1);border:1.5px solid #10b981;font-size:13px;font-weight:600;color:#059669">
                <?php echo e($kunci); ?>

            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <?php if($tryout->tampilkan_kunci_jawaban && $s->penjelasan): ?>
        <div style="margin-top:8px;padding:10px 12px;border-radius:8px;background:rgba(200,77,223,.07);border:1px solid rgba(200,77,223,.2);font-size:12px;color:var(--text-muted)">
            <i class="bi bi-lightbulb-fill text-warning me-1"></i><strong>Pembahasan:</strong> <?php echo e($s->penjelasan); ?>

        </div>
        <?php endif; ?>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php else: ?>
<div class="dashboard-card text-center py-4 fade-up" style="border-radius:16px">
    <i class="bi bi-lock-fill" style="font-size:2.5rem;color:var(--text-muted);opacity:.4;display:block;margin-bottom:12px"></i>
    <p style="color:var(--text-muted);font-size:13px;margin:0">Review jawaban tidak tersedia untuk tryout ini.</p>
</div>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/siswa/tryouts/result.blade.php ENDPATH**/ ?>