<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Pembelian; 
use App\Models\Penjualan;
use App\Models\BarangDijual;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Services\ActivityLogService;

class PenjualanController extends Controller
{
    public function index()
    {
        $penjualans = Penjualan::with(['barangdijual', 'user'])
            ->latest()
            ->paginate(10);
        return view('penjualan.index', compact('penjualans'));
    }

    public function create()
    {
        if (!in_array(auth()->user()->role, ['admin', 'petugas'])) {
            return redirect()->route('penjualan.index')->with('error', 'Anda tidak memiliki akses untuk membuat penjualan.');
        }
        $barangs = BarangDijual::all();
        return view('penjualan.create', compact('barangs'));
    }

    public function store(Request $request)
    {
        Log::info('Entering store method');
        Log::info('Request data:', $request->all());

        if (!in_array(auth()->user()->role, ['admin', 'petugas'])) {
            Log::warning('Unauthorized access attempt');
            return redirect()->route('penjualan.index')->with('error', 'Anda tidak memiliki akses untuk membuat penjualan.');
        }
    
        try {
            DB::beginTransaction();

            // Validasi input
            $validated = $request->validate([
                'barang_dijuals_id' => 'required|exists:barang_dijuals,id',
                'jumlah' => 'required|integer|min:1',
                'tanggal' => 'required|date',
            ]);

            Log::info('Validation passed');
    
            // Ambil data barang dari database
            $barang = BarangDijual::findOrFail($request->barang_dijuals_id);
            Log::info('Found barang:', ['barang' => $barang->toArray()]);
    
            // Cek apakah stok barang cukup
            if ($barang->stok < $request->jumlah) {
                Log::warning('Insufficient stock', [
                    'requested' => $request->jumlah,
                    'available' => $barang->stok
                ]);
                DB::rollBack();
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Stok barang tidak mencukupi.');
            }
    
            // Hitung total harga dan keuntungan
            $total_harga = $barang->harga_jual * $request->jumlah;
            $keuntungan = ($barang->harga_jual - $barang->harga_beli) * $request->jumlah;
    
            Log::info('Creating penjualan record', [
                'total_harga' => $total_harga,
                'keuntungan' => $keuntungan
            ]);

            // Buat record penjualan baru
            $penjualan = Penjualan::create([
                'barang_dijuals_id' => $request->barang_dijuals_id,
                'jumlah' => $request->jumlah,
                'total_harga' => $total_harga,
                'keuntungan' => $keuntungan,
                'tanggal' => $request->tanggal,
                'user_id' => auth()->id()
            ]);

            Log::info('Penjualan created:', ['penjualan' => $penjualan->toArray()]);
    
            // Update stok barang
            $barang->update([
                'stok' => $barang->stok - $request->jumlah
            ]);

            Log::info('Stock updated');
            
            DB::commit();
            Log::info('Transaction committed');

            // Log aktivitas penjualan
            ActivityLogService::logCreate(
                auth()->user(), 
                "Membuat transaksi penjualan: {$barang->nama_barang} ({$request->jumlah} unit)"
            );

            return redirect()->route('penjualan.index')
                ->with('success', 'Penjualan berhasil ditambahkan.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in PenjualanController@store: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all()
            ]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit(Penjualan $penjualan)
    {
        if (!in_array(auth()->user()->role, ['admin', 'petugas'])) {
            return redirect()->route('penjualan.index')->with('error', 'Anda tidak memiliki akses untuk mengedit penjualan.');
        }

        $barangs = BarangDijual::all();
        return view('penjualan.edit', compact('penjualan', 'barangs'));
    }

    public function update(Request $request, Penjualan $penjualan)
    {
        if (!in_array(auth()->user()->role, ['admin', 'petugas'])) {
            return redirect()->route('penjualan.index')->with('error', 'Anda tidak memiliki akses untuk mengedit penjualan.');
        }

        // Validasi input
        $validated = $request->validate([
            'barang_dijuals_id' => 'required|exists:barang_dijuals,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            // Ambil data barang baru
            $barang = BarangDijual::findOrFail($request->barang_dijuals_id);
            
            // Jika barang berbeda atau jumlah berubah, perlu update stok
            if ($penjualan->barang_dijuals_id != $request->barang_dijuals_id || $penjualan->jumlah != $request->jumlah) {
                // Kembalikan stok barang lama
                $oldBarang = BarangDijual::findOrFail($penjualan->barang_dijuals_id);
                $oldBarang->update([
                    'stok' => $oldBarang->stok + $penjualan->jumlah
                ]);

                // Cek stok barang baru
                if ($barang->stok < $request->jumlah) {
                    Log::warning('Insufficient stock', [
                        'requested' => $request->jumlah,
                        'available' => $barang->stok
                    ]);
                    DB::rollBack();
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Stok barang tidak mencukupi.');
                }

                // Kurangi stok barang baru
                $barang->update([
                    'stok' => $barang->stok - $request->jumlah
                ]);
            }

            // Hitung total harga dan keuntungan baru
            $total_harga = $barang->harga_jual * $request->jumlah;
            $keuntungan = ($barang->harga_jual - $barang->harga_beli) * $request->jumlah;

            Log::info('Updating penjualan record', [
                'total_harga' => $total_harga,
                'keuntungan' => $keuntungan
            ]);

            // Update data penjualan
            $oldData = $penjualan->only(['barang_dijuals_id', 'jumlah', 'tanggal']);
            $penjualan->update([
                'barang_dijuals_id' => $request->barang_dijuals_id,
                'jumlah' => $request->jumlah,
                'total_harga' => $total_harga,
                'keuntungan' => $keuntungan,
                'tanggal' => $request->tanggal,
            ]);

            Log::info('Penjualan updated:', ['penjualan' => $penjualan->toArray()]);

            // Log aktivitas update penjualan
            $changes = [];
            foreach ($oldData as $field => $value) {
                if ($value != $penjualan->$field) {
                    $changes[] = "$field: $value -> {$penjualan->$field}";
                }
            }
            if (!empty($changes)) {
                $changeText = implode(', ', $changes);
                ActivityLogService::logUpdate(
                    auth()->user(), 
                    "Mengubah data penjualan {$barang->nama_barang}: $changeText"
                );
            }

            DB::commit();
            Log::info('Transaction committed');

            return redirect()->route('penjualan.index')
                ->with('success', 'Penjualan berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in PenjualanController@update: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all()
            ]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(Penjualan $penjualan)
    {
        $penjualan->load(['barangdijual', 'user']);
        return view('penjualan.show', compact('penjualan'));
    }

    public function destroy(Penjualan $penjualan)
    {
        if (!in_array(auth()->user()->role, ['admin'])) {
            return redirect()->route('penjualan.index')
                ->with('error', 'Anda tidak memiliki akses untuk menghapus penjualan.');
        }

        try {
            DB::beginTransaction();

            // Kembalikan stok barang
            $barang = $penjualan->barangdijual;
            if ($barang) {
                $barang->update([
                    'stok' => $barang->stok + $penjualan->jumlah
                ]);
            }

            // Log aktivitas delete penjualan
            ActivityLogService::logDelete(
                auth()->user(), 
                "Menghapus data penjualan: {$barang->nama_barang} ({$penjualan->jumlah} unit)"
            );

            $penjualan->delete();

            DB::commit();

            return redirect()->route('penjualan.index')
                ->with('success', 'Penjualan berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in PenjualanController@destroy: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
