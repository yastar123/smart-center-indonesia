@extends('layouts.app')
@section('page-title', 'Edit Landing — '.$branch->name)

@section('content')
<div class="container-fluid px-0">

    {{-- HEADER --}}
    <div class="dashboard-card mb-4" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
        <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
        <div class="row align-items-center g-3" style="position:relative">
            <div class="col-md-8">
                <div class="d-flex align-items-center gap-3">
                    <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0" style="color:white">{{ $branch->name }}</h5>
                        <span style="font-size:12px;opacity:.8">Edit konten halaman landing <code style="background:rgba(255,255,255,.15);padding:1px 6px;border-radius:5px">/cabang/{{ $branch->id }}</code></span>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-md-end d-flex gap-2 justify-content-md-end flex-wrap">
                <a href="{{ route('cabang.show', $branch) }}" target="_blank" class="btn btn-sm fw-semibold px-3"
                   style="background:rgba(255,255,255,.15);color:white;border:1px solid rgba(255,255,255,.25);border-radius:10px">
                    <i class="bi bi-eye me-1"></i>Lihat Halaman
                </a>
                <a href="{{ route('admin.landing.cabang.index') }}" class="btn btn-sm fw-semibold px-3"
                   style="background:rgba(255,255,255,.1);color:white;border:1px solid rgba(255,255,255,.2);border-radius:10px">
                    <i class="bi bi-arrow-left me-1"></i>Semua Cabang
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @php
        $city        = $branch->city ?: $branch->name;
        $promoItems  = json_decode($s['promo_items'] ?? '[]', true) ?: ['Mulai belajar dari Rp 50.000/sesi','Garansi nilai naik atau sesi gratis!','Tersedia Home Visit, Online & Offline','Gratis Konsultasi Pertama','#1 Les Privat Terbaik di '.$city];
        $faqItems    = json_decode($s['faq_items'] ?? '[]', true) ?: [['q'=>'Berapa harga les privat SCI '.$city.'?','a'=>'Harga les privat SCI '.$city.' mulai dari Rp 50.000/sesi untuk online.'],['q'=>'Apakah bisa home visit di '.$city.'?','a'=>'Ya! SCI '.$city.' melayani home visit ke seluruh area.']];
        $areasArr    = json_decode($s['areas'] ?? '[]', true) ?: [];
        $areasStr    = implode(', ', $areasArr);
        $defaultFeatures = [
            ['num'=>'01','icon'=>'👩‍🏫','title'=>'Tutor Bersertifikat',   'desc'=>'Semua tutor SCI '.$city.' telah melalui seleksi ketat dan memiliki sertifikat mengajar resmi.'],
            ['num'=>'02','icon'=>'🏠', 'title'=>'Home Visit',              'desc'=>'Tutor datang ke rumah Anda di seluruh wilayah '.$city.'. Nyaman, privat, dan efisien.'],
            ['num'=>'03','icon'=>'📈', 'title'=>'Prestasi Meningkat',      'desc'=>'Mayoritas siswa SCI '.$city.' mengalami peningkatan nilai dalam beberapa bulan pertama.'],
            ['num'=>'04','icon'=>'⏰', 'title'=>'Jadwal Fleksibel',        'desc'=>'Belajar bisa pagi, siang, sore, ataupun malam hari sesuai kebutuhan siswa.'],
            ['num'=>'05','icon'=>'💰', 'title'=>'Harga Transparan',        'desc'=>'Tidak ada biaya tersembunyi. Tersedia paket hemat dan pembayaran per sesi.'],
            ['num'=>'06','icon'=>'🛡️', 'title'=>'Garansi Kepuasan',        'desc'=>'Tutor dapat diganti apabila kurang cocok tanpa biaya tambahan.'],
        ];
        $savedFeatures = json_decode($s['features'] ?? '[]', true) ?: [];
        $editFeatures  = !empty($savedFeatures) ? $savedFeatures : $defaultFeatures;

        $defaultSubjects = [
            ['icon'=>'🔢','name'=>'Matematika',     'desc'=>'SD, SMP, SMA, Kuliah.',           'badge'=>'Terpopuler','badge_type'=>'popular'],
            ['icon'=>'⚡','name'=>'Fisika',          'desc'=>'Mekanika, gelombang, listrik.',    'badge'=>'SMP–SMA',  'badge_type'=>'level'],
            ['icon'=>'🧪','name'=>'Kimia',           'desc'=>'Organik, anorganik, stoikiometri.','badge'=>'SMP–SMA',  'badge_type'=>'level'],
            ['icon'=>'🌿','name'=>'Biologi',         'desc'=>'Sel, genetika, ekosistem.',        'badge'=>'SMP–SMA',  'badge_type'=>'level'],
            ['icon'=>'🇬🇧','name'=>'Bahasa Inggris', 'desc'=>'Speaking, grammar, TOEFL/IELTS.', 'badge'=>'Terpopuler','badge_type'=>'popular'],
            ['icon'=>'💻','name'=>'Komputer',        'desc'=>'MS Office, Programming, Canva.',   'badge'=>'Populer',  'badge_type'=>'hot'],
            ['icon'=>'📊','name'=>'Akuntansi',       'desc'=>'Akuntansi dasar–profesional.',     'badge'=>'Umum',     'badge_type'=>'general'],
            ['icon'=>'🇯🇵','name'=>'Bahasa Jepang',  'desc'=>'Hiragana, katakana, JLPT.',        'badge'=>'Semua Level','badge_type'=>'general'],
            ['icon'=>'📐','name'=>'Bahasa Indonesia','desc'=>'Tata bahasa, UN/UTBK.',            'badge'=>'SD–SMA',   'badge_type'=>'level'],
            ['icon'=>'🎨','name'=>'Seni & Desain',   'desc'=>'Menggambar, desain grafis.',       'badge'=>'Umum',     'badge_type'=>'general'],
            ['icon'=>'🗣️','name'=>'Public Speaking',  'desc'=>'Percaya diri, debat, presentasi.','badge'=>'Semua Level','badge_type'=>'general'],
            ['icon'=>'📚','name'=>'Persiapan SBMPTN','desc'=>'Latihan soal UTBK, simulasi.',     'badge'=>'SMA',      'badge_type'=>'level'],
        ];
        $savedSubjects = json_decode($s['subjects'] ?? '[]', true) ?: [];
        $editSubjects  = !empty($savedSubjects) ? $savedSubjects : $defaultSubjects;

        $heroBgCurrent = $s['hero_bg'] ?? '';
        $metodeImgHv   = $s['metode_img_homevisi'] ?? '';
        $metodeImgOn   = $s['metode_img_online'] ?? '';
        $metodeImgOf   = $s['metode_img_offline'] ?? '';
    @endphp

    {{-- Info box --}}
    <div class="alert border-0 mb-4 small" style="background:rgba(200,77,223,.06);border-left:4px solid #c84ddf !important;border-radius:12px">
        <i class="bi bi-info-circle me-1" style="color:var(--bs-primary)"></i>
        <strong>Kontak, alamat &amp; email</strong> dikelola di menu <a href="{{ route('owner.branches.edit', $branch) }}">Kelola Cabang</a>.
        <strong>Paket &amp; harga</strong> dikelola di menu <a href="{{ route('admin.packages.index') }}">Paket</a>.
        <strong>Testimoni</strong> menggunakan testimoni global — kelola di <a href="{{ route('admin.landing.index') }}#tab-testimonials">Landing Utama → Testimoni</a>.
    </div>

    {{-- NOTE: enctype required for image uploads --}}
    <form action="{{ route('admin.landing.cabang.update', $branch) }}" method="POST"
          enctype="multipart/form-data" id="branchLandingForm">
        @csrf @method('PUT')

        {{-- ── Tab Nav ── --}}
        <ul class="nav nav-tabs lp-tabs mb-4 flex-wrap" id="blTabs">
            <li class="nav-item"><button type="button" class="nav-link active" data-bs-toggle="tab" data-bs-target="#bl-promo"><i class="bi bi-megaphone me-1"></i>Promo</button></li>
            <li class="nav-item"><button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#bl-hero"><i class="bi bi-house-door me-1"></i>Hero</button></li>
            <li class="nav-item"><button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#bl-dipercaya"><i class="bi bi-star me-1"></i>Dipercaya</button></li>
            <li class="nav-item"><button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#bl-program"><i class="bi bi-book me-1"></i>Program</button></li>
            <li class="nav-item"><button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#bl-metode"><i class="bi bi-grid-3x3 me-1"></i>Metode</button></li>
            <li class="nav-item"><button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#bl-jam"><i class="bi bi-clock me-1"></i>Lokasi &amp; Area</button></li>
            <li class="nav-item"><button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#bl-faq"><i class="bi bi-question-circle me-1"></i>FAQ</button></li>
            <li class="nav-item"><button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#bl-cta"><i class="bi bi-rocket me-1"></i>CTA</button></li>
        </ul>

        <div class="tab-content">

            {{-- ────────────────────── PROMO TICKER ────────────────────── --}}
            <div class="tab-pane fade show active" id="bl-promo">
                <div class="card lp-card">
                    <div class="card-header lp-card-header"><i class="bi bi-megaphone me-2"></i>Promo Ticker
                        <small class="fw-normal ms-2 opacity-75">Teks bergulir di baris paling atas halaman cabang</small>
                    </div>
                    <div class="card-body">
                        <div class="alert border-0 mb-3" style="background:rgba(246,175,35,.08);border-left:3px solid #f6af23 !important;border-radius:10px;font-size:.83rem">
                            <i class="bi bi-lightbulb-fill me-1" style="color:#f6af23"></i>
                            Hingga <strong>8 item</strong> teks promo yang bergulir otomatis di bagian atas halaman.
                        </div>
                        <div id="promoList" class="d-flex flex-column gap-2 mb-3">
                            @foreach($promoItems as $i => $item)
                            <div class="promo-row d-flex align-items-center gap-2">
                                <span class="badge bg-light text-muted fw-normal" style="font-size:.68rem;min-width:24px">{{ $i+1 }}</span>
                                <input type="text" name="promo_items[]" value="{{ $item }}" class="form-control form-control-sm" placeholder="Teks promo..." maxlength="200">
                                <button type="button" class="btn btn-sm btn-outline-danger promo-remove flex-shrink-0"><i class="bi bi-x-lg"></i></button>
                            </div>
                            @endforeach
                        </div>
                        <button type="button" id="promoAdd" class="btn btn-sm btn-outline-primary"><i class="bi bi-plus me-1"></i>Tambah Item</button>
                        <div class="mt-3 p-3 rounded-3" style="background:linear-gradient(90deg,#e09000,#f6af23)">
                            <div class="text-dark fw-bold small mb-1">Preview:</div>
                            <div id="promoPreview" class="d-flex gap-3 flex-nowrap overflow-hidden" style="font-size:.82rem;font-weight:600;color:#260632"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ────────────────────── HERO ────────────────────────────── --}}
            <div class="tab-pane fade" id="bl-hero">
                <div class="card lp-card">
                    <div class="card-header lp-card-header"><i class="bi bi-house-door me-2"></i>Les Privat Terbaik di {{ $city }}</div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Teks Badge <small class="text-muted fw-normal">(tampil di atas judul utama)</small></label>
                                <div class="input-group">
                                    <span class="input-group-text">🏆</span>
                                    <input type="text" name="hero_badge" class="form-control"
                                           value="{{ $s['hero_badge'] ?? '#1 Jasa Les Privat '.strtoupper($city).' Terpercaya' }}"
                                           maxlength="200">
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Deskripsi Hero <small class="text-muted fw-normal">(tampil di bawah judul)</small></label>
                                <textarea name="hero_description" class="form-control" rows="4" maxlength="600">{{ $s['hero_description'] ?? 'Smart Center Indonesia hadir di '.$city.' dengan tutor bersertifikat. Layanan home visit, online, dan offline untuk semua jenjang dari TK hingga umum.' }}</textarea>
                                <div class="form-text"><span id="heroDescCount">0</span>/600 karakter</div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-image me-1" style="color:var(--bs-primary)"></i>
                                    Gambar Background Hero
                                    <small class="text-muted fw-normal ms-1">(upload dari laptop — jpg/png/webp, maks 5MB)</small>
                                </label>
                                <input type="file" name="hero_bg" class="form-control" accept="image/*">
                                @if($heroBgCurrent)
                                <div class="mt-2">
                                    <small class="text-muted d-block mb-1">Gambar saat ini:</small>
                                    <img src="{{ $heroBgCurrent }}" alt="Hero BG" style="height:90px;border-radius:10px;object-fit:cover;border:1px solid rgba(200,77,223,.2)">
                                </div>
                                @else
                                <div class="form-text">Kosongkan untuk menggunakan gambar default. Gambar akan ditampilkan transparan di belakang konten hero.</div>
                                @endif
                            </div>
                            {{-- Preview --}}
                            <div class="col-12">
                                <div class="p-3 rounded-3" style="background:linear-gradient(160deg,#1a0228 0%,#461256 45%,#6d1a7e 100%)">
                                    <div class="small fw-bold mb-2" style="color:rgba(255,255,255,.5)">Preview Hero:</div>
                                    <div style="background:rgba(246,175,35,.15);border:1px solid rgba(246,175,35,.3);border-radius:50px;display:inline-flex;align-items:center;gap:6px;padding:4px 12px;font-size:.73rem;font-weight:700;color:#f6af23;margin-bottom:.6rem">
                                        🏆 <span id="prevBadge">{{ $s['hero_badge'] ?? '#1 Jasa Les Privat '.strtoupper($city).' Terpercaya' }}</span>
                                    </div><br>
                                    <div style="font-size:1.5rem;font-weight:900;color:white;line-height:1.1;margin-bottom:.5rem">
                                        Les Privat <em style="font-style:italic;color:#f6af23">Terbaik</em> di {{ $city }}
                                    </div>
                                    <div style="font-size:.85rem;color:rgba(255,255,255,.7);max-width:380px" id="prevDesc">{{ $s['hero_description'] ?? '' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ────────────────────── DIPERCAYA RIBUAN KELUARGA ──────── --}}
            <div class="tab-pane fade" id="bl-dipercaya">
                <div class="card lp-card">
                    <div class="card-header lp-card-header d-flex align-items-center justify-content-between">
                        <span><i class="bi bi-star me-2"></i>Dipercaya Ribuan Keluarga di {{ $city }}</span>
                        <small class="fw-normal opacity-75">6 kartu keunggulan yang tampil di bawah hero</small>
                    </div>
                    <div class="card-body">
                        <div class="alert border-0 mb-3" style="background:rgba(200,77,223,.06);border-left:3px solid #c84ddf !important;border-radius:10px;font-size:.83rem">
                            <i class="bi bi-info-circle me-1" style="color:var(--bs-primary)"></i>
                            Edit 6 kartu keunggulan SCI di kota {{ $city }}. Setiap kartu memiliki nomor, ikon emoji, judul, dan deskripsi.
                        </div>
                        <div id="featList" class="row g-3 mb-3">
                            @foreach($editFeatures as $fi => $feat)
                            <div class="col-md-6 feat-row">
                                <div class="p-3 rounded-3 h-100" style="background:#f9f4ff;border:1.5px solid rgba(200,77,223,.15)">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <span class="badge fw-bold" style="background:rgba(200,77,223,.1);color:#68117e;min-width:28px">{{ $fi+1 }}</span>
                                        <span class="text-muted small">Kartu Keunggulan</span>
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-3">
                                            <label class="form-label small fw-semibold">Label No.</label>
                                            <input type="text" name="feat_num[]" value="{{ $feat['num'] ?? sprintf('%02d',$fi+1) }}" class="form-control form-control-sm" maxlength="5" placeholder="01">
                                        </div>
                                        <div class="col-3">
                                            <label class="form-label small fw-semibold">Ikon</label>
                                            <input type="text" name="feat_icon[]" value="{{ $feat['icon'] ?? '' }}" class="form-control form-control-sm" maxlength="10" placeholder="👩‍🏫">
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label small fw-semibold">Judul *</label>
                                            <input type="text" name="feat_title[]" value="{{ $feat['title'] ?? '' }}" class="form-control form-control-sm" maxlength="60" placeholder="Tutor Bersertifikat" required>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label small fw-semibold">Deskripsi</label>
                                            <textarea name="feat_desc[]" class="form-control form-control-sm" rows="2" maxlength="200">{{ $feat['desc'] ?? '' }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <small class="text-muted"><i class="bi bi-info-circle me-1"></i>Rekomendasi: tepat 6 kartu untuk tampilan terbaik di halaman cabang.</small>
                    </div>
                </div>
            </div>

            {{-- ────────────────────── PROGRAM LES & KURSUS ───────────── --}}
            <div class="tab-pane fade" id="bl-program">
                <div class="card lp-card">
                    <div class="card-header lp-card-header d-flex align-items-center justify-content-between">
                        <span><i class="bi bi-book me-2"></i>Program Les &amp; Kursus di {{ $city }}</span>
                        <small class="fw-normal opacity-75">Mata pelajaran yang tersedia di cabang ini</small>
                    </div>
                    <div class="card-body">
                        <div class="alert border-0 mb-3" style="background:rgba(200,77,223,.06);border-left:3px solid #c84ddf !important;border-radius:10px;font-size:.83rem">
                            <i class="bi bi-info-circle me-1" style="color:var(--bs-primary)"></i>
                            Edit atau hapus mata pelajaran yang tersedia. Gunakan tombol <strong>Tambah</strong> untuk menambah program baru.
                        </div>
                        <div id="subjList" class="row g-3 mb-3">
                            @foreach($editSubjects as $si => $subj)
                            <div class="col-md-6 subj-row">
                                <div class="p-3 rounded-3" style="background:#f9f4ff;border:1.5px solid rgba(200,77,223,.15)">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="badge fw-bold" style="background:rgba(200,77,223,.1);color:#68117e;min-width:28px">{{ $si+1 }}</span>
                                        <button type="button" class="btn btn-sm btn-outline-danger subj-remove py-0 px-2"><i class="bi bi-trash"></i></button>
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-2">
                                            <label class="form-label small fw-semibold">Ikon</label>
                                            <input type="text" name="subj_icon[]" value="{{ $subj['icon'] ?? '' }}" class="form-control form-control-sm" maxlength="10" placeholder="📚">
                                        </div>
                                        <div class="col-10">
                                            <label class="form-label small fw-semibold">Nama Program *</label>
                                            <input type="text" name="subj_name[]" value="{{ $subj['name'] ?? '' }}" class="form-control form-control-sm" maxlength="60" placeholder="Matematika">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label small fw-semibold">Deskripsi</label>
                                            <input type="text" name="subj_desc[]" value="{{ $subj['desc'] ?? '' }}" class="form-control form-control-sm" maxlength="150">
                                        </div>
                                        <div class="col-7">
                                            <label class="form-label small fw-semibold">Teks Badge</label>
                                            <input type="text" name="subj_badge[]" value="{{ $subj['badge'] ?? '' }}" class="form-control form-control-sm" maxlength="30" placeholder="Terpopuler">
                                        </div>
                                        <div class="col-5">
                                            <label class="form-label small fw-semibold">Warna Badge</label>
                                            <select name="subj_badge_type[]" class="form-select form-select-sm">
                                                @foreach(['popular'=>'Ungu (populer)','hot'=>'Merah (hot)','level'=>'Biru (level)','general'=>'Hijau (umum)'] as $val => $lbl)
                                                <option value="{{ $val }}" {{ ($subj['badge_type'] ?? 'general') === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <button type="button" id="subjAdd" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-plus me-1"></i>Tambah Program
                        </button>
                    </div>
                </div>
            </div>

            {{-- ────────────────────── METODE BELAJAR ─────────────────── --}}
            <div class="tab-pane fade" id="bl-metode">
                <div class="card lp-card">
                    <div class="card-header lp-card-header"><i class="bi bi-grid-3x3 me-2"></i>Pilih Cara Belajar Terbaik</div>
                    <div class="card-body">
                        <div class="alert border-0 mb-4" style="background:rgba(200,77,223,.06);border-left:3px solid #c84ddf !important;border-radius:10px;font-size:.83rem">
                            <i class="bi bi-info-circle me-1" style="color:var(--bs-primary)"></i>
                            Atur harga dan gambar untuk setiap metode belajar. Upload gambar dari laptop untuk mengganti foto default.
                        </div>
                        <div class="row g-4">
                            @foreach([
                                ['key'=>'homevisi','label'=>'Home Visit','emoji'=>'🏠','default_price'=>'Rp 65.000','img_field'=>'metode_img_homevisi','cur_img'=>$metodeImgHv,'default_img'=>'https://images.unsplash.com/photo-1434030216411-0b793f4b4173?w=600&q=80'],
                                ['key'=>'online',  'label'=>'Online',    'emoji'=>'🖥️','default_price'=>'Rp 50.000','img_field'=>'metode_img_online',   'cur_img'=>$metodeImgOn,'default_img'=>'https://images.unsplash.com/photo-1588702547923-7093a6c3ba33?w=600&q=80'],
                                ['key'=>'offline', 'label'=>'Offline',   'emoji'=>'🏫','default_price'=>'Rp 55.000','img_field'=>'metode_img_offline',  'cur_img'=>$metodeImgOf,'default_img'=>'https://images.unsplash.com/photo-1580582932707-520aed937b7b?w=600&q=80'],
                            ] as $m)
                            <div class="col-md-4">
                                <div class="p-3 rounded-3 h-100" style="background:#f9f4ff;border:1.5px solid rgba(200,77,223,.15)">
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <span style="font-size:1.4rem">{{ $m['emoji'] }}</span>
                                        <div class="fw-bold" style="color:#260632">{{ $m['label'] }}</div>
                                    </div>
                                    {{-- Price --}}
                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold">Harga per Sesi</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text">Rp</span>
                                            <input type="text" name="price_{{ $m['key'] }}" class="form-control price-input"
                                                   value="{{ $s['price_'.$m['key']] ?? $m['default_price'] }}"
                                                   placeholder="{{ $m['default_price'] }}" maxlength="50">
                                        </div>
                                        <div class="form-text">Default: {{ $m['default_price'] }}</div>
                                    </div>
                                    {{-- Image upload --}}
                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold"><i class="bi bi-laptop me-1"></i>Foto (upload dari laptop)</label>
                                        <input type="file" name="{{ $m['img_field'] }}" class="form-control form-control-sm" accept="image/*">
                                    </div>
                                    {{-- Current / default image --}}
                                    @php $imgSrc = $m['cur_img'] ?: $m['default_img']; @endphp
                                    <div class="rounded-2 overflow-hidden" style="height:110px">
                                        <img src="{{ $imgSrc }}" alt="{{ $m['label'] }}" style="width:100%;height:100%;object-fit:cover" class="metode-img-{{ $m['key'] }}">
                                        @if($m['cur_img'])
                                        <div class="mt-1"><span class="badge bg-success" style="font-size:.65rem">Gambar kustom aktif</span></div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- ────────────────────── LOKASI & AREA ──────────────────── --}}
            <div class="tab-pane fade" id="bl-jam">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card lp-card h-100">
                            <div class="card-header lp-card-header"><i class="bi bi-clock me-2"></i>Kantor SCI {{ $city }} — Jam Operasional</div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label fw-semibold"><i class="bi bi-calendar-week me-1"></i>Senin – Sabtu</label>
                                        <input type="text" name="hours_weekday" class="form-control"
                                               value="{{ $s['hours_weekday'] ?? '08.00 – 20.00 WIB' }}" maxlength="100">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-semibold"><i class="bi bi-calendar-event me-1" style="color:#10b981"></i>Minggu &amp; Hari Libur</label>
                                        <input type="text" name="hours_weekend" class="form-control"
                                               value="{{ $s['hours_weekend'] ?? '09.00 – 16.00 WIB' }}" maxlength="100">
                                        <div class="form-text">Contoh: 09.00 – 16.00 WIB atau Tutup</div>
                                    </div>
                                    <div class="col-12">
                                        <div class="p-3 rounded-3" style="background:#f9f4ff;border:1.5px solid rgba(200,77,223,.15)">
                                            <div class="small fw-bold mb-1" style="color:var(--bs-primary)">Preview:</div>
                                            <div class="small text-muted">Senin–Sabtu: <span id="prevWeekday">{{ $s['hours_weekday'] ?? '08.00 – 20.00 WIB' }}</span></div>
                                            <div class="small text-muted">Minggu: <span id="prevWeekend">{{ $s['hours_weekend'] ?? '09.00 – 16.00 WIB' }}</span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card lp-card h-100">
                            <div class="card-header lp-card-header"><i class="bi bi-pin-map me-2"></i>Area Layanan Home Visit</div>
                            <div class="card-body">
                                <label class="form-label fw-semibold">Daftar Area <small class="text-muted fw-normal">(pisahkan dengan koma)</small></label>
                                <textarea name="areas" id="areasInput" class="form-control mb-3" rows="4"
                                          placeholder="Kota {{ $city }}, Kab. {{ $city }}, Sekitarnya">{{ $areasStr }}</textarea>
                                <div class="small fw-semibold mb-2" style="color:var(--bs-primary)">Preview Chip Area:</div>
                                <div id="areasPreview" class="d-flex gap-2 flex-wrap"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ────────────────────── FAQ ─────────────────────────────── --}}
            <div class="tab-pane fade" id="bl-faq">
                <div class="card lp-card">
                    <div class="card-header lp-card-header d-flex align-items-center justify-content-between">
                        <span><i class="bi bi-question-circle me-2"></i>Pertanyaan Umum Les Privat {{ $city }}</span>
                        <small class="fw-normal opacity-75">Accordion FAQ pada halaman cabang</small>
                    </div>
                    <div class="card-body">
                        <div id="faqList" class="d-flex flex-column gap-3 mb-4">
                            @foreach($faqItems as $fi => $faq)
                            <div class="faq-row border rounded-3 p-3" style="border-color:rgba(200,77,223,.15) !important;background:#fdf8ff">
                                <div class="d-flex align-items-start gap-2">
                                    <span class="badge fw-bold mt-1 faq-num" style="background:rgba(200,77,223,.1);color:#68117e;min-width:24px">{{ $fi+1 }}</span>
                                    <div class="flex-grow-1">
                                        <label class="form-label small fw-semibold mb-1">Pertanyaan</label>
                                        <input type="text" name="faq_q[]" value="{{ $faq['q'] }}" class="form-control form-control-sm mb-2" maxlength="300">
                                        <label class="form-label small fw-semibold mb-1">Jawaban</label>
                                        <textarea name="faq_a[]" class="form-control form-control-sm" rows="3" maxlength="1000">{{ $faq['a'] }}</textarea>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-danger faq-remove flex-shrink-0 mt-1"><i class="bi bi-trash"></i></button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <button type="button" id="faqAdd" class="btn btn-sm btn-outline-primary"><i class="bi bi-plus me-1"></i>Tambah FAQ</button>
                            <small class="text-muted">Rekomendasi: 6–8 pertanyaan</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ────────────────────── CTA — SIAP MULAI BELAJAR ─────────── --}}
            <div class="tab-pane fade" id="bl-cta">
                <div class="card lp-card">
                    <div class="card-header lp-card-header"><i class="bi bi-rocket me-2"></i>Siap Mulai Belajar — Section CTA</div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Eyebrow <small class="text-muted fw-normal">(label kecil di atas judul CTA)</small></label>
                                <input type="text" name="cta_eyebrow" class="form-control"
                                       value="{{ $s['cta_eyebrow'] ?? '🎉 Bergabung Sekarang' }}" maxlength="80">
                                <div class="form-text">Contoh: "🎉 Bergabung Sekarang"</div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Judul CTA <small class="text-muted fw-normal">(baris sebelum nama kota)</small></label>
                                <input type="text" name="cta_title" class="form-control"
                                       value="{{ $s['cta_title'] ?? 'Siap Mulai Belajar' }}" maxlength="120">
                                <div class="form-text">Nama kota otomatis ditambahkan di bawah judul ini.</div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Deskripsi CTA</label>
                                <textarea name="cta_desc" class="form-control" rows="3" maxlength="400">{{ $s['cta_desc'] ?? '' }}</textarea>
                                <div class="form-text">Kosongkan untuk menggunakan teks default (menyebutkan jumlah siswa dan kota).</div>
                            </div>
                            {{-- Preview --}}
                            <div class="col-12">
                                <div class="p-4 rounded-3 text-center" style="background:linear-gradient(160deg,#260632 0%,#461256 60%,#8b1fa8 100%)">
                                    <div style="display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.15);border-radius:50px;padding:5px 16px;font-size:.73rem;font-weight:700;color:rgba(255,255,255,.9);margin-bottom:.75rem">
                                        {{ $s['cta_eyebrow'] ?? '🎉 Bergabung Sekarang' }}
                                    </div>
                                    <div style="font-size:1.5rem;font-weight:900;color:white;line-height:1.2;margin-bottom:.5rem">
                                        {{ $s['cta_title'] ?? 'Siap Mulai Belajar' }}<br>
                                        <em style="font-style:italic;color:#f6af23">{{ $city }}?</em>
                                    </div>
                                    <div style="font-size:.9rem;color:rgba(255,255,255,.7)">{{ $s['cta_desc'] ?? 'Bergabung dengan siswa SCI '.$city.' yang telah merasakan manfaatnya.' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>{{-- end tab-content --}}

        {{-- Sticky Save Bar --}}
        <div class="position-sticky bottom-0 mt-4 py-3" style="z-index:100;background:linear-gradient(to top,var(--body-bg,#f8f5ff) 60%,transparent)">
            <div class="d-flex align-items-center justify-content-between gap-3 p-3 rounded-3"
                 style="background:white;box-shadow:0 -4px 24px rgba(38,6,50,.1);border:1px solid rgba(200,77,223,.12)">
                <div class="small text-muted d-none d-md-block">
                    <i class="bi bi-save me-1" style="color:var(--bs-primary)"></i>
                    Pastikan semua tab sudah dikonfigurasi sebelum menyimpan
                </div>
                <div class="d-flex gap-2 ms-auto">
                    <a href="{{ route('admin.landing.cabang.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x me-1"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-primary px-4 fw-bold">
                        <i class="bi bi-save me-1"></i>Simpan Semua Perubahan
                    </button>
                </div>
            </div>
        </div>

    </form>
</div>

{{-- FAQ Row Template --}}
<template id="faqRowTpl">
    <div class="faq-row border rounded-3 p-3" style="border-color:rgba(200,77,223,.15) !important;background:#fdf8ff">
        <div class="d-flex align-items-start gap-2">
            <span class="badge fw-bold mt-1 faq-num" style="background:rgba(200,77,223,.1);color:#68117e;min-width:24px">?</span>
            <div class="flex-grow-1">
                <label class="form-label small fw-semibold mb-1">Pertanyaan</label>
                <input type="text" name="faq_q[]" class="form-control form-control-sm mb-2" maxlength="300" placeholder="Pertanyaan yang sering diajukan...">
                <label class="form-label small fw-semibold mb-1">Jawaban</label>
                <textarea name="faq_a[]" class="form-control form-control-sm" rows="3" maxlength="1000" placeholder="Jawaban lengkap..."></textarea>
            </div>
            <button type="button" class="btn btn-sm btn-outline-danger faq-remove flex-shrink-0 mt-1"><i class="bi bi-trash"></i></button>
        </div>
    </div>
</template>

{{-- Subject Row Template --}}
<template id="subjRowTpl">
    <div class="col-md-6 subj-row">
        <div class="p-3 rounded-3" style="background:#f9f4ff;border:1.5px solid rgba(200,77,223,.15)">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="badge fw-bold subj-num" style="background:rgba(200,77,223,.1);color:#68117e;min-width:28px">?</span>
                <button type="button" class="btn btn-sm btn-outline-danger subj-remove py-0 px-2"><i class="bi bi-trash"></i></button>
            </div>
            <div class="row g-2">
                <div class="col-2">
                    <label class="form-label small fw-semibold">Ikon</label>
                    <input type="text" name="subj_icon[]" class="form-control form-control-sm" maxlength="10" placeholder="📚">
                </div>
                <div class="col-10">
                    <label class="form-label small fw-semibold">Nama Program *</label>
                    <input type="text" name="subj_name[]" class="form-control form-control-sm" maxlength="60" placeholder="Matematika">
                </div>
                <div class="col-12">
                    <label class="form-label small fw-semibold">Deskripsi</label>
                    <input type="text" name="subj_desc[]" class="form-control form-control-sm" maxlength="150">
                </div>
                <div class="col-7">
                    <label class="form-label small fw-semibold">Teks Badge</label>
                    <input type="text" name="subj_badge[]" class="form-control form-control-sm" maxlength="30" placeholder="Terpopuler">
                </div>
                <div class="col-5">
                    <label class="form-label small fw-semibold">Warna Badge</label>
                    <select name="subj_badge_type[]" class="form-select form-select-sm">
                        <option value="popular">Ungu (populer)</option>
                        <option value="hot">Merah (hot)</option>
                        <option value="level">Biru (level)</option>
                        <option value="general" selected>Hijau (umum)</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
.lp-tabs .nav-link { color:var(--text-muted,#6b7280); border-radius:10px 10px 0 0; font-weight:600; font-size:.875rem; padding:.6rem 1.1rem; }
.lp-tabs .nav-link.active { color:var(--bs-primary,#c84ddf); background:var(--card-bg,white); border-color:var(--card-border,#dee2e6) var(--card-border,#dee2e6) var(--card-bg,white); }
.lp-card { border:1px solid rgba(200,77,223,.15); border-radius:16px; overflow:hidden; box-shadow:0 2px 16px rgba(38,6,50,.06); }
.lp-card-header { background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%); color:white; font-weight:700; font-size:.925rem; padding:.9rem 1.25rem; }
</style>

@push('scripts')
<script>
/* ── Promo ticker ── */
const promoList = document.getElementById('promoList');
function updatePromoNumbers() {
    promoList.querySelectorAll('.promo-row').forEach((row, i) => {
        const b = row.querySelector('.badge'); if (b) b.textContent = i + 1;
    });
    renderPromoPreview();
}
function addPromoRow(val = '') {
    if (promoList.querySelectorAll('.promo-row').length >= 8) return alert('Maksimal 8 item promo.');
    const d = document.createElement('div');
    d.className = 'promo-row d-flex align-items-center gap-2';
    d.innerHTML = `<span class="badge bg-light text-muted fw-normal" style="font-size:.68rem;min-width:24px"></span><input type="text" name="promo_items[]" value="${val.replace(/"/g,'&quot;')}" class="form-control form-control-sm" placeholder="Teks promo..." maxlength="200"><button type="button" class="btn btn-sm btn-outline-danger promo-remove flex-shrink-0"><i class="bi bi-x-lg"></i></button>`;
    promoList.appendChild(d);
    d.querySelector('input').addEventListener('input', renderPromoPreview);
    updatePromoNumbers();
}
document.getElementById('promoAdd').addEventListener('click', () => addPromoRow());
promoList.addEventListener('click', e => { if (e.target.closest('.promo-remove')) { e.target.closest('.promo-row').remove(); updatePromoNumbers(); } });
promoList.querySelectorAll('input').forEach(i => i.addEventListener('input', renderPromoPreview));
function renderPromoPreview() {
    const vals = [...promoList.querySelectorAll('input')].map(i => i.value).filter(Boolean);
    document.getElementById('promoPreview').innerHTML = vals.map(v => `<span style="white-space:nowrap">📢 ${v}</span><span style="opacity:.4">|</span>`).join('');
}
renderPromoPreview();

/* ── Hero live preview ── */
const heroBadgeIn = document.querySelector('[name=hero_badge]');
const heroDescIn  = document.querySelector('[name=hero_description]');
const descCount   = document.getElementById('heroDescCount');
function updateHeroPreview() {
    if (heroBadgeIn) document.getElementById('prevBadge').textContent = heroBadgeIn.value;
    if (heroDescIn) { document.getElementById('prevDesc').textContent = heroDescIn.value; if(descCount) descCount.textContent = heroDescIn.value.length; }
}
if (heroBadgeIn) heroBadgeIn.addEventListener('input', updateHeroPreview);
if (heroDescIn)  heroDescIn.addEventListener('input', updateHeroPreview);
updateHeroPreview();

/* ── Jam live preview ── */
const weekdayIn = document.querySelector('[name=hours_weekday]');
const weekendIn = document.querySelector('[name=hours_weekend]');
if (weekdayIn) weekdayIn.addEventListener('input', () => { document.getElementById('prevWeekday').textContent = weekdayIn.value; });
if (weekendIn) weekendIn.addEventListener('input', () => { document.getElementById('prevWeekend').textContent = weekendIn.value; });

/* ── Area chips preview ── */
const areasInput   = document.getElementById('areasInput');
const areasPreview = document.getElementById('areasPreview');
function renderAreaChips() {
    const chips = areasInput.value.split(',').map(s => s.trim()).filter(Boolean);
    areasPreview.innerHTML = chips.map(c => `<span style="display:inline-flex;align-items:center;gap:.3rem;padding:.35rem .9rem;border-radius:8px;background:#fdf6ff;border:1.5px solid rgba(200,77,223,.2);font-size:.8rem;font-weight:600;color:#260632">📍 ${c}</span>`).join('');
}
if (areasInput) { areasInput.addEventListener('input', renderAreaChips); renderAreaChips(); }

/* ── FAQ dynamic rows ── */
const faqList = document.getElementById('faqList');
const faqTpl  = document.getElementById('faqRowTpl');
function updateFaqNumbers() {
    faqList.querySelectorAll('.faq-row').forEach((row, i) => {
        const n = row.querySelector('.faq-num'); if (n) n.textContent = i + 1;
    });
}
document.getElementById('faqAdd').addEventListener('click', () => {
    faqList.appendChild(faqTpl.content.cloneNode(true)); updateFaqNumbers();
});
faqList.addEventListener('click', e => {
    if (e.target.closest('.faq-remove')) { e.target.closest('.faq-row').remove(); updateFaqNumbers(); }
});

/* ── Subject dynamic rows ── */
const subjList = document.getElementById('subjList');
const subjTpl  = document.getElementById('subjRowTpl');
function updateSubjNumbers() {
    subjList.querySelectorAll('.subj-row').forEach((row, i) => {
        const n = row.querySelector('.subj-num'); if (n) n.textContent = i + 1;
    });
}
document.getElementById('subjAdd').addEventListener('click', () => {
    subjList.appendChild(subjTpl.content.cloneNode(true)); updateSubjNumbers();
});
subjList.addEventListener('click', e => {
    if (e.target.closest('.subj-remove')) { e.target.closest('.subj-row').remove(); updateSubjNumbers(); }
});
</script>
@endpush
@endsection
