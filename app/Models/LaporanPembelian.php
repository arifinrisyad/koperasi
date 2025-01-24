<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporanPembelian extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_barang',
        'jumlah',
        'total_harga',
        'tanggal',
        'bulan',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
