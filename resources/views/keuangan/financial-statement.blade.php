@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Laporan Keuangan Detail</h3>
                    <div class="card-tools">
                        <form action="{{ url()->current() }}" method="get" class="form-inline">
                            <select name="month" class="form-control mr-2">
                                @php
                                    $months = [
                                        1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
                                        4 => 'April', 5 => 'Mei', 6 => 'Juni',
                                        7 => 'Juli', 8 => 'Agustus', 9 => 'September',
                                        10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                    ];
                                @endphp
                                @foreach($months as $key => $value)
                                    <option value="{{ $key }}" {{ $month == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                            <select name="year" class="form-control mr-2">
                                @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                                        {{ $y }}
                                    </option>
                                @endfor
                            </select>
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Summary Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-wallet"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Saldo Awal</span>
                                    <span class="info-box-number">Rp {{ number_format($summary['opening_balance'], 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-arrow-down"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Pemasukan</span>
                                    <span class="info-box-number">Rp {{ number_format($summary['total_income'], 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-danger"><i class="fas fa-arrow-up"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Pengeluaran</span>
                                    <span class="info-box-number">Rp {{ number_format($summary['total_expense'], 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning"><i class="fas fa-balance-scale"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Saldo Akhir</span>
                                    <span class="info-box-number">Rp {{ number_format($summary['closing_balance'], 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detailed Transactions -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Kategori</th>
                                    <th>Keterangan</th>
                                    <th>Jenis</th>
                                    <th>Jumlah</th>
                                    <th>Saldo Berjalan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $runningBalance = $summary['opening_balance']; @endphp
                                @foreach($transactions as $transaction)
                                    @php
                                        if($transaction->jenis == 'pemasukan') {
                                            $runningBalance += $transaction->jumlah;
                                        } else {
                                            $runningBalance -= $transaction->jumlah;
                                        }
                                    @endphp
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($transaction->tanggal)->format('d/m/Y') }}</td>
                                        <td>{{ $transaction->kategori }}</td>
                                        <td>{{ $transaction->keterangan }}</td>
                                        <td>
                                            <span class="badge badge-{{ $transaction->jenis == 'pemasukan' ? 'success' : 'danger' }}">
                                                {{ ucfirst($transaction->jenis) }}
                                            </span>
                                        </td>
                                        <td>Rp {{ number_format($transaction->jumlah, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($runningBalance, 0, ',', '.') }}</td>
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
