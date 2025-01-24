<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    protected $fillable = [
        'barang_dijuals_id',
        'jumlah',
        'total_harga',
        'keuntungan',
        'tanggal',
        'user_id'
    ];

    protected $dates = [
        'tanggal',
        'created_at',
        'updated_at'
    ];

    public function barangdijual()
    {
        return $this->belongsTo(BarangDijual::class, 'barang_dijuals_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
