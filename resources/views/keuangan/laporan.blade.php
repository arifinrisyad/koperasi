@extends('layouts.master')

@section('title', 'Laporan Keuangan')

@push('styles')
<style>
    .small-box {
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        color: white;
    }
    .small-box .inner {
        padding: 10px;
    }
    .small-box .icon {
        position: absolute;
        right: 20px;
        top: 20px;
        font-size: 40px;
        opacity: 0.3;
    }
    .small-box h3 {
        font-size: 24px;
        margin: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .small-box p {
        margin: 0;
    }
    .bg-success { background-color: #28a745 !important; }
    .bg-danger { background-color: #dc3545 !important; }
    .bg-info { background-color: #17a2b8 !important; }
    .bg-warning { background-color: #ffc107 !important; }
    
    .financial-statement {
        background: #fff;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
    }
    .financial-statement table {
        width: 100%;
    }
    .financial-statement th {
        background: #f4f6f9;
    }
    .financial-statement td, .financial-statement th {
        padding: 12px;
    }
    .ratio-card {
        background: #fff;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 15px;
        box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-2">
        <div class="col-12">
            <h1>Laporan Keuangan</h1>
            <div class="card">
                <div class="card-header">
                    <form action="{{ route('keuangan.laporan') }}" method="GET" class="form-inline">
                        <div class="input-group mr-2">
                            <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                        </div>
                        <div class="input-group mr-2">
                            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                        </div>
                        <button type="submit" class="btn btn-primary mr-2">Filter</button>
                        <a href="{{ route('keuangan.export-laporan') }}?start_date={{ request('start_date') }}&end_date={{ request('end_date') }}" 
                           class="btn btn-success">
                            <i class="fas fa-file-excel"></i> Export Excel
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</h3>
                    <p>Total Pemasukan</p>
                </div>
                <div class="icon">
                    <i class="fas fa-arrow-up"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h3>
                    <p>Total Pengeluaran</p>
                </div>
                <div class="icon">
                    <i class="fas fa-arrow-down"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</h3>
                    <p>Saldo Akhir</p>
                </div>
                <div class="icon">
                    <i class="fas fa-wallet"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $profitMargin }}%</h3>
                    <p>Margin Keuntungan</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Laporan Laba Rugi -->
        <div class="col-md-6">
            <div class="financial-statement">
                <h4 class="mb-4">Laporan Laba Rugi</h4>
                <table class="table table-bordered">
                    <tr>
                        <th colspan="2">Pendapatan</th>
                    </tr>
                    @foreach($pemasukanPerKategori as $pemasukan)
                    <tr>
                        <td>{{ $pemasukan->kategori }}</td>
                        <td class="text-right">Rp {{ number_format($pemasukan->total, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                    <tr class="table-success">
                        <th>Total Pendapatan</th>
                        <th class="text-right">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</th>
                    </tr>
                    <tr>
                        <th colspan="2">Pengeluaran</th>
                    </tr>
                    @foreach($pengeluaranPerKategori as $pengeluaran)
                    <tr>
                        <td>{{ $pengeluaran->kategori }}</td>
                        <td class="text-right">Rp {{ number_format($pengeluaran->total, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                    <tr class="table-danger">
                        <th>Total Pengeluaran</th>
                        <th class="text-right">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</th>
                    </tr>
                    <tr class="table-info">
                        <th>Laba/Rugi Bersih</th>
                        <th class="text-right">Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</th>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Analisis Keuangan -->
        <div class="col-md-6">
            <div class="financial-statement">
                <h4 class="mb-4">Analisis Keuangan</h4>
                <div class="row">
                    <div class="col-md-6">
                        <div class="ratio-card">
                            <h6>Current Ratio</h6>
                            <h4>{{ $currentRatio }}%</h4>
                            <small class="text-muted"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="ratio-card">
                            <h6>Profit Margin</h6>
                            <h4>{{ $profitMargin }}%</h4>
                            <small class="text-muted"></small>
                        </div>
                    </div>
                </div>
                
                <!-- Quarterly Comparison -->
                <h5 class="mt-4 mb-3">Perbandingan Kuartal</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Kuartal</th>
                            <th>Pemasukan</th>
                            <th>Pengeluaran</th>
                            <th>Profit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($quarterlyData as $quarter => $data)
                        <tr>
                            <td>Q{{ $quarter }}</td>
                            <td>Rp {{ number_format($data['pemasukan'], 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($data['pengeluaran'], 0, ',', '.') }}</td>
                            <td class="{{ $data['profit'] >= 0 ? 'text-success' : 'text-danger' }}">
                                Rp {{ number_format($data['profit'], 0, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Grafik Trend -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Trend Keuangan Bulanan</h5>
                </div>
                <div class="card-body">
                    <canvas id="trendChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Transaksi Terbaru</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Kategori</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentTransactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->tanggal->format('d/m/Y') }}</td>
                                    <td>{{ $transaction->kategori }}</td>
                                    <td class="{{ $transaction->jenis === 'pemasukan' ? 'text-success' : 'text-danger' }}">
                                        Rp {{ number_format($transaction->jumlah, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Data untuk grafik trend bulanan
    const trendLabels = [];
    const pemasukanValues = [];
    const pengeluaranValues = [];

    @foreach($trendBulanan as $tahun => $bulanData)
        @foreach($bulanData as $bulan => $jenisData)
            trendLabels.push('{{ $tahun }}-{{ str_pad($bulan, 2, "0", STR_PAD_LEFT) }}');
            let pemasukan = 0;
            let pengeluaran = 0;
            
            @foreach($jenisData as $data)
                @if($data->jenis === 'pemasukan')
                    pemasukan = {{ $data->total }};
                @else
                    pengeluaran = {{ $data->total }};
                @endif
            @endforeach
            
            pemasukanValues.push(pemasukan);
            pengeluaranValues.push(pengeluaran);
        @endforeach
    @endforeach

    // Membuat grafik trend
    new Chart(document.getElementById('trendChart'), {
        type: 'line',
        data: {
            labels: trendLabels,
            datasets: [
                {
                    label: 'Pemasukan',
                    data: pemasukanValues,
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    fill: true
                },
                {
                    label: 'Pengeluaran',
                    data: pengeluaranValues,
                    borderColor: '#dc3545',
                    backgroundColor: 'rgba(220, 53, 69, 0.1)',
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Trend Keuangan Bulanan'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
