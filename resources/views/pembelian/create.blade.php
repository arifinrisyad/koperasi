@extends('layouts.master')

@section('content')
    <style>
        /* Page Styling */
        body {
            background-color: #f7f9fc;
            font-family: 'Georgia', serif;
            color: #333;
        }

        h1 {
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        h1 i {
            margin-right: 10px;
        }

        /* Form Container */
        .form-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* Form Group */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: bold;
            color: #2c3e50;
            display: block;
            margin-bottom: 5px;
        }

        .form-group input, .form-group select {
            font-size: 14px;
            padding: 10px;
            width: 100%;
            border: 1px solid #ddd;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .form-group input:focus, .form-group select:focus {
            border-color: #1abc9c;
            box-shadow: 0 0 8px rgba(26, 188, 156, 0.2);
            outline: none;
        }

        /* Error Styling */
        .invalid-feedback {
            color: #dc3545;
            font-size: 14px;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .is-invalid {
            border-color: #dc3545 !important;
        }

        .is-invalid:focus {
            box-shadow: 0 0 8px rgba(220, 53, 69, 0.2) !important;
        }

        /* Buttons */
        .btn-primary {
            background-color: #34495e;
            color: #ffffff;
            border: none;
            padding: 12px 24px;
            font-weight: bold;
            border-radius: 6px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }

        .btn-primary i {
            margin-right: 8px;
        }

        .btn-primary:hover {
            background-color: #1abc9c;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-secondary {
            background-color: #e7e7e7;
            color: #333;
            padding: 12px 24px;
            border-radius: 6px;
            text-decoration: none;
            margin-left: 10px;
            display: inline-flex;
            align-items: center;
            border: none;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .btn-secondary i {
            margin-right: 8px;
        }

        .btn-secondary:hover {
            background-color: #d1d1d1;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        }
    </style>

    <!-- Include SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <div class="form-container mt-4">
        <h1><i class="fas fa-plus-circle"></i> Tambah Pembelian</h1>

        <form id="formPembelian" action="{{ route('pembelian.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="nama_barang"><i class="fas fa-box"></i> Nama Barang</label>
                <input type="text" name="nama_barang" class="form-control @error('nama_barang') is-invalid @enderror" 
                       id="nama_barang" value="{{ old('nama_barang') }}" required>
                @error('nama_barang')
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="jumlah"><i class="fas fa-sort-numeric-up-alt"></i> Jumlah</label>
                <input type="number" name="jumlah" class="form-control @error('jumlah') is-invalid @enderror" 
                       id="jumlah" value="{{ old('jumlah') }}" required>
                @error('jumlah')
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="harga_satuan"><i class="fas fa-money-bill-wave"></i> Harga Beli Satuan</label>
                <input type="number" name="harga_satuan" class="form-control @error('harga_satuan') is-invalid @enderror" 
                       id="harga_satuan" value="{{ old('harga_satuan') }}" required>
                @error('harga_satuan')
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="tanggal"><i class="fas fa-calendar-alt"></i> Tanggal</label>
                <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" 
                       id="tanggal" value="{{ old('tanggal') }}" required readonly>
                @error('tanggal')
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>
            
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan
                </button>
                <a href="{{ route('pembelian.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>

    <!-- Include SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Set tanggal saat ini sebagai default value
            const tanggalInput = document.getElementById('tanggal');
            const currentDate = new Date().toISOString().split('T')[0];
            tanggalInput.value = currentDate;

            // Tampilkan SweetAlert untuk pesan sukses
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    showConfirmButton: true,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#2ecc71',
                    timer: 3000,
                    timerProgressBar: true,
                    position: 'center'
                });
            @endif

            // Tampilkan SweetAlert untuk pesan error
            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: '{{ session('error') }}',
                    showConfirmButton: true,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#e74c3c',
                    timer: 3000,
                    timerProgressBar: true,
                    position: 'center'
                });
            @endif
        });
    </script>
@endsection
