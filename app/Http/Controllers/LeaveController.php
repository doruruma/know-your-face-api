<?php

namespace App\Http\Controllers;

use App\Helpers\Constant;
use App\Helpers\LeaveDetailHelper;
use App\Http\Requests\ChangeLeaveStatusFormRequest;
use App\Http\Requests\LeaveFormRequest;
use App\Http\Resources\LeaveCollection;
use App\Http\Resources\LeaveResource;
use App\Models\Leave;
use App\Models\LeaveDetail;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class LeaveController extends Controller
{
    public function getAll(Request $request): LeaveCollection
    {
        $data = Leave::with(
            'leaveType',
            'workstate',
            'user',
            'approver'
        );
        if ($request->has('search') && $request->search != '')
            $data = $data->where('notes', 'like', "$request->search");
        if ($request->has('leave_type_id') && $request->leave_type_id != '')
            $data = $data->where('leave_type_id', $request->leave_type_id);
        if ($request->has('workstate_id') && $request->workstate_id != '')
            $data = $data->where('workstate_id', $request->workstate_id);
        if ($request->has('user_id') && $request->user_id != '')
            $data = $data->where('user_id', $request->user_id);
        $data = $data->orderBy('created_at', 'DESC')
            ->paginate(Constant::$PAGE_SIZE);
        return new LeaveCollection($data);
    }

    public function getById(Request $request, $id): JsonResponse
    {
        $data = Leave::with(
            'leaveType',
            'workstate',
            'user',
            'approver'
        )->find($id);
        if (!$data)
            return response()->json(null, 204);
        return (new LeaveResource($data))->response();
    }

    public function store(LeaveFormRequest $request)
    {
        $data = DB::transaction(function () use ($request) {
            $leave = new Leave;
            $leave->workstate_id = Constant::$STATE_REQUESTED_ID;
            $leave->leave_type_id = $request->leave_type_id;
            $leave->user_id = $request->user_id;
            $leave->notes = $request->notes;
            try {
                if ($request->hasFile('attachment')) {
                    $file = $request->file('attachment');
                    $attachment = date('d-m-y H:i') . "-user-$request->user_id" . $file->getClientOriginalName();
                    $file->storeAs('leave-attachments', $attachment);
                    $leave->attachment = $attachment;
                }
            } catch (\Throwable $th) {
                Log::error($th->getMessage());
            }
            $leave->save();
            // insert leave_details
            LeaveDetailHelper::bulkStore($leave->id, $request->dates);
            return $leave;
        });
        return (new LeaveResource($data))->response();
    }

    public function update(LeaveFormRequest $request, $id): JsonResponse
    {
        $leave = Leave::find($id);
        if (!$leave)
            return response()->json([
                'message' => 'Data tidak ditemukan'
            ], 500);
        $currentUserId = Auth::user()->id;
        if ($currentUserId != $leave->user_id)
            return response()->json([
                'message' => 'Data tidak ditemukan'
            ], 500);
        if ($leave->workstate_id != Constant::$STATE_REQUESTED_ID)
            return response()->json([
                'message' => 'Data tidak boleh diedit'
            ], 500);
        $data = DB::transaction(function () use ($request, $leave) {
            $leave->notes = $request->notes;
            try {
                if ($request->hasFile('attachment')) {
                    if ($leave->attachment)
                        Storage::delete("leave-attachments/$leave->attachment");
                    $file = $request->file('attachment');
                    $attachment = date('d-m-y H:i') . "-user-$request->user_id" . $file->getClientOriginalName();
                    $file->storeAs('leave-attachments', $attachment);
                    $leave->attachment = $attachment;
                }
            } catch (\Throwable $th) {
                Log::error($th->getMessage());
            }
            $leave->save();
            // insert leave_details
            LeaveDetail::where('leave_id', $leave->id)->forceDelete();
            LeaveDetailHelper::bulkStore($leave->id, $request->dates);
            return $leave;
        });
        return (new LeaveResource($data))->response();
    }

    public function approve(ChangeLeaveStatusFormRequest $request, $id): JsonResponse
    {
        $leave = Leave::find($id);
        if (!$leave)
            return response()->json([
                'message' => 'Data tidak ditemukan'
            ], 500);
        $leave->workstate_id = Constant::$STATE_APPROVED_ID;
        $leave->approver_user_id = Auth::user()->id;
        $leave->approved_date = Carbon::now();
        $leave->approval_notes = $request->approval_notes;
        $leave->save();
        return (new LeaveResource($leave))->response();
    }

    public function reject(ChangeLeaveStatusFormRequest $request, $id): JsonResponse
    {
        $leave = Leave::find($id);
        if (!$leave)
            return response()->json([
                'message' => 'Data tidak ditemukan'
            ], 500);
        $leave->workstate_id = Constant::$STATE_REJECTED_ID;
        $leave->approver_user_id = Auth::user()->id;
        $leave->approved_date = Carbon::now();
        $leave->approval_notes = $request->approval_notes;
        $leave->save();
        return (new LeaveResource($leave))->response();
    }

    public function cancel($id): JsonResponse
    {
        $leave = Leave::find($id);
        if (!$leave)
            return response()->json([
                'message' => 'Data tidak ditemukan'
            ], 500);
        $currentUserId = Auth::user()->id;
        if ($currentUserId != $leave->user_id)
            return response()->json([
                'message' => 'Data tidak ditemukan'
            ], 500);
        $leave->workstate_id = Constant::$STATE_CANCELLED_ID;
        $leave->save();
        return (new LeaveResource($leave))->response();
    }
}
