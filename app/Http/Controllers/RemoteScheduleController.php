<?php

namespace App\Http\Controllers;

use App\Helpers\Constant;
use App\Http\Requests\RemoteScheduleFormRequest;
use App\Http\Resources\RemoteScheduleCollection;
use App\Http\Resources\RemoteScheduleResource;
use App\Models\RemoteSchedule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RemoteScheduleController extends Controller
{
    public function getAll(Request $request): RemoteScheduleCollection
    {
        $data = new RemoteSchedule;
        if ($request->has('user_id') && $request->user_id != '')
            $data = $data->where('user_id', $request->user_id);
        $data = $data->paginate(Constant::$PAGE_SIZE);
        return new RemoteScheduleCollection($data);
    }

    public function getById($id): JsonResponse
    {
        $data = RemoteSchedule::find($id);
        if (!$data)
            return response()->json(null, 204);
        return new ($data);
    }

    public function store(RemoteScheduleFormRequest $request)
    {
        $existing = RemoteSchedule::select('id')
            ->where([
                ['user_id', $request->user_id],
                ['date', $request->date]
            ])
            ->first();
        if ($existing)
            return response()->json([
                'message' => 'Tanggal ini sudah terdaftar'
            ], 500);
        $data = new RemoteSchedule;
        $data->user_id = $request->user_id;
        $data->date = $request->date;
        $data->save();
        return (new RemoteScheduleResource($data))->response();
    }

    public function update(RemoteScheduleFormRequest $request, $id): JsonResponse
    {
        $existing = RemoteSchedule::select('id')
            ->where([
                ['id', '!=', $id],
                ['date', $request->date]
            ])
            ->first();
        if ($existing)
            return response()->json([
                'message' => 'Tanggal ini sudah terdaftar'
            ], 500);
        $data = RemoteSchedule::find($id);
        if (!$data)
            return response()->json([
                'message' => 'Data tidak ditemukan'
            ], 500);
        $data->user_id = $request->user_id;
        $data->date = $request->date;
        $data->save();
        return (new RemoteScheduleResource($data))->response();
    }

    public function delete($id): JsonResponse
    {
        $data = RemoteSchedule::find($id);
        if (!$data)
            return response()->json(null, 204);
        $data->delete();
        return (new RemoteScheduleResource($data))->response();
    }
}
