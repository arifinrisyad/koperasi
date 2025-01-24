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

        /* Search Form Styling */
        .search-form {
            max-width: 300px;
            margin-bottom: 20px;
        }

        .input-group {
            display: flex;
            gap: 0;
        }

        .form-control {
            border: 1px solid #ddd;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }

        /* Pagination Styling */
        .pagination {
            margin: 0;
            padding: 0;
            display: flex;
            gap: 3px;
        }

        .page-link {
            position: relative;
            display: block;
            padding: 0.5rem 0.75rem;
            margin-left: -1px;
            line-height: 1.25;
            color: #3498db;
            background-color: #fff;
            border: 1px solid #dee2e6;
            font-size: 14px;
        }

        .page-item:first-child .page-link {
            margin-left: 0;
            border-top-left-radius: 0.25rem;
            border-bottom-left-radius: 0.25rem;
        }

        .page-item:last-child .page-link {
            border-top-right-radius: 0.25rem;
            border-bottom-right-radius: 0.25rem;
        }

        .page-item.active .page-link {
            z-index: 3;
            color: #fff;
            background-color: #3498db;
            border-color: #3498db;
        }

        .page-item.disabled .page-link {
            color: #6c757d;
            pointer-events: none;
            cursor: not-allowed;
            background-color: #fff;
            border-color: #dee2e6;
        }

        .page-link:hover {
            z-index: 2;
            color: #0056b3;
            text-decoration: none;
            background-color: #e9ecef;
            border-color: #dee2e6;
        }

        .page-link:focus {
            z-index: 3;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }
    </style>

    <!-- Include SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <div class="content">
        <h1><i class="fas fa-boxes"></i> Daftar Barang Dijual</h1>

        <div class="table-container">
            <!-- Search Form -->
            <div class="mb-4">
                <form action="{{ route('barang-dijual.index') }}" method="GET" class="search-form">
                    <div class="input-group">
                        <input type="text" 
                               name="search" 
                               class="form-control" 
                               placeholder="Cari barang..." 
                               value="{{ $search ?? '' }}"
                               style="border-radius: 4px 0 0 4px; padding: 10px 15px;">
                        <button class="btn btn-primary" 
                                type="submit"
                                style="border-radius: 0 4px 4px 0;">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Stok</th>
                        <th>Harga Beli</th>
                        <th>Harga Jual</th>
                        <th>Tanggal Input</th>
                        @if(auth()->user()->role === 'admin') <th>Aksi</th>@endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($barangDijual as $index => $barang)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $barang->nama_barang }}</td>
                            <td>{{ $barang->stok }}</td>
                            <td>Rp {{ number_format($barang->harga_beli, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}</td>
                            <td>{{ $barang->created_at->format('d/m/Y H:i') }}</td>
                            @if(auth()->user()->role === 'admin')
                            <td>
                                  <div class="btn-group">
                                    <a href="{{ route('barang-dijual.edit', $barang->id) }}" class="btn btn-warning">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    {{-- <form id="formHapus{{ $barang->id }}" 
                                          action="{{ route('barang-dijual.destroy', $barang->id) }}" 
                                          method="POST" 
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger" 
                                                onclick="konfirmasiHapus({{ $barang->id }})">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form> --}}
                                  </div>
                               
                            </td> @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data barang</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            @if($barangDijual->hasPages())
                <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-3">
                    <div class="text-muted small">
                        Menampilkan {{ $barangDijual->firstItem() }}-{{ $barangDijual->lastItem() }} dari {{ $barangDijual->total() }} data
                    </div>
                    <nav aria-label="Page navigation">
                        <ul class="pagination mb-0">
                            <!-- Previous Page Link -->
                            @if ($barangDijual->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link">&laquo;</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $barangDijual->previousPageUrl() }}" rel="prev">&laquo;</a>
                                </li>
                            @endif

                            <!-- Pagination Elements -->
                            @foreach ($barangDijual->getUrlRange(1, $barangDijual->lastPage()) as $page => $url)
                                @if ($page == $barangDijual->currentPage())
                                    <li class="page-item active">
                                        <span class="page-link">{{ $page }}</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                    </li>
                                @endif
                            @endforeach

                            <!-- Next Page Link -->
                            @if ($barangDijual->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $barangDijual->nextPageUrl() }}" rel="next">&raquo;</a>
                                </li>
                            @else
                                <li class="page-item disabled">
                                    <span class="page-link">&raquo;</span>
                                </li>
                            @endif
                        </ul>
                    </nav>
                </div>
            @endif
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
                text: "Data barang akan dihapus!",
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
