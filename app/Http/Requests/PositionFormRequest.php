<?php

namespace App\Http\Requests;

class PositionFormRequest extends CustomFormRequest
{    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max:50',
            'description' => 'present'            
        ];
    }

    public function messages()
    {
        return [            
            'max' => ':attribute tidak boleh melebihi 50 karakter'
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Nama posisi',
            'description' => 'Deskripsi'
        ];
    }
}
