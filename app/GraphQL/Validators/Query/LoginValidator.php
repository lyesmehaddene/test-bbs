<?php

declare(strict_types=1);

namespace App\GraphQL\Validators\Query;

use Nuwave\Lighthouse\Validation\Validator;

final class LoginValidator extends Validator
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }
}
