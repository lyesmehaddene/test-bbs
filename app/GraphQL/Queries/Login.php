<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\User;
use Nuwave\Lighthouse\Exceptions\ValidationException;

class Login
{
    /**
     * @param $_ mixed
     * @param  array{email: string, password: string}  $args
     *
     * @throws \Nuwave\Lighthouse\Exceptions\ValidationException
     */
    public function __invoke(mixed $_, array $args): ?User
    {
        if (!$token = auth('api')->attempt($args)) {
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        /** @var \App\Models\User $user */
        $user = auth('api')->user();

        if (!$user->hasVerifiedEmail()) {
            throw ValidationException::withMessages([
                'email' => trans('auth.verified'),
            ]);
        }

        return tap(auth('api')->user(), fn (?User $user) => $user?->withToken($token));
    }
}
