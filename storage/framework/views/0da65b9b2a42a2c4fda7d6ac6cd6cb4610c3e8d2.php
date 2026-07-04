<?php $__env->startSection('page-title', 'Halaman Landing Cabang'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-0">

    
    <div class="dashboard-card mb-4 fade-up" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
        <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
        <div class="row align-items-center g-3" style="position:relative">
            <div class="col-md-8">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0" style="color:white">Kelola Halaman Landing Per Cabang</h5>
                        <span style="font-size:12px;opacity:.8">Edit konten promo, hero, metode belajar, jam operasional, area layanan, dan FAQ untuk setiap halaman cabang</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-md-end d-flex gap-2 justify-content-md-end">
                <a href="<?php echo e(route('admin.landing.index')); ?>" class="btn fw-semibold px-3"
                   style="background:rgba(255,255,255,.15);color:white;border:1px solid rgba(255,255,255,.25);border-radius:10px">
                    <i class="bi bi-arrow-left me-1"></i>Kembali ke Landing Utama
                </a>
            </div>
        </div>
    </div>

    
    <div class="alert border-0 mb-4 d-flex align-items-start gap-3" style="background:rgba(200,77,223,.06);border-left:4px solid #c84ddf !important;border-radius:12px">
        <i class="bi bi-info-circle mt-1" style="color:var(--bs-primary);font-size:1.2rem;flex-shrink:0"></i>
        <div class="small text-muted">
            Setiap cabang memiliki halaman landing sendiri di <code>/cabang/{id}</code>.
            Klik <strong>Edit Landing</strong> pada cabang yang ingin diubah kontennya — promo ticker, teks hero, harga metode belajar, jam operasional, area layanan, dan FAQ.<br>
            Kontak, email, dan alamat cabang dikelola melalui menu <strong>Kelola Cabang</strong>.
            Paket harga dikelola melalui menu <strong>Paket</strong>.
        </div>
    </div>

    
    <div class="row g-3">
        <?php $__empty_1 = true; $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <?php
            $city = $branch->city ?: $branch->name;
            $statusColor = $branch->status === 'aktif' ? '#10b981' : '#6b7280';
        ?>
        <div class="col-md-6 col-xl-4">
            <div class="card h-100" style="border:1px solid rgba(200,77,223,.12);border-radius:16px;box-shadow:0 2px 12px rgba(38,6,50,.05);overflow:hidden">
                
                <div style="background:linear-gradient(135deg,#260632 0%,#461256 70%,#c84ddf 100%);padding:1.1rem 1.25rem;display:flex;align-items:center;justify-content:space-between">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width:36px;height:36px;border-radius:10px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:1rem">
                            <i class="bi bi-building" style="color:white"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-white" style="font-size:.95rem;line-height:1.2"><?php echo e($branch->name); ?></div>
                            <div style="font-size:.7rem;color:rgba(255,255,255,.65)"><?php echo e($city); ?></div>
                        </div>
                    </div>
                    <span style="font-size:.65rem;font-weight:700;padding:3px 9px;border-radius:50px;background:rgba(255,255,255,.15);color:rgba(255,255,255,.9)">
                        <?php echo e(strtoupper($branch->status)); ?>

                    </span>
                </div>

                <div class="card-body d-flex flex-column gap-3 p-3">
                    
                    <div class="d-flex flex-column gap-1">
                        <?php if($branch->phone): ?>
                        <div class="d-flex align-items-center gap-2 small text-muted">
                            <i class="bi bi-telephone-fill" style="color:var(--bs-primary);font-size:.75rem;width:14px"></i>
                            <?php echo e($branch->phone); ?>

                        </div>
                        <?php endif; ?>
                        <?php if($branch->email): ?>
                        <div class="d-flex align-items-center gap-2 small text-muted">
                            <i class="bi bi-envelope-fill" style="color:var(--bs-primary);font-size:.75rem;width:14px"></i>
                            <?php echo e($branch->email); ?>

                        </div>
                        <?php endif; ?>
                        <?php if($branch->address): ?>
                        <div class="d-flex align-items-start gap-2 small text-muted">
                            <i class="bi bi-geo-alt-fill" style="color:var(--bs-primary);font-size:.75rem;width:14px;margin-top:2px"></i>
                            <span><?php echo e(Str::limit($branch->address, 60)); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>

                    
                    <div class="d-flex gap-2 mt-auto">
                        <a href="<?php echo e(route('admin.landing.cabang.show', $branch)); ?>"
                           class="btn btn-primary btn-sm flex-fill fw-semibold">
                            <i class="bi bi-pencil-square me-1"></i>Edit Landing
                        </a>
                        <a href="<?php echo e(route('cabang.show', $branch)); ?>" target="_blank"
                           class="btn btn-sm fw-semibold"
                           style="background:rgba(200,77,223,.08);color:var(--bs-primary);border:1px solid rgba(200,77,223,.2)">
                            <i class="bi bi-eye me-1"></i>Lihat
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-12">
            <div class="text-center py-5 text-muted">
                <i class="bi bi-buildings" style="font-size:3rem;opacity:.3"></i>
                <p class="mt-2">Belum ada cabang yang terdaftar.</p>
            </div>
        </div>
        <?php endif; ?>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/admin/landing/cabang-index.blade.php ENDPATH**/ ?>