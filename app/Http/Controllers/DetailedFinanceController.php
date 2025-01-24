<?php

namespace App\Http\Controllers;

use App\Models\Keuangan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DetailedFinanceController extends Controller
{
    public function cashFlow()
    {
        $currentYear = Carbon::now()->year;
        $monthlyData = Keuangan::selectRaw('MONTH(tanggal) as month, 
            SUM(CASE WHEN jenis = "pemasukan" THEN jumlah ELSE 0 END) as total_pemasukan,
            SUM(CASE WHEN jenis = "pengeluaran" THEN jumlah ELSE 0 END) as total_pengeluaran')
            ->whereYear('tanggal', $currentYear)
            ->groupBy(DB::raw('MONTH(tanggal)'))
            ->orderBy('month')
            ->get();

        return view('keuangan.cash-flow', compact('monthlyData', 'currentYear'));
    }

    public function categoryAnalysis()
    {
        $expenses = Keuangan::where('jenis', 'pengeluaran')
            ->select('kategori', DB::raw('SUM(jumlah) as total'))
            ->groupBy('kategori')
            ->orderBy('total', 'desc')
            ->get();

        $income = Keuangan::where('jenis', 'pemasukan')
            ->select('kategori', DB::raw('SUM(jumlah) as total'))
            ->groupBy('kategori')
            ->orderBy('total', 'desc')
            ->get();

        return view('keuangan.category-analysis', compact('expenses', 'income'));
    }

    public function financialStatement()
    {
        $year = request('year', Carbon::now()->year);
        $month = request('month', Carbon::now()->month);

        $transactions = Keuangan::whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->orderBy('tanggal')
            ->get();

        $openingBalance = Keuangan::whereDate('tanggal', '<', Carbon::create($year, $month, 1))
            ->sum(DB::raw('CASE WHEN jenis = "pemasukan" THEN jumlah ELSE -jumlah END'));

        $summary = [
            'opening_balance' => $openingBalance,
            'total_income' => $transactions->where('jenis', 'pemasukan')->sum('jumlah'),
            'total_expense' => $transactions->where('jenis', 'pengeluaran')->sum('jumlah'),
            'closing_balance' => $openingBalance + 
                $transactions->where('jenis', 'pemasukan')->sum('jumlah') - 
                $transactions->where('jenis', 'pengeluaran')->sum('jumlah')
        ];

        return view('keuangan.financial-statement', compact('transactions', 'summary', 'year', 'month'));
    }

    public function trends()
    {
        $dailyTrends = Keuangan::selectRaw('DATE(tanggal) as date,
            SUM(CASE WHEN jenis = "pemasukan" THEN jumlah ELSE 0 END) as income,
            SUM(CASE WHEN jenis = "pengeluaran" THEN jumlah ELSE 0 END) as expense')
            ->whereBetween('tanggal', [Carbon::now()->subDays(30), Carbon::now()])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('keuangan.trends', compact('dailyTrends'));
    }
}
