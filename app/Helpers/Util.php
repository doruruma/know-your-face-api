<?php

namespace App\Helpers;

use Carbon\Carbon;

class Util
{
    public static function generateNumber($length = 1)
    {
        $randomNumber = '';
        for ($i = 0; $i < $length; $i++) {
            $randomNumber .= rand(0, 9);
        }
        return $randomNumber;
    }

    public static function formatDateTime($dateTime, $format = 'd-m-Y H:i')
    {
        return Carbon::parse($dateTime)->format($format);
    }
}
