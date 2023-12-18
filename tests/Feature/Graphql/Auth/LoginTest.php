<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('can not login with invalid inputs', function (array $data, string $attribute, string $message) {
    login($data)
        ->assertGraphQLErrorCategory('validation')
        ->assertGraphQLValidationError($attribute, $message);
})->with([
    'empty email' => [
        [
            'email' => '',
            'password' => 'SuperStrongP4ssw0rd+',
        ],
        'email',
        fn () => trans('validation.required', ['attribute' => trans('validation.attributes.email')]),
    ],
    'not a valid email' => [
        [
            'email' => 'not-a-valid-email',
            'password' => 'SuperStrongP4ssw0rd+',
        ],
        'email',
        fn () => trans('validation.email', ['attribute' => trans('validation.attributes.email')]),
    ],
    'empty password' => [
        [
            'email' => 'john@doe.com',
            'password' => '',
        ],
        'password',
        fn () => trans('validation.required', ['attribute' => trans('validation.attributes.password')]),
    ],
]);

test('can not login with wrong password', function () {
    $user = User::factory()->create(['password' => Hash::make('GoodPassword1+')]);

    login([
        'email' => $user->email,
        'password' => 'WrongPassword1+',
    ])
        ->assertGraphQLErrorCategory('validation')
        ->assertGraphQLValidationError('email', trans('auth.failed'));
});

test('can not login when email is not verified', function () {
    $user = User::factory()
        ->unverified()
        ->create(['password' => Hash::make($password = 'GoodPassword1+')]);

    login([
        'email' => $user->email,
        'password' => $password,
    ])
        ->assertGraphQLErrorCategory('validation')
        ->assertGraphQLValidationError('email', trans('auth.verified'));
});

test('verified user can login', function () {
    /** @var \App\Models\User $user */
    $user = User::factory()
        ->create(['password' => Hash::make($password = 'GoodPassword1+')]);

    $response = login([
        'email' => $user->email,
        'password' => $password,
    ]);

    $response
        ->assertGraphQLValidationPasses()
        ->assertJson([
            'data' => [
                'login' => [
                    'id' => (string) $user->getKey(),
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                ],
            ],
        ]);

    $token = $response->json('data.login.token');

    expect($token)->not()->toBeNull()
        ->and(app('tymon.jwt.provider.jwt')->decode($token))
        ->sub->toEqual($user->getJWTIdentifier());
});
