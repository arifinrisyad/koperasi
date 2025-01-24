<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanPenjualan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_barang',
        'jumlah',
        'total_harga',
        'keuntungan',
        'tanggal',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function barang_dijuals()
    {
        return $this->belongsTo(BarangDijual::class, 'barang_dijuals_id');
    }

    public function edit($id)
    {
        $laporan = LaporanPenjualan::find($id);
        // Ubah path view sesuai dengan struktur folder yang benar
        return view('laporan.penjualan.edit', compact('laporan'));
    }
}
