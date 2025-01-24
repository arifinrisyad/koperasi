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
            
        // Get daily sales chart data for the last 7 days
        $salesData = Penjualan::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as total_sales'),
            DB::raw('SUM(total_harga) as total_revenue')
        )
        ->groupBy('date')
        ->orderBy('date', 'DESC')
        ->take(7)
        ->get()
        ->reverse();
        
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
