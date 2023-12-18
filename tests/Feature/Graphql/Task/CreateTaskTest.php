<?php

declare(strict_types=1);

use App\Models\User;

function createTask(array $data, ?string $token = null)
{
    return postGraphql(
        data: [
            'query' => /** @lang GraphQL */ <<<'GRAPHQL'
                mutation createTask(
                    $title: String!,
                    $description: String!,
                ) {
                    createTask(
                        title: $title
                        description: $description
                    ) {
                        id
                        title
                        description
                        done
                        created_at
                        updated_at
                    }
                }
                GRAPHQL,
            'variables' => $data,
        ],
        headers: $token ? ['Authorization' => "Bearer {$token}"] : []
    );
}

test('unauthenticated user can not create task', function () {
    createTask([
        'title' => 'Technical test',
        'description' => 'Pass BBS technical test',
    ])
        ->assertGraphQLErrorCategory('authentication');
});

test('can not create task with invalid inputs', function (array $data, string $attribute, string $message) {
    /** @var \App\Models\User $user */
    $user = User::factory()->create();
    $token = auth('api')->fromUser($user);

    createTask($data, $token)
        ->assertGraphQLErrorCategory('validation')
        ->assertGraphQLValidationError($attribute, $message);
})->with([
    'empty title' => [
        [
            'title' => '',
            'description' => 'Pass BBS technical test',
        ],
        'title',
        fn () => trans('validation.required', ['attribute' => trans('validation.attributes.title')]),
    ],
    'empty description' => [
        [
            'title' => 'Technical test',
            'description' => '',
        ],
        'description',
        fn () => trans('validation.required', ['attribute' => trans('validation.attributes.description')]),
    ],
]);

test('can create task', function () {
    /** @var \App\Models\User $user */
    $user = User::factory()->create();
    $token = auth('api')->fromUser($user);

    $data = [
        'title' => 'Technical test',
        'description' => 'Pass BBS technical test',
    ];

    createTask($data, $token)
        ->assertOk()
        ->assertGraphQLValidationPasses()
        ->assertJson([
            'data' => [
                'createTask' => array_merge($data, [
                    'done' => false,
                ]),
            ],
        ]);
});
