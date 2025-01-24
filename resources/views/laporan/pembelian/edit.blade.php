@extends('layouts.master')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-xl border-0">
                    <div class="card-header bg-gradient-primary position-relative py-5">
                        <div class="row align-items-center position-relative z-1">
                            <div class="col">
                                <h5><span class="badge bg-white text-primary mb-2 px-3 py-2">Edit Laporan Pembelian</span>
                                </h5>

                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('laporan-pembelian.update', $laporan->id) }}" method="POST"
                            class="needs-validation" novalidate>
                            @csrf
                            @method('PUT')

                            <div class="row g-4">
                                <!-- Informasi Barang Card -->
                                <div class="col-12 mb-4">
                                    <div class="card bg-light border-0 h-100 hover-shadow">
                                        <div class="card-body p-4">
                                            <div class="d-flex align-items-center mb-4">
                                                <div class="icon-shape bg-primary text-white rounded-circle me-3">
                                                    <i class="fas fa-box"></i>
                                                </div>
                                                <h5 class="text-primary mb-0">Informasi Barang</h5>
                                            </div>
                                            <div class="row g-4">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="nama_barang" class="form-label text-muted">Nama
                                                            Barang</label>
                                                        <div class="input-group input-group-dynamic">
                                                            <span class="input-group-text bg-white border-end-0">
                                                                <i class="fas fa-box-open text-primary"></i>
                                                            </span>
                                                            <input type="text"
                                                                class="form-control ps-2 border-start-0 @error('nama_barang') is-invalid @enderror"
                                                                id="nama_barang" name="nama_barang"
                                                                value="{{ old('nama_barang', $laporan->nama_barang) }}"
                                                                required readonly>
                                                        </div>
                                                        @error('nama_barang')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="jumlah" class="form-label text-muted">Jumlah</label>
                                                        <div class="input-group input-group-dynamic">
                                                            <span class="input-group-text bg-white border-end-0">
                                                                <i class="fas fa-cubes text-primary"></i>
                                                            </span>
                                                            <input type="number"
                                                                class="form-control ps-2 border-start-0 @error('jumlah') is-invalid @enderror"
                                                                id="jumlah" name="jumlah"
                                                                value="{{ old('jumlah', $laporan->jumlah) }}" required readonly>
                                                            <span
                                                                class="input-group-text bg-white border-start-0">unit</span>
                                                        </div>
                                                        @error('jumlah')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Informasi Transaksi Card -->
                                <div class="col-12">
                                    <div class="card bg-light border-0 h-100 hover-shadow">
                                        <div class="card-body p-4">
                                            <div class="d-flex align-items-center mb-4">
                                                <div class="icon-shape bg-success text-white rounded-circle me-3">
                                                    <i class="fas fa-money-bill-wave"></i>
                                                </div>
                                                <h5 class="text-success mb-0">Informasi Transaksi</h5>
                                            </div>
                                            <div class="row g-4">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="total_harga" class="form-label text-muted">Total
                                                            Harga</label>
                                                        <div class="input-group input-group-dynamic">
                                                            <span class="input-group-text bg-white border-end-0">
                                                                <i class="fas fa-money-bill text-success"></i>
                                                            </span>
                                                            <span class="input-group-text bg-white border-end-0">Rp</span>
                                                            <input type="number"
                                                                class="form-control ps-2 border-start-0 @error('total_harga') is-invalid @enderror"
                                                                id="total_harga" name="total_harga"
                                                                value="{{ old('total_harga', $laporan->total_harga) }}"
                                                                required readonly>
                                                        </div>
                                                        @error('total_harga')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="tanggal" class="form-label text-muted">Tanggal</label>
                                                        <div class="input-group input-group-dynamic">
                                                            <span class="input-group-text bg-white border-end-0">
                                                                <i class="fas fa-calendar text-success"></i>
                                                            </span>
                                                            <input type="date"
                                                                class="form-control ps-2 border-start-0 @error('tanggal') is-invalid @enderror"
                                                                id="tanggal" name="tanggal"
                                                                value="{{ old('tanggal', $laporan->tanggal) }}" required >
                                                        </div>
                                                        @error('tanggal')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" name="bulan" value="{{ date('m', strtotime($laporan->tanggal)) }}">
                            </div>

                            <div class="d-flex justify-content-end gap-3 mt-4">
                                <a href="{{ route('laporan-pembelian.show', $laporan->id) }}"
                                    class="btn btn-secondary hover-translate">
                                    <i class="fas fa-times me-2"></i>Batal
                                </a>
                                <button type="submit" class="btn btn-primary hover-translate">
                                    <i class="fas fa-save me-2"></i>Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .wave-wrapper {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            overflow: hidden;
            line-height: 0;
        }

        .waves {
            position: relative;
            width: 100%;
            height: 40px;
        }

        .z-1 {
            z-index: 1;
        }

        .icon-shape {
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        .form-control {
            border: 1px solid #e3e6f0;
            padding: 0.75rem 1rem;
            height: calc(2.75rem + 2px);
            font-size: 0.875rem;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }

        .input-group-text {
            border: 1px solid #e3e6f0;
            font-size: 0.875rem;
        }

        .hover-shadow {
            transition: all 0.3s ease;
        }

        .hover-shadow:hover {
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .08);
            transform: translateY(-3px);
        }

        .hover-translate {
            transition: all 0.3s ease;
        }

        .hover-translate:hover {
            transform: translateY(-3px);
        }

        .btn {
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: 0.5rem;
        }

        .opacity-8 {
            opacity: 0.8;
        }

        .input-group-dynamic .form-control {
            border-radius: 0.5rem;
        }

        .input-group-dynamic .input-group-text {
            border-radius: 0.5rem;
        }

        .input-group-dynamic .form-control:focus {
            border-color: #4e73df;
        }

        .input-group-dynamic .form-control:focus+.input-group-text {
            border-color: #4e73df;
        }
    </style>

    @push('scripts')
        <script>
            $(document).ready(function() {
                // Form validation
                (function() {
                    'use strict';
                    var forms = document.querySelectorAll('.needs-validation');
                    Array.prototype.slice.call(forms).forEach(function(form) {
                        form.addEventListener('submit', function(event) {
                            if (!form.checkValidity()) {
                                event.preventDefault();
                                event.stopPropagation();
                            }
                            form.classList.add('was-validated');
                        }, false);
                    });
                })();

                // Format currency input
                $('#total_harga').on('input', function() {
                    let value = $(this).val();
                    value = value.replace(/[^\d]/g, '');
                    $(this).val(value);
                });

                // Add floating label effect
                $('.form-control').on('focus blur', function(e) {
                    $(this).parents('.form-group').toggleClass('focused', (e.type === 'focus'));
                }).trigger('blur');
            });
        </script>
    @endpush
@endsection
