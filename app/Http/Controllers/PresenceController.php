<?php

namespace App\Http\Controllers;

use App\Exports\PresenceExport;
use App\Helpers\Constant;
use App\Helpers\HolidayHelper;
use App\Helpers\LeaveDetailHelper;
use App\Helpers\PresenceHelper;
use App\Helpers\RemoteScheduleHelper;
use App\Helpers\SettingHelper;
use App\Helpers\StorageHelper;
use App\Helpers\Util;
use App\Http\Requests\ClockInFormRequest;
use App\Http\Requests\ClockOutFormRequest;
use App\Http\Resources\PresenceCollection;
use App\Http\Resources\PresenceResource;
use App\Models\Presence;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class PresenceController extends Controller
{
    public function getAll(Request $request): PresenceCollection
    {
        $currentUser = Auth::user();
        $data = Presence::with('user:id,name');
        if ($currentUser->position_id != Constant::$MANAGEMENT_POSITION_ID)
            $data = $data->where('user_id', $currentUser->id);
        if ($request->has('is_remote') && $request->is_remote != '')
            $data = $data->where('is_remote', $request->is_remote);
        if ($request->has('is_late') && $request->is_late != '')
            $data = $data->where('is_late', $request->is_late);
        if (
            $currentUser->position_id == Constant::$MANAGEMENT_POSITION_ID &&
            $request->has('user_id') && $request->user_id != ''
        )
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

    public function export($startDate, $endDate)
    {
        $fileName = "exported-presence-" . Carbon::now()->format('d-m-Y') . ".xlsx";
        return Excel::download(new PresenceExport($startDate, $endDate), $fileName);
    }

    public function getTodayCount(): JsonResponse
    {
        $count = Presence::select('id')->where([
            ['created_at', '>=', Carbon::now()->startOfDay()],
            ['created_at', '<=', Carbon::now()->endOfDay()],
        ])->get()->count();
        return response()->json([
            'data' => ['count' => $count]
        ]);
    }

    public function getById($id): JsonResponse
    {
        $data = Presence::with('user:id,name')->find($id);
        if (!$data)
            return response()->json(null, 204);
        return (new PresenceResource($data))->response();
    }

    public function checkStatus(): JsonResponse
    {
        $currentUser = Auth::user();
        $isTodayHoliday = HolidayHelper::isTodayHoliday();
        if ($isTodayHoliday || Util::isWeekend())
            return response()->json([
                'message' => 'Tidak bisa absen pada hari libur'
            ], 500);
        $isTodayLeave = LeaveDetailHelper::isTodayLeave($currentUser->id);
        if ($isTodayLeave)
            return response()->json([
                'message' => 'Hari ini anda sedang cuti, tidak perlu absen'
            ], 500);
        $state = 0;
        $todayPresence = PresenceHelper::getTodayPresence($currentUser->id);
        if ($todayPresence) {
            if ($todayPresence->time_out == null)
                $state = 1;
            else
                $state = 2;
        }
        return response()->json([
            'message' => $state
        ]);
    }

    public function clockIn(ClockInFormRequest $request): JsonResponse
    {
        $isTodayHoliday = HolidayHelper::isTodayHoliday();
        if ($isTodayHoliday || Util::isWeekend())
            return response()->json([
                'message' => 'Tidak bisa absen pada hari libur'
            ], 500);
        if (PresenceHelper::getTodayPresence($request->user_id))
            return response()->json([
                'message' => 'Anda sudah absen hari ini'
            ], 500);
        $setting = SettingHelper::getSetting();
        $isTodayRemote = RemoteScheduleHelper::isTodayRemote($request->user_id);
        $clockIn = new Presence;
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
            $clockIn->clock_in_distance = $distance;
        }
        if ($request->hasFile('face_image_clock_in')) {
            $file = $request->file('face_image_clock_in');
            $faceImage = $request->user_id . '-clock-in' . $file->getClientOriginalName();
            $putFile = StorageHelper::putFileAs('presences', $file, $faceImage);
            if (!$putFile)
                return response()->json([
                    'message' => "Gagal mengupload foto wajah"
                ], 500);
            $clockIn->face_image_clock_in = $faceImage;
        }
        $clockIn->user_id = $request->user_id;
        $clockInSchedule = date('Y-m-d H:i', strtotime(date('Y-m-d') . " " . Constant::$CLOCK_IN_TIME));
        $actualClockIn = Carbon::now()->format('Y-m-d H:i');
        $isLate = $clockInSchedule < $actualClockIn ? 1 : 0;
        $clockIn->is_late = $isLate;
        $clockIn->schedule_time_in = Constant::$CLOCK_IN_TIME;
        $clockIn->schedule_time_out = Constant::$CLOCK_OUT_TIME;
        $clockIn->time_in = Carbon::now()->format('H:i');;
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
            $presence->clock_out_distance = $distance;
        }
        if ($request->hasFile('face_image_clock_out')) {
            $file = $request->file('face_image_clock_out');
            $faceImage = $request->user_id . '-clock-out' . $file->getClientOriginalName();
            $putFile = StorageHelper::putFileAs('presences', $file, $faceImage);
            if (!$putFile)
                return response()->json([
                    'message' => "Gagal mengupload foto wajah"
                ], 500);
            $presence->face_image_clock_out = $faceImage;
        }
        $presence->time_out = Carbon::now()->format('H:i');
        $presence->longitude_clock_out = $request->longitude_clock_out;
        $presence->latitude_clock_out = $request->latitude_clock_out;
        $presence->save();
        return (new PresenceResource($presence))->response();
    }
}
