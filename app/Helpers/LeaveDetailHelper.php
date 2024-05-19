<?php

namespace App\Helpers;

use App\Models\LeaveDetail;
use Carbon\Carbon;

class LeaveDetailHelper
{
    public static function bulkStore($leaveId, $dates)
    {
        $arr = [];
        foreach ($dates as $date) {
            array_push($arr, [
                'leave_id' => $leaveId,
                'leave_date' => $date,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }
        LeaveDetail::insert($arr);
    }
}
