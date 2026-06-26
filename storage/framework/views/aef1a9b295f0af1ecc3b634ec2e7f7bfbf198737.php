<?php $__env->startSection('title','Pesan Aplikasi'); ?>
<?php $__env->startSection('page-title','Pesan Aplikasi'); ?>

<?php $__env->startSection('content'); ?>


<div class="dashboard-card mb-4 fade-up" style="background:linear-gradient(135deg,#0c4a6e 0%,#0284c7 100%);color:white;border:none;overflow:hidden;position:relative">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;right:80px;bottom:-50px;width:120px;height:120px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none"></div>
    <div class="row align-items-center g-3" style="position:relative">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-3">
                <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0"><i class="bi bi-chat-dots"></i></div>
                <div>
                    <h5 class="fw-bold mb-0" style="color:white">Pesan Aplikasi</h5>
                    <span style="font-size:12px;opacity:.8">Chat internal antar admin, guru, dan siswa</span>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-md-end">
            <?php if($allowCreateRoom ?? auth()->user()->hasRole('admin')): ?>
            <button onclick="openRoomModal()" class="btn fw-semibold px-4" style="background:rgba(255,255,255,.2);color:white;border:1px solid rgba(255,255,255,.3);border-radius:10px">
                <i class="bi bi-plus-lg me-2"></i>Room Baru
            </button>
            <?php endif; ?>
        </div>
    </div>
</div>


<div class="row g-3 chat-layout">

    
    <div class="col-md-4 col-lg-3">
        <div class="dashboard-card h-100 p-0 d-flex flex-column" style="overflow:hidden">
            <div class="p-3 border-bottom">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" id="roomSearch" placeholder="Cari room...">
                </div>
            </div>
            <div class="flex-grow-1 overflow-auto" id="roomList" style="padding:8px">
                <div class="text-center py-4 text-muted" style="font-size:13px"><div class="spinner-border spinner-border-sm text-primary mb-2"></div><div>Memuat room...</div></div>
            </div>
        </div>
    </div>

    
    <div class="col-md-8 col-lg-9">
        <div class="dashboard-card h-100 p-0 d-flex flex-column" style="overflow:hidden" id="chatArea">
            
            <div id="chatEmpty" class="flex-grow-1 d-flex align-items-center justify-content-center flex-column text-center">
                <i class="bi bi-chat-square-text" style="font-size:3.5rem;color:#cbd5e1;margin-bottom:16px"></i>
                <div class="fw-semibold" style="font-size:15px;color:var(--text-primary)">Pilih Percakapan</div>
                <div class="text-muted" style="font-size:13px;margin-top:4px">
                    <?php if($allowCreateRoom ?? auth()->user()->hasRole('admin')): ?>
                        Pilih room dari kiri atau buat room baru
                    <?php else: ?>
                        Pilih room dari kiri
                    <?php endif; ?>
                </div>
            </div>

            
            <div id="chatActive" style="display:none;flex:1;overflow:hidden;flex-direction:column">
                <div class="p-3 border-bottom d-flex align-items-center justify-content-between gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:38px;height:38px;border-radius:10px;background:linear-gradient(135deg,#c84ddf,#7c3aed);display:flex;align-items:center;justify-content:center;color:white;font-size:16px;flex-shrink:0"><i class="bi bi-chat-dots"></i></div>
                        <div><div class="fw-bold" id="chatRoomName" style="font-size:14px">–</div><div class="text-muted" style="font-size:11px" id="chatRoomType">–</div></div>
                    </div>
                    <div id="chatOnlineDot" class="d-flex align-items-center gap-1" style="font-size:11px;color:var(--text-muted)">
                        <span style="width:7px;height:7px;border-radius:50%;background:#10b981;display:inline-block"></span>Online
                    </div>
                </div>
                <div class="position-relative flex-grow-1 overflow-hidden">
                    <div class="overflow-auto p-3 h-100" id="chatMessages" style="display:flex;flex-direction:column;gap:10px;background:var(--body-bg)">
                    </div>
                    <button id="scrollBottomBtn" onclick="scrollToBottom()" title="Scroll ke bawah"
                        style="position:absolute;bottom:12px;right:12px;width:34px;height:34px;border-radius:50%;background:#c84ddf;color:white;border:none;box-shadow:0 2px 10px rgba(200,77,223,.45);display:none;align-items:center;justify-content:center;font-size:14px;cursor:pointer;transition:opacity .2s">
                        <i class="bi bi-chevron-down"></i>
                    </button>
                </div>
                <div class="p-3 border-top">
                    <form id="messageForm" class="d-flex gap-2">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" id="activeRoomId">
                        <input type="text" name="pesan" class="form-control" id="messageInput" placeholder="Ketik pesan..." autocomplete="off">
                        <button type="submit" class="btn btn-primary px-3" aria-label="Kirim pesan"><i class="bi bi-send"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



<?php if($allowCreateRoom ?? auth()->user()->hasRole('admin')): ?>
<div class="modal fade" id="roomModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius:20px;border:none">
            <div class="modal-header border-0 p-4" style="background:linear-gradient(135deg,#260632,#461256,#c84ddf);border-radius:20px 20px 0 0">
                <h5 class="modal-title fw-bold text-white"><i class="bi bi-chat-dots me-2"></i>Buat Room Chat</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="roomForm">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label fw-semibold">Nama Room <span class="text-danger">*</span></label><input type="text" name="nama_room" class="form-control" required placeholder="Contoh: Diskusi Guru Fisika"></div>
                    <div class="mb-3"><label class="form-label fw-semibold">Jenis Room</label>
                        <select name="jenis_room" class="form-select">
                            <option value="grup">Grup</option>
                            <option value="personal">Personal (1-on-1)</option>
                            <option value="broadcast">Broadcast</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Peserta</label>
                        <select name="peserta_id[]" class="form-select" multiple style="height:120px">
                            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($u->id); ?>"><?php echo e($u->name); ?> (<?php echo e($u->getRoleNames()->first() ?? 'user'); ?>)</option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <div class="form-text">Ctrl+Click untuk pilih banyak</div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4"><i class="bi bi-plus me-2"></i>Buat Room</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.chat-layout {
    min-height: 500px;
}
@media (min-width: 768px) {
    .chat-layout {
        height: calc(100vh - 260px);
        min-height: 520px;
    }
    .chat-layout > [class*="col-"] {
        height: 100%;
    }
}
@media (max-width: 767.98px) {
    .chat-layout > .col-md-4,
    .chat-layout > .col-md-8 {
        height: 48vh;
        min-height: 280px;
    }
}
#chatArea { min-height: 0; }
#chatActive { min-height: 0; display: none; flex-direction: column; }
#chatActive.active { display: flex !important; }
#chatMessages { flex: 1; min-height: 0; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
const baseUrl = '<?php echo e($messageBaseUrl ?? url('admin/messages')); ?>';
const roomsUrl = '<?php echo e($messageRoomsUrl ?? url('admin/messages/rooms')); ?>';
const createRoomRoute = '<?php echo e($messageCreateRoute ?? route('admin.messages.createRoom')); ?>';
let activeRoom = null, pollInterval = null, roomPollInterval = null;
let roomsData = <?php echo json_encode($rooms, 15, 512) ?>;

function fetchRoomsFromServer() {
    fetch(roomsUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => { if (!r.ok) return; return r.json(); })
        .then(res => {
            if (res && res.rooms) {
                roomsData = res.rooms;
                renderRooms(roomsData);
            }
        }).catch(() => {});
}

function renderRooms(rooms) {
    const el = document.getElementById('roomList');
    const search = (document.getElementById('roomSearch').value || '').toLowerCase();
    const filtered = rooms.filter(r => r.nama_room.toLowerCase().includes(search));
    if (!filtered.length) { el.innerHTML = '<div class="text-center py-4 text-muted" style="font-size:13px">Tidak ada room ditemukan</div>'; return; }
    el.innerHTML = filtered.map(r => {
        const lastMsg = r.pesan && r.pesan[0] ? r.pesan[0].pesan : '';
        const isActive = activeRoom && activeRoom.id == r.id;
        return `<div onclick="openRoom(${r.id},'${r.nama_room.replace(/'/g,"\\'").replace(/"/g,"&quot;")}','${r.jenis_room}')"
            class="p-3 rounded-3 mb-1" style="cursor:pointer;transition:background .15s;${isActive?'background:rgba(200,77,223,.12);border:1px solid rgba(200,77,223,.3);':'border:1px solid transparent'}">
            <div class="d-flex align-items-center gap-2">
                <div style="width:36px;height:36px;border-radius:10px;background:${isActive?'linear-gradient(135deg,#c84ddf,#7c3aed)':'rgba(200,77,223,.1)'};display:flex;align-items:center;justify-content:center;font-size:15px;flex-shrink:0;color:${isActive?'white':'#c84ddf'}">
                    <i class="bi ${r.jenis_room==='broadcast'?'bi-megaphone':r.jenis_room==='personal'?'bi-person':'bi-people'}"></i>
                </div>
                <div style="flex:1;min-width:0">
                    <div class="fw-semibold" style="font-size:13px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">${r.nama_room}</div>
                    <div class="text-muted" style="font-size:11px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">${lastMsg ? lastMsg.substring(0,40) : 'Belum ada pesan'}</div>
                </div>
            </div>
        </div>`;
    }).join('');
}

function openRoom(id, name, type) {
    activeRoom = { id, name, type };
    document.getElementById('chatRoomName').textContent = name;
    document.getElementById('chatRoomType').textContent = { grup: 'Grup', personal: 'Personal', broadcast: 'Broadcast' }[type] || type;
    document.getElementById('activeRoomId').value = id;
    document.getElementById('chatEmpty').classList.add('d-none');
    document.getElementById('chatActive').style.display = 'flex';
    loadMessages(id);
    if (pollInterval) clearInterval(pollInterval);
    pollInterval = setInterval(() => loadMessages(id), 4000);
    renderRooms(roomsData);
}

function scrollToBottom() {
    const el = document.getElementById('chatMessages');
    el.scrollTop = el.scrollHeight;
    document.getElementById('scrollBottomBtn').style.display = 'none';
}

function loadMessages(roomId) {
    fetch(`${baseUrl}/${roomId}/messages`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => { if (!r.ok) throw new Error('HTTP '+r.status); return r.json(); })
        .then(res => {
            const myId = <?php echo e(auth()->id()); ?>;
            const el = document.getElementById('chatMessages');
            if (!res.data || !res.data.length) {
                el.innerHTML = '<div class="text-center text-muted d-flex flex-column align-items-center justify-content-center" style="height:100%;padding:32px 0"><i class="bi bi-chat-square-dots" style="font-size:2.5rem;opacity:.25;margin-bottom:10px"></i><div style="font-size:13px">Mulai percakapan pertama!</div></div>';
                return;
            }
            const wasAtBottom = el.scrollHeight - el.scrollTop - el.clientHeight < 80;
            el.innerHTML = res.data.map(m => {
                const isMine = m.pengirim_id == myId;
                const time = (m.created_at||'').toString().substring(11,16);
                return `<div class="d-flex ${isMine?'justify-content-end':'justify-content-start'}">
                    <div style="max-width:72%">
                        ${!isMine ? `<div style="font-size:11px;font-weight:600;color:#c84ddf;margin-bottom:3px;padding-left:4px">${m.pengirim?.name||'User'}</div>` : ''}
                        <div style="background:${isMine?'linear-gradient(135deg,#c84ddf,#7c3aed)':'var(--card-bg)'};color:${isMine?'white':'var(--text-primary)'};border:${isMine?'none':'1px solid var(--card-border)'};padding:9px 14px;border-radius:${isMine?'18px 4px 18px 18px':'4px 18px 18px 18px'};font-size:13.5px;line-height:1.5;word-break:break-word;box-shadow:${isMine?'0 2px 8px rgba(200,77,223,.25)':'0 1px 4px rgba(0,0,0,.07)'}">${m.pesan||''}</div>
                        <div style="font-size:10px;color:var(--text-muted);margin-top:3px;text-align:${isMine?'right':'left'};padding:0 4px">${time}</div>
                    </div>
                </div>`;
            }).join('');
            if (wasAtBottom) { el.scrollTop = el.scrollHeight; }
            else {
                document.getElementById('scrollBottomBtn').style.display = 'flex';
            }
        })
        .catch(() => {});
}

document.getElementById('messageForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const roomId = document.getElementById('activeRoomId').value;
    const input  = document.getElementById('messageInput');
    if (!input.value.trim() || !roomId) return;
    const fd = new FormData(this);
    input.value = '';
    fetch(`${baseUrl}/${roomId}/send`, { method: 'POST', body: fd, headers: { 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>', 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => { if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
        .then(d => { if (d.success) { loadMessages(roomId); } else { showToast(d.message || 'Gagal mengirim pesan.', 'error'); } })
        .catch(() => showToast('Gagal mengirim pesan. Coba lagi.', 'error'));
});

document.getElementById('roomSearch').addEventListener('input', () => renderRooms(roomsData));

<?php if($allowCreateRoom ?? auth()->user()->hasRole('admin')): ?>
function openRoomModal() {
    document.getElementById('roomForm').reset();
    new bootstrap.Modal(document.getElementById('roomModal')).show();
}

document.getElementById('roomForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const fd = new FormData(this);
    fetch(createRoomRoute, { method: 'POST', body: fd, headers: { 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>', 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => { if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
        .then(d => {
            showToast(d.message, d.success ? 'success' : 'error');
            if (d.success) {
                bootstrap.Modal.getInstance(document.getElementById('roomModal')).hide();
                fetchRoomsFromServer();
            }
        }).catch(() => showToast('Gagal membuat ruang. Coba lagi.', 'error'));
});
<?php endif; ?>

document.addEventListener('DOMContentLoaded', () => {
    renderRooms(roomsData);
    // Auto-refresh room list every 8 seconds to pick up new rooms from other users
    roomPollInterval = setInterval(fetchRoomsFromServer, 8000);
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/runner/workspace/resources/views/admin/messages/index.blade.php ENDPATH**/ ?>