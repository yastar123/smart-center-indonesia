@extends('layouts.app')
@section('title','Detail Kelas — '.$class->nama_kelas)
@section('page-title','Detail Kelas')

@section('content')

{{-- HEADER BANNER --}}
<div class="dashboard-card mb-4 fade-up"
     style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div class="row align-items-center g-3" style="position:relative">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('guru.classes.index') }}"
                   class="btn btn-sm flex-shrink-0"
                   style="background:rgba(255,255,255,.15);color:white;border:1px solid rgba(255,255,255,.25);border-radius:9px;padding:6px 12px">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
                <div>
                    <div style="font-size:11px;opacity:.6;margin-bottom:2px;text-transform:uppercase;letter-spacing:.08em">
                        Detail Kelas
                    </div>
                    <h5 class="fw-bold mb-0" style="color:white">{{ $class->nama_kelas }}</h5>
                    <div style="font-size:12px;opacity:.75">
                        {{ $class->mataPelajaran->nama ?? '–' }} · {{ $class->cabang->name ?? 'Pusat' }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('guru.classes.attendance', $class->id) }}"
               class="btn fw-semibold px-4"
               style="background:rgba(255,255,255,.2);color:white;border:1px solid rgba(255,255,255,.3);border-radius:10px;backdrop-filter:blur(10px)">
                <i class="bi bi-clipboard2-check me-2"></i>Input Absensi
            </a>
        </div>
    </div>
</div>

<div class="row g-4">

    {{-- CLASS INFO --}}
    <div class="col-lg-4">
        <div class="dashboard-card fade-up h-100">
            <h6 class="fw-bold mb-4" style="color:var(--text-primary)">
                <i class="bi bi-info-circle text-primary me-2"></i>Informasi Kelas
            </h6>
            <div class="d-flex flex-column gap-3">
                @foreach([
                    ['icon'=>'bi-diagram-3','label'=>'Nama Kelas','value'=>$class->nama_kelas],
                    ['icon'=>'bi-journal-bookmark','label'=>'Mata Pelajaran','value'=>$class->mataPelajaran->nama ?? '–'],
                    ['icon'=>'bi-building','label'=>'Cabang','value'=>$class->cabang->name ?? 'Pusat'],
                    ['icon'=>'bi-people','label'=>'Kapasitas','value'=>($class->kapasitas ?? '–').' siswa'],
                    ['icon'=>'bi-calendar-check','label'=>'Jumlah Pertemuan','value'=>($class->jumlah_pertemuan ?? '–').' pertemuan'],
                    ['icon'=>'bi-tag','label'=>'Jenis Kelas','value'=>ucfirst($class->jenis ?? '–')],
                ] as $row)
                <div class="d-flex align-items-start gap-3">
                    <div style="width:36px;height:36px;border-radius:10px;background:rgba(200,77,223,.1);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        <i class="bi {{ $row['icon'] }} text-primary" style="font-size:14px"></i>
                    </div>
                    <div>
                        <div class="text-muted" style="font-size:11px">{{ $row['label'] }}</div>
                        <div class="fw-semibold" style="font-size:13.5px;color:var(--text-primary)">{{ $row['value'] }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- STUDENT LIST --}}
    <div class="col-lg-8">
        <div class="dashboard-card fade-up">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h6 class="fw-bold mb-1" style="color:var(--text-primary)">
                        <i class="bi bi-people-fill text-primary me-2"></i>Daftar Siswa
                    </h6>
                    <p class="text-muted mb-0" style="font-size:12px">
                        {{ $class->siswa->count() }} siswa terdaftar di kelas ini
                    </p>
                </div>
                <span class="badge"
                      style="background:var(--soft-primary-bg);color:var(--soft-primary-text);font-size:12px;padding:5px 12px;border-radius:8px">
                    {{ $class->siswa->count() }} siswa
                </span>
            </div>

            @if($class->siswa->isEmpty())
            <div class="text-center py-5">
                <div style="width:64px;height:64px;border-radius:50%;background:var(--input-bg);display:flex;align-items:center;justify-content:center;margin:0 auto 12px">
                    <i class="bi bi-people text-muted" style="font-size:1.8rem;opacity:.4"></i>
                </div>
                <p class="text-muted mb-0" style="font-size:13px">Belum ada siswa terdaftar di kelas ini.</p>
            </div>
            @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="thead-modern">
                        <tr>
                            <th class="small text-muted fw-semibold py-3" style="width:40px">#</th>
                            <th class="small text-muted fw-semibold py-3">SISWA</th>
                            <th class="small text-muted fw-semibold py-3 d-none d-md-table-cell">NIS</th>
                            <th class="small text-muted fw-semibold py-3 d-none d-lg-table-cell">CABANG</th>
                            <th class="small text-muted fw-semibold py-3">STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($class->siswa as $i => $s)
                        @php
                            $statusMap = [
                                'aktif'    => ['bg'=>'var(--soft-success-bg)','color'=>'var(--soft-success-text)','label'=>'Aktif'],
                                'nonaktif' => ['bg'=>'var(--soft-muted-bg)','color'=>'var(--soft-muted-text)','label'=>'Nonaktif'],
                                'lulus'    => ['bg'=>'var(--soft-primary-bg)','color'=>'var(--soft-primary-text)','label'=>'Lulus'],
                            ];
                            $badge = $statusMap[$s->status] ?? $statusMap['nonaktif'];
                            $avatar = 'https://ui-avatars.com/api/?name='.urlencode($s->name).'&background='.($s->gender==='P'?'ec4899':'68117e').'&color=fff&size=60';
                        @endphp
                        <tr>
                            <td class="text-muted small fw-semibold">{{ $i + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ $s->photo ? Storage::url($s->photo) : $avatar }}"
                                         class="rounded-circle flex-shrink-0"
                                         width="36" height="36"
                                         style="object-fit:cover;border:2px solid var(--card-border)"
                                         loading="lazy">
                                    <div>
                                        <div class="fw-semibold" style="font-size:13px">{{ $s->name }}</div>
                                        <div class="text-muted" style="font-size:11px">
                                            {{ $s->gender==='L'?'Laki-laki':'Perempuan' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="d-none d-md-table-cell">
                                <code style="background:var(--input-bg);padding:2px 8px;border-radius:6px;font-size:11.5px;color:var(--primary)">
                                    {{ $s->nis }}
                                </code>
                            </td>
                            <td class="d-none d-lg-table-cell">
                                <span style="font-size:12.5px;color:var(--text-muted)">
                                    {{ $s->branch->name ?? 'Pusat' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge"
                                      style="background:{{ $badge['bg'] }};color:{{ $badge['color'] }};padding:4px 10px;border-radius:7px;font-size:11px;font-weight:600">
                                    <i class="bi bi-circle-fill me-1" style="font-size:7px"></i>{{ $badge['label'] }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>

</div>

@endsection
