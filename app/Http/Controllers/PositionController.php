<?php

namespace App\Http\Controllers;

use App\Helpers\Constant;
use App\Http\Resources\PositionCollection;
use App\Models\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function getStaff(Request $request): PositionCollection
    {
        $data = Position::whereNotIn('id', [1]);
        if ($request->has('search') && $request->search != '')
            $data = $data->where('name', 'like', "%$request->search%");
        $data = $data->orderBy('name')->paginate(Constant::$PAGE_SIZE);
        return new PositionCollection($data);
    }

    public function getStaffNoPaging(Request $request): PositionCollection
    {
        $data = Position::whereNotIn('id', [1]);
        if ($request->has('search') && $request->search != '')
            $data = $data->where('name', 'like', "%$request->search%");
        $data = $data->orderBy('name')->get();
        return new PositionCollection($data);
    }
}
