@extends('layouts.app')

@section('content')
    <style>
        /* Page Styling */
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
            color: #333;
        }

        /* Header Styling */
        h1 {
            text-align: center;
            font-size: 36px;
            font-weight: bold;
            color: #34495e;
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: 2px;
            padding: 20px;
            background-color: #2c3e50;
            color: white;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Form Container Styling */
        .form-container {
            max-width: 700px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            border: 1px solid #ddd;
            background: linear-gradient(135deg, #f0f3f4, #ffffff);
        }

        .form-group label {
            font-size: 16px;
            font-weight: bold;
            color: #34495e;
        }

        .form-control {
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 12px;
            font-size: 14px;
            color: #34495e;
            box-shadow: inset 0 4px 8px rgba(0, 0, 0, 0.05);
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-control:focus {
            border-color: #3498db;
            box-shadow: inset 0 4px 8px rgba(0, 123, 255, 0.25);
        }

        /* Buttons Styling */
        .btn {
            padding: 12px 24px;
            font-size: 16px;
            font-weight: bold;
            color: #fff;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background-color: #3498db;
        }

        .btn-primary:hover {
            background-color: #2980b9;
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-secondary {
            background-color: #95a5a6;
        }

        .btn-secondary:hover {
            background-color: #7f8c8d;
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .form-container {
                padding: 20px;
                margin: 20px;
            }

            .btn {
                font-size: 14px;
                padding: 10px 20px;
            }
        }
    </style>

    <div class="content">
        <h1>Edit Pembelian</h1>

        <div class="form-container">
            <form action="{{ route('pembelian.update', $pembelian) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="barang_id">Nama Barang</label>
                    <select name="barang_id" class="form-control" required>
                        @foreach($barangs as $barang)
                            <option value="{{ $barang->id }}" {{ $barang->id == $pembelian->barang_id ? 'selected' : '' }}>
                                {{ $barang->nama_barang }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="jumlah">Jumlah</label>
                    <input type="number" name="jumlah" class="form-control" id="jumlah" value="{{ $pembelian->jumlah }}" required>
                </div>

                <div class="form-group">
                    <label for="total_harga">Total Harga</label>
                    <input type="number" name="total_harga" class="form-control" id="total_harga" value="{{ $pembelian->total_harga }}">
                </div>

                <div class="form-group">
                    <label for="tanggal">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" id="tanggal" value="{{ $pembelian->tanggal }}" required>
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('pembelian.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
@endsection
