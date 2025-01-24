<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BarangDijual extends Model
{
    use HasFactory;

    protected $fillable = [
        'pembelian_id',
        'nama_barang',
        'stok',
        'harga_beli',
        'harga_jual',
    ];

    protected $table = 'barang_dijuals';

    public function pembelian(): BelongsTo
    {
        return $this->belongsTo(Pembelian::class, 'pembelian_id', 'id');
    }

    public function penjualans(): HasMany
    {
        return $this->hasMany(Penjualan::class, 'barang_dijuals_id');
    }
}
