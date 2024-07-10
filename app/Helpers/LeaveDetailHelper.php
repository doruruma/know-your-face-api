<?php

namespace App\Helpers;

use App\Models\LeaveDetail;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;

class LeaveDetailHelper
{
    public static function bulkStore($leaveId, $dates)
    {
        $arr = [];
        foreach ($dates as $date) {
            array_push($arr, [
                'workstate_id' => Constant::$STATE_REQUESTED_ID,
                'leave_id' => $leaveId,
                'leave_date' => $date,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }
        LeaveDetail::insert($arr);
    }

    public static function isTodayLeave($userId)
    {
        return LeaveDetail::select('id')
            ->whereHas('leave', function (Builder $query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->where([
                ['workstate_id', Constant::$STATE_APPROVED_ID],
                ['leave_date', Carbon::now()->format('Y-m-d')]
            ])
            ->first();
    }
}
