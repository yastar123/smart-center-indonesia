@extends('layouts.app')
@section('title','Daftar Kelas Tersedia')
@section('page-title','Daftar Kelas')

@section('content')

{{-- HEADER BANNER --}}
<div class="dashboard-card mb-4 fade-up"
     style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3" style="position:relative">
        <div class="d-flex align-items-center gap-3">
            <div style="width:52px;height:52px;border-radius:16px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:24px;flex-shrink:0">
                <i class="bi bi-grid-3x3-gap-fill"></i>
            </div>
            <div>
                <div style="font-size:11px;opacity:.6;margin-bottom:3px;text-transform:uppercase;letter-spacing:.08em">Portal Siswa</div>
                <h4 class="fw-bold mb-1" style="color:white;letter-spacing:-.02em">Daftar Kelas Tersedia</h4>
                <p class="mb-0" style="opacity:.75;font-size:13px">Pilih kelas yang sesuai dan daftarkan diri Anda</p>
            </div>
        </div>
        <div class="text-end">
            <div style="font-size:28px;font-weight:800;color:white">{{ $classes->count() }}</div>
            <div style="font-size:12px;opacity:.7">Kelas Tersedia</div>
        </div>
    </div>
</div>

{{-- FILTER BAR --}}
<div class="dashboard-card mb-4 fade-up">
    <form id="filterForm" method="GET" action="{{ route('siswa.courses.fees') }}">
        <div class="row g-2 align-items-end">
            <div class="col-12 col-md-3">
                <label class="form-label fw-semibold" style="font-size:11px;text-transform:uppercase;letter-spacing:.05em">Mata Pelajaran</label>
                <select name="course_id" class="form-select form-select-sm" style="border-radius:8px" onchange="this.form.submit()">
                    <option value="">Semua Mapel</option>
                    @foreach($courses as $c)
                    <option value="{{ $c->id }}" {{ request('course_id') == $c->id ? 'selected' : '' }}>{{ $c->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label fw-semibold" style="font-size:11px;text-transform:uppercase;letter-spacing:.05em">Jenis Kelas</label>
                <select name="jenis" class="form-select form-select-sm" style="border-radius:8px" onchange="this.form.submit()">
                    <option value="">Semua</option>
                    <option value="online"  {{ request('jenis')=='online'  ? 'selected' : '' }}>🌐 Online</option>
                    <option value="offline" {{ request('jenis')=='offline' ? 'selected' : '' }}>📍 Offline</option>
                    <option value="private" {{ request('jenis')=='private' ? 'selected' : '' }}>🔒 Private</option>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label fw-semibold" style="font-size:11px;text-transform:uppercase;letter-spacing:.05em">Guru</label>
                <select name="guru_id" class="form-select form-select-sm" style="border-radius:8px" onchange="this.form.submit()">
                    <option value="">Semua Guru</option>
                    @foreach($teachers as $t)
                    <option value="{{ $t->id }}" {{ request('guru_id') == $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label fw-semibold" style="font-size:11px;text-transform:uppercase;letter-spacing:.05em">Harga Min (Rp)</label>
                <input type="number" name="harga_min" value="{{ request('harga_min') }}" placeholder="0"
                    class="form-control form-control-sm" style="border-radius:8px">
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label fw-semibold" style="font-size:11px;text-transform:uppercase;letter-spacing:.05em">Harga Max (Rp)</label>
                <input type="number" name="harga_max" value="{{ request('harga_max') }}" placeholder="999999999"
                    class="form-control form-control-sm" style="border-radius:8px">
            </div>
            <div class="col-12 col-md-1 d-flex gap-1">
                <button type="submit" class="btn btn-primary btn-sm w-100" style="border-radius:8px"><i class="bi bi-search"></i></button>
                @if(request()->hasAny(['course_id','jenis','guru_id','cabang_id','harga_min','harga_max']))
                <a href="{{ route('siswa.courses.fees') }}" class="btn btn-outline-secondary btn-sm" style="border-radius:8px" title="Reset"><i class="bi bi-x-lg"></i></a>
                @endif
            </div>
        </div>
    </form>
</div>

{{-- CARD GRID --}}
@if($classes->isEmpty())
<div class="dashboard-card fade-up">
    <div class="text-center py-5">
        <i class="bi bi-journal-x" style="font-size:56px;opacity:.25;display:block;margin-bottom:16px;color:var(--primary)"></i>
        <div class="fw-bold mb-1" style="font-size:17px">Tidak Ada Kelas Ditemukan</div>
        <div class="text-muted" style="font-size:13px">Coba ubah atau hapus filter untuk melihat lebih banyak kelas.</div>
        @if(request()->hasAny(['course_id','jenis','guru_id','harga_min','harga_max']))
        <a href="{{ route('siswa.courses.fees') }}" class="btn btn-outline-primary mt-3" style="border-radius:10px">
            <i class="bi bi-arrow-clockwise me-1"></i>Reset Filter
        </a>
        @endif
    </div>
</div>
@else
<div class="row g-3 fade-up">
    @foreach($classes as $class)
    @php
        $isEnrolled = in_array($class->id, $enrolledClassIds);
        $fee = $class->mataPelajaran?->fee;
        $price = $fee?->amount ?? 0;
        $jenisConfig = [
            'online'  => ['icon' => 'bi-wifi',        'bg' => 'var(--soft-success-bg)',  'color' => 'var(--soft-success-text)',  'label' => 'Online'],
            'offline' => ['icon' => 'bi-building',     'bg' => 'var(--soft-primary-bg)', 'color' => 'var(--soft-primary-text)', 'label' => 'Offline'],
            'private' => ['icon' => 'bi-person-lock',  'bg' => 'var(--soft-warning-bg)', 'color' => 'var(--soft-warning-text)', 'label' => 'Private'],
        ];
        $jc = $jenisConfig[$class->jenis] ?? $jenisConfig['offline'];
    @endphp
    <div class="col-12 col-md-6 col-xl-4">
        <div class="h-100" style="
            background: var(--card-bg);
            border: 1.5px solid {{ $isEnrolled ? 'var(--soft-success-border, #6ee7b7)' : 'var(--card-border)' }};
            border-radius: 18px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: box-shadow .2s, transform .2s;
            {{ $isEnrolled ? 'box-shadow: 0 0 0 3px rgba(16,185,129,.12);' : '' }}
        "
        onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 12px 32px rgba(104,17,126,.13)'"
        onmouseout="this.style.transform='';this.style.boxShadow='{{ $isEnrolled ? '0 0 0 3px rgba(16,185,129,.12)' : '' }}'">

            {{-- CARD HEADER --}}
            <div style="background:linear-gradient(135deg,#260632,#68117e);padding:18px 20px;position:relative;overflow:hidden">
                <div style="position:absolute;right:-20px;top:-20px;width:100px;height:100px;background:rgba(255,255,255,.05);border-radius:50%"></div>
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div style="font-size:10px;opacity:.6;text-transform:uppercase;letter-spacing:.07em;color:white;margin-bottom:4px">
                            {{ $class->mataPelajaran?->kode ?? 'KELAS' }}
                        </div>
                        <div class="fw-bold" style="color:white;font-size:15px;line-height:1.3;max-width:200px">
                            {{ $class->nama_kelas }}
                        </div>
                        <div style="font-size:12px;color:rgba(255,255,255,.65);margin-top:4px">
                            {{ $class->mataPelajaran?->nama ?? '—' }}
                        </div>
                    </div>
                    <div style="background:{{ $jc['bg'] }};color:{{ $jc['color'] }};padding:4px 10px;border-radius:20px;font-size:11px;font-weight:600;white-space:nowrap;flex-shrink:0">
                        <i class="bi {{ $jc['icon'] }} me-1"></i>{{ $jc['label'] }}
                    </div>
                </div>
                @if($isEnrolled)
                <div style="position:absolute;bottom:10px;right:14px">
                    <span style="background:rgba(16,185,129,.25);color:#6ee7b7;font-size:10px;font-weight:700;padding:3px 8px;border-radius:8px;border:1px solid rgba(110,231,183,.3)">
                        <i class="bi bi-check-circle-fill me-1"></i>TERDAFTAR
                    </span>
                </div>
                @endif
            </div>

            {{-- CARD BODY --}}
            <div style="padding:16px 20px;flex:1;display:flex;flex-direction:column;gap:10px">
                <div class="d-flex align-items-center gap-2" style="font-size:13px">
                    <div style="width:30px;height:30px;border-radius:8px;background:var(--soft-primary-bg);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        <i class="bi bi-person-fill" style="color:var(--soft-primary-text);font-size:13px"></i>
                    </div>
                    <div>
                        <div style="font-size:10px;color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em">Guru</div>
                        <div class="fw-semibold" style="font-size:13px">{{ $class->guru?->name ?? 'Belum ada guru' }}</div>
                    </div>
                </div>
                <div class="d-flex gap-3" style="font-size:12px;color:var(--text-muted)">
                    <div class="d-flex align-items-center gap-1">
                        <i class="bi bi-calendar3-week" style="color:var(--primary)"></i>
                        <span>{{ $class->jumlah_pertemuan ?? '—' }} Pertemuan</span>
                    </div>
                    <div class="d-flex align-items-center gap-1">
                        <i class="bi bi-geo-alt" style="color:var(--primary)"></i>
                        <span>{{ $class->cabang?->name ?? 'Pusat' }}</span>
                    </div>
                    @if($class->kapasitas)
                    <div class="d-flex align-items-center gap-1">
                        <i class="bi bi-people" style="color:var(--primary)"></i>
                        <span>Maks {{ $class->kapasitas }}</span>
                    </div>
                    @endif
                </div>

                {{-- Price --}}
                <div class="mt-auto pt-2" style="border-top:1px solid var(--card-border)">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            @if($price > 0)
                            <div style="font-size:10px;color:var(--text-muted);margin-bottom:2px">Biaya Kelas</div>
                            <div class="fw-bold" style="font-size:19px;color:var(--primary)">Rp {{ number_format($price,0,',','.') }}</div>
                            @else
                            <span class="badge bg-success" style="font-size:12px">Gratis</span>
                            @endif
                        </div>

                        @if($isEnrolled)
                        <button class="btn btn-sm fw-semibold" disabled
                            style="border-radius:10px;background:var(--soft-success-bg);color:var(--soft-success-text);border:1px solid var(--soft-success-border, #6ee7b7);padding:7px 14px;cursor:not-allowed">
                            <i class="bi bi-check-circle-fill me-1"></i>Sudah Terdaftar
                        </button>
                        @elseif($price > 0)
                        <a href="{{ route('siswa.billing.index', ['add_course' => $class->mata_pelajaran_id]) }}"
                            class="btn btn-primary btn-sm fw-semibold"
                            style="border-radius:10px;padding:7px 14px">
                            <i class="bi bi-cart-plus me-1"></i>Daftar
                        </a>
                        @else
                        <a href="{{ route('siswa.billing.index', ['add_course' => $class->mata_pelajaran_id]) }}"
                            class="btn btn-success btn-sm fw-semibold"
                            style="border-radius:10px;padding:7px 14px">
                            <i class="bi bi-plus-circle me-1"></i>Daftar Gratis
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

@endsection
