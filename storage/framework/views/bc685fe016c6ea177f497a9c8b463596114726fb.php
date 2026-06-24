<?php $__env->startSection('title','Absensi — '.$class->nama_kelas); ?>
<?php $__env->startSection('page-title','Input Absensi'); ?>

<?php $__env->startSection('content'); ?>


<div class="dashboard-card mb-4 fade-up"
     style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
    <div class="row align-items-center g-3" style="position:relative">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-3">
                <a href="<?php echo e(route('guru.classes.show', $class->id)); ?>"
                   class="btn btn-sm flex-shrink-0"
                   style="background:rgba(255,255,255,.15);color:white;border:1px solid rgba(255,255,255,.25);border-radius:9px;padding:6px 12px">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
                <div>
                    <div style="font-size:11px;opacity:.6;margin-bottom:2px;text-transform:uppercase;letter-spacing:.08em">Input Absensi</div>
                    <h5 class="fw-bold mb-0" style="color:white"><?php echo e($class->nama_kelas); ?></h5>
                    <div style="font-size:12px;opacity:.75">
                        <?php echo e($class->mataPelajaran->nama ?? '–'); ?> · <?php echo e($class->cabang->name ?? 'Pusat'); ?>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-md-end">
            <div style="font-size:48px;opacity:.1;line-height:1">
                <i class="bi bi-clipboard2-check-fill"></i>
            </div>
        </div>
    </div>
</div>


<div class="dashboard-card mb-4 fade-up" style="border-left:4px solid #c84ddf">
    <div class="d-flex align-items-center gap-2 flex-wrap" style="font-size:12px;color:var(--text-muted)">
        <i class="bi bi-info-circle text-primary me-1"></i>
        <span class="fw-semibold text-primary me-2">Alur Absensi:</span>
        <span class="badge" style="background:var(--soft-warning-bg);color:var(--soft-warning-text);padding:4px 9px;border-radius:7px">① Guru menandai siswa hadir</span>
        <i class="bi bi-arrow-right" style="font-size:11px"></i>
        <span class="badge" style="background:var(--soft-primary-bg);color:var(--soft-primary-text);padding:4px 9px;border-radius:7px">② Siswa klik tombol "Konfirmasi Kehadiran"</span>
        <i class="bi bi-arrow-right" style="font-size:11px"></i>
        <span class="badge" style="background:var(--soft-success-bg);color:var(--soft-success-text);padding:4px 9px;border-radius:7px">③ Status: Hadir ✅</span>
    </div>
</div>


<div class="dashboard-card mb-4 fade-up">
    <div class="fw-semibold mb-2" style="font-size:13px;color:var(--text-primary)">
        <i class="bi bi-table text-primary me-2"></i>Parameter Status Absensi
    </div>
    <div class="row g-2">
        <?php
        $legends = [
            ['guru'=>false,'siswa'=>false,'status'=>'Tidak Hadir','bg'=>'var(--soft-danger-bg)','color'=>'#ef4444'],
            ['guru'=>true, 'siswa'=>false,'status'=>'Menunggu Konfirmasi Siswa','bg'=>'var(--soft-warning-bg)','color'=>'#f6af23'],
            ['guru'=>false,'siswa'=>true, 'status'=>'Tidak Valid','bg'=>'var(--soft-muted-bg)','color'=>'var(--text-muted)'],
            ['guru'=>true, 'siswa'=>true, 'status'=>'Hadir','bg'=>'var(--soft-success-bg)','color'=>'#10b981'],
        ];
        ?>
        <?php $__currentLoopData = $legends; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-sm-6 col-lg-3">
            <div class="d-flex align-items-center gap-2 p-2 rounded-2" style="background:<?php echo e($l['bg']); ?>;border:1px solid rgba(0,0,0,.05)">
                <span style="font-size:14px"><?php echo e($l['guru'] ? '✅' : '❌'); ?></span>
                <span style="font-size:14px"><?php echo e($l['siswa'] ? '✅' : '❌'); ?></span>
                <span style="font-size:12px;font-weight:600;color:<?php echo e($l['color']); ?>"><?php echo e($l['status']); ?></span>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <div class="col-12">
            <div class="text-muted" style="font-size:11px">
                <span class="me-3">✅/❌ pertama = Guru menandai hadir</span>
                <span>✅/❌ kedua = Siswa mengkonfirmasi kehadiran</span>
            </div>
        </div>
    </div>
</div>


<div class="dashboard-card mb-4 fade-up">
    <div class="d-flex align-items-center gap-3 mb-3">
        <div style="width:36px;height:36px;border-radius:10px;background:rgba(200,77,223,.1);display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <i class="bi bi-calendar-check text-primary" style="font-size:15px"></i>
        </div>
        <div>
            <h6 class="fw-bold mb-0" style="font-size:14px;color:var(--text-primary)">Pilih Pertemuan</h6>
            <p class="text-muted mb-0" style="font-size:12px">Pilih jadwal untuk mengisi absensi</p>
        </div>
    </div>
    <select id="jadwalSelect" class="form-select" style="border-radius:10px;border-color:var(--card-border);background:var(--input-bg)">
        <option value="">— Pilih pertemuan —</option>
        <?php $__currentLoopData = $class->jadwal->sortBy('pertemuan_ke'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $j): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $tgl = $j->tanggal instanceof \Carbon\Carbon ? $j->tanggal : \Carbon\Carbon::parse($j->tanggal);
        ?>
        <option value="<?php echo e($j->id); ?>">
            <?php if($j->pertemuan_ke): ?> Pertemuan ke-<?php echo e($j->pertemuan_ke); ?> · <?php endif; ?>
            <?php echo e($tgl->locale('id')->isoFormat('dddd, D MMM Y')); ?> ·
            <?php echo e(\Carbon\Carbon::parse($j->jam_mulai)->format('H:i')); ?> –
            <?php echo e(\Carbon\Carbon::parse($j->jam_selesai)->format('H:i')); ?>

        </option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
</div>


<?php if($class->jadwal->isEmpty()): ?>
<div class="dashboard-card fade-up">
    <div class="text-center py-5">
        <div style="width:80px;height:80px;border-radius:50%;background:var(--input-bg);display:flex;align-items:center;justify-content:center;margin:0 auto 16px">
            <i class="bi bi-calendar-x text-muted" style="font-size:2rem;opacity:.5"></i>
        </div>
        <h6 class="fw-semibold mb-2" style="color:var(--text-primary)">Belum Ada Jadwal</h6>
        <p class="text-muted mb-0" style="font-size:13px">Admin belum membuat jadwal untuk kelas ini.</p>
        <a href="<?php echo e(route('guru.schedules.index')); ?>" class="btn btn-primary mt-3" style="border-radius:10px">
            <i class="bi bi-calendar3 me-1"></i>Lihat Jadwal Saya
        </a>
    </div>
</div>
<?php else: ?>
<div id="jadwalList" class="d-flex flex-column gap-3">
    <?php $__currentLoopData = $class->jadwal->sortBy('pertemuan_ke'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $j): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php
        $tgl     = $j->tanggal instanceof \Carbon\Carbon ? $j->tanggal : \Carbon\Carbon::parse($j->tanggal);
        $isPast  = $tgl->isPast();
        $isToday = $tgl->isToday();
    ?>
    <div class="dashboard-card fade-up" id="jadwal-card-<?php echo e($j->id); ?>">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div style="width:48px;height:48px;border-radius:14px;background:<?php echo e($isToday ? 'rgba(16,185,129,.1)' : 'rgba(200,77,223,.08)'); ?>;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <i class="bi bi-calendar3" style="font-size:18px;color:<?php echo e($isToday ? '#10b981' : '#c84ddf'); ?>"></i>
                </div>
                <div>
                    <div class="fw-semibold" style="font-size:14px;color:var(--text-primary)">
                        <?php if($j->pertemuan_ke): ?>
                        <span style="background:var(--soft-primary-bg);color:var(--soft-primary-text);padding:2px 8px;border-radius:6px;font-size:11px;font-weight:700;margin-right:6px">Pertemuan ke-<?php echo e($j->pertemuan_ke); ?></span>
                        <?php endif; ?>
                        <?php echo e($tgl->locale('id')->isoFormat('dddd, D MMMM Y')); ?>

                        <?php if($isToday): ?>
                        <span class="badge ms-2" style="background:var(--soft-success-bg);color:var(--soft-success-text);font-size:10px;border-radius:6px;padding:2px 8px">Hari Ini</span>
                        <?php endif; ?>
                    </div>
                    <div class="text-muted" style="font-size:12px">
                        <i class="bi bi-clock me-1"></i><?php echo e(\Carbon\Carbon::parse($j->jam_mulai)->format('H:i')); ?> – <?php echo e(\Carbon\Carbon::parse($j->jam_selesai)->format('H:i')); ?>

                        <?php if($j->jenis): ?> · <span class="text-capitalize"><?php echo e($j->jenis); ?></span> <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <?php if($isToday): ?>
                <span class="badge" style="background:var(--soft-success-bg);color:var(--soft-success-text);font-size:11px;padding:4px 10px;border-radius:7px">
                    <i class="bi bi-broadcast me-1"></i>Berlangsung
                </span>
                <?php elseif($isPast): ?>
                <span class="badge" style="background:var(--input-bg);color:var(--text-muted);border:1px solid var(--card-border);font-size:11px;padding:4px 10px;border-radius:7px">
                    <i class="bi bi-check2 me-1"></i>Selesai
                </span>
                <?php else: ?>
                <span class="badge" style="background:var(--soft-info-bg);color:var(--soft-info-text);font-size:11px;padding:4px 10px;border-radius:7px">
                    <i class="bi bi-clock me-1"></i>Akan Datang
                </span>
                <?php endif; ?>
                <button class="btn btn-primary btn-sm" onclick="toggleAttendance(<?php echo e($j->id); ?>)"
                        id="btn-abs-<?php echo e($j->id); ?>"
                        style="border-radius:9px;font-size:12.5px;padding:6px 14px">
                    <i class="bi bi-clipboard2-check me-1"></i>Absensi
                </button>
            </div>
        </div>

        
        <div id="attendance-area-<?php echo e($j->id); ?>" class="mt-0"></div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php endif; ?>

<?php $__env->startPush('scripts'); ?>
<script>
function toggleAttendance(id) {
    const area = document.getElementById('attendance-area-' + id);
    const btn  = document.getElementById('btn-abs-' + id);
    if (!area) return;
    if (area.innerHTML.trim() !== '') {
        area.innerHTML = '';
        if (btn) { btn.innerHTML = '<i class="bi bi-clipboard2-check me-1"></i>Absensi'; btn.classList.remove('btn-secondary'); btn.classList.add('btn-primary'); }
        return;
    }
    if (btn) { btn.innerHTML = '<i class="bi bi-x me-1"></i>Tutup'; btn.classList.remove('btn-primary'); btn.classList.add('btn-secondary'); }
    loadAttendance(id, area);
}

function loadAttendance(id, areaEl) {
    const area = areaEl || document.getElementById('attendance-area-' + id);
    if (!area) return;
    area.innerHTML = `<div class="mt-3 text-center py-4"><div class="spinner-border spinner-border-sm text-primary"></div> <span class="text-muted ms-2" style="font-size:13px">Memuat siswa...</span></div>`;

    fetch('/guru/attendance/' + id + '/students', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => { if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
        .then(res => {
            if (!res.success) { area.innerHTML = '<div class="mt-3 text-muted">Gagal memuat data siswa.</div>'; return; }
            const students = res.students;
            const existing = res.existing || {};

            if (!students.length) {
                area.innerHTML = `<div class="mt-3 p-3 rounded-3 text-center" style="background:var(--input-bg)">
                    <i class="bi bi-people text-muted d-block mb-2" style="font-size:1.5rem;opacity:.4"></i>
                    <p class="text-muted mb-0" style="font-size:13px">Belum ada siswa terdaftar di kelas ini.</p>
                </div>`;
                return;
            }

            // Status label helper
            function statusLabel(guruHadir, siswaKonfirmasi) {
                if (guruHadir && siswaKonfirmasi)  return {label:'Hadir',         clr:'#10b981', bg:'var(--soft-success-bg)'};
                if (guruHadir && !siswaKonfirmasi) return {label:'Menunggu Konfirmasi', clr:'#f6af23', bg:'var(--soft-warning-bg)'};
                if (!guruHadir && siswaKonfirmasi) return {label:'Tidak Valid',   clr:'var(--text-muted)', bg:'var(--soft-muted-bg)'};
                return                                    {label:'Tidak Hadir',    clr:'#ef4444', bg:'var(--soft-danger-bg)'};
            }

            let rows = students.map((s, i) => {
                const rec   = existing[s.id] || {};
                const checked = rec.guru_hadir ? 'checked' : '';
                const avatar = `https://ui-avatars.com/api/?name=${encodeURIComponent(s.name)}&background=68117e&color=fff&size=40`;
                const sl = statusLabel(rec.guru_hadir, rec.siswa_konfirmasi_at);
                return `<tr id="row-${s.id}">
                    <td style="white-space:nowrap;padding:10px 12px">
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-muted" style="font-size:12px;min-width:22px">${i + 1}</span>
                            <img src="${s.photo ? '/storage/' + s.photo : avatar}" class="rounded-circle flex-shrink-0" width="32" height="32" style="object-fit:cover">
                            <span class="fw-semibold" style="font-size:13px">${s.name}</span>
                        </div>
                    </td>
                    <td class="text-center" style="min-width:80px">
                        <label style="display:flex;align-items:center;justify-content:center;gap:6px;cursor:pointer">
                            <input type="checkbox" name="hadir_ids[]" value="${s.id}" ${checked}
                                   class="abs-check" data-sid="${s.id}"
                                   style="width:18px;height:18px;accent-color:#10b981;cursor:pointer">
                            <span style="font-size:11px;color:#10b981;font-weight:600">Hadir</span>
                        </label>
                    </td>
                    <td id="status-${s.id}">
                        <span style="background:${sl.bg};color:${sl.clr};padding:3px 10px;border-radius:7px;font-size:12px;font-weight:600">${sl.label}</span>
                    </td>
                    <td style="font-size:11px;color:var(--text-muted)">
                        ${rec.siswa_konfirmasi_at
                            ? '<i class="bi bi-check2-circle text-success me-1"></i>Dikonfirmasi siswa'
                            : '<span style="opacity:.5">Menunggu siswa</span>'}
                    </td>
                </tr>`;
            }).join('');

            area.innerHTML = `
                <div class="mt-3 p-3 rounded-3" style="background:var(--input-bg);border:1px solid var(--card-border)">
                    <div class="d-flex align-items-center gap-2 mb-3 p-2 rounded-2" style="background:rgba(200,77,223,.07)">
                        <i class="bi bi-exclamation-circle-fill text-primary"></i>
                        <span style="font-size:12px">Centang siswa yang <strong>hadir</strong>. Setelah disimpan, siswa yang ditandai hadir akan melihat tombol konfirmasi di dashboard mereka.</span>
                    </div>
                    <form class="absForm">
                        <input type="hidden" name="jadwal_id" value="${id}">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-3" style="font-size:13px">
                                <thead class="thead-modern">
                                    <tr>
                                        <th class="small text-muted fw-semibold py-2">SISWA</th>
                                        <th class="text-center small text-muted fw-semibold py-2" style="color:#10b981!important">HADIR</th>
                                        <th class="small text-muted fw-semibold py-2">STATUS ABSENSI</th>
                                        <th class="small text-muted fw-semibold py-2">KONFIRMASI SISWA</th>
                                    </tr>
                                </thead>
                                <tbody>${rows}</tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div id="absCount-${id}" class="text-muted" style="font-size:12px"></div>
                            <button type="submit" class="btn btn-primary" style="border-radius:10px">
                                <i class="bi bi-save me-2"></i>Simpan Absensi
                            </button>
                        </div>
                    </form>
                </div>`;

            updateCount(id, students, existing);

            area.querySelectorAll('.abs-check').forEach(r => r.addEventListener('change', () => {
                const cur = {};
                students.forEach(s => {
                    const ch = area.querySelector(`input[value="${s.id}"].abs-check`);
                    if (ch) cur[s.id] = { guru_hadir: ch.checked };
                });
                updateCount(id, students, cur);
            }));

            area.querySelector('.absForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const hadirIds = [];
                area.querySelectorAll('.abs-check:checked').forEach(ch => hadirIds.push(parseInt(ch.value)));

                const submitBtn = area.querySelector('button[type=submit]');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';

                fetch('<?php echo e(route("guru.attendance.store")); ?>', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ jadwal_id: id, hadir_ids: hadirIds })
                }).then(r => { if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
                .then(d => {
                    if (d.success) {
                        showToast(d.message || 'Absensi berhasil disimpan!', 'success');
                        setTimeout(() => loadAttendance(id, area), 600);
                    } else {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="bi bi-save me-2"></i>Simpan Absensi';
                        showToast(d.message || 'Gagal menyimpan', 'error');
                    }
                }).catch(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="bi bi-save me-2"></i>Simpan Absensi';
                    showToast('Terjadi kesalahan. Coba lagi.', 'error');
                });
            });
        })
        .catch(() => { area.innerHTML = '<div class="mt-3 text-muted p-3">Gagal memuat data. Coba lagi.</div>'; });
}

function updateCount(id, students, existing) {
    const el = document.getElementById('absCount-' + id);
    if (!el) return;
    const filled = students.filter(s => existing[s.id]?.guru_hadir).length;
    el.textContent = `${filled} dari ${students.length} siswa ditandai hadir`;
}

document.getElementById('jadwalSelect').addEventListener('change', function() {
    const id = this.value;
    if (!id) return;
    const card = document.getElementById('jadwal-card-' + id);
    const area = document.getElementById('attendance-area-' + id);
    if (card) { card.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
    if (area && area.innerHTML.trim() === '') toggleAttendance(parseInt(id));
});
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/guru/classes/attendance.blade.php ENDPATH**/ ?>