<?php $__env->startSection('title', 'Buat Promo Baru'); ?>
<?php $__env->startSection('page-title', 'Buat Promo Baru'); ?>

<?php $__env->startSection('content'); ?>

<div class="mb-4 fade-up">
    <a href="<?php echo e(route('owner.promo.index')); ?>" class="btn btn-sm"
       style="background:var(--input-bg);border:1px solid var(--card-border);color:var(--text-muted);border-radius:9px;font-size:13px">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
</div>


<div class="mb-4 fade-up" style="animation-delay:.02s">
    <h5 class="fw-bold mb-1" style="color:var(--text-primary)">Setup Promo Baru</h5>
    <p class="text-muted mb-0" style="font-size:13px">Konfigurasi konten penawaran dan tentukan periode tayangnya.</p>
</div>

<form id="promo-form" action="<?php echo e(route('owner.promo.store')); ?>" method="POST" enctype="multipart/form-data">
<?php echo csrf_field(); ?>

<div class="row g-4">

    
    <div class="col-lg-8">

        
        <div class="dashboard-card mb-4 fade-up" style="animation-delay:.04s">
            <div class="d-flex align-items-center gap-3 mb-4">
                <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#461256,#c84ddf);display:flex;align-items:center;justify-content:center;color:white;font-size:14px;font-weight:800;flex-shrink:0">01</div>
                <div>
                    <h6 class="fw-bold mb-0" style="color:var(--text-primary)">Visual & Banner Promo</h6>
                </div>
            </div>

            <div id="banner-drop"
                 onclick="document.getElementById('banner-input').click()"
                 style="border:2px dashed var(--card-border);border-radius:16px;padding:40px 24px;text-align:center;cursor:pointer;transition:.2s;background:var(--input-bg)"
                 onmouseover="this.style.borderColor='#c84ddf'" onmouseout="this.style.borderColor='var(--card-border)'">
                <img id="banner-preview" src="" alt="" style="display:none;max-height:220px;border-radius:10px;margin-bottom:12px;max-width:100%;object-fit:cover">
                <div id="banner-placeholder">
                    <div style="width:56px;height:56px;border-radius:16px;background:rgba(200,77,223,.1);display:flex;align-items:center;justify-content:center;margin:0 auto 12px">
                        <i class="bi bi-image" style="font-size:24px;color:#c84ddf"></i>
                    </div>
                    <div class="fw-semibold mb-1" style="font-size:14px;color:var(--text-primary)">Upload Banner (Landscape)</div>
                    <div class="text-muted" style="font-size:12px">Klik untuk Pilih Gambar</div>
                </div>
                <div class="mt-2 text-muted" style="font-size:11px">Rekomendasi ukuran: 1200 x 600 px (Maks 2MB)</div>
            </div>
            <input type="file" id="banner-input" name="banner" accept="image/*" class="d-none"
                   onchange="previewBanner(this)">
            <?php $__errorArgs = ['banner'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger mt-1" style="font-size:12px"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        
        <div class="dashboard-card mb-4 fade-up" style="animation-delay:.06s">
            <div class="d-flex align-items-center gap-3 mb-4">
                <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#461256,#c84ddf);display:flex;align-items:center;justify-content:center;color:white;font-size:14px;font-weight:800;flex-shrink:0">02</div>
                <h6 class="fw-bold mb-0" style="color:var(--text-primary)">Detail Kampanye</h6>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:13px">Judul Promo <span class="text-danger">*</span></label>
                <input type="text" name="judul" class="form-control <?php $__errorArgs = ['judul'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       value="<?php echo e(old('judul')); ?>" placeholder="Contoh: Gebyar Ramadan Sale 50%" required>
                <?php $__errorArgs = ['judul'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:13px">Tipe Promo</label>
                <div class="row g-2">
                    <?php $__currentLoopData = [
                        'diskon'         => ['label'=>'Diskon Pembayaran',   'icon'=>'bi-percent',        'color'=>'#c84ddf'],
                        'bundle_upgrade' => ['label'=>'Bundle Upgrade',      'icon'=>'bi-arrow-up-circle','color'=>'#0284c7'],
                        'special_price'  => ['label'=>'Special Price',       'icon'=>'bi-tag-fill',       'color'=>'#10b981'],
                        'lainnya'        => ['label'=>'Lainnya',             'icon'=>'bi-three-dots',     'color'=>'#68117e'],
                    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-6 col-md-3">
                        <label class="tipe-card d-flex flex-column align-items-center gap-2 p-3 text-center"
                               style="border:1.5px solid var(--card-border);border-radius:12px;cursor:pointer;transition:.15s;background:var(--input-bg)"
                               data-val="<?php echo e($val); ?>" data-color="<?php echo e($opt['color']); ?>">
                            <input type="radio" name="tipe" value="<?php echo e($val); ?>" class="d-none"
                                   <?php echo e(old('tipe','diskon') === $val ? 'checked' : ''); ?>>
                            <i class="bi <?php echo e($opt['icon']); ?>" style="font-size:20px;color:<?php echo e($opt['color']); ?>"></i>
                            <span style="font-size:11.5px;font-weight:600;color:var(--text-primary)"><?php echo e($opt['label']); ?></span>
                        </label>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:13px">Kode Promo <span class="text-muted fw-normal">(Opsional)</span></label>
                <input type="text" name="kode_promo" class="form-control <?php $__errorArgs = ['kode_promo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       value="<?php echo e(old('kode_promo')); ?>" placeholder="KODEPROMO2026" style="text-transform:uppercase;letter-spacing:.05em">
                <?php $__errorArgs = ['kode_promo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="mb-1">
                <label class="form-label fw-semibold" style="font-size:13px">Deskripsi Singkat</label>
                <textarea name="deskripsi" class="form-control <?php $__errorArgs = ['deskripsi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                          rows="3" placeholder="Jelaskan keuntungan promo ini kepada siswa..."><?php echo e(old('deskripsi')); ?></textarea>
                <?php $__errorArgs = ['deskripsi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>

        
        <div class="dashboard-card fade-up" style="animation-delay:.08s">
            <div class="d-flex align-items-center gap-3 mb-4">
                <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#461256,#c84ddf);display:flex;align-items:center;justify-content:center;color:white;font-size:14px;font-weight:800;flex-shrink:0">03</div>
                <h6 class="fw-bold mb-0" style="color:var(--text-primary)">Penjadwalan & Target</h6>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-6">
                    <label class="form-label fw-semibold" style="font-size:13px">Tgl Mulai Tayang <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_mulai" class="form-control <?php $__errorArgs = ['tanggal_mulai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           value="<?php echo e(old('tanggal_mulai')); ?>" required>
                    <?php $__errorArgs = ['tanggal_mulai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-6">
                    <label class="form-label fw-semibold" style="font-size:13px">Tgl Berakhir <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_berakhir" class="form-control <?php $__errorArgs = ['tanggal_berakhir'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           value="<?php echo e(old('tanggal_berakhir')); ?>" required>
                    <?php $__errorArgs = ['tanggal_berakhir'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:13px">Target Distribusi</label>
                <div class="d-flex flex-column gap-2">
                    <?php $__currentLoopData = [
                        'semua'          => 'Semua Siswa',
                        'paket_intensif' => 'Hanya Paket Intensif',
                        'cabang'         => 'Hanya Cabang Tertentu',
                        'cicilan'        => 'Hanya Siswa Cicilan',
                    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <label class="target-item d-flex align-items-center gap-3 p-3"
                           style="border:1.5px solid var(--card-border);border-radius:12px;cursor:pointer;transition:.15s"
                           data-val="<?php echo e($val); ?>">
                        <input type="radio" name="target" value="<?php echo e($val); ?>"
                               <?php echo e(old('target','semua') === $val ? 'checked' : ''); ?>

                               onchange="toggleCabang(this.value)" class="form-check-input mb-0">
                        <span style="font-size:13px;font-weight:500;color:var(--text-primary)"><?php echo e($label); ?></span>
                    </label>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <div id="cabang-row" class="mb-3" style="<?php echo e(old('target') === 'cabang' ? '' : 'display:none'); ?>">
                <label class="form-label fw-semibold" style="font-size:13px">Pilih Cabang</label>
                <select name="cabang_id" class="form-select <?php $__errorArgs = ['cabang_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <option value="">— Pilih Cabang —</option>
                    <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($b->id); ?>" <?php echo e(old('cabang_id') == $b->id ? 'selected' : ''); ?>><?php echo e($b->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['cabang_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="p-3 mt-2" style="background:rgba(200,77,223,.06);border-radius:12px;border:1px solid rgba(200,77,223,.15)">
                <div style="font-size:12.5px;color:#461256;font-style:italic">
                    <i class="bi bi-info-circle me-1"></i>
                    Promo akan langsung muncul di dashboard portal siswa terpilih setelah status diatur ke Aktif.
                </div>
            </div>
        </div>

    </div>

    
    <div class="col-lg-4">
        <div class="dashboard-card fade-up" style="animation-delay:.05s;position:sticky;top:80px">
            <h6 class="fw-bold mb-4" style="color:var(--text-primary)">
                <i class="bi bi-send text-primary me-2"></i>Publikasi
            </h6>

            <div class="mb-4">
                <div class="p-3 mb-2" style="border:1.5px solid var(--card-border);border-radius:12px;background:var(--input-bg)">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <i class="bi bi-circle-fill" style="font-size:8px;color:#f6af23"></i>
                        <span class="fw-semibold" style="font-size:13px">Draft</span>
                    </div>
                    <div class="text-muted" style="font-size:11.5px">Simpan sebagai draft, tidak muncul ke siswa.</div>
                </div>
                <div class="p-3" style="border:1.5px solid rgba(16,185,129,.3);border-radius:12px;background:rgba(16,185,129,.05)">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <i class="bi bi-circle-fill" style="font-size:8px;color:#10b981"></i>
                        <span class="fw-semibold" style="font-size:13px">Aktif</span>
                    </div>
                    <div class="text-muted" style="font-size:11.5px">Langsung tayang di portal siswa.</div>
                </div>
            </div>

            <button type="submit" name="status" value="draft"
                    class="btn w-100 fw-bold mb-3"
                    style="border-radius:12px;border:1.5px solid var(--card-border);color:var(--text-muted);background:var(--input-bg);padding:11px;letter-spacing:.04em">
                <i class="bi bi-save me-2"></i>SIMPAN DRAFT
            </button>

            <button type="submit" name="status" value="aktif"
                    class="btn btn-primary w-100 fw-bold"
                    style="border-radius:12px;padding:11px;letter-spacing:.04em">
                <i class="bi bi-send me-2"></i>PUBLIKASIKAN SEKARANG
            </button>
        </div>
    </div>

</div>
</form>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function previewBanner(input) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('banner-preview').src = e.target.result;
        document.getElementById('banner-preview').style.display = 'block';
        document.getElementById('banner-placeholder').style.display = 'none';
    };
    reader.readAsDataURL(input.files[0]);
}

function toggleCabang(val) {
    document.getElementById('cabang-row').style.display = val === 'cabang' ? '' : 'none';
    document.querySelectorAll('.target-item').forEach(el => {
        el.style.borderColor  = el.dataset.val === val ? '#c84ddf' : 'var(--card-border)';
        el.style.background   = el.dataset.val === val ? 'rgba(200,77,223,.05)' : '';
    });
}

document.addEventListener('DOMContentLoaded', () => {
    // Tipe cards highlight
    const tipeCards = document.querySelectorAll('.tipe-card');
    function highlightTipe() {
        const checked = document.querySelector('input[name="tipe"]:checked');
        tipeCards.forEach(card => {
            const isActive = card.dataset.val === (checked?.value ?? '');
            card.style.borderColor = isActive ? card.dataset.color : 'var(--card-border)';
            card.style.background  = isActive ? card.dataset.color + '0d' : 'var(--input-bg)';
        });
    }
    tipeCards.forEach(card => {
        card.addEventListener('click', () => {
            const radio = card.querySelector('input[type="radio"]');
            radio.checked = true;
            highlightTipe();
        });
    });
    highlightTipe();

    // Target highlight on load
    const checkedTarget = document.querySelector('input[name="target"]:checked');
    if (checkedTarget) toggleCabang(checkedTarget.value);
    document.querySelectorAll('input[name="target"]').forEach(r => {
        r.addEventListener('change', () => toggleCabang(r.value));
    });

    // Kode promo uppercase
    document.querySelector('input[name="kode_promo"]')?.addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/owner/promo/create.blade.php ENDPATH**/ ?>