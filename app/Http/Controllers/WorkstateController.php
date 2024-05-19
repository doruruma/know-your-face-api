<?php

namespace App\Http\Controllers;

use App\Http\Resources\WorkstateCollection;
use App\Models\Workstate;

class WorkstateController extends Controller
{
    public function getAll(): WorkstateCollection
    {
        $data = Workstate::all();
        return new WorkstateCollection($data);
    }
}
