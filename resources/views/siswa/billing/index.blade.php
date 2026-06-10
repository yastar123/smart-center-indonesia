@extends('layouts.app')
@section('title','Tagihan')
@section('page-title','Tagihan Saya')

@section('content')
<div class="row g-4">
    @foreach($courses as $course)
    <div class="col-md-6">
        <div class="card p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="fw-semibold">{{ $course->nama }}</div>
                    <div class="text-muted" style="font-size:12px">Biaya: Rp {{ number_format($fees[$course->id] ?? 0,0,',','.') }}</div>
                </div>
                <div>
                    @if(isset($payments[$course->id]))
                        <small class="badge bg-warning text-dark">{{ ucfirst($payments[$course->id]->status) }}</small>
                    @else
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#payModal{{ $course->id }}">Bayar</button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="payModal{{ $course->id }}" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <form action="{{ route('siswa.billing.pay', $course->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-header">
              <h5 class="modal-title">Bayar {{ $course->nama }}</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label class="form-label">Unggah Bukti (jpg,png,pdf)</label>
                <input type="file" name="proof" class="form-control" required>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
              <button class="btn btn-primary">Kirim Bukti</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    @endforeach
</div>
@endsection
