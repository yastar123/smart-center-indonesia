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

<div class="dashboard-card fade-up">
    <?php if($packages->isEmpty()): ?>
    <div class="empty-state">
        <div class="empty-state-icon"><i class="bi bi-journal-x"></i></div>
        <div class="empty-state-title">Belum Ada Mata Pelajaran</div>
        <div class="empty-state-desc">Kamu belum terdaftar di paket apapun. Hubungi admin cabang untuk pendaftaran.</div>
    </div>
    <?php else: ?>
    <div class="table-responsive">
        <table class="table table-bordered align-middle mb-0">
            <thead>
                <tr>
                    <th>Nama Paket</th>
                    <th>Jumlah Sesi</th>
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
                    <td class="fw-semibold"><?php echo e($package->nama); ?></td>
                    <td><?php echo e($package->jumlah_pertemuan ?? 0); ?></td>
                    <td><?php echo e($package->metode_absensi ?? '-'); ?></td>
                    <td><?php echo e($package->tipe_kelas ?? '-'); ?></td>
                    <td><?php echo e($courseNames ?: '-'); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/siswa/courses/index.blade.php ENDPATH**/ ?>