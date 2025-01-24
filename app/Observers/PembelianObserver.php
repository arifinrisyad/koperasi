<?php

namespace App\Observers;

use App\Models\Pembelian;
use App\Models\Keuangan;

class PembelianObserver
{
    public function created(Pembelian $pembelian)
    {
        // Catat pengeluaran otomatis dari pembelian
        Keuangan::create([
            'user_id' => auth()->id(),
            'jenis' => 'pengeluaran',
            'kategori' => 'Pembelian Barang',
            'jumlah' => $pembelian->total, // Menggunakan total dari pembelian
            'keterangan' => 'Pembelian #' . $pembelian->id . ' - ' . $pembelian->nama_barang,
            'tanggal' => $pembelian->tanggal,
            'bukti_transaksi' => null,
            'referensi_id' => $pembelian->id,
            'referensi_type' => 'pembelian'
        ]);
    }

    public function deleted(Pembelian $pembelian)
    {
        // Hapus catatan keuangan terkait jika pembelian dihapus
        Keuangan::where('referensi_id', $pembelian->id)
                ->where('referensi_type', 'pembelian')
                ->delete();
    }
}
