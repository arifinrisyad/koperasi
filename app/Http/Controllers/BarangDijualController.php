<?php

namespace App\Http\Controllers;

use App\Models\BarangDijual;
use Illuminate\Http\Request;

class BarangDijualController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $barangDijual = BarangDijual::query()
            ->when($search, function($query) use ($search) {
                $query->where('nama_barang', 'like', "%{$search}%")
                    ->orWhere('stok', 'like', "%{$search}%")
                    ->orWhere('harga_beli', 'like', "%{$search}%")
                    ->orWhere('harga_jual', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(6);

        if($request->ajax()) {
            return view('barang_dijuals.table', compact('barangDijual'));
        }

        return view('barang_dijuals.index', compact('barangDijual', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
        ]);

        BarangDijual::create($request->all());

        return redirect()->route('barang-dijual.index')
            ->with('success', 'Barang berhasil ditambahkan!');
    }

    public function edit(BarangDijual $barangDijual)
    {
        return view('barang_dijuals.edit', compact('barangDijual'));
    }

    public function update(Request $request, BarangDijual $barangDijual)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
        ]);

        $barangDijual->update($request->all());

        return redirect()->route('barang-dijual.index')
            ->with('success', 'Data barang berhasil diperbarui!');
    }

    public function destroy(BarangDijual $barangDijual)
    {
        $barangDijual->delete();

        return redirect()->route('barang-dijual.index')
            ->with('success', 'Data barang berhasil dihapus.');
    }
}
