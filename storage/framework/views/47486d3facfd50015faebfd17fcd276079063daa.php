<?php $__env->startSection('title', 'Kelas Saya'); ?>
<?php $__env->startSection('page-title', 'Kelas Saya'); ?>

<?php $__env->startSection('content'); ?>
<div>


<div class="dashboard-card mb-4 fade-up" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
    <div class="row align-items-center g-3" style="position:relative">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-3">
                <div style="width:52px;height:52px;border-radius:16px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:24px;flex-shrink:0">
                    <i class="bi bi-collection-fill"></i>
                </div>
                <div>
                    <h4 class="fw-bold mb-1" style="color:white;letter-spacing:-.02em">Kelas Saya</h4>
                    <p class="mb-0" style="opacity:.75;font-size:13px">Akses modul, video, dan latihan soal dari paket Anda.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="<?php echo e(route('siswa.schedule-agreements.index')); ?>" class="btn fw-semibold px-4"
               style="background:rgba(255,255,255,.15);color:white;border:1px solid rgba(255,255,255,.3);border-radius:10px;font-size:13px">
                <i class="bi bi-plus-circle me-2"></i>Request Kelas Tambahan
            </a>
        </div>
    </div>
</div>


<?php if(isset($registration) && $registration && !empty($registration->interest_sessions)): ?>
<div class="dashboard-card mb-4 fade-up">
    <div class="d-flex align-items-center gap-2 mb-3">
        <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#260632,#c84ddf);display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <i class="bi bi-bookmark-star-fill text-white" style="font-size:.9rem"></i>
        </div>
        <div>
            <h6 class="fw-bold mb-0" style="font-size:.95rem">Program Terdaftar</h6>
            <div class="text-muted" style="font-size:.75rem">Mata pelajaran &amp; jumlah sesi yang ditetapkan admin</div>
        </div>
    </div>
    <div class="row g-3">
        <?php
            $interestSessions = $registration->interest_sessions ?? [];
            $totalSesiAll = array_sum($interestSessions);
            $colors = [
                'linear-gradient(135deg,#6366f1,#8b5cf6)',
                'linear-gradient(135deg,#0284c7,#38bdf8)',
                'linear-gradient(135deg,#10b981,#34d399)',
                'linear-gradient(135deg,#f59e0b,#fcd34d)',
                'linear-gradient(135deg,#ec4899,#f9a8d4)',
                'linear-gradient(135deg,#c84ddf,#a855f7)',
                'linear-gradient(135deg,#14b8a6,#2dd4bf)',
                'linear-gradient(135deg,#ef4444,#f87171)',
            ];
            $i = 0;
        ?>
        <?php $__currentLoopData = $interestSessions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject => $sessions): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php $grad = $colors[$i % count($colors)]; $i++; ?>
        <div class="col-sm-6 col-lg-4">
            <div class="d-flex align-items-center gap-3 p-3 rounded-3 h-100"
                 style="background:var(--input-bg);border:1px solid var(--card-border)">
                <div style="width:42px;height:42px;border-radius:12px;background:<?php echo e($grad); ?>;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <i class="bi bi-book-fill text-white" style="font-size:.9rem"></i>
                </div>
                <div style="min-width:0">
                    <div class="fw-semibold text-truncate" style="font-size:.85rem;color:var(--text-primary)" title="<?php echo e($subject); ?>"><?php echo e($subject); ?></div>
                    <div class="mt-1 d-flex align-items-center gap-1">
                        <span class="fw-bold" style="font-size:1rem;color:var(--primary)"><?php echo e($sessions); ?></span>
                        <span class="text-muted" style="font-size:.75rem">sesi</span>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php if($totalSesiAll > 0): ?>
    <div class="mt-3 pt-3 d-flex align-items-center justify-content-between" style="border-top:1px solid var(--card-border)">
        <span class="text-muted" style="font-size:.78rem"><i class="bi bi-info-circle me-1"></i>Ditetapkan oleh admin cabang</span>
        <span class="fw-bold" style="font-size:.82rem;color:var(--primary)">
            Total <?php echo e($totalSesiAll); ?> sesi
        </span>
    </div>
    <?php endif; ?>
</div>
<?php endif; ?>


</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/siswa/kelas/index.blade.php ENDPATH**/ ?>