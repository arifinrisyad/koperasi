<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pembelian;
use App\Models\Penjualan;
use App\Models\BarangDijual;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PetugasController extends Controller
{
    public function dashboard()
    {
        // Get total transactions
        $totalPembelian = Pembelian::count();
        $totalPenjualan = Penjualan::count();
        
        // Get total products
        $totalProduk = BarangDijual::count();
        
        // Get latest transactions
        $latestPembelian = Pembelian::latest()
            ->take(5)
            ->get();
            
        $latestPenjualan = Penjualan::with('barangdijual')
            ->latest()
            ->take(5)
            ->get();
            
        // Get stock alerts (products with low stock)
        $lowStockProducts = BarangDijual::where('stok', '<=', 10)
            ->orderBy('stok', 'asc')
            ->take(5)
            ->get();
            
        // Get monthly sales data for the current year
        $salesData = Penjualan::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('YEAR(created_at) as year'),
            DB::raw('COUNT(*) as total_sales'),
            DB::raw('SUM(total_harga) as total_revenue')
        )
        ->whereYear('created_at', date('Y'))
        ->groupBy('year', 'month')
        ->orderBy('year', 'ASC')
        ->orderBy('month', 'ASC')
        ->get()
        ->map(function ($item) {
            $monthName = date('F', mktime(0, 0, 0, $item->month, 1));
            return [
                'date' => $monthName,
                'total_sales' => $item->total_sales,
                'total_revenue' => $item->total_revenue
            ];
        });
        
        return view('petugas.dashboard', compact(
            'totalPembelian',
            'totalPenjualan',
            'totalProduk',
            'latestPembelian',
            'latestPenjualan',
            'lowStockProducts',
            'salesData'
        ));
    }
}
