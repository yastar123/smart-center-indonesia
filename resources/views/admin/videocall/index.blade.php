@extends('layouts.app')
@section('title','Video Call')
@section('page-title','Video Call Online')

@section('content')
<div class="fade-up">

{{-- HEADER --}}
<div class="dashboard-card mb-4 fade-up" style="background:linear-gradient(135deg,#134e4a 0%,#0d9488 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
    <div class="row align-items-center g-3" style="position:relative">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-3">
                <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0"><i class="bi bi-camera-video"></i></div>
                <div>
                    <h5 class="fw-bold mb-0" style="color:white">Video Call Online</h5>
                    <span style="font-size:12px;opacity:.8">Kelas virtual dan konsultasi online berbasis Jitsi Meet</span>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-md-end">
            <button onclick="createRoom()" class="btn fw-semibold px-4" style="background:rgba(255,255,255,.2);color:white;border:1px solid rgba(255,255,255,.3);border-radius:10px">
                <i class="bi bi-plus-lg me-2"></i>Buat Room Baru
            </button>
        </div>
    </div>
</div>

{{-- TIPS --}}
<div class="row g-3 mb-4">
    @foreach([
        ['icon'=>'bi-camera-video','color'=>'#0d9488','bg'=>'rgba(13,148,136,.1)','title'=>'Kelas Virtual','desc'=>'Buat room kelas virtual untuk pembelajaran online real-time'],
        ['icon'=>'bi-person-check','color'=>'#c84ddf','bg'=>'rgba(200,77,223,.1)','title'=>'Konsultasi 1-on-1','desc'=>'Sesi konsultasi privat antara guru dan siswa'],
        ['icon'=>'bi-people','color'=>'#68117e','bg'=>'rgba(104,17,126,.1)','title'=>'Meeting Guru','desc'=>'Rapat virtual tim pengajar lintas cabang'],
        ['icon'=>'bi-link-45deg','color'=>'#f6af23','bg'=>'rgba(246,175,35,.1)','title'=>'Share Link','desc'=>'Bagikan link meeting dengan mudah via tombol copy'],
    ] as $t)
    <div class="col-6 col-md-3">
        <div class="dashboard-card h-100" style="border-top:3px solid {{ $t['color'] }}">
            <div style="width:40px;height:40px;border-radius:10px;background:{{ $t['bg'] }};display:flex;align-items:center;justify-content:center;margin-bottom:12px"><i class="bi {{ $t['icon'] }}" style="color:{{ $t['color'] }};font-size:18px"></i></div>
            <div class="fw-semibold mb-1" style="font-size:13px">{{ $t['title'] }}</div>
            <div class="text-muted" style="font-size:12px">{{ $t['desc'] }}</div>
        </div>
    </div>
    @endforeach
</div>

{{-- ACTIVE MEETING --}}
<div class="row g-3">
    <div class="col-md-5">
        <div class="dashboard-card h-100">
            <h6 class="fw-bold mb-3"><i class="bi bi-plus-circle me-2 text-primary"></i>Buat Room Meeting</h6>
            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Room</label>
                <input type="text" id="roomNameInput" class="form-control" placeholder="Contoh: kelas-matematika-12a">
                <div class="form-text">Nama room hanya boleh huruf, angka, dan tanda hubung</div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Tipe Room</label>
                <select id="roomType" class="form-select">
                    <option value="kelas">Kelas Virtual</option>
                    <option value="konsultasi">Konsultasi</option>
                    <option value="meeting">Meeting Guru</option>
                </select>
            </div>
            <button onclick="startMeeting()" class="btn btn-primary w-100 mb-3">
                <i class="bi bi-camera-video-fill me-2"></i>Mulai Video Call
            </button>
            <hr class="my-3">
            <h6 class="fw-semibold mb-3">Bergabung ke Room</h6>
            <div class="input-group">
                <input type="text" id="joinRoomInput" class="form-control" placeholder="Masukkan nama room...">
                <button class="btn btn-outline-primary" onclick="joinRoom()"><i class="bi bi-box-arrow-in-right me-1"></i>Join</button>
            </div>
            <div id="roomLinkBox" class="mt-3 p-3 rounded-3 d-none" style="background:rgba(13,148,136,.08);border:1px solid rgba(13,148,136,.3)">
                <div class="fw-semibold mb-2" style="font-size:13px;color:#0d9488"><i class="bi bi-link me-1"></i>Link Meeting:</div>
                <div class="d-flex gap-2 align-items-center">
                    <code id="roomLink" style="font-size:12px;word-break:break-all;flex:1"></code>
                    <button onclick="copyLink()" class="btn btn-sm btn-outline-teal flex-shrink-0" style="border-color:#0d9488;color:#0d9488"><i class="bi bi-clipboard"></i></button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="dashboard-card h-100 p-0 overflow-hidden" id="meetingFrame" style="min-height:400px;display:flex;align-items:center;justify-content:center">
            <div class="text-center p-4">
                <i class="bi bi-camera-video" style="font-size:3.5rem;color:#cbd5e1;display:block;margin-bottom:16px"></i>
                <div class="fw-semibold" style="font-size:15px;color:var(--text-primary)">Belum ada meeting aktif</div>
                <div class="text-muted" style="font-size:13px;margin-top:6px">Buat room baru atau bergabung ke room yang sudah ada</div>
            </div>
        </div>
    </div>
</div>

</div>
@endsection

@push('scripts')
<script>
const jitsiDomain = 'meet.jit.si';

function generateRoomName() {
    return 'smartcenter-' + Math.random().toString(36).substring(2, 8);
}

function startMeeting() {
    let name = document.getElementById('roomNameInput').value.trim();
    if (!name) { name = generateRoomName(); document.getElementById('roomNameInput').value = name; }
    name = name.replace(/[^a-zA-Z0-9\-_]/g, '-').toLowerCase();
    showMeeting(name);
}

function joinRoom() {
    const name = document.getElementById('joinRoomInput').value.trim().replace(/[^a-zA-Z0-9\-_]/g, '-').toLowerCase();
    if (!name) { showToast('Masukkan nama room terlebih dahulu', 'warning'); return; }
    showMeeting(name);
}

function showMeeting(roomName) {
    const fullLink = `https://${jitsiDomain}/${roomName}`;
    document.getElementById('roomLinkBox').classList.remove('d-none');
    document.getElementById('roomLink').textContent = fullLink;

    const isMobile = window.innerWidth < 768;
    const frameH   = isMobile ? 'calc(100vh - 200px)' : '620px';
    const frame    = document.getElementById('meetingFrame');
    frame.innerHTML = `<iframe src="${fullLink}"
        allow="camera; microphone; display-capture; autoplay"
        style="width:100%;height:${frameH};min-height:400px;border:none;display:block"
        allowfullscreen loading="lazy"></iframe>`;
    frame.style.minHeight = isMobile ? '400px' : '620px';
    frame.style.display   = 'block';
    frame.style.padding   = '0';
}

function createRoom() {
    const name = generateRoomName();
    document.getElementById('roomNameInput').value = name;
    document.getElementById('roomNameInput').focus();
    showToast('Nama room digenerate otomatis. Klik "Mulai Video Call" untuk memulai.', 'info');
}

function copyLink() {
    const link = document.getElementById('roomLink').textContent;
    navigator.clipboard.writeText(link).then(() => showToast('Link berhasil disalin!', 'success'));
}
</script>
@endpush
