<?php

namespace App\Http\Requests;

use App\Rules\UserEmailUnique;
use App\Rules\UserNikUnique;

class UpdateUserFormRequest extends UserFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'position_id' => 'required',
            'nik' => ['required', 'digits:16', new UserNikUnique($this->route('id', 0))],
            'name' => 'required|max:255',
            'phone' => 'required|max_digits:13',
            'email' => ['required', 'email', new UserEmailUnique($this->route('id', 0))],
            'gender' => 'required',
            'password' => 'required|confirmed',
            'profile_image' => 'nullable|file|max:4096|mimes:png,jpg',
            'face_image' => 'nullable|file|max:5120|mimes:png,jpg',
        ];
    }    
}
