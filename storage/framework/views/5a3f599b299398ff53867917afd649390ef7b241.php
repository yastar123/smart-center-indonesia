<?php $__env->startSection('title','List Mata Pelajaran'); ?>
<?php $__env->startSection('page-title','List Mata Pelajaran'); ?>

<?php $__env->startSection('content'); ?>

<div class="dashboard-card mb-4 fade-up"
     style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3" style="position:relative">
        <div class="d-flex align-items-center gap-3">
            <div style="width:52px;height:52px;border-radius:16px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:24px;flex-shrink:0">
                <i class="bi bi-journal-bookmark-fill"></i>
            </div>
            <div>
                <div style="font-size:11px;opacity:.6;margin-bottom:3px;text-transform:uppercase;letter-spacing:.08em">Program Belajar</div>
                <h4 class="fw-bold mb-1" style="color:white;letter-spacing:-.02em">Mata Pelajaran Saya</h4>
                <p class="mb-0" style="opacity:.75;font-size:13px">Mata pelajaran terdaftar beserta status pembayaran</p>
            </div>
        </div>
        <div style="font-size:64px;opacity:.08;line-height:1;flex-shrink:0">
            <i class="bi bi-journals"></i>
        </div>
    </div>
</div>


<?php if($registrationCourses->isNotEmpty()): ?>
<div class="dashboard-card fade-up mb-4">
    <div class="d-flex align-items-center gap-2 mb-3">
        <div style="width:34px;height:34px;border-radius:9px;background:linear-gradient(135deg,#260632,#c84ddf);display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <i class="bi bi-journal-check text-white" style="font-size:.9rem"></i>
        </div>
        <div>
            <div class="fw-bold" style="font-size:.92rem">Mata Pelajaran Terdaftar</div>
            <div class="text-muted" style="font-size:.75rem">
                <?php
                    $statusLabel = match($registration->academic_status ?? '') {
                        'terjadwal'      => 'Terjadwal',
                        'menunggu_kelas' => 'Menunggu Kelas',
                        default          => 'Aktif',
                    };
                    $statusColor = $registration->academic_status === 'terjadwal' ? 'var(--soft-success-text)' : 'var(--soft-warning-text)';
                ?>
                Status: <span style="color:<?php echo e($statusColor); ?>;font-weight:600"><?php echo e($statusLabel); ?></span>
            </div>
        </div>
    </div>
    <div class="row g-3">
        <?php $__currentLoopData = $registrationCourses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-md-6 col-lg-4">
            <div class="p-3 rounded-3 h-100 d-flex align-items-center gap-3"
                 style="background:var(--input-bg);border:1.5px solid var(--card-border)">
                <div style="width:42px;height:42px;border-radius:11px;background:rgba(200,77,223,.12);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <i class="bi bi-journal-bookmark-fill text-primary" style="font-size:17px"></i>
                </div>
                <div>
                    <div class="fw-semibold" style="font-size:.88rem;color:var(--text-primary)"><?php echo e($rc->nama); ?></div>
                    <?php if($rc->deskripsi): ?>
                    <div class="text-muted" style="font-size:.73rem;margin-top:2px"><?php echo e(Str::limit($rc->deskripsi, 50)); ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php endif; ?>

<div class="dashboard-card fade-up">
    <?php if($packages->isEmpty() && $registrationCourses->isEmpty()): ?>
    <div class="empty-state">
        <div class="empty-state-icon"><i class="bi bi-journal-x"></i></div>
        <div class="empty-state-title">Belum Ada Mata Pelajaran</div>
        <div class="empty-state-desc">Kamu belum terdaftar di paket apapun. Hubungi admin cabang untuk pendaftaran.</div>
    </div>
    <?php elseif($packages->isEmpty()): ?>
    <div class="text-center py-4">
        <p class="text-muted mb-0" style="font-size:13px">Belum ada paket belajar. Mata pelajaran terdaftar sudah ditampilkan di atas.</p>
    </div>
    <?php else: ?>
    <div class="d-flex align-items-center gap-2 mb-3">
        <div style="width:34px;height:34px;border-radius:9px;background:linear-gradient(135deg,#166534,#22c55e);display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <i class="bi bi-boxes text-white" style="font-size:.9rem"></i>
        </div>
        <div class="fw-bold" style="font-size:.92rem">Paket Belajar</div>
    </div>
    <div class="table-responsive">
        <table class="table table-modern align-middle mb-0">
            <thead>
                <tr>
                    <th>Nama Paket</th>
                    <th class="text-center">Jumlah Sesi</th>
                    <th>Metode Absensi</th>
                    <th>Tipe Kelas</th>
                    <th>Mata Pelajaran</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $packages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $package): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $courseNames = $package->mataPelajaran->pluck('nama')->filter()->implode(', ');
                ?>
                <tr>
                    <td>
                        <div class="fw-semibold"><?php echo e($package->nama); ?></div>
                        <?php if($package->deskripsi): ?>
                            <div class="text-muted" style="font-size:11px;margin-top:2px"><?php echo e(Str::limit($package->deskripsi, 60)); ?></div>
                        <?php endif; ?>
                    </td>
                    <td class="text-center">
                        <span class="badge" style="background:var(--soft-primary-bg);color:var(--soft-primary-text);border:1px solid var(--soft-primary-border);border-radius:8px;padding:4px 10px;font-weight:700">
                            <?php echo e($package->jumlah_pertemuan ?? 0); ?>x
                        </span>
                    </td>
                    <td>
                        <span class="badge" style="background:var(--soft-info-bg);color:var(--soft-info-text);border:1px solid var(--soft-info-border);border-radius:8px;padding:4px 10px;font-size:11px">
                            <?php echo e($package->metode_absensi ?? '-'); ?>

                        </span>
                    </td>
                    <td>
                        <?php
                            $tipeBg = match(strtolower($package->tipe_kelas ?? '')) {
                                'private' => ['bg'=>'var(--soft-warning-bg)','color'=>'var(--soft-warning-text)','border'=>'var(--soft-warning-border)'],
                                'reguler','regular','group' => ['bg'=>'var(--soft-success-bg)','color'=>'var(--soft-success-text)','border'=>'var(--soft-success-border)'],
                                default   => ['bg'=>'var(--soft-muted-bg)','color'=>'var(--soft-muted-text)','border'=>'var(--soft-muted-border)'],
                            };
                        ?>
                        <span class="badge" style="background:<?php echo e($tipeBg['bg']); ?>;color:<?php echo e($tipeBg['color']); ?>;border:1px solid <?php echo e($tipeBg['border']); ?>;border-radius:8px;padding:4px 10px;font-size:11px">
                            <?php echo e($package->tipe_kelas ?? '-'); ?>

                        </span>
                    </td>
                    <td>
                        <?php if($courseNames): ?>
                            <?php $__currentLoopData = $package->mataPelajaran->pluck('nama')->filter(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nama): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="badge me-1 mb-1" style="background:var(--soft-primary-bg);color:var(--soft-primary-text);border:1px solid var(--soft-primary-border);border-radius:6px;font-weight:500"><?php echo e($nama); ?></span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <span class="text-muted">—</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/siswa/courses/index.blade.php ENDPATH**/ ?>