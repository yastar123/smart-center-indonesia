@extends('layouts.app')
@section('title', 'Edit Promo')
@section('page-title', 'Edit Promo')

@section('content')

<div class="mb-4 fade-up">
    <a href="{{ route('owner.promo.show', $promo->id) }}" class="btn btn-sm"
       style="background:var(--input-bg);border:1px solid var(--card-border);color:var(--text-muted);border-radius:9px;font-size:13px">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
</div>

<div class="mb-4 fade-up" style="animation-delay:.02s">
    <h5 class="fw-bold mb-1" style="color:var(--text-primary)">Edit Promo: {{ $promo->judul }}</h5>
    <p class="text-muted mb-0" style="font-size:13px">ID: {{ $promo->kode }}</p>
</div>

<form action="{{ route('owner.promo.update', $promo->id) }}" method="POST" enctype="multipart/form-data">
@csrf

<div class="row g-4">
    <div class="col-lg-8">

        {{-- STEP 01: Banner --}}
        <div class="dashboard-card mb-4 fade-up" style="animation-delay:.04s">
            <div class="d-flex align-items-center gap-3 mb-4">
                <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#461256,#c84ddf);display:flex;align-items:center;justify-content:center;color:white;font-size:14px;font-weight:800;flex-shrink:0">01</div>
                <h6 class="fw-bold mb-0" style="color:var(--text-primary)">Visual & Banner Promo</h6>
            </div>
            <div id="banner-drop" onclick="document.getElementById('banner-input').click()"
                 style="border:2px dashed var(--card-border);border-radius:16px;padding:24px;text-align:center;cursor:pointer;transition:.2s;background:var(--input-bg)"
                 onmouseover="this.style.borderColor='#c84ddf'" onmouseout="this.style.borderColor='var(--card-border)'">
                @if($promo->banner_path)
                <img id="banner-preview" src="{{ asset('storage/'.$promo->banner_path) }}" alt=""
                     style="max-height:200px;border-radius:10px;margin-bottom:10px;max-width:100%;object-fit:cover">
                <div class="text-muted" style="font-size:12px">Klik untuk ganti banner</div>
                @else
                <img id="banner-preview" src="" alt="" style="display:none;max-height:200px;border-radius:10px;margin-bottom:12px;max-width:100%;object-fit:cover">
                <div id="banner-placeholder">
                    <i class="bi bi-image" style="font-size:28px;color:#c84ddf"></i>
                    <div class="mt-2 text-muted" style="font-size:13px">Klik untuk Upload Banner</div>
                </div>
                @endif
            </div>
            <input type="file" id="banner-input" name="banner" accept="image/*" class="d-none" onchange="previewBanner(this)">
            <div class="text-muted mt-2" style="font-size:11px">Rekomendasi: 1200 × 600 px, Maks 2MB</div>
        </div>

        {{-- STEP 02: Detail --}}
        <div class="dashboard-card mb-4 fade-up" style="animation-delay:.06s">
            <div class="d-flex align-items-center gap-3 mb-4">
                <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#461256,#c84ddf);display:flex;align-items:center;justify-content:center;color:white;font-size:14px;font-weight:800;flex-shrink:0">02</div>
                <h6 class="fw-bold mb-0" style="color:var(--text-primary)">Detail Kampanye</h6>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:13px">Judul Promo <span class="text-danger">*</span></label>
                <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror"
                       value="{{ old('judul', $promo->judul) }}" required>
                @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:13px">Tipe Promo</label>
                <div class="row g-2">
                    @foreach([
                        'diskon'         => ['label'=>'Diskon Pembayaran',   'icon'=>'bi-percent',        'color'=>'#c84ddf'],
                        'bundle_upgrade' => ['label'=>'Bundle Upgrade',      'icon'=>'bi-arrow-up-circle','color'=>'#0284c7'],
                        'special_price'  => ['label'=>'Special Price',       'icon'=>'bi-tag-fill',       'color'=>'#10b981'],
                        'lainnya'        => ['label'=>'Lainnya',             'icon'=>'bi-three-dots',     'color'=>'#68117e'],
                    ] as $val => $opt)
                    <div class="col-6 col-md-3">
                        <label class="tipe-card d-flex flex-column align-items-center gap-2 p-3 text-center"
                               style="border:1.5px solid var(--card-border);border-radius:12px;cursor:pointer;transition:.15s;background:var(--input-bg)"
                               data-val="{{ $val }}" data-color="{{ $opt['color'] }}">
                            <input type="radio" name="tipe" value="{{ $val }}" class="d-none"
                                   {{ old('tipe', $promo->tipe) === $val ? 'checked' : '' }}>
                            <i class="bi {{ $opt['icon'] }}" style="font-size:20px;color:{{ $opt['color'] }}"></i>
                            <span style="font-size:11.5px;font-weight:600;color:var(--text-primary)">{{ $opt['label'] }}</span>
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:13px">Kode Promo <span class="text-muted fw-normal">(Opsional)</span></label>
                <input type="text" name="kode_promo" class="form-control"
                       value="{{ old('kode_promo', $promo->kode_promo) }}" placeholder="KODEPROMO2026"
                       style="text-transform:uppercase;letter-spacing:.05em">
            </div>

            <div>
                <label class="form-label fw-semibold" style="font-size:13px">Deskripsi Singkat</label>
                <textarea name="deskripsi" class="form-control" rows="3"
                          placeholder="Jelaskan keuntungan promo ini kepada siswa...">{{ old('deskripsi', $promo->deskripsi) }}</textarea>
            </div>
        </div>

        {{-- STEP 03: Jadwal & Target --}}
        <div class="dashboard-card fade-up" style="animation-delay:.08s">
            <div class="d-flex align-items-center gap-3 mb-4">
                <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#461256,#c84ddf);display:flex;align-items:center;justify-content:center;color:white;font-size:14px;font-weight:800;flex-shrink:0">03</div>
                <h6 class="fw-bold mb-0" style="color:var(--text-primary)">Penjadwalan & Target</h6>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-6">
                    <label class="form-label fw-semibold" style="font-size:13px">Tgl Mulai Tayang <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_mulai" class="form-control"
                           value="{{ old('tanggal_mulai', $promo->tanggal_mulai?->format('Y-m-d')) }}" required>
                </div>
                <div class="col-6">
                    <label class="form-label fw-semibold" style="font-size:13px">Tgl Berakhir <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_berakhir" class="form-control"
                           value="{{ old('tanggal_berakhir', $promo->tanggal_berakhir?->format('Y-m-d')) }}" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:13px">Target Distribusi</label>
                <div class="d-flex flex-column gap-2">
                    @foreach([
                        'semua'          => 'Semua Siswa',
                        'paket_intensif' => 'Hanya Paket Intensif',
                        'cabang'         => 'Hanya Cabang Tertentu',
                        'cicilan'        => 'Hanya Siswa Cicilan',
                    ] as $val => $label)
                    <label class="target-item d-flex align-items-center gap-3 p-3"
                           style="border:1.5px solid var(--card-border);border-radius:12px;cursor:pointer;transition:.15s"
                           data-val="{{ $val }}">
                        <input type="radio" name="target" value="{{ $val }}"
                               {{ old('target', $promo->target) === $val ? 'checked' : '' }}
                               onchange="toggleCabang(this.value)" class="form-check-input mb-0">
                        <span style="font-size:13px;font-weight:500;color:var(--text-primary)">{{ $label }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <div id="cabang-row" style="{{ old('target', $promo->target) === 'cabang' ? '' : 'display:none' }}">
                <label class="form-label fw-semibold" style="font-size:13px">Pilih Cabang</label>
                <select name="cabang_id" class="form-select">
                    <option value="">— Pilih Cabang —</option>
                    @foreach($branches as $b)
                    <option value="{{ $b->id }}" {{ old('cabang_id', $promo->cabang_id) == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

    </div>

    {{-- Right: Status & Submit --}}
    <div class="col-lg-4">
        <div class="dashboard-card fade-up" style="animation-delay:.05s;position:sticky;top:80px">
            <h6 class="fw-bold mb-3" style="color:var(--text-primary)">Status Promo</h6>
            <div class="mb-4">
                @foreach(['draft'=>['Draft','#f6af23'],'aktif'=>['Aktif','#10b981'],'berakhir'=>['Berakhir','#64748b']] as $sv=>[$sl,$sc])
                <label class="d-flex align-items-center gap-3 p-3 mb-2" style="border:1.5px solid var(--card-border);border-radius:12px;cursor:pointer">
                    <input type="radio" name="status" value="{{ $sv }}" class="form-check-input mb-0"
                           {{ old('status', $promo->status) === $sv ? 'checked' : '' }}>
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-circle-fill" style="font-size:8px;color:{{ $sc }}"></i>
                        <span class="fw-semibold" style="font-size:13px">{{ $sl }}</span>
                    </div>
                </label>
                @endforeach
            </div>
            <button type="submit" class="btn btn-primary w-100 fw-bold" style="border-radius:12px;padding:11px">
                <i class="bi bi-save me-2"></i>Simpan Perubahan
            </button>
            <a href="{{ route('owner.promo.show', $promo->id) }}" class="btn w-100 mt-2"
               style="border-radius:12px;border:1px solid var(--card-border);color:var(--text-muted)">Batal</a>
        </div>
    </div>
</div>
</form>

@endsection

@push('scripts')
<script>
function previewBanner(input) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
        const prev = document.getElementById('banner-preview');
        prev.src = e.target.result;
        prev.style.display = 'block';
        const ph = document.getElementById('banner-placeholder');
        if (ph) ph.style.display = 'none';
    };
    reader.readAsDataURL(input.files[0]);
}
function toggleCabang(val) {
    document.getElementById('cabang-row').style.display = val === 'cabang' ? '' : 'none';
    document.querySelectorAll('.target-item').forEach(el => {
        el.style.borderColor = el.dataset.val === val ? '#c84ddf' : 'var(--card-border)';
        el.style.background  = el.dataset.val === val ? 'rgba(200,77,223,.05)' : '';
    });
}
document.addEventListener('DOMContentLoaded', () => {
    const tipeCards = document.querySelectorAll('.tipe-card');
    function highlightTipe() {
        const checked = document.querySelector('input[name="tipe"]:checked');
        tipeCards.forEach(card => {
            const active = card.dataset.val === (checked?.value ?? '');
            card.style.borderColor = active ? card.dataset.color : 'var(--card-border)';
            card.style.background  = active ? card.dataset.color + '0d' : 'var(--input-bg)';
        });
    }
    tipeCards.forEach(c => c.addEventListener('click', () => {
        c.querySelector('input').checked = true; highlightTipe();
    }));
    highlightTipe();
    const ct = document.querySelector('input[name="target"]:checked');
    if (ct) toggleCabang(ct.value);
    document.querySelectorAll('input[name="target"]').forEach(r => r.addEventListener('change', () => toggleCabang(r.value)));
    document.querySelector('input[name="kode_promo"]')?.addEventListener('input', function() { this.value = this.value.toUpperCase(); });
});
</script>
@endpush
