<?php

declare(strict_types=1);

namespace App\GraphQL\Validators\Mutation;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Nuwave\Lighthouse\Validation\Validator;

final class RegisterValidator extends Validator
{
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'min:1', 'max:255'],
            'last_name' => ['required', 'string', 'min:1', 'max:255'],
            'email' => ['required', 'string', 'email', 'min:1', 'max:254', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'max:254', Password::defaults()],
        ];
    }
}
