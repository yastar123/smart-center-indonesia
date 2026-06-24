<?php $__env->startSection('title', 'Jadwal Mata Pelajaran'); ?>
<?php $__env->startSection('page-title', 'Jadwal Mata Pelajaran'); ?>

<?php $__env->startSection('content'); ?>


<div class="dashboard-card mb-4 fade-up" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
    <div class="row align-items-center g-3" style="position:relative">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-3 mb-2">
                <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px">
                    <i class="bi bi-calendar-week-fill"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0" style="color:white">Jadwal Sesi Mata Pelajaran</h5>
                    <span style="font-size:12px;opacity:.8">Atur jadwal setiap sesi berdasarkan paket belajar yang tersedia</span>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="<?php echo e(route('admin.schedules.create')); ?>" class="btn fw-semibold px-4"
               style="background:rgba(255,255,255,.2);color:white;border:1px solid rgba(255,255,255,.3);border-radius:10px;backdrop-filter:blur(10px)">
                <i class="bi bi-plus-lg me-2"></i>Tambah Jadwal
            </a>
        </div>
    </div>
</div>


<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3 fade-up">
        <div class="stat-card" style="border-top:3px solid #c84ddf">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="stat-title">Total Jadwal</div><div class="stat-value"><?php echo e($stats['total']); ?></div></div>
                <div class="stat-icon bg-primary-soft" style="color:white"><i class="bi bi-calendar-week"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.05s">
        <div class="stat-card" style="border-top:3px solid #f6af23">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="stat-title">Hari Ini</div><div class="stat-value text-warning"><?php echo e($stats['hari_ini']); ?></div></div>
                <div class="stat-icon bg-warning-soft" style="color:white"><i class="bi bi-sun-fill"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.10s">
        <div class="stat-card" style="border-top:3px solid #0284c7">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="stat-title">Dijadwalkan</div><div class="stat-value text-primary"><?php echo e($stats['dijadwalkan']); ?></div></div>
                <div class="stat-icon bg-info-soft" style="color:white"><i class="bi bi-clock-history"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-up" style="animation-delay:.15s">
        <div class="stat-card" style="border-top:3px solid #10b981">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="stat-title">Selesai</div><div class="stat-value text-success"><?php echo e($stats['selesai']); ?></div></div>
                <div class="stat-icon bg-success-soft" style="color:white"><i class="bi bi-check-circle-fill"></i></div>
            </div>
        </div>
    </div>
</div>


<div class="dashboard-card mb-4 fade-up">
    <form id="filterForm" method="GET" action="<?php echo e(route('admin.schedules.index')); ?>">
        <div class="row g-2 align-items-end">
            <div class="col-12 col-md-3">
                <label class="form-label fw-semibold" style="font-size:12px">Cari Paket / Topik / Ruangan</label>
                <div class="input-group">
                    <span class="input-group-text" style="border-radius:10px 0 0 10px;background:var(--input-bg);border-color:var(--card-border)">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari jadwal..."
                        class="form-control" style="border-radius:0 10px 10px 0;border-color:var(--card-border);background:var(--input-bg)"
                        onchange="document.getElementById('filterForm').submit()">
                </div>
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label fw-semibold" style="font-size:12px">Paket</label>
                <select name="paket_id" class="form-select" style="border-radius:10px"
                    onchange="document.getElementById('filterForm').submit()">
                    <option value="">Semua Paket</option>
                    <?php $__currentLoopData = $pakets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($p->id); ?>" <?php echo e(request('paket_id')==$p->id?'selected':''); ?>><?php echo e($p->nama); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label fw-semibold" style="font-size:12px">Status</label>
                <select name="status" class="form-select" style="border-radius:10px"
                    onchange="document.getElementById('filterForm').submit()">
                    <option value="">Semua</option>
                    <option value="dijadwalkan" <?php echo e(request('status')=='dijadwalkan'?'selected':''); ?>>Dijadwalkan</option>
                    <option value="berlangsung" <?php echo e(request('status')=='berlangsung'?'selected':''); ?>>Berlangsung</option>
                    <option value="selesai"     <?php echo e(request('status')=='selesai'?'selected':''); ?>>Selesai</option>
                    <option value="dibatalkan"  <?php echo e(request('status')=='dibatalkan'?'selected':''); ?>>Dibatalkan</option>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label fw-semibold" style="font-size:12px">Tanggal</label>
                <input type="date" name="tanggal" value="<?php echo e(request('tanggal')); ?>" class="form-control"
                    style="border-radius:10px" onchange="document.getElementById('filterForm').submit()">
            </div>
            <div class="col-6 col-md-2">
                <?php if(request()->hasAny(['search','status','paket_id','tanggal'])): ?>
                <a href="<?php echo e(route('admin.schedules.index')); ?>" class="btn btn-outline-secondary w-100" style="border-radius:10px" title="Reset">
                    <i class="bi bi-x-lg me-1"></i>Reset
                </a>
                <?php else: ?>
                <a href="<?php echo e(route('admin.schedules.create')); ?>" class="btn btn-primary w-100 fw-semibold" style="border-radius:10px">
                    <i class="bi bi-plus-lg"></i>
                </a>
                <?php endif; ?>
            </div>
        </div>
    </form>
</div>


<div class="dashboard-card fade-up">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold mb-0"><i class="bi bi-list-ul text-primary me-2"></i>Daftar Jadwal Sesi
            <span class="badge ms-2" style="background:var(--soft-primary-bg);color:var(--soft-primary-text);font-size:11px"><?php echo e($schedules->total()); ?> data</span>
        </h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover table-modern align-middle mb-0">
            <thead class="thead-modern">
                <tr>
                    <th class="ps-3">Paket</th>
                    <th>Sesi</th>
                    <th>Tanggal &amp; Waktu</th>
                    <th class="d-none d-md-table-cell">Guru</th>
                    <th class="d-none d-lg-table-cell">Ruangan / Link</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $schedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $statusMap = [
                        'dijadwalkan' => ['bg'=>'var(--soft-primary-bg)','color'=>'var(--soft-primary-text)','label'=>'Dijadwalkan'],
                        'berlangsung' => ['bg'=>'var(--soft-warning-bg)','color'=>'var(--soft-warning-text)','label'=>'Berlangsung'],
                        'selesai'     => ['bg'=>'var(--soft-success-bg)','color'=>'var(--soft-success-text)','label'=>'Selesai'],
                        'dibatalkan'  => ['bg'=>'var(--soft-danger-bg)','color'=>'var(--soft-danger-text)','label'=>'Dibatalkan'],
                    ];
                    $st = $statusMap[$sc->status] ?? ['bg'=>'var(--soft-muted-bg)','color'=>'var(--soft-muted-text)','label'=>$sc->status];
                    $isToday = $sc->tanggal && $sc->tanggal->isToday();
                    $mapelNames = $sc->paket?->mataPelajaran->pluck('nama')->join(', ') ?? '–';
                ?>
                <tr style="border-bottom:1px solid var(--card-border);transition:background .15s<?php echo e($isToday ? ';background:rgba(104,17,126,.03)' : ''); ?>">
                    <td class="ps-3">
                        <div class="fw-semibold" style="font-size:13px"><?php echo e($sc->paket?->nama ?? '–'); ?></div>
                        <div class="text-muted" style="font-size:11px">
                            <i class="bi bi-journal-bookmark me-1"></i><?php echo e($mapelNames); ?>

                        </div>
                    </td>
                    <td>
                        <span style="display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:50%;background:var(--soft-primary-bg);color:var(--soft-primary-text);font-size:13px;font-weight:700">
                            <?php echo e($sc->pertemuan_ke ?? '–'); ?>

                        </span>
                    </td>
                    <td>
                        <div class="fw-semibold" style="font-size:13px">
                            <?php echo e($sc->tanggal ? $sc->tanggal->format('d M Y') : '–'); ?>

                            <?php if($isToday): ?><span class="badge ms-1" style="background:var(--soft-primary-bg);color:var(--soft-primary-text);font-size:10px">Hari ini</span><?php endif; ?>
                        </div>
                        <div class="text-muted" style="font-size:11px"><i class="bi bi-clock me-1"></i><?php echo e(str_replace(':', '.', substr($sc->jam_mulai ?? '', 0, 5)) ?: '–'); ?> – <?php echo e(str_replace(':', '.', substr($sc->jam_selesai ?? '', 0, 5)) ?: '–'); ?> WIB</div>
                    </td>
                    <td class="d-none d-md-table-cell">
                        <div style="font-size:12.5px"><?php echo e($sc->paket?->guru?->name ?? $sc->guru?->name ?? '–'); ?></div>
                    </td>
                    <td class="d-none d-lg-table-cell text-muted" style="font-size:.82rem">
                        <?php if($sc->ruangan): ?>
                        <i class="bi bi-door-open me-1"></i><?php echo e($sc->ruangan); ?>

                        <?php elseif($sc->link_meeting): ?>
                        <a href="<?php echo e($sc->link_meeting); ?>" target="_blank" class="text-decoration-none" style="font-size:.82rem"><i class="bi bi-link-45deg me-1"></i>Buka Link</a>
                        <?php else: ?>
                        –
                        <?php endif; ?>
                    </td>
                    <td>
                        <span style="background:<?php echo e($st['bg']); ?>;color:<?php echo e($st['color']); ?>;padding:4px 10px;border-radius:8px;font-size:11px;font-weight:600"><?php echo e($st['label']); ?></span>
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-1">
                            <button onclick="showDetail(<?php echo e($sc->id); ?>)" class="btn btn-sm btn-act-view" title="Detail"><i class="bi bi-eye-fill"></i></button>
                            <button onclick="editSchedule(<?php echo e($sc->id); ?>)" class="btn btn-sm btn-act-edit" title="Edit"><i class="bi bi-pencil-fill"></i></button>
                            <button onclick="deleteSchedule(<?php echo e($sc->id); ?>, '<?php echo e(addslashes($sc->paket?->nama ?? 'Jadwal ini')); ?>', <?php echo e($sc->pertemuan_ke ?? 0); ?>)" class="btn btn-sm btn-act-del" title="Hapus"><i class="bi bi-trash-fill"></i></button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <div class="text-muted">
                            <i class="bi bi-calendar-x" style="font-size:40px;display:block;margin-bottom:12px;opacity:.4"></i>
                            <div class="fw-semibold mb-1">Belum ada jadwal sesi</div>
                            <div style="font-size:12px">Klik "Tambah Jadwal" untuk menjadwalkan sesi belajar</div>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($schedules->hasPages()): ?>
    <div class="mt-4 d-flex justify-content-center"><?php echo e($schedules->links()); ?></div>
    <?php endif; ?>
</div>


<div class="modal fade" id="scheduleModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius:18px;overflow:hidden">
            <div class="modal-header border-0 p-4" style="background:linear-gradient(135deg,#461256,#68117e);color:#fff">
                <h6 class="modal-title fw-bold" id="modalTitle"><i class="bi bi-calendar-plus me-2"></i>Tambah Jadwal Sesi</h6>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" style="background:var(--input-bg)">
                <input type="hidden" id="scheduleId">

                
                <div class="mb-3 p-3 rounded-3" id="paketInfoBox" style="background:var(--soft-primary-bg);border:1.5px solid var(--soft-primary-border);display:none">
                    <div class="d-flex gap-3 align-items-center">
                        <div style="width:40px;height:40px;border-radius:10px;background:var(--primary);display:flex;align-items:center;justify-content:center;color:white;flex-shrink:0"><i class="bi bi-box-seam"></i></div>
                        <div style="flex:1">
                            <div class="fw-bold" id="paketInfoName" style="font-size:14px;color:var(--soft-primary-text)"></div>
                            <div style="font-size:12px;color:var(--text-muted)" id="paketInfoMeta"></div>
                        </div>
                        <div id="paketInfoProgress" style="text-align:right">
                            <div style="font-size:12px;color:var(--text-muted)">Sesi terjadwal</div>
                            <div class="fw-bold" id="paketInfoCount" style="font-size:15px;color:var(--soft-primary-text)">– / –</div>
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold" style="font-size:12px">Paket Belajar <span class="text-danger">*</span></label>
                        <select id="paket_id" class="form-select" onchange="onPaketChange(this.value)">
                            <option value="">— Pilih Paket —</option>
                            <?php $__currentLoopData = $pakets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($p->id); ?>"
                                data-nama="<?php echo e($p->nama); ?>"
                                data-guru="<?php echo e($p->guru?->name ?? '–'); ?>"
                                data-mapel="<?php echo e($p->mataPelajaran->pluck('nama')->join(', ') ?: '–'); ?>"
                                data-jenis="<?php echo e($p->jenis); ?>"
                                data-jumlah="<?php echo e($p->jumlah_pertemuan); ?>">
                                <?php echo e($p->nama); ?> — <?php echo e($p->mataPelajaran->pluck('nama')->join(', ') ?: '–'); ?> — <?php echo e($p->guru?->name ?? 'belum ada guru'); ?> — <?php echo e(ucfirst($p->jenis)); ?>

                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:12px">Sesi Ke <span class="text-danger">*</span></label>
                        <select id="pertemuan_ke" class="form-select">
                            <option value="">— Pilih dulu paket —</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:12px">Metode Kelas <span class="text-danger">*</span></label>
                        <select id="sc_jenis" class="form-select">
                            <option value="offline">🏫 Offline (Tatap Muka)</option>
                            <option value="online">💻 Online</option>
                            <option value="private">👤 Private</option>
                        </select>
                    </div>
                    <div class="col-md-6" id="statusScWrap" style="display:none">
                        <label class="form-label fw-semibold" style="font-size:12px">Status</label>
                        <select id="sc_status" class="form-select">
                            <option value="dijadwalkan">Dijadwalkan</option>
                            <option value="berlangsung">Berlangsung</option>
                            <option value="selesai">Selesai</option>
                            <option value="dibatalkan">Dibatalkan</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:12px">Tanggal Mulai <span class="text-danger">*</span></label>
                        <input type="date" id="tanggal" class="form-control" style="border-radius:10px">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:12px">Jam Mulai <span class="text-danger">*</span></label>
                        <input type="text" id="jam_mulai" class="form-control flatpickr-time-input" placeholder="13:30" autocomplete="off" style="border-radius:10px">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:12px">Jam Selesai <span class="text-danger">*</span></label>
                        <input type="text" id="jam_selesai" class="form-control flatpickr-time-input" placeholder="15:00" autocomplete="off" style="border-radius:10px">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:12px">Ruangan <span class="text-muted">(opsional)</span></label>
                        <input type="text" id="ruangan" class="form-control" placeholder="cth: Ruang A1, Lab Komputer..." style="border-radius:10px">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold" style="font-size:12px">Link Meeting <span class="text-muted">(opsional, untuk sesi online)</span></label>
                        <input type="text" id="link_meeting" class="form-control" placeholder="https://meet.google.com/..." style="border-radius:10px">
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 p-4" style="background:var(--input-bg)">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal" style="border-radius:10px"><i class="bi bi-x me-1"></i>Batal</button>
                <button type="button" class="btn btn-primary px-5 fw-semibold" id="saveBtn" onclick="saveSchedule()" style="border-radius:10px"><i class="bi bi-check-lg me-1"></i>Simpan Jadwal</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:18px;overflow:hidden">
            <div class="modal-header border-0 p-4" style="background:linear-gradient(135deg,#461256,#68117e);color:#fff">
                <h6 class="modal-title fw-bold"><i class="bi bi-calendar-event me-2"></i>Detail Jadwal Sesi</h6>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0" id="detailBody">
                <div class="text-center py-5"><div class="spinner-border text-primary"></div></div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.flatpickr-time-input').forEach(function(el) {
        flatpickr(el, {
            enableTime: true,
            noCalendar: true,
            time_24hr: true,
            dateFormat: 'H:i',
            minuteIncrement: 5,
        });
    });
});

function fmtWib(t) {
    if (!t || t === '–') return '–';
    return t.substr(0,5).replace(':', '.') + ' WIB';
}

const paketData = {};
<?php $__currentLoopData = $pakets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
paketData[<?php echo e($p->id); ?>] = {
    nama:      <?php echo json_encode($p->nama, 15, 512) ?>,
    guru:      <?php echo json_encode($p->guru?->name ?? '–', 15, 512) ?>,
    mapel:     <?php echo json_encode($p->mataPelajaran->pluck('nama')->join(', ') ?: '–', 512) ?>,
    jenis:     <?php echo json_encode($p->jenis, 15, 512) ?>,
    tipeKelas: <?php echo json_encode($p->tipe_kelas ?? 'offline', 15, 512) ?>,
    jumlah:    <?php echo e($p->jumlah_pertemuan); ?>,
};
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

function onPaketChange(paketId, currentSesi) {
    const box = document.getElementById('paketInfoBox');
    const sel = document.getElementById('pertemuan_ke');

    if (!paketId) {
        box.style.display = 'none';
        sel.innerHTML = '<option value="">— Pilih dulu paket —</option>';
        return;
    }

    const p = paketData[paketId];
    if (!p) return;

    document.getElementById('paketInfoName').textContent = p.nama + ' — ' + p.mapel;
    document.getElementById('paketInfoMeta').textContent = 'Guru: ' + p.guru + ' | Jenis: ' + p.jenis;
    box.style.display = 'block';

    // Auto-set delivery method from package tipe_kelas
    const jenisEl = document.getElementById('sc_jenis');
    if (jenisEl) {
        const validJenis = ['online', 'offline', 'private'];
        jenisEl.value = validJenis.includes(p.tipeKelas) ? p.tipeKelas : 'offline';
    }

    fetch(`/admin/schedules?paket_id=${paketId}&all=1`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => { if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
        .then(data => {
            const used = (data.data || []).map(s => s.pertemuan_ke);
            document.getElementById('paketInfoCount').textContent = used.length + ' / ' + p.jumlah;

            sel.innerHTML = '';
            for (let i = 1; i <= p.jumlah; i++) {
                const opt = document.createElement('option');
                opt.value = i;
                const alreadyScheduled = used.includes(i) && i !== Number(currentSesi);
                opt.textContent = 'Sesi ke-' + i + (alreadyScheduled ? ' (sudah dijadwalkan)' : '');
                if (alreadyScheduled) opt.style.color = 'var(--text-muted)';
                if (i === Number(currentSesi)) opt.selected = true;
                sel.appendChild(opt);
            }
        })
        .catch(() => {
            sel.innerHTML = '';
            for (let i = 1; i <= p.jumlah; i++) {
                const opt = document.createElement('option');
                opt.value = i;
                opt.textContent = 'Sesi ke-' + i;
                if (i === Number(currentSesi)) opt.selected = true;
                sel.appendChild(opt);
            }
        });
}

function openModal() {
    document.getElementById('scheduleId').value    = '';
    document.getElementById('modalTitle').innerHTML= '<i class="bi bi-calendar-plus me-2"></i>Tambah Jadwal Sesi';
    document.getElementById('paket_id').value      = '';
    document.getElementById('pertemuan_ke').innerHTML = '<option value="">— Pilih dulu paket —</option>';
    document.getElementById('tanggal').value       = '';
    document.getElementById('jam_mulai').value     = '';
    document.getElementById('jam_selesai').value   = '';
    document.getElementById('ruangan').value       = '';
    document.getElementById('link_meeting').value  = '';
    document.getElementById('sc_jenis').value      = 'offline';
    document.getElementById('paketInfoBox').style.display  = 'none';
    document.getElementById('statusScWrap').style.display  = 'none';
    new bootstrap.Modal('#scheduleModal').show();
}

function editSchedule(id) {
    window.location.href = '/admin/schedules/' + id + '/edit';
}

function saveSchedule() {
    const id     = document.getElementById('scheduleId').value;
    const url    = id ? '/admin/schedules/' + id : '<?php echo e(route("admin.schedules.store")); ?>';
    const paketId = document.getElementById('paket_id').value;
    const sesiKe  = document.getElementById('pertemuan_ke').value;
    const tgl     = document.getElementById('tanggal').value;
    const jMulai  = document.getElementById('jam_mulai').value;
    const jSelesai= document.getElementById('jam_selesai').value;

    if (!paketId)  { showToast('Pilih paket belajar terlebih dahulu.', 'warning'); return; }
    if (!sesiKe)   { showToast('Pilih sesi ke berapa.', 'warning'); return; }
    if (!tgl)      { showToast('Tanggal wajib diisi.', 'warning'); return; }
    if (!jMulai || !jSelesai) { showToast('Jam mulai dan selesai wajib diisi.', 'warning'); return; }

    const payload = {
        _token:       document.querySelector('meta[name=csrf-token]').content,
        paket_id:     paketId,
        jenis:        document.getElementById('sc_jenis').value || 'offline',
        pertemuan_ke: sesiKe,
        tanggal:      tgl,
        jam_mulai:    jMulai,
        jam_selesai:  jSelesai,
        ruangan:      document.getElementById('ruangan').value || null,
        link_meeting: document.getElementById('link_meeting').value || null,
    };
    if (id) { payload._method = 'PUT'; payload.status = document.getElementById('sc_status').value; }

    const btn = document.getElementById('saveBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';

    $.ajax({
        url, method: 'POST', data: payload,
        success(res) {
            if (res.success) {
                bootstrap.Modal.getInstance(document.getElementById('scheduleModal'))?.hide();
                showToast(res.message, 'success');
                setTimeout(() => location.reload(), 1200);
            } else {
                showToast(res.message || 'Terjadi kesalahan.', 'error');
            }
        },
        error(xhr) {
            const errors = xhr.responseJSON?.errors;
            const msg = errors ? Object.values(errors).flat().join('; ') : (xhr.responseJSON?.message ?? 'Terjadi kesalahan.');
            showToast(msg, 'error');
        },
        complete() { btn.disabled = false; btn.innerHTML = '<i class="bi bi-check-lg me-1"></i>Simpan Jadwal'; }
    });
}

function showDetail(id) {
    document.getElementById('detailBody').innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary"></div></div>';
    new bootstrap.Modal('#detailModal').show();
    $.get('/admin/schedules/' + id, function(res) {
        const s = res.data;
        const statusMap = {
            dijadwalkan:'rgba(200,77,223,.15):#c84ddf:Dijadwalkan',
            berlangsung:'rgba(246,175,35,.15):#e09000:Berlangsung',
            selesai:'rgba(16,185,129,.15):#16a34a:Selesai',
            dibatalkan:'rgba(239,68,68,.15):#dc2626:Dibatalkan'
        };
        const [sbg,scol,slbl] = (statusMap[s.status]||'rgba(148,163,184,.15):#64748b:'+s.status).split(':');
        const tgl = s.tanggal ? s.tanggal.substr(0,10) : '–';
        const mapelNames = s.paket?.mata_pelajaran?.map(m => m.nama).join(', ') ?? '–';
        document.getElementById('detailBody').innerHTML = `
            <div style="padding:20px">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <div class="fw-bold" style="font-size:15px">${s.paket?.nama ?? 'Paket'} — Sesi ke-${s.pertemuan_ke ?? '?'}</div>
                        <div style="font-size:12px;color:var(--text-muted)">${tgl} · ${fmtWib(s.jam_mulai)} – ${fmtWib(s.jam_selesai)}</div>
                    </div>
                    <span style="background:${sbg};color:${scol};padding:4px 12px;border-radius:8px;font-size:12px;font-weight:600">${slbl}</span>
                </div>
                <table style="width:100%;border-collapse:collapse;font-size:13px">
                    ${drow('Paket', s.paket?.nama ?? '–')}
                    ${drow('Mata Pelajaran', mapelNames)}
                    ${drow('Guru', s.paket?.guru?.name ?? s.guru?.name ?? '–')}
                    ${drow('Cabang', s.paket?.cabang?.name ?? s.cabang?.name ?? '–')}
                    ${drow('Jenis', s.paket?.jenis ? (s.paket.jenis.charAt(0).toUpperCase() + s.paket.jenis.slice(1)) : '–')}
                    ${s.link_meeting ? drow('Link Meeting', '<a href="'+s.link_meeting+'" target="_blank">Buka Link</a>') : drow('Ruangan', s.ruangan || '–')}
                </table>
            </div>
        `;
    }).fail(() => { document.getElementById('detailBody').innerHTML = '<div class="text-center py-5 text-muted">Gagal memuat data</div>'; });
}

function drow(label, val) {
    return `<tr style="border-bottom:1px solid var(--card-border)">
        <td style="padding:7px 4px 7px 0;color:var(--text-muted);font-size:12px;width:38%">${label}</td>
        <td style="padding:7px 0;font-size:13px;font-weight:500;color:var(--text-primary)">${val}</td>
    </tr>`;
}

function deleteSchedule(id, paket, sesi) {
    confirmAction(`Hapus jadwal sesi ke-${sesi} dari paket "${paket}"?`, function() {
        $.post('/admin/schedules/' + id, {
            _method: 'DELETE',
            _token:  document.querySelector('meta[name=csrf-token]').content
        }, function(res) {
            if (res.success) { showToast(res.message, 'success'); setTimeout(() => location.reload(), 1200); }
        }).fail(() => showToast('Tidak dapat menghapus jadwal.', 'error'));
    }, null, {title:'Hapus Jadwal', okText:'Ya, Hapus'});
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/admin/schedules/index.blade.php ENDPATH**/ ?>