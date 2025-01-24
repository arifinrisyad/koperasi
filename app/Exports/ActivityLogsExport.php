<?php

namespace App\Exports;

use App\Models\ActivityLog;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ActivityLogsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return ActivityLog::with('user')->latest()->get();
    }

    public function headings(): array
    {
        return [
            'Waktu',
            'User',
            'Tipe Aktivitas',
            'Deskripsi',
            'IP Address',
            'User Agent'
        ];
    }

    public function map($activityLog): array
    {
        return [
            $activityLog->created_at->format('d/m/Y H:i:s'),
            $activityLog->user->name,
            ucfirst($activityLog->activity_type),
            $activityLog->description,
            $activityLog->ip_address,
            $activityLog->user_agent
        ];
    }
}
