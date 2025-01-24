@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Edit Transaksi</h1>
            <p class="text-muted small mb-0">Edit data transaksi keuangan</p>
        </div>
        <a href="{{ route('keuangan.index') }}" class="btn btn-outline-primary btn-sm shadow-sm">
            <i class="fas fa-arrow-left fa-sm me-1"></i> Kembali
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('keuangan.update', $keuangan->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label">Jenis Transaksi <span class="text-danger">*</span></label>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="jenis" id="pemasukan" value="pemasukan" {{ old('jenis', $keuangan->jenis) == 'pemasukan' ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="pemasukan">
                                            Pemasukan
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="jenis" id="pengeluaran" value="pengeluaran" {{ old('jenis', $keuangan->jenis) == 'pengeluaran' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="pengeluaran">
                                            Pengeluaran
                                        </label>
                                    </div>
                                </div>
                                @error('jenis')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Kategori <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('kategori') is-invalid @enderror" 
                                       name="kategori" value="{{ old('kategori', $keuangan->kategori) }}" 
                                       placeholder="Contoh: Penjualan, Gaji, Operasional, dll" required>
                                @error('kategori')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Jumlah <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control @error('jumlah') is-invalid @enderror" 
                                           name="jumlah" value="{{ old('jumlah', $keuangan->jumlah) }}" 
                                           placeholder="0" min="0" required>
                                </div>
                                @error('jumlah')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('tanggal') is-invalid @enderror" 
                                       name="tanggal" value="{{ old('tanggal', $keuangan->tanggal->format('Y-m-d')) }}" required>
                                @error('tanggal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Keterangan</label>
                                <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                                          name="keterangan" rows="3" 
                                          placeholder="Tambahkan keterangan transaksi (opsional)">{{ old('keterangan', $keuangan->keterangan) }}</textarea>
                                @error('keterangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Bukti Transaksi</label>
                                @if($keuangan->bukti_transaksi)
                                    <div class="mb-2">
                                        <img src="{{ Storage::url($keuangan->bukti_transaksi) }}" 
                                             alt="Bukti Transaksi" 
                                             class="img-thumbnail" 
                                             style="max-height: 200px;">
                                    </div>
                                @endif
                                <input type="file" class="form-control @error('bukti_transaksi') is-invalid @enderror" 
                                       name="bukti_transaksi" accept="image/*">
                                <div class="form-text">Format: JPG, PNG, GIF (Max. 2MB)</div>
                                @error('bukti_transaksi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <hr>
                                <div class="d-flex gap-2 justify-content-end">
                                    <a href="{{ route('keuangan.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times fa-sm me-1"></i> Batal
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save fa-sm me-1"></i> Simpan Perubahan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
