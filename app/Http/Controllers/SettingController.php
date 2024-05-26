<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingFormRequest;
use App\Http\Resources\SettingResource;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;

class SettingController extends Controller
{
    public function get(): JsonResponse
    {
        $data = Setting::find(1);
        return (new SettingResource($data))->response();
    }

    public function update(SettingFormRequest $request): JsonResponse
    {
        $setting = Setting::find(1);
        $setting->office_address = $request->office_address;
        $setting->office_longitude = $request->office_longitude;
        $setting->office_latitude = $request->office_latitude;
        $setting->max_distance = $request->max_distance;
        $setting->save();
        return response()->json([
            'message' => 'Settings Updated'
        ]);
    }
}
