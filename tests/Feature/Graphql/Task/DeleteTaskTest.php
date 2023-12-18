<?php

declare(strict_types=1);

use App\Models\Task;
use App\Models\User;
use function Pest\Laravel\assertDatabaseMissing;

function deleteTask(array $data, ?string $token = null)
{
    return postGraphql(
        data: [
            'query' => /** @lang GraphQL */ <<<'GRAPHQL'
                mutation deleteTask(
                    $id: ID!
                ) {
                    deleteTask(
                        id: $id
                    )
                }
                GRAPHQL,
            'variables' => $data,
        ],
        headers: $token ? ['Authorization' => "Bearer {$token}"] : []
    );
}

test('unauthenticated user can not delete task', function () {
    deleteTask([
        'id' => 1,
    ])
        ->assertGraphQLErrorCategory('authentication');
});

test('can not delete unknown task', function () {
    /** @var \App\Models\User $user */
    $user = User::factory()->create();
    $token = auth('api')->fromUser($user);

    deleteTask(array_merge([
        'id' => 1000,
    ]), $token)
        ->assertGraphQLErrorCategory('validation')
        ->assertGraphQLValidationError('id', trans('validation.exists', [
            'attribute' => trans('validation.attributes.id'),
        ]));
});

test('can not delete another user task', function () {
    /** @var \App\Models\User $user */
    $user = User::factory()->create();
    $token = auth('api')->fromUser($user);

    $task = Task::factory()->for(User::factory())->create();

    $data = [
        'id' => $task->getKey(),
    ];

    deleteTask($data, $token)
        ->assertGraphQLValidationError('id', trans('validation.exists', [
            'attribute' => trans('validation.attributes.id'),
        ]));
});

test('can delete task', function () {
    /** @var \App\Models\User $user */
    $user = User::factory()->create();
    $token = auth('api')->fromUser($user);

    $task = Task::factory()->for($user)->create();

    $data = [
        'id' => $task->getKey(),
    ];

    deleteTask($data, $token)
        ->assertOk()
        ->assertGraphQLValidationPasses()
        ->assertJson([
            'data' => [
                'deleteTask' => true,
            ],
        ]);

    assertDatabaseMissing('tasks', [
        'id' => $task->getKey(),
    ]);
});
