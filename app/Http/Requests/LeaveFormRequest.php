<?php

namespace App\Http\Requests;

use app\Helpers\Constant;
use Illuminate\Validation\Rule;

class LeaveFormRequest extends CustomFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'leave_type_id' => 'required',
            'user_id' => 'required',            
            'attachment' => [
                Rule::requiredIf($this->leave_type_id == Constant::$SICK_LEAVE_ID),
                'file',
                'mimes:png,jpg',
                'max:4096'
            ],
            'notes' => 'required',
            'dates' => 'required|array'
        ];
    }

    public function messages()
    {
        return [
            'array' => ':attribute tidak sesuai format yang diterima sistem',
            'file' => ':attribute harus berupa file',
            'mimes' => ':attribute harus berekstensi jpg / png',
            'attachment.max' => ':attribute tidak boleh melebihi ukuran 4 Mb',
        ];
    }

    public function attributes()
    {
        return [
            'leave_type_id' => 'Jenis cuti',
            'user_id' => 'Pemohon',
            'attachment' => 'Lampiran',
            'notes' => 'Keterangan',
            'dates' => 'Tanggal-tanggal cuti'
        ];
    }
}
