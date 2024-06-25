<?php

namespace App\Http\Requests;

class ChangeLeaveStatusFormRequest extends CustomFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'approval_notes' => 'present',
            'details' => 'required|array'
        ];
    }

    public function messages(): array
    {
        return array_merge(parent::messages(), [
            'array' => 'Format :attribute tidak valid'
        ]);
    }

    public function attributes(): array
    {
        return [
            'details' => 'Tanggal cuti'
        ];
    }
}
