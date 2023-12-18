<?php

declare(strict_types=1);

use App\Models\Task;
use App\Models\User;

function markTaskAsUndone(array $data, ?string $token = null)
{
    return postGraphql(
        data: [
            'query' => /** @lang GraphQL */ <<<'GRAPHQL'
                mutation markTaskAsUndone(
                    $id: ID!
                ) {
                    markTaskAsUndone(
                        id: $id
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

test('unauthenticated user can not mark task as undone', function () {
    markTaskAsUndone([
        'id' => 1,
    ])
        ->assertGraphQLErrorCategory('authentication');
});

test('can not mark another user task as undone', function () {
    /** @var \App\Models\User $user */
    $user = User::factory()->create();
    $token = auth('api')->fromUser($user);

    $task = Task::factory()->for(User::factory())->create([]);

    markTaskAsUndone([
        'id' => $task->getKey(),
    ], $token)
        ->assertGraphQLValidationError('id', trans('validation.exists', [
            'attribute' => trans('validation.attributes.id'),
        ]));
});

test('can mark task as undone', function () {
    /** @var \App\Models\User $user */
    $user = User::factory()->create();
    $token = auth('api')->fromUser($user);

    $task = Task::factory()->for($user)->create([
        'done' => true,
    ]);

    markTaskAsUndone([
        'id' => $task->getKey(),
    ], $token)
        ->assertOk()
        ->assertGraphQLValidationPasses()
        ->assertJson([
            'data' => [
                'markTaskAsUndone' => [
                    'done' => false,
                ],
            ],
        ]);
});
