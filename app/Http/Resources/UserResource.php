<?php

namespace App\Http\Resources;

use App\Helpers\Util;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if ($this->gender == 'm')
            $gender = 'Laki-laki';
        else if ($this->gender == 'f')
            $gender = 'Perempuan';
        else
            $gender = 'LGBT';
        return [
            'id' => $this->id,
            'position_id' => $this->position_id,
            'position' => $this->whenLoaded('position'), 
            'nik' => $this->nik,
            'name' => $this->name,
            'phone' => $this->phone,
            'gender' => $gender,
            'email' => $this->email,
            'profile_image' => $this->profile_image,
            'face_image' => $this->face_image,
            'created_at' => Util::formatDateTime($this->created_at),
            'updated_at' => Util::formatDateTime($this->updated_at)
        ];
    }
}
