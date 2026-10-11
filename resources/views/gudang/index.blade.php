@extends('layouts.gudang')

@section('konten')
<div class="container mt-4 mb-5">
    <div class="row mb-3">
        <div class="col-md-8">
            <h4 class="fw-bold mb-0 text-dark"><i class="fas fa-cubes me-2" style="color: #e60023;"></i>Manajemen Stok Barang</h4>
            <p class="text-muted small">Kelola ketersediaan stok barang pusat secara real-time</p>
        </div>
        <div class="col-md-4 text-md-end">
            <span class="badge bg-light text-dark border p-2 shadow-sm">
                <i class="fas fa-user-circle me-1 text-danger"></i> {{ $namaAdmin }}
            </span>
        </div>
    </div>

    {{-- Notifikasi sukses setelah stok diperbarui --}}
    @if (session('sukses'))
        <div class="alert alert-success border-0 shadow-sm rounded-3">
            <i class="fas fa-check-circle me-1"></i> {{ session('sukses') }}
        </div>
    @endif

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-0">

            @if ($stok->isEmpty())
                <div class="text-center p-5 text-muted">
                    <i class="fas fa-box-open fa-3x mb-3 text-light"></i>
                    <h5>Belum ada data barang.</h5>
                    <p class="small">Hubungi Owner untuk menambahkan barang ke gudang pusat.</p>
                </div>
            @else

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-secondary">
                        <tr>
                            <th scope="col" class="ps-4">NAMA BARANG</th>
                            <th scope="col" width="150" class="text-center">SATUAN</th>
                            <th scope="col" width="250" class="text-center pe-4">UPDATE STOK</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($stok as $row)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark" style="font-size: 1.1rem;">{{ $row->nama_barang }}</div>
                            </td>
                            <td class="text-center text-muted">
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2 py-1">
                                    {{ $row->satuan }}
                                </span>
                            </td>
                            <td class="pe-4">
                                {{-- Formulir pembaruan stok per barang --}}
                                <form action="{{ route('gudang.update') }}" method="POST" class="d-flex align-items-center justify-content-center gap-2 m-0">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $row->id }}">
                                    <input type="number" name="stok" class="form-control form-control-sm text-center fw-bold" style="width: 80px;" value="{{ $row->stok }}" required>
                                    <button type="submit" name="update_stok" class="btn btn-dfc btn-sm fw-bold">
                                        <i class="fas fa-save me-1"></i> Simpan
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @endif

        </div>
    </div>
</div>
@endsection
