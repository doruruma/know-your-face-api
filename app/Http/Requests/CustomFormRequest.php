<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function messages()
    {
        return [
            'required' => ':attribute tidak boleh kosong',
            'present' => ':attribute is expected from request body',
            'confirmed' => 'Konfirmasi :attribute tidak sesuai'
        ];
    }
}
