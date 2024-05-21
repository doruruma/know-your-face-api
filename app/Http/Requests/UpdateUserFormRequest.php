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
        $rules = $this->rules;
        $rules['nik'] = ['required', 'digits:16', new UserNikUnique($this->route('id', 0))];
        $rules['email'] = ['required', 'email', new UserEmailUnique($this->route('id', 0))];
        return $rules;
    }
}
