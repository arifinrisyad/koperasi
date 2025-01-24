@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Detail Penjualan</h4>
                    <a href="{{ route('penjualan.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>

                <div class="card-body">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th width="200">Nomor Transaksi</th>
                                <td>{{ $penjualan->id }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal</th>
                                <td>{{ \Carbon\Carbon::parse($penjualan->tanggal)->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th>Nama Barang</th>
                                <td>{{ $penjualan->barang_dijuals->nama_barang }}</td>
                            </tr>
                            <tr>
                                <th>Jumlah</th>
                                <td>{{ $penjualan->jumlah }}</td>
                            </tr>
                            <tr>
                                <th>Harga Satuan</th>
                                <td>Rp {{ number_format($penjualan->barang_dijuals->harga_jual, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th>Total Harga</th>
                                <td>Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th>Keuntungan</th>
                                <td>Rp {{ number_format($penjualan->keuntungan, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th>Petugas</th>
                                <td>{{ $penjualan->petugas->name }}</td>
                            </tr>
                            <tr>
                                <th>Waktu Input</th>
                                <td>{{ $penjualan->created_at->format('d/m/Y H:i:s') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
