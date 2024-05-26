<?php

namespace App\Helpers;

use App\Models\RemoteSchedule;
use Carbon\Carbon;

class RemoteScheduleHelper
{
    public static function isTodayRemote($userId)
    {
        return RemoteSchedule::select('id')
            ->where([
                ['user_id', $userId],
                ['date', Carbon::now()->format('Y-m-d')]
            ])->first();
    }
}
