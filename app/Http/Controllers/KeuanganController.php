<?php

namespace App\Http\Controllers;

use App\Models\Keuangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class KeuanganController extends Controller
{
    public function index(Request $request)
    {
        $query = Keuangan::with('user')->latest();

        // Filter by type
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        // Filter by category
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            try {
                $query->whereDate('tanggal', '>=', $request->start_date);
            } catch (\Exception $e) {
                // Jika format tanggal tidak valid, abaikan filter
            }
        }
        
        if ($request->filled('end_date')) {
            try {
                $query->whereDate('tanggal', '<=', $request->end_date);
            } catch (\Exception $e) {
                // Jika format tanggal tidak valid, abaikan filter
            }
        }

        $keuangans = $query->paginate(10);
        
        // Calculate summary
        $totalPemasukan = Keuangan::where('jenis', 'pemasukan')
            ->when($request->filled('start_date'), function($q) use ($request) {
                try {
                    return $q->whereDate('tanggal', '>=', $request->start_date);
                } catch (\Exception $e) {
                    return $q;
                }
            })
            ->when($request->filled('end_date'), function($q) use ($request) {
                try {
                    return $q->whereDate('tanggal', '<=', $request->end_date);
                } catch (\Exception $e) {
                    return $q;
                }
            })
            ->sum('jumlah');

        $totalPengeluaran = Keuangan::where('jenis', 'pengeluaran')
            ->when($request->filled('start_date'), function($q) use ($request) {
                try {
                    return $q->whereDate('tanggal', '>=', $request->start_date);
                } catch (\Exception $e) {
                    return $q;
                }
            })
            ->when($request->filled('end_date'), function($q) use ($request) {
                try {
                    return $q->whereDate('tanggal', '<=', $request->end_date);
                } catch (\Exception $e) {
                    return $q;
                }
            })
            ->sum('jumlah');

        $saldoAkhir = $totalPemasukan - $totalPengeluaran;

        $kategoriList = Keuangan::distinct()->pluck('kategori');

        return view('keuangan.index', compact('keuangans', 'totalPemasukan', 'totalPengeluaran', 'saldoAkhir', 'kategoriList'));
    }

    public function create()
    {
        return view('keuangan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis' => 'required|in:pemasukan,pengeluaran',
            'kategori' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'tanggal' => 'required|date',
            'bukti_transaksi' => 'nullable|image|max:2048'
        ]);

        $data = $request->all();
        $data['user_id'] = auth()->id();

        if ($request->hasFile('bukti_transaksi')) {
            $file = $request->file('bukti_transaksi');
            $path = $file->store('bukti-transaksi', 'public');
            $data['bukti_transaksi'] = $path;
        }

        Keuangan::create($data);

        return redirect()->route('keuangan.index')
            ->with('success', 'Data keuangan berhasil ditambahkan');
    }

    public function show(Keuangan $keuangan)
    {
        return view('keuangan.show', compact('keuangan'));
    }

    public function edit(Keuangan $keuangan)
    {
        return view('keuangan.edit', compact('keuangan'));
    }

    public function update(Request $request, Keuangan $keuangan)
    {
        $request->validate([
            'jenis' => 'required|in:pemasukan,pengeluaran',
            'kategori' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'tanggal' => 'required|date',
            'bukti_transaksi' => 'nullable|image|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('bukti_transaksi')) {
            // Delete old file if exists
            if ($keuangan->bukti_transaksi) {
                Storage::disk('public')->delete($keuangan->bukti_transaksi);
            }

            $file = $request->file('bukti_transaksi');
            $path = $file->store('bukti-transaksi', 'public');
            $data['bukti_transaksi'] = $path;
        }

        $keuangan->update($data);

        return redirect()->route('keuangan.index')
            ->with('success', 'Data keuangan berhasil diperbarui');
    }

    public function destroy(Keuangan $keuangan)
    {
        if ($keuangan->bukti_transaksi) {
            Storage::disk('public')->delete($keuangan->bukti_transaksi);
        }

        $keuangan->forceDelete();

        return redirect()->route('keuangan.index')
            ->with('success', 'Data keuangan berhasil dihapus');
    }

    public function export(Request $request)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set judul
        $sheet->setCellValue('A1', 'LAPORAN KEUANGAN');
        $sheet->mergeCells('A1:F1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Set header kolom
        $headers = ['No', 'Tanggal', 'Jenis', 'Kategori', 'Keterangan', 'Nominal'];
        foreach (range('A', 'F') as $key => $column) {
            $sheet->setCellValue($column . '3', $headers[$key]);
            $sheet->getStyle($column . '3')->getFont()->setBold(true);
            
            // Atur border untuk header
            $sheet->getStyle($column . '3')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            
            // Atur lebar kolom
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Ambil data keuangan
        $query = Keuangan::query();
        
        if ($request->has('jenis') && $request->jenis != '') {
            $query->where('jenis', $request->jenis);
        }
        
        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('kategori', $request->kategori);
        }
        
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }
        
        $keuangan = $query->orderBy('tanggal', 'desc')->get();

        // Isi data
        $row = 4;
        $total = 0;
        foreach ($keuangan as $index => $item) {
            $nominal = $item->jenis === 'Pengeluaran' ? -$item->jumlah : $item->jumlah;
            $total += $nominal;
            
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, Carbon::parse($item->tanggal)->format('d/m/Y'));
            $sheet->setCellValue('C' . $row, $item->jenis);
            $sheet->setCellValue('D' . $row, $item->kategori);
            $sheet->setCellValue('E' . $row, $item->keterangan);
            $sheet->setCellValue('F' . $row, abs($nominal));
            
            // Format angka untuk kolom nominal
            $sheet->getStyle('F' . $row)->getNumberFormat()->setFormatCode('"Rp "#,##0');
            
            // Atur warna text berdasarkan jenis transaksi
            if ($item->jenis === 'Pengeluaran') {
                $sheet->getStyle('F' . $row)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED));
            } else {
                $sheet->getStyle('F' . $row)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_DARKGREEN));
            }
            
            // Atur border untuk setiap cell
            foreach (range('A', 'F') as $column) {
                $sheet->getStyle($column . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            }
            
            $row++;
        }

        // Tambah total di bawah
        $row++;
        $sheet->setCellValue('E' . $row, 'TOTAL:');
        $sheet->setCellValue('F' . $row, number_format(abs($total), 0, ',', '.'));
        $sheet->getStyle('E' . $row . ':F' . $row)->getFont()->setBold(true);
        $sheet->getStyle('F' . $row)->getNumberFormat()->setFormatCode('"Rp "#,##0');
        
        // Atur border untuk total
        $sheet->getStyle('E' . $row . ':F' . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        
        // Atur warna total berdasarkan nilainya
        if ($total < 0) {
            $sheet->getStyle('F' . $row)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED));
        } else {
            $sheet->getStyle('F' . $row)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_DARKGREEN));
        }

        // Buat file Excel
        $writer = new Xlsx($spreadsheet);
        $filename = 'Laporan_Keuangan_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit;
    }

    public function laporan(Request $request)
    {
        try {
            $query = Keuangan::with('user');
            
            // Filter by date range
            if ($request->filled('start_date')) {
                $query->whereDate('tanggal', '>=', $request->start_date);
            }
            
            if ($request->filled('end_date')) {
                $query->whereDate('tanggal', '<=', $request->end_date);
            }

            // Get summary data
            $totalPemasukan = (clone $query)->where('jenis', 'pemasukan')->sum('jumlah');
            $totalPengeluaran = (clone $query)->where('jenis', 'pengeluaran')->sum('jumlah');
            $saldoAkhir = $totalPemasukan - $totalPengeluaran;

            // Get data by category with percentage
            $pemasukanPerKategori = (clone $query)
                ->where('jenis', 'pemasukan')
                ->selectRaw('kategori, sum(jumlah) as total')
                ->groupBy('kategori')
                ->get()
                ->map(function ($item) use ($totalPemasukan) {
                    $item->percentage = $totalPemasukan > 0 ? round(($item->total / $totalPemasukan) * 100, 2) : 0;
                    return $item;
                });

            $pengeluaranPerKategori = (clone $query)
                ->where('jenis', 'pengeluaran')
                ->selectRaw('kategori, sum(jumlah) as total')
                ->groupBy('kategori')
                ->get()
                ->map(function ($item) use ($totalPengeluaran) {
                    $item->percentage = $totalPengeluaran > 0 ? round(($item->total / $totalPengeluaran) * 100, 2) : 0;
                    return $item;
                });

            // Get monthly trend
            $trendBulanan = (clone $query)
                ->selectRaw('YEAR(tanggal) as tahun, MONTH(tanggal) as bulan, jenis, SUM(jumlah) as total')
                ->groupBy('tahun', 'bulan', 'jenis')
                ->orderBy('tahun')
                ->orderBy('bulan')
                ->get();

            // Debug log
            \Log::info('Trend Bulanan Data:', ['data' => $trendBulanan->toArray()]);
            
            $trendBulanan = $trendBulanan->groupBy(['tahun', 'bulan']);

            // Calculate financial metrics
            $currentRatio = $totalPemasukan > 0 ? round($saldoAkhir / $totalPemasukan * 100, 2) : 0;
            $profitMargin = $totalPemasukan > 0 ? round(($totalPemasukan - $totalPengeluaran) / $totalPemasukan * 100, 2) : 0;
            
            // Calculate growth rates
            $previousPeriodQuery = clone $query;
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $currentPeriodDays = (strtotime($request->end_date) - strtotime($request->start_date)) / (60 * 60 * 24);
                $previousStart = date('Y-m-d', strtotime($request->start_date . ' -' . $currentPeriodDays . ' days'));
                $previousEnd = date('Y-m-d', strtotime($request->start_date . ' -1 day'));
                
                $previousPeriodQuery->whereDate('tanggal', '>=', $previousStart)
                                  ->whereDate('tanggal', '<=', $previousEnd);
            } else {
                $previousPeriodQuery->whereDate('tanggal', '<', now()->startOfMonth());
            }

            $previousPemasukan = $previousPeriodQuery->where('jenis', 'pemasukan')->sum('jumlah');
            $previousPengeluaran = $previousPeriodQuery->where('jenis', 'pengeluaran')->sum('jumlah');

            $pemasukanGrowth = $previousPemasukan > 0 ? 
                round((($totalPemasukan - $previousPemasukan) / $previousPemasukan) * 100, 2) : 0;
            $pengeluaranGrowth = $previousPengeluaran > 0 ? 
                round((($totalPengeluaran - $previousPengeluaran) / $previousPengeluaran) * 100, 2) : 0;

            // Get recent transactions
            $recentTransactions = (clone $query)
                ->orderBy('tanggal', 'desc')
                ->limit(10)
                ->get();

            // Calculate quarterly data
            $quarterlyData = $this->calculateQuarterlyData($query);

            // Calculate daily averages
            $dailyAverages = $this->calculateDailyAverages($query);

            // Get top categories
            $topCategories = $this->getTopCategories($query);
            
            return view('keuangan.laporan', compact(
                'totalPemasukan',
                'totalPengeluaran',
                'saldoAkhir',
                'pemasukanPerKategori',
                'pengeluaranPerKategori',
                'trendBulanan',
                'currentRatio',
                'profitMargin',
                'pemasukanGrowth',
                'pengeluaranGrowth',
                'recentTransactions',
                'quarterlyData',
                'dailyAverages',
                'topCategories'
            ));
        } catch (\Exception $e) {
            \Log::error('Error in laporan method: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            return response()->view('errors.custom', [
                'title' => 'Error',
                'message' => 'Terjadi kesalahan saat memuat laporan keuangan. ' . ($e->getMessage())
            ], 500);
        }
    }

    private function calculateDailyAverages($query)
    {
        $result = [
            'pemasukan' => 0,
            'pengeluaran' => 0
        ];

        try {
            // Ambil rentang tanggal
            $dateRange = (clone $query)->selectRaw('MIN(tanggal) as min_date, MAX(tanggal) as max_date')->first();
            
            if ($dateRange->min_date && $dateRange->max_date) {
                // Hitung jumlah hari
                $days = max(1, Carbon::parse($dateRange->max_date)->diffInDays(Carbon::parse($dateRange->min_date)) + 1);
                
                // Hitung total pemasukan dan pengeluaran
                $totalPemasukan = (clone $query)->where('jenis', 'pemasukan')->sum('jumlah');
                $totalPengeluaran = (clone $query)->where('jenis', 'pengeluaran')->sum('jumlah');
                
                // Hitung rata-rata harian
                $result['pemasukan'] = $days > 0 ? round($totalPemasukan / $days) : $totalPemasukan;
                $result['pengeluaran'] = $days > 0 ? round($totalPengeluaran / $days) : $totalPengeluaran;
            }
        } catch (\Exception $e) {
            \Log::error('Error calculating daily averages: ' . $e->getMessage());
        }

        return $result;
    }

    private function getTopCategories($query)
    {
        $pemasukan = (clone $query)
            ->where('jenis', 'pemasukan')
            ->selectRaw('kategori, sum(jumlah) as total')
            ->groupBy('kategori')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $pengeluaran = (clone $query)
            ->where('jenis', 'pengeluaran')
            ->selectRaw('kategori, sum(jumlah) as total')
            ->groupBy('kategori')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return [
            'pemasukan' => $pemasukan,
            'pengeluaran' => $pengeluaran
        ];
    }

    private function calculateQuarterlyData($query)
    {
        $currentYear = date('Y');
        $quarters = [];
        
        for ($quarter = 1; $quarter <= 4; $quarter++) {
            $startMonth = ($quarter - 1) * 3 + 1;
            $endMonth = $quarter * 3;
            
            $quarterData = (clone $query)
                ->whereYear('tanggal', $currentYear)
                ->whereRaw("MONTH(tanggal) BETWEEN ? AND ?", [$startMonth, $endMonth]);
            
            $quarters[$quarter] = [
                'pemasukan' => $quarterData->where('jenis', 'pemasukan')->sum('jumlah'),
                'pengeluaran' => $quarterData->where('jenis', 'pengeluaran')->sum('jumlah'),
                'profit' => 0
            ];
            
            $quarters[$quarter]['profit'] = $quarters[$quarter]['pemasukan'] - $quarters[$quarter]['pengeluaran'];
        }
        
        return $quarters;
    }

    public function exportLaporan(Request $request)
    {
        try {
            // Base query
            $query = Keuangan::query();
            
            // Apply date filters
            if ($request->filled('start_date')) {
                $query->whereDate('tanggal', '>=', $request->start_date);
            }
            
            if ($request->filled('end_date')) {
                $query->whereDate('tanggal', '<=', $request->end_date);
            }

            // Get all data first
            $keuangan = $query->orderBy('tanggal')->get();

            // Calculate totals (case insensitive)
            $totalPemasukan = $keuangan->filter(function($item) {
                return strtolower($item->jenis) === 'pemasukan';
            })->sum('jumlah') ?? 0;

            $totalPengeluaran = $keuangan->filter(function($item) {
                return strtolower($item->jenis) === 'pengeluaran';
            })->sum('jumlah') ?? 0;

            $saldoAkhir = $totalPemasukan - $totalPengeluaran;

            // Calculate additional metrics
            $startDate = $request->filled('start_date') ? Carbon::parse($request->start_date) : Carbon::now();
            $endDate = $request->filled('end_date') ? Carbon::parse($request->end_date) : Carbon::now();
            $totalDays = max(1, $startDate->diffInDays($endDate) + 1); // Minimal 1 hari

            $profitMargin = $totalPemasukan > 0 ? ($saldoAkhir / $totalPemasukan * 100) : 0;
            $avgPengeluaranPerHari = $totalPengeluaran / $totalDays;
            $avgPemasukanPerHari = $totalPemasukan / $totalDays;

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Set title
            $sheet->setCellValue('A1', 'LAPORAN KEUANGAN');
            $sheet->mergeCells('A1:E1');
            $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
            $sheet->getStyle('A1')->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            // Set period
            $periode = 'Periode: ' . $startDate->format('d/m/Y') . ' - ' . $endDate->format('d/m/Y');
            $sheet->setCellValue('A2', $periode);
            $sheet->mergeCells('A2:E2');
            $sheet->getStyle('A2')->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            // Set headers
            $headers = ['Tanggal', 'Jenis', 'Kategori', 'Keterangan', 'Jumlah'];
            foreach ($headers as $key => $header) {
                $column = chr(65 + $key);
                $sheet->setCellValue($column . '4', $header);
                $sheet->getStyle($column . '4')->getFont()->setBold(true);
                $sheet->getStyle($column . '4')->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('E2EFDA');
                $sheet->getStyle($column . '4')->getBorders()
                    ->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle($column . '4')->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }

            // Fill data
            $row = 5;
            foreach ($keuangan as $item) {
                $sheet->setCellValue('A' . $row, Carbon::parse($item->tanggal)->format('d/m/Y'));
                $sheet->setCellValue('B' . $row, ucfirst(strtolower($item->jenis)));
                $sheet->setCellValue('C' . $row, $item->kategori ?? '-');
                $sheet->setCellValue('D' . $row, $item->keterangan ?? '-');
                $sheet->setCellValue('E' . $row, (float)$item->jumlah);

                // Format number
                $sheet->getStyle('E' . $row)->getNumberFormat()
                    ->setFormatCode('"Rp "#,##0');

                // Set color based on transaction type
                $textColor = strtolower($item->jenis) === 'pemasukan' ? '28a745' : 'dc3545';
                $sheet->getStyle('E' . $row)->getFont()
                    ->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color($textColor));

                // Add borders
                $sheet->getStyle('A'.$row.':E'.$row)->getBorders()
                    ->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                $row++;
            }

            // Add summary
            $row += 2;
            $sheet->setCellValue('A' . $row, 'RINGKASAN');
            $sheet->mergeCells('A'.$row.':E'.$row);
            $sheet->getStyle('A'.$row)->getFont()->setBold(true);
            $sheet->getStyle('A'.$row.':E'.$row)->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setRGB('E2EFDA');
            $sheet->getStyle('A'.$row.':E'.$row)->getBorders()
                ->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

            // Total Pemasukan
            $row++;
            $sheet->setCellValue('A' . $row, 'Total Pemasukan');
            $sheet->mergeCells('A'.$row.':D'.$row);
            $sheet->setCellValue('E' . $row, (float)$totalPemasukan);
            $sheet->getStyle('E' . $row)->getNumberFormat()
                ->setFormatCode('"Rp "#,##0');
            $sheet->getStyle('A'.$row.':E'.$row)->getBorders()
                ->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

            // Total Pengeluaran
            $row++;
            $sheet->setCellValue('A' . $row, 'Total Pengeluaran');
            $sheet->mergeCells('A'.$row.':D'.$row);
            $sheet->setCellValue('E' . $row, (float)$totalPengeluaran);
            $sheet->getStyle('E' . $row)->getNumberFormat()
                ->setFormatCode('"Rp "#,##0');
            $sheet->getStyle('A'.$row.':E'.$row)->getBorders()
                ->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

            // Saldo Akhir
            $row++;
            $sheet->setCellValue('A' . $row, 'Saldo Akhir');
            $sheet->mergeCells('A'.$row.':D'.$row);
            $sheet->setCellValue('E' . $row, (float)$saldoAkhir);
            $sheet->getStyle('E' . $row)->getNumberFormat()
                ->setFormatCode('"Rp "#,##0');
            $sheet->getStyle('A'.$row.':E'.$row)->getFont()->setBold(true);
            $sheet->getStyle('A'.$row.':E'.$row)->getBorders()
                ->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

            // Add empty row
            $row += 2;

            // Add profit margin
            $row++;
            $sheet->setCellValue('A' . $row, 'Profit Margin (%)');
            $sheet->mergeCells('A'.$row.':D'.$row);
            $sheet->setCellValue('E' . $row, (float)$profitMargin);
            $sheet->getStyle('E' . $row)->getNumberFormat()
                ->setFormatCode('0.00"%"');
            $sheet->getStyle('A'.$row.':E'.$row)->getBorders()
                ->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

            // Add average expenses per day
            $row++;
            $sheet->setCellValue('A' . $row, 'Rata-rata Pengeluaran per Hari');
            $sheet->mergeCells('A'.$row.':D'.$row);
            $sheet->setCellValue('E' . $row, (float)$avgPengeluaranPerHari);
            $sheet->getStyle('E' . $row)->getNumberFormat()
                ->setFormatCode('"Rp "#,##0');
            $sheet->getStyle('A'.$row.':E'.$row)->getBorders()
                ->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

            // Add average income per day
            $row++;
            $sheet->setCellValue('A' . $row, 'Rata-rata Pemasukan per Hari');
            $sheet->mergeCells('A'.$row.':D'.$row);
            $sheet->setCellValue('E' . $row, (float)$avgPemasukanPerHari);
            $sheet->getStyle('E' . $row)->getNumberFormat()
                ->setFormatCode('"Rp "#,##0');
            $sheet->getStyle('A'.$row.':E'.$row)->getBorders()
                ->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

            // Set column width
            foreach (range('A', 'E') as $column) {
                $sheet->getColumnDimension($column)->setAutoSize(true);
            }

            // Create Excel file
            $writer = new Xlsx($spreadsheet);
            $filename = 'Laporan_Keuangan_' . date('Y-m-d_H-i-s') . '.xlsx';

            ob_end_clean(); // Clear any previous output
            ob_start(); // Start output buffering

            $writer->save('php://output');
            $content = ob_get_contents(); // Get the contents
            ob_end_clean(); // Clear the buffer

            return response($content)
                ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
                ->header('Content-Disposition', 'attachment; filename="'.$filename.'"')
                ->header('Content-Length', strlen($content))
                ->header('Cache-Control', 'max-age=0');

        } catch (\Exception $e) {
            \Log::error('Excel Export Error: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return back()->with('error', 'Terjadi kesalahan saat mengexport laporan: ' . $e->getMessage());
        }
    }
}
