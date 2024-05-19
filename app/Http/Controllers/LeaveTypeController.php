<?php

namespace App\Http\Controllers;

use App\Http\Resources\LeaveTypeCollection;
use App\Models\LeaveType;

class LeaveTypeController extends Controller
{
    public function getAll(): LeaveTypeCollection
    {
        $data = LeaveType::all();
        return new LeaveTypeCollection($data);
    }
}
