<?php

namespace App\Observers;

use App\Models\Penjualan;
use App\Models\Keuangan;

class PenjualanObserver
{
    public function created(Penjualan $penjualan)
    {
        // Catat pemasukan otomatis dari penjualan
        Keuangan::create([
            'user_id' => auth()->id(),
            'jenis' => 'pemasukan',
            'kategori' => 'Penjualan Barang',
            'jumlah' => $penjualan->total_harga,
            'keterangan' => 'Penjualan #' . $penjualan->id,
            'tanggal' => $penjualan->created_at,
            'bukti_transaksi' => null,
            'referensi_id' => $penjualan->id,
            'referensi_type' => 'penjualan'
        ]);
    }

    public function deleted(Penjualan $penjualan)
    {
        // Hapus catatan keuangan terkait jika penjualan dihapus
        Keuangan::where('referensi_id', $penjualan->id)
                ->where('referensi_type', 'penjualan')
                ->delete();
    }
}
