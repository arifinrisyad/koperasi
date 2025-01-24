@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tren Keuangan (30 Hari Terakhir)</h3>
                </div>
                <div class="card-body">
                    <!-- Summary Cards -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-plus"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Pemasukan</span>
                                    <span class="info-box-number">Rp {{ number_format($totalIncome, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-danger"><i class="fas fa-minus"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Pengeluaran</span>
                                    <span class="info-box-number">Rp {{ number_format($totalExpense, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-chart-line"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Arus Kas Bersih</span>
                                    <span class="info-box-number">Rp {{ number_format($netCashFlow, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Chart -->
                    <div class="chart-container" style="position: relative; height:50vh; width:100%">
                        <canvas id="trendChart"></canvas>
                    </div>

                    <!-- Transactions Table -->
                    <div class="table-responsive mt-4">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th class="text-right">Pemasukan</th>
                                    <th class="text-right">Pengeluaran</th>
                                    <th class="text-right">Selisih</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($dailyData as $data)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($data->date)->format('d/m/Y') }}</td>
                                        <td class="text-right text-success">
                                            Rp {{ number_format($data->total_pemasukan, 0, ',', '.') }}
                                        </td>
                                        <td class="text-right text-danger">
                                            Rp {{ number_format($data->total_pengeluaran, 0, ',', '.') }}
                                        </td>
                                        <td class="text-right {{ $data->total_pemasukan - $data->total_pengeluaran >= 0 ? 'text-success' : 'text-danger' }}">
                                            Rp {{ number_format($data->total_pemasukan - $data->total_pengeluaran, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Tidak ada data untuk periode ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('trendChart').getContext('2d');
    
    const dates = @json($dailyData->pluck('date'));
    const income = @json($dailyData->pluck('total_pemasukan'));
    const expense = @json($dailyData->pluck('total_pengeluaran'));
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: dates.map(date => {
                const d = new Date(date);
                return d.toLocaleDateString('id-ID', { day: '2-digit', month: '2-digit' });
            }),
            datasets: [{
                label: 'Pemasukan',
                data: income,
                borderColor: 'rgb(40, 167, 69)',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                fill: true
            }, {
                label: 'Pengeluaran',
                data: expense,
                borderColor: 'rgb(220, 53, 69)',
                backgroundColor: 'rgba(220, 53, 69, 0.1)',
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': Rp ' + 
                                new Intl.NumberFormat('id-ID').format(context.raw);
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush
@endsection
