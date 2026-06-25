<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Formulir Pendaftaran | Smart Center Indonesia</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            min-height: 100vh;
            background:
                radial-gradient(circle at top right, rgba(200,77,223,.12), transparent 18%),
                linear-gradient(180deg, #f7f3ff 0%, #f5f1ff 100%);
            font-family: 'Inter', 'Segoe UI', sans-serif;
            padding: 0;
            margin: 0;
            position: relative;
            overflow-x: hidden;
        }
        body::before {
            content:''; position:fixed; width:520px; height:520px;
            background:radial-gradient(circle,rgba(104,17,126,.08) 0%,transparent 72%);
            top:-180px; right:-120px; border-radius:50%;
            pointer-events:none;
        }
        body::after {
            content:''; position:fixed; width:420px; height:420px;
            background:radial-gradient(circle,rgba(200,77,223,.06) 0%,transparent 68%);
            bottom:-180px; left:-120px; border-radius:50%;
            pointer-events:none;
        }

        .form-wrapper {
            width: 100%;
            min-height: 100vh;
            margin: 0;
            position: relative;
            z-index: 1;
        }
        .form-layout {
            display: grid;
            grid-template-columns: 50% 50%;
            min-height: 100vh;
            background: #fff;
            border-radius: 0;
            overflow: hidden;
            box-shadow: none;
        }
        .visual-panel {
            position: relative;
            background:
                linear-gradient(135deg, rgba(38,6,50,.96), rgba(70,18,86,.92), rgba(104,17,126,.88)),
                radial-gradient(circle at top right, rgba(200,77,223,.18), transparent 18%),
                radial-gradient(circle at bottom left, rgba(255,255,255,.06), transparent 16%);
            color: #fff;
            padding: 2.75rem 2rem;
            display: flex;
            align-items: center;
        }
        .visual-panel::before,
        .visual-panel::after {
            content:'';
            position:absolute;
            border-radius:50%;
            background:rgba(255,255,255,.06);
        }
        .visual-panel::before { width:210px; height:210px; top:-70px; right:-50px; }
        .visual-panel::after { width:140px; height:140px; bottom:-50px; left:-30px; }
        .visual-content {
            position: relative;
            z-index: 1;
            max-width: 460px;
        }
        .visual-badge {
            display:inline-flex; align-items:center; gap:6px;
            background:rgba(255,255,255,.08);
            border:1px solid rgba(255,255,255,.12);
            color:#f7dfff;
            font-size:.72rem; font-weight:700;
            letter-spacing:.14em; text-transform:uppercase;
            border-radius:999px; padding:6px 12px;
            margin-bottom:1rem;
        }
        .visual-content h2 {
            font-family:'Plus Jakarta Sans',sans-serif;
            font-size: clamp(2rem, 3vw, 3rem);
            font-weight:800;
            line-height:1.1;
            margin-bottom:.8rem;
        }
        .visual-content p {
            color:rgba(255,255,255,.84);
            font-size: .98rem;
            line-height:1.6;
            margin-bottom:1.2rem;
        }
        .visual-points {
            list-style:none;
            margin:0;
            padding:0;
            display:grid;
            gap:.65rem;
        }
        .visual-points li {
            display:flex;
            align-items:center;
            gap:.7rem;
            font-size:.9rem;
            color:#f7f0ff;
        }
        .visual-points li span {
            width:28px; height:28px;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            background:rgba(255,255,255,.08);
            border-radius:50%;
            flex-shrink:0;
        }
        .form-panel {
            background:#fff;
            padding: 0;
            display:flex;
            flex-direction:column;
        }
        .back-link {
            display: none;
        }
        .form-card {
            background:#fff;
            border-radius: 0;
            border: 0;
            overflow:hidden;
            height:100%;
            display:flex;
            flex-direction:column;
        }

        .form-header {
            background:
                linear-gradient(135deg, #260632 0%, #461256 55%, #68117e 100%);
            padding: 1.5rem 1rem 1.1rem;
            color: white;
            text-align: center;
            position: relative;
            overflow: hidden;
            border-radius: 0;
        }
        .form-header::before,
        .form-header::after {
            content:''; position:absolute; border-radius:50%; background:rgba(255,255,255,.06);
        }
        .form-header::before { width:180px; height:180px; top:-90px; right:-40px; }
        .form-header::after { width:140px; height:140px; bottom:-60px; left:-30px; }
        .brand-badge {
            display:inline-flex; align-items:center; gap:6px;
            background:rgba(200,77,223,.18);
            border:1px solid rgba(200,77,223,.45);
            color:#f6d7ff;
            font-size:.72rem;
            font-weight:700;
            letter-spacing:.12em;
            text-transform:uppercase;
            padding:5px 12px;
            border-radius:999px;
            margin-bottom:10px;
            position:relative;
            z-index:1;
        }
        .form-header .brand-logo {
            width:54px; height:54px; border-radius:15px;
            background:linear-gradient(135deg, rgba(255,255,255,.18), rgba(255,255,255,.08));
            display:flex; align-items:center; justify-content:center;
            font-size:24px; margin:0 auto 10px;
            border:1px solid rgba(255,255,255,.18);
            box-shadow: inset 0 1px 0 rgba(255,255,255,.08);
        }
        .form-header h4 {
            font-size:1.5rem; font-weight:800; margin:0;
            font-family:'Plus Jakarta Sans',sans-serif;
            letter-spacing:.04em;
        }
        .form-header p  {
            font-size:.78rem; opacity:.78; margin:6px 0 0;
            letter-spacing:.1em; text-transform:uppercase;
        }

        .form-body {
            background: #fff;
            padding: 0;
            overflow: hidden;
            flex:1;
        }

        .stepper {
            display: flex;
            gap: 0.35rem;
            padding: 0.9rem 0.9rem 0.75rem;
            background: #fff;
            border-bottom: 1px solid #f1eefb;
            overflow-x: auto;
        }
        .stepper-item {
            flex: 1;
            min-width: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.35rem;
            padding: 0.55rem 0.45rem;
            border-radius: 999px;
            background: #f7f5ff;
            color: #9ca3af;
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            transition: all .2s ease;
            white-space: nowrap;
        }
        .stepper-item.active {
            background: linear-gradient(135deg, #fdf4ff, #f7e7ff);
            color: #461256;
        }
        .stepper-item.completed {
            background: #eefbf6;
            color: #0f9f6e;
        }
        .stepper-item .step-dot {
            width: 18px;
            height: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: currentColor;
            color: #fff;
            font-size: 0.66rem;
            font-weight: 800;
        }

        .step-panel { display: none; }
        .step-panel.active { display: block; }

        .step-actions {
            display: flex;
            justify-content: space-between;
            gap: 0.75rem;
            padding: 0 0.9rem 0.9rem;
        }
        .btn-step {
            border: none;
            border-radius: 12px;
            padding: 0.7rem 1rem;
            font-size: 0.9rem;
            font-weight: 700;
            transition: all .2s ease;
        }
        .btn-step.btn-prev {
            background: #f5f5f7;
            color: #374151;
        }
        .btn-step.btn-next {
            background: linear-gradient(135deg, #68117e, #c84ddf);
            color: #fff;
        }
        .btn-step:hover {
            transform: translateY(-1px);
        }

        .section-card {
            padding: 1rem 1rem 0.9rem;
            border-bottom: 1px solid #f3f0ff;
            background: #fff;
        }
        .section-card:last-child { border-bottom: none; }
        .section-title {
            display: flex; align-items: center; gap: 10px;
            font-size: .78rem; font-weight: 800; text-transform: uppercase;
            letter-spacing: .08em; color: #461256; margin-bottom: .9rem;
        }
        .section-title::before {
            content:''; width:4px; height:18px;
            background: linear-gradient(135deg, #461256, #c84ddf);
            border-radius: 4px; flex-shrink: 0;
        }
        .section-card:hover {
            background: linear-gradient(180deg, #fff 0%, #fcfaff 100%);
        }

        .form-label {
            font-weight: 700; font-size: .82rem; color: #344054; margin-bottom: .35rem;
        }
        .form-control, .form-select {
            border: 1.5px solid #e7e3ff; border-radius: 12px;
            padding: .68rem .9rem; font-size: .9rem;
            background: #fcfbff; transition: border-color .2s, box-shadow .2s;
            color: #1f2937;
        }
        .form-control:focus, .form-select:focus {
            border-color: #c84ddf; box-shadow: 0 0 0 4px rgba(200,77,223,.1);
            background: #fff; outline: none;
        }
        .form-control.is-invalid, .form-select.is-invalid { border-color: #ef4444; }
        .invalid-feedback { font-size: .78rem; color: #ef4444; }

        .check-grid { display: flex; flex-wrap: wrap; gap: 8px; }
        .check-pill {
            display: flex; align-items: center; gap: 7px;
            padding: 7px 14px; border: 1.5px solid #e7e3ff; border-radius: 999px;
            font-size: .82rem; cursor: pointer; transition: all .2s;
            background: #fcfbff; user-select: none; color: #475467;
        }
        .check-pill input { display: none; }
        .check-pill:hover { border-color: #c84ddf; background: #fdf4ff; }
        .check-pill.selected { border-color: #c84ddf; background: #fdf4ff; color: #461256; font-weight: 700; }
        .check-pill.selected::before { content: '✓ '; color: #c84ddf; }

        .option-btn-group { display: flex; flex-wrap: wrap; gap: 8px; }
        .option-btn {
            padding: 9px 18px; border: 1.5px solid #e7e3ff; border-radius: 999px;
            font-size: .88rem; cursor: pointer; transition: all .2s; background: #fcfbff;
            font-weight: 600; color: #475467;
        }
        .option-btn.active { border-color: #c84ddf; background: linear-gradient(135deg, #fdf4ff, #f7e7ff); color: #461256; font-weight: 700; }

        .program-group { margin-bottom: 1rem; }
        .program-group-label {
            font-size: 11px; font-weight: 700; text-transform: uppercase;
            letter-spacing: .06em; color: #9ca3af; margin-bottom: 8px;
        }

        .accordion-cat {
            display: flex; align-items: center; justify-content: space-between;
            gap: 8px; padding: 10px 14px; border: 1.5px solid #e7e3ff;
            border-radius: 12px; cursor: pointer; background: #fcfbff;
            font-size: .88rem; font-weight: 700; color: #461256;
            transition: all .2s; user-select: none; margin-bottom: 6px;
        }
        .accordion-cat:hover { border-color: #c84ddf; background: #fdf4ff; }
        .accordion-cat.open { border-color: #c84ddf; background: linear-gradient(135deg,#fdf4ff,#f7e7ff); }
        .accordion-cat .acc-arrow { transition: transform .2s; font-size: .7rem; color: #9ca3af; }
        .accordion-cat.open .acc-arrow { transform: rotate(90deg); color: #c84ddf; }
        .accordion-body { display: none; padding: 6px 2px 10px; }
        .accordion-body.open { display: block; }

        .schedule-row {
            display: flex; align-items: center; gap: 10px;
            padding: 8px 12px; border: 1.5px solid #e7e3ff;
            border-radius: 12px; background: #fcfbff; margin-bottom: 8px;
        }
        .schedule-row .day-label {
            min-width: 62px; font-size: .82rem; font-weight: 700; color: #461256;
        }
        .schedule-row .form-control { font-size: .85rem; border-radius: 8px; }
        .schedule-row .btn-add-slot {
            flex-shrink: 0; border: 1.5px dashed #c84ddf; background: none;
            color: #c84ddf; border-radius: 8px; padding: 5px 10px;
            font-size: .75rem; font-weight: 700; white-space: nowrap;
            cursor: pointer; transition: all .2s;
        }
        .schedule-row .btn-add-slot:hover { background: #fdf4ff; }
        .time-slot { display: flex; align-items: center; gap: 6px; }
        .time-slot .btn-remove-slot {
            background: none; border: none; color: #ef4444; cursor: pointer;
            font-size: .75rem; padding: 2px 4px; border-radius: 4px;
            transition: background .2s;
        }
        .time-slot .btn-remove-slot:hover { background: #fef2f2; }

        .btn-submit {
            background: linear-gradient(135deg, #68117e, #c84ddf 50%, #c84ddf);
            background-size: 200% auto; border: none; border-radius: 12px; padding: 1rem;
            font-size: 1rem; font-weight: 700; color: white; width: 100%;
            transition: background-position .4s, transform .2s, box-shadow .2s;
        }
        .btn-submit:hover { background-position: right center; transform: translateY(-2px); box-shadow: 0 10px 28px rgba(200,77,223,.45); color: white; }
        .btn-submit:active { transform: translateY(0); }

        .day-check-group { display: flex; flex-wrap: wrap; gap: 8px; }
        .day-pill {
            width: 44px; height: 44px; border: 1.5px solid #e7e3ff; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; font-weight: 700; cursor: pointer;
            transition: all .2s; background: #fcfbff; user-select: none; color: #475467;
        }
        .day-pill:hover { border-color: #c84ddf; background: #fdf4ff; }
        .day-pill.selected { border-color: #c84ddf; background: linear-gradient(135deg,#461256,#c84ddf); color: white; }

        @media(max-width: 992px) {
            .form-layout {
                grid-template-columns: 1fr;
            }
            .visual-panel {
                display: none;
            }
        }
        @media(max-width: 768px) {
            body { padding: 0; }
            .form-wrapper { width: 100%; }
            .form-panel { padding: 0; }
            .back-link { margin: 0.8rem 0 0.7rem 0.7rem; }
            .form-header { padding: 1.2rem .9rem; }
            .form-header h4 { font-size: 1.2rem; }
            .stepper { padding: .8rem .7rem; }
            .stepper-item { font-size: .58rem; padding: .5rem .3rem; }
            .section-card { padding: .95rem .8rem; }
        }
        @media(max-width: 600px) {
            .stepper-item span:last-child { display: none; }
            .stepper-item { justify-content: center; }
        }
    </style>
</head>
<body>

<div class="form-wrapper">
    <div class="form-layout">
        <section class="visual-panel">
            <div class="visual-content">
                <span class="visual-badge"><i class="bi bi-mortarboard-fill me-1"></i> Smart Center Indonesia</span>
                <h2>Daftar sekarang dan mulai perjalanan belajar Anda.</h2>
                <p>Tim pengajar profesional siap membantu siswa belajar dengan metode yang fleksibel, personal, dan sesuai kebutuhan.</p>
                <ul class="visual-points">
                    <li><span><i class="bi bi-check2"></i></span> Pilihan program sesuai target belajar</li>
                    <li><span><i class="bi bi-check2"></i></span> Jadwal fleksibel dan tutor berpengalaman</li>
                    <li><span><i class="bi bi-check2"></i></span> Pendampingan dari awal hingga selesai</li>
                </ul>
            </div>
        </section>

        <section class="form-panel">
            <div class="form-card">
                <div class="form-header">
                    <div class="brand-badge"><i class="bi bi-mortarboard-fill me-1"></i> Smart Center Indonesia</div>
                    <div class="brand-logo"><i class="bi bi-mortarboard-fill"></i></div>
                    <h4>Formulir Pendaftaran Siswa Baru</h4>
                    <p>Isi data dengan lengkap dan benar</p>
                </div>

                <div class="form-body">

                    @if ($errors->any())
                    <div class="mx-3 mt-3 d-flex align-items-start gap-2 p-3 rounded-3" style="background:#fef2f2;border:1.5px solid #fecaca;color:#dc2626">
                        <i class="bi bi-exclamation-triangle-fill flex-shrink-0 mt-1"></i>
                        <div style="font-size:.85rem">
                            <strong>Harap perbaiki:</strong>
                            <ul class="mb-0 mt-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                        </div>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}" id="regForm">
                        @csrf

                        <div class="stepper">
                            <div class="stepper-item active" data-stepper="1"><span class="step-dot">1</span><span>Data Siswa</span></div>
                            <div class="stepper-item" data-stepper="2"><span class="step-dot">2</span><span>Data Orang Tua</span></div>
                            <div class="stepper-item" data-stepper="3"><span class="step-dot">3</span><span>Program Belajar</span></div>
                            <div class="stepper-item" data-stepper="4"><span class="step-dot">4</span><span>Program Diminati</span></div>
                            <div class="stepper-item" data-stepper="5"><span class="step-dot">5</span><span>Jadwal & Kirim</span></div>
                        </div>

            {{-- STEP 1: DATA SISWA --}}
            <div class="step-panel active" data-step="1">
                <div class="section-card">
                    <div class="section-title">Data Siswa</div>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}" placeholder="Nama lengkap siswa" required autofocus>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Tempat Lahir</label>
                            <input type="text" name="birth_place" class="form-control" value="{{ old('birth_place') }}" placeholder="Kota tempat lahir">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Lahir</label>
                            <input type="date" name="birth_date" class="form-control" value="{{ old('birth_date') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Jenis Kelamin</label>
                            <select name="gender" class="form-select">
                                <option value="">Pilih...</option>
                                <option value="L" {{ old('gender')=='L'?'selected':'' }}>Laki-laki</option>
                                <option value="P" {{ old('gender')=='P'?'selected':'' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Alamat Tinggal</label>
                            <textarea name="address" class="form-control" rows="2" placeholder="Alamat lengkap tempat tinggal">{{ old('address') }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nomor WA/HP <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">+62</span>
                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                       value="{{ old('phone') }}" placeholder="8xxxxxxxxxx" required>
                            </div>
                            @error('phone')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kategori Peserta Didik</label>
                            <select name="education_level" id="education_level" class="form-select" onchange="handleEducationLevel(this.value)">
                                <option value="">-- Pilih Kategori --</option>
                                <option value="Pra Sekolah (PAUD/TK)" {{ old('education_level')=='Pra Sekolah (PAUD/TK)'?'selected':'' }}>Pra Sekolah (PAUD/TK)</option>
                                <option value="Sekolah Dasar (SD)" {{ old('education_level')=='Sekolah Dasar (SD)'?'selected':'' }}>Sekolah Dasar (SD)</option>
                                <option value="Sekolah Menengah Pertama (SMP)" {{ old('education_level')=='Sekolah Menengah Pertama (SMP)'?'selected':'' }}>Sekolah Menengah Pertama (SMP)</option>
                                <option value="Sekolah Menengah Atas/Kejuruan (SMA/SMK)" {{ old('education_level')=='Sekolah Menengah Atas/Kejuruan (SMA/SMK)'?'selected':'' }}>Sekolah Menengah Atas/Kejuruan (SMA/SMK)</option>
                                <option value="Mahasiswa" {{ old('education_level')=='Mahasiswa'?'selected':'' }}>Mahasiswa</option>
                                <option value="Umum" {{ old('education_level')=='Umum'?'selected':'' }}>Umum</option>
                            </select>
                        </div>

                        {{-- Parent fields shown inline when Pra Sekolah is selected --}}
                        <div id="praSekolahFields" class="col-12 {{ old('education_level')=='Pra Sekolah (PAUD/TK)' ? '' : 'd-none' }}">
                            <div class="row g-3 p-3 rounded-3" style="background:#fdf4ff;border:1.5px solid #e9d5ff;">
                                <div class="col-12" style="font-size:.78rem;font-weight:800;text-transform:uppercase;letter-spacing:.07em;color:#461256">
                                    <i class="bi bi-people-fill me-1"></i> Data Orang Tua / Wali
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Nama Orang Tua / Wali <span class="text-danger">*</span></label>
                                    <input type="text" name="parent_name" id="parent_name_step1" class="form-control" value="{{ old('parent_name') }}" placeholder="Nama lengkap orang tua/wali">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">HP Orang Tua / Wali <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">+62</span>
                                        <input type="text" name="parent_phone" id="parent_phone_step1" class="form-control" value="{{ old('parent_phone') }}" placeholder="8xxxxxxxxxx">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="step-actions">
                    <div></div>
                    <button type="button" class="btn-step btn-next" data-action="next">Lanjut</button>
                </div>
            </div>

            {{-- STEP 2: DATA ORANG TUA --}}
            <div class="step-panel" data-step="2">
                <div class="section-card">
                    <div class="section-title">Data Orang Tua / Wali</div>
                    <p class="text-muted mb-3" style="font-size:12px">Kosongkan jika sudah diisi di langkah sebelumnya (Pra Sekolah)</p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Orang Tua/Wali</label>
                            <input type="text" name="parent_name" class="form-control" value="{{ old('parent_name') }}" placeholder="Nama lengkap orang tua/wali">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nomor WA/HP Orang Tua/Wali</label>
                            <div class="input-group">
                                <span class="input-group-text">+62</span>
                                <input type="text" name="parent_phone" class="form-control" value="{{ old('parent_phone') }}" placeholder="8xxxxxxxxxx">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="step-actions">
                    <button type="button" class="btn-step btn-prev" data-action="prev">Sebelumnya</button>
                    <button type="button" class="btn-step btn-next" data-action="next">Lanjut</button>
                </div>
            </div>

            {{-- STEP 3: PROGRAM BELAJAR --}}
            <div class="step-panel" data-step="3">
                <div class="section-card">
                    <div class="section-title">Program Belajar</div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label">Program Belajar</label>
                            <div class="option-btn-group" id="programGroup">
                                <div class="option-btn {{ old('program_belajar','kelas') == 'kelas' ? 'active' : '' }}" data-val="kelas" onclick="pickProgram('kelas',this)">Kelas</div>
                                <div class="option-btn {{ old('program_belajar','kelas') == 'privat' ? 'active' : '' }}" data-val="privat" onclick="pickProgram('privat',this)">Privat</div>
                            </div>
                            <input type="hidden" name="program_belajar" id="program_belajar" value="{{ old('program_belajar','kelas') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Sistem Belajar</label>
                            <div class="option-btn-group" id="sistemGroup">
                                <div class="option-btn active" data-val="online" onclick="pickOption('sistem','online',this)">Online (Daring)</div>
                                <div class="option-btn" data-val="offline" onclick="pickOption('sistem','offline',this)">Offline (Tatap Muka)</div>
                            </div>
                            <input type="hidden" name="sistem_belajar" id="sistem_belajar" value="{{ old('sistem_belajar','online') }}">
                        </div>
                        <div class="col-12" id="tempatBelajarSection">
                            <label class="form-label">Tempat Belajar</label>
                            <div class="option-btn-group" id="tempatGroup">
                                <div class="option-btn active" data-val="kantor" onclick="pickOption('tempat','kantor',this)">Belajar di Kantor</div>
                                <div class="option-btn d-none" data-val="rumah" id="tempatRumahBtn" onclick="pickOption('tempat','rumah',this)">Guru ke Rumah</div>
                            </div>
                            <input type="hidden" name="tempat_belajar" id="tempat_belajar" value="{{ old('tempat_belajar','kantor') }}">
                        </div>
                    </div>
                </div>
                <div class="step-actions">
                    <button type="button" class="btn-step btn-prev" data-action="prev">Sebelumnya</button>
                    <button type="button" class="btn-step btn-next" data-action="next">Lanjut</button>
                </div>
            </div>

            {{-- STEP 4: PROGRAM YANG DIMINATI --}}
            <div class="step-panel" data-step="4">
                <div class="section-card">
                    <div class="section-title">Program yang Diminati</div>
                    <p class="text-muted mb-3" style="font-size:13px">Pilih satu atau lebih program yang ingin diikuti</p>

                    @if($subjects->isEmpty())
                        <p class="text-muted" style="font-size:.9rem">Belum ada program tersedia. Silakan hubungi admin.</p>
                    @else
                        @php
                            $jenisLabels = [
                                'komputer'  => 'Kursus Komputer',
                                'bahasa'    => 'Kursus Bahasa Asing',
                                'mapel'     => 'Mata Pelajaran',
                                'kedinasan' => 'Program Kedinasan',
                                'akpol'     => 'AKPOL / AKMIL / BINTARA',
                                'cpns'      => 'CPNS',
                                'bumn'      => 'BUMN',
                            ];
                            $jenisIcons = [
                                'komputer'  => 'bi-pc-display',
                                'bahasa'    => 'bi-translate',
                                'mapel'     => 'bi-journal-bookmark',
                                'kedinasan' => 'bi-building',
                                'cpns'      => 'bi-file-earmark-person',
                                'bumn'      => 'bi-briefcase',
                                'akpol'     => 'bi-shield',
                            ];
                            $jenisOrder = ['komputer','bahasa','mapel','kedinasan','cpns','bumn','akpol'];
                        @endphp

                        {{-- All program types as accordion --}}
                        <div class="mt-2">
                            @foreach($jenisOrder as $jenis)
                                @if(!$subjects->has($jenis)) @continue @endif
                                @php $items = $subjects[$jenis]; @endphp
                                <div class="mb-2" id="section-{{ $jenis }}">
                                    <div class="accordion-cat" onclick="toggleAccordion('acc-{{ $jenis }}', this)">
                                        <span>
                                            <i class="bi {{ $jenisIcons[$jenis] ?? 'bi-journal' }} me-2" style="color:#c84ddf"></i>
                                            {{ $jenisLabels[$jenis] ?? ucfirst($jenis) }}
                                            <span class="badge ms-2" style="background:rgba(200,77,223,.12);color:#c84ddf;font-size:.63rem;font-weight:600;border-radius:10px;padding:2px 7px">{{ $items->count() }}</span>
                                        </span>
                                        <i class="bi bi-chevron-right acc-arrow"></i>
                                    </div>
                                    <div class="accordion-body {{ collect(old('program_minat', []))->intersect($items->pluck('nama'))->isNotEmpty() ? 'open' : '' }}" id="acc-{{ $jenis }}">
                                        <div class="check-grid">
                                            @foreach($items as $course)
                                            <label class="check-pill {{ in_array($course->nama, old('program_minat', [])) ? 'selected' : '' }}" onclick="togglePill(this)">
                                                <input type="checkbox" name="program_minat[]" value="{{ $course->nama }}"
                                                       {{ in_array($course->nama, old('program_minat', [])) ? 'checked' : '' }}>
                                                {{ $course->nama }}
                                            </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="step-actions">
                    <button type="button" class="btn-step btn-prev" data-action="prev">Sebelumnya</button>
                    <button type="button" class="btn-step btn-next" data-action="next">Lanjut</button>
                </div>
            </div>

            {{-- STEP 5: JADWAL BELAJAR + SUBMIT --}}
            <div class="step-panel" data-step="5">
                <div class="section-card">
                    <div class="section-title">Jadwal Belajar</div>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Hari Belajar</label>
                            <div class="day-check-group">
                                @php
                                    $days    = ['Sen','Sel','Rab','Kam','Jum','Sab','Min'];
                                    $dayVals = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
                                    $oldHari = old('hari_belajar', []);
                                @endphp
                                @foreach($days as $i => $d)
                                <label class="day-pill {{ in_array($dayVals[$i], $oldHari) ? 'selected' : '' }}"
                                       onclick="toggleDay(this, '{{ $dayVals[$i] }}')">
                                    <input type="checkbox" name="hari_belajar[]" value="{{ $dayVals[$i] }}" style="display:none"
                                           {{ in_array($dayVals[$i], $oldHari) ? 'checked' : '' }}>
                                    {{ $d }}
                                </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Per-day time schedule --}}
                        <div class="col-12" id="dayScheduleWrapper" style="{{ empty($oldHari) ? 'display:none' : '' }}">
                            <label class="form-label">Jam Belajar per Hari
                                <span class="text-muted fw-normal" style="font-size:.75rem">(isi jam untuk setiap hari yang dipilih)</span>
                            </label>
                            <div id="dayScheduleContainer">
                                @foreach($dayVals as $dayVal)
                                    @if(in_array($dayVal, $oldHari))
                                    @php $oldSlots = old("jam_detail.{$dayVal}", ['']); @endphp
                                    <div class="schedule-row" id="srow-{{ $dayVal }}">
                                        <div class="day-label">{{ $dayVal }}</div>
                                        <div class="flex-fill" id="slots-{{ $dayVal }}">
                                            @foreach((array)$oldSlots as $si => $slot)
                                            <div class="time-slot {{ $si > 0 ? 'mt-1' : '' }}">
                                                <input type="text" name="jam_detail[{{ $dayVal }}][]"
                                                       class="form-control"
                                                       placeholder="cth. 10:00 - 12:00"
                                                       value="{{ $slot }}" autocomplete="off">
                                                @if($si > 0)
                                                <button type="button" class="btn-remove-slot" onclick="removeSlot(this)" title="Hapus"><i class="bi bi-x"></i></button>
                                                @endif
                                            </div>
                                            @endforeach
                                        </div>
                                        <button type="button" class="btn-add-slot" onclick="addSlot('{{ $dayVal }}')">
                                            <i class="bi bi-plus me-1"></i>Tambah
                                        </button>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tanggal Mulai Belajar</label>
                            <input type="date" name="tanggal_mulai" class="form-control" value="{{ old('tanggal_mulai') }}">
                        </div>
                    </div>
                </div>
                <div class="section-card">
                    <button type="submit" class="btn btn-submit" id="regBtn">
                        <span id="regText"><i class="bi bi-send-check me-2"></i>Kirim Formulir Pendaftaran</span>
                        <span id="regLoading" class="d-none">
                            <span class="spinner-border spinner-border-sm me-2"></span>Memproses...
                        </span>
                    </button>
                    <div class="text-center mt-3" style="font-size:.8rem;color:#9ca3af">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" style="color:#c84ddf;font-weight:600;text-decoration:none">Masuk di sini</a>
                    </div>
                </div>
                <div class="step-actions">
                    <button type="button" class="btn-step btn-prev" data-action="prev">Sebelumnya</button>
                    <div></div>
                </div>
            </div>

                    </form>
                </div>
            </div>
        </section>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function pickOption(group, val, el) {
    const groupMap = { 'sistem': 'sistem_belajar', 'tempat': 'tempat_belajar' };
    el.closest('.option-btn-group').querySelectorAll('.option-btn').forEach(b => b.classList.remove('active'));
    el.classList.add('active');
    document.getElementById(groupMap[group]).value = val;
}

function pickProgram(val, el) {
    el.closest('.option-btn-group').querySelectorAll('.option-btn').forEach(b => b.classList.remove('active'));
    el.classList.add('active');
    document.getElementById('program_belajar').value = val;

    const rumahBtn  = document.getElementById('tempatRumahBtn');
    const kantorBtn = document.querySelector('#tempatGroup .option-btn[data-val="kantor"]');
    if (val === 'privat') {
        rumahBtn.classList.remove('d-none');
    } else {
        rumahBtn.classList.add('d-none');
        rumahBtn.classList.remove('active');
        kantorBtn.classList.add('active');
        document.getElementById('tempat_belajar').value = 'kantor';
    }

}

(function initProgramState() {
    const prog = document.getElementById('program_belajar');
    if (!prog) return;
    if (prog.value === 'privat') {
        const rumahBtn = document.getElementById('tempatRumahBtn');
        if (rumahBtn) rumahBtn.classList.remove('d-none');
    }
})();

function togglePill(el) {
    const cb = el.querySelector('input[type=checkbox]');
    setTimeout(() => { el.classList.toggle('selected', cb.checked); }, 0);
}

// Accordion for government program categories
function toggleAccordion(id, headerEl) {
    const body = document.getElementById(id);
    if (!body) return;
    const isOpen = body.classList.toggle('open');
    if (headerEl) headerEl.classList.toggle('open', isOpen);
}

// Auto-open accordion if any item was pre-checked (old() repopulation)
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.accordion-body').forEach(body => {
        if (body.querySelector('input:checked')) {
            body.classList.add('open');
            const header = body.previousElementSibling;
            if (header && header.classList.contains('accordion-cat')) header.classList.add('open');
        }
    });
});

// ── Per-day schedule ──────────────────────────────────────────────
const DAY_ORDER = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];

function toggleDay(el, dayName) {
    const cb = el.querySelector('input[type=checkbox]');
    setTimeout(() => {
        const checked = cb.checked;
        el.classList.toggle('selected', checked);
        if (checked) addDayRow(dayName);
        else removeDayRow(dayName);
        updateScheduleWrapper();
    }, 0);
}

function addDayRow(dayName) {
    if (document.getElementById('srow-' + dayName)) return;
    const container = document.getElementById('dayScheduleContainer');

    const row = document.createElement('div');
    row.className = 'schedule-row';
    row.id = 'srow-' + dayName;
    row.innerHTML = `
        <div class="day-label">${dayName}</div>
        <div class="flex-fill" id="slots-${dayName}">
            <div class="time-slot">
                <input type="text" name="jam_detail[${dayName}][]"
                       class="form-control" placeholder="cth. 10:00 - 12:00" autocomplete="off">
            </div>
        </div>
        <button type="button" class="btn-add-slot" onclick="addSlot('${dayName}')">
            <i class="bi bi-plus me-1"></i>Tambah
        </button>`;

    // Insert in day order
    let inserted = false;
    const existing = container.querySelectorAll('.schedule-row');
    for (const existRow of existing) {
        const existDay = existRow.id.replace('srow-', '');
        if (DAY_ORDER.indexOf(dayName) < DAY_ORDER.indexOf(existDay)) {
            container.insertBefore(row, existRow);
            inserted = true;
            break;
        }
    }
    if (!inserted) container.appendChild(row);
}

function removeDayRow(dayName) {
    const row = document.getElementById('srow-' + dayName);
    if (row) row.remove();
}

function updateScheduleWrapper() {
    const wrapper   = document.getElementById('dayScheduleWrapper');
    const container = document.getElementById('dayScheduleContainer');
    if (!wrapper || !container) return;
    wrapper.style.display = container.querySelectorAll('.schedule-row').length > 0 ? '' : 'none';
}

function addSlot(dayName) {
    const slotsDiv = document.getElementById('slots-' + dayName);
    if (!slotsDiv) return;
    const div = document.createElement('div');
    div.className = 'time-slot mt-1';
    div.innerHTML = `
        <input type="text" name="jam_detail[${dayName}][]"
               class="form-control" placeholder="cth. 15:00 - 17:00" autocomplete="off">
        <button type="button" class="btn-remove-slot" onclick="removeSlot(this)" title="Hapus">
            <i class="bi bi-x"></i>
        </button>`;
    slotsDiv.appendChild(div);
}

function removeSlot(btn) {
    btn.closest('.time-slot')?.remove();
}

function showStep(step) {
    const panels = document.querySelectorAll('.step-panel');
    const items = document.querySelectorAll('.stepper-item');

    panels.forEach((panel, index) => {
        const isActive = index === step - 1;
        panel.classList.toggle('active', isActive);
    });

    items.forEach((item, index) => {
        const current = index + 1;
        item.classList.toggle('active', current === step);
        item.classList.toggle('completed', current < step);
    });
}

document.querySelectorAll('[data-action="next"]').forEach(btn => {
    btn.addEventListener('click', () => {
        const current = document.querySelector('.step-panel.active');
        const nextStep = parseInt(current.dataset.step, 10) + 1;
        if (nextStep <= 5) {
            showStep(nextStep);
        }
    });
});

document.querySelectorAll('[data-action="prev"]').forEach(btn => {
    btn.addEventListener('click', () => {
        const current = document.querySelector('.step-panel.active');
        const prevStep = parseInt(current.dataset.step, 10) - 1;
        if (prevStep >= 1) {
            showStep(prevStep);
        }
    });
});

// Restore pill states on page load (for old() repopulation)
document.querySelectorAll('.check-pill input:checked').forEach(cb => {
    cb.closest('.check-pill').classList.add('selected');
});

document.getElementById('regForm').addEventListener('submit', function() {
    document.getElementById('regText').classList.add('d-none');
    document.getElementById('regLoading').classList.remove('d-none');
    document.getElementById('regBtn').disabled = true;
});

function handleEducationLevel(val) {
    const praFields = document.getElementById('praSekolahFields');
    if (val === 'Pra Sekolah (PAUD/TK)') {
        praFields.classList.remove('d-none');
    } else {
        praFields.classList.add('d-none');
    }
}
// Run on load in case of old() repopulation
handleEducationLevel(document.getElementById('education_level').value);
</script>
</body>
</html>
