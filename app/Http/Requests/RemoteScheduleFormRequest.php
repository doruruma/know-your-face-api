<?php

namespace App\Http\Requests;

class RemoteScheduleFormRequest extends CustomFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required',
            'date' => 'required|date'
        ];
    }

    public function attributes()
    {
        return [
            'user_id' => 'Pegawai',
            'date' => 'Tanggal'
        ];
    }
}
