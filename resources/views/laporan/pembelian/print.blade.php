<!DOCTYPE html>
<html>
<head>
    <title>Print Laporan Pembelian</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            padding: 0;
            font-size: 24px;
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
        @media print {
            @page {
                margin: 0;
                size: A4;
            }
            body {
                margin: 1.6cm;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN PEMBELIAN</h1>
        <p>{{ config('app.name', 'Laravel') }}</p>
    </div>

    <div class="content">
        <table>
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
                <th>Tanggal</th>
                <td>{{ \Carbon\Carbon::parse($laporan->tanggal)->format('d F Y') }}</td>
            </tr>
            <tr>
                <th>Dibuat Oleh</th>
                <td>{{ $laporan->user->name }}</td>
            </tr>
        </table>

        <div class="footer">
            <p>Dicetak pada: {{ \Carbon\Carbon::now()->format('d F Y H:i:s') }}</p>
        </div>
    </div>

    <script>
        window.print();
    </script>
</body>
</html>
