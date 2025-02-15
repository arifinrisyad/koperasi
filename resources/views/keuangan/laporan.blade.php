@extends('layouts.master')

@section('title', 'Laporan Keuangan')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    body {
        font-family: 'Inter', sans-serif;
        background: #f8fafc;
    }
    
    /* Page Header */
    .page-header {
        background: linear-gradient(135deg, #fff, #f8fafc);
        padding: 30px 0;
        margin-bottom: 30px;
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }
    .page-header h1 {
        font-size: 28px;
        font-weight: 700;
        color: #1a202c;
        margin: 0;
        letter-spacing: -0.5px;
    }
    .page-header p {
        color: #64748b;
        margin: 10px 0 0;
        font-size: 16px;
    }
    
    /* Filter Section */
    .filter-section {
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        margin-bottom: 30px;
        border: 1px solid rgba(0, 0, 0, 0.05);
        position: relative;
        overflow: hidden;
    }
    
    .filter-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #3490dc, #4c9aff);
    }
    
    .filter-header {
        padding: 20px 25px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .filter-header i {
        font-size: 20px;
        color: #3490dc;
    }
    
    .filter-header h5 {
        margin: 0;
        font-size: 16px;
        font-weight: 600;
        color: #1a202c;
    }
    
    .filter-body {
        padding: 25px;
    }
    
    .filter-form {
        display: flex;
        gap: 20px;
        align-items: center;
        flex-wrap: wrap;
    }
    
    .date-range-group {
        display: flex;
        gap: 15px;
        align-items: center;
        flex: 1;
        min-width: 300px;
    }
    
    .date-input-group {
        flex: 1;
        position: relative;
    }
    
    .date-input-group label {
        display: block;
        margin-bottom: 8px;
        font-size: 13px;
        font-weight: 500;
        color: #64748b;
    }
    
    .date-input-group .form-control {
        width: 100%;
        padding: 12px 15px;
        font-size: 14px;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        color: #1a202c;
        background: #f8fafc;
        transition: all 0.2s ease;
    }
    
    .date-input-group .form-control:focus {
        border-color: #3490dc;
        box-shadow: 0 0 0 3px rgba(52, 144, 220, 0.15);
        background: #fff;
    }
    
    .date-input-group .form-control:hover {
        border-color: #cbd5e1;
    }
    
    .button-group {
        display: flex;
        gap: 12px;
        align-items: center;
    }
    
    .btn {
        padding: 12px 24px;
        font-size: 14px;
        font-weight: 600;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        white-space: nowrap;
    }
    
    .btn i {
        font-size: 16px;
    }
    
    .btn-filter {
        background: linear-gradient(135deg, #3490dc, #4c9aff);
        color: #fff;
        border: none;
        box-shadow: 0 4px 6px rgba(52, 144, 220, 0.2);
    }
    
    .btn-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(52, 144, 220, 0.25);
    }
    
    .btn-export {
        background: linear-gradient(135deg, #059669, #10b981);
        color: #fff;
        border: none;
        box-shadow: 0 4px 6px rgba(5, 150, 105, 0.2);
    }
    
    .btn-export:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(5, 150, 105, 0.25);
    }
    
    .btn-export:disabled {
        background: linear-gradient(135deg, #9ca3af, #d1d5db);
        box-shadow: none;
        cursor: not-allowed;
        transform: none;
    }
    
    .btn-export:disabled:hover {
        transform: none;
        box-shadow: none;
    }
    
    /* Tooltip */
    .tooltip-wrapper {
        position: relative;
        display: inline-block;
    }
    
    .tooltip-wrapper[data-tooltip]:hover::after {
        content: attr(data-tooltip);
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        padding: 8px 12px;
        background: rgba(0, 0, 0, 0.8);
        color: #fff;
        font-size: 12px;
        border-radius: 6px;
        white-space: nowrap;
        z-index: 10;
        margin-bottom: 8px;
    }
    
    .tooltip-wrapper[data-tooltip]:hover::before {
        content: '';
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        border: 6px solid transparent;
        border-top-color: rgba(0, 0, 0, 0.8);
        margin-bottom: -4px;
    }
    
    @media (max-width: 768px) {
        .filter-form {
            flex-direction: column;
            gap: 15px;
        }
        
        .date-range-group {
            flex-direction: column;
            min-width: 100%;
        }
        
        .button-group {
            width: 100%;
            justify-content: stretch;
        }
        
        .btn {
            flex: 1;
            justify-content: center;
        }
    }
    
    /* Summary Cards */
    .small-box {
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 30px;
        color: white;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
    }
    .small-box::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(45deg, rgba(255,255,255,0.15), transparent);
        z-index: 1;
    }
    .small-box::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 50%;
        background: linear-gradient(to top, rgba(0,0,0,0.1), transparent);
        z-index: 1;
    }
    .small-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.12);
    }
    .small-box .inner {
        position: relative;
        z-index: 2;
        padding: 0;
    }
    .small-box h3 {
        font-size: 32px;
        font-weight: 700;
        margin: 0 0 10px;
        letter-spacing: -0.5px;
    }
    .small-box p {
        font-size: 16px;
        margin: 0;
        opacity: 0.9;
        font-weight: 500;
    }
    .small-box small {
        display: block;
        margin-top: 15px;
        font-size: 14px;
        opacity: 0.9;
        font-weight: 500;
    }
    .small-box .icon {
        position: absolute;
        right: 25px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 56px;
        opacity: 0.15;
        z-index: 1;
        transition: all 0.3s ease;
    }
    .small-box:hover .icon {
        transform: translateY(-50%) scale(1.1);
        opacity: 0.2;
    }
    
    /* Gradient Backgrounds */
    .bg-success { 
        background: linear-gradient(135deg, #0acf97, #02a8b5);
    }
    .bg-danger { 
        background: linear-gradient(135deg, #fa5c7c, #f77eb9);
    }
    .bg-info { 
        background: linear-gradient(135deg, #39afd1, #4c9aff);
    }
    .bg-warning { 
        background: linear-gradient(135deg, #ffbc00, #ffd91e);
    }
    
    /* Financial Statements */
    .financial-statement {
        background: #fff;
        padding: 30px;
        margin-bottom: 30px;
        border-radius: 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }
    .financial-statement:hover {
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.08);
    }
    .financial-statement h4 {
        color: #1a202c;
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f1f5f9;
        letter-spacing: -0.5px;
    }
    .financial-statement table {
        width: 100%;
        margin-bottom: 0;
    }
    .financial-statement th {
        background: #f8fafc;
        color: #1a202c;
        font-weight: 600;
        padding: 15px;
        font-size: 14px;
        border-bottom: 2px solid #e2e8f0;
    }
    .financial-statement td {
        padding: 15px;
        color: #4a5568;
        font-size: 14px;
        border-bottom: 1px solid #f1f5f9;
        transition: all 0.2s ease;
    }
    .financial-statement tr:hover td {
        background: #f8fafc;
    }
    .financial-statement .text-right {
        text-align: right;
    }
    .financial-statement .table-success th {
        background: #d1fae5;
        color: #065f46;
        border-bottom: none;
    }
    .financial-statement .table-danger th {
        background: #fee2e2;
        color: #991b1b;
        border-bottom: none;
    }
    .financial-statement .table-info th {
        background: #e0f2fe;
        color: #075985;
        border-bottom: none;
    }
    
    /* Chart Container */
    .chart-container {
        background: #fff;
        padding: 30px;
        border-radius: 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        margin-bottom: 30px;
        transition: all 0.3s ease;
    }
    .chart-container:hover {
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.08);
    }
    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }
    .chart-title {
        font-size: 20px;
        font-weight: 700;
        color: #1a202c;
        margin: 0;
        letter-spacing: -0.5px;
    }
    .chart-legend {
        display: flex;
        gap: 20px;
        align-items: center;
    }
    .legend-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        color: #64748b;
        font-weight: 500;
        transition: all 0.2s ease;
        padding: 8px 12px;
        border-radius: 8px;
    }
    .legend-item:hover {
        background: #f8fafc;
        transform: translateY(-1px);
    }
    .legend-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        transition: all 0.2s ease;
    }
    .legend-dot.income {
        background: #0acf97;
        box-shadow: 0 0 10px rgba(10, 207, 151, 0.3);
    }
    .legend-dot.expense {
        background: #fa5c7c;
        box-shadow: 0 0 10px rgba(250, 92, 124, 0.3);
    }
    
    /* Recent Transactions */
    .recent-transactions {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        transition: all 0.3s ease;
    }
    .recent-transactions:hover {
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.08);
    }
    .recent-transactions .card-header {
        padding: 20px 25px;
        background: transparent;
        border-bottom: 1px solid #f1f5f9;
    }
    .recent-transactions .card-title {
        font-size: 20px;
        font-weight: 700;
        color: #1a202c;
        margin: 0;
        letter-spacing: -0.5px;
    }
    .recent-transactions .table {
        margin: 0;
    }
    .recent-transactions th {
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
        padding: 15px 25px;
        background: #f8fafc;
        border-bottom: 2px solid #e2e8f0;
    }
    .recent-transactions td {
        padding: 15px 25px;
        font-size: 14px;
        color: #4a5568;
        border-bottom: 1px solid #f1f5f9;
        transition: all 0.2s ease;
    }
    .recent-transactions tr:hover td {
        background: #f8fafc;
    }
    .recent-transactions .text-success {
        color: #0acf97 !important;
        font-weight: 600;
    }
    .recent-transactions .text-danger {
        color: #fa5c7c !important;
        font-weight: 600;
    }
    
    /* Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .small-box, .financial-statement, .chart-container, .recent-transactions {
        animation: fadeInUp 0.5s ease-out;
    }
    
    /* Loading States */
    .loading {
        position: relative;
    }
    .loading::after {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255,255,255,0.8);
        backdrop-filter: blur(4px);
        border-radius: 20px;
        z-index: 1000;
    }
    .loading::before {
        content: "";
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 40px;
        height: 40px;
        border: 3px solid #f1f5f9;
        border-top: 3px solid #3490dc;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        z-index: 1001;
    }
    @keyframes spin {
        0% { transform: translate(-50%, -50%) rotate(0deg); }
        100% { transform: translate(-50%, -50%) rotate(360deg); }
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .page-header {
            padding: 20px 0;
        }
        .page-header h1 {
            font-size: 24px;
        }
        .small-box {
            padding: 20px;
            margin-bottom: 20px;
        }
        .small-box h3 {
            font-size: 24px;
        }
        .small-box .icon {
            font-size: 40px;
        }
        .financial-statement {
            padding: 20px;
        }
        .chart-container {
            padding: 20px;
        }
        .filter-form {
            flex-direction: column;
        }
        .filter-form .input-group {
            max-width: 100%;
        }
        .chart-header {
            flex-direction: column;
            gap: 15px;
            align-items: flex-start;
        }
        .chart-legend {
            flex-wrap: wrap;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-12">
                    <h1>Laporan Keuangan</h1>
                    <p>Ringkasan dan analisis keuangan untuk periode terpilih</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <div class="filter-header">
            <i class="fas fa-filter"></i>
            <h5>Filter Laporan</h5>
        </div>
        <div class="filter-body">
            <form action="{{ route('keuangan.laporan') }}" method="GET" class="filter-form">
                <div class="date-range-group">
                    <div class="date-input-group">
                        <label for="start_date">Tanggal Mulai</label>
                        <input type="date" id="start_date" name="start_date" class="form-control" value="{{ request('start_date') }}" required>
                    </div>
                    <div class="date-input-group">
                        <label for="end_date">Tanggal Akhir</label>
                        <input type="date" id="end_date" name="end_date" class="form-control" value="{{ request('end_date') }}" required>
                    </div>
                </div>
                <div class="button-group">
                    <button type="submit" class="btn btn-filter">
                        <i class="fas fa-filter"></i>
                        <span>Filter Data</span>
                    </button>
                    @if(request('start_date') && request('end_date'))
                        <a href="{{ route('keuangan.export-laporan', ['start_date' => request('start_date'), 'end_date' => request('end_date')]) }}" 
                           class="btn btn-export">
                            <i class="fas fa-file-excel"></i>
                            <span>Export Excel</span>
                        </a>
                    @else
                        <div class="tooltip-wrapper" data-tooltip="Pilih tanggal terlebih dahulu">
                            <button type="button" class="btn btn-export" disabled>
                                <i class="fas fa-file-excel"></i>
                                <span>Export Excel</span>
                            </button>
                        </div>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</h3>
                    <p>Total Pemasukan</p>
                    <small class="text-white">
                        @if($pemasukanGrowth > 0)
                            <i class="fas fa-arrow-up"></i>
                        @elseif($pemasukanGrowth < 0)
                            <i class="fas fa-arrow-down"></i>
                        @endif
                       
                    </small>
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
                    <small class="text-white">
                        @if($pengeluaranGrowth > 0)
                            <i class="fas fa-arrow-up"></i>
                        @elseif($pengeluaranGrowth < 0)
                            <i class="fas fa-arrow-down"></i>
                        @endif
                      
                    </small>
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
                    <small class="text-white">
                        Rata-rata harian: Rp {{ number_format($dailyAverages['pemasukan'] - $dailyAverages['pengeluaran'], 0, ',', '.') }}
                    </small>
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
                    <small class="text-white">
                        Dari total pendapatan
                    </small>
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
                        <th colspan="3">Pendapatan</th>
                    </tr>
                    @foreach($pemasukanPerKategori as $pemasukan)
                    <tr>
                        <td>{{ $pemasukan->kategori }}</td>
                        <td class="text-right">Rp {{ number_format($pemasukan->total, 0, ',', '.') }}</td>
                        <td class="text-right">{{ $pemasukan->percentage }}%</td>
                    </tr>
                    @endforeach
                    <tr class="table-success">
                        <th>Total Pendapatan</th>
                        <th class="text-right">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</th>
                        <th class="text-right">100%</th>
                    </tr>
                    <tr>
                        <th colspan="3">Pengeluaran</th>
                    </tr>
                    @foreach($pengeluaranPerKategori as $pengeluaran)
                    <tr>
                        <td>{{ $pengeluaran->kategori }}</td>
                        <td class="text-right">Rp {{ number_format($pengeluaran->total, 0, ',', '.') }}</td>
                        <td class="text-right">{{ $pengeluaran->percentage }}%</td>
                    </tr>
                    @endforeach
                    <tr class="table-danger">
                        <th>Total Pengeluaran</th>
                        <th class="text-right">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</th>
                        <th class="text-right">100%</th>
                    </tr>
                    <tr class="table-info">
                        <th>Laba/Rugi Bersih</th>
                        <th class="text-right" colspan="2">Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</th>
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
                            <small class="text-muted">Rasio saldo terhadap pendapatan</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="ratio-card">
                            <h6>Profit Margin</h6>
                            <h4>{{ $profitMargin }}%</h4>
                            <small class="text-muted">Persentase laba dari pendapatan</small>
                        </div>
                    </div>
                </div>
                
                <!-- Rata-rata Harian -->
                <div class="mt-4">
                    <h5 class="mb-3">Rata-rata Harian</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="ratio-card">
                                <h6>Pemasukan Harian</h6>
                                <h4>Rp {{ number_format($dailyAverages['pemasukan'], 0, ',', '.') }}</h4>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="ratio-card">
                                <h6>Pengeluaran Harian</h6>
                                <h4>Rp {{ number_format($dailyAverages['pengeluaran'], 0, ',', '.') }}</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Top 5 Kategori -->
                <div class="mt-4">
                    <h5 class="mb-3">Top 5 Kategori</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-success">Pemasukan Tertinggi</h6>
                            <ul class="list-group">
                                @foreach($topCategories['pemasukan'] as $category)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $category->kategori }}
                                    <span>Rp {{ number_format($category->total, 0, ',', '.') }}</span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-danger">Pengeluaran Tertinggi</h6>
                            <ul class="list-group">
                                @foreach($topCategories['pengeluaran'] as $category)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $category->kategori }}
                                    <span>Rp {{ number_format($category->total, 0, ',', '.') }}</span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Grafik Trend -->
        <div class="col-md-8">
            <div class="chart-container">
                <div class="chart-header">
                    <h5 class="chart-title">Trend Keuangan Bulanan</h5>
                    <div class="chart-legend">
                        <div class="legend-item">
                            <span class="legend-dot income"></span>
                            <span>Pemasukan</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-dot expense"></span>
                            <span>Pengeluaran</span>
                        </div>
                    </div>
                </div>
                <div class="chart-wrapper">
                    <canvas id="trendChart"></canvas>
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
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
<script>
    // Data untuk grafik trend bulanan
    const trendLabels = [];
    const pemasukanValues = [];
    const pengeluaranValues = [];

    @foreach($trendBulanan as $tahun => $bulanData)
        @foreach($bulanData as $bulan => $jenisData)
            trendLabels.push('{{ date("M Y", strtotime($tahun."-".$bulan."-01")) }}');
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

    // Format angka ke format rupiah
    const formatRupiah = (angka) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(angka);
    };

    // Konfigurasi grafik
    const ctx = document.getElementById('trendChart').getContext('2d');
    
    // Gradient untuk pemasukan
    const incomeGradient = ctx.createLinearGradient(0, 0, 0, 400);
    incomeGradient.addColorStop(0, 'rgba(10, 207, 151, 0.25)');
    incomeGradient.addColorStop(1, 'rgba(10, 207, 151, 0)');
    
    // Gradient untuk pengeluaran
    const expenseGradient = ctx.createLinearGradient(0, 0, 0, 400);
    expenseGradient.addColorStop(0, 'rgba(250, 92, 124, 0.25)');
    expenseGradient.addColorStop(1, 'rgba(250, 92, 124, 0)');

    // Membuat grafik
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: trendLabels,
            datasets: [
                {
                    label: 'Pemasukan',
                    data: pemasukanValues,
                    borderColor: '#0acf97',
                    backgroundColor: incomeGradient,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.3,
                    pointRadius: 0,
                    pointHoverRadius: 6,
                    pointHoverBackgroundColor: '#0acf97',
                    pointHoverBorderColor: '#fff',
                    pointHoverBorderWidth: 2,
                    capBezierPoints: true
                },
                {
                    label: 'Pengeluaran',
                    data: pengeluaranValues,
                    borderColor: '#fa5c7c',
                    backgroundColor: expenseGradient,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.3,
                    pointRadius: 0,
                    pointHoverRadius: 6,
                    pointHoverBackgroundColor: '#fa5c7c',
                    pointHoverBorderColor: '#fff',
                    pointHoverBorderWidth: 2,
                    capBezierPoints: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index'
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    enabled: true,
                    mode: 'index',
                    intersect: false,
                    backgroundColor: 'rgba(255, 255, 255, 0.95)',
                    titleColor: '#2c3e50',
                    titleFont: {
                        size: 13,
                        weight: '600',
                        family: "'Inter', 'Helvetica', sans-serif"
                    },
                    bodyColor: '#64748b',
                    bodyFont: {
                        size: 12,
                        family: "'Inter', 'Helvetica', sans-serif"
                    },
                    borderColor: 'rgba(0,0,0,0.1)',
                    borderWidth: 1,
                    padding: 12,
                    boxPadding: 6,
                    usePointStyle: true,
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += formatRupiah(context.parsed.y);
                            return label;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        font: {
                            size: 12,
                            family: "'Inter', 'Helvetica', sans-serif"
                        },
                        color: '#64748b',
                        padding: 8
                    }
                },
                y: {
                    grid: {
                        borderDash: [5, 5],
                        drawBorder: false,
                        color: 'rgba(0,0,0,0.05)'
                    },
                    ticks: {
                        font: {
                            size: 12,
                            family: "'Inter', 'Helvetica', sans-serif"
                        },
                        color: '#64748b',
                        padding: 10,
                        callback: function(value) {
                            return formatRupiah(value);
                        }
                    }
                }
            },
            layout: {
                padding: {
                    top: 20,
                    right: 20,
                    bottom: 20,
                    left: 20
                }
            }
        }
    });
</script>
@endpush
