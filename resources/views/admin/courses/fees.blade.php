@extends('layouts.app')
@section('title','Biaya Mata Pelajaran')
@section('page-title','Biaya Mata Pelajaran')

@section('content')
<div class="dashboard-card mb-4">
    <h6 class="fw-bold">Biaya Mata Pelajaran</h6>
    <div class="table-responsive mt-3">
        <table class="table table-hover">
            <thead>
                <tr><th>Nama</th><th>Cabang</th><th>Biaya (Rp)</th><th class="text-end">Aksi</th></tr>
            </thead>
            <tbody>
                @foreach($courses as $c)
                <tr>
                    <td>{{ $c->nama }}</td>
                    <td>{{ $c->cabang->name ?? 'Pusat' }}</td>
                    <td>
                        <form action="{{ route('admin.courses.fees.update', $c->id) }}" method="POST" class="d-flex gap-2 align-items-center">
                            @csrf
                            <input type="text" name="amount" value="{{ number_format($fees[$c->id] ?? 0,2,'.','') }}" class="form-control form-control-sm" style="width:160px">
                            <button class="btn btn-sm btn-primary">Simpan</button>
                        </form>
                    </td>
                    <td class="text-end">
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
