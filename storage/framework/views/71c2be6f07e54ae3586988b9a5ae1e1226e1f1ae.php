<?php $__env->startSection('title', 'Detail Promo'); ?>
<?php $__env->startSection('page-title', 'Detail Promo'); ?>

<?php $__env->startSection('content'); ?>

<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3 fade-up">
    <a href="<?php echo e(route('owner.promo.index')); ?>" class="btn btn-sm"
       style="background:var(--input-bg);border:1px solid var(--card-border);color:var(--text-muted);border-radius:9px;font-size:13px">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
    <div class="d-flex gap-2">
        <a href="<?php echo e(route('owner.promo.edit', $promo->id)); ?>" class="btn btn-sm btn-outline-primary" style="border-radius:9px;font-size:13px">
            <i class="bi bi-pencil me-1"></i>Edit
        </a>
        <form action="<?php echo e(route('owner.promo.destroy', $promo->id)); ?>" method="POST" class="d-inline"
              onsubmit="return confirm('Hapus promo ini?')">
            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
            <button type="submit" class="btn btn-sm" style="border-radius:9px;font-size:13px;background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.2);color:#dc2626">
                <i class="bi bi-trash me-1"></i>Hapus
            </button>
        </form>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">

        
        <?php if($promo->banner_path): ?>
        <div class="dashboard-card mb-4 fade-up p-0" style="overflow:hidden;border-radius:18px">
            <img src="<?php echo e(asset('storage/'.$promo->banner_path)); ?>" alt="<?php echo e($promo->judul); ?>"
                 style="width:100%;max-height:300px;object-fit:cover;display:block">
        </div>
        <?php endif; ?>

        <div class="dashboard-card mb-4 fade-up" style="animation-delay:.04s">
            <div class="d-flex align-items-start justify-content-between gap-3 mb-3">
                <div>
                    <div class="text-muted mb-1" style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.08em"><?php echo e($promo->kode); ?></div>
                    <h5 class="fw-bold mb-2" style="color:var(--text-primary)"><?php echo e($promo->judul); ?></h5>
                    <span class="badge" style="background:rgba(200,77,223,.12);color:#c84ddf;font-size:11px;padding:4px 10px;border-radius:20px">
                        <?php echo e($promo->tipe_label); ?>

                    </span>
                </div>
                <?php
                    $sc = match($promo->status) {
                        'aktif'    => ['bg'=>'#dcfce7','text'=>'#16a34a'],
                        'berakhir' => ['bg'=>'rgba(148,163,184,.15)','text'=>'#64748b'],
                        default    => ['bg'=>'rgba(246,175,35,.15)','text'=>'#d97706'],
                    };
                ?>
                <span class="badge flex-shrink-0" style="background:<?php echo e($sc['bg']); ?>;color:<?php echo e($sc['text']); ?>;font-size:12px;padding:6px 14px;border-radius:20px;font-weight:600">
                    <?php echo e($promo->status_label); ?>

                </span>
            </div>

            <?php if($promo->kode_promo): ?>
            <div class="p-3 mb-3" style="background:var(--input-bg);border-radius:12px;border:1px dashed var(--card-border)">
                <div class="text-muted mb-1" style="font-size:11px">KODE PROMO</div>
                <div class="fw-bold" style="font-size:18px;letter-spacing:.12em;color:#c84ddf;font-family:monospace"><?php echo e($promo->kode_promo); ?></div>
            </div>
            <?php endif; ?>

            <?php if($promo->deskripsi): ?>
            <div style="font-size:14px;color:var(--text-primary);line-height:1.7"><?php echo e($promo->deskripsi); ?></div>
            <?php endif; ?>
        </div>

    </div>

    <div class="col-lg-4">
        <div class="dashboard-card mb-3 fade-up" style="animation-delay:.03s">
            <h6 class="fw-bold mb-3" style="color:var(--text-primary);font-size:13px;text-transform:uppercase;letter-spacing:.05em">Periode Tayang</h6>
            <div class="d-flex align-items-center gap-2 mb-2">
                <i class="bi bi-calendar-event text-primary" style="font-size:14px"></i>
                <span style="font-size:13px"><?php echo e($promo->tanggal_mulai->format('d M Y')); ?></span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-calendar-x text-danger" style="font-size:14px"></i>
                <span style="font-size:13px"><?php echo e($promo->tanggal_berakhir->format('d M Y')); ?></span>
            </div>
        </div>

        <div class="dashboard-card mb-3 fade-up" style="animation-delay:.05s">
            <h6 class="fw-bold mb-3" style="color:var(--text-primary);font-size:13px;text-transform:uppercase;letter-spacing:.05em">Target</h6>
            <?php
                $targetLabel = [
                    'semua'          => 'Semua Siswa',
                    'paket_intensif' => 'Hanya Paket Intensif',
                    'cabang'         => 'Cabang: '.($promo->cabang?->name ?? '—'),
                    'cicilan'        => 'Hanya Siswa Cicilan',
                ][$promo->target] ?? $promo->target;
            ?>
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-people-fill text-primary" style="font-size:14px"></i>
                <span style="font-size:13px"><?php echo e($targetLabel); ?></span>
            </div>
        </div>

        <div class="dashboard-card fade-up" style="animation-delay:.07s">
            <h6 class="fw-bold mb-3" style="color:var(--text-primary);font-size:13px;text-transform:uppercase;letter-spacing:.05em">Statistik</h6>
            <div class="row g-3">
                <div class="col-6">
                    <div class="text-center p-3" style="background:var(--input-bg);border-radius:12px">
                        <div class="fw-bold" style="font-size:22px;color:var(--text-primary)"><?php echo e(number_format($promo->views)); ?></div>
                        <div class="text-muted" style="font-size:11px">Views</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="text-center p-3" style="background:rgba(200,77,223,.06);border-radius:12px">
                        <div class="fw-bold" style="font-size:22px;color:#c84ddf"><?php echo e(number_format($promo->claims)); ?></div>
                        <div class="text-muted" style="font-size:11px">Claims</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/owner/promo/show.blade.php ENDPATH**/ ?>