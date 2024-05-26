<?php

namespace App\Http\Requests;

class SettingFormRequest extends CustomFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'office_address' => 'required',
            'office_longitude' => 'required',
            'office_latitude' => 'required',
            'max_distance' => 'required|integer'
        ];
    }

    public function attributes()
    {
        return [
            'office_address' => 'Alamat lengkap',
            'office_longitude' => 'Koordinat longitude',
            'office_latitude' => 'Koordinat latitude',
            'max_distance' => 'Toleransi jarak'
        ];
    }
}
