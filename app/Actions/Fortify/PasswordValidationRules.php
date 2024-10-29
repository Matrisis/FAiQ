<?php

namespace App\Actions\Fortify;

use Illuminate\Validation\Rules\Password;

trait PasswordValidationRules
{
    /**
     * Get the validation rules used to validate passwords.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function passwordRules(): array
    {
        if(ENV('APP_ENV') === 'local')
            return ['required', 'string', Password::default(), 'confirmed'];
        return ['required', 'string',
            Password::default()
                ->symbols()
                ->mixedCase()
                ->uncompromised()
                ->numbers(),
            'confirmed'];
    }
}
