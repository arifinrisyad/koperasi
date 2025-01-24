<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Pembelian;
use App\Models\BarangDijual;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Services\ActivityLogService;

class PembelianController extends Controller
{
    public function index()
    {
        $pembelians = Pembelian::with('user')->latest()->paginate(6);
        return view('pembelian.index', compact('pembelians'));
    }

    public function create()
    {
        return view('pembelian.create');
    }

    public function store(Request $request)
    {
        // Validasi data yang diterima
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'nama_barang' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'harga_satuan' => 'required|numeric|min:0'
        ]);

        try {
            DB::beginTransaction();

            // Hitung total
            $total = $validated['jumlah'] * $validated['harga_satuan'];

            // Simpan data pembelian
            $pembelian = Pembelian::create([
                'tanggal' => $validated['tanggal'],
                'nama_barang' => $validated['nama_barang'],
                'jumlah' => $validated['jumlah'],
                'harga_satuan' => $validated['harga_satuan'],
                'total' => $total,
                'user_id' => auth()->id(),
            ]);
            
            // Log aktivitas pembelian
            ActivityLogService::logCreate(
                auth()->user(), 
                "Membuat transaksi pembelian: {$pembelian->nama_barang} ({$pembelian->jumlah} unit)"
            );

            // Cek apakah barang dengan nama yang sama sudah ada
            $existingBarang = BarangDijual::where('nama_barang', $validated['nama_barang'])->first();
            
            if ($existingBarang) {
                // Jika barang sudah ada, update stok saja
                $existingBarang->stok += $validated['jumlah'];
                $existingBarang->save();
            } else {
                // Jika barang belum ada, buat baru
                BarangDijual::create([
                    'pembelian_id' => $pembelian->id,
                    'nama_barang' => $validated['nama_barang'],
                    'stok' => $validated['jumlah'],
                    'harga_beli' => $validated['harga_satuan'],
                    'harga_jual' => $validated['harga_satuan'] * 1.2, // Markup 20%
                ]);
            }

            DB::commit();

            return redirect()
                ->route('pembelian.index')
                ->with('success', 'Data pembelian berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit(Pembelian $pembelian)
    {
        $barangs = Barang::all();
        return view('pembelian.edit', compact('pembelian', 'barangs'));
    }

    public function update(Request $request, Pembelian $pembelian)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'nama_barang' => 'required',
            'jumlah' => 'required|numeric|min:1',
            'harga_satuan' => 'required|numeric|min:0'
        ]);

        $oldData = $pembelian->only(['tanggal', 'nama_barang', 'jumlah', 'harga_satuan']);

        $pembelian->update([
            'tanggal' => $request->tanggal,
            'nama_barang' => $request->nama_barang,
            'jumlah' => $request->jumlah,
            'harga_satuan' => $request->harga_satuan,
            'total' => $request->jumlah * $request->harga_satuan
        ]);

        // Log aktivitas update pembelian
        $changes = [];
        foreach ($oldData as $field => $value) {
            if ($value != $pembelian->$field) {
                $changes[] = "$field: $value -> {$pembelian->$field}";
            }
        }
        if (!empty($changes)) {
            $changeText = implode(', ', $changes);
            ActivityLogService::logUpdate(
                auth()->user(), 
                "Mengubah data pembelian {$pembelian->nama_barang}: $changeText"
            );
        }

        return redirect()->route('pembelian.index')->with('success', 'Data pembelian berhasil diperbarui.');
    }

    public function destroy(Pembelian $pembelian)
    {
        // Log aktivitas delete pembelian
        ActivityLogService::logDelete(
            auth()->user(), 
            "Menghapus data pembelian: {$pembelian->nama_barang} ({$pembelian->jumlah} unit)"
        );

        $pembelian->delete();
        return redirect()->route('pembelian.index')->with('success', 'Pembelian berhasil dihapus.');
    }

    public function destroyAll()
    {
        try {
            // Begin transaction
            DB::beginTransaction();

            // Delete all barang dijual records first
            BarangDijual::query()->delete();

            // Then delete all pembelian records
            Pembelian::query()->delete();

            // Commit transaction
            DB::commit();

            return redirect()->route('pembelian.index')
                   ->with('success', 'Semua data pembelian berhasil dihapus!');
        } catch (\Exception $e) {
            // Rollback in case of error
            DB::rollback();
            return redirect()->route('pembelian.index')
                   ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}