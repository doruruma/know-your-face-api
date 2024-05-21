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

    // calculate distance between 2 long & lat using haversine great circle distance
    public static function calculateDistanceOfCoordinates(
        $latitudeFrom,
        $longitudeFrom,
        $latitudeTo,
        $longitudeTo
    ) {
        $earthRadius = 6371000;
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);
        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;
        $a = sin($latDelta / 2) * sin($latDelta / 2) +
            cos($latFrom) * cos($latTo) *
            sin($lonDelta / 2) * sin($lonDelta / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;
        return $distance;
    }
}
