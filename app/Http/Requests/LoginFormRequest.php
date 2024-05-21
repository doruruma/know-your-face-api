<?php

namespace App\Http\Requests;

class LoginFormRequest extends CustomFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'username' => 'required',
            'password' => 'required'
        ];
    }

    public function attributes()
    {
        return [
            'username' => 'Email',
            'password' => 'Password'
        ];
    }
}
