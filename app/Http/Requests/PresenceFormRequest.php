<?php

namespace App\Http\Requests;

class PresenceFormRequest extends CustomFormRequest
{
    protected $rules = [
        'user_id' => 'required'
    ];

    public function messages()
    {
        return [
            'file' => ':attribute harus berupa file',
            'mimes' => ':attribute harus berekstensi jpg / png',
            'max' => ':attribute tidak boleh melebihi ukuran 4 Mb',
        ];
    }

    public function attributes()
    {
        return [
            'user_id' => 'Pegawai',
            'longitude_clock_in' => 'Koordinat longitude',
            'latitude_clock_in' => 'Koordinat latitude',
            'longitude_clock_out' => 'Koordinat longitude',
            'latitude_clock_out' => 'Koordinat latitude',
            'face_image_clock_in' => 'Foto wajah',
            'face_image_clock_out' => 'Foto wajah',
        ];
    }
}
