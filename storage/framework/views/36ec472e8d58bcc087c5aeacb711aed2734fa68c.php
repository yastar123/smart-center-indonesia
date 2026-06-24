<?php $__env->startSection('title','Edit Jadwal Sesi'); ?>
<?php $__env->startSection('page-title','Edit Jadwal Sesi'); ?>

<?php $__env->startSection('content'); ?>
<div class="fade-up">

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.schedules.index')); ?>">Jadwal Sesi</a></li>
        <li class="breadcrumb-item active">Edit Jadwal</li>
    </ol>
</nav>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="dashboard-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h5 class="fw-bold mb-1"><i class="bi bi-pencil-square me-2 text-primary"></i>Edit Jadwal Sesi</h5>
                    <div class="text-muted small">Perbarui jadwal: <strong><?php echo e($schedule->paket?->nama ?? '—'); ?></strong> — Sesi ke-<?php echo e($schedule->pertemuan_ke); ?></div>
                </div>
                <a href="<?php echo e(route('admin.schedules.index')); ?>" class="btn btn-outline-secondary" style="border-radius:10px">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
            </div>

            <form action="<?php echo e(route('admin.schedules.update', $schedule)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <?php if($errors->any()): ?>
                <div class="alert alert-danger mb-3">
                    <ul class="mb-0 ps-3"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
                </div>
                <?php endif; ?>

                <div class="row g-3">
                    
                    <div class="col-12">
                        <label class="form-label fw-semibold">Paket Belajar <span class="text-danger">*</span></label>
                        <select name="paket_id" id="paket_id" class="form-select" required onchange="onPaketChange(this.value)">
                            <option value="">— Pilih Paket —</option>
                            <?php $__currentLoopData = $pakets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($p->id); ?>" <?php echo e(old('paket_id', $schedule->paket_id) == $p->id ? 'selected' : ''); ?>>
                                <?php echo e($p->nama); ?>

                                <?php if($p->guru): ?> — <?php echo e($p->guru->name); ?><?php endif; ?>
                                <?php if($p->cabang): ?> (<?php echo e($p->cabang->name); ?>)<?php endif; ?>
                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    
                    <div class="col-12" id="paketDetailBox" style="display:none">
                        <div class="p-3 rounded-3" style="background:var(--soft-primary-bg);border:1.5px solid var(--soft-primary-border)">
                            <div class="fw-semibold mb-2" style="color:var(--soft-primary-text)"><i class="bi bi-box-seam me-2"></i>Detail Paket</div>
                            <div class="row g-2 small" id="paketDetailContent"></div>
                        </div>
                    </div>

                    
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Sesi Ke- <span class="text-danger">*</span></label>
                        <select name="pertemuan_ke" id="pertemuan_ke" class="form-select" required>
                            <option value="">— Pilih dulu paket —</option>
                        </select>
                    </div>

                    
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal" id="tanggal" class="form-control"
                               value="<?php echo e(old('tanggal', $schedule->tanggal?->format('Y-m-d'))); ?>" required>
                    </div>

                    
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Jam Mulai <span class="text-danger">*</span></label>
                        <input type="time" name="jam_mulai" class="form-control"
                               value="<?php echo e(old('jam_mulai', substr($schedule->jam_mulai ?? '', 0, 5))); ?>" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Jam Selesai <span class="text-danger">*</span></label>
                        <input type="time" name="jam_selesai" class="form-control"
                               value="<?php echo e(old('jam_selesai', substr($schedule->jam_selesai ?? '', 0, 5))); ?>" required>
                    </div>

                    
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <?php $__currentLoopData = ['dijadwalkan'=>'Dijadwalkan','berlangsung'=>'Berlangsung','selesai'=>'Selesai','dibatalkan'=>'Dibatalkan']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $lbl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($val); ?>" <?php echo e(old('status', $schedule->status) == $val ? 'selected' : ''); ?>><?php echo e($lbl); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Topik / Materi</label>
                        <input type="text" name="topik" class="form-control"
                               value="<?php echo e(old('topik', $schedule->topik)); ?>" placeholder="Contoh: Persamaan Kuadrat, Past Tense, dll">
                    </div>

                    
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Ruangan</label>
                        <input type="text" name="ruangan" class="form-control"
                               value="<?php echo e(old('ruangan', $schedule->ruangan)); ?>" placeholder="Opsional">
                    </div>

                    
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Link Meeting</label>
                        <input type="url" name="link_meeting" class="form-control"
                               value="<?php echo e(old('link_meeting', $schedule->link_meeting)); ?>" placeholder="https://zoom.us/...">
                    </div>

                    
                    <div class="col-12">
                        <label class="form-label fw-semibold">Catatan</label>
                        <textarea name="catatan" class="form-control" rows="2"><?php echo e(old('catatan', $schedule->catatan)); ?></textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                    <a href="<?php echo e(route('admin.schedules.index')); ?>" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary px-4 fw-semibold">
                        <i class="bi bi-check-lg me-1"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="dashboard-card" id="paketInfoSidebar">
            <h6 class="fw-bold mb-3"><i class="bi bi-info-circle me-2 text-primary"></i>Info Paket Terpilih</h6>
            <div id="paketInfoSidebarContent">
                <?php if($schedule->paket): ?>
                <div class="mb-2 p-2 rounded" style="background:var(--input-bg)"><span class="text-muted small">Nama Paket</span><div class="fw-semibold"><?php echo e($schedule->paket->nama); ?></div></div>
                <div class="mb-2 p-2 rounded" style="background:var(--input-bg)"><span class="text-muted small">Guru Pengajar</span><div class="fw-semibold"><?php echo e($schedule->paket->guru?->name ?? '—'); ?></div></div>
                <div class="mb-2 p-2 rounded" style="background:var(--input-bg)"><span class="text-muted small">Cabang</span><div class="fw-semibold"><?php echo e($schedule->paket->cabang?->name ?? 'Pusat'); ?></div></div>
                <div class="mb-2 p-2 rounded" style="background:var(--input-bg)"><span class="text-muted small">Jenis Paket</span><div class="fw-semibold"><?php echo e($schedule->paket->jenis ?? '—'); ?></div></div>
                <div class="mb-2 p-2 rounded" style="background:var(--input-bg)"><span class="text-muted small">Total Sesi</span><div class="fw-bold text-primary" style="font-size:1.3rem"><?php echo e($schedule->paket->jumlah_pertemuan ?? '—'); ?></div></div>
                <div class="mb-2 p-2 rounded" style="background:var(--input-bg)"><span class="text-muted small">Mata Pelajaran</span><div class="fw-semibold"><?php echo e($schedule->paket->mataPelajaran->pluck('nama')->join(', ') ?: '—'); ?></div></div>
                <div class="mb-2 p-2 rounded" style="background:var(--input-bg)"><span class="text-muted small">Harga Dasar</span><div class="fw-semibold">Rp <?php echo e(number_format($schedule->paket->harga ?? 0,0,',','.')); ?></div></div>
                <?php else: ?>
                <div class="text-muted small text-center py-3">Pilih paket untuk melihat detail</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

</div>
<?php $__env->stopSection(); ?>

<?php
$paketsJson = $pakets->map(function ($p) {
    return [
        'id' => $p->id,
        'nama' => $p->nama,
        'jenis' => $p->jenis,
        'jumlah_pertemuan' => $p->jumlah_pertemuan,
        'metode_absensi' => $p->metode_absensi,
        'tipe_kelas' => $p->tipe_kelas,
        'harga' => $p->harga,
        'deskripsi' => $p->deskripsi,
        'status' => $p->status,
        'cabang' => $p->cabang?->name,
        'guru' => $p->guru?->name,
        'mata_pelajaran' => $p->mataPelajaran->pluck('nama'),
    ];
});
?>

<?php $__env->startPush('scripts'); ?>
<script>
const pakets = <?php echo json_encode($paketsJson, 15, 512) ?>;

const currentSesi = <?php echo e($schedule->pertemuan_ke ?? 'null'); ?>;

function onPaketChange(paketId, sesiKe) {
    const detailBox = document.getElementById('paketDetailBox');
    const detailContent = document.getElementById('paketDetailContent');
    const sesiSelect = document.getElementById('pertemuan_ke');
    const sidebarContent = document.getElementById('paketInfoSidebarContent');

    if (!paketId) {
        detailBox.style.display = 'none';
        sesiSelect.innerHTML = '<option value="">— Pilih dulu paket —</option>';
        return;
    }

    const pkg = pakets.find(p => p.id == paketId);
    if (!pkg) return;

    detailBox.style.display = 'block';
    detailContent.innerHTML = `
        <div class="col-6"><strong>Nama:</strong> ${pkg.nama || '—'}</div>
        <div class="col-6"><strong>Jenis:</strong> ${pkg.jenis || '—'}</div>
        <div class="col-6"><strong>Jumlah Sesi:</strong> ${pkg.jumlah_pertemuan || '—'}</div>
        <div class="col-6"><strong>Metode Absensi:</strong> ${pkg.metode_absensi || '—'}</div>
        <div class="col-6"><strong>Tipe Kelas:</strong> ${pkg.tipe_kelas || '—'}</div>
        <div class="col-6"><strong>Harga:</strong> Rp ${pkg.harga ? parseInt(pkg.harga).toLocaleString('id-ID') : '—'}</div>
        <div class="col-6"><strong>Cabang:</strong> ${pkg.cabang || 'Pusat'}</div>
        <div class="col-6"><strong>Guru:</strong> ${pkg.guru || '—'}</div>
        <div class="col-12"><strong>Mata Pelajaran:</strong> ${pkg.mata_pelajaran?.join(', ') || '—'}</div>
    `;

    sidebarContent.innerHTML = `
        <div class="mb-2 p-2 rounded" style="background:var(--input-bg)"><span class="text-muted small">Guru Pengajar</span><div class="fw-semibold">${pkg.guru || '—'}</div></div>
        <div class="mb-2 p-2 rounded" style="background:var(--input-bg)"><span class="text-muted small">Cabang</span><div class="fw-semibold">${pkg.cabang || 'Pusat'}</div></div>
        <div class="mb-2 p-2 rounded" style="background:var(--input-bg)"><span class="text-muted small">Jenis Paket</span><div class="fw-semibold">${pkg.jenis || '—'}</div></div>
        <div class="mb-2 p-2 rounded" style="background:var(--input-bg)"><span class="text-muted small">Total Sesi</span><div class="fw-bold text-primary" style="font-size:1.3rem">${pkg.jumlah_pertemuan || '—'}</div></div>
        <div class="mb-2 p-2 rounded" style="background:var(--input-bg)"><span class="text-muted small">Harga Dasar</span><div class="fw-semibold">Rp ${pkg.harga ? parseInt(pkg.harga).toLocaleString('id-ID') : '—'}</div></div>
        <div class="mb-2 p-2 rounded" style="background:var(--input-bg)"><span class="text-muted small">Mata Pelajaran</span><div class="fw-semibold">${pkg.mata_pelajaran?.join(', ') || '—'}</div></div>
    `;

    const total = parseInt(pkg.jumlah_pertemuan) || 0;
    const selected = sesiKe ?? currentSesi;
    let opts = '<option value="">— Pilih Sesi —</option>';
    for (let i = 1; i <= total; i++) {
        opts += `<option value="${i}" ${i == selected ? 'selected' : ''}>Sesi ke-${i}</option>`;
    }
    sesiSelect.innerHTML = opts;
}

// Init on page load with current paket_id
const initPaket = document.getElementById('paket_id').value;
if (initPaket) onPaketChange(initPaket);
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/admin/schedules/edit.blade.php ENDPATH**/ ?>