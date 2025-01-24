@extends('layouts.master')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-cart-plus me-4"></i>Tambah Penjualan</h4>
                </div>

                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('penjualan.store') }}" method="POST" id="penjualanForm">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="barang_dijuals_id" class="form-label">
                                <i class="bi bi-box-seam me-1"></i> Pilih Barang
                            </label>
                            <select name="barang_dijuals_id" id="barang_dijuals_id" 
                                    class="form-select @error('barang_dijuals_id') is-invalid @enderror" required>
                                <option value="">Pilih Barang</option>
                                @foreach($barangs as $barang)
                                    <option value="{{ $barang->id }}" 
                                            data-stok="{{ $barang->stok }}"
                                            data-harga="{{ $barang->harga_jual }}">
                                        {{ $barang->nama_barang }} (Stok: {{ $barang->stok }}) - Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                            @error('barang_dijuals_id')
                                <div class="invalid-feedback">
                                    <i class="bi bi-info-circle me-2"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="jumlah" class="form-label">
                                <i class="bi bi-bag-plus me-1"></i> Jumlah
                            </label>
                            <input type="number" name="jumlah" id="jumlah" 
                                   class="form-control @error('jumlah') is-invalid @enderror" 
                                   required min="1" onchange="checkStock(this.value)" 
                                   data-bs-toggle="tooltip" data-bs-placement="top" title="Masukkan jumlah barang yang ingin dijual">
                            @error('jumlah')
                                <div class="invalid-feedback">
                                    <i class="bi bi-info-circle me-2"></i>{{ $message }}
                                </div>
                            @enderror
                            <small id="stokWarning" class="text-danger" style="display: none;">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i> Jumlah melebihi stok yang tersedia!
                            </small>
                        </div>

                        <div class="form-group mb-3">
                            <label for="tanggal" class="form-label">
                                <i class="bi bi-calendar3 me-1"></i> Tanggal
                            </label>
                            <input type="date" name="tanggal" id="tanggal" 
                                   class="form-control @error('tanggal') is-invalid @enderror" 
                                   value="{{ date('Y-m-d') }}" required>
                            @error('tanggal')
                                <div class="invalid-feedback">
                                    <i class="bi bi-info-circle me-2"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label>
                                <i class="bi bi-cash me-1"></i> Total Harga
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-cash-stack"></i></span>
                                <input type="text" id="total_harga" class="form-control" readonly>
                            </div>
                        </div>

                        <div class="form-group text-end">
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="bi bi-save me-2"></i> Simpan Penjualan
                            </button>
                            <a href="{{ route('penjualan.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i> Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('penjualanForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i> Menyimpan...';
    
    this.submit();
});

function checkStock(jumlah) {
    const select = document.getElementById('barang_dijuals_id');
    const option = select.selectedOptions[0];
    const stok = parseInt(option.getAttribute('data-stok'));
    const warning = document.getElementById('stokWarning');
    const submitBtn = document.getElementById('submitBtn');
    
    if (parseInt(jumlah) > stok) {
        warning.style.display = 'block';
        warning.textContent = `Jumlah melebihi stok yang tersedia! (Stok: ${stok})`;
        submitBtn.disabled = true;
    } else {
        warning.style.display = 'none';
        submitBtn.disabled = false;
    }
    
    // Update total harga
    const harga = parseFloat(option.getAttribute('data-harga'));
    const total = harga * parseInt(jumlah || 0);
    document.getElementById('total_harga').value = new Intl.NumberFormat('id-ID').format(total);
}

document.getElementById('barang_dijuals_id').addEventListener('change', function() {
    const jumlah = document.getElementById('jumlah').value;
    if (jumlah) {
        checkStock(jumlah);
    } else {
        document.getElementById('total_harga').value = '';
    }
});

// Initialize Bootstrap tooltips
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
});
</script>
@endsection
