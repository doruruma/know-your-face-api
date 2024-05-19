<?php

namespace App\Http\Requests;

class UpdatePasswordFormRequest extends CustomFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'old_password' => 'required',
            'new_password' => 'required|confirmed|different:old_password|max:255'
        ];
    }

    public function messages()
    {
        return [
            'different'  => ':attribute tidak boleh sama dengan Password lama',
            'max' => ':attribute tidak boleh melebihi 255 karakter'
        ];
    }

    public function attributes()
    {
        return [
            'old_password' => 'Password lama',
            'new_password' => 'Password baru' 
        ];
    }
}
