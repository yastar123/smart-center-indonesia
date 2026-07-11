<?php $__env->startSection('title', 'Daftar Kelas Aktif'); ?>
<?php $__env->startSection('page-title', 'Daftar Kelas Aktif'); ?>

<?php $__env->startSection('content'); ?>
<div>


<div class="dashboard-card mb-4 fade-up" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
    <div class="row align-items-center g-3" style="position:relative">
        <div class="col-md-7">
            <div class="d-flex align-items-center gap-3 mb-1">
                <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0">
                    <i class="bi bi-grid-3x3-gap-fill"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0" style="color:white">Daftar Kelas Aktif</h5>
                    <span style="font-size:12px;opacity:.8">Manajemen Kelas — Kelola semua kelas aktif: setup guru, ruangan, jadwal, dan daftar murid.</span>
                </div>
            </div>
        </div>
        <div class="col-md-5 text-md-end d-flex justify-content-md-end gap-2 flex-wrap">
            <a href="<?php echo e(route('admin.schedule-dashboard.index')); ?>" class="btn fw-semibold px-3" style="background:rgba(255,255,255,.15);color:white;border:1px solid rgba(255,255,255,.25);border-radius:10px;font-size:13px">
                <i class="bi bi-calendar-week me-1"></i>Tampilan Kalender
            </a>
            <a href="<?php echo e(route('admin.schedule-create.index')); ?>" class="btn fw-semibold px-3" style="background:rgba(255,255,255,.2);color:white;border:1px solid rgba(255,255,255,.3);border-radius:10px;font-size:13px">
                <i class="bi bi-plus-lg me-1"></i>Tambah Kelas Baru
            </a>
        </div>
    </div>
</div>


<div class="row g-3 mb-4">
    <?php
        $statCards = [
            ['label'=>'Total Kelas',  'value'=>$stats['total'],       'icon'=>'bi-collection-fill',   'color'=>'#c84ddf', 'bg'=>'bg-primary-soft'],
            ['label'=>'Aktif',        'value'=>$stats['aktif'],       'icon'=>'bi-play-circle-fill',  'color'=>'#10b981', 'bg'=>'bg-success-soft'],
            ['label'=>'Penuh',        'value'=>$stats['penuh'],       'icon'=>'bi-people-fill',        'color'=>'#f6af23', 'bg'=>'bg-warning-soft'],
            ['label'=>'Total Murid',  'value'=>$stats['total_murid'], 'icon'=>'bi-person-fill',        'color'=>'#0284c7', 'bg'=>'bg-info-soft'],
        ];
    ?>
    <?php $__currentLoopData = $statCards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $sc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:<?php echo e($i * 0.05); ?>s">
        <div class="stat-card" style="border-top:3px solid <?php echo e($sc['color']); ?>">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title"><?php echo e($sc['label']); ?></div>
                    <div class="stat-value" style="color:<?php echo e($sc['color']); ?>" data-auto-count="<?php echo e($sc['value']); ?>"><?php echo e($sc['value']); ?></div>
                </div>
                <div class="stat-icon <?php echo e($sc['bg']); ?>" style="color:white"><i class="bi <?php echo e($sc['icon']); ?>"></i></div>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>


<div class="dashboard-card mb-4 fade-up">
    <form id="filterForm" method="GET" action="<?php echo e(route('admin.schedule-list.index')); ?>">
        <div class="row g-2 align-items-end">
            <div class="col-12 col-md-5">
                <label class="form-label fw-semibold" style="font-size:12px">Cari nama kelas, guru, atau kode...</label>
                <div class="input-group">
                    <span class="input-group-text" style="border-radius:10px 0 0 10px;background:var(--input-bg);border-color:var(--card-border)">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                        placeholder="Cari nama kelas, guru, atau kode..."
                        class="form-control"
                        style="border-radius:0 10px 10px 0;border-color:var(--card-border);background:var(--input-bg)"
                        onchange="document.getElementById('filterForm').submit()">
                </div>
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label fw-semibold" style="font-size:12px">Semua Status</label>
                <select name="status_filter" class="form-select" style="border-radius:10px"
                    onchange="document.getElementById('filterForm').submit()">
                    <option value="">Semua Status</option>
                    <option value="aktif"  <?php echo e(request('status_filter')=='aktif'  ? 'selected' : ''); ?>>Aktif</option>
                    <option value="penuh"  <?php echo e(request('status_filter')=='penuh'  ? 'selected' : ''); ?>>Penuh</option>
                    <option value="draft"  <?php echo e(request('status_filter')=='draft'  ? 'selected' : ''); ?>>Draft</option>
                </select>
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label fw-semibold" style="font-size:12px">Semua Mapel</label>
                <select name="mata_pelajaran_id" class="form-select" style="border-radius:10px"
                    onchange="document.getElementById('filterForm').submit()">
                    <option value="">Semua Mapel</option>
                    <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($c->id); ?>" <?php echo e(request('mata_pelajaran_id')==$c->id ? 'selected' : ''); ?>><?php echo e($c->nama); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-12 col-md-1 d-flex align-items-end">
                <?php if(request()->hasAny(['search','status_filter','mata_pelajaran_id'])): ?>
                <a href="<?php echo e(route('admin.schedule-list.index')); ?>" class="btn btn-outline-secondary w-100" style="border-radius:10px" title="Reset filter">
                    <i class="bi bi-x-lg"></i>
                </a>
                <?php endif; ?>
            </div>
        </div>
    </form>
</div>


<div class="dashboard-card fade-up">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold mb-0">
            <i class="bi bi-table text-primary me-2"></i>Daftar Kelas
            <span class="badge ms-2" style="background:var(--soft-primary-bg);color:var(--soft-primary-text);font-size:11px"><?php echo e($classes->total()); ?> kelas</span>
        </h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover table-modern align-middle mb-0" style="min-width:900px">
            <thead class="thead-modern">
                <tr>
                    <th class="ps-3" style="min-width:220px">Kelas &amp; Paket</th>
                    <th style="min-width:160px">Guru</th>
                    <th style="min-width:180px">Ruangan &amp; Jadwal</th>
                    <th style="min-width:140px">Detail Sesi</th>
                    <th style="min-width:140px">Kapasitas Murid</th>
                    <th style="min-width:90px">Status</th>
                    <th class="text-center" style="min-width:120px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kelas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $pctBar = min($kelas->_pct, 100);
                    $barClr = $pctBar >= 100 ? '#ef4444' : ($pctBar >= 75 ? '#f6af23' : '#10b981');

                    $statusBadge = match($kelas->_computed_status) {
                        'penuh'  => ['bg'=>'var(--soft-danger-bg)','color'=>'var(--soft-danger-text)','label'=>'Penuh'],
                        'draft'  => ['bg'=>'var(--soft-muted-bg)', 'color'=>'var(--soft-muted-text)', 'label'=>'Draft'],
                        default  => ['bg'=>'var(--soft-success-bg)','color'=>'var(--soft-success-text)','label'=>'Aktif'],
                    };

                    $avatarBg = match(true) {
                        str_contains(strtolower($kelas->mataPelajaran?->nama ?? ''), 'mat')  => 'linear-gradient(135deg,#6366f1,#8b5cf6)',
                        str_contains(strtolower($kelas->mataPelajaran?->nama ?? ''), 'fis')  => 'linear-gradient(135deg,#0284c7,#38bdf8)',
                        str_contains(strtolower($kelas->mataPelajaran?->nama ?? ''), 'kim')  => 'linear-gradient(135deg,#10b981,#34d399)',
                        str_contains(strtolower($kelas->mataPelajaran?->nama ?? ''), 'ing')  => 'linear-gradient(135deg,#f59e0b,#fcd34d)',
                        str_contains(strtolower($kelas->mataPelajaran?->nama ?? ''), 'bio')  => 'linear-gradient(135deg,#059669,#10b981)',
                        default => 'linear-gradient(135deg,#461256,#c84ddf)',
                    };
                ?>
                <tr style="border-bottom:1px solid var(--card-border);vertical-align:middle">
                    
                    <td class="ps-3">
                        <div class="d-flex align-items-start gap-3">
                            <div style="width:42px;height:42px;border-radius:12px;background:<?php echo e($avatarBg); ?>;color:white;display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:700;flex-shrink:0">
                                <?php echo e($kelas->_initials); ?>

                            </div>
                            <div style="min-width:0">
                                <div class="fw-bold text-truncate" style="font-size:13px;max-width:160px"><?php echo e($kelas->nama_kelas); ?></div>
                                <div class="mt-1">
                                    <span class="badge" style="background:<?php echo e($kelas->_jenis_color); ?>;font-size:10px;font-weight:600;border-radius:6px"><?php echo e($kelas->_jenis_label); ?></span>
                                </div>
                                <div class="text-muted mt-1" style="font-size:11px;max-width:160px">
                                    <span class="fw-semibold" style="color:var(--text-muted)"><?php echo e($kelas->_code); ?></span>
                                    <?php if($kelas->_paket): ?>
                                    · <?php echo e($kelas->_paket->nama); ?>

                                    <?php if($kelas->_total): ?> (<?php echo e($kelas->_total); ?> Sesi) <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </td>

                    
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#461256,#c84ddf);color:white;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;flex-shrink:0">
                                <?php echo e($kelas->_teacher_initials ?: '?'); ?>

                            </div>
                            <div style="min-width:0">
                                <div class="fw-semibold text-truncate" style="font-size:12px;max-width:120px"><?php echo e($kelas->_teacher_name); ?></div>
                                <?php if($kelas->guru?->subjects): ?>
                                <div class="text-muted text-truncate" style="font-size:11px;max-width:120px"><?php echo e(is_array($kelas->guru->subjects) ? implode(', ', array_slice($kelas->guru->subjects, 0, 2)) : ''); ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>

                    
                    <td>
                        <div style="font-size:12px">
                            <div class="fw-semibold mb-1">
                                <i class="bi bi-geo-alt-fill text-muted me-1"></i>
                                <span class="text-truncate" style="max-width:140px;display:inline-block;vertical-align:middle"><?php echo e($kelas->_room); ?></span>
                            </div>
                            <?php if($kelas->_days): ?>
                            <div class="text-muted mb-1"><i class="bi bi-calendar3 me-1"></i><?php echo e($kelas->_days); ?></div>
                            <?php endif; ?>
                            <?php if($kelas->_time !== '—'): ?>
                            <div class="text-muted"><i class="bi bi-clock me-1"></i><?php echo e($kelas->_time); ?></div>
                            <?php endif; ?>
                        </div>
                    </td>

                    
                    <td>
                        <div class="fw-semibold" style="font-size:13px;color:var(--text-color)">
                            <?php echo e($kelas->_done); ?> dari <?php echo e($kelas->_total ?: '?'); ?> Sesi
                        </div>
                        <?php if($kelas->_total > 0): ?>
                        <div class="mt-2" style="height:6px;background:var(--card-border);border-radius:99px;width:100px;overflow:hidden">
                            <?php $sessPct = $kelas->_total > 0 ? round($kelas->_done / $kelas->_total * 100) : 0; ?>
                            <div style="height:100%;width:<?php echo e($sessPct); ?>%;background:linear-gradient(90deg,#461256,#c84ddf);border-radius:99px;transition:width .4s"></div>
                        </div>
                        <div class="text-muted mt-1" style="font-size:10px"><?php echo e($sessPct ?? 0); ?>% selesai</div>
                        <?php endif; ?>
                    </td>

                    
                    <td>
                        <div style="font-size:13px">
                            <span class="fw-bold" style="font-size:16px"><?php echo e($kelas->_enrolled); ?></span>
                            <span class="text-muted" style="font-size:12px"> murid</span>
                        </div>
                        <?php if($kelas->_capacity > 0): ?>
                        <div class="text-muted" style="font-size:11px">/ <?php echo e($kelas->_capacity); ?> kapasitas</div>
                        <div class="mt-2" style="height:6px;background:var(--card-border);border-radius:99px;width:100px;overflow:hidden">
                            <div style="height:100%;width:<?php echo e($pctBar); ?>%;background:<?php echo e($barClr); ?>;border-radius:99px;transition:width .4s"></div>
                        </div>
                        <div class="text-muted mt-1" style="font-size:10px"><?php echo e($kelas->_pct); ?>% terisi</div>
                        <?php endif; ?>
                    </td>

                    
                    <td>
                        <span style="background:<?php echo e($statusBadge['bg']); ?>;color:<?php echo e($statusBadge['color']); ?>;padding:5px 10px;border-radius:8px;font-size:11px;font-weight:600;white-space:nowrap">
                            <?php echo e($statusBadge['label']); ?>

                        </span>
                    </td>

                    
                    <td class="text-center">
                        <button class="btn btn-sm btn-primary fw-semibold" style="font-size:11px;border-radius:8px;white-space:nowrap"
                            onclick="addStudentToClass(<?php echo e($kelas->id); ?>, '<?php echo e(addslashes($kelas->nama_kelas)); ?>')">
                            <i class="bi bi-person-plus me-1"></i>Tambah Murid
                        </button>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <div>
                            <i class="bi bi-collection" style="font-size:48px;color:var(--text-muted);opacity:.4;display:block;margin-bottom:12px"></i>
                            <div class="fw-semibold mb-1">Belum ada kelas</div>
                            <div class="text-muted" style="font-size:13px">Mulai dengan menambah kelas baru</div>
                            <a href="<?php echo e(route('admin.schedule-create.index')); ?>" class="btn btn-primary btn-sm mt-3">
                                <i class="bi bi-plus-lg me-1"></i>Tambah Kelas Baru
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if($classes->hasPages()): ?>
    <div class="mt-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="text-muted" style="font-size:13px">
            Menampilkan <?php echo e($classes->firstItem()); ?> sampai <?php echo e($classes->lastItem()); ?> dari <?php echo e($classes->total()); ?> kelas
        </div>
        <div><?php echo e($classes->links()); ?></div>
    </div>
    <?php else: ?>
    <div class="mt-3 text-muted" style="font-size:13px">
        Menampilkan 1 sampai <?php echo e($classes->count()); ?> dari <?php echo e($classes->total()); ?> kelas
    </div>
    <?php endif; ?>
</div>


<div class="modal fade" id="addStudentModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:18px;overflow:hidden">
            <div class="modal-header border-0 p-4" style="background:linear-gradient(135deg,#461256,#68117e);color:#fff">
                <h6 class="modal-title fw-bold"><i class="bi bi-person-plus me-2"></i>Tambah Murid ke Kelas</h6>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <p class="text-muted mb-1" style="font-size:13px">Kelas yang dipilih:</p>
                <div class="fw-bold mb-3" id="addStudentClassName" style="font-size:15px"></div>
                <p class="text-muted" style="font-size:13px">Fitur tambah murid ke kelas ini sedang dalam pengembangan. Silakan gunakan menu <strong>Manajemen Siswa</strong> untuk mendaftarkan siswa ke paket.</p>
            </div>
            <div class="modal-footer border-0 p-3">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal" style="border-radius:10px">Tutup</button>
                <a href="<?php echo e(route('admin.students.index')); ?>" class="btn btn-primary px-4 fw-semibold" style="border-radius:10px">
                    <i class="bi bi-people me-1"></i>Ke Manajemen Siswa
                </a>
            </div>
        </div>
    </div>
</div>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function addStudentToClass(classId, className) {
    document.getElementById('addStudentClassName').textContent = className;
    new bootstrap.Modal(document.getElementById('addStudentModal')).show();
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/admin/schedule-list/index.blade.php ENDPATH**/ ?>