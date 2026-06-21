@extends('layouts.app')
@section('title','Input Gaji Guru')
@section('page-title','Input Gaji Guru')

@section('content')
<div class="container-fluid px-0">
    <div class="dashboard-card border-0 shadow-none rounded-0 mb-0 p-4 p-md-5" style="min-height: calc(100vh - 120px);">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-4">
            <div>
                <h5 class="fw-bold mb-1">Form Input Gaji</h5>
                <div class="text-muted small">Isi data pembayaran gaji guru di halaman terpisah</div>
            </div>
            <a href="{{ route('admin.salaries.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
        </div>

        <form action="{{ route('admin.salaries.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Guru <span class="text-danger">*</span></label>
                        <select id="guru_id" name="guru_id" class="form-select" required>
                            <option value="">Pilih Guru</option>
                            @foreach($teachers as $t)
                                <option value="{{ $t->id }}" {{ old('guru_id', request('guru_id')) == $t->id ? 'selected' : '' }}>
                                    {{ $t->name }} - {{ $t->branch?->name ?? 'Pusat' }} - {{ $t->jenis_guru ?? 'Guru' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Periode <span class="text-danger">*</span></label>
                        <input type="month" name="periode" class="form-control" value="{{ old('periode', date('Y-m')) }}" required>
                    </div>
                    <div class="col-12">
                        <div id="teacherPackageInfo" class="alert alert-light border d-none mb-0">
                            <div class="fw-semibold mb-2">Detail Paket yang diajar guru</div>
                            <div id="teacherPackageList" class="small text-muted"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tipe Gaji</label>
                        <select name="tipe_gaji" class="form-select">
                            <option value="bulanan" {{ old('tipe_gaji') == 'bulanan' ? 'selected' : '' }}>Gaji Bulanan</option>
                            <option value="freelance" {{ old('tipe_gaji') == 'freelance' ? 'selected' : '' }}>Gaji Freelance</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Gaji Pokok (Rp)</label>
                        <input type="number" name="gaji_pokok" class="form-control" value="{{ old('gaji_pokok', 0) }}" min="0" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Bonus</label>
                        <input type="number" name="bonus" class="form-control" value="{{ old('bonus', 0) }}" min="0">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select">
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="dibayar" {{ old('status') == 'dibayar' ? 'selected' : '' }}>Dibayar</option>
                            <option value="batal" {{ old('status') == 'batal' ? 'selected' : '' }}>Batal</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Metode Pembayaran</label>
                        <select name="metode_pembayaran" class="form-select">
                            <option value="">Pilih</option>
                            <option value="Transfer Bank" {{ old('metode_pembayaran') == 'Transfer Bank' ? 'selected' : '' }}>Transfer Bank</option>
                            <option value="Tunai" {{ old('metode_pembayaran') == 'Tunai' ? 'selected' : '' }}>Tunai</option>
                            <option value="E-Wallet" {{ old('metode_pembayaran') == 'E-Wallet' ? 'selected' : '' }}>E-Wallet</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tanggal Pembayaran</label>
                        <input type="date" name="tanggal_pembayaran" class="form-control" value="{{ old('tanggal_pembayaran') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nama Bank</label>
                        <input type="text" name="nama_bank" class="form-control" value="{{ old('nama_bank') }}" placeholder="BCA, Mandiri, dll">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nomor Rekening</label>
                        <input type="text" name="nomor_rekening" class="form-control" value="{{ old('nomor_rekening') }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Bukti Pembayaran</label>
                        <input type="file" name="bukti_pembayaran" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4 pt-2 border-top">
                    <a href="{{ route('admin.salaries.index') }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary px-4">Simpan</button>
                </div>
            </form>
        </div>
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
                    <span><strong>Jenis Paket:</strong> ${pkg.jenis || '-'}</span>
                    <span><strong>Jumlah Sesi:</strong> ${pkg.jumlah_pertemuan ?? '-'}</span>
                    <span><strong>Durasi:</strong> ${pkg.durasi_bulan ?? '-'} bulan</span>
                </div>
            </div>
        `).join('');

        packageInfo.classList.remove('d-none');
    }

    function loadPackages() {
        const teacherId = guruSelect.value;
        if (!teacherId) {
            packageInfo.classList.add('d-none');
            packageList.innerHTML = '';
            return;
        }

        packageInfo.classList.remove('d-none');
        packageList.innerHTML = '<div class="text-muted">Memuat data paket...</div>';

        fetch(`/admin/salaries/teachers/${teacherId}/packages`)
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    renderPackages(result.data || []);
                } else {
                    packageList.innerHTML = '<div class="text-danger">Gagal memuat data paket.</div>';
                }
            })
            .catch(() => {
                packageList.innerHTML = '<div class="text-danger">Gagal memuat data paket.</div>';
            });
    }

    guruSelect.addEventListener('change', loadPackages);
    if (guruSelect.value) {
        loadPackages();
    }
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initTeacherPackageInfo);
} else {
    initTeacherPackageInfo();
}
</script>
@endpush
