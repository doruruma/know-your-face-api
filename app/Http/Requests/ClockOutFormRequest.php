<?php

namespace App\Http\Requests;

class ClockOutFormRequest extends PresenceFormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = $this->rules;
        $rules['longitude_clock_out'] = 'required';
        $rules['latitude_clock_out'] = 'required';
        $rules['face_image_clock_out'] = 'required|file|mimes:png,jpg|max:4096';
        return $rules;
    }
}
