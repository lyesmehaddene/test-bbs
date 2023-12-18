<?php

declare(strict_types=1);

use App\Models\User;

function register(array $data)
{
    return postGraphql(
        [
            'query' => /** @lang GraphQL */ <<<'GRAPHQL'
                mutation register(
                    $first_name: String!,
                    $last_name: String!,
                    $email: String!,
                    $password: String!
                ) {
                    register(
                        first_name: $first_name
                        last_name: $last_name
                        email: $email
                        password: $password
                    ) {
                        id
                        token
                        first_name
                        last_name
                        email
                        created_at
                        updated_at
                    }
                }
                GRAPHQL,
            'variables' => $data,
        ],
    );
}

test('can not register with invalid inputs', function (array $data, string $attribute, string $message) {
    register($data)
        ->assertGraphQLErrorCategory('validation')
        ->assertGraphQLValidationError($attribute, $message);
})->with([
    'empty first name' => [
        [
            'first_name' => '',
            'last_name' => 'Doe',
            'email' => 'john@doe.com',
            'password' => 'SuperStrongP4ssw0rd+',
        ],
        'first_name',
        fn () => trans('validation.required', ['attribute' => trans('validation.attributes.first_name')]),
    ],
    'empty last name' => [
        [
            'first_name' => 'John',
            'last_name' => '',
            'email' => 'john@doe.com',
            'password' => 'SuperStrongP4ssw0rd+',
        ],
        'last_name',
        fn () => trans('validation.required', ['attribute' => trans('validation.attributes.last_name')]),
    ],
    'empty email' => [
        [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => '',
            'password' => 'SuperStrongP4ssw0rd+',
        ],
        'email',
        fn () => trans('validation.required', ['attribute' => trans('validation.attributes.email')]),
    ],
    'already used email' => [
        [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@doe.com',
            'password' => 'SuperStrongP4ssw0rd+',
        ],
        'email',
        fn () => trans('validation.unique', ['attribute' => trans('validation.attributes.email')]),
        function (): void {
            User::factory()->create(['email' => 'john@doe.com']);
        },
    ],
    'not a valid email' => [
        [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'not-a-valid-email',
            'password' => 'SuperStrongP4ssw0rd+',
        ],
        'email',
        fn () => trans('validation.email', ['attribute' => trans('validation.attributes.email')]),
    ],
    'password not strong enough' => [
        [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@doe.com',
            'password' => 'weak',
        ],
        'password',
        fn () => trans('validation.min.string', ['attribute' => trans('validation.attributes.password'), 'min' => 8]),
    ],
]);

test('can register', function () {
    $data = [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john@doe.com',
    ];

    $response = register(array_merge($data, [
        'password' => 'SuperStrongP4ssw0rd+',
    ]));

    $response
        ->assertOk()
        ->assertGraphQLValidationPasses()
        ->assertJson([
            'data' => [
                'register' => $data,
            ],
        ]);

    expect($response->json('data.register.token'))->toBeNull();
});
