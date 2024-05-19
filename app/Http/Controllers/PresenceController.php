<?php

namespace App\Http\Controllers;

use app\Helpers\Constant;
use App\Http\Resources\PresenceCollection;
use App\Models\Presence;
use Illuminate\Http\Request;

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

    public function store()
    {
        // TODO        
    }
}
