@extends('layouts.app')
@section('title','Absensi Kelas')
@section('page-title','Absensi Kelas')

@section('content')
<div class="dashboard-card mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold mb-0">Absensi — {{ $class->nama_kelas }}</h6>
        <div>
            <a href="{{ route('guru.classes.show', $class->id) }}" class="btn btn-sm btn-outline-secondary">Kembali</a>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label small">Pilih Pertemuan (Jadwal)</label>
        <select id="jadwalSelect" class="form-select">
            <option value="">Pilih pertemuan...</option>
            @foreach($class->jadwal as $j)
                <option value="{{ $j->id }}">{{ $j->tanggal->format('Y-m-d') }} · {{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }} — {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label small">Semua Pertemuan</label>
        <div class="row" id="jadwalList">
            @foreach($class->jadwal as $j)
            <div class="col-12 mb-3">
                <div class="card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-semibold">{{ $j->tanggal->format('Y-m-d') }} · {{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }} — {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}</div>
                            @if(!empty($j->keterangan))<div class="small text-muted">{{ $j->keterangan }}</div>@endif
                        </div>
                        <div>
                            <button class="btn btn-sm btn-outline-primary" onclick="toggleAttendance({{ $j->id }})">Lihat Absensi</button>
                        </div>
                    </div>
                    <div id="attendance-area-{{ $j->id }}" class="p-3"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleAttendance(id){
    const area = document.getElementById(`attendance-area-${id}`);
    if (!area) return;
    // toggle: if already loaded, clear; otherwise load
    if (area.innerHTML.trim() !== '') { area.innerHTML = ''; return; }
    loadAttendance(id, area);
}

function loadAttendance(id, areaEl){
    const area = areaEl || document.getElementById('attendance-area-'+id);
    if (!area) return;
    area.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary"></div></div>';
    fetch(`/guru/attendance/${id}/students`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json()).then(res => {
            if (!res.success) { area.innerHTML = '<div class="text-muted">Gagal memuat siswa.</div>'; return; }
            const students = res.students;
            const existing = res.existing || {};
            let html = `<form class="absForm"><input type="hidden" name="jadwal_id" value="${id}">`;
            html += '<table class="table"><thead><tr><th>Nama</th><th class="text-center">Hadir</th><th class="text-center">Izin</th><th class="text-center">Sakit</th><th class="text-center">Alpa</th></tr></thead><tbody>';
            students.forEach(s => {
                const cur = existing[s.id] || '';
                html += `<tr><td>${s.name}</td>`;
                ['hadir','izin','sakit','alpa'].forEach(st => {
                    html += `<td class="text-center"><input type="radio" name="status_${s.id}" value="${st}" ${cur===st?'checked':''}></td>`;
                });
                html += `</tr>`;
            });
            html += `</tbody></table>`;
            html += `<div class="d-flex justify-content-end"><button class="btn btn-primary" type="submit">Simpan Absensi</button></div></form>`;
            area.innerHTML = html;
            area.querySelector('.absForm').addEventListener('submit', function(e){
                e.preventDefault();
                const abs = [];
                students.forEach(s => {
                    const v = area.querySelector(`input[name="status_${s.id}"]:checked`);
                    if (v) abs.push({ siswa_id: s.id, status: v.value });
                });
                fetch(`{{ route('guru.attendance.store') }}`, { method: 'POST', headers: {'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Content-Type': 'application/json','X-Requested-With':'XMLHttpRequest'}, body: JSON.stringify({ jadwal_id: id, absensi: abs }) })
                    .then(r=>r.json()).then(d=>{ if(d.success) { showToast(d.message||'Tersimpan','success'); } else showToast('Gagal','error'); });
            });
        }).catch(()=> area.innerHTML = '<div class="text-muted">Gagal memuat siswa.</div>');
}

document.getElementById('jadwalSelect').addEventListener('change', function() {
    const id = this.value;
    if (!id) return;
    // scroll to card and open
    const card = document.getElementById('attendance-area-'+id);
    if (card) {
        card.scrollIntoView({ behavior: 'smooth', block: 'center' });
        // load if empty
        if (card.innerHTML.trim() === '') loadAttendance(id, card);
    }
});
</script>
@endpush

@endsection
