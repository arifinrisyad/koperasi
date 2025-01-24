@extends('layouts.master')

@section('content')
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold text-success"><i class="fas fa-file-invoice"></i> Tambah Laporan Penjualan</h1>
        <div>
            <a href="{{ route('laporan-penjualan.index') }}" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="card shadow-lg border-0">
        <div class="card-header bg-success text-white d-flex align-items-center">
            <h5 class="mb-0"><i class="fas fa-clipboard-list me-2"></i> Form Tambah Laporan</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('laporan-penjualan.store') }}" method="POST" id="formLaporan">
                @csrf
                <input type="hidden" name="bulan" id="bulan_hidden">
                <input type="hidden" name="tahun" id="tahun_hidden">
                
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="bulan" class="form-label fw-semibold">
                            <i class="fas fa-calendar-alt"></i> Bulan
                        </label>
                        <select id="bulan" class="form-select" required>
                            <option value="">Pilih Bulan</option>
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}">{{ DateTime::createFromFormat('!m', $i)->format('F') }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="tahun" class="form-label fw-semibold">
                            <i class="fas fa-calendar"></i> Tahun
                        </label>
                        <select id="tahun" class="form-select" required>
                            <option value="">Pilih Tahun</option>
                            @php
                                $currentYear = date('Y');
                                $startYear = $currentYear - 5;
                                $endYear = $currentYear + 5;
                            @endphp
                            @for($year = $startYear; $year <= $endYear; $year++)
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
                        <input type="text" name="nama_barang" id="nama_barang" class="form-control" readonly required>
                    </div>
                    <div class="col-md-6">
                        <label for="jumlah" class="form-label fw-semibold">
                            <i class="fas fa-sort-numeric-up-alt"></i> Jumlah
                        </label>
                        <input type="number" name="jumlah" id="jumlah" class="form-control" readonly required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="total_harga" class="form-label fw-semibold">
                            <i class="fas fa-money-bill-wave"></i> Total Harga
                        </label>
                        <input type="number" name="total_harga" id="total_harga" class="form-control" readonly required>
                    </div>
                    <div class="col-md-4">
                        <label for="keuntungan" class="form-label fw-semibold">
                            <i class="fas fa-chart-line"></i> Keuntungan
                        </label>
                        <input type="number" name="keuntungan" id="keuntungan" class="form-control" readonly required>
                    </div>
                    <div class="col-md-4">
                        <label for="tanggal" class="form-label fw-semibold">
                            <i class="fas fa-calendar-day"></i> Tanggal
                        </label>
                        <input type="date" name="tanggal" id="tanggal" class="form-control" required>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-success px-4 py-2" id="submitBtn">
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
        // Add CSRF token to all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function formatCurrency(number) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(number);
        }

        function resetForm() {
            $('#nama_barang').val('');
            $('#jumlah').val('');
            $('#total_harga').val('');
            $('#keuntungan').val('');
            $('#tanggal').val('');
            $('#bulan_hidden').val('');
            $('#tahun_hidden').val('');
            $('#submitBtn').prop('disabled', true);
        }

        function fetchData() {
            var bulan = $('#bulan').val();
            var tahun = $('#tahun').val();
            
            if (!bulan || !tahun) {
                resetForm();
                return;
            }

            console.log('Fetching data for:', { bulan, tahun });

            // Set hidden fields
            $('#bulan_hidden').val(bulan);
            $('#tahun_hidden').val(tahun);
            
            // Disable submit button while loading
            $('#submitBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Loading...');
            
            $.ajax({
                url: "{{ route('laporan-penjualan.fetch') }}",
                type: 'POST',
                data: { bulan, tahun },
                dataType: 'json',
                success: function(response) {
                    console.log('Response:', response);
                    
                    if (response.error) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Perhatian',
                            text: response.error,
                            showConfirmButton: true,
                            position: 'center'
                        });
                        resetForm();
                        return;
                    }

                    // Fill form fields
                    $('#nama_barang').val(response.nama_barang || '');
                    $('#jumlah').val(response.jumlah || 0);
                    $('#total_harga').val(response.total_harga || 0);
                    $('#keuntungan').val(response.keuntungan || 0);
                    $('#tanggal').val(response.tanggal || '');
                    
                    // Enable submit button
                    $('#submitBtn').prop('disabled', false);

                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Data Berhasil Dimuat',
                        html: `
                            Total Penjualan: ${formatCurrency(response.total_harga || 0)}<br>
                            Total Keuntungan: ${formatCurrency(response.keuntungan || 0)}
                        `,
                        timer: 3000,
                        showConfirmButton: false,
                        position: 'center'
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error details:', {
                        status: xhr.status,
                        statusText: xhr.statusText,
                        responseText: xhr.responseText,
                        error: error
                    });

                    let errorMessage = 'Terjadi kesalahan saat mengambil data.';
                    
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMessage = xhr.responseJSON.error;
                    }

                    Swal.fire({
                        icon: 'info',
                        title: 'Informasi',
                        text: errorMessage,
                        showConfirmButton: true,
                        position: 'center'
                    });
                    
                    resetForm();
                    $('#submitBtn').prop('disabled', true).html('Simpan');
                },
                complete: function() {
                    $('#submitBtn').html('<i class="fas fa-save"></i> Simpan Laporan');
                }
            });
        }

        // Fetch data when month or year changes
        $('#bulan, #tahun').change(fetchData);

        // Handle form submission
        $('#formLaporan').on('submit', function(e) {
            e.preventDefault();
            
            if (!$('#bulan').val() || !$('#tahun').val()) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: 'Silakan pilih bulan dan tahun terlebih dahulu',
                    position: 'center'
                });
                return;
            }

            Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah Anda yakin ingin menyimpan laporan penjualan ini?',
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

    @if(session('success'))
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

    @if(session('error'))
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
