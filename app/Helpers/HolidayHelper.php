<?php

namespace App\Helpers;

use App\Models\Holiday;
use Carbon\Carbon;

class HolidayHelper
{
    public static function isTodayHoliday()
    {
        return Holiday::select('id')->where('date', Carbon::now()->format('Y-m-d'))->first();
    }
}
