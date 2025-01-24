<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use Illuminate\Http\Request;
use App\Models\LaporanPenjualan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanPenjualanController extends Controller
{
    public function index()
    {
        $laporans = LaporanPenjualan::with('user')->latest()->paginate(10);
        return view('laporan.penjualan.index', compact('laporans'));
    }

    public function create()
    {
        return view('laporan.penjualan.create');
    }

    public function fetchData(Request $request)
    {
        try {
            \Log::info('Received request data:', $request->all());
            
            $request->validate([
                'bulan' => 'required|numeric|min:1|max:12',
                'tahun' => 'required|numeric|min:2000|max:2099',
            ]);

            $bulan = str_pad($request->bulan, 2, '0', STR_PAD_LEFT);
            $tahun = $request->tahun;

            // Check if report already exists
            $existingReport = LaporanPenjualan::whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->first();

            if ($existingReport) {
                return response()->json([
                    'error' => 'Laporan penjualan untuk bulan ini sudah ada!'
                ], 400);
            }

            \Log::info('Querying sales data for:', ['bulan' => $bulan, 'tahun' => $tahun]);

            // Get all sales for the selected month and year
            $penjualans = Penjualan::select(
                    'penjualans.*',
                    'barang_dijuals.nama_barang'
                )
                ->join('barang_dijuals', 'penjualans.barang_dijuals_id', '=', 'barang_dijuals.id')
                ->whereMonth('penjualans.tanggal', $bulan)
                ->whereYear('penjualans.tanggal', $tahun)
                ->get();

            if ($penjualans->isEmpty()) {
                return response()->json([
                    'error' => 'Perhatian: Tidak ada data penjualan untuk periode ' . date('F Y', strtotime($tahun . '-' . $bulan . '-01')) . '.'
                ], 404);
            }

            // Group sales by product and calculate totals
            $groupedData = $penjualans->groupBy('nama_barang');
            $namaBarangList = [];
            $totalJumlah = 0;
            $totalHarga = 0;
            $totalKeuntungan = 0;

            foreach ($groupedData as $namaBarang => $items) {
                if (!$namaBarang) continue; // Skip if nama_barang is null
                
                $jumlahBarang = $items->sum('jumlah');
                $namaBarangList[] = $namaBarang;
                $totalJumlah += $jumlahBarang;
                $totalHarga += $items->sum('total_harga');
                $totalKeuntungan += $items->sum('keuntungan');
            }

            $result = [
                'nama_barang' => implode(', ', $namaBarangList),
                'jumlah' => $totalJumlah,
                'total_harga' => $totalHarga,
                'keuntungan' => $totalKeuntungan,
                'tanggal' => $tahun . '-' . $bulan . '-01'
            ];

            \Log::info('Fetch Data Response:', $result);
            return response()->json($result);

        } catch (\Exception $e) {
            \Log::error('Error in fetchData: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'error' => 'Terjadi kesalahan saat mengambil data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'bulan' => 'required|date_format:m',
                'tahun' => 'required|digits:4',
                'nama_barang' => 'required|string',
                'jumlah' => 'required|integer',
                'total_harga' => 'required|numeric',
                'keuntungan' => 'required|numeric',
                'tanggal' => 'required|date',
            ]);

            // Check if report already exists
            $existingReport = LaporanPenjualan::whereMonth('tanggal', $validated['bulan'])
                ->whereYear('tanggal', $validated['tahun'])
                ->first();

            if ($existingReport) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors(['error' => 'Laporan untuk periode ini sudah ada!']);
            }

            // Create the report
            $report = new LaporanPenjualan();
            $report->nama_barang = $validated['nama_barang'];
            $report->jumlah = $validated['jumlah'];
            $report->total_harga = $validated['total_harga'];
            $report->keuntungan = $validated['keuntungan'];
            $report->tanggal = $validated['tanggal'];
            $report->user_id = auth()->id();
            $report->save();

            return redirect()
                ->route('laporan-penjualan.index')
                ->with('success', 'Laporan penjualan berhasil disimpan.');

        } catch (\Exception $e) {
            Log::error('Error in store: ' . $e->getMessage());
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $laporan = LaporanPenjualan::find($id);
        return view('laporan.penjualan.edit', compact('laporan'));
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'nama_barang' => 'required|string|max:255',
                'jumlah' => 'required|integer|min:1',
                'total_harga' => 'required|numeric|min:0',
                'keuntungan' => 'required|numeric|min:0',
                'tanggal' => 'required|date',
            ]);

            $laporan = LaporanPenjualan::findOrFail($id);
            $laporan->update([
                'nama_barang' => $request->nama_barang,
                'jumlah' => $request->jumlah,
                'total_harga' => $request->total_harga,
                'keuntungan' => $request->keuntungan,
                'tanggal' => $request->tanggal,
            ]);

            return redirect()
                ->route('laporan-penjualan.show', $id)
                ->with('success', 'Laporan penjualan berhasil diperbarui.');

        } catch (\Exception $e) {
            \Log::error('Error in update: ' . $e->getMessage());
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $laporan = LaporanPenjualan::findOrFail($id);
        $laporan->delete();

        return redirect()->route('laporan-penjualan.index')->with('success', 'Laporan penjualan berhasil dihapus.');
    }

    public function show($id)
    {
        $laporan = LaporanPenjualan::with('user')->findOrFail($id);
        return view('laporan.penjualan.show', compact('laporan'));
    }

    public function print($id)
    {
        $laporan = LaporanPenjualan::with('user')->findOrFail($id);
        return view('laporan.penjualan.print', compact('laporan'));
    }

    public function exportPdf($id)
    {
        $laporan = LaporanPenjualan::with('user')->findOrFail($id);
        
        $pdf = Pdf::loadView('laporan.penjualan.print', compact('laporan'));
        
        // Remove default header/footer
        $pdf->setPaper('A4');
        $pdf->getOptions()->set([
            'isRemoteEnabled' => true,
            'isPhpEnabled' => true,
            'defaultFont' => 'sans-serif',
            'isHtml5ParserEnabled' => true,
            'margin_top' => 20,
            'margin_bottom' => 20,
            'margin_left' => 20,
            'margin_right' => 20,
        ]);
        
        return $pdf->download('laporan-penjualan-' . $laporan->id . '.pdf');
    }
}
