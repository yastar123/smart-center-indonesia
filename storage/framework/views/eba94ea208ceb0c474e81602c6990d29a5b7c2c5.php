<?php $__env->startSection('title','Laporan Absensi'); ?>
<?php $__env->startSection('page-title','Laporan Absensi'); ?>

<?php $__env->startSection('content'); ?>


<div class="dashboard-card mb-4 fade-up"
     style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div class="row align-items-center g-3" style="position:relative">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-3">
                <a href="<?php echo e(route('guru.attendance.history')); ?>"
                   class="btn btn-sm flex-shrink-0"
                   style="background:rgba(255,255,255,.15);color:white;border:1px solid rgba(255,255,255,.25);border-radius:9px;padding:6px 12px">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
                <div>
                    <div style="font-size:11px;opacity:.6;margin-bottom:2px;text-transform:uppercase;letter-spacing:.08em">Guru</div>
                    <h5 class="fw-bold mb-0" style="color:white">Laporan Absensi Saya</h5>
                    <div style="font-size:12px;opacity:.75">Rekap semua jadwal yang telah diabsensi</div>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-md-end">
            <div style="font-size:48px;opacity:.1;line-height:1"><i class="bi bi-clipboard2-data-fill"></i></div>
        </div>
    </div>
</div>


<div class="dashboard-card fade-up">
    <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
        <h6 class="fw-bold mb-0" style="font-size:14px;color:var(--text-primary)">
            <i class="bi bi-table text-primary me-2"></i>Daftar Jadwal Diabsensi
        </h6>
        <a href="<?php echo e(route('guru.attendance.history')); ?>" class="btn btn-outline-primary btn-sm" style="border-radius:9px;font-size:12.5px">
            <i class="bi bi-clock-history me-1"></i>Riwayat Lengkap
        </a>
    </div>

    <?php if($schedules->isEmpty()): ?>
    <div class="text-center py-5">
        <div style="width:80px;height:80px;border-radius:50%;background:var(--input-bg);display:flex;align-items:center;justify-content:center;margin:0 auto 16px">
            <i class="bi bi-clipboard2-x text-muted" style="font-size:2rem;opacity:.5"></i>
        </div>
        <h6 class="fw-semibold" style="color:var(--text-primary)">Belum Ada Data Absensi</h6>
        <p class="text-muted mb-0" style="font-size:13px">Belum ada jadwal yang telah direkam absensinya.</p>
    </div>
    <?php else: ?>
    <div class="table-responsive">
        <table class="table table-hover align-middle" style="font-size:13px">
            <thead>
                <tr style="background:var(--input-bg)">
                    <th class="text-muted fw-semibold py-2" style="font-size:11px;text-transform:uppercase;letter-spacing:.04em">#</th>
                    <th class="text-muted fw-semibold py-2" style="font-size:11px;text-transform:uppercase;letter-spacing:.04em">Kelas</th>
                    <th class="text-muted fw-semibold py-2" style="font-size:11px;text-transform:uppercase;letter-spacing:.04em">Tanggal</th>
                    <th class="text-muted fw-semibold py-2" style="font-size:11px;text-transform:uppercase;letter-spacing:.04em">Pertemuan</th>
                    <th class="text-muted fw-semibold py-2 text-center" style="font-size:11px;text-transform:uppercase;letter-spacing:.04em">Hadir</th>
                    <th class="text-muted fw-semibold py-2 text-center" style="font-size:11px;text-transform:uppercase;letter-spacing:.04em">Konfirmasi</th>
                </tr>
            </thead>
            <tbody>
            <?php $__currentLoopData = $schedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $tgl     = \Carbon\Carbon::parse($s->tanggal);
                $total   = $s->absensi->count();
                $hadir   = $s->absensi->where('guru_hadir', true)->count();
                $confirm = $s->absensi->whereNotNull('siswa_konfirmasi_at')->count();
            ?>
            <tr>
                <td class="text-muted"><?php echo e($schedules->firstItem() + $i); ?></td>
                <td>
                    <div class="fw-semibold" style="font-size:13px"><?php echo e($s->kelas->nama_kelas ?? '–'); ?></div>
                    <div class="text-muted" style="font-size:11px"><?php echo e(\Carbon\Carbon::parse($s->jam_mulai)->format('H:i')); ?>–<?php echo e(\Carbon\Carbon::parse($s->jam_selesai)->format('H:i')); ?></div>
                </td>
                <td><?php echo e($tgl->locale('id')->isoFormat('D MMM Y')); ?></td>
                <td>
                    <?php if($s->pertemuan_ke): ?>
                    <span style="background:var(--soft-primary-bg);color:var(--soft-primary-text);padding:2px 8px;border-radius:6px;font-size:11px;font-weight:700">ke-<?php echo e($s->pertemuan_ke); ?></span>
                    <?php else: ?>
                    <span class="text-muted">–</span>
                    <?php endif; ?>
                </td>
                <td class="text-center">
                    <?php if($total > 0): ?>
                    <span style="background:var(--soft-success-bg);color:var(--soft-success-text);padding:3px 10px;border-radius:7px;font-size:12px;font-weight:600">
                        <?php echo e($hadir); ?>/<?php echo e($total); ?>

                    </span>
                    <?php else: ?>
                    <span class="text-muted" style="font-size:12px">–</span>
                    <?php endif; ?>
                </td>
                <td class="text-center">
                    <?php if($hadir > 0): ?>
                    <span style="background:<?php echo e($confirm==$hadir ? 'var(--soft-success-bg)' : 'var(--soft-warning-bg)'); ?>;color:<?php echo e($confirm==$hadir ? 'var(--soft-success-text)' : 'var(--soft-warning-text)'); ?>;padding:3px 10px;border-radius:7px;font-size:12px;font-weight:600">
                        <?php echo e($confirm); ?>/<?php echo e($hadir); ?>

                    </span>
                    <?php else: ?>
                    <span class="text-muted" style="font-size:12px">–</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <div class="mt-2"><?php echo e($schedules->links()); ?></div>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/guru/attendance/report.blade.php ENDPATH**/ ?>