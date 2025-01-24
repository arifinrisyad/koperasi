<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\BarangDijual;
use App\Models\Penjualan;
use App\Models\Pembelian;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        $startOfWeek = $now->copy()->startOfWeek();
        $endOfWeek = $now->copy()->endOfWeek();

        // Data untuk total penjualan bulan ini
        $totalPenjualan = Penjualan::whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->count();

        // Data untuk total keuntungan bulan ini
        $totalKeuntungan = Penjualan::whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->sum('keuntungan');

        // Data untuk total barang
        $totalBarang = BarangDijual::sum('stok');

        // Data untuk total pembelian bulan ini
        $totalPembelian = Pembelian::whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->count();

        // Data performa penjualan
        $performaPenjualan = [
            'hari_ini' => Penjualan::whereDate('created_at', $now->toDateString())->count(),
            'minggu_ini' => Penjualan::whereBetween('created_at', [$startOfWeek, $endOfWeek])->count()
        ];

        // Data performa pendapatan
        $performaPendapatan = [
            'hari_ini' => Penjualan::whereDate('created_at', $now->toDateString())->sum('total_harga'),
            'bulan_ini' => Penjualan::whereMonth('created_at', $now->month)
                ->whereYear('created_at', $now->year)
                ->sum('total_harga')
        ];

        // Data penjualan mingguan untuk grafik
        $startDate = $now->copy()->subDays(6)->startOfDay();
        $endDate = $now->copy()->endOfDay();
        
        $penjualanMingguan = DB::table('penjualans')
            ->select(
                DB::raw('DATE(created_at) as tanggal'),
                DB::raw('COUNT(*) as total_penjualan'),
                DB::raw('COALESCE(SUM(total_harga), 0) as total_pendapatan'),
                DB::raw('COALESCE(SUM(keuntungan), 0) as total_keuntungan')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('tanggal')
            ->get();

        // Data pembelian mingguan untuk grafik
        $pembelianMingguan = DB::table('pembelians')
            ->select(
                DB::raw('DATE(created_at) as tanggal'),
                DB::raw('COALESCE(SUM(total), 0) as total_pembelian')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('tanggal')
            ->get();

        // Fill in missing dates with zero values
        $filledPenjualanMingguan = collect();
        $currentDate = $startDate->copy();
        
        while ($currentDate <= $endDate) {
            $dateStr = $currentDate->toDateString();
            $existingPenjualan = $penjualanMingguan->firstWhere('tanggal', $dateStr);
            $existingPembelian = $pembelianMingguan->firstWhere('tanggal', $dateStr);
            
            $filledPenjualanMingguan->push([
                'tanggal' => $dateStr,
                'total_penjualan' => (int)($existingPenjualan->total_penjualan ?? 0),
                'total_pendapatan' => (float)($existingPenjualan->total_pendapatan ?? 0),
                'total_keuntungan' => (float)($existingPenjualan->total_keuntungan ?? 0),
                'total_pembelian' => (float)($existingPembelian->total_pembelian ?? 0)
            ]);
            
            $currentDate->addDay();
        }

        // Data barang dengan stok menipis (<=10)
        $barangMenipis = BarangDijual::where('stok', '<=', 10)
            ->orderBy('stok', 'asc')
            ->get();

        // Data penjualan terbaru
        $penjualanTerbaru = Penjualan::with(['barangdijual', 'user'])
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard', compact(
            'totalPenjualan',
            'totalKeuntungan',
            'totalBarang',
            'totalPembelian',
            'performaPenjualan',
            'performaPendapatan',
            'filledPenjualanMingguan',
            'barangMenipis',
            'penjualanTerbaru',
            'now',
            'startOfWeek',
            'endOfWeek',
            'startDate',
            'endDate'
        ));
    }
}