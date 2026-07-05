<?php $__env->startSection('title', 'Pengumuman'); ?>
<?php $__env->startSection('page-title', 'Pengumuman'); ?>

<?php $__env->startSection('content'); ?>


<div class="dashboard-card mb-4 fade-up"
     style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
    <div class="d-flex align-items-center gap-3" style="position:relative">
        <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0">
            <i class="bi bi-megaphone-fill"></i>
        </div>
        <div>
            <h5 class="fw-bold mb-0" style="color:white">Pengumuman</h5>
            <span style="font-size:12px;opacity:.8">Informasi terbaru dari lembaga Smart Center Indonesia</span>
        </div>
        <div class="ms-auto d-flex align-items-center gap-2">
            <span class="badge" style="background:rgba(255,255,255,.15);color:white;padding:6px 14px;border-radius:20px;font-size:12px">
                <i class="bi bi-bell me-1"></i><?php echo e($announcements->total()); ?> pengumuman aktif
            </span>
        </div>
    </div>
</div>

<?php
$jenisConfig = [
    'info'    => ['label'=>'Info',    'icon'=>'bi-info-circle-fill',       'bg'=>'var(--soft-primary-bg)',  'color'=>'var(--soft-primary-text)',  'border'=>'var(--soft-primary-border)'],
    'promo'   => ['label'=>'Promo',   'icon'=>'bi-tag-fill',               'bg'=>'rgba(246,175,35,.12)',     'color'=>'#d97706',                   'border'=>'rgba(246,175,35,.3)'],
    'penting' => ['label'=>'Penting', 'icon'=>'bi-exclamation-triangle-fill','bg'=>'rgba(239,68,68,.08)',    'color'=>'#dc2626',                   'border'=>'rgba(239,68,68,.25)'],
    'update'  => ['label'=>'Update',  'icon'=>'bi-arrow-up-circle-fill',   'bg'=>'var(--soft-success-bg)',  'color'=>'var(--soft-success-text)',  'border'=>'var(--soft-success-border)'],
];
?>

<?php if($announcements->isEmpty()): ?>

<div class="dashboard-card fade-up text-center py-5">
    <i class="bi bi-megaphone" style="font-size:4rem;opacity:.15;display:block;margin-bottom:16px"></i>
    <h5 class="fw-bold mb-2">Belum Ada Pengumuman</h5>
    <p class="text-muted mb-4" style="font-size:14px;max-width:360px;margin:0 auto">
        Tidak ada pengumuman aktif saat ini. Pantau terus halaman ini untuk informasi terbaru.
    </p>
    <a href="<?php echo e(route('siswa.dashboard')); ?>" class="btn btn-primary px-4 fw-semibold" style="border-radius:10px">
        <i class="bi bi-arrow-left me-2"></i>Kembali ke Dashboard
    </a>
</div>
<?php else: ?>


<?php $pinned = $announcements->filter(fn($a) => $a->is_pinned); ?>
<?php if($pinned->isNotEmpty()): ?>
<div class="mb-3">
    <div class="d-flex align-items-center gap-2 mb-2" style="font-size:12px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em">
        <i class="bi bi-pin-fill" style="color:#c84ddf"></i> Disematkan
    </div>
    <div class="row g-3">
        <?php $__currentLoopData = $pinned; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ann): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php $cfg = $jenisConfig[$ann->jenis] ?? $jenisConfig['info']; ?>
        <div class="col-12 fade-up">
            <div class="dashboard-card" style="border-left:4px solid #c84ddf;position:relative">
                <div style="position:absolute;top:12px;right:14px;display:flex;gap:6px;align-items:center">
                    <span style="background:rgba(200,77,223,.1);color:#c84ddf;border:1px solid rgba(200,77,223,.25);border-radius:20px;padding:3px 10px;font-size:11px;font-weight:600">
                        <i class="bi bi-pin me-1"></i>Disematkan
                    </span>
                    <span style="background:<?php echo e($cfg['bg']); ?>;color:<?php echo e($cfg['color']); ?>;border:1px solid <?php echo e($cfg['border']); ?>;border-radius:20px;padding:3px 10px;font-size:11px;font-weight:600">
                        <i class="<?php echo e($cfg['icon']); ?> me-1"></i><?php echo e($cfg['label']); ?>

                    </span>
                </div>
                <h6 class="fw-bold mb-2" style="font-size:15px;padding-right:180px"><?php echo e($ann->judul); ?></h6>
                <p class="text-muted mb-3" style="font-size:13.5px;line-height:1.65;white-space:pre-line"><?php echo e($ann->konten); ?></p>
                <div class="d-flex align-items-center gap-3 flex-wrap" style="font-size:12px;color:var(--text-muted)">
                    <span><i class="bi bi-calendar3 me-1"></i><?php echo e($ann->created_at->locale('id')->isoFormat('D MMM Y')); ?></span>
                    <?php if($ann->tanggal_mulai || $ann->tanggal_selesai): ?>
                    <span><i class="bi bi-clock me-1"></i>
                        <?php echo e($ann->tanggal_mulai?->locale('id')->isoFormat('D MMM') ?? '–'); ?>

                        s/d
                        <?php echo e($ann->tanggal_selesai?->locale('id')->isoFormat('D MMM Y') ?? 'Tidak terbatas'); ?>

                    </span>
                    <?php endif; ?>
                    <?php if($ann->cabang): ?>
                    <span><i class="bi bi-building me-1"></i><?php echo e($ann->cabang->name); ?></span>
                    <?php else: ?>
                    <span><i class="bi bi-globe me-1"></i>Semua Cabang</span>
                    <?php endif; ?>
                    <?php if($ann->file): ?>
                    <a href="<?php echo e(Storage::url($ann->file)); ?>" target="_blank" class="text-decoration-none fw-semibold" style="color:#c84ddf">
                        <i class="bi bi-paperclip me-1"></i>Lampiran
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php endif; ?>


<?php $regular = $announcements->filter(fn($a) => !$a->is_pinned); ?>
<?php if($regular->isNotEmpty()): ?>
<div class="d-flex align-items-center gap-2 mb-2" style="font-size:12px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em">
    <i class="bi bi-clock-history" style="color:#c84ddf"></i> Terbaru
</div>
<div class="row g-3 mb-4">
    <?php $__currentLoopData = $regular; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ann): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php $cfg = $jenisConfig[$ann->jenis] ?? $jenisConfig['info']; ?>
    <div class="col-md-6 col-lg-4 fade-up">
        <div class="dashboard-card h-100 d-flex flex-column" style="border-top:3px solid <?php echo e($ann->jenis === 'penting' ? '#ef4444' : ($ann->jenis === 'promo' ? '#f6af23' : '#c84ddf')); ?>">
            <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
                <span style="background:<?php echo e($cfg['bg']); ?>;color:<?php echo e($cfg['color']); ?>;border:1px solid <?php echo e($cfg['border']); ?>;border-radius:20px;padding:3px 10px;font-size:11px;font-weight:600;flex-shrink:0">
                    <i class="<?php echo e($cfg['icon']); ?> me-1"></i><?php echo e($cfg['label']); ?>

                </span>
                <span style="font-size:11px;color:var(--text-muted);flex-shrink:0"><?php echo e($ann->created_at->diffForHumans()); ?></span>
            </div>
            <h6 class="fw-bold mb-2" style="font-size:14px;line-height:1.4"><?php echo e($ann->judul); ?></h6>
            <p class="text-muted flex-grow-1" style="font-size:13px;line-height:1.6;overflow:hidden;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical"><?php echo e($ann->konten); ?></p>
            <div class="d-flex align-items-center justify-content-between mt-2 pt-2" style="border-top:1px solid var(--card-border);font-size:11.5px;color:var(--text-muted)">
                <span>
                    <?php if($ann->cabang): ?><i class="bi bi-building me-1"></i><?php echo e($ann->cabang->name); ?>

                    <?php else: ?><i class="bi bi-globe me-1"></i>Semua Cabang
                    <?php endif; ?>
                </span>
                <div class="d-flex align-items-center gap-2">
                    <?php if($ann->tanggal_selesai): ?>
                    <span style="background:var(--input-bg);border-radius:6px;padding:2px 7px;border:1px solid var(--card-border)">
                        <i class="bi bi-hourglass-split me-1"></i>s/d <?php echo e($ann->tanggal_selesai->locale('id')->isoFormat('D MMM Y')); ?>

                    </span>
                    <?php endif; ?>
                    <?php if($ann->file): ?>
                    <a href="<?php echo e(Storage::url($ann->file)); ?>" target="_blank" class="text-decoration-none" style="color:#c84ddf" title="Lihat lampiran">
                        <i class="bi bi-paperclip"></i>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php endif; ?>


<?php if($announcements->hasPages()): ?>
<div class="d-flex justify-content-center mt-2">
    <?php echo e($announcements->links()); ?>

</div>
<?php endif; ?>

<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/siswa/announcements.blade.php ENDPATH**/ ?>