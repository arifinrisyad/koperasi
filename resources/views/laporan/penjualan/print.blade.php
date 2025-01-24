<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Print Laporan Penjualan</title>
    <style>
        @page {
            margin: 0cm 0cm;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 3cm 2cm 2cm 2cm;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            padding: 0;
            font-size: 24px;
            color: #333;
        }
        .content {
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            width: 200px;
        }
        .footer {
            margin-top: 50px;
            text-align: right;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN PENJUALAN</h1>
    </div>

    <div class="content">
        <table>
            <tr>
                <th>Tanggal</th>
                <td>{{ \Carbon\Carbon::parse($laporan->tanggal)->format('d F Y') }}</td>
            </tr>
            <tr>
                <th>Nama Barang</th>
                <td>{{ $laporan->nama_barang }}</td>
            </tr>
            <tr>
                <th>Jumlah</th>
                <td>{{ $laporan->jumlah }}</td>
            </tr>
            <tr>
                <th>Total Harga</th>
                <td>Rp {{ number_format($laporan->total_harga, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Keuntungan</th>
                <td>Rp {{ number_format($laporan->keuntungan, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Dibuat Oleh</th>
                <td>{{ $laporan->user->name }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->format('d F Y H:i:s') }}</p>
    </div>

    <script>
        window.print();
    </script>
</body>
</html>
