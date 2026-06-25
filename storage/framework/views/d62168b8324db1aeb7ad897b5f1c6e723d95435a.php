<?php $__env->startSection('title','Riwayat Absensi'); ?>
<?php $__env->startSection('page-title','Riwayat Absensi'); ?>

<?php $__env->startSection('content'); ?>


<div class="dashboard-card mb-4 fade-up" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div class="row align-items-center g-3" style="position:relative">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-3">
                <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0"><i class="bi bi-clipboard2-pulse"></i></div>
                <div>
                    <h5 class="fw-bold mb-0" style="color:white">Riwayat Absensi</h5>
                    <span style="font-size:12px;opacity:.8">Rekap absensi seluruh guru dan mata pelajaran</span>
                </div>
            </div>
        </div>
    </div>
</div>


<?php if($summary): ?>
<div class="row g-3 mb-4">
    <?php
    $cards = [
        ['label'=>'Total Catatan','value'=>$summary->total,'icon'=>'bi-list-check','color'=>'var(--bs-primary)','bg'=>'var(--soft-primary)'],
        ['label'=>'Hadir','value'=>$summary->hadir,'icon'=>'bi-check-circle','color'=>'#10b981','bg'=>'var(--soft-success)'],
        ['label'=>'Tidak Hadir','value'=>$summary->tidak_hadir,'icon'=>'bi-x-circle','color'=>'#ef4444','bg'=>'var(--soft-danger)'],
        ['label'=>'Izin','value'=>$summary->izin,'icon'=>'bi-clipboard-check','color'=>'#3b82f6','bg'=>'var(--soft-info)'],
        ['label'=>'Sakit','value'=>$summary->sakit,'icon'=>'bi-heart-pulse','color'=>'#8b5cf6','bg'=>'var(--soft-primary)'],
        ['label'=>'Menunggu Konfirmasi','value'=>$summary->menunggu,'icon'=>'bi-hourglass-split','color'=>'#f59e0b','bg'=>'var(--soft-warning)'],
    ];
    ?>
    <?php $__currentLoopData = $cards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="dashboard-card text-center py-3 fade-up">
            <div style="width:40px;height:40px;border-radius:12px;background:<?php echo e($c['bg']); ?>;display:flex;align-items:center;justify-content:center;margin:0 auto 8px;font-size:18px;color:<?php echo e($c['color']); ?>"><i class="bi <?php echo e($c['icon']); ?>"></i></div>
            <div class="fw-bold" style="font-size:20px"><?php echo e(number_format($c['value'])); ?></div>
            <div class="text-muted" style="font-size:11px"><?php echo e($c['label']); ?></div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php endif; ?>


<div class="dashboard-card mb-4 fade-up">
    <form method="GET" action="<?php echo e(route('admin.attendance-history.index')); ?>" class="row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label small fw-semibold mb-1">Mata Pelajaran</label>
            <select name="course_id" class="form-select form-select-sm">
                <option value="">Semua Mata Pelajaran</option>
                <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($c->id); ?>" <?php echo e(request('course_id') == $c->id ? 'selected' : ''); ?>><?php echo e($c->nama); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label small fw-semibold mb-1">Guru</label>
            <select name="teacher_id" class="form-select form-select-sm">
                <option value="">Semua Guru</option>
                <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($t->id); ?>" <?php echo e(request('teacher_id') == $t->id ? 'selected' : ''); ?>><?php echo e($t->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label small fw-semibold mb-1">Status</label>
            <select name="status" class="form-select form-select-sm">
                <option value="">Semua Status</option>
                <option value="hadir" <?php echo e(request('status')=='hadir' ? 'selected' : ''); ?>>Hadir</option>
                <option value="menunggu_konfirmasi" <?php echo e(request('status')=='menunggu_konfirmasi' ? 'selected' : ''); ?>>Menunggu Konfirmasi</option>
                <option value="tidak_hadir" <?php echo e(request('status')=='tidak_hadir' ? 'selected' : ''); ?>>Tidak Hadir</option>
                <option value="izin" <?php echo e(request('status')=='izin' ? 'selected' : ''); ?>>Izin</option>
                <option value="sakit" <?php echo e(request('status')=='sakit' ? 'selected' : ''); ?>>Sakit</option>
                <option value="alpa" <?php echo e(request('status')=='alpa' ? 'selected' : ''); ?>>Alpa</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label small fw-semibold mb-1">Dari Tanggal</label>
            <input type="date" name="tanggal_from" class="form-control form-control-sm" value="<?php echo e(request('tanggal_from')); ?>">
        </div>
        <div class="col-md-2">
            <label class="form-label small fw-semibold mb-1">Sampai Tanggal</label>
            <input type="date" name="tanggal_to" class="form-control form-control-sm" value="<?php echo e(request('tanggal_to')); ?>">
        </div>
        <div class="col-12 d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-sm px-4"><i class="bi bi-funnel me-1"></i>Filter</button>
            <a href="<?php echo e(route('admin.attendance-history.index')); ?>" class="btn btn-light btn-sm px-3"><i class="bi bi-x me-1"></i>Reset</a>
        </div>
    </form>
</div>


<div class="dashboard-card fade-up">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h6 class="fw-bold mb-0">Data Absensi</h6>
        <span class="badge bg-secondary"><?php echo e($query->total()); ?> data</span>
    </div>

    <?php if($query->isEmpty()): ?>
    <div class="text-center py-5">
        <i class="bi bi-clipboard2-x" style="font-size:3rem;color:#cbd5e1"></i>
        <div class="text-muted mt-2">Belum ada data absensi</div>
    </div>
    <?php else: ?>
    <div class="table-responsive">
        <table class="table table-hover align-middle" style="font-size:13px">
            <thead style="background:var(--input-bg)">
                <tr>
                    <th style="color:var(--text-muted);font-size:11px;text-transform:uppercase;letter-spacing:.04em">Siswa</th>
                    <th style="color:var(--text-muted);font-size:11px;text-transform:uppercase;letter-spacing:.04em">Mata Pelajaran</th>
                    <th style="color:var(--text-muted);font-size:11px;text-transform:uppercase;letter-spacing:.04em">Kelas</th>
                    <th style="color:var(--text-muted);font-size:11px;text-transform:uppercase;letter-spacing:.04em">Guru</th>
                    <th style="color:var(--text-muted);font-size:11px;text-transform:uppercase;letter-spacing:.04em">Tanggal &amp; Waktu</th>
                    <th class="text-center" style="color:var(--text-muted);font-size:11px;text-transform:uppercase;letter-spacing:.04em">Pertemuan</th>
                    <th class="text-center" style="color:var(--text-muted);font-size:11px;text-transform:uppercase;letter-spacing:.04em">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $query; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                $statusMap = [
                    'hadir'                => ['label'=>'Hadir',                'class'=>'bg-success'],
                    'menunggu_konfirmasi'  => ['label'=>'Menunggu Konfirmasi',  'class'=>'bg-warning text-dark'],
                    'tidak_hadir'          => ['label'=>'Tidak Hadir',          'class'=>'bg-danger'],
                    'tidak_valid'          => ['label'=>'Tidak Valid',           'class'=>'bg-secondary'],
                    'izin'                 => ['label'=>'Izin',                 'class'=>'bg-info text-dark'],
                    'sakit'                => ['label'=>'Sakit',                'class'=>'bg-primary'],
                    'alpa'                 => ['label'=>'Alpa',                 'class'=>'bg-dark'],
                ];
                $s = $statusMap[$row->status] ?? ['label'=>ucfirst($row->status),'class'=>'bg-secondary'];
                ?>
                <tr>
                    <td>
                        <div class="fw-semibold"><?php echo e($row->siswa_name); ?></div>
                        <?php if($row->nis): ?><div class="text-muted" style="font-size:11px">NIS: <?php echo e($row->nis); ?></div><?php endif; ?>
                    </td>
                    <td><?php echo e($row->mata_pelajaran); ?></td>
                    <td><?php echo e($row->nama_kelas); ?></td>
                    <td><?php echo e($row->guru_name ?? '–'); ?></td>
                    <td>
                        <div><?php echo e(\Carbon\Carbon::parse($row->tanggal)->locale('id')->isoFormat('D MMM Y')); ?></div>
                        <div class="text-muted" style="font-size:11px">
                            <?php echo e(\Carbon\Carbon::parse($row->jam_mulai)->format('H:i')); ?> – <?php echo e(\Carbon\Carbon::parse($row->jam_selesai)->format('H:i')); ?> WIB
                        </div>
                    </td>
                    <td class="text-center fw-semibold">#<?php echo e($row->pertemuan_ke); ?></td>
                    <td class="text-center">
                        <span class="badge <?php echo e($s['class']); ?>" style="font-size:11px"><?php echo e($s['label']); ?></span>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>

    
    <div class="d-flex justify-content-center mt-3">
        <?php echo e($query->links()); ?>

    </div>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/admin/attendance-history/index.blade.php ENDPATH**/ ?>