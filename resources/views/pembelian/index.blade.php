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

        /* Search Box Styling */
        .search-box {
            margin-bottom: 20px;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
            padding: 0 20px;
        }

        .search-box input {
            width: 100%;
            padding: 12px 20px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .search-box input:focus {
            border-color: #3498db;
            box-shadow: 0 0 10px rgba(52, 152, 219, 0.1);
            outline: none;
        }

        /* Button Container */
        .btn-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
            padding: 0 20px;
        }

        .btn-tambah {
            background-color: #2ecc71;
            color: white;
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn-tambah i {
            margin-right: 8px;
        }

        .btn-tambah:hover {
            background-color: #27ae60;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(46, 204, 113, 0.3);
            color: white;
            text-decoration: none;
        }

        .btn-hapus-semua {
            background-color: #e74c3c;
            color: white;
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn-hapus-semua i {
            margin-right: 8px;
        }

        .btn-hapus-semua:hover {
            background-color: #c0392b;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(231, 76, 60, 0.3);
            color: white;
            text-decoration: none;
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
        
.pagination-container {
    margin-top: 30px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.pagination {
    display: flex;
    list-style: none;
    padding: 0;
    margin: 0;
    gap: 10px; /* Menambah jarak antar item */
}

.page-item {
    margin: 0;
}

.page-link {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 40px;
    height: 40px;
    padding: 8px 12px;
    color: #2c3e50;
    background-color: #ffffff; /* Warna latar belakang putih */
    border: 1px solid #e2e8f0;
    font-weight: 500;
    text-decoration: none;
    border-radius: 5px; /* Menambahkan border-radius */
    transition: all 0.3s ease;
}

.page-link:hover {
    background-color: #3498db; /* Warna latar belakang saat hover */
    color: white; /* Warna teks saat hover */
    border-color: #2980b9; /* Warna border saat hover */
}

.page-item.active .page-link {
    background-color: #3498db; /* Warna latar belakang untuk item aktif */
    color: white; /* Warna teks untuk item aktif */
    border-color: #3498db; /* Warna border untuk item aktif */
}

.page-item.disabled .page-link {
    color: #a0aec0; /* Warna teks untuk item disabled */
    pointer-events: none; /* Nonaktifkan interaksi */
    background-color: #f7fafc; /* Warna latar belakang untuk item disabled */
    border-color: #e2e8f0; /* Warna border untuk item disabled */
}

        /* Responsif untuk Mobile */
        @media (max-width: 640px) {
            .pagination {
                gap: 2px;
                padding: 5px;
            }

            .page-link {
                min-width: 35px;
                height: 35px;
                padding: 6px 10px;
                font-size: 14px;
            }
        }

        /* Action Button Styling */
        .btn-danger {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-danger:hover {
            background-color: #c0392b;
            transform: translateY(-1px);
            box-shadow: 0 3px 8px rgba(231, 76, 60, 0.3);
        }

        /* Empty State Styling */
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
        <h1><i class="fas fa-shopping-cart"></i> Daftar Pembelian</h1>

        <div class="search-box">
            <input type="text" id="searchInput" placeholder="Cari berdasarkan nama barang..." class="form-control">
        </div>

        <div class="btn-container">
            <a href="{{ route('pembelian.create') }}" class="btn-tambah">
                <i class="fas fa-plus"></i> Tambah Pembelian
            </a>
            {{-- <form id="formHapusSemua" action="{{ route('pembelian.destroy-all') }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-hapus-semua" onclick="konfirmasiHapusSemua(event);">
                    <i class="fas fa-trash"></i> Hapus Semua Data
                </button>
            </form> --}}
        </div>

        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
                        <th>Harga Satuan</th>
                        <th>Total</th>
                        <th>Tanggal</th>
                        <th>User</th>
                        {{-- <th>Aksi</th> --}}
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @forelse($pembelians as $index => $pembelian)
                        <tr>
                            <td>{{ $pembelians->firstItem() + $index }}</td>
                            <td>{{ $pembelian->nama_barang }}</td>
                            <td>{{ $pembelian->jumlah }}</td>
                            <td>Rp {{ number_format($pembelian->harga_satuan, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($pembelian->total, 0, ',', '.') }}</td>
                            <td>{{ $pembelian->tanggal->format('d/m/Y') }}</td>
                            <td>
                                @if ($pembelian->user)
                                    {{ $pembelian->user->name }}
                                    <span class="
                                        @switch($pembelian->user->role)
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
                                        ({{ ucfirst($pembelian->user->role) }})
                                    </span>
                                @else
                                    Tidak Diketahui
                                @endif
                            </td>
                            {{-- <td>
                                <form id="formHapus{{ $pembelian->id }}" action="{{ route('pembelian.destroy', $pembelian->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger" onclick="konfirmasiHapus({{ $pembelian->id }})">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </td> --}}
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Tidak ada data pembelian</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="pagination-container">
                <ul class="pagination">
                    <li class="page-item {{ $pembelians->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $pembelians->previousPageUrl() }}">«</a>
                    </li>
                    @for ($i = 1; $i <= $pembelians->lastPage(); $i++)
                        <li class="page-item {{ $i == $pembelians->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $pembelians->url($i) }}">{{ $i }}</a>
                        </li>
                    @endfor
                    <li class="page-item {{ $pembelians->hasMorePages() ? '' : 'disabled' }}">
                        <a class="page-link" href="{{ $pembelians->nextPageUrl() }}">»</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Include SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Live Search
        document.getElementById('searchInput').addEventListener('keyup', function() {
            let searchQuery = this.value.toLowerCase();
            let tableBody = document.getElementById('tableBody');
            let rows = tableBody.getElementsByTagName('tr');

            for (let row of rows) {
                let namaBarang = row.getElementsByTagName('td')[1].textContent.toLowerCase();
                if (namaBarang.includes(searchQuery)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });

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
                text: "Data pembelian akan dihapus!",
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

        // Fungsi konfirmasi hapus semua data
        function konfirmasiHapusSemua(event) {
            event.preventDefault();
            
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "SEMUA data pembelian akan dihapus! Tindakan ini tidak dapat dibatalkan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e74c3c',
                cancelButtonColor: '#95a5a6',
                confirmButtonText: 'Ya, hapus semua!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('formHapusSemua').submit();
                }
            });
        }
    </script>
@endsection
