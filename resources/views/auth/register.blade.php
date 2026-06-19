<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Formulir Pendaftaran | Ayo Kursus</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #260632 0%, #461256 40%, #461256 75%, #c84ddf 100%);
            font-family: 'Inter', 'Segoe UI', sans-serif;
            padding: 1.5rem 1rem 3rem;
            position: relative;
            overflow-x: hidden;
        }
        body::before {
            content:''; position:fixed; width:500px; height:500px;
            background:radial-gradient(circle,rgba(200,77,223,.2) 0%,transparent 70%);
            top:-150px; right:-150px; border-radius:50%;
            animation:orb1 8s ease-in-out infinite alternate; pointer-events:none;
        }
        @keyframes orb1{from{transform:translate(0,0) scale(1);}to{transform:translate(30px,20px) scale(1.1);}}

        .form-wrapper {
            max-width: 860px;
            margin: 0 auto;
            position: relative; z-index: 1;
        }
        .back-link {
            display:inline-flex; align-items:center; gap:6px;
            color:rgba(255,255,255,.65); font-size:.78rem; font-weight:600;
            text-decoration:none; transition:color .2s; margin-bottom:1rem;
        }
        .back-link:hover { color:rgba(255,255,255,.95); }

        .form-header {
            background: linear-gradient(135deg, #461256, #68117e, #c84ddf);
            padding: 1.75rem 2rem;
            border-radius: 20px 20px 0 0;
            color: white;
            text-align: center;
        }
        .form-header .brand-logo {
            width:52px; height:52px; border-radius:15px;
            background:rgba(255,255,255,.2); display:flex; align-items:center;
            justify-content:center; font-size:24px; margin:0 auto 10px;
            border:1px solid rgba(255,255,255,.25);
        }
        .form-header h4 { font-size:22px; font-weight:800; margin:0; font-family:'Plus Jakarta Sans',sans-serif; }
        .form-header p  { font-size:13px; opacity:.75; margin:4px 0 0; }

        .form-body {
            background: white;
            border-radius: 0 0 20px 20px;
            padding: 0;
            overflow: hidden;
            box-shadow: 0 40px 80px rgba(0,0,0,.45);
        }

        .section-card {
            padding: 1.5rem 2rem;
            border-bottom: 1px solid #f0f0f0;
        }
        .section-card:last-child { border-bottom: none; }
        .section-title {
            display: flex; align-items: center; gap: 10px;
            font-size: 13px; font-weight: 700; text-transform: uppercase;
            letter-spacing: .06em; color: #461256; margin-bottom: 1.25rem;
        }
        .section-title::before {
            content:''; width:4px; height:18px;
            background: linear-gradient(135deg, #461256, #c84ddf);
            border-radius: 4px; flex-shrink: 0;
        }

        .form-label { font-weight: 600; font-size: .8rem; color: #374151; margin-bottom: .35rem; }
        .form-control, .form-select {
            border: 1.5px solid #e5e7eb; border-radius: 10px;
            padding: .55rem .85rem; font-size: .88rem;
            background: #fafafa; transition: border-color .2s, box-shadow .2s;
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
            padding: 6px 14px; border: 1.5px solid #e5e7eb; border-radius: 30px;
            font-size: 13px; cursor: pointer; transition: all .2s; background: #fafafa; user-select: none;
        }
        .check-pill input { display: none; }
        .check-pill:hover { border-color: #c84ddf; background: #fdf4ff; }
        .check-pill.selected { border-color: #c84ddf; background: #fdf4ff; color: #461256; font-weight: 600; }
        .check-pill.selected::before { content: '✓ '; color: #c84ddf; }

        .option-btn-group { display: flex; flex-wrap: wrap; gap: 8px; }
        .option-btn {
            padding: 7px 18px; border: 1.5px solid #e5e7eb; border-radius: 30px;
            font-size: 13px; cursor: pointer; transition: all .2s; background: #fafafa;
            font-weight: 500; color: #374151;
        }
        .option-btn.active { border-color: #c84ddf; background: #fdf4ff; color: #461256; font-weight: 700; }

        .program-group { margin-bottom: 1rem; }
        .program-group-label {
            font-size: 11px; font-weight: 700; text-transform: uppercase;
            letter-spacing: .06em; color: #9ca3af; margin-bottom: 8px;
        }

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
            width: 44px; height: 44px; border: 1.5px solid #e5e7eb; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; font-weight: 600; cursor: pointer;
            transition: all .2s; background: #fafafa; user-select: none; color: #374151;
        }
        .day-pill:hover { border-color: #c84ddf; background: #fdf4ff; }
        .day-pill.selected { border-color: #c84ddf; background: linear-gradient(135deg,#461256,#c84ddf); color: white; }

        @media(max-width: 600px) { .section-card { padding: 1.25rem 1rem; } }
    </style>
</head>
<body>

<div class="form-wrapper">
    <a href="{{ url('/') }}" class="back-link"><i class="bi bi-arrow-left"></i> Kembali ke Beranda</a>

    <div class="form-header">
        <div class="brand-logo"><i class="bi bi-mortarboard-fill"></i></div>
        <h4>FORMULIR PENDAFTARAN SISWA</h4>
        <p>AYO KURSUS — Isi data dengan lengkap dan benar</p>
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

            {{-- SECTION 1: DATA SISWA --}}
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
                        <label class="form-label">Jenjang Pendidikan (Sekolah & Kelas) / Umum</label>
                        <input type="text" name="school_name" class="form-control" value="{{ old('school_name') }}" placeholder="cth. SMA Negeri 1 – Kelas 11 / Umum">
                    </div>
                </div>
            </div>

            {{-- SECTION 2: DATA ORANG TUA --}}
            <div class="section-card">
                <div class="section-title">Data Orang Tua / Wali</div>
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

            {{-- SECTION 3: PROGRAM BELAJAR --}}
            <div class="section-card">
                <div class="section-title">Program Belajar</div>

                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label">Program Belajar</label>
                        <div class="option-btn-group" id="programGroup">
                            <div class="option-btn active" data-val="kelas" onclick="pickOption('program','kelas',this)">Kelas</div>
                            <div class="option-btn" data-val="privat" onclick="pickOption('program','privat',this)">Privat</div>
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
                    <div class="col-md-6">
                        <label class="form-label">Tempat Belajar</label>
                        <div class="option-btn-group" id="tempatGroup">
                            <div class="option-btn active" data-val="kantor" onclick="pickOption('tempat','kantor',this)">Belajar di Kantor</div>
                            <div class="option-btn" data-val="rumah" onclick="pickOption('tempat','rumah',this)">Guru ke Rumah</div>
                        </div>
                        <input type="hidden" name="tempat_belajar" id="tempat_belajar" value="{{ old('tempat_belajar','kantor') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Sistem Pengambilan</label>
                        <div class="option-btn-group" id="sistemPaketGroup">
                            <div class="option-btn active" data-val="paket" onclick="pickOption('sistem_paket','paket',this)">Paket</div>
                            <div class="option-btn" data-val="sesi" onclick="pickOption('sistem_paket','sesi',this)">Pertemuan / Sesi</div>
                        </div>
                        <input type="hidden" name="sistem_paket" id="sistem_paket" value="{{ old('sistem_paket','paket') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jumlah Paket / Pertemuan</label>
                        <input type="text" name="jumlah_paket" class="form-control" value="{{ old('jumlah_paket') }}" placeholder="cth. 12 sesi / 1 paket">
                    </div>
                </div>
            </div>

            {{-- SECTION 4: PROGRAM YANG DIMINATI --}}
            <div class="section-card">
                <div class="section-title">Program yang Diminati</div>
                <p class="text-muted mb-3" style="font-size:13px">Pilih satu atau lebih program yang ingin diikuti</p>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="program-group">
                            <div class="program-group-label">🖥️ Kursus Komputer</div>
                            <div class="check-grid">
                                @php $komputerOptions = ['Microsoft Office Perkantoran','Word','Excel','PowerPoint','Desain Grafis','CorelDraw','Photoshop','AutoCAD','Programmer / Coding']; @endphp
                                @foreach($komputerOptions as $opt)
                                <label class="check-pill" onclick="togglePill(this)">
                                    <input type="checkbox" name="program_minat[]" value="{{ $opt }}"
                                           {{ in_array($opt, old('program_minat', [])) ? 'checked' : '' }}>
                                    {{ $opt }}
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="program-group">
                            <div class="program-group-label">🌍 Kursus Bahasa Asing</div>
                            <div class="check-grid">
                                @php $bahasaOptions = ['Bahasa Inggris','Bahasa Arab','Bahasa Mandarin','Bahasa Jepang','Bahasa Korea']; @endphp
                                @foreach($bahasaOptions as $opt)
                                <label class="check-pill" onclick="togglePill(this)">
                                    <input type="checkbox" name="program_minat[]" value="{{ $opt }}"
                                           {{ in_array($opt, old('program_minat', [])) ? 'checked' : '' }}>
                                    {{ $opt }}
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="program-group mt-3">
                            <div class="program-group-label">📚 Mata Pelajaran</div>
                            <div class="check-grid">
                                @php $mapelOptions = ['Matematika','Kimia','Biologi','Bahasa Indonesia','Fisika','Akuntansi / Ekonomi','Geografi','Bahasa Inggris','IPA','IPS']; @endphp
                                @foreach($mapelOptions as $opt)
                                <label class="check-pill" onclick="togglePill(this)">
                                    <input type="checkbox" name="program_minat[]" value="{{ $opt }}"
                                           {{ in_array($opt, old('program_minat', [])) ? 'checked' : '' }}>
                                    {{ $opt }}
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="program-group">
                            <div class="program-group-label">🏛️ Program Kedinasan</div>
                            <div class="check-grid">
                                @php $kedinasanOptions = ['SKD (TIU, TWK, TKP)','TPA','Psikotes','TBI']; @endphp
                                @foreach($kedinasanOptions as $opt)
                                <label class="check-pill" onclick="togglePill(this)">
                                    <input type="checkbox" name="program_minat[]" value="{{ $opt }}"
                                           {{ in_array($opt, old('program_minat', [])) ? 'checked' : '' }}>
                                    {{ $opt }}
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="program-group mt-3">
                            <div class="program-group-label">🎖️ AKPOL / AKMIL / BINTARA</div>
                            <div class="check-grid">
                                @php $akpolOptions = ['Pengetahuan Umum','Wawasan Kebangsaan','Bahasa Indonesia','Bahasa Inggris','TKD','Tes Akademik']; @endphp
                                @foreach($akpolOptions as $opt)
                                <label class="check-pill" onclick="togglePill(this)">
                                    <input type="checkbox" name="program_minat[]" value="{{ $opt }}"
                                           {{ in_array($opt, old('program_minat', [])) ? 'checked' : '' }}>
                                    {{ $opt }}
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="program-group">
                            <div class="program-group-label">📋 CPNS</div>
                            <div class="check-grid">
                                <label class="check-pill" onclick="togglePill(this)">
                                    <input type="checkbox" name="program_minat[]" value="SKD CPNS (TIU, TWK, TKP)"
                                           {{ in_array('SKD CPNS (TIU, TWK, TKP)', old('program_minat', [])) ? 'checked' : '' }}>
                                    SKD (TIU, TWK, TKP)
                                </label>
                            </div>
                        </div>

                        <div class="program-group mt-3">
                            <div class="program-group-label">🏢 BUMN</div>
                            <div class="check-grid">
                                @php $bumnOptions = ['TKD BUMN','Tes AKHLAK','TWK BUMN']; @endphp
                                @foreach($bumnOptions as $opt)
                                <label class="check-pill" onclick="togglePill(this)">
                                    <input type="checkbox" name="program_minat[]" value="{{ $opt }}"
                                           {{ in_array($opt, old('program_minat', [])) ? 'checked' : '' }}>
                                    {{ $opt }}
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SECTION 5: JADWAL BELAJAR --}}
            <div class="section-card">
                <div class="section-title">Jadwal Belajar</div>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Hari Belajar</label>
                        <div class="day-check-group">
                            @php $days = ['Sen','Sel','Rab','Kam','Jum','Sab','Min'];
                                 $dayVals = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu']; @endphp
                            @foreach($days as $i => $d)
                            <label class="day-pill {{ in_array($dayVals[$i], old('hari_belajar', [])) ? 'selected' : '' }}" onclick="toggleDay(this)">
                                <input type="checkbox" name="hari_belajar[]" value="{{ $dayVals[$i] }}" style="display:none"
                                       {{ in_array($dayVals[$i], old('hari_belajar', [])) ? 'checked' : '' }}>
                                {{ $d }}
                            </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jam Belajar <span class="text-muted fw-normal">(Fleksibel / Sesuai Kesepakatan)</span></label>
                        <input type="text" name="jam_belajar" class="form-control" value="{{ old('jam_belajar') }}" placeholder="cth. 15.00 – 17.00 / Fleksibel">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Mulai Belajar</label>
                        <input type="date" name="tanggal_mulai" class="form-control" value="{{ old('tanggal_mulai') }}">
                    </div>
                </div>
            </div>

            {{-- SUBMIT --}}
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

        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function pickOption(group, val, el) {
    const groupMap = {
        'program': 'program_belajar',
        'sistem': 'sistem_belajar',
        'tempat': 'tempat_belajar',
        'sistem_paket': 'sistem_paket',
    };
    el.closest('.option-btn-group').querySelectorAll('.option-btn').forEach(b => b.classList.remove('active'));
    el.classList.add('active');
    document.getElementById(groupMap[group]).value = val;
}

function togglePill(el) {
    const cb = el.querySelector('input[type=checkbox]');
    setTimeout(() => {
        el.classList.toggle('selected', cb.checked);
    }, 0);
}

function toggleDay(el) {
    const cb = el.querySelector('input[type=checkbox]');
    setTimeout(() => {
        el.classList.toggle('selected', cb.checked);
    }, 0);
}

// Restore pill states on page load (for old() repopulation)
document.querySelectorAll('.check-pill input:checked').forEach(cb => {
    cb.closest('.check-pill').classList.add('selected');
});

document.getElementById('regForm').addEventListener('submit', function() {
    document.getElementById('regText').classList.add('d-none');
    document.getElementById('regLoading').classList.remove('d-none');
    document.getElementById('regBtn').disabled = true;
});
</script>
</body>
</html>
