@extends('layouts.app')
@section('title','Jadwal Saya')
@section('page-title','Jadwal Saya')

@section('content')

{{-- HEADER --}}
<div class="dashboard-card mb-4 fade-up"
     style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3" style="position:relative">
        <div class="d-flex align-items-center gap-3">
            <div style="width:52px;height:52px;border-radius:16px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:24px;flex-shrink:0">
                <i class="bi bi-calendar3"></i>
            </div>
            <div>
                <div style="font-size:11px;opacity:.6;margin-bottom:3px;text-transform:uppercase;letter-spacing:.08em">Jadwal Mengajar</div>
                <h4 style="font-weight:800;margin-bottom:3px;color:white;letter-spacing:-.02em">Jadwal Saya</h4>
                <p style="opacity:.65;margin:0;font-size:13px">
                    Lihat dan perbarui jadwal pertemuan yang ditugaskan oleh admin
                </p>
            </div>
        </div>
        <div style="font-size:64px;opacity:.08;line-height:1;flex-shrink:0">
            <i class="bi bi-calendar-week-fill"></i>
        </div>
    </div>
</div>

{{-- INFO FLOW --}}
<div class="dashboard-card mb-4 fade-up" style="border-left:4px solid #c84ddf">
    <div class="d-flex align-items-center gap-2 flex-wrap" style="font-size:12px;color:var(--text-muted)">
        <i class="bi bi-info-circle text-primary me-1"></i>
        <span class="fw-semibold text-primary me-2">Alur Jadwal:</span>
        <span class="badge" style="background:var(--soft-primary-bg);color:var(--soft-primary-text);padding:4px 9px;border-radius:7px">① Admin membuat jadwal</span>
        <i class="bi bi-arrow-right" style="font-size:11px"></i>
        <span class="badge" style="background:var(--soft-warning-bg);color:var(--soft-warning-text);padding:4px 9px;border-radius:7px">② Guru dapat memperbarui</span>
        <i class="bi bi-arrow-right" style="font-size:11px"></i>
        <span class="badge" style="background:var(--soft-success-bg);color:var(--soft-success-text);padding:4px 9px;border-radius:7px">③ Siswa melihat jadwal final</span>
    </div>
</div>

{{-- FILTER --}}
<div class="dashboard-card mb-4 fade-up">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-auto">
            <label class="form-label small text-muted mb-1">Bulan</label>
            <select name="bulan" class="form-select form-select-sm" style="border-radius:9px;min-width:110px">
                <option value="">Semua Bulan</option>
                @foreach(range(1,12) as $m)
                <option value="{{ $m }}" {{ request('bulan')==$m?'selected':'' }}>
                    {{ \Carbon\Carbon::create()->month($m)->locale('id')->isoFormat('MMMM') }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-auto">
            <label class="form-label small text-muted mb-1">Tahun</label>
            <select name="tahun" class="form-select form-select-sm" style="border-radius:9px;min-width:90px">
                <option value="">Semua</option>
                @foreach([now()->year, now()->year-1] as $y)
                <option value="{{ $y }}" {{ request('tahun')==$y?'selected':'' }}>{{ $y }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-auto">
            <label class="form-label small text-muted mb-1">Status</label>
            <select name="status" class="form-select form-select-sm" style="border-radius:9px;min-width:120px">
                <option value="">Semua Status</option>
                <option value="dijadwalkan" {{ request('status')=='dijadwalkan'?'selected':'' }}>Dijadwalkan</option>
                <option value="berlangsung" {{ request('status')=='berlangsung'?'selected':'' }}>Berlangsung</option>
                <option value="selesai"     {{ request('status')=='selesai'?'selected':'' }}>Selesai</option>
            </select>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary btn-sm" style="border-radius:9px">
                <i class="bi bi-funnel me-1"></i>Filter
            </button>
            <a href="{{ route('guru.schedules.index') }}" class="btn btn-sm ms-1" style="border-radius:9px;background:var(--input-bg);border:1px solid var(--card-border)">Reset</a>
        </div>
    </form>
</div>

{{-- TABLE --}}
<div class="dashboard-card fade-up">
    @if($schedules->isEmpty())
    <div class="text-center py-5">
        <div style="width:80px;height:80px;border-radius:50%;background:var(--input-bg);display:flex;align-items:center;justify-content:center;margin:0 auto 16px">
            <i class="bi bi-calendar-x text-muted" style="font-size:2rem;opacity:.5"></i>
        </div>
        <h6 class="fw-semibold mb-2" style="color:var(--text-primary)">Belum Ada Jadwal</h6>
        <p class="text-muted mb-0" style="font-size:13px">Admin belum membuat jadwal untuk Anda.</p>
    </div>
    @else
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size:13px">
            <thead class="thead-modern">
                <tr>
                    <th>#</th>
                    <th>Mata Pelajaran / Kelas</th>
                    <th>Pertemuan</th>
                    <th>Tanggal & Waktu</th>
                    <th>Metode</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($schedules as $i => $s)
                @php
                    $isNew = $s->created_at->gte(now()->subHours(48));
                    $tgl   = $s->tanggal instanceof \Carbon\Carbon ? $s->tanggal : \Carbon\Carbon::parse($s->tanggal);
                    $statusMap = [
                        'dijadwalkan' => ['bg'=>'var(--soft-info-bg)','color'=>'#0284c7','label'=>'Dijadwalkan','icon'=>'bi-clock'],
                        'berlangsung' => ['bg'=>'var(--soft-success-bg)','color'=>'#10b981','label'=>'Berlangsung','icon'=>'bi-broadcast'],
                        'selesai'     => ['bg'=>'var(--soft-muted-bg)','color'=>'var(--text-muted)','label'=>'Selesai','icon'=>'bi-check2'],
                        'dibatalkan'  => ['bg'=>'var(--soft-danger-bg)','color'=>'#ef4444','label'=>'Dibatalkan','icon'=>'bi-x-circle'],
                    ];
                    $sm = $statusMap[$s->status] ?? ['bg'=>'var(--input-bg)','color'=>'#999','label'=>ucfirst($s->status),'icon'=>'bi-circle'];
                    $canEdit = !app(\App\Services\ScheduleLockService::class)->isScheduleLocked($s);
                @endphp
                <tr>
                    <td class="text-muted" style="font-size:12px">{{ ($schedules->currentPage()-1)*$schedules->perPage() + $i + 1 }}</td>
                    <td>
                        <div class="fw-semibold" style="font-size:13px">
                            {{ $s->kelas->mataPelajaran->nama ?? '—' }}
                            @if($isNew)
                            <span class="badge ms-1" style="background:var(--soft-success-bg);color:var(--soft-success-text);font-size:9px;border-radius:5px;padding:2px 6px">Baru</span>
                            @endif
                        </div>
                        <div class="text-muted" style="font-size:11px">{{ $s->kelas->nama_kelas ?? '—' }}</div>
                    </td>
                    <td>
                        @if($s->pertemuan_ke)
                        <span style="background:var(--soft-primary-bg);color:var(--soft-primary-text);padding:2px 9px;border-radius:6px;font-size:12px;font-weight:700">
                            Pertemuan ke-{{ $s->pertemuan_ke }}
                        </span>
                        @else
                        <span class="text-muted">—</span>
                        @endif
                        @if($s->topik)
                        <div class="text-muted" style="font-size:11px;margin-top:2px">{{ Str::limit($s->topik, 40) }}</div>
                        @endif
                    </td>
                    <td>
                        <div class="fw-semibold" style="font-size:13px">{{ $tgl->locale('id')->isoFormat('ddd, D MMM Y') }}</div>
                        <div class="text-muted" style="font-size:11px">
                            <i class="bi bi-clock me-1"></i>
                            {{ \Carbon\Carbon::parse($s->jam_mulai)->format('H:i') }} – {{ \Carbon\Carbon::parse($s->jam_selesai)->format('H:i') }}
                        </div>
                    </td>
                    <td>
                        @if($s->jenis === 'online')
                        <span style="background:rgba(2,132,199,.1);color:#0284c7;padding:3px 9px;border-radius:6px;font-size:11.5px;font-weight:600">
                            <i class="bi bi-camera-video me-1"></i>Online
                        </span>
                        @elseif($s->jenis === 'private')
                        <span style="background:rgba(200,77,223,.1);color:#c84ddf;padding:3px 9px;border-radius:6px;font-size:11.5px;font-weight:600">
                            <i class="bi bi-person-video me-1"></i>Private
                        </span>
                        @else
                        <span style="background:var(--soft-muted-bg);color:var(--text-muted);padding:3px 9px;border-radius:6px;font-size:11.5px;font-weight:600">
                            <i class="bi bi-building me-1"></i>{{ $s->ruangan ?: 'Offline' }}
                        </span>
                        @endif
                    </td>
                    <td>
                        <span style="background:{{ $sm['bg'] }};color:{{ $sm['color'] }};padding:3px 10px;border-radius:6px;font-size:11.5px;font-weight:600">
                            <i class="bi {{ $sm['icon'] }} me-1"></i>{{ $sm['label'] }}
                        </span>
                    </td>
                    <td>
                        @if($canEdit)
                        <button class="btn btn-sm btn-outline-primary" style="border-radius:8px;font-size:12px"
                                onclick="openEdit({{ $s->id }})">
                            <i class="bi bi-pencil me-1"></i>Edit
                        </button>
                        @else
                        <span class="text-muted" style="font-size:12px"><i class="bi bi-lock me-1"></i>Terkunci</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($schedules->hasPages())
    <div class="mt-3">{{ $schedules->links() }}</div>
    @endif
    @endif
</div>

{{-- EDIT MODAL --}}
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;border:none">
            <div class="modal-header border-0 pb-0" style="background:linear-gradient(135deg,#260632,#461256);border-radius:16px 16px 0 0;padding:20px 24px 16px">
                <h6 class="modal-title fw-bold" style="color:white;font-size:15px">
                    <i class="bi bi-pencil-square me-2"></i>Perbarui Jadwal
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:20px 24px">
                <div id="editFormAlert" class="alert d-none mb-3" style="border-radius:10px;font-size:13px"></div>
                <form id="editForm">
                    <input type="hidden" id="editScheduleId">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Tanggal</label>
                            <input type="date" id="editTanggal" name="tanggal" class="form-control" style="border-radius:10px">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Jam Mulai</label>
                            <input type="time" id="editJamMulai" name="jam_mulai" class="form-control" style="border-radius:10px">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Jam Selesai</label>
                            <input type="time" id="editJamSelesai" name="jam_selesai" class="form-control" style="border-radius:10px">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Metode</label>
                            <select id="editJenis" name="jenis" class="form-select" style="border-radius:10px">
                                <option value="offline">Offline</option>
                                <option value="online">Online</option>
                                <option value="private">Private</option>
                            </select>
                        </div>
                        <div class="col-12" id="ruanganField">
                            <label class="form-label small fw-semibold">Ruangan</label>
                            <input type="text" id="editRuangan" name="ruangan" class="form-control" style="border-radius:10px" placeholder="Contoh: Kelas A3">
                        </div>
                        <div class="col-12" id="linkField" style="display:none">
                            <label class="form-label small fw-semibold">Link Meeting</label>
                            <input type="text" id="editLinkMeeting" name="link_meeting" class="form-control" style="border-radius:10px" placeholder="https://zoom.us/...">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Topik / Materi</label>
                            <input type="text" id="editTopik" name="topik" class="form-control" style="border-radius:10px" placeholder="Opsional">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Catatan</label>
                            <textarea id="editCatatan" name="catatan" class="form-control" rows="2" style="border-radius:10px" placeholder="Opsional"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 pt-0" style="padding:12px 24px 20px">
                <button type="button" class="btn btn-sm" data-bs-dismiss="modal"
                        style="border-radius:9px;background:var(--input-bg);border:1px solid var(--card-border)">Batal</button>
                <button type="button" id="saveEditBtn" class="btn btn-primary btn-sm" style="border-radius:9px">
                    <i class="bi bi-save me-1"></i>Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openEdit(id) {
    const modal = new bootstrap.Modal(document.getElementById('editModal'));
    document.getElementById('editScheduleId').value = id;
    document.getElementById('editFormAlert').className = 'alert d-none mb-3';

    fetch('/guru/schedules/' + id, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => { if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
        .then(res => {
            if (!res.success) { showToast(res.message || 'Gagal memuat data', 'error'); return; }
            const d = res.data;
            document.getElementById('editTanggal').value    = d.tanggal ? d.tanggal.substring(0,10) : '';
            document.getElementById('editJamMulai').value   = d.jam_mulai ? d.jam_mulai.substring(0,5) : '';
            document.getElementById('editJamSelesai').value = d.jam_selesai ? d.jam_selesai.substring(0,5) : '';
            document.getElementById('editJenis').value      = d.jenis || 'offline';
            document.getElementById('editRuangan').value    = d.ruangan || '';
            document.getElementById('editLinkMeeting').value= d.link_meeting || '';
            document.getElementById('editTopik').value      = d.topik || '';
            document.getElementById('editCatatan').value    = d.catatan || '';
            toggleLocationFields(d.jenis);
            modal.show();
        })
        .catch(() => showToast('Gagal memuat data jadwal', 'error'));
}

function toggleLocationFields(jenis) {
    const isOnline = jenis === 'online';
    document.getElementById('ruanganField').style.display = isOnline ? 'none' : '';
    document.getElementById('linkField').style.display    = isOnline ? '' : 'none';
}

document.getElementById('editJenis').addEventListener('change', function() {
    toggleLocationFields(this.value);
});

document.getElementById('saveEditBtn').addEventListener('click', function() {
    const id  = document.getElementById('editScheduleId').value;
    const btn = this;
    const alertEl = document.getElementById('editFormAlert');

    const payload = {
        tanggal:      document.getElementById('editTanggal').value,
        jam_mulai:    document.getElementById('editJamMulai').value,
        jam_selesai:  document.getElementById('editJamSelesai').value,
        jenis:        document.getElementById('editJenis').value,
        ruangan:      document.getElementById('editRuangan').value,
        link_meeting: document.getElementById('editLinkMeeting').value,
        topik:        document.getElementById('editTopik').value,
        catatan:      document.getElementById('editCatatan').value,
        _method:      'PUT',
    };

    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Menyimpan...';

    fetch('/guru/schedules/' + id, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify(payload),
    }).then(r => { if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
    .then(d => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-save me-1"></i>Simpan Perubahan';
        if (d.success) {
            bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
            showToast(d.message || 'Jadwal berhasil diperbarui!', 'success');
            setTimeout(() => location.reload(), 800);
        } else {
            alertEl.className = 'alert alert-danger mb-3';
            alertEl.textContent = d.message || 'Gagal menyimpan';
        }
    }).catch(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-save me-1"></i>Simpan Perubahan';
        showToast('Terjadi kesalahan. Coba lagi.', 'error');
    });
});
</script>
@endpush

@endsection
