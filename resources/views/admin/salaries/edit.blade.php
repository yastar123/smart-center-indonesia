@extends('layouts.app')
@section('title','Edit Data Gaji')
@section('page-title','Edit Data Gaji')

@section('content')
<div class="fade-up">

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.salaries.index') }}">Gaji Guru</a></li>
        <li class="breadcrumb-item active">Edit Data Gaji</li>
    </ol>
</nav>

<div class="dashboard-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-bold mb-1"><i class="bi bi-pencil-square me-2 text-primary"></i>Edit Data Gaji</h5>
            <div class="text-muted small">Perbarui data pembayaran gaji untuk: <strong>{{ $salary->guru?->name ?? '—' }}</strong> • Periode <strong>{{ $salary->periode }}</strong></div>
        </div>
        <a href="{{ route('admin.salaries.index') }}" class="btn btn-outline-secondary" style="border-radius:10px">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>

    <form action="{{ route('admin.salaries.update', $salary) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @if($errors->any())
        <div class="alert alert-danger mb-3">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
        @endif

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">Guru <span class="text-danger">*</span></label>
                <select id="guru_id" name="guru_id" class="form-select" required>
                    <option value="">Pilih Guru</option>
                    @foreach($teachers as $t)
                        <option value="{{ $t->id }}" {{ old('guru_id', $salary->guru_id) == $t->id ? 'selected' : '' }}>
                            {{ $t->name }} — {{ $t->branch?->name ?? 'Pusat' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Periode <span class="text-danger">*</span></label>
                <input type="month" name="periode" class="form-control" value="{{ old('periode', $salary->periode) }}" required>
            </div>

            {{-- Package info --}}
            <div class="col-12">
                <div id="teacherPackageInfo" class="alert alert-light border {{ $salary->guru_id ? '' : 'd-none' }} mb-0">
                    <div class="fw-semibold mb-2">Detail Paket yang diajar guru</div>
                    <div id="teacherPackageList" class="small text-muted">Memuat data paket...</div>
                </div>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Tipe Gaji <span class="text-danger">*</span></label>
                <select name="tipe_gaji" class="form-select" required>
                    <option value="bulanan" {{ old('tipe_gaji', $salary->tipe_gaji) == 'bulanan' ? 'selected' : '' }}>Gaji Bulanan</option>
                    <option value="freelance" {{ old('tipe_gaji', $salary->tipe_gaji) == 'freelance' ? 'selected' : '' }}>Gaji Freelance</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Gaji Pokok (Rp) <span class="text-danger">*</span></label>
                <input type="number" name="gaji_pokok" class="form-control" value="{{ old('gaji_pokok', $salary->gaji_pokok) }}" min="0" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Jam Mengajar</label>
                <input type="number" step="0.5" name="jam_mengajar" class="form-control" value="{{ old('jam_mengajar', $salary->jam_mengajar) }}" min="0">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Tarif Per Jam (Rp)</label>
                <input type="number" name="tarif_per_jam" class="form-control" value="{{ old('tarif_per_jam', $salary->tarif_per_jam) }}" min="0">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Bonus (Rp)</label>
                <input type="number" name="bonus" class="form-control" value="{{ old('bonus', $salary->bonus) }}" min="0">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Potongan (Rp)</label>
                <input type="number" name="potongan" class="form-control" value="{{ old('potongan', $salary->potongan) }}" min="0">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                <select name="status" class="form-select" required>
                    <option value="pending"  {{ old('status', $salary->status) == 'pending'  ? 'selected' : '' }}>Pending</option>
                    <option value="dibayar"  {{ old('status', $salary->status) == 'dibayar'  ? 'selected' : '' }}>Dibayar</option>
                    <option value="batal"    {{ old('status', $salary->status) == 'batal'    ? 'selected' : '' }}>Batal</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Metode Pembayaran</label>
                <select name="metode_pembayaran" class="form-select">
                    <option value="">Pilih</option>
                    <option value="Transfer Bank" {{ old('metode_pembayaran', $salary->metode_pembayaran) == 'Transfer Bank' ? 'selected' : '' }}>Transfer Bank</option>
                    <option value="Tunai"         {{ old('metode_pembayaran', $salary->metode_pembayaran) == 'Tunai' ? 'selected' : '' }}>Tunai</option>
                    <option value="E-Wallet"      {{ old('metode_pembayaran', $salary->metode_pembayaran) == 'E-Wallet' ? 'selected' : '' }}>E-Wallet</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Tanggal Pembayaran</label>
                <input type="date" name="tanggal_pembayaran" class="form-control" value="{{ old('tanggal_pembayaran', $salary->tanggal_pembayaran?->format('Y-m-d')) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Nama Bank</label>
                <input type="text" name="nama_bank" class="form-control" value="{{ old('nama_bank', $salary->nama_bank) }}" placeholder="BCA, Mandiri, dll">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Nomor Rekening</label>
                <input type="text" name="nomor_rekening" class="form-control" value="{{ old('nomor_rekening', $salary->nomor_rekening) }}">
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold">Bukti Pembayaran</label>
                @if($salary->bukti_pembayaran)
                <div class="mb-2">
                    <a href="{{ asset('storage/'.$salary->bukti_pembayaran) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-eye me-1"></i>Lihat Bukti Saat Ini
                    </a>
                </div>
                @endif
                <input type="file" name="bukti_pembayaran" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                <div class="form-text">Kosongkan jika tidak ingin mengubah bukti pembayaran</div>
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold">Catatan</label>
                <textarea name="catatan" class="form-control" rows="3" placeholder="Catatan tambahan (opsional)">{{ old('catatan', $salary->catatan) }}</textarea>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
            <a href="{{ route('admin.salaries.index') }}" class="btn btn-outline-secondary">Batal</a>
            <button type="submit" class="btn btn-primary px-4 fw-semibold">
                <i class="bi bi-check-lg me-1"></i>Simpan Perubahan
            </button>
        </div>
    </form>
</div>
</div>
@endsection

@push('scripts')
<script>
(function initTeacherPackageInfo() {
    const guruSelect = document.getElementById('guru_id');
    const packageInfo = document.getElementById('teacherPackageInfo');
    const packageList = document.getElementById('teacherPackageList');
    if (!guruSelect || !packageInfo || !packageList) return;

    function renderPackages(packages) {
        if (!packages.length) {
            packageList.innerHTML = '<div>Tidak ada paket aktif untuk guru ini.</div>';
            packageInfo.classList.remove('d-none');
            return;
        }
        packageList.innerHTML = packages.map(pkg => `
            <div class="border rounded p-2 mb-2">
                <div class="fw-semibold text-dark">${pkg.nama || '-'}</div>
                <div class="d-flex flex-wrap gap-3 mt-1">
                    <span><strong>Jenis:</strong> ${pkg.jenis || '-'}</span>
                    <span><strong>Sesi:</strong> ${pkg.jumlah_pertemuan ?? '-'}</span>
                    <span><strong>Durasi:</strong> ${pkg.durasi_bulan ?? '-'} bulan</span>
                </div>
            </div>`).join('');
        packageInfo.classList.remove('d-none');
    }

    function loadPackages() {
        const id = guruSelect.value;
        if (!id) { packageInfo.classList.add('d-none'); return; }
        packageInfo.classList.remove('d-none');
        packageList.innerHTML = '<div class="text-muted">Memuat data paket...</div>';
        fetch(`/admin/salaries/teachers/${id}/packages`)
            .then(r => r.json())
            .then(res => { if (res.success) renderPackages(res.data || []); })
            .catch(() => { packageList.innerHTML = '<div class="text-danger">Gagal memuat.</div>'; });
    }

    guruSelect.addEventListener('change', loadPackages);
    if (guruSelect.value) loadPackages();
}());
</script>
@endpush
