<?php

namespace App\Http\Controllers;

use App\Helpers\Constant;
use App\Http\Requests\HolidayFormRequest;
use App\Http\Resources\HolidayCollection;
use App\Http\Resources\HolidayResource;
use App\Models\Holiday;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    public function getAll(Request $request): HolidayCollection
    {
        $data = new Holiday;
        if ($request->has('search') && $request->search != '')
            $data = $data->where('name', 'like', "%$request->search%");
        $data = $data->paginate(Constant::$PAGE_SIZE);
        return new HolidayCollection($data);
    }

    public function getPerYear($year): HolidayCollection
    {
        $carbon = Carbon::create($year);
        $firstOfMonth = $carbon->firstOfYear()->format('Y-m-d');
        $lastOfMonth = $carbon->lastOfYear()->format('Y-m-d');
        $data = Holiday::where([
            ['date', '>=', $firstOfMonth],
            ['date', '<=', $lastOfMonth]
        ])->get();
        return new HolidayCollection($data);
    }

    public function getById($id): JsonResponse
    {
        $data = Holiday::find($id);
        if (!$data)
            return response()->json(null, 204);
        return (new HolidayResource($data))->response();
    }

    public function store(HolidayFormRequest $request): JsonResponse
    {
        $existing = Holiday::select('id')
            ->where('date', $request->date)
            ->first();
        if ($existing)
            return response()->json([
                'message' => 'Tanggal ini sudah terdaftar'
            ], 500);
        $data = new Holiday;
        $data->name = $request->name;
        $data->date = $request->date;
        $data->save();
        return (new HolidayResource($data))->response();
    }

    public function update(HolidayFormRequest $request, $id): JsonResponse
    {
        $existing = Holiday::select('id')
            ->where([
                ['id', '!=', $id],
                ['date', $request->date]
            ])->first();
        if ($existing)
            return response()->json([
                'message' => 'Tanggal ini sudah terdaftar'
            ], 500);
        $data = Holiday::find($id);
        if (!$data)
            return response()->json([
                'message' => 'Data tidak ditemukan'
            ], 500);
        $data->name = $request->name;
        $data->date = $request->date;
        $data->save();
        return (new HolidayResource($data))->response();
    }

    public function delete($id): JsonResponse
    {
        $data = Holiday::find($id);
        if (!$data)
            return response()->json([
                'message' => 'Data tidak ditemukan'
            ], 500);
        $data->delete();
        return (new HolidayResource($data))->response();
    }
}
