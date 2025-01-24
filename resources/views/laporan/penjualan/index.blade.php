@extends('layouts.master')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-chart-line text-primary"></i> Laporan Penjualan
        </h1>
        <div>
            <button class="btn btn-outline-primary me-2" onclick="printTable()">
                <i class="fas fa-print"></i> Cetak Laporan
            </button>
            <a href="{{ route('laporan-penjualan.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> Tambah Laporan
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Penjualan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($laporans->sum('total_harga'), 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Keuntungan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($laporans->sum('keuntungan'), 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Barang Terjual</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($laporans->sum('jumlah'), 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Laporan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $laporans->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-table me-2"></i>Daftar Laporan Penjualan
            </h6>
            <div class="d-flex align-items-center">
                <div class="input-group me-2" style="width: 250px;">
                    <input type="text" class="form-control" id="searchInput" placeholder="Cari laporan...">
                    <button class="btn btn-primary" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th class="text-center" style="width: 5%">No</th>
                            <th class="text-center" style="width: 12%">Bulan</th>
                            <th class="text-center" style="width: 20%">Nama Barang</th>
                            <th class="text-center" style="width: 8%">Jumlah</th>
                            <th class="text-center" style="width: 12%">Total Harga</th>
                            <th class="text-center" style="width: 12%">Keuntungan</th>
                            <th class="text-center" style="width: 15%">Tanggal</th>
                            <th class="text-center" style="width: 8%">User</th>
                            <th class="text-center" style="width: 8%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($laporans as $laporan)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ \Carbon\Carbon::parse($laporan->tanggal)->translatedFormat('F Y') }}</td>
                                <td>{{ $laporan->nama_barang }}</td>
                                <td class="text-center">{{ number_format($laporan->jumlah, 0, ',', '.') }}</td>
                                <td class="text-end">Rp {{ number_format($laporan->total_harga, 0, ',', '.') }}</td>
                                <td class="text-end">Rp {{ number_format($laporan->keuntungan, 0, ',', '.') }}</td>
                                <td class="text-center">{{ \Carbon\Carbon::parse($laporan->created_at)->setTimezone('Asia/Jakarta')->translatedFormat('d F Y H:i') }}</td>
                                <td class="text-center">
                                    <span class="badge bg-info">{{ $laporan->user->name }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="{{ route('laporan-penjualan.show', $laporan->id) }}" 
                                           class="btn btn-sm btn-info" 
                                           data-bs-toggle="tooltip" 
                                           title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-danger" 
                                                onclick="confirmDelete({{ $laporan->id }})"
                                                data-bs-toggle="tooltip" 
                                                title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <img src="https://cdn-icons-png.flaticon.com/512/7465/7465679.png" 
                                         alt="No Data" 
                                         style="width: 100px; height: 100px; opacity: 0.5">
                                    <p class="text-muted mt-3">Belum ada data laporan penjualan</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    @if ($laporans instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        Menampilkan {{ $laporans->firstItem() ?? 0 }} sampai {{ $laporans->lastItem() ?? 0 }} dari {{ $laporans->total() }} data
                    @else
                        Total: {{ $laporans->count() }} data
                    @endif
                </div>
                <div>
                    @if ($laporans instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        {{ $laporans->links('pagination::bootstrap-5') }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .border-left-primary { border-left: 4px solid #4e73df !important; }
    .border-left-success { border-left: 4px solid #1cc88a !important; }
    .border-left-info { border-left: 4px solid #36b9cc !important; }
    .border-left-warning { border-left: 4px solid #f6c23e !important; }
    
    .table > :not(caption) > * > * {
        padding: 0.75rem;
        vertical-align: middle;
    }
    
    .btn-group .btn {
        padding: 0.25rem 0.5rem;
    }
    
    .badge {
        font-weight: 500;
        padding: 0.5em 0.8em;
    }
    
    .card-header {
        background-color: #f8f9fc;
        border-bottom: 1px solid #e3e6f0;
    }

    /* Custom SweetAlert2 Styles */
    .custom-swal-container {
        z-index: 9999;
    }

    .custom-popup {
        font-size: 1em !important;
        border-radius: 20px !important;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
        background: linear-gradient(145deg, #ffffff, #f8f9fa) !important;
        border: 1px solid rgba(255,255,255,0.8) !important;
    }

    .swal2-backdrop-show {
        backdrop-filter: blur(5px);
    }

    .custom-swal-icon {
        transform: scale(1.1);
        margin: 2.5em auto 1em auto !important;
        border: none !important;
    }

    .custom-swal-icon.swal2-success {
        border: 0.25em solid #a5dc86 !important;
    }

    .custom-swal-icon.swal2-error {
        border: 0.25em solid #f27474 !important;
    }

    .custom-swal-title {
        font-size: 1.8em !important;
        font-weight: 600 !important;
        color: #2c3e50 !important;
        margin: 0.5em 0 !important;
        padding: 0 !important;
        letter-spacing: -0.5px !important;
        text-transform: uppercase !important;
        font-family: 'Segoe UI', sans-serif !important;
    }

    .custom-swal-content {
        font-size: 1.1em !important;
        color: #505c6d !important;
        margin: 1em 1.5em !important;
        line-height: 1.6 !important;
        font-family: 'Segoe UI', sans-serif !important;
    }

    .custom-swal-button {
        padding: 12px 30px !important;
        font-size: 1.1em !important;
        border-radius: 12px !important;
        font-weight: 500 !important;
        text-transform: uppercase !important;
        letter-spacing: 1px !important;
        transition: all 0.3s ease !important;
        box-shadow: 0 3px 12px rgba(0,0,0,0.1) !important;
    }

    .custom-swal-button:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2) !important;
    }

    .custom-swal-button:focus {
        box-shadow: 0 0 0 3px rgba(40,167,69,0.3) !important;
    }

    /* Animation */
    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translate3d(0, -20%, 0);
        }
        to {
            opacity: 1;
            transform: translate3d(0, 0, 0);
        }
    }

    .animated {
        animation-duration: 0.4s;
        animation-fill-mode: both;
        animation-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    }

    .fadeInDown {
        animation-name: fadeInDown;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
    
    // Search functionality
    $("#searchInput").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#dataTable tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    // Success and error notifications
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            timer: 3000,
            showConfirmButton: true,
            confirmButtonColor: '#28a745',
            position: 'center',
            customClass: {
                popup: 'animated fadeInDown custom-popup',
                container: 'custom-swal-container',
                title: 'custom-swal-title',
                htmlContainer: 'custom-swal-content',
                confirmButton: 'custom-swal-button',
                icon: 'custom-swal-icon'
            },
            width: '30em',
            padding: '1.5em',
            backdrop: `
                rgba(0,0,123,0.4)
                url("/images/nyan-cat.gif")
                left top
                no-repeat
            `
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: "{{ session('error') }}",
            showConfirmButton: true,
            confirmButtonColor: '#dc3545',
            position: 'center',
            customClass: {
                popup: 'animated fadeInDown custom-popup',
                container: 'custom-swal-container',
                title: 'custom-swal-title',
                htmlContainer: 'custom-swal-content',
                confirmButton: 'custom-swal-button',
                icon: 'custom-swal-icon'
            },
            width: '30em',
            padding: '1.5em',
            backdrop: `
                rgba(123,0,0,0.4)
                left top
                no-repeat
            `
        });
    @endif
});

// Delete confirmation
function confirmDelete(id) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data laporan akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
        }
    });
}

// Print functionality
function printTable() {
    window.print();
}
</script>

<!-- Hidden delete forms -->
@foreach($laporans as $laporan)
    <form id="delete-form-{{ $laporan->id }}" 
          action="{{ route('laporan-penjualan.destroy', $laporan->id) }}" 
          method="POST" 
          style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endforeach
@endpush
@endsection
