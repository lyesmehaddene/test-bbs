<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;

class Register
{
    /**
     * @param  array{first_name: string, last_name: string, email: string, password: string}  $args
     */
    public function __invoke(mixed $_, array $args): User
    {
        $user = User::query()->create([
            'first_name' => $args['first_name'],
            'last_name' => $args['last_name'],
            'email' => $args['email'],
            'password' => Hash::make($args['password']),
        ]);

        event(new Registered($user));

        return $user;
    }
}
