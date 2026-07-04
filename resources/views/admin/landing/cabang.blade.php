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

    @php $city = $branch->city ?: $branch->name; @endphp

    {{-- Info box --}}
    <div class="alert border-0 mb-4 small" style="background:rgba(200,77,223,.06);border-left:4px solid #c84ddf !important;border-radius:12px">
        <i class="bi bi-info-circle me-1" style="color:var(--bs-primary)"></i>
        <strong>Kontak, alamat &amp; email</strong> dikelola di menu <a href="{{ route('owner.branches.edit', $branch) }}">Kelola Cabang</a>.
        <strong>Paket &amp; harga</strong> dikelola di menu <a href="{{ route('admin.packages.index') }}">Paket</a>.
        <strong>Testimoni</strong> menggunakan testimoni global yang dikelola di <a href="{{ route('admin.landing.index') }}#tab-testimonials">Landing Utama → Testimoni</a>.
    </div>

    <form action="{{ route('admin.landing.cabang.update', $branch) }}" method="POST" id="branchLandingForm">
        @csrf @method('PUT')

        {{-- Tabs --}}
        <ul class="nav nav-tabs lp-tabs mb-4" id="blTabs">
            <li class="nav-item"><button type="button" class="nav-link active" data-bs-toggle="tab" data-bs-target="#bl-promo"><i class="bi bi-megaphone me-1"></i>Promo Ticker</button></li>
            <li class="nav-item"><button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#bl-hero"><i class="bi bi-house-door me-1"></i>Hero</button></li>
            <li class="nav-item"><button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#bl-metode"><i class="bi bi-grid-3x3 me-1"></i>Metode Belajar</button></li>
            <li class="nav-item"><button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#bl-jam"><i class="bi bi-clock me-1"></i>Jam &amp; Area</button></li>
            <li class="nav-item"><button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#bl-faq"><i class="bi bi-question-circle me-1"></i>FAQ</button></li>
        </ul>

        <div class="tab-content">

            {{-- ────── PROMO TICKER ────── --}}
            <div class="tab-pane fade show active" id="bl-promo">
                <div class="card lp-card">
                    <div class="card-header lp-card-header">
                        <i class="bi bi-megaphone me-2"></i>Promo Ticker
                        <span class="ms-2 badge bg-light text-dark fw-normal" style="font-size:.72rem">Teks bergulir di baris paling atas halaman cabang</span>
                    </div>
                    <div class="card-body">
                        @php
                            $promoItems = json_decode($s['promo_items'] ?? '[]', true) ?: [];
                            if (empty($promoItems)) {
                                $promoItems = [
                                    'Mulai belajar dari Rp 50.000/sesi',
                                    'Garansi nilai naik atau sesi gratis!',
                                    'Tersedia Home Visit, Online & Offline',
                                    'Gratis Konsultasi Pertama',
                                    '#1 Les Privat Terbaik di '.$city,
                                ];
                            }
                        @endphp

                        <div class="alert border-0 mb-4" style="background:rgba(246,175,35,.08);border-left:3px solid #f6af23 !important;border-radius:10px;font-size:.83rem">
                            <i class="bi bi-lightbulb-fill me-1" style="color:#f6af23"></i>
                            Tambahkan hingga <strong>8 item</strong> teks promo yang akan bergulir otomatis di bagian atas halaman. Pisahkan dengan tombol tambah/hapus di bawah.
                        </div>

                        <div id="promoList" class="d-flex flex-column gap-2 mb-3">
                            @foreach($promoItems as $i => $item)
                            <div class="promo-row d-flex align-items-center gap-2">
                                <span class="drag-handle text-muted" style="cursor:grab;font-size:1.1rem;flex-shrink:0">⠿</span>
                                <span class="badge bg-light text-muted fw-normal" style="font-size:.68rem;min-width:22px">{{ $i+1 }}</span>
                                <input type="text" name="promo_items[]" value="{{ $item }}"
                                       class="form-control form-control-sm" placeholder="Teks promo..." maxlength="200">
                                <button type="button" class="btn btn-sm btn-outline-danger flex-shrink-0 promo-remove" title="Hapus">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>
                            @endforeach
                        </div>

                        <button type="button" id="promoAdd" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-plus me-1"></i>Tambah Item Promo
                        </button>

                        {{-- Preview --}}
                        <div class="mt-4 p-3 rounded-3" style="background:linear-gradient(90deg,#e09000,#f6af23);overflow:hidden">
                            <div class="text-dark fw-bold small mb-1">Preview Ticker:</div>
                            <div id="promoPreview" class="d-flex gap-3 flex-nowrap overflow-hidden" style="font-size:.82rem;font-weight:600;color:#260632"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ────── HERO ────── --}}
            <div class="tab-pane fade" id="bl-hero">
                <div class="card lp-card">
                    <div class="card-header lp-card-header"><i class="bi bi-house-door me-2"></i>Konten Hero Section</div>
                    <div class="card-body">
                        <div class="alert border-0 mb-4" style="background:rgba(200,77,223,.06);border-left:3px solid #c84ddf !important;border-radius:10px;font-size:.83rem">
                            <i class="bi bi-info-circle me-1" style="color:var(--bs-primary)"></i>
                            Judul utama <strong>"Les Privat Terbaik di {{ $city }}"</strong> diatur otomatis dari nama kota cabang.
                            Di sini Anda bisa mengubah teks badge dan deskripsi sub-judul.
                        </div>

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">
                                    Teks Badge <small class="text-muted fw-normal">(tampil di atas judul utama)</small>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">🏆</span>
                                    <input type="text" name="hero_badge" class="form-control"
                                           value="{{ $s['hero_badge'] ?? '#1 Jasa Les Privat '.strtoupper($city).' Terpercaya' }}"
                                           maxlength="200" placeholder="#1 Jasa Les Privat JAKARTA Terpercaya">
                                </div>
                                <div class="form-text">Contoh: "#1 Jasa Les Privat JAKARTA Terpercaya"</div>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">
                                    Deskripsi Hero <small class="text-muted fw-normal">(tampil di bawah judul, sebelum form)</small>
                                </label>
                                <textarea name="hero_description" class="form-control" rows="4" maxlength="600"
                                          placeholder="Smart Center Indonesia hadir di {{ $city }} dengan tutor bersertifikat...">{{ $s['hero_description'] ?? 'Smart Center Indonesia hadir di '.$city.' dengan tutor bersertifikat. Layanan home visit, online, dan offline untuk semua jenjang dari TK hingga umum.' }}</textarea>
                                <div class="form-text"><span id="heroDescCount">0</span>/600 karakter</div>
                            </div>

                            {{-- Live preview --}}
                            <div class="col-12">
                                <div class="p-3 rounded-3" style="background:linear-gradient(160deg,#1a0228 0%,#461256 45%,#6d1a7e 100%)">
                                    <div class="small fw-bold mb-2" style="color:rgba(255,255,255,.5)">Preview Hero:</div>
                                    <div style="background:rgba(246,175,35,.15);border:1px solid rgba(246,175,35,.3);border-radius:50px;display:inline-flex;align-items:center;gap:6px;padding:5px 14px;font-size:.73rem;font-weight:700;color:#f6af23;margin-bottom:.75rem">
                                        🏆 <span id="prevBadge">{{ $s['hero_badge'] ?? '#1 Jasa Les Privat '.strtoupper($city).' Terpercaya' }}</span>
                                    </div><br>
                                    <div style="font-size:1.6rem;font-weight:900;color:white;line-height:1.1;margin-bottom:.5rem">
                                        Les Privat <em style="font-style:italic;color:#f6af23">Terbaik</em><br>di {{ $city }}
                                    </div>
                                    <div style="font-size:.85rem;color:rgba(255,255,255,.7);max-width:400px" id="prevDesc">{{ $s['hero_description'] ?? 'Smart Center Indonesia hadir di '.$city.' dengan tutor bersertifikat.' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ────── METODE BELAJAR ────── --}}
            <div class="tab-pane fade" id="bl-metode">
                <div class="card lp-card">
                    <div class="card-header lp-card-header"><i class="bi bi-grid-3x3 me-2"></i>Harga Metode Belajar</div>
                    <div class="card-body">
                        <div class="alert border-0 mb-4" style="background:rgba(200,77,223,.06);border-left:3px solid #c84ddf !important;border-radius:10px;font-size:.83rem">
                            <i class="bi bi-info-circle me-1" style="color:var(--bs-primary)"></i>
                            Harga yang diisi di sini tampil di kartu <strong>Pilih Cara Belajar Terbaik</strong> pada halaman cabang.
                            Kosongkan untuk menggunakan harga default.
                        </div>

                        <div class="row g-4">
                            {{-- Home Visit --}}
                            <div class="col-md-4">
                                <div class="p-3 rounded-3 h-100" style="background:#f9f4ff;border:1.5px solid rgba(200,77,223,.15)">
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <div style="font-size:1.5rem">🏠</div>
                                        <div class="fw-bold" style="color:#260632">Home Visit</div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold">Harga per Sesi</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text">Rp</span>
                                            <input type="text" name="price_homevisi" class="form-control price-input"
                                                   value="{{ $s['price_homevisi'] ?? 'Rp 65.000' }}"
                                                   placeholder="Rp 65.000" maxlength="50">
                                        </div>
                                        <div class="form-text">Default: Rp 65.000</div>
                                    </div>
                                    <div class="price-preview-card p-2 rounded-2 text-center" style="background:white;border:1px solid rgba(200,77,223,.2)">
                                        <div class="small text-muted">Mulai</div>
                                        <div class="fw-bold" style="color:#68117e;font-size:1.1rem" id="prevHomevisi">{{ $s['price_homevisi'] ?? 'Rp 65.000' }}</div>
                                        <div class="small text-muted">/sesi</div>
                                    </div>
                                </div>
                            </div>

                            {{-- Online --}}
                            <div class="col-md-4">
                                <div class="p-3 rounded-3 h-100" style="background:#f9f4ff;border:1.5px solid rgba(200,77,223,.15)">
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <div style="font-size:1.5rem">🖥️</div>
                                        <div class="fw-bold" style="color:#260632">Online</div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold">Harga per Sesi</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text">Rp</span>
                                            <input type="text" name="price_online" class="form-control price-input"
                                                   value="{{ $s['price_online'] ?? 'Rp 50.000' }}"
                                                   placeholder="Rp 50.000" maxlength="50">
                                        </div>
                                        <div class="form-text">Default: Rp 50.000</div>
                                    </div>
                                    <div class="price-preview-card p-2 rounded-2 text-center" style="background:white;border:1px solid rgba(200,77,223,.2)">
                                        <div class="small text-muted">Mulai</div>
                                        <div class="fw-bold" style="color:#68117e;font-size:1.1rem" id="prevOnline">{{ $s['price_online'] ?? 'Rp 50.000' }}</div>
                                        <div class="small text-muted">/sesi</div>
                                    </div>
                                </div>
                            </div>

                            {{-- Offline --}}
                            <div class="col-md-4">
                                <div class="p-3 rounded-3 h-100" style="background:#f9f4ff;border:1.5px solid rgba(200,77,223,.15)">
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <div style="font-size:1.5rem">🏫</div>
                                        <div class="fw-bold" style="color:#260632">Offline (Di Kantor)</div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold">Harga per Sesi</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text">Rp</span>
                                            <input type="text" name="price_offline" class="form-control price-input"
                                                   value="{{ $s['price_offline'] ?? 'Rp 55.000' }}"
                                                   placeholder="Rp 55.000" maxlength="50">
                                        </div>
                                        <div class="form-text">Default: Rp 55.000</div>
                                    </div>
                                    <div class="price-preview-card p-2 rounded-2 text-center" style="background:white;border:1px solid rgba(200,77,223,.2)">
                                        <div class="small text-muted">Mulai</div>
                                        <div class="fw-bold" style="color:#68117e;font-size:1.1rem" id="prevOffline">{{ $s['price_offline'] ?? 'Rp 55.000' }}</div>
                                        <div class="small text-muted">/sesi</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ────── JAM & AREA ────── --}}
            <div class="tab-pane fade" id="bl-jam">
                <div class="row g-4">
                    {{-- Jam Operasional --}}
                    <div class="col-md-6">
                        <div class="card lp-card h-100">
                            <div class="card-header lp-card-header"><i class="bi bi-clock me-2"></i>Jam Operasional</div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label fw-semibold">
                                            <i class="bi bi-calendar-week me-1" style="color:var(--bs-primary)"></i>
                                            Senin – Sabtu
                                        </label>
                                        <input type="text" name="hours_weekday" class="form-control"
                                               value="{{ $s['hours_weekday'] ?? '08.00 – 20.00 WIB' }}"
                                               placeholder="08.00 – 20.00 WIB" maxlength="100">
                                        <div class="form-text">Contoh: 08.00 – 20.00 WIB</div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-semibold">
                                            <i class="bi bi-calendar-event me-1" style="color:#10b981"></i>
                                            Minggu &amp; Hari Libur
                                        </label>
                                        <input type="text" name="hours_weekend" class="form-control"
                                               value="{{ $s['hours_weekend'] ?? '09.00 – 16.00 WIB' }}"
                                               placeholder="09.00 – 16.00 WIB" maxlength="100">
                                        <div class="form-text">Contoh: 09.00 – 16.00 WIB atau Tutup</div>
                                    </div>

                                    {{-- Preview --}}
                                    <div class="col-12">
                                        <div class="p-3 rounded-3" style="background:#f9f4ff;border:1.5px solid rgba(200,77,223,.15)">
                                            <div class="small fw-bold mb-2" style="color:var(--bs-primary)">Preview:</div>
                                            <div class="d-flex gap-2 align-items-start mb-1">
                                                <i class="bi bi-clock-fill mt-1" style="color:var(--bs-primary);font-size:.75rem;flex-shrink:0;width:14px"></i>
                                                <div class="small">
                                                    <div class="fw-semibold">Jam Operasional</div>
                                                    <div class="text-muted">Senin – Sabtu: <span id="prevWeekday">{{ $s['hours_weekday'] ?? '08.00 – 20.00 WIB' }}</span></div>
                                                    <div class="text-muted">Minggu: <span id="prevWeekend">{{ $s['hours_weekend'] ?? '09.00 – 16.00 WIB' }}</span></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Area Layanan --}}
                    <div class="col-md-6">
                        <div class="card lp-card h-100">
                            <div class="card-header lp-card-header"><i class="bi bi-pin-map me-2"></i>Area Layanan Home Visit</div>
                            <div class="card-body">
                                @php
                                    $areasArr = json_decode($s['areas'] ?? '[]', true) ?: [];
                                    $areasStr = implode(', ', $areasArr);
                                @endphp
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Daftar Area <small class="text-muted fw-normal">(pisahkan dengan koma)</small></label>
                                    <textarea name="areas" id="areasInput" class="form-control" rows="4"
                                              placeholder="Kota {{ $city }}, Kab. {{ $city }}, Sekitarnya">{{ $areasStr }}</textarea>
                                    <div class="form-text">Contoh: Kota Jakarta, Jakarta Selatan, Jakarta Timur, Tangerang</div>
                                </div>

                                {{-- Tag-style preview --}}
                                <div>
                                    <div class="small fw-semibold mb-2" style="color:var(--bs-primary)">Preview Chip Area:</div>
                                    <div id="areasPreview" class="d-flex gap-2 flex-wrap"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ────── FAQ ────── --}}
            <div class="tab-pane fade" id="bl-faq">
                <div class="card lp-card">
                    <div class="card-header lp-card-header d-flex align-items-center justify-content-between">
                        <span><i class="bi bi-question-circle me-2"></i>Pertanyaan Umum (FAQ)</span>
                        <small class="fw-normal opacity-75">Tampil sebagai accordion pada halaman cabang</small>
                    </div>
                    <div class="card-body">
                        @php
                            $faqItems = json_decode($s['faq_items'] ?? '[]', true) ?: [];
                            if (empty($faqItems)) {
                                $faqItems = [
                                    ['q' => 'Berapa harga les privat SCI '.$city.'?',    'a' => 'Harga les privat SCI '.$city.' mulai dari Rp 50.000/sesi untuk online hingga Rp 65.000/sesi untuk home visit. Tersedia paket bulanan dan per-sesi yang lebih hemat.'],
                                    ['q' => 'Apakah bisa les privat home visit di '.$city.'?', 'a' => 'Ya! SCI '.$city.' melayani home visit ke seluruh kota dan kabupaten di '.$city.'. Tutor profesional kami siap datang ke rumah Anda sesuai jadwal yang disepakati.'],
                                    ['q' => 'Bagaimana cara mendaftar les privat di SCI '.$city.'?', 'a' => 'Isi formulir konsultasi gratis di halaman ini, atau hubungi kami via WhatsApp. Tim kami akan menghubungi Anda dalam 1 jam untuk mencocokkan tutor yang sesuai.'],
                                    ['q' => 'Apakah ada garansi nilai naik?', 'a' => 'Ya! SCI memberikan garansi nilai naik atau sesi gratis.'],
                                ];
                            }
                        @endphp

                        <div id="faqList" class="d-flex flex-column gap-3 mb-4">
                            @foreach($faqItems as $fi => $faq)
                            <div class="faq-row border rounded-3 p-3" style="border-color:rgba(200,77,223,.15) !important;background:#fdf8ff">
                                <div class="d-flex align-items-start gap-2 mb-2">
                                    <span class="badge fw-bold mt-1" style="background:rgba(200,77,223,.1);color:#68117e;min-width:24px">{{ $fi+1 }}</span>
                                    <div class="flex-grow-1">
                                        <label class="form-label small fw-semibold mb-1">Pertanyaan</label>
                                        <input type="text" name="faq_q[]" value="{{ $faq['q'] }}"
                                               class="form-control form-control-sm mb-2"
                                               placeholder="Pertanyaan yang sering diajukan..." maxlength="300">
                                        <label class="form-label small fw-semibold mb-1">Jawaban</label>
                                        <textarea name="faq_a[]" class="form-control form-control-sm" rows="3"
                                                  placeholder="Jawaban lengkap..." maxlength="1000">{{ $faq['a'] }}</textarea>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-danger faq-remove flex-shrink-0 mt-1" title="Hapus FAQ">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="d-flex align-items-center gap-3">
                            <button type="button" id="faqAdd" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-plus me-1"></i>Tambah FAQ
                            </button>
                            <small class="text-muted">Rekomendasi: 6–8 pertanyaan</small>
                        </div>
                    </div>
                </div>
            </div>

        </div>{{-- end tab-content --}}

        {{-- Sticky Save Bar --}}
        <div class="position-sticky bottom-0 mt-4 py-3 px-0" style="z-index:100;background:linear-gradient(to top,var(--body-bg,#f8f5ff) 60%,transparent)">
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
        <div class="d-flex align-items-start gap-2 mb-2">
            <span class="badge fw-bold mt-1 faq-num" style="background:rgba(200,77,223,.1);color:#68117e;min-width:24px">?</span>
            <div class="flex-grow-1">
                <label class="form-label small fw-semibold mb-1">Pertanyaan</label>
                <input type="text" name="faq_q[]" class="form-control form-control-sm mb-2"
                       placeholder="Pertanyaan yang sering diajukan..." maxlength="300">
                <label class="form-label small fw-semibold mb-1">Jawaban</label>
                <textarea name="faq_a[]" class="form-control form-control-sm" rows="3"
                          placeholder="Jawaban lengkap..." maxlength="1000"></textarea>
            </div>
            <button type="button" class="btn btn-sm btn-outline-danger faq-remove flex-shrink-0 mt-1" title="Hapus FAQ">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    </div>
</template>

<style>
.lp-tabs .nav-link { color: var(--text-muted,#6b7280); border-radius: 10px 10px 0 0; font-weight: 600; font-size: .875rem; padding: .6rem 1.1rem; }
.lp-tabs .nav-link.active { color: var(--bs-primary,#c84ddf); background: var(--card-bg,white); border-color: var(--card-border,#dee2e6) var(--card-border,#dee2e6) var(--card-bg,white); }
.lp-card { border: 1px solid rgba(200,77,223,.15); border-radius: 16px; overflow: hidden; box-shadow: 0 2px 16px rgba(38,6,50,.06); }
.lp-card-header { background: linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%); color: white; font-weight: 700; font-size: .925rem; padding: .9rem 1.25rem; }
.drag-handle { opacity:.4; transition:opacity .2s; }
.drag-handle:hover { opacity:.85; }
</style>

@push('scripts')
<script>
/* ── Promo ticker dynamic rows ── */
const promoList = document.getElementById('promoList');
const promoAdd  = document.getElementById('promoAdd');

function updatePromoNumbers() {
    promoList.querySelectorAll('.promo-row').forEach((row, i) => {
        row.querySelector('.badge').textContent = i + 1;
    });
    renderPromoPreview();
}

function addPromoRow(val = '') {
    if (promoList.querySelectorAll('.promo-row').length >= 8) {
        return alert('Maksimal 8 item promo.');
    }
    const div = document.createElement('div');
    div.className = 'promo-row d-flex align-items-center gap-2';
    div.innerHTML = `
        <span class="drag-handle text-muted" style="cursor:grab;font-size:1.1rem;flex-shrink:0">⠿</span>
        <span class="badge bg-light text-muted fw-normal" style="font-size:.68rem;min-width:22px"></span>
        <input type="text" name="promo_items[]" value="${val}" class="form-control form-control-sm" placeholder="Teks promo..." maxlength="200">
        <button type="button" class="btn btn-sm btn-outline-danger flex-shrink-0 promo-remove" title="Hapus"><i class="bi bi-x-lg"></i></button>`;
    promoList.appendChild(div);
    div.querySelector('input').addEventListener('input', renderPromoPreview);
    updatePromoNumbers();
}

promoAdd.addEventListener('click', () => addPromoRow());

promoList.addEventListener('click', e => {
    if (e.target.closest('.promo-remove')) {
        e.target.closest('.promo-row').remove();
        updatePromoNumbers();
    }
});

promoList.querySelectorAll('input').forEach(i => i.addEventListener('input', renderPromoPreview));

function renderPromoPreview() {
    const vals = [...promoList.querySelectorAll('input')].map(i => i.value).filter(v => v.trim());
    const prev = document.getElementById('promoPreview');
    prev.innerHTML = vals.map(v => `<span style="white-space:nowrap">📢 ${v}</span><span style="opacity:.4">|</span>`).join('');
}
renderPromoPreview();

/* ── Hero live preview ── */
const heroBadgeIn = document.querySelector('[name=hero_badge]');
const heroDescIn  = document.querySelector('[name=hero_description]');
const descCount   = document.getElementById('heroDescCount');

function updateHeroPreview() {
    if (heroBadgeIn) document.getElementById('prevBadge').textContent = heroBadgeIn.value;
    if (heroDescIn) {
        document.getElementById('prevDesc').textContent = heroDescIn.value;
        descCount.textContent = heroDescIn.value.length;
    }
}
if (heroBadgeIn) heroBadgeIn.addEventListener('input', updateHeroPreview);
if (heroDescIn)  heroDescIn.addEventListener('input', updateHeroPreview);
updateHeroPreview();

/* ── Pricing live preview ── */
[['price_homevisi','prevHomevisi'],['price_online','prevOnline'],['price_offline','prevOffline']].forEach(([name, id]) => {
    const inp = document.querySelector(`[name=${name}]`);
    const out = document.getElementById(id);
    if (inp && out) inp.addEventListener('input', () => out.textContent = inp.value || '—');
});

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
    areasPreview.innerHTML = chips.map(c =>
        `<span style="display:inline-flex;align-items:center;gap:.3rem;padding:.35rem .9rem;border-radius:8px;background:#fdf6ff;border:1.5px solid rgba(200,77,223,.2);font-size:.8rem;font-weight:600;color:#260632">📍 ${c}</span>`
    ).join('');
}
if (areasInput) { areasInput.addEventListener('input', renderAreaChips); renderAreaChips(); }

/* ── FAQ dynamic rows ── */
const faqList = document.getElementById('faqList');
const faqTpl  = document.getElementById('faqRowTpl');

function updateFaqNumbers() {
    faqList.querySelectorAll('.faq-row').forEach((row, i) => {
        const num = row.querySelector('.faq-num');
        if (num) num.textContent = i + 1;
    });
}

document.getElementById('faqAdd').addEventListener('click', () => {
    const clone = faqTpl.content.cloneNode(true);
    faqList.appendChild(clone);
    updateFaqNumbers();
});

faqList.addEventListener('click', e => {
    if (e.target.closest('.faq-remove')) {
        e.target.closest('.faq-row').remove();
        updateFaqNumbers();
    }
});
</script>
@endpush
@endsection
