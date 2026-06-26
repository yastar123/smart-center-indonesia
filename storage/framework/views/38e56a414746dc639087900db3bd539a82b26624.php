<?php $__env->startSection('title','Tambah Jadwal Sesi'); ?>
<?php $__env->startSection('page-title','Tambah Jadwal Sesi'); ?>

<?php $__env->startSection('content'); ?>
<div class="fade-up">

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.schedules.index')); ?>">Jadwal Sesi</a></li>
        <li class="breadcrumb-item active">Tambah Jadwal</li>
    </ol>
</nav>


<div class="dashboard-card mb-4" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-20px;top:-20px;width:160px;height:160px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div class="d-flex align-items-center justify-content-between" style="position:relative">
        <div class="d-flex align-items-center gap-3">
            <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0">
                <i class="bi bi-calendar-plus"></i>
            </div>
            <div>
                <h5 class="fw-bold mb-0" style="color:white">Tambah Jadwal Sesi</h5>
                <div style="font-size:12px;opacity:.8">Buat jadwal sesi baru untuk paket belajar</div>
            </div>
        </div>
        <a href="<?php echo e(route('admin.schedules.index')); ?>" class="btn btn-sm"
           style="background:rgba(255,255,255,.15);color:white;border:1px solid rgba(255,255,255,.3)">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>
</div>

<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show mb-4"><i class="bi bi-check-circle me-2"></i><?php echo e(session('success')); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>


<div class="dashboard-card mb-4 py-3">
    <div class="d-flex align-items-center justify-content-center gap-0">
        <?php
        $steps = [
            ['num'=>1,'label'=>'Pilih Paket','icon'=>'bi-box-seam'],
            ['num'=>2,'label'=>'Mata Pelajaran','icon'=>'bi-book'],
            ['num'=>3,'label'=>'Guru Pengajar','icon'=>'bi-person-badge'],
            ['num'=>4,'label'=>'Waktu & Detail','icon'=>'bi-clock'],
        ];
        ?>
        <?php $__currentLoopData = $steps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="d-flex align-items-center">
            <div id="step-indicator-<?php echo e($step['num']); ?>" class="d-flex flex-column align-items-center" style="min-width:80px">
                <div style="width:36px;height:36px;border-radius:50%;background:var(--input-bg);border:2px solid var(--card-border);display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:700;color:var(--text-muted);transition:.3s" id="step-circle-<?php echo e($step['num']); ?>">
                    <?php echo e($step['num']); ?>

                </div>
                <div style="font-size:11px;color:var(--text-muted);margin-top:4px;text-align:center;font-weight:500" id="step-label-<?php echo e($step['num']); ?>"><?php echo e($step['label']); ?></div>
            </div>
            <?php if(!$loop->last): ?>
            <div style="width:40px;height:2px;background:var(--card-border);margin-bottom:20px;flex-shrink:0" id="step-line-<?php echo e($step['num']); ?>"></div>
            <?php endif; ?>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>

<form action="<?php echo e(route('admin.schedules.store')); ?>" method="POST" id="scheduleForm">
<?php echo csrf_field(); ?>
<?php if($errors->any()): ?>
<div class="alert alert-danger mb-4">
    <ul class="mb-0 ps-3"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
</div>
<?php endif; ?>


<div class="dashboard-card mb-4" id="section-paket">
    <div class="d-flex align-items-center gap-2 mb-4 pb-3 border-bottom">
        <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#c84ddf,#461256);display:flex;align-items:center;justify-content:center;color:white;font-size:14px;flex-shrink:0">
            <i class="bi bi-box-seam"></i>
        </div>
        <div>
            <div class="fw-bold" style="font-size:15px">1. Pilih Paket Belajar <span class="text-danger">*</span></div>
            <div class="text-muted" style="font-size:12px">Paket yang menentukan mata pelajaran, sesi, dan siswa terdaftar</div>
        </div>
    </div>

    <div class="row g-3">
        
        <div class="col-lg-8">
            <label class="form-label fw-semibold">Paket Belajar <span class="text-danger">*</span></label>
            <select name="paket_id" id="paket_id" class="form-select" required onchange="onPaketChange(this.value)">
                <option value="">— Pilih Paket —</option>
                <?php $__currentLoopData = $pakets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($p->id); ?>" <?php echo e(old('paket_id') == $p->id ? 'selected' : ''); ?>>
                    <?php echo e($p->nama); ?><?php echo e($p->cabang ? ' ('.$p->cabang->name.')' : ''); ?>

                </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        
        <div class="col-lg-4">
            <label class="form-label fw-semibold">Sesi Ke- <span class="text-danger">*</span></label>
            <select name="pertemuan_ke" id="pertemuan_ke" class="form-select" required onchange="onSesiChange(this.value)">
                <option value="">— Pilih paket dulu —</option>
            </select>
            <div id="sesiWarning"></div>
        </div>

        
        <div class="col-12" id="sesiProgressBar" style="display:none"></div>

        
        <div class="col-12" id="paketDetailBox" style="display:none">
            <div class="p-3 rounded-3" style="background:var(--soft-primary-bg);border:1.5px solid var(--soft-primary-border)">
                <div class="fw-semibold mb-2" style="color:var(--soft-primary-text);font-size:13px"><i class="bi bi-info-circle me-2"></i>Detail Paket Terpilih</div>
                <div class="row g-2" id="paketDetailContent" style="font-size:12px"></div>
            </div>
        </div>

        
        <div class="col-12" id="daftarSiswaPaketBox" style="display:none">
            <div class="p-3 rounded-3" style="background:var(--input-bg);border:1px solid var(--card-border)">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="fw-semibold" style="font-size:13px">
                        <i class="bi bi-people-fill text-primary me-2"></i>
                        Siswa Terdaftar di Paket Ini
                        <span id="siswaPaketCount" class="badge ms-1" style="background:var(--soft-primary);color:#461256;font-size:11px"></span>
                    </div>
                </div>
                <div id="daftarSiswaPaketContent" class="d-flex flex-wrap gap-2">
                    <span class="text-muted" style="font-size:12px">Memuat siswa...</span>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="dashboard-card mb-4" id="section-mapel" style="display:none">
    <div class="d-flex align-items-center gap-2 mb-4 pb-3 border-bottom">
        <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#8b5cf6,#6d28d9);display:flex;align-items:center;justify-content:center;color:white;font-size:14px;flex-shrink:0">
            <i class="bi bi-book"></i>
        </div>
        <div>
            <div class="fw-bold" style="font-size:15px">2. Mata Pelajaran Sesi Ini <span class="text-danger">*</span></div>
            <div class="text-muted" style="font-size:12px">Pilih satu mata pelajaran dari paket yang diajarkan pada sesi ini</div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-8">
            <label class="form-label fw-semibold">Mata Pelajaran <span class="text-danger">*</span></label>
            <select name="mata_pelajaran_id" id="mata_pelajaran_id" class="form-select" required onchange="onMapelChange(this.value)">
                <option value="">— Pilih mata pelajaran dari paket —</option>
            </select>
            <div class="form-text">Mata pelajaran yang akan diajarkan di sesi ini. Daftar diambil dari paket yang dipilih.</div>
        </div>
        <div class="col-lg-4">
            <div class="p-3 rounded-3 h-100" style="background:var(--input-bg);border:1px solid var(--card-border);min-height:60px" id="mapelInfoBox">
                <div class="text-muted" style="font-size:12px">Pilih mata pelajaran untuk melihat info</div>
            </div>
        </div>
    </div>
</div>


<div class="dashboard-card mb-4" id="section-guru" style="display:none">
    <div class="d-flex align-items-center gap-2 mb-4 pb-3 border-bottom">
        <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#10b981,#047857);display:flex;align-items:center;justify-content:center;color:white;font-size:14px;flex-shrink:0">
            <i class="bi bi-person-badge"></i>
        </div>
        <div>
            <div class="fw-bold" style="font-size:15px">3. Guru Pengajar <span class="text-danger">*</span></div>
            <div class="text-muted" style="font-size:12px">Guru yang mengajarkan mata pelajaran ini pada sesi ini</div>
        </div>
    </div>
    <div class="row g-3">
        <div class="col-lg-6">
            <label class="form-label fw-semibold">Guru Pengajar <span class="text-danger">*</span></label>
            <select name="guru_id" id="guru_id" class="form-select" required onchange="onGuruChange(this.value)">
                <option value="">— Pilih Guru —</option>
            </select>
            <div class="form-text" id="guruFilterNote">Menampilkan semua guru aktif. Pilih mata pelajaran terlebih dahulu untuk menyaring guru sesuai keahlian.</div>
        </div>
        <div class="col-lg-6">
            <div class="p-3 rounded-3 h-100" style="background:var(--input-bg);border:1px solid var(--card-border)" id="guruInfoBox">
                <div class="text-muted" style="font-size:12px">Pilih guru untuk melihat informasinya</div>
            </div>
        </div>
    </div>
</div>


<div class="dashboard-card mb-4" id="section-waktu" style="display:none">
    <div class="d-flex align-items-center gap-2 mb-4 pb-3 border-bottom">
        <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#0ea5e9,#0369a1);display:flex;align-items:center;justify-content:center;color:white;font-size:14px;flex-shrink:0">
            <i class="bi bi-clock"></i>
        </div>
        <div>
            <div class="fw-bold" style="font-size:15px">4. Waktu & Detail Sesi</div>
            <div class="text-muted" style="font-size:12px">Tanggal, jam, metode, dan info tambahan</div>
        </div>
    </div>
    <div class="row g-3">
        <div class="col-md-3">
            <label class="form-label fw-semibold">Metode Kelas <span class="text-danger">*</span></label>
            <select name="jenis" id="jenis" class="form-select" required>
                <option value="offline" <?php echo e(old('jenis','offline') == 'offline' ? 'selected' : ''); ?>>🏫 Offline (Tatap Muka)</option>
                <option value="online"  <?php echo e(old('jenis') == 'online'  ? 'selected' : ''); ?>>💻 Online</option>
                <option value="private" <?php echo e(old('jenis') == 'private' ? 'selected' : ''); ?>>👤 Private</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold">Tanggal <span class="text-danger">*</span></label>
            <input type="date" name="tanggal" id="tanggal" class="form-control" value="<?php echo e(old('tanggal', date('Y-m-d'))); ?>" required>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold">Jam Mulai <span class="text-danger">*</span></label>
            <input type="time" name="jam_mulai" id="jam_mulai" class="form-control" value="<?php echo e(old('jam_mulai')); ?>" required>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold">Jam Selesai <span class="text-danger">*</span></label>
            <input type="time" name="jam_selesai" id="jam_selesai" class="form-control" value="<?php echo e(old('jam_selesai')); ?>" required>
        </div>
        <div class="col-12">
            <label class="form-label fw-semibold">Topik / Materi <span class="text-muted fw-normal">(opsional)</span></label>
            <input type="text" name="topik" class="form-control" value="<?php echo e(old('topik')); ?>" placeholder="Contoh: Persamaan Kuadrat, Past Tense, dll">
        </div>
    </div>

    
    <div class="mt-3 pt-3 border-top">
        <div class="fw-semibold mb-1" style="font-size:13px"><i class="bi bi-building me-1 text-muted"></i>Kelas <span class="text-muted fw-normal">(opsional — untuk absensi berbasis kelas)</span></div>
        <div class="row g-3">
            <div class="col-lg-8">
                <select name="kelas_id" id="kelas_id" class="form-select">
                    <option value="">— Tidak terhubung ke kelas tertentu —</option>
                    <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($c->id); ?>" <?php echo e(old('kelas_id') == $c->id ? 'selected' : ''); ?>>
                        <?php echo e($c->nama_kelas); ?><?php echo e($c->mataPelajaran ? ' — '.$c->mataPelajaran->nama : ''); ?><?php echo e($c->cabang ? ' ('.$c->cabang->name.')' : ''); ?>

                    </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <div class="form-text">Pilih kelas agar jadwal ini muncul di halaman absensi guru untuk kelas tersebut.</div>
            </div>
        </div>
    </div>

    
    <div class="mt-3 pt-3 border-top">
        <div class="fw-semibold mb-1" style="font-size:13px"><i class="bi bi-journals me-1 text-muted"></i>Modul Belajar <span class="text-muted fw-normal">(opsional)</span></div>
        <div class="row g-3">
            <div class="col-lg-8">
                <select name="module_id" id="module_id" class="form-select" onchange="onModuleChange(this.value)">
                    <option value="">— Tidak ada modul —</option>
                    <?php $__currentLoopData = $modules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($m->id); ?>" <?php echo e(old('module_id') == $m->id ? 'selected' : ''); ?>>
                        <?php echo e($m->judul); ?><?php if($m->mataPelajaran): ?> – <?php echo e($m->mataPelajaran->nama); ?><?php endif; ?>
                        <?php if($m->kode_modul): ?> [<?php echo e($m->kode_modul); ?>]<?php endif; ?>
                    </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-lg-4">
                <div class="p-2 rounded-2" style="background:var(--input-bg);border:1px solid var(--card-border);min-height:40px;font-size:12px" id="moduleInfoBox">
                    <span class="text-muted">Tidak ada modul dipilih</span>
                </div>
            </div>
        </div>
    </div>

    
    <div class="mt-3 pt-3 border-top">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">Ruangan <span class="text-muted fw-normal">(opsional)</span></label>
                <div class="input-group">
                    <span class="input-group-text" style="background:var(--input-bg);border-color:var(--card-border)"><i class="bi bi-door-open text-muted"></i></span>
                    <input type="text" name="ruangan" class="form-control" value="<?php echo e(old('ruangan')); ?>" placeholder="misal: Ruang A1">
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Link Meeting <span class="text-muted fw-normal">(opsional)</span></label>
                <div class="input-group">
                    <span class="input-group-text" style="background:var(--input-bg);border-color:var(--card-border)"><i class="bi bi-camera-video text-muted"></i></span>
                    <input type="url" name="link_meeting" class="form-control" value="<?php echo e(old('link_meeting')); ?>" placeholder="https://zoom.us/...">
                </div>
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold">Catatan <span class="text-muted fw-normal">(opsional)</span></label>
                <textarea name="catatan" class="form-control" rows="2" placeholder="Catatan tambahan"><?php echo e(old('catatan')); ?></textarea>
            </div>
        </div>
    </div>
</div>


<div class="dashboard-card" id="section-submit" style="display:none">
    <div class="d-flex justify-content-between align-items-center">
        <div class="text-muted" style="font-size:13px"><i class="bi bi-info-circle me-1"></i>Field bertanda <span class="text-danger">*</span> wajib diisi</div>
        <div class="d-flex gap-2">
            <a href="<?php echo e(route('admin.schedules.index')); ?>" class="btn btn-outline-secondary px-4">Batal</a>
            <button type="submit" id="submitBtn" class="btn btn-primary px-5 fw-semibold">
                <i class="bi bi-calendar-check me-2"></i>Simpan Jadwal
            </button>
        </div>
    </div>
</div>

</form>
</div>
<?php $__env->stopSection(); ?>

<?php
$paketsJson = $pakets->map(function ($p) {
    return [
        'id'               => $p->id,
        'nama'             => $p->nama,
        'jenis'            => $p->jenis,
        'jumlah_pertemuan' => $p->jumlah_pertemuan,
        'tipe_kelas'       => $p->tipe_kelas,
        'harga'            => $p->harga,
        'deskripsi'        => $p->deskripsi,
        'cabang'           => $p->cabang?->name,
        'cabang_id'        => $p->cabang_id,
        'guru_id'          => $p->guru_id,
        'guru_name'        => $p->guru?->name,
        // subjects with ID for the dropdown
        'mata_pelajaran'   => $p->mataPelajaran->map(fn($m) => ['id' => $m->id, 'nama' => $m->nama, 'kategori' => $m->kategori ?? '']),
        // course_id => [teacher_id, ...] mapping from package_course_teachers
        'course_teachers'  => $p->courseTeachers->groupBy('course_id')->map(fn($ct) => $ct->pluck('teacher_id')->values()),
    ];
});

$teachersJson = $teachers->map(function ($t) {
    return [
        'id'         => $t->id,
        'name'       => $t->name,
        'branch'     => $t->branch?->name,
        'branch_id'  => $t->branch_id,
        'nig'        => $t->nig,
        'email'      => $t->email,
        // course IDs this teacher is linked to
        'course_ids' => $t->courses->pluck('id')->values()->toArray(),
    ];
});

$modulesJson = $modules->map(function ($m) {
    return [
        'id'             => $m->id,
        'judul'          => $m->judul,
        'kode_modul'     => $m->kode_modul,
        'mata_pelajaran' => $m->mataPelajaran?->nama,
    ];
});
?>

<?php $__env->startPush('scripts'); ?>
<script>
const pakets   = <?php echo json_encode($paketsJson, 15, 512) ?>;
const teachers = <?php echo json_encode($teachersJson, 15, 512) ?>;
const modules  = <?php echo json_encode($modulesJson, 15, 512) ?>;
const packageStudentsBaseUrl = '/admin/schedules/package';
let usedSessionsCache = {};
let currentPaket = null;
let currentMapelId = null;

// ── Step indicator helpers ──────────────────────────────────────────────────
function activateStep(n) {
    for (let i = 1; i <= 4; i++) {
        const circle = document.getElementById('step-circle-' + i);
        const label  = document.getElementById('step-label-' + i);
        const line   = document.getElementById('step-line-' + i);
        if (!circle) continue;
        if (i < n) {
            circle.style.background = 'linear-gradient(135deg,#10b981,#047857)';
            circle.style.color      = 'white';
            circle.style.border     = '2px solid #10b981';
            circle.innerHTML        = '<i class="bi bi-check-lg"></i>';
            label.style.color       = '#10b981';
        } else if (i === n) {
            circle.style.background = 'linear-gradient(135deg,#c84ddf,#461256)';
            circle.style.color      = 'white';
            circle.style.border     = '2px solid #c84ddf';
            circle.innerHTML        = i;
            label.style.color       = '#c84ddf';
        } else {
            circle.style.background = 'var(--input-bg)';
            circle.style.color      = 'var(--text-muted)';
            circle.style.border     = '2px solid var(--card-border)';
            circle.innerHTML        = i;
            label.style.color       = 'var(--text-muted)';
        }
        if (line) {
            line.style.background = i < n ? '#10b981' : 'var(--card-border)';
        }
    }
}

function showSections(...ids) {
    ['section-mapel','section-guru','section-waktu','section-submit'].forEach(id => {
        document.getElementById(id).style.display = ids.includes(id) ? '' : 'none';
    });
}

// ── Paket change ────────────────────────────────────────────────────────────
function onPaketChange(paketId) {
    currentPaket    = null;
    currentMapelId  = null;

    // Reset downstream sections
    showSections();
    activateStep(1);

    document.getElementById('mata_pelajaran_id').value = '';
    document.getElementById('mapelInfoBox').innerHTML = '<div class="text-muted" style="font-size:12px">Pilih mata pelajaran untuk melihat info</div>';
    document.getElementById('guru_id').innerHTML = '<option value="">— Pilih Guru —</option>';
    document.getElementById('guruInfoBox').innerHTML = '<div class="text-muted" style="font-size:12px">Pilih guru untuk melihat informasinya</div>';

    const detailBox     = document.getElementById('paketDetailBox');
    const detailContent = document.getElementById('paketDetailContent');
    const sesiSelect    = document.getElementById('pertemuan_ke');
    const siswaBox      = document.getElementById('daftarSiswaPaketBox');
    const mapelSection  = document.getElementById('section-mapel');

    if (!paketId) {
        detailBox.style.display = 'none';
        siswaBox.style.display  = 'none';
        mapelSection.style.display = 'none';
        sesiSelect.innerHTML = '<option value="">— Pilih paket dulu —</option>';
        hideSesiWarning();
        return;
    }

    const pkg = pakets.find(p => p.id == paketId);
    if (!pkg) return;
    currentPaket = pkg;

    // Package detail box
    detailBox.style.display = 'block';
    const mapelNames = pkg.mata_pelajaran.map(m => m.nama).join(', ') || '—';
    detailContent.innerHTML = `
        <div class="col-md-3 col-6"><strong>Jenis:</strong> ${pkg.jenis || '—'}</div>
        <div class="col-md-3 col-6"><strong>Total Sesi:</strong> ${pkg.jumlah_pertemuan || '—'}</div>
        <div class="col-md-3 col-6"><strong>Tipe Kelas:</strong> ${pkg.tipe_kelas || '—'}</div>
        <div class="col-md-3 col-6"><strong>Harga:</strong> Rp ${pkg.harga ? parseInt(pkg.harga).toLocaleString('id-ID') : '—'}</div>
        <div class="col-md-6 col-12"><strong>Mata Pelajaran:</strong> ${mapelNames}</div>
        <div class="col-md-6 col-12"><strong>Cabang:</strong> ${pkg.cabang || 'Pusat'}</div>
        ${pkg.deskripsi ? `<div class="col-12"><strong>Deskripsi:</strong> ${pkg.deskripsi}</div>` : ''}
    `;

    // Auto-set jenis from package
    const jenisSelect = document.getElementById('jenis');
    if (jenisSelect && !jenisSelect.dataset.manuallyChanged) {
        const validJenis = ['online', 'offline', 'private'];
        jenisSelect.value = validJenis.includes(pkg.tipe_kelas) ? pkg.tipe_kelas : 'offline';
    }

    // Populate mata pelajaran dropdown
    const mapelSelect = document.getElementById('mata_pelajaran_id');
    if (pkg.mata_pelajaran.length === 0) {
        mapelSelect.innerHTML = '<option value="">— Paket ini belum memiliki mata pelajaran —</option>';
    } else {
        let opts = '<option value="">— Pilih mata pelajaran —</option>';
        pkg.mata_pelajaran.forEach(m => {
            opts += `<option value="${m.id}">${m.nama}${m.kategori ? ' ('+m.kategori+')' : ''}</option>`;
        });
        mapelSelect.innerHTML = opts;
        // Auto-select if only one subject
        if (pkg.mata_pelajaran.length === 1) {
            mapelSelect.value = pkg.mata_pelajaran[0].id;
            onMapelChange(pkg.mata_pelajaran[0].id);
        }
    }
    mapelSection.style.display = '';

    // Show sessions dropdown
    sesiSelect.innerHTML = '<option value="">⏳ Memuat sesi...</option>';
    sesiSelect.disabled = true;
    hideSesiWarning();

    loadUsedSessions(paketId, pkg).then(() => {
        buildSesiOptions(paketId, pkg);
        sesiSelect.disabled = false;
    });

    // Load students
    loadStudentsByPackage(paketId);
    activateStep(2);
}

// ── Mata Pelajaran change ───────────────────────────────────────────────────
function onMapelChange(mapelId) {
    currentMapelId = mapelId ? parseInt(mapelId) : null;
    const infoBox     = document.getElementById('mapelInfoBox');
    const guruSection = document.getElementById('section-guru');
    const guruSelect  = document.getElementById('guru_id');
    const noteEl      = document.getElementById('guruFilterNote');

    if (!mapelId) {
        infoBox.innerHTML = '<div class="text-muted" style="font-size:12px">Pilih mata pelajaran untuk melihat info</div>';
        guruSection.style.display = 'none';
        showSections('section-mapel');
        activateStep(2);
        return;
    }

    // Show mapel info
    const pkg = currentPaket;
    const m   = pkg ? pkg.mata_pelajaran.find(x => x.id == mapelId) : null;
    infoBox.innerHTML = m
        ? `<div class="text-muted mb-1" style="font-size:11px;text-transform:uppercase;letter-spacing:.5px">Mata Pelajaran Dipilih</div>
           <div class="fw-semibold">${m.nama}</div>
           ${m.kategori ? `<div class="text-muted" style="font-size:12px">Kategori: ${m.kategori}</div>` : ''}`
        : `<div class="fw-semibold">ID: ${mapelId}</div>`;

    // Filter guru: first check package-course-teacher assignments, then fallback to course_ids
    const pkg = currentPaket;
    let pkgTeacherIds = [];
    if (pkg && pkg.course_teachers) {
        const ct = pkg.course_teachers[parseInt(mapelId)];
        if (ct && ct.length > 0) pkgTeacherIds = ct.map(Number);
    }

    let matched, unmatched;
    if (pkgTeacherIds.length > 0) {
        // Use package-course-teacher assignments
        matched   = teachers.filter(t => pkgTeacherIds.includes(t.id));
        unmatched = teachers.filter(t => !pkgTeacherIds.includes(t.id));
    } else {
        // Fallback to teacher's own course_ids
        matched   = teachers.filter(t => t.course_ids.includes(parseInt(mapelId)));
        unmatched = teachers.filter(t => !t.course_ids.includes(parseInt(mapelId)));
    }

    let opts = '<option value="">— Pilih Guru —</option>';
    if (matched.length > 0) {
        const groupLabel = pkgTeacherIds.length > 0
            ? `✅ Guru paket ini untuk mapel ini (${matched.length})`
            : `✅ Guru mapel ini (${matched.length})`;
        opts += `<optgroup label="${groupLabel}">`;
        matched.forEach(t => {
            opts += `<option value="${t.id}">${t.name}${t.branch ? ' ('+t.branch+')' : ''}</option>`;
        });
        opts += '</optgroup>';
    }
    if (unmatched.length > 0) {
        opts += `<optgroup label="— Guru lainnya (${unmatched.length})">`;
        unmatched.forEach(t => {
            opts += `<option value="${t.id}">${t.name}${t.branch ? ' ('+t.branch+')' : ''}</option>`;
        });
        opts += '</optgroup>';
    }
    guruSelect.innerHTML = opts;

    // Auto-select if only one assigned teacher in package for this course
    if (matched.length === 1) {
        guruSelect.value = matched[0].id;
        onGuruChange(matched[0].id);
    } else if (pkg && pkg.guru_id && matched.find(t => t.id == pkg.guru_id)) {
        guruSelect.value = pkg.guru_id;
        onGuruChange(pkg.guru_id);
    }

    noteEl.textContent = pkgTeacherIds.length > 0
        ? `${matched.length} guru ditugaskan untuk mata pelajaran ini dalam paket — ditampilkan di atas.`
        : (matched.length > 0
            ? `${matched.length} guru terdaftar mengajar mata pelajaran ini.`
            : 'Belum ada guru yang ditetapkan untuk mata pelajaran ini dalam paket. Pilih dari daftar.');

    guruSection.style.display = '';
    showSections('section-mapel', 'section-guru');
    activateStep(3);
}

// ── Guru change ─────────────────────────────────────────────────────────────
function onGuruChange(guruId) {
    const guruInfoBox   = document.getElementById('guruInfoBox');
    const waktuSection  = document.getElementById('section-waktu');
    const submitSection = document.getElementById('section-submit');

    if (!guruId) {
        guruInfoBox.innerHTML = '<div class="text-muted" style="font-size:12px">Pilih guru untuk melihat informasinya</div>';
        waktuSection.style.display  = 'none';
        submitSection.style.display = 'none';
        showSections('section-mapel', 'section-guru');
        activateStep(3);
        return;
    }

    const t = teachers.find(x => x.id == guruId);
    if (t) {
        guruInfoBox.innerHTML = `
            <div class="text-muted mb-1" style="font-size:11px;text-transform:uppercase;letter-spacing:.5px">Guru Terpilih</div>
            <div class="fw-semibold">${t.name}</div>
            ${t.nig  ? `<div class="text-muted" style="font-size:12px">NIG: ${t.nig}</div>`   : ''}
            ${t.email ? `<div class="text-muted" style="font-size:12px">${t.email}</div>`     : ''}
            ${t.branch ? `<div class="text-muted" style="font-size:12px">Cabang: ${t.branch}</div>` : ''}
        `;
    }

    showSections('section-mapel', 'section-guru', 'section-waktu', 'section-submit');
    activateStep(4);
}

// ── Session dropdown ─────────────────────────────────────────────────────────
function loadUsedSessions(paketId, pkg) {
    if (usedSessionsCache[paketId]) return Promise.resolve();
    return fetch(`${packageStudentsBaseUrl}/${paketId}/used-sessions`, {
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
    })
    .then(r => r.ok ? r.json() : { used: {} })
    .then(data => { usedSessionsCache[paketId] = data.used || {}; })
    .catch(() => { usedSessionsCache[paketId] = {}; });
}

function buildSesiOptions(paketId, pkg) {
    const sesiSelect = document.getElementById('pertemuan_ke');
    const used       = usedSessionsCache[paketId] || {};
    const totalSesi  = Number(pkg.jumlah_pertemuan || 0);
    const usedKeys   = Object.keys(used).map(Number);
    const sisaCount  = totalSesi - usedKeys.length;

    let sesiOptions = `<option value="">— Pilih Sesi (${sisaCount} tersisa dari ${totalSesi}) —</option>`;
    for (let i = 1; i <= totalSesi; i++) {
        if (used[i]) {
            const info       = used[i];
            const statusIcon = info.status === 'selesai' ? '✅' : (info.status === 'berlangsung' ? '🟡' : '🔵');
            const topikText  = info.topik ? ` — ${info.topik}` : '';
            const tglText    = info.tanggal ? ` (${info.tanggal})` : '';
            sesiOptions += `<option value="${i}" disabled style="color:#999;background:#f5f5f5">
                ${statusIcon} Sesi ke-${i}${topikText}${tglText} — Sudah dijadwalkan
            </option>`;
        } else {
            sesiOptions += `<option value="${i}">✨ Sesi ke-${i} — Belum dijadwalkan</option>`;
        }
    }
    sesiSelect.innerHTML = sesiOptions;
    updateSesiInfoBar(paketId, pkg);
}

function updateSesiInfoBar(paketId, pkg) {
    const used      = usedSessionsCache[paketId] || {};
    const total     = Number(pkg.jumlah_pertemuan || 0);
    const usedCount = Object.keys(used).length;
    const sisa      = total - usedCount;
    const bar       = document.getElementById('sesiProgressBar');
    if (!bar) return;

    const pct = total > 0 ? Math.round((usedCount / total) * 100) : 0;
    bar.style.display = 'block';
    bar.innerHTML = `
        <div class="d-flex align-items-center justify-content-between mb-1" style="font-size:12px">
            <span class="fw-semibold">Progress Sesi Paket</span>
            <span style="color:var(--text-muted)">${usedCount} / ${total} sudah dijadwalkan</span>
        </div>
        <div class="progress" style="height:8px;border-radius:4px;background:var(--input-bg)">
            <div class="progress-bar" role="progressbar"
                 style="width:${pct}%;background:${pct >= 100 ? '#dc3545' : pct >= 75 ? '#f6af23' : '#10b981'};border-radius:4px"
                 aria-valuenow="${pct}" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
        <div class="mt-1 d-flex gap-3" style="font-size:11px;color:var(--text-muted)">
            <span>✅ Sudah: ${usedCount} sesi</span>
            <span>✨ Tersisa: ${sisa} sesi</span>
            ${pct >= 100 ? '<span class="text-danger fw-semibold">⚠️ Semua sesi sudah dijadwalkan</span>' : ''}
        </div>
    `;
}

function onSesiChange(val) {
    const paketId = document.getElementById('paket_id').value;
    if (!paketId || !val) { hideSesiWarning(); return; }
    const used = usedSessionsCache[paketId] || {};
    if (used[val]) {
        const info = used[val];
        showSesiWarning(`Sesi ke-${val} sudah dijadwalkan pada ${info.tanggal || '—'}${info.topik ? ' ('+info.topik+')' : ''}. Pilih sesi lain.`);
        document.getElementById('submitBtn').disabled = true;
    } else {
        hideSesiWarning();
        document.getElementById('submitBtn').disabled = false;
    }
}

function showSesiWarning(msg) {
    const el = document.getElementById('sesiWarning');
    el.innerHTML = `<div class="mt-2 p-2 rounded-2" style="background:#fff3cd;border:1px solid #ffc107;font-size:12px;color:#856404">
        <i class="bi bi-exclamation-triangle-fill me-1"></i>${msg}</div>`;
}
function hideSesiWarning() {
    const el = document.getElementById('sesiWarning');
    if (el) el.innerHTML = '';
    const btn = document.getElementById('submitBtn');
    if (btn) btn.disabled = false;
}

// ── Students box ────────────────────────────────────────────────────────────
function loadStudentsByPackage(paketId) {
    if (!paketId) return;
    const box     = document.getElementById('daftarSiswaPaketBox');
    const content = document.getElementById('daftarSiswaPaketContent');
    const counter = document.getElementById('siswaPaketCount');
    box.style.display = 'block';
    content.innerHTML = '<span class="text-muted" style="font-size:12px"><i class="bi bi-hourglass-split me-1"></i>Memuat daftar siswa...</span>';
    counter.textContent = '';

    fetch(`${packageStudentsBaseUrl}/${paketId}/students`, {
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
    })
    .then(r => { if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
    .then(data => {
        const students = data.students || [];
        const count    = data.count || 0;
        counter.textContent = count + ' siswa';
        if (!students.length) {
            content.innerHTML = '<span class="text-muted" style="font-size:12px"><i class="bi bi-info-circle me-1"></i>Belum ada siswa yang terdaftar di paket ini.</span>';
            return;
        }
        content.innerHTML = students.map(s =>
            `<span style="background:var(--soft-primary);color:#461256;padding:4px 10px;border-radius:20px;font-size:12px;font-weight:500;display:inline-flex;align-items:center;gap:4px">
                <i class="bi bi-person-fill"></i>${s.name}${s.nis ? `<span style="opacity:.65;font-size:10px">#${s.nis}</span>` : ''}
            </span>`
        ).join('');
    })
    .catch(() => {
        content.innerHTML = '<span class="text-muted" style="font-size:12px"><i class="bi bi-exclamation-circle me-1"></i>Gagal memuat daftar siswa.</span>';
    });
}

// ── Module info ──────────────────────────────────────────────────────────────
function onModuleChange(moduleId) {
    const box = document.getElementById('moduleInfoBox');
    if (!moduleId) { box.innerHTML = '<span class="text-muted">Tidak ada modul dipilih</span>'; return; }
    const m = modules.find(x => x.id == moduleId);
    if (!m) return;
    box.innerHTML = `<strong>${m.judul}</strong>${m.kode_modul ? ` <span style="opacity:.6">[${m.kode_modul}]</span>` : ''}${m.mata_pelajaran ? `<br><span class="text-muted">${m.mata_pelajaran}</span>` : ''}`;
}

// ── Init on validation error ─────────────────────────────────────────────────
const initPaket = document.getElementById('paket_id').value;
if (initPaket) {
    onPaketChange(initPaket);
    // Restore mata_pelajaran_id after async loads
    const initMapel = '<?php echo e(old("mata_pelajaran_id")); ?>';
    const initGuru  = '<?php echo e(old("guru_id")); ?>';
    if (initMapel) {
        setTimeout(() => {
            document.getElementById('mata_pelajaran_id').value = initMapel;
            onMapelChange(initMapel);
            if (initGuru) {
                setTimeout(() => {
                    document.getElementById('guru_id').value = initGuru;
                    onGuruChange(initGuru);
                }, 100);
            }
        }, 600);
    }
}
const initModule = document.getElementById('module_id').value;
if (initModule) onModuleChange(initModule);

activateStep(1);
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/admin/schedules/create.blade.php ENDPATH**/ ?>