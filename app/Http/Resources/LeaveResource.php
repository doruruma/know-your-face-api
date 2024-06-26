<?php

namespace App\Http\Resources;

use App\Helpers\Util;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeaveResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'leave_details' => new LeaveDetailCollection($this->whenLoaded('leaveDetails')),
            'leave_type_id' => $this->leave_type_id,
            'leave_type' => new LeaveTypeResource($this->whenLoaded('leaveType')),
            'workstate_id' => $this->workstate_id,
            'workstate' => new WorkstateResource($this->whenLoaded('workstate')),
            'user_id' => $this->user_id,
            'user' => new UserResource($this->whenLoaded('user')),
            'approver_user_id' => $this->approver_user_id,
            'approver' => new UserResource($this->whenLoaded('approver')),
            'approved_date' => Util::formatDateTime($this->approved_date),
            'attachment' => $this->attachment ? "storage/leave-attachments/$this->attachment" : null,
            'notes' => $this->notes,
            'created_at' => Util::formatDateTime($this->created_at),
            'updated_at' => Util::formatDateTime($this->updated_at)
        ];
    }
}
