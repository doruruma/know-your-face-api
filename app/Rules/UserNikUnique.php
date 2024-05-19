<?php

namespace App\Rules;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UserNikUnique implements ValidationRule
{
    protected $id;

    public function __construct($id = 0)
    {
        $this->id = $id;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $wheres = ['nik', $value];
        if ($this->id != 0)
            array_push($wheres, ['id', '!=', $this->id]);
        $existingUser = User::select('nik')
            ->where($wheres)
            ->first();
        if ($existingUser)
            $fail(':attribute user ini sudah terdaftar');
    }
}
