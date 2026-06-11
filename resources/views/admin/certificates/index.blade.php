@extends('layouts.app')

@section('title', 'Sertifikat')
@section('page-title', 'Sertifikat')

@section('content')
<div>

    {{-- HEADER BANNER --}}
    <div class="dashboard-card mb-4 fade-up" style="background:linear-gradient(135deg,#260632 0%,#461256 50%,#c84ddf 100%);color:white;border:none;overflow:hidden;position:relative">
        <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
        <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
        <div class="row align-items-center g-3" style="position:relative">
            <div class="col-md-8">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0">
                        <i class="bi bi-award-fill"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0" style="color:white">Manajemen Sertifikat</h5>
                        <span style="font-size:12px;opacity:.8">Upload dan kelola sertifikat berdasarkan mata pelajaran siswa</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- STAT CARDS --}}
    <div class="row g-3 mb-4">
        @php
            $statCards = [
                ['label'=>'Total Sertifikat','value'=>$stats['total'],     'icon'=>'bi-award-fill',     'topColor'=>'#f6af23','textColor'=>'text-warning','iconBg'=>'bg-warning-soft'],
                ['label'=>'Kompetensi',      'value'=>$stats['kompetensi'],'icon'=>'bi-patch-check-fill','topColor'=>'#c84ddf','textColor'=>'text-primary','iconBg'=>'bg-primary-soft'],
                ['label'=>'Kelulusan',       'value'=>$stats['kelulusan'], 'icon'=>'bi-mortarboard-fill','topColor'=>'#10b981','textColor'=>'text-success','iconBg'=>'bg-success-soft'],
                ['label'=>'Prestasi',        'value'=>$stats['prestasi'],  'icon'=>'bi-trophy-fill',    'topColor'=>'#ef4444','textColor'=>'text-danger', 'iconBg'=>'bg-danger-soft'],
            ];
        @endphp
        @foreach($statCards as $i => $sc)
        <div class="col-6 col-lg-3 fade-up" style="animation-delay:{{ $i * 0.05 }}s">
            <div class="stat-card" style="border-top:3px solid {{ $sc['topColor'] }}">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-title">{{ $sc['label'] }}</div>
                        <div class="stat-value {{ $sc['textColor'] }} count-up" data-target="{{ $sc['value'] }}">{{ $sc['value'] }}</div>
                    </div>
                    <div class="stat-icon {{ $sc['iconBg'] }}" style="color:white">
                        <i class="bi {{ $sc['icon'] }}"></i>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    {{-- INSTRUCTIONS --}}
    <div class="dashboard-card mb-4 fade-up" style="border-left:4px solid #c84ddf">
        <div class="d-flex align-items-start gap-3">
            <div style="width:40px;height:40px;border-radius:10px;background:rgba(200,77,223,.1);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <i class="bi bi-info-circle text-primary" style="font-size:18px"></i>
            </div>
            <div>
                <h6 class="fw-bold mb-1" style="font-size:14px">Cara Upload Sertifikat</h6>
                <ol style="margin-bottom:0;padding-left:1.2rem;font-size:12px;color:var(--text-muted);line-height:1.6">
                    <li>Klik nama siswa di bawah untuk melihat mata pelajaran</li>
                    <li>Pilih mata pelajaran yang akan diberikan sertifikat</li>
                    <li>Upload file sertifikat (PDF, JPG, PNG) untuk mata pelajaran tersebut</li>
                </ol>
            </div>
        </div>
    </div>
    
    {{-- STUDENTS LIST --}}
    <div class="dashboard-card fade-up">
        <h6 class="mb-3 fw-semibold"><i class="bi bi-people-fill me-2 text-primary"></i>Daftar Siswa (Klik untuk Lihat Mata Pelajaran)</h6>
        @if($students->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-people" style="font-size:3rem;opacity:.3;color:var(--text-muted);display:block;margin-bottom:12px"></i>
            <div class="fw-semibold">Belum ada siswa</div>
        </div>
        @else
        <div class="row g-2">
            @foreach($students as $st)
            <div class="col-6 col-md-4 col-lg-3">
                <button class="btn btn-outline-secondary w-100 text-start p-3" style="border-radius:12px" onclick="openStudentCourses({{ $st->id }}, '{{ addslashes($st->user?->name ?? 'Siswa #'.$st->id) }}')">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width:36px;height:36px;border-radius:10px;background:rgba(200,77,223,.1);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                            <i class="bi bi-person-fill" style="color:#c84ddf"></i>
                        </div>
                        <div class="flex-grow-1 min-width-0">
                            <div class="fw-semibold" style="font-size:.9rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $st->user?->name ?? 'Siswa #'.$st->id }}</div>
                            <div class="small text-muted">ID: {{ $st->id }}</div>
                        </div>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </div>
                </button>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

{{-- MODAL: STUDENT COURSES --}}
<div class="modal fade" id="studentCoursesModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0" style="border-radius:20px;box-shadow:0 20px 60px rgba(0,0,0,.15);">
            <div class="modal-header border-0 p-4" style="background:linear-gradient(135deg,#260632,#461256,#c84ddf);border-radius:20px 20px 0 0">
                <div>
                    <h5 class="modal-title fw-bold mb-0 text-white" id="studentCoursesTitle"><i class="bi bi-book me-2"></i>Mata Pelajaran</h5>
                    <div style="font-size:12px;opacity:.75;color:white;margin-top:3px" id="studentCoursesSubtitle">Klik mata pelajaran untuk upload sertifikat</div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" id="studentCoursesBody">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary mb-2"></div>
                    <div class="text-muted">Memuat...</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL: UPLOAD FORM --}}
<div class="modal fade" id="uploadCertModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0" style="border-radius:20px;box-shadow:0 20px 60px rgba(0,0,0,.15);">
            <div class="modal-header border-0 p-4" style="background:linear-gradient(135deg,#10b981,#059669);border-radius:20px 20px 0 0">
                <div>
                    <h5 class="modal-title fw-bold mb-0 text-white"><i class="bi bi-upload me-2"></i>Upload Sertifikat</h5>
                    <div style="font-size:12px;opacity:.75;color:white;margin-top:3px" id="uploadCertTitle">-</div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="uploadCertForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="siswa_id" id="upload_siswa_id">
                <input type="hidden" name="mata_pelajaran_id" id="upload_mata_pelajaran_id">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Judul Sertifikat <span class="text-danger">*</span></label>
                        <input type="text" name="judul" class="form-control" required placeholder="cth: Sertifikat Kompetensi Biologi">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jenis <span class="text-danger">*</span></label>
                        <select name="jenis" class="form-select" required>
                            <option value="">-- Pilih Jenis --</option>
                            <option value="kompetensi">Kompetensi</option>
                            <option value="kelulusan">Kelulusan</option>
                            <option value="prestasi">Prestasi</option>
                            <option value="partisipasi">Partisipasi</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal Terbit <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_terbit" class="form-control" required value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Deskripsi <span class="text-muted">(opsional)</span></label>
                        <textarea name="deskripsi" class="form-control" rows="2" placeholder="Keterangan tambahan..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">File Sertifikat <span class="text-danger">*</span></label>
                        <input type="file" name="file_sertifikat" class="form-control" required accept=".pdf,.jpg,.jpeg,.png">
                        <div class="form-text">Format: PDF, JPG, PNG. Maks 10 MB</div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-2">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success px-4" id="uploadCertBtn"><i class="bi bi-upload me-2"></i>Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openStudentCourses(studentId, studentName) {
    const modal = new bootstrap.Modal(document.getElementById('studentCoursesModal'));
    document.getElementById('studentCoursesTitle').innerText = studentName;
    const body = document.getElementById('studentCoursesBody');
    body.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary mb-2"></div><div class="text-muted">Memuat mata pelajaran...</div></div>';
    modal.show();

    fetch(`/admin/students/${studentId}/courses`)
        .then(r => { if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
        .then(res => {
            if (!res.success) { body.innerHTML = '<div class="text-danger text-center py-3">Gagal memuat data.</div>'; return; }
            const courses = res.data;
            if (!courses.length) { 
                body.innerHTML = '<div class="text-center py-4"><i class="bi bi-book" style="font-size:2.5rem;opacity:.3;color:var(--text-muted);display:block;margin-bottom:10px"></i><div class="text-muted">Siswa belum terdaftar pada mata pelajaran apa pun.</div></div>'; 
                return; 
            }

            let html = '<div class="list-group">';
            courses.forEach(c => {
                html += `<div class="list-group-item d-flex justify-content-between align-items-center" style="border-radius:10px;margin-bottom:8px;border:1px solid var(--card-border)">
                    <div>
                        <div class="fw-semibold">${escapeHtml(c.nama)}</div>
                        <div class="small text-muted">ID: ${c.id}</div>
                    </div>
                    <button class="btn btn-sm btn-primary" onclick="openUploadForm(${studentId}, ${c.id}, '${escapeHtml(c.nama)}', '${escapeHtml(studentName)}')">
                        <i class="bi bi-upload me-1"></i>Upload
                    </button>
                </div>`;
            });
            html += '</div>';
            body.innerHTML = html;
        }).catch(() => { body.innerHTML = '<div class="text-danger text-center py-3">Gagal menghubungi server.</div>'; });
}

function openUploadForm(studentId, courseId, courseName, studentName) {
    bootstrap.Modal.getInstance(document.getElementById('studentCoursesModal'))?.hide();
    
    document.getElementById('uploadCertTitle').innerText = studentName + ' — ' + courseName;
    document.getElementById('upload_siswa_id').value = studentId;
    document.getElementById('upload_mata_pelajaran_id').value = courseId;
    document.getElementById('uploadCertForm').reset();
    document.getElementById('upload_siswa_id').value = studentId;
    document.getElementById('upload_mata_pelajaran_id').value = courseId;
    
    new bootstrap.Modal(document.getElementById('uploadCertModal')).show();
}

document.getElementById('uploadCertForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('uploadCertBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mengupload...';

    const fd = new FormData(this);
    fd.append('cabang_id', '{{ auth()->user()->branch_id ?? '' }}');
    fd.append('diterbitkan_oleh', 'Admin Smart Center');

    fetch('/admin/certificates', { method: 'POST', body: fd, headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
        .then(r => { if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
        .then(res => {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-upload me-2"></i>Upload';
            showToast(res.message, res.success ? 'success' : 'error');
            if (res.success) {
                bootstrap.Modal.getInstance(document.getElementById('uploadCertModal'))?.hide();
            }
        }).catch(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-upload me-2"></i>Upload';
            showToast('Gagal mengupload sertifikat', 'error');
        });
});

function escapeHtml(s) { 
    if (!s) return ''; 
    return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#39;'); 
}
</script>
@endpush
