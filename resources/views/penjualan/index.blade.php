@extends('layouts.master')

@section('content')
    <style>
        
        /* Global Styling */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7fc;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .content {
            padding: 50px 30px;
        }

        /* Header Styling */
        h1 {
            text-align: center;
            font-size: 38px;
            font-weight: 700;
            color: #34495e;
            margin-bottom: 40px;
            letter-spacing: 1px;
        }

        h1 i {
            margin-right: 10px;
            color: #e74c3c;
        }

        /* Button Container */
        .button-container {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
        }

        .btn-primary {
            background-color: #2ecc71;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-primary:hover {
            background-color: #27ae60;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(46, 204, 113, 0.3);
        }

        /* Table Container */
        .table-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-top: 30px;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Table Styling */
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }

        .table th, .table td {
            padding: 15px;
            text-align: left;
            font-size: 16px;
        }

        .table th {
            background-color: #2c3e50;
            color: white;
            font-weight: 600;
        }

        .table tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .table tr:hover {
            background-color: #f1f2f6;
        }

        /* Button Styling */
        .btn-group {
            display: flex;
            gap: 8px;
        }

        .btn {
            padding: 8px 15px;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: none;
        }

        .btn i {
            font-size: 16px;
        }

        .btn-warning {
            background-color: #f39c12;
            color: white;
        }

        .btn-warning:hover {
            background-color: #d68910;
            transform: translateY(-1px);
            box-shadow: 0 3px 8px rgba(243, 156, 18, 0.3);
        }

        .btn-danger {
            background-color: #e74c3c;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c0392b;
            transform: translateY(-1px);
            box-shadow: 0 3px 8px rgba(231, 76, 60, 0.3);
        }

        /* Empty State */
        .text-center {
            text-align: center;
            padding: 20px;
            color: #7f8c8d;
            font-style: italic;
        }

        .role-admin {
        color: red;
        font-weight: bold;
    }

    .role-petugas {
        color: green;
        font-weight: bold;
    }
    .role-default {
        color: gray;
        font-weight: bold;
    }
    </style>

    <!-- Include SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <div class="content">
        <h1><i class="fas fa-shopping-cart"></i> Daftar Penjualan</h1>

        <div class="button-container">
            <a href="{{ route('penjualan.create') }}" class="btn-primary">
                <i class="fas fa-plus"></i> Tambah Penjualan
            </a>
        </div>

        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
                        <th>Total Harga</th>
                        <th>Keuntungan</th>
                        <th>Tanggal</th>
                        <th>Petugas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penjualans as $index => $penjualan)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ optional($penjualan->barangdijual)->nama_barang ?? 'Barang tidak ditemukan' }}</td>
                            <td>{{ $penjualan->jumlah }}</td>
                            <td>Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($penjualan->keuntungan, 0, ',', '.') }}</td>
                            <td>{{ $penjualan->tanggal }}</td>
                           
                            <td>
                                @if ($penjualan->user)
                                    {{ $penjualan->user->name }}
                                    <span class="
                                        @switch($penjualan->user->role)
                                            @case('admin')
                                                role-admin
                                                @break
                                            @case('petugas')
                                                role-petugas
                                                @break
                                            @default
                                                role-default
                                        @endswitch
                                    ">
                                        ({{ ucfirst($penjualan->user->role) }})
                                    </span>
                                @else
                                    Tidak Diketahui
                                @endif
                            </td>
                            


                            <td>
                                <div class="btn-group">
                                   
                                    <form id="formHapus{{ $penjualan->id }}" 
                                          action="{{ route('penjualan.destroy', $penjualan->id) }}" 
                                          method="POST" 
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger" 
                                                onclick="konfirmasiHapus({{ $penjualan->id }})">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Tidak ada data penjualan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Include SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
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

        // Fungsi konfirmasi hapus data
        function konfirmasiHapus(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data penjualan akan dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e74c3c',
                cancelButtonColor: '#95a5a6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('formHapus' + id).submit();
                }
            });
        }
    </script>
@endsection
