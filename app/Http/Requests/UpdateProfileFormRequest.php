<?php

namespace App\Http\Requests;

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
        ];
    }

    public function messages()
    {
        return [
            'name.max' => ':attribute tidak boleh melebihi 255 karakter',
            'max_digits' => ':attribute tidak boleh melebihi 13 karakter angka',
            'file' => ':attribute harus berupa file',
            'mimes' => ':attribute harus berekstensi jpg / png',
            'profile_image.max' => ':attribute tidak boleh melebihi ukuran 4 Mb',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Nama lengkap',
            'phone' => 'Nomor HP',
            'profile_image' => 'Foto profil',
        ];
    }
}
