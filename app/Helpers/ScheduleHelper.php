<?php

namespace App\Helpers;

use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;

class ScheduleHelper
{
    public static function getScheduleByUserIdToday($userId)
    {
        return Schedule::with('scheduleXUsers:schedule_id,user_id,is_wfh')
            ->whereHas('scheduleXUsers', function (Builder $query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->where('date', Carbon::now()->format('Y-m-d'))
            ->first();
    }
}
