<?php

namespace App\Http\Requests;

class WorkHourFormRequest extends CustomFormRequest
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
            'time_start' => 'required',
            'time_end' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'max' => ':attribute tidak boleh melebihi 255 karakter'
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Nama jam kerja',
            'time_start' => 'Jam masuk',
            'time_end' => 'Jam pulang'
        ];
    }
}
