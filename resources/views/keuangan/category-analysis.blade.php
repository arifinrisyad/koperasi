@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Analisis Kategori Pemasukan</h3>
                </div>
                <div class="card-body">
                    <div class="chart mb-4">
                        <canvas id="incomeChart" style="min-height: 300px;"></canvas>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Kategori</th>
                                    <th class="text-right">Jumlah</th>
                                    <th class="text-right">Persentase</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalIncome = $income->sum('total');
                                @endphp
                                @foreach($income as $category)
                                    <tr>
                                        <td>{{ $category->kategori }}</td>
                                        <td class="text-right">Rp {{ number_format($category->total, 0, ',', '.') }}</td>
                                        <td class="text-right">
                                            {{ number_format(($category->total / $totalIncome) * 100, 1) }}%
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="font-weight-bold">
                                    <td>Total</td>
                                    <td class="text-right">Rp {{ number_format($totalIncome, 0, ',', '.') }}</td>
                                    <td class="text-right">100%</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Analisis Kategori Pengeluaran</h3>
                </div>
                <div class="card-body">
                    <div class="chart mb-4">
                        <canvas id="expenseChart" style="min-height: 300px;"></canvas>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Kategori</th>
                                    <th class="text-right">Jumlah</th>
                                    <th class="text-right">Persentase</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalExpense = $expenses->sum('total');
                                @endphp
                                @foreach($expenses as $category)
                                    <tr>
                                        <td>{{ $category->kategori }}</td>
                                        <td class="text-right">Rp {{ number_format($category->total, 0, ',', '.') }}</td>
                                        <td class="text-right">
                                            {{ number_format(($category->total / $totalExpense) * 100, 1) }}%
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="font-weight-bold">
                                    <td>Total</td>
                                    <td class="text-right">Rp {{ number_format($totalExpense, 0, ',', '.') }}</td>
                                    <td class="text-right">100%</td>
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
    // Function to generate random colors
    function generateColors(count) {
        const colors = [];
        for (let i = 0; i < count; i++) {
            const hue = (i * 137.508) % 360; // Use golden angle approximation
            colors.push(`hsl(${hue}, 70%, 60%)`);
        }
        return colors;
    }

    // Income Chart
    const incomeData = @json($income);
    const incomeColors = generateColors(incomeData.length);
    
    new Chart(document.getElementById('incomeChart').getContext('2d'), {
        type: 'pie',
        data: {
            labels: incomeData.map(item => item.kategori),
            datasets: [{
                data: incomeData.map(item => item.total),
                backgroundColor: incomeColors
            }]
        },
        options: {
            responsive: true,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const value = context.raw;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return `Rp ${new Intl.NumberFormat('id-ID').format(value)} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });

    // Expense Chart
    const expenseData = @json($expenses);
    const expenseColors = generateColors(expenseData.length);
    
    new Chart(document.getElementById('expenseChart').getContext('2d'), {
        type: 'pie',
        data: {
            labels: expenseData.map(item => item.kategori),
            datasets: [{
                data: expenseData.map(item => item.total),
                backgroundColor: expenseColors
            }]
        },
        options: {
            responsive: true,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const value = context.raw;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return `Rp ${new Intl.NumberFormat('id-ID').format(value)} (${percentage}%)`;
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
