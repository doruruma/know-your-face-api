<?php

namespace App\Http\Requests;

class UserFormRequest extends CustomFormRequest
{
    public function messages()
    {
        return [
            'digits' => ':attribute harus berupa angka sepanjang 16 karakter',
            'name.max' => ':attribute tidak boleh melebihi 255 karakter',
            'max_digits' => ':attribute tidak boleh melebihi 13 karakter angka',
            'email' => ':attribute tidak valid',
            'file' => ':attribute harus berupa file',
            'mimes' => ':attribute harus berekstensi jpg / png',
            'profile_image.max' => ':attribute tidak boleh melebihi ukuran 4 Mb',
            'face_image.max' => ':attribute tidak boleh melebihi ukuran 5 Mb'
        ];
    }

    public function attributes()
    {
        return [
            'position_id' => 'Posisi',
            'nik' => 'NIK',
            'name' => 'Nama lengkap',
            'phone' => 'Nomor HP',
            'email' => 'Email',
            'gender' => 'Jenis kelamin',
            'password' => 'Password',
            'profile_image' => 'Foto profil',
            'face_image' => 'Foto pengenalan wajah'
        ];
    }
}
