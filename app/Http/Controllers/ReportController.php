<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\Pembelian;
use Illuminate\Http\Request;
use PDF;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function generateReport()
    {
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now();

        $penjualanData = Penjualan::whereBetween('created_at', [$startDate, $endDate])
            ->with(['barangdijual', 'user'])
            ->get();

        $pembelianData = Pembelian::whereBetween('created_at', [$startDate, $endDate])
            ->with(['user'])
            ->get();

        $totalPenjualan = $penjualanData->count();
        $totalPendapatan = $penjualanData->sum('total_harga');
        $totalKeuntungan = $penjualanData->sum('keuntungan');
        $totalPembelian = $pembelianData->count();
        $totalPengeluaran = $pembelianData->sum('total');

        $data = [
            'startDate' => $startDate->format('d/m/Y'),
            'endDate' => $endDate->format('d/m/Y'),
            'penjualanData' => $penjualanData,
            'pembelianData' => $pembelianData,
            'totalPenjualan' => $totalPenjualan,
            'totalPendapatan' => $totalPendapatan,
            'totalKeuntungan' => $totalKeuntungan,
            'totalPembelian' => $totalPembelian,
            'totalPengeluaran' => $totalPengeluaran
        ];

        $pdf = PDF::loadView('reports.monthly', $data);
        
        return $pdf->download('laporan-bulanan-' . $endDate->format('F-Y') . '.pdf');
    }
}
