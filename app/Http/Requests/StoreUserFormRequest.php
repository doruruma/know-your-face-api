<?php

namespace App\Http\Requests;

use App\Rules\UserEmailUnique;
use App\Rules\UserNikUnique;

class StoreUserFormRequest extends UserFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = $this->rules;
        $rules['password'] = ['required', 'confirmed'];
        $rules['nik'] = ['required', 'digits:16', new UserNikUnique()];
        $rules['email'] = ['required', 'email', new UserEmailUnique()];
        return $rules;
    }
}
