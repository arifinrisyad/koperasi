<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->latest();

        // Filter berdasarkan tipe aktivitas
        if ($request->has('activity_type')) {
            $query->where('activity_type', $request->activity_type);
        }

        // Filter berdasarkan user
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter berdasarkan tanggal
        if ($request->has('date_start')) {
            $query->whereDate('created_at', '>=', $request->date_start);
        }
        if ($request->has('date_end')) {
            $query->whereDate('created_at', '<=', $request->date_end);
        }

        $logs = $query->paginate(20);

        return view('activity-logs.index', compact('logs'));
    }

    public function show(ActivityLog $activityLog)
    {
        $activityLog = ActivityLog::with('user')->findOrFail($activityLog->id);
        return view('activity-logs.show', compact('activityLog'));
    }

    public function destroy(ActivityLog $activityLog)
    {
        $activityLog->delete();
        return redirect()->route('activity-logs.index')
            ->with('success', 'Activity log berhasil dihapus');
    }

    public function clear()
    {
        ActivityLog::truncate();
        return redirect()->route('activity-logs.index')
            ->with('success', 'Semua activity logs berhasil dihapus');
    }

    public function export()
    {
        $logs = ActivityLog::with('user')->latest()->get();
        
        $filename = 'activity_logs_' . date('Y-m-d_H-i-s') . '.xls';
        
        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'max-age=0'
        ];

        $columns = [
            'WAKTU',
            'PENGGUNA',
            'TIPE AKTIVITAS',
            'DESKRIPSI',
            'ALAMAT IP',
            'DETAIL PROPERTI'
        ];

        $html = '
        <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <style>
                table {
                    border-collapse: collapse;
                    width: 100%;
                }
                th {
                    background-color: #4e73df;
                    color: white;
                    font-weight: bold;
                    text-align: center;
                    vertical-align: middle;
                    border: 1px solid #2e59d9;
                    padding: 5px;
                }
                td {
                    border: 1px solid #e3e6f0;
                    padding: 5px;
                    vertical-align: top;
                }
                .text-center {
                    text-align: center;
                }
                .text-wrap {
                    white-space: pre-wrap;
                }
            </style>
        </head>
        <body>
            <h2>LAPORAN ACTIVITY LOGS</h2>
            <p>Tanggal Export: ' . date('d/m/Y H:i:s') . '</p>
            <table>
                <thead>
                    <tr>';
        
        foreach ($columns as $column) {
            $html .= '<th>' . $column . '</th>';
        }
        
        $html .= '</tr></thead><tbody>';

        foreach ($logs as $log) {
            $properties = json_decode($log->properties, true);
            $formattedProperties = '';
            
            if (is_array($properties)) {
                foreach ($properties as $key => $value) {
                    if (is_array($value)) {
                        $value = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
                    }
                    $formattedProperties .= ucfirst($key) . ": " . $value . "\n";
                }
            }

            $activityType = match($log->activity_type) {
                'login' => 'Login',
                'logout' => 'Logout',
                'create' => 'Tambah Data',
                'update' => 'Update Data',
                'delete' => 'Hapus Data',
                default => ucfirst($log->activity_type)
            };

            $html .= '<tr>';
            $html .= '<td class="text-center">' . $log->created_at->format('d/m/Y H:i:s') . '</td>';
            $html .= '<td>' . (optional($log->user)->name ?? 'System') . '</td>';
            $html .= '<td class="text-center">' . $activityType . '</td>';
            $html .= '<td>' . strip_tags($log->description) . '</td>';
            $html .= '<td class="text-center">' . $log->ip_address . '</td>';
            $html .= '<td class="text-wrap">' . nl2br(htmlspecialchars(trim($formattedProperties))) . '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody></table>';
        $html .= '<p><small>* Data ini diekspor dari sistem pada ' . date('d/m/Y H:i:s') . '</small></p>';
        $html .= '</body></html>';

        return response($html, 200, $headers);
    }
}
