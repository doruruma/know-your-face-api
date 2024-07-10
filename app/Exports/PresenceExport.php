<?php

namespace App\Exports;

use App\Helpers\Util;
use App\Models\Presence;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PresenceExport implements FromCollection, WithHeadings
{
    private $startDate;
    private $endDate;

    public function __construct($startDate = '', $endDate = '')
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        $data = Presence::with('user')
            ->where([
                ['created_at', '>=', $this->startDate],
                ['created_at', '<=', $this->endDate]
            ])->get();
        return $data->map(function ($item) {
            return [
                'id' => $item->id,
                'pegawai' => $item->user->name,
                'terlambat' => $item->is_late == 1 ? 'Terlambat' : 'Tepat Waktu',
                'schedule_time_in' => $item->schedule_time_in,
                'schedule_time_out' => $item->schedule_time_out,
                'time_in' => Util::formatDateTime($item->time_in, 'H:i'),
                'time_out' => Util::formatDateTime($item->time_out, 'H:i'),
                'longitude_clock_in' => $item->longitude_clock_in,
                'longitude_clock_out' => $item->longitude_clock_out,
                'latitude_clock_in' => $item->latitude_clock_in,
                'latitude_clock_out' => $item->latitude_clock_out,
                'clock_in_distance' => $item->clock_in_distance,
                'clock_out_distance' => $item->clock_out_distance,
                'wfh' => $item->is_remote == 1 ? 'Ya' : 'Tidak',
                'created_at' => Util::formatDateTime($item->created_at),
                'updated_at' => Util::formatDateTime($item->updated_at)
            ];
        });
    }

    public function headings(): array
    {
        return [
            'id',
            'terlambat',
            'pegawai',
            'schedule_time_in',
            'schedule_time_out',
            'time_in',
            'time_out',
            'longitude_clock_in',
            'longitude_clock_out',
            'latitude_clock_in',
            'latitude_clock_out',
            'clock_in_distance',
            'clock_out_distance',
            'wfh',
            'created_at',
            'updated_at',
        ];
    }
}
