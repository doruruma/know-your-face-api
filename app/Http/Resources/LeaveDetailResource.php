<?php

namespace App\Http\Resources;

use App\Helpers\Util;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeaveDetailResource extends JsonResource
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
            'workstate_id' => $this->workstate_id,
            'workstate' => new WorkstateResource($this->whenLoaded('workstate')),
            'leave_id' => $this->leave_id,
            'leave_date' => Util::formatDateTime($this->leave_date, 'd-m-Y'),
            'created_at' => Util::formatDateTime($this->created_at),
            'updated_at' => Util::formatDateTime($this->updated_at)
        ];
    }
}
