@extends('layouts.master')

@section('content')
<div class="container my-5">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-success p-4">
            <div class="row align-items-center">
                <div class="col">
                    <h6 class="text-white text-uppercase ls-1 mb-1">Edit Laporan Penjualan</h6>
                </div>
                <div class="col-auto">
                    <a href="{{ route('laporan-penjualan.show', $laporan->id) }}" class="btn btn-light">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('laporan-penjualan.update', $laporan->id) }}" method="POST" id="editForm">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nama_barang" class="form-label">
                            <i class="fas fa-box me-2"></i>Nama Barang
                        </label>
                        <input type="text" id="nama_barang" name="nama_barang" class="form-control" 
                               value="{{ old('nama_barang', $laporan->nama_barang) }}" required readonly>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="jumlah" class="form-label">
                            <i class="fas fa-sort-numeric-up me-2"></i>Jumlah
                        </label>
                        <input type="number" id="jumlah" name="jumlah" class="form-control" 
                               value="{{ old('jumlah', $laporan->jumlah) }}" required readonly>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="total_harga" class="form-label">
                            <i class="fas fa-money-bill-wave me-2"></i>Total Harga
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" id="total_harga" name="total_harga" class="form-control" 
                                   value="{{ old('total_harga', $laporan->total_harga) }}" required readonly>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="keuntungan" class="form-label">
                            <i class="fas fa-chart-line me-2"></i>Keuntungan
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" id="keuntungan" name="keuntungan" class="form-control" 
                                   value="{{ old('keuntungan', $laporan->keuntungan) }}" required readonly>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="tanggal" class="form-label">
                            <i class="fas fa-calendar me-2"></i>Tanggal
                        </label>
                        <input type="date" id="tanggal" name="tanggal" class="form-control" 
                               value="{{ old('tanggal', $laporan->tanggal) }}" required>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i>Simpan Perubahan
                    </button>
                    <a href="{{ route('laporan-penjualan.show', $laporan->id) }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#editForm').on('submit', function(e) {
        e.preventDefault();
        
        Swal.fire({
            title: 'Konfirmasi',
            text: 'Apakah Anda yakin ingin menyimpan perubahan?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Simpan',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        });
    });

    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            timer: 3000,
            showConfirmButton: false
        });
    @endif

    @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: "{{ $errors->first() }}"
        });
    @endif
});
</script>
@endpush
@endsection
