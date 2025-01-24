@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Arus Kas Tahun {{ $currentYear }}</h3>
                </div>
                <div class="card-body">
                    <!-- Chart -->
                    <div class="chart mb-4">
                        <canvas id="cashFlowChart" style="min-height: 300px;"></canvas>
                    </div>

                    <!-- Monthly Data Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Bulan</th>
                                    <th class="text-right">Pemasukan</th>
                                    <th class="text-right">Pengeluaran</th>
                                    <th class="text-right">Selisih</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $months = [
                                        1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
                                        4 => 'April', 5 => 'Mei', 6 => 'Juni',
                                        7 => 'Juli', 8 => 'Agustus', 9 => 'September',
                                        10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                    ];
                                    $totalPemasukan = 0;
                                    $totalPengeluaran = 0;
                                @endphp

                                @foreach($months as $monthNum => $monthName)
                                    @php
                                        $monthData = $monthlyData->firstWhere('month', $monthNum);
                                        $pemasukan = $monthData ? $monthData->total_pemasukan : 0;
                                        $pengeluaran = $monthData ? $monthData->total_pengeluaran : 0;
                                        $selisih = $pemasukan - $pengeluaran;
                                        $totalPemasukan += $pemasukan;
                                        $totalPengeluaran += $pengeluaran;
                                    @endphp
                                    <tr>
                                        <td>{{ $monthName }}</td>
                                        <td class="text-right">Rp {{ number_format($pemasukan, 0, ',', '.') }}</td>
                                        <td class="text-right">Rp {{ number_format($pengeluaran, 0, ',', '.') }}</td>
                                        <td class="text-right {{ $selisih >= 0 ? 'text-success' : 'text-danger' }}">
                                            Rp {{ number_format(abs($selisih), 0, ',', '.') }}
                                            {{ $selisih >= 0 ? '(+)' : '(-)' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="font-weight-bold">
                                    <td>Total</td>
                                    <td class="text-right">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</td>
                                    <td class="text-right">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
                                    <td class="text-right {{ ($totalPemasukan - $totalPengeluaran) >= 0 ? 'text-success' : 'text-danger' }}">
                                        Rp {{ number_format(abs($totalPemasukan - $totalPengeluaran), 0, ',', '.') }}
                                        {{ ($totalPemasukan - $totalPengeluaran) >= 0 ? '(+)' : '(-)' }}
                                    </td>
                                </tr>
                            </tfoot>
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
    const ctx = document.getElementById('cashFlowChart').getContext('2d');
    const monthNames = @json(array_values($months));
    const monthlyData = @json($monthlyData);
    
    const data = {
        labels: monthNames,
        datasets: [
            {
                label: 'Pemasukan',
                data: Array(12).fill(0).map((_, i) => {
                    const month = monthlyData.find(m => m.month === i + 1);
                    return month ? month.total_pemasukan : 0;
                }),
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1
            },
            {
                label: 'Pengeluaran',
                data: Array(12).fill(0).map((_, i) => {
                    const month = monthlyData.find(m => m.month === i + 1);
                    return month ? month.total_pengeluaran : 0;
                }),
                borderColor: 'rgb(255, 99, 132)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                tension: 0.1
            }
        ]
    };

    new Chart(ctx, {
        type: 'line',
        data: data,
        options: {
            responsive: true,
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
            interaction: {
                intersect: false,
                mode: 'index'
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
