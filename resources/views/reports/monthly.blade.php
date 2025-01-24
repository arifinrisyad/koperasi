<!DOCTYPE html>
<html>
<head>
    <title>Laporan Bulanan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            color: #2c3e50;
        }
        .summary {
            margin-bottom: 30px;
        }
        .summary-item {
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
        }
        .text-right {
            text-align: right;
        }
        .section-title {
            margin-top: 30px;
            margin-bottom: 15px;
            color: #2c3e50;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Bulanan Koperasi</h1>
        <p>Periode: {{ $startDate }} - {{ $endDate }}</p>
    </div>

    <div class="summary">
        <h3>Ringkasan</h3>
        <div class="summary-item">Total Penjualan: {{ $totalPenjualan }} transaksi</div>
        <div class="summary-item">Total Pendapatan: Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
        <div class="summary-item">Total Keuntungan: Rp {{ number_format($totalKeuntungan, 0, ',', '.') }}</div>
        <div class="summary-item">Total Pembelian: {{ $totalPembelian }} transaksi</div>
        <div class="summary-item">Total Pengeluaran: Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</div>
    </div>

    <h3 class="section-title">Detail Penjualan</h3>
    <table>
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
            @foreach($penjualanData as $penjualan)
            <tr>
                <td>{{ $penjualan->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ optional($penjualan->barangdijual)->nama_barang ?? 'Barang tidak ditemukan' }}</td>
                <td>{{ $penjualan->jumlah }}</td>
                <td class="text-right">Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($penjualan->keuntungan, 0, ',', '.') }}</td>
                <td>{{ optional($penjualan->user)->name ?? 'User tidak ditemukan' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h3 class="section-title">Detail Pembelian</h3>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Barang</th>
                <th>Jumlah</th>
                <th>Harga Satuan</th>
                <th>Total</th>
                <th>User</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pembelianData as $pembelian)
            <tr>
                <td>{{ $pembelian->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $pembelian->nama_barang }}</td>
                <td>{{ $pembelian->jumlah }}</td>
                <td class="text-right">Rp {{ number_format($pembelian->harga_satuan, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($pembelian->total, 0, ',', '.') }}</td>
                <td>{{ optional($pembelian->user)->name ?? 'User tidak ditemukan' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
