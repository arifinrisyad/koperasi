<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\LaporanPembelian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;

class LaporanPembelianController extends Controller
{
    public function index()
    {
        $laporans = LaporanPembelian::with('user')->latest()->paginate(10);
        return view('laporan.pembelian.index', compact('laporans'));
    }

    public function create()
    {
        return view('laporan.pembelian.create');
    }

    public function fetchData(Request $request)
    {
        $request->validate([
            'bulan' => 'required|date_format:m',
            'tahun' => 'required|digits:4'
        ]);

        $bulan = $request->bulan;
        $tahun = $request->tahun;

        // Cek apakah laporan sudah ada
        $existingLaporan = LaporanPembelian::whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->first();

        if ($existingLaporan) {
            return response()->json([
                'error' => true,
                'message' => 'Laporan pembelian untuk periode ini sudah dibuat'
            ], 400);
        }

        // Ambil data pembelian berdasarkan bulan dan tahun
        $pembelians = Pembelian::whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->select('nama_barang', 'jumlah', 'total_harga', 'tanggal', 'user_id')
            ->with('admin')
            ->get();

        // Hitung total
        $totalJumlah = $pembelians->sum('jumlah');
        $totalHarga = $pembelians->sum('total_harga');

        // Format data untuk response
        $data = $pembelians->map(function ($item) {
            return [
                'tanggal' => $item->tanggal,
                'nama_barang' => $item->nama_barang,
                'jumlah' => $item->jumlah,
                'total_harga' => $item->total_harga,
                'supplier' => $item->admin->name ?? 'N/A'
            ];
        });

        return response()->json([
            'data' => $data,
            'total_jumlah' => $totalJumlah,
            'total_harga' => $totalHarga
        ]);
    }

    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'jumlah' => 'required|integer',
            'total_harga' => 'required|numeric',
            'tanggal' => 'required|date',
        ]);

        try {
            // Tambahkan bulan berdasarkan tanggal yang dipilih
            $validated['bulan'] = date('m', strtotime($validated['tanggal']));
            $validated['user_id'] = auth()->id();

            // Cek apakah sudah ada laporan untuk bulan yang sama
            $existingLaporan = LaporanPembelian::whereMonth('tanggal', $validated['bulan'])
                ->whereYear('tanggal', date('Y', strtotime($validated['tanggal'])))
                ->first();

            if ($existingLaporan) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Laporan pembelian untuk bulan ini sudah ada!');
            }

            // Simpan laporan pembelian
            LaporanPembelian::create($validated);

            return redirect()
                ->route('laporan-pembelian.index')
                ->with('success', 'Laporan pembelian berhasil disimpan!');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $laporan = LaporanPembelian::findOrFail($id);
        return view('laporan.pembelian.edit', compact('laporan'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'jumlah' => 'required|integer',
            'total_harga' => 'required|numeric',
            'tanggal' => 'required|date',
            'bulan' => 'required|string|max:2',
        ]);

        $laporan = LaporanPembelian::findOrFail($id);
        $laporan->update($validated);

        return redirect()->route('laporan-pembelian.index')->with('success', 'Laporan pembelian berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $laporan = LaporanPembelian::findOrFail($id);
        $laporan->delete();

        return redirect()->route('laporan-pembelian.index')->with('success', 'Laporan pembelian berhasil dihapus.');
    }

    public function cetakPDF()
    {
        $laporans = LaporanPembelian::with('user')->latest()->get();
        
        $pdf = PDF::loadView('laporan.pembelian.pdf', compact('laporans'));
        
        return $pdf->download('laporan-pembelian.pdf');
    }

    public function getDataBulan(Request $request)
    {
        $request->validate([
            'bulan' => 'required|numeric|between:1,12',
            'tahun' => 'required|numeric'
        ]);

        try {
            // Ambil data pembelian berdasarkan bulan dan tahun
            $pembelians = Pembelian::whereMonth('tanggal', $request->bulan)
                                 ->whereYear('tanggal', $request->tahun)
                                 ->get();

            if ($pembelians->isEmpty()) {
                return response()->json(['data' => null]);
            }

            // Hitung total dari semua pembelian di bulan tersebut
            $totalHarga = $pembelians->sum('total');
            $totalJumlah = $pembelians->sum('jumlah');
            
            // Ambil data pembelian pertama untuk nama barang dan tanggal
            $firstPembelian = $pembelians->first();

            // Format data untuk response
            $data = [
                'nama_barang' => $firstPembelian->nama_barang,
                'jumlah' => $totalJumlah,
                'total_harga' => $totalHarga,
                'tanggal' => $firstPembelian->tanggal
            ];

            return response()->json(['data' => $data]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $laporan = LaporanPembelian::with('user')->findOrFail($id);
        return view('laporan.pembelian.show', compact('laporan'));
    }

    public function print($id)
    {
        $laporan = LaporanPembelian::with('user')->findOrFail($id);
        return view('laporan.pembelian.print', compact('laporan'));
    }

    public function exportPdf($id)
    {
        $laporan = LaporanPembelian::with('user')->findOrFail($id);
        
        $pdf = PDF::loadView('laporan.pembelian.print', compact('laporan'));
        
        return $pdf->download('laporan-pembelian-' . $laporan->id . '.pdf');
    }
}
