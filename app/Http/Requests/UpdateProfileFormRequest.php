<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateProfileFormRequest extends CustomFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max:255',
            'phone' => 'required|max_digits:13',
            'profile_image' => 'nullable|file|max:4096|mimes:png,jpg',
            'old_password' => 'nullable',
            'new_password' => [Rule::requiredIf($this->old_password != ''), 'confirmed', 'different:old_password', 'max:255']
        ];
    }

    public function messages()
    {
        return array_merge(parent::messages(), [
            'name.max' => ':attribute tidak boleh melebihi 255 karakter',
            'max_digits' => ':attribute tidak boleh melebihi 13 karakter angka',
            'file' => ':attribute harus berupa file',
            'mimes' => ':attribute harus berekstensi jpg / png',
            'profile_image.max' => ':attribute tidak boleh melebihi ukuran 4 Mb',
            'different'  => ':attribute tidak boleh sama dengan Password lama',
            'max' => ':attribute tidak boleh melebihi 255 karakter'
        ]);
    }

    public function attributes()
    {
        return [
            'name' => 'Nama lengkap',
            'phone' => 'Nomor HP',
            'profile_image' => 'Foto profil',
            'old_password' => 'Password lama',
            'new_password' => 'Password baru'
        ];
    }
}
