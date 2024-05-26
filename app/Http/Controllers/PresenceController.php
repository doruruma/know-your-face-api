<?php

namespace App\Http\Controllers;

use app\Helpers\Constant;
use App\Helpers\HolidayHelper;
use App\Helpers\PresenceHelper;
use App\Helpers\RemoteScheduleHelper;
use App\Helpers\SettingHelper;
use App\Helpers\Util;
use App\Http\Requests\ClockInFormRequest;
use App\Http\Requests\ClockOutFormRequest;
use App\Http\Resources\PresenceCollection;
use App\Http\Resources\PresenceResource;
use App\Models\Presence;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PresenceController extends Controller
{
    public function getAll(Request $request): PresenceCollection
    {
        $data = new Presence;
        if ($request->has('user_id') && $request->user_id != '')
            $data = $data->where('user_id', $request->user_id);
        if (
            ($request->has('start_date') && $request->start_date != '') &&
            ($request->has('end_date') && $request->end_date != '')
        )
            $data = $data->whereDate('created_at', '>=', $request->start_date)
                ->whereDate('created_at', '<=', $request->end_date);
        $data = $data->orderBy('created_at', 'DESC')->paginate(Constant::$PAGE_SIZE);
        return new PresenceCollection($data);
    }

    public function clockIn(ClockInFormRequest $request): JsonResponse
    {
        $isTodayHoliday = HolidayHelper::isTodayHoliday();
        if ($isTodayHoliday || Util::isWeekend())
            return response()->json([
                'message' => 'Tidak bisa absen pada hari libur'
            ], 500);
        if (PresenceHelper::isUserClockInToday($request->user_id))
            return response()->json([
                'message' => 'Anda sudah absen hari ini'
            ], 500);
        $setting = SettingHelper::getSetting();
        $isTodayRemote = RemoteScheduleHelper::isTodayRemote($request->user_id);
        if (!$isTodayRemote) {
            $distance = Util::calculateDistanceOfCoordinates(
                $setting->latitude,
                $setting->longitude,
                $request->latitude_clock_in,
                $request->longitude_clock_in
            );
            $maxDistance = $setting->max_distance;
            if ($distance > $maxDistance)
                return response()->json([
                    'message' => "Jarak anda dengan lokasi kantor harus dibawah $maxDistance"
                ], 500);
        }
        $clockIn = new Presence;
        try {
            if ($request->hasFile('face_image_clock_in')) {
                $file = $request->file('face_image_clock_in');
                $faceImage = $request->user_id . '-clock-in' . $file->getClientOriginalName();
                $file->storeAs('presences', $faceImage);
                $clockIn->face_image_clock_in = $faceImage;
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json([
                'message' => 'Gagal mengupload foto wajah'
            ], 500);
        }
        $clockIn->user_id = $request->user_id;
        $clockIn->schedule_time_in = Constant::$CLOCK_IN_TIME;
        $clockIn->schedule_time_out = Constant::$CLOCK_OUT_TIME;
        $clockIn->time_in = Carbon::now()->format('H:i');
        $clockIn->longitude_clock_in = $request->longitude_clock_in;
        $clockIn->latitude_clock_in = $request->latitude_clock_in;
        $clockIn->is_remote = $isTodayRemote ? 1 : 0;
        $clockIn->save();
        return (new PresenceResource($clockIn))->response();
    }

    public function clockOut(ClockOutFormRequest $request): JsonResponse
    {
        $presence = PresenceHelper::getTodayPresence($request->user_id);
        if (!$presence)
            return response()->json([
                'message' => 'Anda belum absen masuk hari ini'
            ], 500);
        if ($presence->face_image_clock_out)
            return response()->json([
                'message' => 'Anda sudah absen pulang hari ini'
            ], 500);
        $setting = SettingHelper::getSetting();
        if ($presence->is_remote == 0) {
            $distance = Util::calculateDistanceOfCoordinates(
                $setting->latitude,
                $setting->longitude,
                $request->latitude_clock_in,
                $request->longitude_clock_in
            );
            $maxDistance = $setting->max_distance;
            if ($distance > $maxDistance)
                return response()->json([
                    'message' => "Jarak anda dengan lokasi kantor harus dibawah $maxDistance"
                ], 500);
        }
        try {
            if ($request->hasFile('face_image_clock_out')) {
                $file = $request->file('face_image_clock_out');
                $faceImage = $request->user_id . '-clock-out' . $file->getClientOriginalName();
                $file->storeAs('presences', $faceImage);
                $presence->face_image_clock_out = $faceImage;
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json([
                'message' => 'Gagal mengupload foto wajah'
            ], 500);
        }
        $presence->time_out = Carbon::now()->format('H:i');
        $presence->longitude_clock_out = $request->longitude_clock_out;
        $presence->latitude_clock_out = $request->latitude_clock_out;
        $presence->save();
        return (new PresenceResource($presence))->response();
    }
}
