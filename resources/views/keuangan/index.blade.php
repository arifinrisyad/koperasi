@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Pengelolaan Keuangan</h1>
            <p class="text-muted small mb-0">Kelola pemasukan dan pengeluaran koperasi</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('keuangan.create') }}" class="btn btn-primary btn-sm shadow-sm">
                <i class="fas fa-plus fa-sm me-1"></i> Tambah Transaksi
            </a>
            <a href="{{ route('keuangan.export') }}" class="btn btn-success btn-sm shadow-sm" target="_blank">
                <i class="fas fa-file-export fa-sm me-1"></i> Export Data
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs text-muted text-uppercase mb-1">Total Pemasukan</div>
                            <div class="h3 mb-0 text-success">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="rounded-circle bg-success bg-opacity-10 p-3">
                                <i class="fas fa-arrow-up text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs text-muted text-uppercase mb-1">Total Pengeluaran</div>
                            <div class="h3 mb-0 text-danger">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="rounded-circle bg-danger bg-opacity-10 p-3">
                                <i class="fas fa-arrow-down text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs text-muted text-uppercase mb-1">Saldo Akhir</div>
                            <div class="h3 mb-0 {{ $saldoAkhir >= 0 ? 'text-success' : 'text-danger' }}">
                                Rp {{ number_format(abs($saldoAkhir), 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                                <i class="fas fa-wallet text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('keuangan.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small">Jenis Transaksi</label>
                    <select name="jenis" class="form-select">
                        <option value="">Semua</option>
                        <option value="pemasukan" {{ request('jenis', '') == 'pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                        <option value="pengeluaran" {{ request('jenis', '') == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Kategori</label>
                    <select name="kategori" class="form-select">
                        <option value="">Semua</option>
                        @foreach($kategoriList as $kategori)
                            <option value="{{ $kategori }}" {{ request('kategori', '') == $kategori ? 'selected' : '' }}>
                                {{ $kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Tanggal Mulai</label>
                    <input type="date" class="form-control" name="start_date" value="{{ request('start_date', '') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Tanggal Akhir</label>
                    <input type="date" class="form-control" name="end_date" value="{{ request('end_date', '') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-filter fa-sm me-1"></i> Filter
                    </button>
                    <a href="{{ route('keuangan.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-undo fa-sm"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4">Tanggal</th>
                            <th>Kategori</th>
                            <th>Keterangan</th>
                            <th class="text-end">Jumlah</th>
                            <th>Sumber</th>
                            <th>Bukti</th>
                            <th>User</th>
                            <th class="text-center px-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($keuangans as $keuangan)
                            <tr>
                                <td class="px-4">{{ $keuangan->tanggal->format('d/m/Y') }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-circle fa-xs me-2 text-{{ $keuangan->status_color }}"></i>
                                        <span>{{ $keuangan->kategori }}</span>
                                    </div>
                                </td>
                                <td>{{ str_replace('#', '', $keuangan->keterangan) ?? '-' }}</td>
                                <td class="text-end fw-bold text-{{ $keuangan->status_color }}">
                                    {{ $keuangan->formatted_jumlah }}
                                </td>
                                <td>
                                    <span class="badge bg-{{ $keuangan->referensi_type ? 'info' : 'secondary' }}">
                                        {{ $keuangan->sumber_transaksi }}
                                    </span>
                                </td>
                                <td>
                                    @if($keuangan->bukti_transaksi)
                                        <a href="{{ Storage::url($keuangan->bukti_transaksi) }}" 
                                           class="btn btn-outline-secondary btn-sm"
                                           target="_blank">
                                            <i class="fas fa-image fa-sm"></i>
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-gray-200 p-2 me-2">
                                            <i class="fas fa-user fa-sm text-gray-600"></i>
                                        </div>
                                        <span>{{ $keuangan->user->name }}</span>
                                    </div>
                                </td>
                                <td class="text-center px-4">
                                    <div class="d-flex gap-1 justify-content-center">
                                        <a href="{{ route('keuangan.show', $keuangan->id) }}" 
                                           class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye fa-sm"></i>
                                        </a>
                                        <a href="{{ route('keuangan.edit', $keuangan->id) }}" 
                                           class="btn btn-outline-info btn-sm">
                                            <i class="fas fa-edit fa-sm"></i>
                                        </a>
                                        <form action="{{ route('keuangan.destroy', $keuangan->id) }}" 
                                              method="POST" 
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-outline-danger btn-sm"
                                                    onclick="konfirmasiHapus(event)">
                                                <i class="fas fa-trash fa-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Tidak ada data transaksi
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($keuangans->hasPages())
                <div class="d-flex justify-content-end p-4 border-top">
                    {{ $keuangans->links() }}
                </div>
            @endif
        </div>
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

    // Konfirmasi hapus data
    function konfirmasiHapus(event) {
        event.preventDefault();
        const form = event.target.closest('form');
        
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data keuangan akan dihapus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e74c3c',
            cancelButtonColor: '#95a5a6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }
</script>
@endsection
