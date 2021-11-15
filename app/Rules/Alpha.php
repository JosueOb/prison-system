<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Alpha implements Rule
{
    private string $regular_expression = "/^[[:alpha:][:space:](áéíóúÁÉÍÓÚñÑ)]+$/";

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        return (bool)preg_match($this->regular_expression, $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'The :attribute may only contain letters and spaces.';
    }
}