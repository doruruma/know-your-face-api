<?php

namespace App\Exports;

use App\Helpers\Util;
use App\Models\LeaveDetail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LeaveExport implements FromCollection, WithHeadings
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
        $data = LeaveDetail::with(
            'leave',
            'workstate',
            'leave.leaveType',
            'leave.approver',
        )->where([
            ['created_at', '>=', $this->startDate],
            ['created_at', '<=', $this->endDate]
        ])->get();
        return $data->map(function ($item) {
            $leave = $item->leave;
            return [
                'id' => $item->id,
                'leave_type' => $leave->leaveType->name,
                'workstate' => $item->workstate->name,
                'user' => $leave->user->name,
                'approver' => $leave->approver->name ?? "-",
                'approved_date' => Util::formatDateTime($leave->approved_date),
                'date' => Util::formatDateTime($item->leave_date),
                'notes' => $leave->notes,
                'created_at' => Util::formatDateTime($item->created_at),
                'updated_at' => Util::formatDateTime($item->updated_at)
            ];
        });
    }

    public function headings(): array
    {
        return [
            'id',
            'leave_type',
            'workstate',
            'user',
            'approver',
            'approved_date',
            'date',
            'notes',
            'created_at',
            'updated_at',
        ];
    }
}
