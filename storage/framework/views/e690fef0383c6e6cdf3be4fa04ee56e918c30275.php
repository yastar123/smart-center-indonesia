<?php $__env->startSection('title', 'Jadwal Kelas'); ?>
<?php $__env->startSection('page-title', 'Jadwal Kelas'); ?>

<?php $__env->startSection('content'); ?>
<div>


<div class="dashboard-card mb-4 fade-up" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
    <div class="row align-items-center g-3" style="position:relative">
        <div class="col-md-7">
            <div class="d-flex align-items-center gap-3 mb-1">
                <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0">
                    <i class="bi bi-calendar-week"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0" style="color:white">Jadwal Kelas</h5>
                    <span style="font-size:12px;opacity:.8">Kelola dan pantau seluruh sesi kelas cabang hari ini.</span>
                </div>
            </div>
        </div>
        <div class="col-md-5 text-md-end d-flex justify-content-md-end gap-2 flex-wrap">
            <a href="<?php echo e(route('admin.schedule-list.index')); ?>" class="btn fw-semibold px-3" style="background:rgba(255,255,255,.15);color:white;border:1px solid rgba(255,255,255,.25);border-radius:10px;font-size:13px">
                <i class="bi bi-list-ul me-1"></i>Kelola Daftar Kelas
            </a>
            <a href="<?php echo e(route('admin.schedule-create.index')); ?>" class="btn fw-semibold px-3" style="background:rgba(255,255,255,.2);color:white;border:1px solid rgba(255,255,255,.3);border-radius:10px;font-size:13px">
                <i class="bi bi-plus-lg me-1"></i>Buat Jadwal Baru
            </a>
        </div>
    </div>
</div>


<div class="dashboard-card mb-4 fade-up">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div class="d-flex gap-2 flex-wrap" id="dayStrip">
            <?php $__currentLoopData = $weekDays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('admin.schedule-dashboard.index', ['date' => $wd['date']])); ?>"
               class="text-decoration-none"
               style="flex-shrink:0">
                <div style="width:64px;text-align:center;padding:8px 4px;border-radius:12px;cursor:pointer;transition:.2s;
                    <?php echo e($wd['active'] ? 'background:linear-gradient(135deg,#461256,#c84ddf);color:white;box-shadow:0 4px 12px rgba(200,77,223,.4)' : 'background:var(--input-bg);color:var(--text-color)'); ?>">
                    <div style="font-size:10px;font-weight:600;text-transform:uppercase;opacity:<?php echo e($wd['active'] ? 1 : .6); ?>"><?php echo e($wd['day']); ?></div>
                    <div style="font-size:20px;font-weight:700;line-height:1.2"><?php echo e($wd['num']); ?></div>
                    <?php if($wd['count'] > 0): ?>
                        <div style="font-size:10px;opacity:.8"><?php echo e($wd['count']); ?> sesi</div>
                    <?php else: ?>
                        <div style="font-size:10px;opacity:.4">—</div>
                    <?php endif; ?>
                </div>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <div class="d-flex align-items-center gap-2">
            <label class="fw-semibold text-muted" style="font-size:13px;white-space:nowrap">Pilih Tanggal:</label>
            <form method="GET" id="dateForm">
                <input type="date" name="date" class="form-control form-control-sm"
                       value="<?php echo e($date->format('Y-m-d')); ?>"
                       onchange="this.form.submit()"
                       style="width:150px">
            </form>
        </div>
    </div>
</div>


<div class="row g-3 mb-4">
    <?php
        $qStats = [
            ['label'=>'Total Sesi',  'value'=>$stats['total'],     'icon'=>'bi-calendar-check',  'color'=>'#c84ddf', 'bg'=>'bg-primary-soft'],
            ['label'=>'Berlangsung', 'value'=>$stats['ongoing'],   'icon'=>'bi-play-circle-fill', 'color'=>'#10b981', 'bg'=>'bg-success-soft'],
            ['label'=>'Menunggu',    'value'=>$stats['scheduled'], 'icon'=>'bi-hourglass-split',  'color'=>'#f6af23', 'bg'=>'bg-warning-soft'],
            ['label'=>'Selesai',     'value'=>$stats['completed'], 'icon'=>'bi-check-circle-fill','color'=>'#6b7280', 'bg'=>'bg-muted-bg'],
        ];
    ?>
    <?php $__currentLoopData = $qStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $qs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:<?php echo e($i * 0.05); ?>s">
        <div class="stat-card" style="border-top:3px solid <?php echo e($qs['color']); ?>">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title"><?php echo e($qs['label']); ?></div>
                    <div class="stat-value" style="color:<?php echo e($qs['color']); ?>" data-auto-count="<?php echo e($qs['value']); ?>"><?php echo e($qs['value']); ?></div>
                </div>
                <div class="stat-icon <?php echo e($qs['bg']); ?>" style="color:white"><i class="bi <?php echo e($qs['icon']); ?>"></i></div>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>


<?php if(count($schedules) === 0): ?>
<div class="dashboard-card text-center py-5 fade-up">
    <i class="bi bi-calendar-x" style="font-size:48px;color:var(--text-muted)"></i>
    <h6 class="mt-3 fw-bold">Tidak ada sesi kelas pada tanggal ini</h6>
    <p class="text-muted" style="font-size:13px"><?php echo e($date->isoFormat('dddd, D MMMM Y')); ?></p>
    <a href="<?php echo e(route('admin.schedule-create.index')); ?>" class="btn btn-primary mt-2">
        <i class="bi bi-plus-lg me-2"></i>Buat Jadwal Baru
    </a>
</div>
<?php else: ?>
<div class="row g-3">
    <?php $__currentLoopData = $schedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php
        $barColor   = match($sc['status']) { 'ongoing' => '#10b981', 'scheduled' => '#c84ddf', default => '#9ca3af' };
        $badgeStyle = match($sc['status']) { 'ongoing' => 'background:rgba(16,185,129,.15);color:#059669', 'scheduled' => 'background:rgba(200,77,223,.15);color:#8b1dc5', default => 'background:rgba(107,114,128,.15);color:#6b7280' };
        $badgeLabel = match($sc['status']) { 'ongoing' => 'Sedang Berlangsung', 'scheduled' => 'Dijadwalkan', default => 'Selesai' };
        $timeStyle  = match($sc['status']) { 'ongoing' => 'color:#10b981', 'completed' => 'color:#9ca3af', default => 'color:var(--text-color)' };

        $parts    = preg_split('/[\s]+/', trim($sc['teacher_name']));
        $tInitials = strtoupper(
            (isset($parts[0][0]) ? $parts[0][0] : '') .
            (isset($parts[1][0]) ? $parts[1][0] : (isset($parts[0][1]) ? $parts[0][1] : ''))
        );

        [$cur, $cap] = explode('/', $sc['students_count']);
        $cur = (int)$cur; $cap = (int)$cap;
    ?>
    <div class="col-md-6 col-xl-4 fade-up">
        <div class="dashboard-card h-100" style="padding:0;overflow:hidden;border-top:4px solid <?php echo e($barColor); ?>">
            <div style="padding:16px">

                
                <div class="d-flex justify-content-between align-items-start gap-2 mb-3">
                    <div style="min-width:0">
                        <h6 class="fw-bold mb-0 text-truncate" style="font-size:14px"><?php echo e($sc['class_name']); ?></h6>
                        <div class="text-muted text-truncate" style="font-size:12px"><?php echo e($sc['subject_name']); ?></div>
                    </div>
                    <span class="badge flex-shrink-0" style="<?php echo e($badgeStyle); ?>;font-size:10px;padding:5px 9px;border-radius:8px"><?php echo e($badgeLabel); ?></span>
                </div>

                
                <div class="d-flex align-items-start gap-3 mb-3 p-2 rounded-3" style="background:var(--input-bg)">
                    <div style="width:38px;height:38px;border-radius:10px;background:linear-gradient(135deg,#461256,#c84ddf);color:white;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;flex-shrink:0;letter-spacing:.5px">
                        <?php echo e($tInitials ?: '?'); ?>

                    </div>
                    <div style="flex:1;min-width:0">
                        <div class="fw-semibold text-truncate" style="font-size:13px"><?php echo e($sc['teacher_name']); ?></div>
                        <div class="text-muted text-truncate" style="font-size:11px">
                            <i class="bi bi-geo-alt-fill me-1"></i><?php echo e($sc['room_name']); ?>

                        </div>
                        <div class="fw-bold mt-1" style="font-size:13px;<?php echo e($timeStyle); ?>">
                            <i class="bi bi-clock me-1"></i><?php echo e(substr($sc['jam_mulai'],0,5)); ?> – <?php echo e(substr($sc['jam_selesai'],0,5)); ?>

                        </div>
                    </div>
                </div>

                
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-1">
                        <?php if($cur > 0): ?>
                        <div style="width:28px;height:28px;border-radius:50%;background:var(--soft-primary-bg);border:2px solid var(--card-bg);display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;color:var(--soft-primary-text)">
                            +<?php echo e($cur); ?>

                        </div>
                        <?php endif; ?>
                        <span class="text-muted" style="font-size:11px"><?php echo e($cur); ?>/<?php echo e($cap); ?> murid</span>
                    </div>
                    <div class="d-flex gap-1 flex-wrap justify-content-end">
                        <?php if($sc['status'] === 'ongoing'): ?>
                            <a href="<?php echo e(route('admin.schedules.show', $sc['schedule_id'])); ?>"
                               class="btn btn-sm btn-success fw-semibold" style="font-size:11px;border-radius:8px">
                                <i class="bi bi-eye me-1"></i>Pantau Kelas
                            </a>
                        <?php elseif($sc['status'] === 'completed'): ?>
                            <a href="<?php echo e(route('admin.schedules.show', $sc['schedule_id'])); ?>"
                               class="btn btn-sm btn-outline-secondary" style="font-size:11px;border-radius:8px">
                                <i class="bi bi-file-text me-1"></i>Lihat Laporan Sesi
                            </a>
                        <?php else: ?>
                            <a href="<?php echo e(route('admin.schedules.edit', $sc['schedule_id'])); ?>"
                               class="btn btn-sm btn-outline-primary" style="font-size:11px;border-radius:8px">
                                <i class="bi bi-pencil me-1"></i>Edit
                            </a>
                            <a href="<?php echo e(route('admin.schedules.show', $sc['schedule_id'])); ?>"
                               class="btn btn-sm btn-primary fw-semibold" style="font-size:11px;border-radius:8px">
                                <i class="bi bi-info-circle me-1"></i>Detail Kelas
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php endif; ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/admin/schedule-dashboard/index.blade.php ENDPATH**/ ?>