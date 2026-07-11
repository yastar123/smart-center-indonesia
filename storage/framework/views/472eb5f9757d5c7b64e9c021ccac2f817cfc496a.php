<?php $__env->startSection('title', 'Proses Pendaftaran'); ?>
<?php $__env->startSection('page-title', 'Proses Pendaftaran Siswa'); ?>

<?php $__env->startSection('content'); ?>
<div class="fade-up">


<div class="dashboard-card mb-4" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-40px;top:-40px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3" style="position:relative">
        <div>
            <div style="font-size:11px;opacity:.6;text-transform:uppercase;letter-spacing:.08em;margin-bottom:5px">Manajemen Pendaftaran</div>
            <h4 class="fw-bold mb-1" style="color:white;font-size:clamp(16px,2vw,22px)">Proses Pendaftaran &mdash; <?php echo e($registration->name); ?></h4>
            <p style="opacity:.72;margin:0;font-size:13px">Atur mata pelajaran, guru, paket kelas &amp; pembayaran sebelum akun siswa dibuat dan dikirim ke WhatsApp.</p>
        </div>
        <a href="<?php echo e(route('admin.registration-list.index')); ?>" class="btn fw-semibold px-4"
           style="background:rgba(255,255,255,.15);color:white;border:1px solid rgba(255,255,255,.3);border-radius:12px;backdrop-filter:blur(10px);white-space:nowrap">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>
</div>

<style>
    .pw-stepper { display:flex; gap:.5rem; padding:1rem; background:var(--card-bg); border:1px solid var(--card-border); border-radius:14px 14px 0 0; overflow-x:auto; }
    .pw-stepper-item { flex:1; min-width:0; display:flex; align-items:center; justify-content:center; gap:.4rem; padding:.6rem .5rem; border-radius:999px; background:var(--input-bg); color:var(--text-muted); font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.03em; white-space:nowrap; transition:all .2s ease; }
    .pw-stepper-item.active { background:linear-gradient(135deg,#fdf4ff,#f7e7ff); color:#461256; }
    .pw-stepper-item.completed { background:var(--soft-success-bg); color:var(--soft-success-text); }
    .pw-step-dot { width:20px; height:20px; display:inline-flex; align-items:center; justify-content:center; border-radius:50%; background:currentColor; color:#fff; font-size:.68rem; font-weight:800; flex-shrink:0; }
    .pw-panel { display:none; }
    .pw-panel.active { display:block; }
    .pw-card { background:var(--card-bg); border:1px solid var(--card-border); border-radius:0 0 14px 14px; padding:1.5rem; }
    .pw-course-row { border:1px solid var(--card-border); border-radius:12px; padding:1rem; margin-bottom:.75rem; background:var(--input-bg); }
    .pw-course-row.disabled { opacity:.45; }
    .pw-actions { display:flex; justify-content:space-between; gap:.75rem; margin-top:1.5rem; }
    @media (max-width:576px) {
        .pw-stepper-item span:last-child { display:none; }
        .pw-stepper-item { justify-content:center; }
    }
</style>

<?php if($registration->status === 'rejected'): ?>
<div class="alert alert-danger"><i class="bi bi-x-circle me-2"></i>Pendaftaran ini telah ditolak dan tidak dapat diproses.</div>
<?php else: ?>

<div class="dashboard-card p-0" style="overflow:hidden">
    <div class="pw-stepper">
        <div class="pw-stepper-item active" data-stepper="1"><span class="pw-step-dot">1</span><span>Informasi Siswa</span></div>
        <div class="pw-stepper-item" data-stepper="2"><span class="pw-step-dot">2</span><span>Paket Kelas</span></div>
        <div class="pw-stepper-item" data-stepper="3"><span class="pw-step-dot">3</span><span>Mapel &amp; Guru</span></div>
        <div class="pw-stepper-item" data-stepper="4"><span class="pw-step-dot">4</span><span>Pembayaran</span></div>
        <div class="pw-stepper-item" data-stepper="5"><span class="pw-step-dot">5</span><span>Preview</span></div>
    </div>

    <form id="processForm" class="pw-card">
        <?php echo csrf_field(); ?>

        
        <div class="pw-panel active" data-step="1">
            <h6 class="fw-bold mb-3"><i class="bi bi-person-vcard me-2 text-primary"></i>Informasi Siswa</h6>
            <p class="text-muted" style="font-size:.8rem">Data ini awalnya diisi siswa saat mendaftar &mdash; admin dapat mengoreksi/melengkapinya jika perlu.</p>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold" style="font-size:.78rem">Nama <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="name" value="<?php echo e($registration->name); ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold" style="font-size:.78rem">No. HP <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="phone" value="<?php echo e($registration->phone); ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold" style="font-size:.78rem">Jenis Kelamin</label>
                    <select class="form-select" name="gender">
                        <option value="">Pilih…</option>
                        <option value="L" <?php echo e($registration->gender==='L'?'selected':''); ?>>Laki-laki</option>
                        <option value="P" <?php echo e($registration->gender==='P'?'selected':''); ?>>Perempuan</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label" style="font-size:.78rem;color:var(--text-muted)">Tempat Lahir</label>
                    <input type="text" class="form-control" name="birth_place" value="<?php echo e($registration->birth_place); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label" style="font-size:.78rem;color:var(--text-muted)">Tgl Lahir</label>
                    <input type="date" class="form-control" name="birth_date" value="<?php echo e($registration->birth_date?->format('Y-m-d')); ?>">
                </div>
                <div class="col-12">
                    <label class="form-label" style="font-size:.78rem;color:var(--text-muted)">Alamat</label>
                    <input type="text" class="form-control" name="address" value="<?php echo e($registration->address); ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label" style="font-size:.78rem;color:var(--text-muted)">Nama Orang Tua</label>
                    <input type="text" class="form-control" name="parent_name" value="<?php echo e($registration->parent_name); ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label" style="font-size:.78rem;color:var(--text-muted)">No. HP Orang Tua</label>
                    <input type="text" class="form-control" name="parent_phone" value="<?php echo e($registration->parent_phone); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label" style="font-size:.78rem;color:var(--text-muted)">Program</label>
                    <select class="form-select" name="program">
                        <option value="">Pilih…</option>
                        <option value="kelas" <?php echo e($registration->program==='kelas'?'selected':''); ?>>Kelas</option>
                        <option value="privat" <?php echo e($registration->program==='privat'?'selected':''); ?>>Privat</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label" style="font-size:.78rem;color:var(--text-muted)">Sistem</label>
                    <select class="form-select" name="system">
                        <option value="">Pilih…</option>
                        <option value="online" <?php echo e($registration->system==='online'?'selected':''); ?>>Online</option>
                        <option value="offline" <?php echo e($registration->system==='offline'?'selected':''); ?>>Offline</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold" style="font-size:.78rem">Cabang <span class="text-danger">*</span></label>
                    <select name="branch_id" id="branchSelect" class="form-select" required>
                        <option value="">Pilih cabang…</option>
                        <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($b->id); ?>" <?php echo e($matchedBranch && $matchedBranch->id === $b->id ? 'selected' : ''); ?>><?php echo e($b->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <div class="form-text" style="font-size:.72rem">Cabang asal pendaftaran: <strong><?php echo e($registration->branch ?: '–'); ?></strong></div>
                </div>
            </div>
            <div class="pw-actions"><span></span><button type="button" class="btn btn-primary" data-action="next">Lanjut<i class="bi bi-arrow-right ms-1"></i></button></div>
        </div>

        
        <div class="pw-panel" data-step="2">
            <h6 class="fw-bold mb-3"><i class="bi bi-box-seam me-2 text-primary"></i>Paket Kelas</h6>
            <p class="text-muted" style="font-size:.83rem">Pilih paket belajar untuk siswa ini (opsional — bisa dilewati jika belum ada paket yang cocok).</p>
            <div class="row g-3" id="packageOptions">
                <div class="col-12">
                    <div class="form-check p-3 border rounded-3" style="border-color:var(--card-border)!important">
                        <input class="form-check-input" type="radio" name="package_id" id="pkgNone" value="" checked>
                        <label class="form-check-label fw-semibold" for="pkgNone">Tanpa Paket (susun manual per mata pelajaran)</label>
                    </div>
                </div>
                <?php $__currentLoopData = $packages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pkg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-6">
                    <div class="form-check p-3 border rounded-3 h-100" style="border-color:var(--card-border)!important">
                        <input class="form-check-input" type="radio" name="package_id" id="pkg<?php echo e($pkg->id); ?>" value="<?php echo e($pkg->id); ?>" data-cabang="<?php echo e($pkg->cabang_id); ?>" data-harga="<?php echo e($pkg->harga); ?>">
                        <label class="form-check-label w-100" for="pkg<?php echo e($pkg->id); ?>">
                            <div class="fw-semibold"><?php echo e($pkg->nama); ?></div>
                            <div class="text-muted" style="font-size:.75rem"><?php echo e($pkg->tipe_kelas ?? 'Reguler'); ?> &middot; <?php echo e($pkg->jumlah_pertemuan ?? '–'); ?> pertemuan</div>
                            <div class="fw-bold text-primary mt-1">Rp<?php echo e(number_format($pkg->harga ?? 0,0,',','.')); ?></div>
                        </label>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <div class="pw-actions">
                <button type="button" class="btn btn-outline-secondary" data-action="prev"><i class="bi bi-arrow-left me-1"></i>Kembali</button>
                <button type="button" class="btn btn-primary" data-action="next">Lanjut<i class="bi bi-arrow-right ms-1"></i></button>
            </div>
        </div>

        
        <div class="pw-panel" data-step="3">
            <h6 class="fw-bold mb-3"><i class="bi bi-journal-bookmark me-2 text-primary"></i>Mata Pelajaran &amp; Guru</h6>
            <?php if($courses->isEmpty()): ?>
            <div class="alert alert-warning" style="font-size:.85rem"><i class="bi bi-exclamation-triangle me-2"></i>Tidak ditemukan mata pelajaran yang cocok dengan minat pendaftaran ini di data master. Hubungi bagian akademik untuk melengkapi data mata pelajaran.</div>
            <?php else: ?>
            <p class="text-muted" style="font-size:.83rem">Centang mata pelajaran yang akan diambil siswa, lalu tentukan guru pengajar dan jumlah sesi.</p>
            <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php $fee = $course->fee->amount ?? 0; ?>
            <div class="pw-course-row" data-course-row="<?php echo e($course->id); ?>">
                <div class="row g-2 align-items-center">
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input course-check" type="checkbox" id="course<?php echo e($course->id); ?>" checked disabled>
                            <input type="hidden" name="course_ids[]" value="<?php echo e($course->id); ?>">
                            <label class="form-check-label fw-semibold" for="course<?php echo e($course->id); ?>"><?php echo e($course->nama); ?></label>
                        </div>
                        <div class="form-text" style="font-size:.68rem">Mapel pilihan siswa &mdash; tidak dapat dihapus</div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select form-select-sm" name="course_teacher[<?php echo e($course->id); ?>]">
                            <option value="">Pilih guru…</option>
                            <?php $__currentLoopData = $course->guru; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($t->id); ?>"><?php echo e($t->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="number" min="1" class="form-control form-control-sm" name="course_sessions[<?php echo e($course->id); ?>]" placeholder="Jml sesi" value="<?php echo e($registration->interest_sessions[$course->nama] ?? 8); ?>">
                    </div>
                    <div class="col-md-4">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">Rp</span>
                            <input type="number" min="0" class="form-control fee-input" name="course_fee[<?php echo e($course->id); ?>]" value="<?php echo e($fee); ?>">
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
            <div class="pw-actions">
                <button type="button" class="btn btn-outline-secondary" data-action="prev"><i class="bi bi-arrow-left me-1"></i>Kembali</button>
                <button type="button" class="btn btn-primary" data-action="next">Lanjut<i class="bi bi-arrow-right ms-1"></i></button>
            </div>
        </div>

        
        <div class="pw-panel" data-step="4">
            <h6 class="fw-bold mb-3"><i class="bi bi-cash-coin me-2 text-primary"></i>Pembayaran</h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold" style="font-size:.78rem">Total Biaya</label>
                    <div class="input-group"><span class="input-group-text">Rp</span><input type="number" min="0" class="form-control" id="totalBiaya" name="total_biaya" required></div>
                    <div class="form-text" style="font-size:.72rem">Dihitung otomatis dari mata pelajaran / paket yang dipilih — bisa disesuaikan.</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label" style="font-size:.78rem;color:var(--text-muted)">Biaya per Sesi (opsional)</label>
                    <div class="input-group"><span class="input-group-text">Rp</span><input type="number" min="0" class="form-control" name="biaya_per_sesi"></div>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold" style="font-size:.78rem">Status Pembayaran</label>
                    <div class="d-flex gap-3 flex-wrap">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_status" id="payBelum" value="belum_bayar" checked>
                            <label class="form-check-label" for="payBelum">Belum Dibayar &mdash; kirim invoice, siswa masuk status <em>Atur Jadwal</em></label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_status" id="payLunas" value="lunas">
                            <label class="form-check-label" for="payLunas">Lunas &mdash; siswa langsung <em>Terjadwal</em></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pw-actions">
                <button type="button" class="btn btn-outline-secondary" data-action="prev"><i class="bi bi-arrow-left me-1"></i>Kembali</button>
                <button type="button" class="btn btn-primary" data-action="next">Lanjut<i class="bi bi-arrow-right ms-1"></i></button>
            </div>
        </div>

        
        <div class="pw-panel" data-step="5">
            <h6 class="fw-bold mb-3"><i class="bi bi-clipboard2-check me-2 text-primary"></i>Preview &amp; Konfirmasi</h6>
            <div id="previewBox" class="p-3 rounded-3" style="background:var(--input-bg);border:1px solid var(--card-border);font-size:.86rem"></div>
            <div class="alert alert-info mt-3" style="font-size:.82rem"><i class="bi bi-info-circle me-2"></i>Setelah disubmit, akun login siswa akan dibuat otomatis dan Anda dapat langsung mengirimkannya ke WhatsApp siswa.</div>
            <div class="pw-actions">
                <button type="button" class="btn btn-outline-secondary" data-action="prev"><i class="bi bi-arrow-left me-1"></i>Kembali</button>
                <button type="submit" class="btn btn-success" id="submitBtn">
                    <span id="submitText"><i class="bi bi-check-circle me-1"></i>Verifikasi &amp; Buat Akun</span>
                    <span id="submitLoading" class="d-none"><span class="spinner-border spinner-border-sm me-1"></span>Memproses...</span>
                </button>
            </div>
        </div>
    </form>

    
    <div id="successPanel" class="pw-card d-none text-center">
        <i class="bi bi-check-circle-fill text-success" style="font-size:3rem"></i>
        <h5 class="fw-bold mt-3 mb-1">Akun Siswa Berhasil Dibuat</h5>
        <p class="text-muted" style="font-size:.85rem">Kirim informasi akun ini ke WhatsApp siswa agar bisa langsung login.</p>
        <div class="mx-auto text-start p-3 rounded-3 mt-3" style="max-width:420px;background:var(--input-bg);border:1px solid var(--card-border)">
            <div class="d-flex justify-content-between align-items-center mb-2"><span class="text-muted" style="font-size:.78rem">Nama</span><strong id="cred-name">–</strong></div>
            <div class="d-flex justify-content-between align-items-center mb-2"><span class="text-muted" style="font-size:.78rem">Email</span><code id="cred-email">–</code></div>
            <div class="d-flex justify-content-between align-items-center"><span class="text-muted" style="font-size:.78rem">Password</span><code id="cred-password">–</code></div>
        </div>
        <div class="d-flex justify-content-center gap-2 mt-4 flex-wrap">
            <button type="button" class="btn btn-success" onclick="sendToWA()"><i class="bi bi-whatsapp me-1"></i>Kirim ke WhatsApp Siswa</button>
            <a href="<?php echo e(route('admin.registration-list.index')); ?>" class="btn btn-outline-secondary"><i class="bi bi-list-check me-1"></i>Kembali ke Daftar</a>
        </div>
    </div>
</div>
<?php endif; ?>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
const _processUrl = "<?php echo e(route('admin.registration-list.process.store', $registration->id)); ?>";
const _csrf = "<?php echo e(csrf_token()); ?>";
let _credData = {};

function showStep(step) {
    document.querySelectorAll('.pw-panel').forEach((panel, i) => panel.classList.toggle('active', i === step - 1));
    document.querySelectorAll('.pw-stepper-item').forEach((item, i) => {
        const current = i + 1;
        item.classList.toggle('active', current === step);
        item.classList.toggle('completed', current < step);
    });
    if (step === 5) buildPreview();
}

document.querySelectorAll('[data-action="next"]').forEach(btn => {
    btn.addEventListener('click', () => {
        const current = document.querySelector('.pw-panel.active');
        const next = parseInt(current.dataset.step, 10) + 1;
        if (next <= 5) showStep(next);
    });
});
document.querySelectorAll('[data-action="prev"]').forEach(btn => {
    btn.addEventListener('click', () => {
        const current = document.querySelector('.pw-panel.active');
        const prev = parseInt(current.dataset.step, 10) - 1;
        if (prev >= 1) showStep(prev);
    });
});

function recalcTotal() {
    let total = 0;
    document.querySelectorAll('.course-check').forEach(chk => {
        const row = chk.closest('.pw-course-row');
        row.classList.toggle('disabled', !chk.checked);
        row.querySelectorAll('select, input').forEach(el => { if (el !== chk) el.disabled = !chk.checked; });
        if (chk.checked) {
            const feeInput = row.querySelector('.fee-input');
            total += parseFloat(feeInput?.value || 0);
        }
    });
    document.getElementById('totalBiaya').value = total || 0;
}
document.querySelectorAll('.course-check, .fee-input').forEach(el => el.addEventListener('input', recalcTotal));
document.querySelectorAll('.course-check').forEach(el => el.addEventListener('change', recalcTotal));
recalcTotal();

document.querySelectorAll('input[name="package_id"]').forEach(r => {
    r.addEventListener('change', () => {
        const checked = document.querySelector('input[name="package_id"]:checked');
        if (checked && checked.value) {
            document.getElementById('totalBiaya').value = checked.dataset.harga || 0;
        } else {
            recalcTotal();
        }
    });
});

document.getElementById('branchSelect').addEventListener('change', function() {
    const branchId = this.value;
    document.querySelectorAll('#packageOptions .col-md-6').forEach(col => {
        const radio = col.querySelector('input[name="package_id"]');
        if (!branchId || !radio.dataset.cabang || radio.dataset.cabang === branchId) {
            col.classList.remove('d-none');
        } else {
            col.classList.add('d-none');
            if (radio.checked) document.getElementById('pkgNone').checked = true;
        }
    });
});

function buildPreview() {
    const branchName = document.getElementById('branchSelect').selectedOptions[0]?.text || '–';
    const pkgRadio = document.querySelector('input[name="package_id"]:checked');
    const pkgName = pkgRadio && pkgRadio.value ? pkgRadio.closest('label').querySelector('.fw-semibold').textContent : 'Tanpa Paket';
    const rows = [];
    document.querySelectorAll('.course-check:checked').forEach(chk => {
        const row = chk.closest('.pw-course-row');
        const teacher = row.querySelector('select').selectedOptions[0]?.text || '–';
        const sesi = row.querySelector('input[name^="course_sessions"]').value || '–';
        const fee = row.querySelector('.fee-input').value || 0;
        const courseName = row.querySelector('.form-check-label').textContent;
        rows.push(`<tr><td>${courseName}</td><td>${teacher}</td><td>${sesi}</td><td>Rp${Number(fee).toLocaleString('id-ID')}</td></tr>`);
    });
    const total = document.getElementById('totalBiaya').value || 0;
    const payStatus = document.querySelector('input[name="payment_status"]:checked').value === 'lunas' ? 'Lunas' : 'Belum Dibayar';

    document.getElementById('previewBox').innerHTML = `
        <div class="row g-2 mb-3">
            <div class="col-md-6"><span class="text-muted">Cabang:</span> <strong>${branchName}</strong></div>
            <div class="col-md-6"><span class="text-muted">Paket:</span> <strong>${pkgName}</strong></div>
        </div>
        <table class="table table-sm"><thead><tr><th>Mapel</th><th>Guru</th><th>Sesi</th><th>Biaya</th></tr></thead>
        <tbody>${rows.join('') || '<tr><td colspan="4" class="text-muted text-center">Tidak ada mapel dipilih</td></tr>'}</tbody></table>
        <div class="d-flex justify-content-between mt-2"><span class="text-muted">Status Pembayaran:</span><strong>${payStatus}</strong></div>
        <div class="d-flex justify-content-between"><span class="text-muted">Total Biaya:</span><strong class="text-primary">Rp${Number(total).toLocaleString('id-ID')}</strong></div>
    `;
}

document.getElementById('processForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const checkedCourses = document.querySelectorAll('.course-check:checked').length;
    if (checkedCourses === 0) {
        showToast('Pilih minimal satu mata pelajaran sebelum melanjutkan.', 'error');
        showStep(3);
        return;
    }

    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    document.getElementById('submitText').classList.add('d-none');
    document.getElementById('submitLoading').classList.remove('d-none');

    const formData = new FormData(this);
    fetch(_processUrl, {
        method: 'POST',
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': _csrf },
        body: formData,
    })
    .then(r => r.json().then(d => ({ ok: r.ok, d })))
    .then(({ ok, d }) => {
        if (ok && d.success) {
            _credData = d;
            document.getElementById('cred-name').textContent = d.name || '–';
            document.getElementById('cred-email').textContent = d.email || '–';
            document.getElementById('cred-password').textContent = d.password || '–';
            document.getElementById('processForm').classList.add('d-none');
            document.getElementById('successPanel').classList.remove('d-none');
        } else {
            showToast(d.message || 'Gagal memproses pendaftaran.', 'error');
            submitBtn.disabled = false;
            document.getElementById('submitText').classList.remove('d-none');
            document.getElementById('submitLoading').classList.add('d-none');
        }
    })
    .catch(() => {
        showToast('Terjadi kesalahan. Coba lagi.', 'error');
        submitBtn.disabled = false;
        document.getElementById('submitText').classList.remove('d-none');
        document.getElementById('submitLoading').classList.add('d-none');
    });
});

function sendToWA() {
    const phone = (_credData.phone || '').replace(/\D/g, '');
    if (!phone) { showToast('Nomor HP siswa tidak tersedia.', 'error'); return; }
    const wa = phone.startsWith('0') ? '62' + phone.slice(1) : phone;
    const loginUrl = '<?php echo e(url("/login")); ?>';
    const msg = encodeURIComponent(
        'Halo ' + (_credData.name || 'Siswa') + ',\n\n' +
        'Selamat datang di Smart Center Indonesia!\n\n' +
        'Pendaftaran Anda telah *diverifikasi*. Berikut data akun login Anda:\n\n' +
        '*Email:* ' + (_credData.email || '-') + '\n' +
        '*Password:* ' + (_credData.password || '-') + '\n' +
        '*No. Registrasi:* ' + (_credData.no_reg || '-') + '\n\n' +
        '*Link Login:*\n' + loginUrl + '\n\n' +
        'Segera login dan lengkapi profil Anda. Jangan bagikan password kepada siapapun.\n\n' +
        'Terima kasih & selamat belajar!'
    );
    window.open('https://wa.me/' + wa + '?text=' + msg, '_blank');
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/admin/registration/process.blade.php ENDPATH**/ ?>