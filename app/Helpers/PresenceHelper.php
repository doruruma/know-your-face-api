<?php

namespace App\Helpers;

use App\Models\Presence;
use Carbon\Carbon;

class PresenceHelper
{
    public static function isUserClockInToday($userId)
    {
        return Presence::select('id', 'face_image_clock_out')
            ->where('user_id', $userId)
            ->whereDate('created_at', Carbon::now()->format('Y-m-d'))
            ->first();
    }
}
