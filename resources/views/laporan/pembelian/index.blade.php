@extends('layouts.master')

@section('content')
    <div class="container-fluid px-4">
        <!-- Header Section -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-shopping-basket text-primary"></i> Laporan Pembelian
            </h1>
            <div>
                <button class="btn btn-outline-primary me-2" onclick="printTable()">
                    <i class="fas fa-print"></i> Cetak Laporan
                </button>
                <a href="{{ route('laporan-pembelian.create') }}" class="btn btn-primary">
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
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Pembelian</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">Rp
                                    {{ number_format($laporans->sum('total_harga'), 0, ',', '.') }}</div>
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
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Barang</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($laporans->sum('jumlah'), 0, ',', '.') }} Unit</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-box fa-2x text-gray-300"></i>
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
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Rata-rata Pembelian
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">Rp
                                    {{ number_format($laporans->avg('total_harga'), 0, ',', '.') }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-chart-bar fa-2x text-gray-300"></i>
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
                    <i class="fas fa-table me-2"></i>Daftar Laporan Pembelian
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
                                <th class="text-center" style="width: 25%">Nama Barang</th>
                                <th class="text-center" style="width: 10%">Jumlah</th>
                                <th class="text-center" style="width: 15%">Total Harga</th>
                                <th class="text-center" style="width: 15%">Tanggal</th>
                                <th class="text-center" style="width: 15%">User</th>
                                <th class="text-center" style="width: 15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($laporans as $laporan)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $laporan->nama_barang }}</td>
                                    <td class="text-center">{{ number_format($laporan->jumlah, 0, ',', '.') }}</td>
                                    <td class="text-end">Rp {{ number_format($laporan->total_harga, 0, ',', '.') }}</td>
                                    <td class="text-center">
                                        {{ \Carbon\Carbon::parse($laporan->tanggal)->translatedFormat('d F Y') }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-info">{{ $laporan->user->name }}</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('laporan-pembelian.show', $laporan->id) }}"
                                                class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger"
                                                onclick="confirmDelete({{ $laporan->id }})" data-bs-toggle="tooltip"
                                                title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <img src="https://cdn-icons-png.flaticon.com/512/7465/7465679.png" alt="No Data"
                                            style="width: 100px; height: 100px; opacity: 0.5">
                                        <p class="text-muted mt-3">Belum ada data laporan pembelian</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        @if ($laporans instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            Menampilkan {{ $laporans->firstItem() ?? 0 }} sampai {{ $laporans->lastItem() ?? 0 }} dari
                            {{ $laporans->total() }} data
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
            .border-left-primary {
                border-left: 4px solid #4e73df !important;
            }

            .border-left-success {
                border-left: 4px solid #1cc88a !important;
            }

            .border-left-info {
                border-left: 4px solid #36b9cc !important;
            }

            .border-left-warning {
                border-left: 4px solid #f6c23e !important;
            }

            .table> :not(caption)>*>* {
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
                border-radius: 24px !important;
                box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2) !important;
                background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%) !important;
                border: 2px solid rgba(255, 255, 255, 0.9) !important;
                backdrop-filter: blur(10px) !important;
                -webkit-backdrop-filter: blur(10px) !important;
            }

            .swal2-backdrop-show {
                backdrop-filter: blur(8px);
                background: rgba(0, 0, 0, 0.4) !important;
            }

            .custom-swal-icon {
                transform: scale(1.2);
                margin: 2.5em auto 1em auto !important;
                border: none !important;
                position: relative !important;
            }

            .custom-swal-icon.swal2-success {
                border: 0.25em solid rgba(165, 220, 134, 0.3) !important;
                box-shadow: 0 0 20px rgba(165, 220, 134, 0.2) !important;
            }

            .custom-swal-icon.swal2-error {
                border: 0.25em solid rgba(242, 116, 116, 0.3) !important;
                box-shadow: 0 0 20px rgba(242, 116, 116, 0.2) !important;
            }

            .custom-swal-title {
                font-size: 2em !important;
                font-weight: 700 !important;
                background: linear-gradient(45deg, #2c3e50, #3498db) !important;
                -webkit-background-clip: text !important;
                -webkit-text-fill-color: transparent !important;
                margin: 0.5em 0 !important;
                padding: 0 !important;
                letter-spacing: -0.5px !important;
                text-transform: uppercase !important;
                font-family: 'Segoe UI', sans-serif !important;
            }

            .custom-swal-content {
                font-size: 1.15em !important;
                color: #505c6d !important;
                margin: 1em 1.5em !important;
                line-height: 1.6 !important;
                font-family: 'Segoe UI', sans-serif !important;
                font-weight: 400 !important;
            }

            .custom-swal-button {
                padding: 12px 35px !important;
                font-size: 1.1em !important;
                border-radius: 50px !important;
                font-weight: 600 !important;
                text-transform: uppercase !important;
                letter-spacing: 1.5px !important;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
                background: linear-gradient(45deg, #28a745, #20c997) !important;
                border: none !important;
                box-shadow: 0 4px 15px rgba(40, 167, 69, 0.2) !important;
            }

            .custom-swal-button:hover {
                transform: translateY(-3px) scale(1.02) !important;
                box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3) !important;
                background: linear-gradient(45deg, #2ebd4e, #23e2ab) !important;
            }

            .custom-swal-button:focus {
                box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.3) !important;
            }

            /* Animation */
            @keyframes fadeInDown {
                from {
                    opacity: 0;
                    transform: translate3d(0, -30%, 0) scale(0.95);
                }

                to {
                    opacity: 1;
                    transform: translate3d(0, 0, 0) scale(1);
                }
            }

            @keyframes fadeOutUp {
                from {
                    opacity: 1;
                    transform: translate3d(0, 0, 0) scale(1);
                }

                to {
                    opacity: 0;
                    transform: translate3d(0, -30%, 0) scale(0.95);
                }
            }

            .animated {
                animation-duration: 0.5s;
                animation-fill-mode: both;
                animation-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            }

            .fadeInDown {
                animation-name: fadeInDown;
            }

            .fadeOutUp {
                animation-name: fadeOutUp;
            }

            .faster {
                animation-duration: 0.3s;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            $(document).ready(function() {
                // Initialize tooltips
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
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
                @if (session('success'))
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
                        width: '32em',
                        padding: '2em',
                        backdrop: `
                rgba(0,0,123,0.4)
                url("data:image/svg+xml,%3Csvg width='80' height='80' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M21.184 20c.357-.13.72-.264 1.088-.402l1.768-.661C33.64 15.347 39.647 14 50 14c10.271 0 15.362 1.222 24.629 4.928.955.383 1.869.74 2.75 1.072h6.225c-2.51-.73-5.139-1.691-8.233-2.928C65.888 13.278 60.562 12 50 12c-10.626 0-16.855 1.397-26.66 5.063l-1.767.662c-2.475.923-4.66 1.674-6.724 2.275h6.335zm0-20C13.258 2.892 8.077 4 0 4V2c5.744 0 9.951-.574 14.85-2h6.334zM77.38 0C85.239 2.966 90.502 4 100 4V2c-6.842 0-11.386-.542-16.396-2h-6.225zM0 14c8.44 0 13.718-1.21 22.272-4.402l1.768-.661C33.64 5.347 39.647 4 50 4c10.271 0 15.362 1.222 24.629 4.928C84.112 12.722 89.438 14 100 14v-2c-10.271 0-15.362-1.222-24.629-4.928C65.888 3.278 60.562 2 50 2 39.374 2 33.145 3.397 23.34 7.063l-1.767.662C13.223 10.84 8.163 12 0 12v2z' fill='%239C92AC' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E")
                fixed
            `,
                        showClass: {
                            popup: 'animated fadeInDown faster'
                        },
                        hideClass: {
                            popup: 'animated fadeOutUp faster'
                        }
                    });
                @endif

                @if (session('error'))
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
                        width: '32em',
                        padding: '2em',
                        backdrop: `
                rgba(123,0,0,0.4)
                url("data:image/svg+xml,%3Csvg width='80' height='80' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M21.184 20c.357-.13.72-.264 1.088-.402l1.768-.661C33.64 15.347 39.647 14 50 14c10.271 0 15.362 1.222 24.629 4.928.955.383 1.869.74 2.75 1.072h6.225c-2.51-.73-5.139-1.691-8.233-2.928C65.888 13.278 60.562 12 50 12c-10.626 0-16.855 1.397-26.66 5.063l-1.767.662c-2.475.923-4.66 1.674-6.724 2.275h6.335zm0-20C13.258 2.892 8.077 4 0 4V2c5.744 0 9.951-.574 14.85-2h6.334zM77.38 0C85.239 2.966 90.502 4 100 4V2c-6.842 0-11.386-.542-16.396-2h-6.225zM0 14c8.44 0 13.718-1.21 22.272-4.402l1.768-.661C33.64 5.347 39.647 4 50 4c10.271 0 15.362 1.222 24.629 4.928C84.112 12.722 89.438 14 100 14v-2c-10.271 0-15.362-1.222-24.629-4.928C65.888 3.278 60.562 2 50 2 39.374 2 33.145 3.397 23.34 7.063l-1.767.662C13.223 10.84 8.163 12 0 12v2z' fill='%239C92AC' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E")
                fixed
            `,
                        showClass: {
                            popup: 'animated fadeInDown faster'
                        },
                        hideClass: {
                            popup: 'animated fadeOutUp faster'
                        }
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
        @foreach ($laporans as $laporan)
            <form id="delete-form-{{ $laporan->id }}" action="{{ route('laporan-pembelian.destroy', $laporan->id) }}"
                method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>
        @endforeach
    @endpush
@endsection
