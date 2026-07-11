<?php $__env->startSection('title', 'Master Kurikulum & Silabus'); ?>
<?php $__env->startSection('page-title', 'Master Kurikulum & Silabus'); ?>

<?php $__env->startSection('content'); ?>


<div class="dashboard-card mb-4 fade-up"
     style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative;padding:28px">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3" style="position:relative">
        <div class="d-flex align-items-center gap-3">
            <div style="width:56px;height:56px;border-radius:18px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:26px;flex-shrink:0;border:1.5px solid rgba(255,255,255,.12)">
                <i class="bi bi-journal-richtext"></i>
            </div>
            <div>
                <div style="font-size:11px;opacity:.55;margin-bottom:4px;text-transform:uppercase;letter-spacing:.1em">Owner Panel</div>
                <h4 style="font-weight:800;margin-bottom:4px;color:white;letter-spacing:-.03em;font-size:clamp(18px,2.5vw,24px)">
                    Master Kurikulum & Silabus
                </h4>
                <p style="opacity:.65;margin:0;font-size:13px">Visualisasi paket, bab, dan pengelolaan silabus PDF</p>
            </div>
        </div>
        <a href="<?php echo e(route('owner.curriculum.create')); ?>"
           class="btn fw-semibold px-4"
           style="background:white;color:#461256;border:none;border-radius:12px;font-size:13px;box-shadow:0 4px 12px rgba(0,0,0,.15)">
            <i class="bi bi-plus-lg me-2"></i>Tambah Kurikulum & Silabus
        </a>
    </div>
</div>

<?php if(session('success')): ?>
<div class="alert alert-success d-flex align-items-center gap-2 mb-4 fade-up" style="border-radius:12px;border:none;background:rgba(16,185,129,.1);color:#065f46">
    <i class="bi bi-check-circle-fill"></i><?php echo e(session('success')); ?>

</div>
<?php endif; ?>

<?php if($curricula->isEmpty()): ?>
<div class="dashboard-card text-center py-5 fade-up">
    <div style="width:80px;height:80px;border-radius:50%;background:var(--input-bg);display:flex;align-items:center;justify-content:center;margin:0 auto 16px">
        <i class="bi bi-journal-richtext text-muted" style="font-size:2rem;opacity:.4"></i>
    </div>
    <h6 class="fw-semibold mb-2">Belum Ada Kurikulum</h6>
    <p class="text-muted mb-4" style="font-size:13px">Mulai tambahkan kurikulum & silabus untuk mata pelajaran Anda.</p>
    <a href="<?php echo e(route('owner.curriculum.create')); ?>" class="btn btn-primary" style="border-radius:10px">
        <i class="bi bi-plus-lg me-2"></i>Tambah Kurikulum & Silabus
    </a>
</div>
<?php else: ?>

<?php $__currentLoopData = $curricula; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $courseId => $courseItems): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<?php
    $firstItem = $courseItems->first();
    $course    = $firstItem->course;
    $colors    = ['#c84ddf','#10b981','#0284c7','#f6af23','#68117e','#059669'];
    $clr       = $colors[$loop->index % count($colors)];
    $totalBab  = $courseItems->sum(fn($c) => $c->chapters->count());
    $paketCount = $course->packages()->count();
?>

<div class="dashboard-card mb-4 fade-up" style="animation-delay:<?php echo e($loop->index * 0.05); ?>s">
    
    <div class="d-flex align-items-center justify-content-between mb-3 pb-3" style="border-bottom:1.5px solid var(--card-border)">
        <div class="d-flex align-items-center gap-3">
            <div style="width:46px;height:46px;border-radius:14px;background:<?php echo e($clr); ?>18;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <i class="bi bi-journal-bookmark-fill" style="color:<?php echo e($clr); ?>;font-size:20px"></i>
            </div>
            <div>
                <h6 class="fw-bold mb-0" style="color:var(--text-primary);font-size:16px"><?php echo e($course->nama); ?></h6>
                <div class="d-flex align-items-center gap-2 mt-1 flex-wrap">
                    <?php $__currentLoopData = $courseItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ci): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <span class="badge" style="background:<?php echo e($ci->scope === 'global' ? 'rgba(200,77,223,.12)' : 'rgba(2,132,199,.12)'); ?>;color:<?php echo e($ci->scope === 'global' ? '#c84ddf' : '#0284c7'); ?>;font-size:11px;padding:3px 9px;border-radius:20px">
                        <i class="bi bi-<?php echo e($ci->scope === 'global' ? 'globe' : 'geo-alt'); ?> me-1"></i>
                        <?php echo e($ci->scope === 'global' ? 'Global' : 'Lokal ('.$ci->cabang?->name.')'); ?>

                        &middot; <?php echo e($ci->chapters->count()); ?> bab
                    </span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <span class="text-muted" style="font-size:11px">
                        <i class="bi bi-box-seam me-1"></i><?php echo e($paketCount); ?> paket
                    </span>
                </div>
            </div>
        </div>
        <div class="d-flex gap-2">
            <?php $__currentLoopData = $courseItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ci): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('owner.curriculum.edit', $ci->id)); ?>"
               class="btn btn-sm"
               title="Edit kurikulum <?php echo e($ci->scope); ?>"
               style="background:var(--input-bg);border:1px solid var(--card-border);color:var(--text-muted);border-radius:8px;font-size:12px">
                <i class="bi bi-pencil me-1"></i>Edit
            </a>
            <form action="<?php echo e(route('owner.curriculum.destroy', $ci->id)); ?>" method="POST" class="d-inline"
                  onsubmit="return confirm('Hapus kurikulum ini?')">
                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                <button type="submit" class="btn btn-sm"
                        style="background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.2);color:#dc2626;border-radius:8px;font-size:12px">
                    <i class="bi bi-trash"></i>
                </button>
            </form>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    
    <?php $__currentLoopData = $courseItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ci): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php if($courseItems->count() > 1): ?>
    <div class="mb-2" style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:<?php echo e($ci->scope === 'global' ? '#c84ddf' : '#0284c7'); ?>;opacity:.8">
        <i class="bi bi-<?php echo e($ci->scope === 'global' ? 'globe' : 'geo-alt'); ?> me-1"></i>
        <?php echo e($ci->scope === 'global' ? 'Kurikulum Global' : 'Lokal — '.$ci->cabang?->name); ?>

    </div>
    <?php endif; ?>

    <?php if($ci->chapters->isEmpty()): ?>
    <p class="text-muted" style="font-size:13px">Belum ada bab. <a href="<?php echo e(route('owner.curriculum.edit', $ci->id)); ?>">Tambah bab</a></p>
    <?php else: ?>
    <div class="d-flex flex-column gap-2 mb-3">
        <?php $__currentLoopData = $ci->chapters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="d-flex align-items-center gap-3 p-3"
             style="border-radius:12px;border:1px solid var(--card-border);background:var(--input-bg)">
            <div style="width:32px;height:32px;border-radius:50%;background:<?php echo e($clr); ?>18;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;color:<?php echo e($clr); ?>;flex-shrink:0">
                <?php echo e($ch->urutan); ?>

            </div>
            <div style="flex:1;min-width:0">
                <div class="fw-semibold text-truncate" style="font-size:13.5px;color:var(--text-primary)"><?php echo e($ch->judul); ?></div>
                <div class="text-muted" style="font-size:11.5px">
                    <i class="bi bi-clock me-1"></i><?php echo e($ch->jumlah_sesi); ?> sesi
                </div>
            </div>
            <div class="d-flex align-items-center gap-2 flex-shrink-0">
                <?php if($ch->pdf_path): ?>
                <a href="<?php echo e(asset('storage/'.$ch->pdf_path)); ?>" target="_blank"
                   class="btn btn-sm d-flex align-items-center gap-1"
                   style="background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.2);color:#dc2626;border-radius:8px;font-size:11px;padding:4px 10px">
                    <i class="bi bi-file-pdf-fill"></i><span class="d-none d-md-inline">PDF</span>
                </a>
                <?php else: ?>
                <form action="<?php echo e(route('owner.curriculum.chapter.pdf', $ch->id)); ?>" method="POST" enctype="multipart/form-data" class="d-flex align-items-center gap-1">
                    <?php echo csrf_field(); ?>
                    <label class="btn btn-sm d-flex align-items-center gap-1 mb-0" style="background:var(--soft-muted-bg);border:1px dashed var(--card-border);color:var(--text-muted);border-radius:8px;font-size:11px;padding:4px 10px;cursor:pointer">
                        <i class="bi bi-upload"></i><span class="d-none d-md-inline">Upload PDF</span>
                        <input type="file" name="pdf" accept=".pdf" class="d-none" onchange="this.closest('form').submit()">
                    </label>
                </form>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/owner/curriculum/index.blade.php ENDPATH**/ ?>