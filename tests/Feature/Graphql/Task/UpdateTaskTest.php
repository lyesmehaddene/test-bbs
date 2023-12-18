<?php

declare(strict_types=1);

use App\Models\Task;
use App\Models\User;

function updateTask(array $data, ?string $token = null)
{
    return postGraphql(
        data: [
            'query' => /** @lang GraphQL */ <<<'GRAPHQL'
                mutation updateTask(
                    $id: ID!
                    $title: String!,
                    $description: String!,
                ) {
                    updateTask(
                        id: $id
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

test('unauthenticated user can not update task', function () {
    updateTask([
        'id' => 1,
        'title' => 'Technical test',
        'description' => 'Pass BBS technical test',
    ])
        ->assertGraphQLErrorCategory('authentication');
});

test('can not update task with invalid inputs', function (array $data, string $attribute, string $message) {
    /** @var \App\Models\User $user */
    $user = User::factory()->create();
    $token = auth('api')->fromUser($user);

    $task = Task::factory()->for($user)->create();

    updateTask(array_merge($data, [
        'id' => $task->getKey(),
    ]), $token)
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

test('can not update another user task', function () {
    /** @var \App\Models\User $user */
    $user = User::factory()->create();
    $token = auth('api')->fromUser($user);

    $task = Task::factory()->for(User::factory())->create();

    $data = [
        'id' => $task->getKey(),
        'title' => 'Technical test',
        'description' => 'Pass BBS technical test',
    ];

    updateTask($data, $token)
        ->assertGraphQLValidationError('id', trans('validation.exists', [
            'attribute' => trans('validation.attributes.id'),
        ]));
});

test('can update task', function () {
    /** @var \App\Models\User $user */
    $user = User::factory()->create();
    $token = auth('api')->fromUser($user);

    $task = Task::factory()->for($user)->create();

    $data = [
        'id' => $task->getKey(),
        'title' => 'Technical test',
        'description' => 'Pass BBS technical test',
    ];

    updateTask($data, $token)
        ->assertOk()
        ->assertGraphQLValidationPasses()
        ->assertJson([
            'data' => [
                'updateTask' => array_merge($data, [
                    'done' => false,
                ]),
            ],
        ]);
});
