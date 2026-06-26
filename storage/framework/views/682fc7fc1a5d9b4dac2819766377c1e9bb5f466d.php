<?php $__env->startSection('title','Riwayat — '.$course->nama); ?>
<?php $__env->startSection('page-title','Riwayat Absensi'); ?>

<?php $__env->startSection('content'); ?>

<div class="dashboard-card mb-4 fade-up"
     style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3" style="position:relative">
        <div class="d-flex align-items-center gap-3">
            <a href="<?php echo e(route('siswa.attendance')); ?>"
               class="btn btn-sm flex-shrink-0"
               style="background:rgba(255,255,255,.15);color:white;border:1px solid rgba(255,255,255,.25);border-radius:9px;padding:6px 12px">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
            <div>
                <div style="font-size:11px;opacity:.6;margin-bottom:2px;text-transform:uppercase;letter-spacing:.08em">Riwayat Absensi</div>
                <h5 class="fw-bold mb-0" style="color:white"><?php echo e($course->nama); ?></h5>
                <div style="font-size:12px;opacity:.7;margin-top:2px">Detail kehadiran per pertemuan</div>
            </div>
        </div>
        <div style="font-size:64px;opacity:.08;line-height:1;flex-shrink:0">
            <i class="bi bi-clipboard2-check"></i>
        </div>
    </div>
</div>


<div class="dashboard-card mb-4 fade-up" style="border-left:4px solid #c84ddf">
    <div class="fw-semibold mb-2" style="font-size:13px;color:var(--text-primary)">
        <i class="bi bi-info-circle text-primary me-2"></i>Status Absensi
    </div>
    <div class="d-flex flex-wrap gap-2" style="font-size:12px">
        <span style="background:var(--soft-success-bg);color:#10b981;padding:3px 10px;border-radius:6px;font-weight:600">✅ Hadir</span>
        <span style="font-size:11px;color:var(--text-muted)">= Guru menandai + kamu konfirmasi</span>
        <span class="d-block d-sm-none w-100"></span>
        <span style="background:var(--soft-warning-bg);color:#f6af23;padding:3px 10px;border-radius:6px;font-weight:600">⏳ Menunggu Konfirmasimu</span>
        <span style="font-size:11px;color:var(--text-muted)">= Guru menandai hadir, kamu belum konfirmasi</span>
    </div>
</div>

<?php if($classes->isEmpty()): ?>
<div class="dashboard-card fade-up">
    <div class="empty-state">
        <div class="empty-state-icon"><i class="bi bi-clipboard-x"></i></div>
        <div class="empty-state-title">Tidak Ada Kelas Ditemukan</div>
        <div class="empty-state-desc">Belum ada kelas yang terdaftar untuk mata pelajaran ini.</div>
    </div>
</div>
<?php else: ?>
<?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="dashboard-card mb-4 fade-up">
    <h6 class="fw-bold mb-3"><?php echo e($class->nama_kelas); ?></h6>

    <?php if($class->jadwal->isEmpty()): ?>
    <div class="text-center py-4" style="color:var(--text-muted)">
        <i class="bi bi-calendar-x d-block mb-2" style="font-size:2rem;opacity:.3"></i>
        <div style="font-size:13px">Belum ada jadwal pertemuan.</div>
    </div>
    <?php else: ?>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="thead-modern">
                <tr>
                    <th>#</th>
                    <th>Pertemuan</th>
                    <th>Tanggal</th>
                    <th>Waktu</th>
                    <th>Status Absensi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $class->jadwal; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $j): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $rec     = $myAttendance[$j->id] ?? null;
                    $guruHadir    = $rec ? (bool)$rec->guru_hadir : false;
                    $siswaKonfirm = $rec ? $rec->siswa_konfirmasi_at : null;
                    $status       = $rec?->status ?? 'tidak_hadir';

                    $statusMap = [
                        'hadir'               => ['bg'=>'var(--soft-success-bg)','color'=>'#10b981','label'=>'Hadir','icon'=>'bi-check-circle-fill'],
                        'menunggu_konfirmasi' => ['bg'=>'var(--soft-warning-bg)','color'=>'#f6af23','label'=>'Menunggu Konfirmasimu','icon'=>'bi-hourglass-split'],
                        'tidak_valid'         => ['bg'=>'var(--soft-muted-bg)','color'=>'var(--text-muted)','label'=>'Tidak Valid','icon'=>'bi-x-circle'],
                        'tidak_hadir'         => ['bg'=>'var(--soft-danger-bg)','color'=>'#ef4444','label'=>'Tidak Hadir','icon'=>'bi-x-circle'],
                    ];
                    $sm = $statusMap[$status] ?? $statusMap['tidak_hadir'];

                    $canConfirm = $guruHadir && !$siswaKonfirm;
                ?>
                <tr>
                    <td><?php echo e($i + 1); ?></td>
                    <td class="fw-semibold">
                        <?php if($j->pertemuan_ke): ?>
                        <span style="background:var(--soft-primary-bg);color:var(--soft-primary-text);padding:2px 8px;border-radius:6px;font-size:12px;font-weight:700">Pertemuan #<?php echo e($j->pertemuan_ke); ?></span>
                        <?php else: ?>
                        —
                        <?php endif; ?>
                    </td>
                    <td><?php echo e($j->tanggal->locale('id')->isoFormat('D MMM Y')); ?></td>
                    <td><?php echo e(\Carbon\Carbon::parse($j->jam_mulai)->format('H:i')); ?> – <?php echo e(\Carbon\Carbon::parse($j->jam_selesai)->format('H:i')); ?></td>
                    <td>
                        <?php if($rec): ?>
                        <span class="badge" style="background:<?php echo e($sm['bg']); ?>;color:<?php echo e($sm['color']); ?>;font-size:11.5px;padding:4px 10px;border-radius:7px;font-weight:600">
                            <i class="bi <?php echo e($sm['icon']); ?> me-1"></i><?php echo e($sm['label']); ?>

                        </span>
                        <?php else: ?>
                        <span class="text-muted" style="font-size:12px">Guru belum mengisi</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($canConfirm): ?>
                        <button class="btn btn-success btn-sm confirm-btn" data-schedule="<?php echo e($j->id); ?>"
                                style="border-radius:8px;font-size:12px;font-weight:600">
                            <i class="bi bi-hand-thumbs-up me-1"></i>Konfirmasi Kehadiran
                        </button>
                        <?php elseif($siswaKonfirm): ?>
                        <span class="text-muted" style="font-size:12px">
                            <i class="bi bi-check2-all text-success me-1"></i>
                            Dikonfirmasi <?php echo e(\Carbon\Carbon::parse($siswaKonfirm)->locale('id')->diffForHumans()); ?>

                        </span>
                        <?php else: ?>
                        <span class="text-muted" style="font-size:12px">—</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.querySelectorAll('.confirm-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const scheduleId = this.dataset.schedule;
        const original   = this.innerHTML;
        this.disabled  = true;
        this.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Mengkonfirmasi...';

        fetch('/siswa/attendance/' + scheduleId + '/confirm', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                'X-Requested-With': 'XMLHttpRequest',
            }
        }).then(r => { if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
        .then(d => {
            if (d.success) {
                showToast(d.message || 'Kehadiran dikonfirmasi!', 'success');
                setTimeout(() => location.reload(), 700);
            } else {
                this.disabled  = false;
                this.innerHTML = original;
                showToast(d.message || 'Gagal konfirmasi', 'error');
            }
        }).catch(() => {
            this.disabled  = false;
            this.innerHTML = original;
            showToast('Terjadi kesalahan. Coba lagi.', 'error');
        });
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/siswa/attendance/show.blade.php ENDPATH**/ ?>