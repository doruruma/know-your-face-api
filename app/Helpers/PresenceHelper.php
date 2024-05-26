<?php

namespace App\Helpers;

use App\Models\Presence;
use Carbon\Carbon;

class PresenceHelper
{
    public static function getTodayPresence($userId)
    {
        return Presence::where('user_id', $userId)
            ->whereDate('created_at', Carbon::now()->format('Y-m-d'))
            ->first();
    }
}
