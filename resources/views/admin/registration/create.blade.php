@extends('layouts.app')

@section('title', 'Registrasi Baru')
@section('page-title', 'Registrasi Baru')

@section('content')
<div class="fade-up">

{{-- BREADCRUMB --}}
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.registration.index') }}">Daftar Registrasi</a></li>
        <li class="breadcrumb-item active">Registrasi Baru</li>
    </ol>
</nav>

@if($errors->any())
    <div class="alert alert-danger mb-3">
        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-3"><i class="bi bi-x-circle me-2"></i>{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<form method="POST" action="{{ route('admin.registration.store') }}" id="regForm">
@csrf

<div class="row g-3">

    {{-- LEFT COLUMN: 4 CARDS --}}
    <div class="col-lg-8">

        {{-- CARD 1: PENDAFTAR & WALI --}}
        <div class="dashboard-card mb-3">
            <div class="d-flex align-items-center gap-2 mb-3">
                <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,#461256,#c84ddf);display:flex;align-items:center;justify-content:center;color:white;font-size:14px;font-weight:700">1</div>
                <h6 class="fw-bold mb-0">Informasi Pendaftar & Wali</h6>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Pilih Cabang Target <span class="text-danger">*</span></label>
                    <select name="cabang_id" class="form-select @error('cabang_id') is-invalid @enderror" required id="branchSelect" onchange="updateQuote()">
                        <option value="">— Pilih Cabang —</option>
                        @foreach($branches as $b)
                            <option value="{{ $b->id }}" {{ old('cabang_id')==$b->id?'selected':'' }}>{{ $b->name }}</option>
                        @endforeach
                    </select>
                    @error('cabang_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Jenis Siswa</label>
                    <div class="d-flex gap-2">
                        <input type="hidden" name="is_new_student" id="isNewStudent" value="1">
                        <button type="button" id="btnNew" onclick="switchStudent('new')"
                            class="btn btn-sm flex-fill btn-primary">
                            <i class="bi bi-person-plus me-1"></i>Siswa Baru
                        </button>
                        <button type="button" id="btnOld" onclick="switchStudent('old')"
                            class="btn btn-sm flex-fill btn-outline-secondary">
                            <i class="bi bi-person-check me-1"></i>Siswa Lama
                        </button>
                    </div>
                </div>

                {{-- SISWA BARU FIELDS --}}
                <div id="newStudentFields">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Lengkap Siswa <span class="text-danger">*</span></label>
                            <input type="text" name="student_name" class="form-control" value="{{ old('student_name') }}" placeholder="Nama lengkap siswa" oninput="updateQuote()">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">No. WA Siswa</label>
                            <input type="text" name="student_phone" class="form-control" value="{{ old('student_phone') }}" placeholder="08xx-xxxx-xxxx">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Wali</label>
                            <input type="text" name="wali_name" class="form-control" value="{{ old('wali_name') }}" placeholder="Nama orang tua / wali">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">No. WA Wali</label>
                            <input type="text" name="wali_phone" class="form-control" value="{{ old('wali_phone') }}" placeholder="08xx-xxxx-xxxx">
                        </div>
                    </div>
                </div>

                {{-- SISWA LAMA FIELDS --}}
                <div id="oldStudentFields" class="col-12" style="display:none">
                    <label class="form-label fw-semibold">Pilih Siswa <span class="text-danger">*</span></label>
                    <select name="student_id" class="form-select" onchange="updateQuote()">
                        <option value="">— Cari siswa —</option>
                        @foreach($students as $s)
                            <option value="{{ $s->id }}" data-name="{{ $s->name }}" {{ old('student_id')==$s->id?'selected':'' }}>
                                {{ $s->name }} {{ $s->phone ? '('.$s->phone.')' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- CARD 2: KONFIGURASI KELAS & PAKET --}}
        <div class="dashboard-card mb-3">
            <div class="d-flex align-items-center gap-2 mb-3">
                <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,#461256,#c84ddf);display:flex;align-items:center;justify-content:center;color:white;font-size:14px;font-weight:700">2</div>
                <h6 class="fw-bold mb-0">Konfigurasi Kelas & Paket</h6>
            </div>

            <div class="row g-3">
                {{-- TOGGLE PAKET STANDAR / CUSTOM --}}
                <div class="col-12">
                    <div class="d-flex gap-2 mb-3">
                        <input type="hidden" name="is_custom_package" id="isCustomPackage" value="0">
                        <button type="button" id="btnStandard" onclick="switchPackage('standard')"
                            class="btn btn-sm btn-primary flex-fill">
                            <i class="bi bi-box-seam me-1"></i>Paket Standar
                        </button>
                        <button type="button" id="btnCustom" onclick="switchPackage('custom')"
                            class="btn btn-sm btn-outline-secondary flex-fill">
                            <i class="bi bi-pencil-square me-1"></i>Custom Package
                        </button>
                    </div>

                    {{-- PAKET STANDAR --}}
                    <div id="standardPackage">
                        <select name="package_id" class="form-select mb-3" onchange="onPackageChange(this)">
                            <option value="">— Pilih paket standar —</option>
                            @foreach($packages as $pk)
                                <option value="{{ $pk->id }}"
                                    data-price="{{ $pk->harga }}"
                                    data-sessions="{{ $pk->jumlah_pertemuan }}"
                                    data-name="{{ $pk->nama }}"
                                    {{ old('package_id')==$pk->id?'selected':'' }}>
                                    {{ $pk->nama }} — Rp {{ number_format($pk->harga, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="package_price" id="packagePrice" value="{{ old('package_price', 0) }}">
                        <input type="hidden" name="package_name_std" id="packageNameStd" value="">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Metode Absensi <span class="text-danger">*</span></label>
                                <select name="metode_absensi" class="form-select @error('metode_absensi') is-invalid @enderror" required>
                                    <option value="manual"    {{ old('metode_absensi','manual')=='manual'   ?'selected':'' }}>Manual</option>
                                    <option value="otomatis"  {{ old('metode_absensi')=='otomatis'?'selected':'' }}>Otomatis</option>
                                </select>
                                @error('metode_absensi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    {{-- CUSTOM PACKAGE --}}
                    <div id="customPackage" style="display:none">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Nama Paket Custom <span class="text-danger">*</span></label>
                                <input type="text" name="custom_package_name" class="form-control" placeholder="Misal: Paket Khusus UTBK Arif Rahman" value="{{ old('custom_package_name') }}" oninput="updateQuote()">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Kategori Tipe</label>
                                <select name="custom_kategori" class="form-select">
                                    <option value="academic" {{ old('custom_kategori','academic')=='academic'?'selected':'' }}>Academic (Kurikulum Sekolah)</option>
                                    <option value="skill"    {{ old('custom_kategori')=='skill'   ?'selected':'' }}>Skill / Soft-Skill</option>
                                    <option value="kedinasan" {{ old('custom_kategori')=='kedinasan'?'selected':'' }}>Kedinasan</option>
                                    <option value="bahasa"  {{ old('custom_kategori')=='bahasa'  ?'selected':'' }}>Bahasa Asing</option>
                                    <option value="komputer" {{ old('custom_kategori')=='komputer'?'selected':'' }}>Kursus Komputer</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Hubungkan Modul <span class="text-muted fw-normal">(Opsional)</span></label>
                                <select name="course_id" class="form-select">
                                    <option value="">— Pilih Modul Master —</option>
                                    @foreach($courses as $c)
                                        <option value="{{ $c->id }}" {{ old('course_id')==$c->id?'selected':'' }}>{{ $c->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Total Pertemuan (Sesi) <span class="text-danger">*</span></label>
                                <input type="number" name="jumlah_pertemuan" class="form-control" value="{{ old('jumlah_pertemuan', 1) }}" min="1">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Masa Aktif Paket (Bulan) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" name="durasi_bulan" class="form-control" value="{{ old('durasi_bulan', 3) }}" min="1">
                                    <span class="input-group-text">bulan</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Harga Kesepakatan Paket (Deal Price) <span class="text-danger">*</span></label>
                                <input type="number" name="custom_package_price" class="form-control" placeholder="0" value="{{ old('custom_package_price', 0) }}" min="0" oninput="syncCustomPrice()">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Biaya Registrasi Pendaftaran (Deal)</label>
                                <input type="number" name="biaya_registrasi" class="form-control" value="{{ old('biaya_registrasi', 0) }}" min="0" oninput="updateQuote()">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Deskripsi & Catatan Kontrak</label>
                                <textarea name="custom_deskripsi" class="form-control" rows="3" placeholder="Catatan atau deskripsi khusus paket ini…">{{ old('custom_deskripsi') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tipe Kelas <span class="text-danger">*</span></label>
                    <select name="jenis" class="form-select @error('jenis') is-invalid @enderror" required onchange="updateQuote()">
                        <option value="offline" {{ old('jenis')=='offline'?'selected':'' }}>Offline (Reguler)</option>
                        <option value="online"  {{ old('jenis')=='online' ?'selected':'' }}>Online</option>
                        <option value="private" {{ old('jenis','private')=='private'?'selected':'' }}>Private</option>
                    </select>
                    @error('jenis')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        {{-- CARD 3: JADWAL & GURU --}}
        <div class="dashboard-card mb-3">
            <div class="d-flex align-items-center gap-2 mb-3">
                <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,#461256,#c84ddf);display:flex;align-items:center;justify-content:center;color:white;font-size:14px;font-weight:700">3</div>
                <h6 class="fw-bold mb-0">Setup Jadwal & Guru</h6>
            </div>

            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Assign Guru Pengajar <span class="text-danger">*</span></label>
                    <select name="guru_id" class="form-select @error('guru_id') is-invalid @enderror" required onchange="updateTeacherType(this)">
                        <option value="">— Pilih guru —</option>
                        @foreach($teachers as $t)
                            <option value="{{ $t->id }}"
                                data-name="{{ $t->name }}"
                                data-type="{{ $t->tipe_gaji ?? 'contract' }}"
                                {{ old('guru_id')==$t->id?'selected':'' }}>
                                {{ $t->name }} — {{ implode(', ', (array)($t->subjects ?? [])) }}
                            </option>
                        @endforeach
                    </select>
                    @error('guru_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4" id="teacherTypeDisplay" style="display:none">
                    <label class="form-label fw-semibold">Tipe Guru</label>
                    <input type="text" id="teacherTypeText" class="form-control" readonly>
                    <input type="hidden" name="teacher_type" id="teacherType">
                </div>

                <div class="col-md-6" id="customFeeWrap" style="display:none">
                    <label class="form-label fw-semibold">Custom Fee per Sesi (Rp)</label>
                    <input type="number" name="custom_teacher_fee" class="form-control" value="{{ old('custom_teacher_fee', 0) }}" min="0">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tanggal Mulai Kelas <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_mulai" class="form-control @error('tanggal_mulai') is-invalid @enderror"
                           value="{{ old('tanggal_mulai', date('Y-m-d')) }}" required>
                    @error('tanggal_mulai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        {{-- CARD 4: SKEMA TAGIHAN --}}
        <div class="dashboard-card">
            <div class="d-flex align-items-center gap-2 mb-3">
                <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,#461256,#c84ddf);display:flex;align-items:center;justify-content:center;color:white;font-size:14px;font-weight:700">4</div>
                <h6 class="fw-bold mb-0">Skema Tagihan & Aktivasi</h6>
            </div>

            <div class="mb-3">
                <input type="hidden" name="billing_mode" id="billingMode" value="prepaid">
                <div class="d-flex gap-2">
                    <button type="button" id="btnPrepaid" onclick="switchBilling('prepaid')"
                        class="btn btn-sm btn-primary flex-fill">
                        <i class="bi bi-cash-coin me-1"></i>Prabayar (Prepaid)
                    </button>
                    <button type="button" id="btnPostpaid" onclick="switchBilling('postpaid')"
                        class="btn btn-sm btn-outline-secondary flex-fill">
                        <i class="bi bi-receipt me-1"></i>Pascabayar (Per Sesi)
                    </button>
                </div>
            </div>

            <div id="prepaidFields">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Jumlah Cicilan</label>
                        <select name="cicilan" class="form-select">
                            <option value="1">Lunas (1x)</option>
                            <option value="2">2x Cicilan</option>
                            <option value="3">3x Cicilan</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Mulai Ditagihkan</label>
                        <input type="date" name="tagihan_mulai" class="form-control" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Jatuh Tempo</label>
                        <input type="date" name="jatuh_tempo" class="form-control" value="{{ date('Y-m-d', strtotime('+7 days')) }}">
                    </div>
                </div>
            </div>

            <div id="postpaidFields" style="display:none">
                <div class="p-3 rounded-3" style="background:var(--soft-info);border:1.5px solid rgba(2,132,199,.2)">
                    <div class="fw-semibold mb-1" style="font-size:13px;color:#0284c7">
                        <i class="bi bi-receipt me-2"></i>Pascabayar (Per Sesi)
                        <span class="text-muted fw-normal ms-1" style="font-size:12px">Invoice muncul harian/bulanan setelah kelas berjalan.</span>
                    </div>
                    <div class="mt-2 p-2 rounded-2" style="background:rgba(255,255,255,.6);font-size:12.5px;color:#374151">
                        <strong>Informasi Pascabayar</strong><br>
                        Tagihan awal hari ini adalah <strong>Rp 0</strong> <span class="text-muted">(Hanya Biaya Admin jika ada)</span>.
                        Akun siswa akan otomatis aktif. Invoice sesi akan di-generate setiap guru mensubmit Jurnal Mengajar.
                    </div>
                </div>
            </div>

            <div class="mt-4 pt-3 border-top d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4" id="submitBtn">
                    <i class="bi bi-check-circle me-2"></i>Selesaikan Registrasi
                </button>
                <a href="{{ route('admin.registration.index') }}" class="btn btn-outline-secondary px-4">
                    <i class="bi bi-arrow-left me-2"></i>Batal & Kembali
                </a>
            </div>
        </div>

    </div>{{-- /LEFT COL --}}

    {{-- RIGHT COLUMN: LIVE QUOTATION --}}
    <div class="col-lg-4">
        <div class="dashboard-card" style="position:sticky;top:80px">
            <div style="background:linear-gradient(135deg,#260632,#c84ddf);margin:-16px -16px 16px;padding:16px;border-radius:12px 12px 0 0;color:white">
                <h6 class="fw-bold mb-0"><i class="bi bi-receipt me-2"></i>Live Quotation</h6>
                <div style="font-size:11px;opacity:.8">Estimasi biaya real-time</div>
            </div>

            <div id="quoteContent">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted" style="font-size:13px">Nama Siswa</span>
                    <span class="fw-semibold text-end" style="font-size:13px" id="qStudentName">—</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted" style="font-size:13px">Cabang</span>
                    <span class="fw-semibold text-end" style="font-size:13px" id="qBranch">—</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted" style="font-size:13px">Nama Paket</span>
                    <span class="fw-semibold text-end" style="font-size:13px" id="qPackage">—</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted" style="font-size:13px">Skema Tagihan</span>
                    <span class="fw-semibold text-end" style="font-size:13px" id="qBilling">Prabayar</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted" style="font-size:13px">Tipe Kelas</span>
                    <span class="fw-semibold text-end" style="font-size:13px" id="qJenis">Private</span>
                </div>

                <div class="border-top my-3"></div>

                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted" style="font-size:12px">Harga Paket</span>
                    <span style="font-size:12px" id="qPkgPrice">Rp 0</span>
                </div>
                <div class="d-flex justify-content-between mb-1" id="adminFeeRow">
                    <span class="text-muted" style="font-size:12px">Biaya Registrasi <span class="badge" style="background:rgba(246,175,35,.15);color:#e09000;font-size:9px">Siswa Baru</span></span>
                    <span style="font-size:12px">Rp 150.000</span>
                </div>

                <div class="border-top my-2"></div>

                <div class="d-flex justify-content-between align-items-center">
                    <span class="fw-bold">Total Awal Tagihan</span>
                    <span class="fw-bold text-primary" style="font-size:18px" id="qTotal">Rp 0</span>
                </div>

                <div class="mt-3 p-2 rounded" style="background:var(--soft-primary);font-size:11px;color:#461256">
                    <i class="bi bi-info-circle me-1"></i>
                    Estimasi di atas belum termasuk biaya cicilan jika ada.
                </div>
            </div>
        </div>
    </div>

</div>{{-- /ROW --}}
</form>

</div>
@push('scripts')
<script>
let isNewStudent = true;
let isCustomPkg  = false;
let billingMode  = 'prepaid';
let packagePrice = 0;

const branches = @json($branches->pluck('name','id'));

function switchStudent(type) {
    isNewStudent = (type === 'new');
    document.getElementById('isNewStudent').value = isNewStudent ? '1' : '0';
    document.getElementById('newStudentFields').style.display = isNewStudent ? '' : 'none';
    document.getElementById('oldStudentFields').style.display = isNewStudent ? 'none' : '';
    document.getElementById('btnNew').className = 'btn btn-sm flex-fill ' + (isNewStudent ? 'btn-primary' : 'btn-outline-secondary');
    document.getElementById('btnOld').className = 'btn btn-sm flex-fill ' + (!isNewStudent ? 'btn-primary' : 'btn-outline-secondary');
    document.getElementById('adminFeeRow').style.display = isNewStudent ? '' : 'none';
    updateQuote();
}

function switchPackage(type) {
    isCustomPkg = (type === 'custom');
    document.getElementById('isCustomPackage').value = isCustomPkg ? '1' : '0';
    document.getElementById('standardPackage').style.display = isCustomPkg ? 'none' : '';
    document.getElementById('customPackage').style.display   = isCustomPkg ? '' : 'none';
    document.getElementById('btnStandard').className = 'btn btn-sm flex-fill ' + (!isCustomPkg ? 'btn-primary' : 'btn-outline-secondary');
    document.getElementById('btnCustom').className   = 'btn btn-sm flex-fill ' + (isCustomPkg  ? 'btn-primary' : 'btn-outline-secondary');
    updateQuote();
}

function switchBilling(mode) {
    billingMode = mode;
    document.getElementById('billingMode').value = mode;
    document.getElementById('prepaidFields').style.display  = mode === 'prepaid'  ? '' : 'none';
    document.getElementById('postpaidFields').style.display = mode === 'postpaid' ? '' : 'none';
    document.getElementById('btnPrepaid').className  = 'btn btn-sm flex-fill ' + (mode === 'prepaid'  ? 'btn-primary' : 'btn-outline-secondary');
    document.getElementById('btnPostpaid').className = 'btn btn-sm flex-fill ' + (mode === 'postpaid' ? 'btn-primary' : 'btn-outline-secondary');
    document.getElementById('qBilling').textContent = mode === 'prepaid' ? 'Prabayar' : 'Pascabayar';
    updateQuote();
}

function onPackageChange(sel) {
    const opt = sel.options[sel.selectedIndex];
    packagePrice = parseFloat(opt.dataset.price || 0);
    document.getElementById('packagePrice').value = packagePrice;
    document.getElementById('packageNameStd').value = opt.dataset.name || '';
    document.getElementById('qPackage').textContent = opt.dataset.name || '—';
    updateQuote();
}

function syncCustomPrice() {
    packagePrice = parseFloat(document.querySelector('[name=custom_package_price]').value || 0);
    document.getElementById('packagePrice').value = packagePrice;
    updateQuote();
}

function updateTeacherType(sel) {
    const opt = sel.options[sel.selectedIndex];
    const type = opt.dataset.type || 'contract';
    document.getElementById('teacherType').value = type;
    document.getElementById('teacherTypeText').value = type === 'freelance' ? 'Freelance' : 'Contract';
    document.getElementById('teacherTypeDisplay').style.display = sel.value ? '' : 'none';
    document.getElementById('customFeeWrap').style.display = type === 'freelance' ? '' : 'none';
}

function formatRupiah(num) {
    return 'Rp ' + Math.round(num).toLocaleString('id-ID');
}

function updateQuote() {
    const branchSel = document.getElementById('branchSelect');
    const branchName = branchSel.options[branchSel.selectedIndex]?.text || '—';
    document.getElementById('qBranch').textContent = branchName === '— Pilih Cabang —' ? '—' : branchName;

    let studentName = '—';
    if (isNewStudent) {
        studentName = document.querySelector('[name=student_name]')?.value || '—';
    } else {
        const sel = document.querySelector('[name=student_id]');
        studentName = sel?.options[sel.selectedIndex]?.dataset.name || '—';
    }
    document.getElementById('qStudentName').textContent = studentName || '—';

    const jenisSel = document.querySelector('[name=jenis]');
    document.getElementById('qJenis').textContent = jenisSel ? (jenisSel.value === 'private' ? 'Private' : jenisSel.value === 'online' ? 'Online' : 'Offline') : '—';

    if (isCustomPkg) {
        const cpName = document.querySelector('[name=custom_package_name]')?.value;
        document.getElementById('qPackage').textContent = cpName || '— (Custom)';
        packagePrice = parseFloat(document.querySelector('[name=custom_package_price]')?.value || 0);
    }

    const adminFee = isNewStudent ? 150000 : 0;
    const total    = billingMode === 'prepaid' ? packagePrice + adminFee : 0;

    document.getElementById('qPkgPrice').textContent = formatRupiah(packagePrice);
    document.getElementById('qTotal').textContent    = formatRupiah(total);
    document.getElementById('adminFeeRow').style.display = isNewStudent ? '' : 'none';
}

updateQuote();
</script>
@endpush
@endsection
