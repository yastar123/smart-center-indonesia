<?php $__env->startSection('title', 'Sertifikat'); ?>
<?php $__env->startSection('page-title', 'Sertifikat'); ?>

<?php $__env->startSection('content'); ?>
<div>

    
    <div class="dashboard-card mb-4 fade-up" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
        <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
        <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
        <div class="row align-items-center g-3" style="position:relative">
            <div class="col-md-8">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0">
                        <i class="bi bi-award-fill"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0" style="color:white">Manajemen Sertifikat</h5>
                        <span style="font-size:12px;opacity:.8">Klik nama siswa untuk melihat mata pelajaran dan menerbitkan sertifikat</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-md-end">
                <div style="font-size:12px;opacity:.75;color:white;text-align:right;line-height:1.4">
                    <i class="bi bi-info-circle me-1"></i>Total Sertifikat: <strong><?php echo e($stats['total']); ?></strong>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row g-3 mb-4">
        <?php
            $statCards = [
                ['label'=>'Total Sertifikat','value'=>$stats['total'],     'icon'=>'bi-award-fill',     'topColor'=>'#f6af23','textColor'=>'text-warning','iconBg'=>'bg-warning-soft'],
                ['label'=>'Kompetensi',      'value'=>$stats['kompetensi'],'icon'=>'bi-patch-check-fill','topColor'=>'#c84ddf','textColor'=>'text-primary','iconBg'=>'bg-primary-soft'],
                ['label'=>'Kelulusan',       'value'=>$stats['kelulusan'], 'icon'=>'bi-mortarboard-fill','topColor'=>'#10b981','textColor'=>'text-success','iconBg'=>'bg-success-soft'],
                ['label'=>'Prestasi',        'value'=>$stats['prestasi'],  'icon'=>'bi-trophy-fill',    'topColor'=>'#ef4444','textColor'=>'text-danger', 'iconBg'=>'bg-danger-soft'],
            ];
        ?>
        <?php $__currentLoopData = $statCards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $sc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-6 col-lg-3 fade-up" style="animation-delay:<?php echo e($i * 0.05); ?>s">
            <div class="stat-card" style="border-top:3px solid <?php echo e($sc['topColor']); ?>">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-title"><?php echo e($sc['label']); ?></div>
                        <div class="stat-value <?php echo e($sc['textColor']); ?> count-up" data-target="<?php echo e($sc['value']); ?>"><?php echo e($sc['value']); ?></div>
                    </div>
                    <div class="stat-icon <?php echo e($sc['iconBg']); ?>" style="color:white">
                        <i class="bi <?php echo e($sc['icon']); ?>"></i>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <div class="dashboard-card mb-4 fade-up">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0">
                <i class="bi bi-people text-primary me-2"></i>Daftar Siswa
                <span class="badge ms-1" style="background:var(--soft-primary-bg);color:var(--soft-primary-text);font-size:11px"><?php echo e($students->total()); ?></span>
            </h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr style="background:var(--input-bg)">
                        <th style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600" class="ps-3">Nama Siswa</th>
                        <th style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Nama Cabang</th>
                        <th style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Paket</th>
                        <th class="text-center" style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Sertifikat</th>
                        <th class="text-center" style="color:var(--text-muted);font-size:12px;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr style="border-bottom:1px solid var(--card-border);transition:background .15s"
                        onmouseover="this.style.background='rgba(104,17,126,.05)'"
                        onmouseout="this.style.background=''">
                        <td class="ps-3">
                            <div class="d-flex align-items-center gap-2">
                                <div style="width:34px;height:34px;border-radius:9px;background:linear-gradient(135deg,#c84ddf,#7c3aed);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                    <i class="bi bi-person-fill" style="color:white;font-size:14px"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold" style="font-size:13px"><?php echo e($st->user?->name ?? 'Siswa #'.$st->id); ?></div>
                                    <div class="text-muted" style="font-size:11px">NIS: <?php echo e($st->nis ?? '—'); ?></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span style="font-size:13px"><?php echo e($st->branch?->name ?? '—'); ?></span>
                        </td>
                        <td>
                            <?php if($st->package): ?>
                                <span style="font-size:13px;font-weight:500"><?php echo e($st->package->nama); ?></span>
                                <div class="text-muted" style="font-size:11px"><?php echo e($st->package->jumlah_pertemuan ?? '—'); ?> sesi</div>
                            <?php else: ?>
                                <span class="text-muted" style="font-size:12px">—</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <?php $certCount = \App\Models\Certificate::where('siswa_id', $st->id)->count(); ?>
                            <?php if($certCount > 0): ?>
                                <span style="background:var(--soft-success-bg);color:var(--soft-success-text);padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600"><?php echo e($certCount); ?> sertifikat</span>
                            <?php else: ?>
                                <span style="background:var(--soft-muted-bg);color:var(--text-muted);padding:3px 10px;border-radius:20px;font-size:11px">Belum ada</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <a href="<?php echo e(route('admin.certificates.student', $st->id)); ?>"
                               class="btn btn-sm btn-primary"
                               style="border-radius:8px;font-size:12px">
                                <i class="bi bi-arrow-right-circle me-1"></i>Lihat & Upload
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-people" style="font-size:2rem;display:block;opacity:.25;margin-bottom:.5rem"></i>
                            Belum ada siswa aktif
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($students->hasPages()): ?>
        <div class="mt-3 px-2"><?php echo e($students->links()); ?></div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/admin/certificates/index.blade.php ENDPATH**/ ?>