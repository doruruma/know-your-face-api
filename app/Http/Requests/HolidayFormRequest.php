<?php

namespace App\Http\Requests;

class HolidayFormRequest extends CustomFormRequest
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
            'date' => 'required|date'
        ];
    }

    public function messages()
    {
        return array_merge(parent::messages(), [
            'max' => ':attribute tidak boleh melebihi 255 karakter'
        ]);
    }

    public function attributes()
    {
        return [
            'name' => 'Keterangan',
            'date' => 'Tanggal'
        ];
    }
}
