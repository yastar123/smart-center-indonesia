<?php $__env->startSection('title','Riwayat Absensi'); ?>
<?php $__env->startSection('page-title','Riwayat Absensi'); ?>

<?php $__env->startSection('content'); ?>

<div class="dashboard-card mb-4 fade-up"
     style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3" style="position:relative">
        <div class="d-flex align-items-center gap-3">
            <div style="width:52px;height:52px;border-radius:16px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:24px;flex-shrink:0">
                <i class="bi bi-clock-history"></i>
            </div>
            <div>
                <div style="font-size:11px;opacity:.6;margin-bottom:3px;text-transform:uppercase;letter-spacing:.08em">Rekap Kehadiran</div>
                <h4 class="fw-bold mb-1" style="color:white;letter-spacing:-.02em">Riwayat Absensi</h4>
                <p class="mb-0" style="opacity:.75;font-size:13px">Daftar mata pelajaran dan jumlah pertemuan yang telah dilaksanakan</p>
            </div>
        </div>
        <div style="font-size:64px;opacity:.08;line-height:1;flex-shrink:0">
            <i class="bi bi-clipboard2-check-fill"></i>
        </div>
    </div>
</div>


<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 fade-up">
        <div class="stat-card" style="border-top:3px solid #c84ddf">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Mata Pelajaran</div>
                    <div class="stat-value text-primary"><?php echo e($courses->count()); ?></div>
                    <div class="stat-label text-muted" style="font-size:11px">yang diajar</div>
                </div>
                <div class="stat-icon bg-primary-soft" style="color:white"><i class="bi bi-journal-bookmark-fill"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 fade-up" style="animation-delay:.05s">
        <div class="stat-card" style="border-top:3px solid #10b981">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Total Pertemuan</div>
                    <div class="stat-value text-success"><?php echo e(($meetingCounts ?? collect())->sum()); ?></div>
                    <div class="stat-label text-muted" style="font-size:11px">semua mapel</div>
                </div>
                <div class="stat-icon bg-success-soft" style="color:white"><i class="bi bi-calendar-check-fill"></i></div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4 fade-up" style="animation-delay:.10s">
        <div class="stat-card" style="border-top:3px solid #f6af23">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Status Absensi</div>
                    <div class="stat-value" style="color:#f6af23;font-size:16px">Aktif</div>
                    <div class="stat-label" style="font-size:11px;color:#f6af23"><i class="bi bi-circle-fill me-1" style="font-size:7px"></i>Tahun Ajaran Berjalan</div>
                </div>
                <div class="stat-icon bg-warning-soft" style="color:white"><i class="bi bi-activity"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="dashboard-card fade-up">
    <?php if($courses->isEmpty()): ?>
    <div class="empty-state">
        <div class="empty-state-icon"><i class="bi bi-clipboard-x"></i></div>
        <div class="empty-state-title">Belum Ada Mata Pelajaran</div>
        <div class="empty-state-desc">Anda belum memiliki mata pelajaran yang diajar. Hubungi admin untuk penugasan kelas.</div>
    </div>
    <?php else: ?>
    <div class="row g-3">
        <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-md-6 col-lg-4">
            <a href="<?php echo e(route('guru.attendance.history.show', $course->id)); ?>"
               class="text-decoration-none d-block p-4 rounded-3 h-100"
               style="background:var(--input-bg);border:1.5px solid var(--card-border)">
                <div class="fw-bold mb-1" style="color:var(--text-primary)"><?php echo e($course->nama); ?></div>
                <div class="text-muted mb-3" style="font-size:12px"><?php echo e($course->deskripsi ?? 'Mata pelajaran'); ?></div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="badge bg-primary-soft text-primary">
                        <i class="bi bi-calendar-event me-1"></i>
                        <?php echo e($meetingCounts[$course->id] ?? 0); ?> Pertemuan
                    </span>
                    <i class="bi bi-arrow-right text-primary"></i>
                </div>
            </a>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/guru/attendance-history/index.blade.php ENDPATH**/ ?>