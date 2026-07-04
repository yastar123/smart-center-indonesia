<?php $__env->startSection('title', 'Absensi — Daftar Paket'); ?>
<?php $__env->startSection('page-title', 'Manajemen Absensi'); ?>

<?php $__env->startSection('content'); ?>

<div class="dashboard-card mb-4 fade-up" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div class="row align-items-center g-3" style="position:relative">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-3 mb-2">
                <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px">
                    <i class="bi bi-clipboard2-check-fill"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0" style="color:white">Manajemen Absensi</h5>
                    <span style="font-size:12px;opacity:.8">Pilih paket untuk melihat dan mengelola absensi per sesi</span>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="<?php echo e(route('admin.attendance.sessions')); ?>" class="btn fw-semibold px-4"
               style="background:rgba(255,255,255,.2);color:white;border:1px solid rgba(255,255,255,.3);border-radius:10px;backdrop-filter:blur(10px)">
                <i class="bi bi-list-check me-2"></i>Lihat Semua Sesi
            </a>
        </div>
    </div>
</div>


<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3 fade-up">
        <div class="stat-card" style="border-top:3px solid #c84ddf">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="stat-title">Total Paket</div><div class="stat-value"><?php echo e($pakets->count()); ?></div></div>
                <div class="stat-icon bg-primary-soft" style="color:white"><i class="bi bi-box-seam"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.05s">
        <div class="stat-card" style="border-top:3px solid #10b981">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="stat-title">Total Sesi</div><div class="stat-value text-success"><?php echo e($pakets->sum('total_sesi')); ?></div></div>
                <div class="stat-icon bg-success-soft" style="color:white"><i class="bi bi-calendar-week"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.10s">
        <div class="stat-card" style="border-top:3px solid #0284c7">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="stat-title">Rekap Hadir</div><div class="stat-value text-primary"><?php echo e($pakets->sum('hadir_count')); ?></div></div>
                <div class="stat-icon bg-info-soft" style="color:white"><i class="bi bi-person-check-fill"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.15s">
        <div class="stat-card" style="border-top:3px solid #ef4444">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="stat-title">Rekap Alpa</div><div class="stat-value text-danger"><?php echo e($pakets->sum('alpa_count')); ?></div></div>
                <div class="stat-icon bg-danger-soft" style="color:white"><i class="bi bi-person-x-fill"></i></div>
            </div>
        </div>
    </div>
</div>


<div class="dashboard-card fade-up">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h6 class="fw-bold mb-0">
            <i class="bi bi-box-seam text-primary me-2"></i>Daftar Paket
            <span class="badge ms-2" style="background:var(--soft-primary-bg);color:var(--soft-primary-text);font-size:11px"><?php echo e($pakets->count()); ?> paket</span>
        </h6>
    </div>

    <?php if($pakets->isEmpty()): ?>
    <div class="text-center py-5">
        <i class="bi bi-box-seam" style="font-size:48px;color:var(--text-muted);display:block;margin-bottom:12px;opacity:.4"></i>
        <div class="fw-semibold text-muted">Belum ada paket</div>
        <div style="font-size:12px;color:var(--text-muted)">Buat paket terlebih dahulu di menu Course Package</div>
    </div>
    <?php else: ?>
    <div class="table-responsive">
        <table class="table table-hover table-modern align-middle mb-0">
            <thead class="thead-modern">
                <tr>
                    <th class="ps-3">Nama Paket</th>
                    <th>Jenis</th>
                    <th>Cabang</th>
                    <th>Guru</th>
                    <th class="text-center">Total Sesi</th>
                    <th class="text-center">Selesai</th>
                    <th class="text-center">Hadir</th>
                    <th class="text-center">Alpa</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $pakets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $jenisMap = [
                        'privat'   => ['bg'=>'var(--soft-primary-bg)','color'=>'var(--soft-primary-text)','label'=>'Privat'],
                        'reguler'  => ['bg'=>'var(--soft-info-bg)','color'=>'var(--soft-info-text)','label'=>'Reguler'],
                        'intensif' => ['bg'=>'var(--soft-warning-bg)','color'=>'var(--soft-warning-text)','label'=>'Intensif'],
                        'online'   => ['bg'=>'var(--soft-success-bg)','color'=>'var(--soft-success-text)','label'=>'Online'],
                    ];
                    $jm = $jenisMap[$p->jenis] ?? ['bg'=>'var(--soft-muted-bg)','color'=>'var(--soft-muted-text)','label'=>ucfirst($p->jenis ?? '—')];
                    $progress = $p->total_sesi > 0 ? round($p->selesai_sesi / $p->total_sesi * 100) : 0;
                    $mapel = $p->mataPelajaran->pluck('nama')->implode(', ');
                ?>
                <tr style="border-bottom:1px solid var(--card-border);cursor:pointer"
                    onclick="window.location='<?php echo e(route('admin.attendance.sessions')); ?>?paket_id=<?php echo e($p->id); ?>'">
                    <td class="ps-3">
                        <div class="fw-semibold" style="font-size:13px"><?php echo e($p->nama); ?></div>
                        <?php if($mapel): ?>
                        <div class="text-muted" style="font-size:11px"><i class="bi bi-journal-bookmark me-1"></i><?php echo e(\Illuminate\Support\Str::limit($mapel, 50)); ?></div>
                        <?php endif; ?>
                        <?php if($p->total_sesi > 0): ?>
                        <div class="mt-1">
                            <div style="height:4px;background:var(--card-border);border-radius:2px;overflow:hidden;width:120px">
                                <div style="height:100%;width:<?php echo e($progress); ?>%;background:linear-gradient(90deg,#c84ddf,#461256);border-radius:2px;transition:width .6s ease"></div>
                            </div>
                            <span style="font-size:10px;color:var(--text-muted)"><?php echo e($progress); ?>% selesai</span>
                        </div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="badge" style="background:<?php echo e($jm['bg']); ?>;color:<?php echo e($jm['color']); ?>;font-size:11px;padding:4px 10px;border-radius:8px">
                            <?php echo e($jm['label']); ?>

                        </span>
                    </td>
                    <td style="font-size:12.5px"><?php echo e($p->cabang?->name ?? '—'); ?></td>
                    <td style="font-size:12.5px"><?php echo e($p->guru?->name ?? '—'); ?></td>
                    <td class="text-center">
                        <span class="fw-bold" style="font-size:13px"><?php echo e($p->total_sesi); ?></span>
                    </td>
                    <td class="text-center">
                        <span class="fw-semibold text-success" style="font-size:13px"><?php echo e($p->selesai_sesi); ?></span>
                    </td>
                    <td class="text-center">
                        <?php if($p->hadir_count > 0): ?>
                        <span style="color:var(--soft-success-text);font-size:12px;font-weight:600">
                            <i class="bi bi-check-circle-fill me-1"></i><?php echo e($p->hadir_count); ?>

                        </span>
                        <?php else: ?>
                        <span class="text-muted" style="font-size:12px">0</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-center">
                        <?php if($p->alpa_count > 0): ?>
                        <span style="color:var(--soft-danger-text);font-size:12px;font-weight:600">
                            <i class="bi bi-x-circle-fill me-1"></i><?php echo e($p->alpa_count); ?>

                        </span>
                        <?php else: ?>
                        <span class="text-muted" style="font-size:12px">0</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-center" onclick="event.stopPropagation()">
                        <a href="<?php echo e(route('admin.attendance.sessions')); ?>?paket_id=<?php echo e($p->id); ?>"
                           class="btn btn-sm btn-primary fw-semibold" style="border-radius:8px;font-size:11px;padding:5px 14px">
                            <i class="bi bi-eye me-1"></i>Lihat Sesi
                        </a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/admin/attendance/packages.blade.php ENDPATH**/ ?>