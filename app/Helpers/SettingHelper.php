<?php

namespace App\Helpers;

use App\Models\Setting;

class SettingHelper
{
    public static function getSetting()
    {
        return Setting::find(1);
    }
}
