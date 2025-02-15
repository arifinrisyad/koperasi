@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        <div class="d-flex align-items-center">
            <div class="mr-3 text-center" style="margin-right: 20px;">
                <div id="digitalClock" 
                    class="h4 mb-0 text-primary font-weight-bold" 
                    style="font-size: 1.5rem; border: 1px solid #d1d3e2; border-radius: 8px; padding: 10px 15px; background-color: #f8f9fc; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                </div>
                <small class="text-gray-600" style="display: block; margin-top: 5px;">
                    {{ now()->format('l, d F Y') }}
                </small>
            </div>
            <a href="{{ route('report.generate') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-download fa-sm text-white-50"></i> Generate Report
            </a>
        </div>
    </div>
    

    <!-- Summary Cards -->
    <div class="row">
        <!-- Penjualan Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Penjualan (Bulan Ini)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalPenjualan }} Transaksi</div>
                            <div class="mt-2 text-xs text-gray-600">
                                <span class="mr-2"><i class="fas fa-arrow-up text-success"></i> {{ $performaPenjualan['hari_ini'] }} hari ini</span>
                                <span><i class="fas fa-calendar text-info"></i> {{ $performaPenjualan['minggu_ini'] }} minggu ini</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Pendapatan (Bulan Ini)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($performaPendapatan['bulan_ini'], 0, ',', '.') }}</div>
                            <div class="mt-2 text-xs text-gray-600">
                                <span class="mr-2"><i class="fas fa-arrow-up text-success"></i> Rp {{ number_format($performaPendapatan['hari_ini'], 0, ',', '.') }} hari ini</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stock Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Barang</div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $totalBarang }} Unit</div>
                                </div>
                            </div>
                            <div class="mt-2 text-xs text-gray-600">
                                <span class="text-warning">{{ count($barangMenipis) }} barang stok menipis</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Purchases Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pembelian (Bulan Ini)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalPembelian }} Transaksi</div>
                            <div class="mt-2 text-xs text-gray-600">
                                <span class="text-info">Total pengeluaran bulan ini</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-basket fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Weekly Sales Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Grafik </h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Filter:</div>
                            <a class="dropdown-item" href="#">7 Hari Terakhir</a>
                            <a class="dropdown-item" href="#">30 Hari Terakhir</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Download Report</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="weeklySalesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Low Stock Alert -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-exclamation-triangle text-warning mr-1"></i>
                        Stok Menipis
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Barang</th>
                                    <th width="100">Stok</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($barangMenipis as $barang)
                                <tr>
                                    <td>{{ $barang->nama_barang }}</td>
                                    <td class="text-center">
                                        <span class="badge badge-{{ $barang->stok <= 5 ? 'danger' : 'warning' }}">
                                            {{ $barang->stok }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" class="text-center">
                                        <i class="fas fa-check-circle text-success"></i>
                                        Semua stok dalam kondisi aman
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-history mr-1"></i>
                Transaksi Terbaru
            </h6>
            <a href="{{ route('penjualan.index') }}" class="btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-eye fa-sm text-white-50"></i> Lihat Semua
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Barang</th>
                            <th>Jumlah</th>
                            <th>Total</th>
                            <th>Keuntungan</th>
                            <th>User</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penjualanTerbaru as $penjualan)
                        <tr>
                            <td>{{ $penjualan->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ optional($penjualan->barangdijual)->nama_barang ?? 'Barang tidak ditemukan' }}</td>
                            <td class="text-center">{{ $penjualan->jumlah }}</td>
                            <td class="text-right">Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}</td>
                            <td class="text-right text-success">Rp {{ number_format($penjualan->keuntungan, 0, ',', '.') }}</td>
                            <td>{{ optional($penjualan->user)->name ?? 'User tidak ditemukan' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                <i class="fas fa-info-circle text-info"></i>
                                Belum ada transaksi
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    #digitalClock {
        font-family: 'Roboto Mono', monospace;
        font-size: 1.5rem;
        color: #4e73df;
        text-shadow: 0 0 10px rgba(78, 115, 223, 0.1);
    }
    
    .chart-area {
        position: relative;
        height: 320px;
        width: 100%;
    }
    
    .border-left-primary { border-left: .25rem solid #4e73df !important; }
    .border-left-success { border-left: .25rem solid #1cc88a !important; }
    .border-left-info { border-left: .25rem solid #36b9cc !important; }
    .border-left-warning { border-left: .25rem solid #f6c23e !important; }
    
    .text-xs { font-size: .7rem; }
    .text-gray-300 { color: #dddfeb !important; }
    .text-gray-600 { color: #858796 !important; }
    .text-gray-800 { color: #5a5c69 !important; }
    
    .shadow-sm { box-shadow: 0 .125rem .25rem 0 rgba(58,59,69,.2) !important; }
    .shadow { box-shadow: 0 .15rem 1.75rem 0 rgba(58,59,69,.15) !important; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/locale/id.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    moment.locale('id');
    
    // Data untuk grafik mingguan
    const weeklyData = @json($filledPenjualanMingguan);
    const labels = weeklyData.map(item => moment(item.tanggal).format('DD MMM'));
    const salesData = weeklyData.map(item => item.total_penjualan);
    const revenueData = weeklyData.map(item => item.total_pendapatan);
    const profitData = weeklyData.map(item => item.total_keuntungan);
    const purchaseData = weeklyData.map(item => item.total_pembelian);

    // Inisialisasi grafik mingguan
    const ctx = document.getElementById('weeklySalesChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Penjualan (Transaksi)',
                    data: salesData,
                    borderColor: '#4e73df',
                    backgroundColor: 'rgba(78, 115, 223, 0.05)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    yAxisID: 'y'
                },
                {
                    label: 'Pendapatan',
                    data: revenueData,
                    borderColor: '#1cc88a',
                    backgroundColor: 'rgba(28, 200, 138, 0.05)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    yAxisID: 'y1'
                },
                {
                    label: 'Keuntungan',
                    data: profitData,
                    borderColor: '#36b9cc',
                    backgroundColor: 'rgba(54, 185, 204, 0.05)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    yAxisID: 'y1'
                },
                {
                    label: 'Pembelian',
                    data: purchaseData,
                    borderColor: '#f6c23e',
                    backgroundColor: 'rgba(246, 194, 62, 0.05)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 15
                    }
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    padding: 10,
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.datasetIndex === 0) {
                                label += context.parsed.y + ' transaksi';
                            } else {
                                label += 'Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                            }
                            return label;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    }
                },
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Jumlah Transaksi'
                    },
                    grid: {
                        drawBorder: false,
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Rupiah'
                    },
                    grid: {
                        drawOnChartArea: false,
                    }
                }
            }
        }
    });
    
    // Inisialisasi jam digital
    const digitalClock = document.getElementById('digitalClock');
    function updateClock() {
        const now = new Date();
        const hours = now.getHours().toString().padStart(2, '0');
        const minutes = now.getMinutes().toString().padStart(2, '0');
        const seconds = now.getSeconds().toString().padStart(2, '0');
        digitalClock.textContent = `${hours}:${minutes}:${seconds}`;
    }
    updateClock();
    setInterval(updateClock, 1000);
});
</script>
@endpush@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        <div class="d-flex align-items-center">
            <div class="mr-3 text-center" style="margin-right: 20px;">
                <div id="digitalClock" 
                    class="h4 mb-0 text-primary font-weight-bold" 
                    style="font-size: 1.5rem; border: 1px solid #d1d3e2; border-radius: 8px; padding: 10px 15px; background-color: #f8f9fc; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                </div>
                <small class="text-gray-600" style="display: block; margin-top: 5px;">
                    {{ now()->format('l, d F Y') }}
                </small>
            </div>
            <a href="{{ route('report.generate') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-download fa-sm text-white-50"></i> Generate Report
            </a>
        </div>
    </div>
    

    <!-- Summary Cards -->
    <div class="row">
        <!-- Penjualan Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Penjualan (Bulan Ini)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalPenjualan }} Transaksi</div>
                            <div class="mt-2 text-xs text-gray-600">
                                <span class="mr-2"><i class="fas fa-arrow-up text-success"></i> {{ $performaPenjualan['hari_ini'] }} hari ini</span>
                                <span><i class="fas fa-calendar text-info"></i> {{ $performaPenjualan['minggu_ini'] }} minggu ini</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Pendapatan (Bulan Ini)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($performaPendapatan['bulan_ini'], 0, ',', '.') }}</div>
                            <div class="mt-2 text-xs text-gray-600">
                                <span class="mr-2"><i class="fas fa-arrow-up text-success"></i> Rp {{ number_format($performaPendapatan['hari_ini'], 0, ',', '.') }} hari ini</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stock Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Barang</div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $totalBarang }} Unit</div>
                                </div>
                            </div>
                            <div class="mt-2 text-xs text-gray-600">
                                <span class="text-warning">{{ count($barangMenipis) }} barang stok menipis</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Purchases Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pembelian (Bulan Ini)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalPembelian }} Transaksi</div>
                            <div class="mt-2 text-xs text-gray-600">
                                <span class="text-info">Total pengeluaran bulan ini</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-basket fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Weekly Sales Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Grafik </h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Filter:</div>
                            <a class="dropdown-item" href="#">7 Hari Terakhir</a>
                            <a class="dropdown-item" href="#">30 Hari Terakhir</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Download Report</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="weeklySalesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Low Stock Alert -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-exclamation-triangle text-warning mr-1"></i>
                        Stok Menipis
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Barang</th>
                                    <th width="100">Stok</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($barangMenipis as $barang)
                                <tr>
                                    <td>{{ $barang->nama_barang }}</td>
                                    <td class="text-center">
                                        <span class="badge badge-{{ $barang->stok <= 5 ? 'danger' : 'warning' }}">
                                            {{ $barang->stok }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" class="text-center">
                                        <i class="fas fa-check-circle text-success"></i>
                                        Semua stok dalam kondisi aman
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-history mr-1"></i>
                Transaksi Terbaru
            </h6>
            <a href="{{ route('penjualan.index') }}" class="btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-eye fa-sm text-white-50"></i> Lihat Semua
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Barang</th>
                            <th>Jumlah</th>
                            <th>Total</th>
                            <th>Keuntungan</th>
                            <th>User</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penjualanTerbaru as $penjualan)
                        <tr>
                            <td>{{ $penjualan->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ optional($penjualan->barangdijual)->nama_barang ?? 'Barang tidak ditemukan' }}</td>
                            <td class="text-center">{{ $penjualan->jumlah }}</td>
                            <td class="text-right">Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}</td>
                            <td class="text-right text-success">Rp {{ number_format($penjualan->keuntungan, 0, ',', '.') }}</td>
                            <td>{{ optional($penjualan->user)->name ?? 'User tidak ditemukan' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                <i class="fas fa-info-circle text-info"></i>
                                Belum ada transaksi
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    #digitalClock {
        font-family: 'Roboto Mono', monospace;
        font-size: 1.5rem;
        color: #4e73df;
        text-shadow: 0 0 10px rgba(78, 115, 223, 0.1);
    }
    
    .chart-area {
        position: relative;
        height: 320px;
        width: 100%;
    }
    
    .border-left-primary { border-left: .25rem solid #4e73df !important; }
    .border-left-success { border-left: .25rem solid #1cc88a !important; }
    .border-left-info { border-left: .25rem solid #36b9cc !important; }
    .border-left-warning { border-left: .25rem solid #f6c23e !important; }
    
    .text-xs { font-size: .7rem; }
    .text-gray-300 { color: #dddfeb !important; }
    .text-gray-600 { color: #858796 !important; }
    .text-gray-800 { color: #5a5c69 !important; }
    
    .shadow-sm { box-shadow: 0 .125rem .25rem 0 rgba(58,59,69,.2) !important; }
    .shadow { box-shadow: 0 .15rem 1.75rem 0 rgba(58,59,69,.15) !important; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/locale/id.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    moment.locale('id');
    
    // Data untuk grafik mingguan
    const weeklyData = @json($filledPenjualanMingguan);
    const labels = weeklyData.map(item => moment(item.tanggal).format('DD MMM'));
    const salesData = weeklyData.map(item => item.total_penjualan);
    const revenueData = weeklyData.map(item => item.total_pendapatan);
    const profitData = weeklyData.map(item => item.total_keuntungan);
    const purchaseData = weeklyData.map(item => item.total_pembelian);

    // Inisialisasi grafik mingguan
    const ctx = document.getElementById('weeklySalesChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Penjualan (Transaksi)',
                    data: salesData,
                    borderColor: '#4e73df',
                    backgroundColor: 'rgba(78, 115, 223, 0.05)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    yAxisID: 'y'
                },
                {
                    label: 'Pendapatan',
                    data: revenueData,
                    borderColor: '#1cc88a',
                    backgroundColor: 'rgba(28, 200, 138, 0.05)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    yAxisID: 'y1'
                },
                {
                    label: 'Keuntungan',
                    data: profitData,
                    borderColor: '#36b9cc',
                    backgroundColor: 'rgba(54, 185, 204, 0.05)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    yAxisID: 'y1'
                },
                {
                    label: 'Pembelian',
                    data: purchaseData,
                    borderColor: '#f6c23e',
                    backgroundColor: 'rgba(246, 194, 62, 0.05)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 15
                    }
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    padding: 10,
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.datasetIndex === 0) {
                                label += context.parsed.y + ' transaksi';
                            } else {
                                label += 'Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                            }
                            return label;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    }
                },
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Jumlah Transaksi'
                    },
                    grid: {
                        drawBorder: false,
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Rupiah'
                    },
                    grid: {
                        drawOnChartArea: false,
                    }
                }
            }
        }
    });
    
    // Inisialisasi jam digital
    const digitalClock = document.getElementById('digitalClock');
    function updateClock() {
        const now = new Date();
        const hours = now.getHours().toString().padStart(2, '0');
        const minutes = now.getMinutes().toString().padStart(2, '0');
        const seconds = now.getSeconds().toString().padStart(2, '0');
        digitalClock.textContent = `${hours}:${minutes}:${seconds}`;
    }
    updateClock();
    setInterval(updateClock, 1000);
});
</script>
@endpush