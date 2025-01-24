@extends('layouts.master')

@section('content')
    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="fw-bold text-success"><i class="fas fa-file-invoice"></i> Tambah Laporan Pembelian</h1>
            <div>
                {{-- <a href="{{ route('laporan-pembelian.cetak-pdf') }}" class="btn btn-danger me-2" target="_blank">
                <i class="fas fa-file-pdf"></i> Cetak PDF
            </a> --}}
                <a href="{{ route('laporan-pembelian.index') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <div class="card shadow-lg border-0">
            <div class="card-header bg-success text-white d-flex align-items-center">
                <h5 class="mb-0"><i class="fas fa-clipboard-list me-2"></i> Form Tambah Laporan</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('laporan-pembelian.store') }}" method="POST" id="formLaporan">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="bulan" class="form-label fw-semibold">
                                <i class="fas fa-calendar-alt"></i> Bulan
                            </label>
                            <select name="bulan" id="bulan" class="form-select" required>
                                <option value="">Pilih Bulan</option>
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}">
                                        {{ DateTime::createFromFormat('!m', $i)->format('F') }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="tahun" class="form-label fw-semibold">
                                <i class="fas fa-calendar"></i> Tahun
                            </label>
                            <select name="tahun" id="tahun" class="form-select" required>
                                <option value="">Pilih Tahun</option>
                                @php
                                    $currentYear = date('Y');
                                    $startYear = $currentYear - 5;
                                    $endYear = $currentYear + 5;
                                @endphp
                                @for ($year = $startYear; $year <= $endYear; $year++)
                                    <option value="{{ $year }}" {{ $year == $currentYear ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="nama_barang" class="form-label fw-semibold">
                                <i class="fas fa-box"></i> Nama Barang
                            </label>
                            <input type="text" name="nama_barang" id="nama_barang" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="jumlah" class="form-label fw-semibold">
                                <i class="fas fa-sort-numeric-up-alt"></i> Jumlah
                            </label>
                            <input type="number" name="jumlah" id="jumlah" class="form-control" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="total_harga" class="form-label fw-semibold">
                                <i class="fas fa-money-bill-wave"></i> Total Harga
                            </label>
                            <input type="number" name="total_harga" id="total_harga" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="tanggal" class="form-label fw-semibold">
                                <i class="fas fa-calendar-day"></i> Tanggal
                            </label>
                            <input type="date" name="tanggal" id="tanggal" class="form-control" required>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-success px-4 py-2">
                            <i class="fas fa-save"></i> Simpan Laporan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Include SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#bulan').change(function() {
                var bulan = $(this).val();
                var tahun = $('#tahun').val();
                if (bulan && tahun) {
                    $.ajax({
                        url: "{{ route('laporan-pembelian.get-data-bulan') }}",
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            bulan: bulan,
                            tahun: tahun
                        },
                        success: function(response) {
                            console.log('Response:', response);
                            if (response.data) {
                                $('#nama_barang').val(response.data.nama_barang);
                                $('#jumlah').val(response.data.jumlah);
                                $('#total_harga').val(response.data.total_harga);
                                $('#tanggal').val(response.data.tanggal);

                                console.log('Data loaded:', {
                                    nama_barang: response.data.nama_barang,
                                    jumlah: response.data.jumlah,
                                    total_harga: response.data.total_harga,
                                    tanggal: response.data.tanggal
                                });
                            } else {
                                $('#nama_barang').val('');
                                $('#jumlah').val('');
                                $('#total_harga').val('');
                                $('#tanggal').val('');
                                console.log('No data found for selected month and year');
                            }
                        },
                        error: function(xhr) {
                            console.error('Error:', xhr.responseJSON);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: xhr.responseJSON?.message ||
                                    'Terjadi kesalahan saat mengambil data',
                                timer: 2000,
                                showConfirmButton: false,
                                position: 'center'
                            });

                            // Clear fields on error
                            $('#nama_barang').val('');
                            $('#jumlah').val('');
                            $('#total_harga').val('');
                            $('#tanggal').val('');
                        }
                    });
                }
            });

            $('#tahun').change(function() {
                $('#bulan').trigger('change');
            });

            $('#formLaporan').on('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Konfirmasi',
                    text: 'Apakah Anda yakin ingin menyimpan laporan pembelian ini?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Simpan',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    position: 'center'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        });

        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                showConfirmButton: true,
                confirmButtonText: 'OK',
                confirmButtonColor: '#28a745',
                position: 'center'
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session('error') }}',
                showConfirmButton: true,
                confirmButtonText: 'OK',
                confirmButtonColor: '#dc3545',
                position: 'center'
            });
        @endif
    </script>
@endsection
