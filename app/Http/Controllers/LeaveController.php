<?php

namespace App\Http\Controllers;

use App\Helpers\Constant;
use App\Helpers\LeaveDetailHelper;
use App\Helpers\StorageHelper;
use App\Http\Requests\ChangeLeaveStatusFormRequest;
use App\Http\Requests\LeaveFormRequest;
use App\Http\Resources\LeaveCollection;
use App\Http\Resources\LeaveResource;
use App\Models\Leave;
use App\Models\LeaveDetail;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LeaveController extends Controller
{
    public function getAll(Request $request): LeaveCollection
    {
        $currentUser = Auth::user();
        $data = Leave::with(
            'leaveDetails',
            'leaveType:id,name',
            'workstate:id,name',
            'user',
            'approver'
        );
        if ($currentUser->position_id != Constant::$MANAGEMENT_POSITION_ID)
            $data = $data->where('user_id', $currentUser->id);
        if ($request->has('search') && $request->search != '')
            $data = $data->where('notes', 'like', "$request->search");
        if ($request->has('leave_type_id') && $request->leave_type_id != '')
            $data = $data->where('leave_type_id', $request->leave_type_id);
        if ($request->has('workstate_id') && $request->workstate_id != '')
            $data = $data->where('workstate_id', $request->workstate_id);
        if (
            $currentUser->position_id == Constant::$MANAGEMENT_POSITION_ID &&
            $request->has('user_id') && $request->user_id != ''
        )
            $data = $data->where('user_id', $request->user_id);
        $data = $data->orderBy('created_at', 'DESC')
            ->paginate(Constant::$PAGE_SIZE);
        return new LeaveCollection($data);
    }

    public function getPerYear($year): LeaveCollection
    {
        $carbon = Carbon::create($year);
        $firstOfMonth = $carbon->firstOfYear()->format('Y-m-d');
        $lastOfMonth = $carbon->lastOfYear()->format('Y-m-d');
        $data = Leave::with(
            'leaveDetails',
            'user',
        )->whereHas('leaveDetails', function (Builder $query) use ($firstOfMonth, $lastOfMonth) {
            $query->where([
                ['workstate_id', Constant::$STATE_APPROVED_ID],
                ['leave_date', '>=', $firstOfMonth],
                ['leave_date', '<=', $lastOfMonth]
            ]);
        })->where([
            ['workstate_id', Constant::$STATE_APPROVED_ID]
        ])->get();
        return new LeaveCollection($data);
    }

    public function getById(Request $request, $id): JsonResponse
    {
        $withs = [
            'leaveType',
            'workstate',
            'user',
            'approver'
        ];
        if ($request->has('detailed') && $request->detailed == true)
            array_push($withs, 'leaveDetails');
        $data = Leave::with($withs)->find($id);
        if (!$data)
            return response()->json(null, 204);
        return (new LeaveResource($data))->response();
    }

    public function getTodayRequestedCount(): JsonResponse
    {
        $count = Leave::select('id')
            ->where([
                ['workstate_id', Constant::$STATE_REQUESTED_ID],
                ['created_at', '>=', Carbon::now()->startOfDay()],
                ['created_at', '<=', Carbon::now()->endOfDay()],
            ])
            ->get()
            ->count();
        return response()->json([
            'data' => ['count' => $count]
        ]);
    }

    public function getTodayApprovedSickCount(): JsonResponse
    {
        $count = LeaveDetail::select('id')
            ->whereHas('leave', function (Builder $query) {
                $query->where('leave_type_id', Constant::$SICK_LEAVE_ID);
            })
            ->where([
                ['workstate_id', Constant::$STATE_APPROVED_ID],
                ['leave_date', Carbon::now()->format('Y-m-d')],
            ])
            ->get()
            ->count();
        return response()->json([
            'data' => ['count' => $count]
        ]);
    }

    public function getTodayApprovedLeaveCount(): JsonResponse
    {
        $count = LeaveDetail::select('id')
            ->whereHas('leave', function (Builder $query) {
                $query->where('leave_type_id', Constant::$LEAVE_ID);
            })
            ->where([
                ['workstate_id', Constant::$STATE_APPROVED_ID],
                ['leave_date', Carbon::now()->format('Y-m-d')],
            ])
            ->get()
            ->count();
        return response()->json([
            'data' => ['count' => $count]
        ]);
    }

    public function getRequestedById(Request $request, $id): JsonResponse
    {
        $withs = [
            'leaveType',
            'workstate',
            'user',
            'approver'
        ];
        if ($request->has('detailed') && $request->detailed == true)
            array_push($withs, 'leaveDetails');
        $data = Leave::with($withs)->where([
            ['id', $id],
            ['workstate_id', Constant::$STATE_REQUESTED_ID]
        ])->first();
        if (!$data)
            return response()->json(null, 204);
        return (new LeaveResource($data))->response();
    }

    public function store(LeaveFormRequest $request)
    {
        $storedLeaves = Leave::select('id', 'workstate_id')
            ->where('user_id', $request->user_id)
            ->orWhere(function (Builder $query) {
                $query->where('workstate_id', Constant::$STATE_REQUESTED_ID)
                    ->orWhere('workstate_id', Constant::$STATE_APPROVED_ID);
            })
            ->get();
        $whereDates = [];
        foreach ($request->dates as $date) {
            array_push($whereDates, ['leave_date', $date]);
        }
        $storedLeaveDetail = LeaveDetail::select('leave_id', 'leave_date')
            ->whereIn('leave_id', $storedLeaves->pluck('id'))
            ->where($whereDates)
            ->first();
        if ($storedLeaveDetail)
            return response()->json([
                'message' => 'Anda sudah mengajukan cuti di tanggal yang sama'
            ], 500);
        $data = DB::transaction(function () use ($request) {
            $leave = new Leave;
            $leave->workstate_id = Constant::$STATE_REQUESTED_ID;
            $leave->leave_type_id = $request->leave_type_id;
            $leave->user_id = $request->user_id;
            $leave->notes = $request->notes;
            $leave->attachment = Constant::$LEAVE_ATTACHMENT_IMAGE;
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $attachment = Str::uuid() . "-user-$request->user_id" . $file->getClientOriginalName();
                $putFile = StorageHelper::putFileAs('leave-attachments', $file, $attachment);
                if ($putFile)
                    $leave->attachment = $attachment;
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
            if ($request->hasFile('attachment')) {
                if ($leave->attachment != Constant::$LEAVE_ATTACHMENT_IMAGE)
                    Storage::delete("leave-attachments/$leave->attachment");
                $file = $request->file('attachment');
                $attachment = Str::uuid() . "-user-$request->user_id" . $file->getClientOriginalName();
                $putFile = StorageHelper::putFileAs('leave-attachments', $file, $attachment);
                if ($putFile)
                    $leave->attachment = $attachment;
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
        $data = DB::transaction(function () use ($request, $leave) {
            $ids = collect($request->details)->pluck('id');
            $strCases = '';
            $details = json_decode(json_encode($request->details));
            $isApprovedExist = current(array_filter($details, function ($detail) {
                return $detail->workstate_id === Constant::$STATE_APPROVED_ID;
            }));
            $workstateId = $isApprovedExist ? Constant::$STATE_APPROVED_ID : Constant::$STATE_REJECTED_ID;
            foreach ($details as $detail) {
                $strCases .= 'WHEN id = ' . $detail->id . ' THEN ' . $detail->workstate_id . "\n";
            }
            $leave->workstate_id = $workstateId;
            $leave->approver_user_id = Auth::user()->id;
            $leave->approved_date = Carbon::now();
            $leave->approval_notes = $request->approval_notes;
            $leave->save();
            // update leave_details
            LeaveDetail::whereIn('id', $ids)->update([
                'workstate_id' => DB::raw('CASE
                    ' . $strCases . '
                    ELSE ' . Constant::$STATE_REQUESTED_ID . '
                    END
                '),
                'updated_at' => Carbon::now()
            ]);
            return $leave;
        });
        return (new LeaveResource($data))->response();
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
        $result = DB::transaction(function () use ($id, $leave) {
            $leave->workstate_id = Constant::$STATE_CANCELLED_ID;
            $leave->save();
            // update leave_details
            $leaveDetailIds = LeaveDetail::select('id')
                ->where('leave_id', $id)
                ->get()
                ->pluck('id');
            LeaveDetail::whereIn('id', $leaveDetailIds)->update([
                'workstate_id' => Constant::$STATE_CANCELLED_ID
            ]);
            return $leave;
        });
        return (new LeaveResource($result))->response();
    }
}
