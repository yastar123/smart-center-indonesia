@extends('layouts.app')
@section('title','Tambah Artikel')
@section('page-title','Tambah Artikel')

@section('content')
<div class="dashboard-card fade-up" style="max-width: 1000px; margin: 0 auto; border-radius: 0; border: none; box-shadow: none; padding: 24px;">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
        <div>
            <h4 class="fw-bold mb-1">Buat Artikel Baru</h4>
            <p class="text-muted mb-0" style="font-size: 13px;">Isi detail artikel, lalu simpan untuk ditampilkan di halaman publik.</p>
        </div>
        <a href="{{ route('admin.articles.index') }}" class="btn btn-outline-secondary">Kembali</a>
    </div>

    <form id="articleCreateForm" method="POST" action="{{ route('admin.articles.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label fw-semibold">Judul Artikel <span class="text-danger">*</span></label>
                <input type="text" name="judul" class="form-control form-control-lg" placeholder="Tulis judul artikel..." required>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                <select name="kategori" class="form-select">
                    <option value="berita">Berita</option>
                    <option value="tips">Tips & Trik</option>
                    <option value="akademik">Akademik</option>
                    <option value="promo">Promo</option>
                    <option value="lainnya">Lainnya</option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                <select name="status" class="form-select">
                    <option value="draft">Draft</option>
                    <option value="published">Published</option>
                </select>
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold">Ringkasan</label>
                <textarea name="ringkasan" class="form-control" rows="3" maxlength="500" placeholder="Deskripsi singkat artikel..."></textarea>
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold">Konten Artikel <span class="text-danger">*</span></label>
                <textarea name="konten" class="form-control" rows="14" placeholder="Tulis isi artikel di sini..." required></textarea>
                <div class="form-text">Mendukung HTML dasar.</div>
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold">Thumbnail</label>
                <input type="file" name="thumbnail" class="form-control" accept="image/*">
            </div>
        </div>

        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary px-4">Simpan Artikel</button>
            <a href="{{ route('admin.articles.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('articleCreateForm')?.addEventListener('submit', function (e) {
    e.preventDefault();

    const form = this;
    const fd = new FormData(form);

    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: fd
    })
    .then(async r => {
        const data = await r.json().catch(() => ({}));
        if (!r.ok) {
            throw new Error(data.message || 'Gagal menyimpan artikel');
        }
        window.showToast?.(data.message || 'Artikel berhasil disimpan', 'success');
        setTimeout(() => window.location.href = '{{ route('admin.articles.index') }}', 700);
    })
    .catch(err => {
        window.showToast?.(err.message || 'Terjadi kesalahan', 'error');
    });
});
</script>
@endpush
