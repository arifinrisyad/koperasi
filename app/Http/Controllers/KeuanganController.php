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
            $sheet->getStyle('F' . $row)->getNumberFormat()->setFormatCode('#,##0');
            
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
        $sheet->getStyle('F' . $row)->getNumberFormat()->setFormatCode('#,##0');
        
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
}
