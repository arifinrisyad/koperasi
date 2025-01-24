<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Keuangan extends Model
{
    use HasFactory;

    protected $table = 'keuangans';

    protected $fillable = [
        'user_id',
        'jenis',
        'kategori',
        'jumlah',
        'keterangan',
        'tanggal',
        'bukti_transaksi',
        'referensi_id',
        'referensi_type'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function referensi()
    {
        return $this->morphTo();
    }

    public function getFormattedJumlahAttribute()
    {
        return 'Rp ' . number_format($this->jumlah, 0, ',', '.');
    }

    public function getStatusColorAttribute()
    {
        return $this->jenis === 'pemasukan' ? 'success' : 'danger';
    }

    public function getJenisLabelAttribute()
    {
        return ucfirst($this->jenis);
    }

    public function getSumberTransaksiAttribute()
    {
        if ($this->referensi_type === 'penjualan') {
            return 'Otomatis (Penjualan)';
        } elseif ($this->referensi_type === 'pembelian') {
            return 'Otomatis (Pembelian)';
        }
        return 'Manual';
    }
}
