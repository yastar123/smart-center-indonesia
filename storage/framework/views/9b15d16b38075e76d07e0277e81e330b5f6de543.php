<?php $__env->startSection('title', 'Manajemen Absensi'); ?>
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
                    <h5 class="fw-bold mb-0" style="color:white">Sesi Absensi</h5>
                    <span style="font-size:12px;opacity:.8">
                        <?php if(request('paket_id') && ($pkt = \App\Models\Package::find(request('paket_id')))): ?>
                            Paket: <strong><?php echo e($pkt->nama); ?></strong>
                        <?php else: ?>
                            Semua sesi belajar — filter berdasarkan paket atau guru
                        <?php endif; ?>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="<?php echo e(route('admin.attendance.index')); ?>" class="btn fw-semibold px-4"
               style="background:rgba(255,255,255,.2);color:white;border:1px solid rgba(255,255,255,.3);border-radius:10px;backdrop-filter:blur(10px)">
                <i class="bi bi-arrow-left me-2"></i>Kembali ke Paket
            </a>
        </div>
    </div>
</div>


<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3 fade-up">
        <div class="stat-card" style="border-top:3px solid #c84ddf">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="stat-title">Total Sesi</div><div class="stat-value"><?php echo e($stats['total']); ?></div></div>
                <div class="stat-icon bg-primary-soft" style="color:white"><i class="bi bi-calendar-week"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.05s">
        <div class="stat-card" style="border-top:3px solid #10b981">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="stat-title">Sesi Selesai</div><div class="stat-value text-success"><?php echo e($stats['selesai']); ?></div></div>
                <div class="stat-icon bg-success-soft" style="color:white"><i class="bi bi-check-circle-fill"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.10s">
        <div class="stat-card" style="border-top:3px solid #0284c7">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="stat-title">Rekap Hadir</div><div class="stat-value text-primary"><?php echo e($stats['hadir']); ?></div></div>
                <div class="stat-icon bg-info-soft" style="color:white"><i class="bi bi-person-check-fill"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.15s">
        <div class="stat-card" style="border-top:3px solid #ef4444">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="stat-title">Rekap Alpa</div><div class="stat-value text-danger"><?php echo e($stats['alpa']); ?></div></div>
                <div class="stat-icon bg-danger-soft" style="color:white"><i class="bi bi-person-x-fill"></i></div>
            </div>
        </div>
    </div>
</div>


<div class="dashboard-card mb-4 fade-up">
    <form id="filterForm" method="GET" action="<?php echo e(route('admin.attendance.sessions')); ?>">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-semibold" style="font-size:12px">Paket</label>
                <select name="paket_id" class="form-select" style="border-radius:10px" onchange="document.getElementById('filterForm').submit()">
                    <option value="">Semua Paket</option>
                    <?php $__currentLoopData = $pakets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($p->id); ?>" <?php echo e(request('paket_id')==$p->id?'selected':''); ?>><?php echo e($p->nama); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold" style="font-size:12px">Guru</label>
                <select name="guru_id" class="form-select" style="border-radius:10px" onchange="document.getElementById('filterForm').submit()">
                    <option value="">Semua Guru</option>
                    <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($t->id); ?>" <?php echo e(request('guru_id')==$t->id?'selected':''); ?>><?php echo e($t->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold" style="font-size:12px">Status Sesi</label>
                <select name="status" class="form-select" style="border-radius:10px" onchange="document.getElementById('filterForm').submit()">
                    <option value="">Semua</option>
                    <option value="dijadwalkan" <?php echo e(request('status')=='dijadwalkan'?'selected':''); ?>>Dijadwalkan</option>
                    <option value="berlangsung" <?php echo e(request('status')=='berlangsung'?'selected':''); ?>>Berlangsung</option>
                    <option value="selesai"     <?php echo e(request('status')=='selesai'?'selected':''); ?>>Selesai</option>
                    <option value="dibatalkan"  <?php echo e(request('status')=='dibatalkan'?'selected':''); ?>>Dibatalkan</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold" style="font-size:12px">Tanggal</label>
                <input type="date" name="tanggal" value="<?php echo e(request('tanggal')); ?>" class="form-control"
                    style="border-radius:10px" onchange="document.getElementById('filterForm').submit()">
            </div>
            <div class="col-md-2">
                <?php if(request()->hasAny(['paket_id','guru_id','status','tanggal'])): ?>
                <a href="<?php echo e(route('admin.attendance.sessions')); ?>" class="btn btn-outline-secondary w-100" style="border-radius:10px">
                    <i class="bi bi-x-lg me-1"></i>Reset
                </a>
                <?php endif; ?>
            </div>
        </div>
    </form>
</div>


<div class="dashboard-card fade-up">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold mb-0">
            <i class="bi bi-list-check text-primary me-2"></i>Daftar Sesi Belajar
            <span class="badge ms-2" style="background:var(--soft-primary-bg);color:var(--soft-primary-text);font-size:11px"><?php echo e($query->total()); ?> data</span>
        </h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover table-modern align-middle mb-0">
            <thead class="thead-modern">
                <tr>
                    <th class="ps-3">Paket / Mata Pelajaran</th>
                    <th>Sesi</th>
                    <th>Tanggal &amp; Waktu</th>
                    <th>Guru</th>
                    <th>Absensi</th>
                    <th>Status</th>
                    <th class="text-center">Kelola</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $query; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $statusMap = [
                        'dijadwalkan' => ['bg'=>'var(--soft-primary-bg)','color'=>'var(--soft-primary-text)','label'=>'Dijadwalkan'],
                        'berlangsung' => ['bg'=>'var(--soft-warning-bg)','color'=>'var(--soft-warning-text)','label'=>'Berlangsung'],
                        'selesai'     => ['bg'=>'var(--soft-success-bg)','color'=>'var(--soft-success-text)','label'=>'Selesai'],
                        'dibatalkan'  => ['bg'=>'var(--soft-danger-bg)','color'=>'var(--soft-danger-text)','label'=>'Dibatalkan'],
                    ];
                    $st = $statusMap[$sc->status] ?? ['bg'=>'var(--soft-muted-bg)','color'=>'var(--soft-muted-text)','label'=>$sc->status];
                    $mapelNames = $sc->paket?->mataPelajaran->pluck('nama')->join(', ') ?? '–';
                    $absensiCount = $sc->absensi->count();
                    $hadirCount   = $sc->absensi->where('status', 'hadir')->count();
                    $alpaCount    = $sc->absensi->whereIn('status', ['alpa','tidak_hadir'])->count();
                    $isToday      = $sc->tanggal && $sc->tanggal->isToday();
                ?>
                <tr style="border-bottom:1px solid var(--card-border)">
                    <td class="ps-3">
                        <div class="fw-semibold" style="font-size:13px"><?php echo e($sc->paket?->nama ?? '–'); ?></div>
                        <div class="text-muted" style="font-size:11px"><i class="bi bi-journal-bookmark me-1"></i><?php echo e($mapelNames); ?></div>
                    </td>
                    <td>
                        <span style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:50%;background:var(--soft-primary-bg);color:var(--soft-primary-text);font-size:13px;font-weight:700">
                            <?php echo e($sc->pertemuan_ke ?? '–'); ?>

                        </span>
                    </td>
                    <td>
                        <div class="fw-semibold" style="font-size:13px">
                            <?php echo e($sc->tanggal ? $sc->tanggal->format('d M Y') : '–'); ?>

                            <?php if($isToday): ?><span class="badge ms-1" style="background:var(--soft-primary-bg);color:var(--soft-primary-text);font-size:10px">Hari ini</span><?php endif; ?>
                        </div>
                        <div class="text-muted" style="font-size:11px"><?php echo e(str_replace(':', '.', substr($sc->jam_mulai ?? '', 0, 5)) ?: '–'); ?> – <?php echo e(str_replace(':', '.', substr($sc->jam_selesai ?? '', 0, 5)) ?: '–'); ?> WIB</div>
                    </td>
                    <td>
                        <div style="font-size:12.5px"><?php echo e($sc->paket?->guru?->name ?? $sc->guru?->name ?? '–'); ?></div>
                    </td>
                    <td>
                        <?php if($absensiCount > 0): ?>
                        <div style="font-size:12px">
                            <span style="color:var(--soft-success-text)"><i class="bi bi-check-circle-fill me-1"></i><?php echo e($hadirCount); ?> hadir</span>
                            <?php if($alpaCount > 0): ?>
                            <span class="ms-2" style="color:var(--soft-danger-text)"><i class="bi bi-x-circle-fill me-1"></i><?php echo e($alpaCount); ?> alpa</span>
                            <?php endif; ?>
                        </div>
                        <div class="text-muted" style="font-size:11px"><?php echo e($absensiCount); ?> siswa tercatat</div>
                        <?php else: ?>
                        <span class="text-muted" style="font-size:12px">Belum ada absensi</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span style="background:<?php echo e($st['bg']); ?>;color:<?php echo e($st['color']); ?>;padding:4px 10px;border-radius:8px;font-size:11px;font-weight:600"><?php echo e($st['label']); ?></span>
                    </td>
                    <td class="text-center">
                        <?php if($absensiCount > 0): ?>
                        <button onclick="openAbsensiModal(<?php echo e($sc->id); ?>, '<?php echo e(addslashes($sc->paket?->nama ?? '')); ?>', <?php echo e($sc->pertemuan_ke ?? 0); ?>)"
                            class="btn btn-sm btn-primary fw-semibold" style="border-radius:8px;font-size:11px;padding:5px 12px">
                            <i class="bi bi-pencil-fill me-1"></i>Edit Absensi
                        </button>
                        <?php else: ?>
                        <span class="text-muted" style="font-size:11px">–</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <div class="text-muted">
                            <i class="bi bi-calendar-x" style="font-size:40px;display:block;margin-bottom:12px;opacity:.4"></i>
                            <div class="fw-semibold mb-1">Belum ada sesi yang ditemukan</div>
                            <div style="font-size:12px">Coba ubah filter atau tambah jadwal sesi belajar terlebih dahulu</div>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($query->hasPages()): ?>
    <div class="mt-4 d-flex justify-content-center"><?php echo e($query->links()); ?></div>
    <?php endif; ?>
</div>


<div class="modal fade" id="absensiModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius:18px;overflow:hidden">
            <div class="modal-header border-0 p-4" style="background:linear-gradient(135deg,#461256,#68117e);color:#fff">
                <div>
                    <h6 class="modal-title fw-bold mb-0"><i class="bi bi-clipboard2-check me-2"></i>Edit Absensi Siswa</h6>
                    <div style="font-size:12px;opacity:.8" id="absensiModalSubtitle"></div>
                </div>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0" id="absensiModalBody">
                <div class="text-center py-5"><div class="spinner-border text-primary"></div></div>
            </div>
            <div class="modal-footer border-0 p-4" style="background:var(--input-bg)">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal" style="border-radius:10px"><i class="bi bi-x me-1"></i>Batal</button>
                <button type="button" class="btn btn-primary px-5 fw-semibold" id="saveAbsensiBtn" onclick="saveAbsensi()" style="border-radius:10px"><i class="bi bi-check-lg me-1"></i>Simpan Absensi</button>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
let currentScheduleId = null;
let absensiRows = [];

const statusOptions = [
    { value: 'hadir',               label: 'Hadir',               color: 'var(--soft-success-text)' },
    { value: 'menunggu_konfirmasi', label: 'Menunggu Konfirmasi', color: 'var(--soft-warning-text)' },
    { value: 'tidak_hadir',         label: 'Tidak Hadir',         color: 'var(--soft-danger-text)'  },
    { value: 'izin',                label: 'Izin',                color: 'var(--soft-info-text)'    },
    { value: 'sakit',               label: 'Sakit',               color: 'var(--soft-warning-text)' },
    { value: 'alpa',                label: 'Alpa',                color: 'var(--soft-danger-text)'  },
];

function openAbsensiModal(scheduleId, paketNama, sesiKe) {
    currentScheduleId = scheduleId;
    absensiRows = [];
    document.getElementById('absensiModalSubtitle').textContent = paketNama + ' — Sesi ke-' + sesiKe;
    document.getElementById('absensiModalBody').innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary"></div></div>';
    new bootstrap.Modal('#absensiModal').show();

    $.get('/admin/attendance/' + scheduleId, function(res) {
        if (!res.success) { showToast('Gagal memuat data absensi.', 'error'); return; }

        absensiRows = res.data.absensi;
        const rows = absensiRows.map((ab, idx) => {
            const opts = statusOptions.map(s =>
                `<option value="${s.value}" ${ab.status === s.value ? 'selected' : ''}>${s.label}</option>`
            ).join('');
            const konfirmasi = ab.konfirmasi_at
                ? `<span style="font-size:10px;color:var(--soft-success-text)"><i class="bi bi-check2-circle me-1"></i>Konfirmasi siswa</span>`
                : `<span style="font-size:10px;color:var(--text-muted)"><i class="bi bi-clock me-1"></i>Belum konfirmasi</span>`;
            return `<tr style="border-bottom:1px solid var(--card-border)">
                <td class="ps-3" style="font-size:13px">
                    <div class="fw-semibold">${ab.siswa_name}</div>
                    <div class="text-muted" style="font-size:11px">NIS: ${ab.siswa_nis}</div>
                    <div class="mt-1">${konfirmasi}</div>
                </td>
                <td>
                    <select class="form-select form-select-sm" id="ab_status_${ab.id}" style="border-radius:8px;font-size:12px;min-width:160px">
                        ${opts}
                    </select>
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm" id="ab_catatan_${ab.id}"
                           value="${ab.catatan ?? ''}" placeholder="Catatan (opsional)"
                           style="border-radius:8px;font-size:12px">
                </td>
            </tr>`;
        }).join('');

        document.getElementById('absensiModalBody').innerHTML = `
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr style="background:var(--input-bg)">
                            <th class="ps-3" style="font-size:11px;color:var(--text-muted);font-weight:600;text-transform:uppercase">Siswa</th>
                            <th style="font-size:11px;color:var(--text-muted);font-weight:600;text-transform:uppercase">Status</th>
                            <th style="font-size:11px;color:var(--text-muted);font-weight:600;text-transform:uppercase">Catatan</th>
                        </tr>
                    </thead>
                    <tbody>${rows}</tbody>
                </table>
            </div>`;
    }).fail(() => {
        document.getElementById('absensiModalBody').innerHTML = '<div class="text-center py-5 text-muted">Gagal memuat data</div>';
    });
}

function saveAbsensi() {
    if (!currentScheduleId || !absensiRows.length) return;

    const payload = {
        _token: document.querySelector('meta[name=csrf-token]').content,
        absensi: absensiRows.map(ab => ({
            id:      ab.id,
            status:  document.getElementById('ab_status_' + ab.id)?.value ?? ab.status,
            catatan: document.getElementById('ab_catatan_' + ab.id)?.value ?? '',
        }))
    };

    const btn = document.getElementById('saveAbsensiBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';

    $.ajax({
        url:    '/admin/attendance/' + currentScheduleId + '/bulk',
        method: 'POST',
        data:   JSON.stringify(payload),
        contentType: 'application/json',
        success(res) {
            if (res.success) {
                bootstrap.Modal.getInstance(document.getElementById('absensiModal'))?.hide();
                showToast(res.message, 'success');
                setTimeout(() => location.reload(), 1200);
            } else {
                showToast(res.message || 'Terjadi kesalahan.', 'error');
            }
        },
        error(xhr) {
            const msg = xhr.responseJSON?.message ?? 'Terjadi kesalahan saat menyimpan.';
            showToast(msg, 'error');
        },
        complete() {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-lg me-1"></i>Simpan Absensi';
        }
    });
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/admin/attendance/index.blade.php ENDPATH**/ ?>