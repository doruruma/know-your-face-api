<?php

namespace App\Http\Controllers;

use App\Http\Requests\WorkHourFormRequest;
use App\Http\Resources\WorkHourCollection;
use App\Http\Resources\WorkHourResource;
use App\Models\WorkHour;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WorkHourController extends Controller
{
    public function getAll(Request $request): WorkHourCollection
    {
        $data = new WorkHour;
        if ($request->has('search') && $request->search != '')
            $data = $data->where('name', 'like', "$request->search");
        return new WorkHourCollection($data);
    }

    public function store(WorkHourFormRequest $request): JsonResponse
    {
        $workHour = new WorkHour;
        $workHour->name = $request->name;
        $workHour->time_start = $request->time_start;
        $workHour->time_end = $request->time_end;
        $workHour->save();
        return (new WorkHourResource($workHour))->response();
    }

    public function update(WorkHourFormRequest $request, $id): JsonResponse
    {
        $workHour = WorkHour::find($id);
        if (!$workHour)
            return response()->json([
                'message' => 'Jam kerja tidak ditemukan'
            ], 500);
        $workHour->name = $request->name;
        $workHour->time_start = $request->time_start;
        $workHour->time_end = $request->time_end;
        $workHour->save();
        return (new WorkHourResource($workHour))->response();
    }

    public function delete($id): JsonResponse
    {
        $workHour = WorkHour::find($id);
        if (!$workHour)
            return response()->json([
                'message' => 'Jam kerja tidak ditemukan'
            ], 500);
        $workHour->delete();
        return (new WorkHourResource($workHour))->response();
    }
}
