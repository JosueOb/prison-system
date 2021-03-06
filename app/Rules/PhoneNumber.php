<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PhoneNumber implements Rule
{
    private string $regular_expression = "/(^(09)[0-9]{8})+$/";

    /* Determine if the validation rule passes */
    public function passes($attribute, $value)
    {
        return (bool)preg_match($this->regular_expression, $value);
    }

    /* Get the validation error message */
    public function message()
    {
        return 'The :attribute format is invalid.';
    }
}
