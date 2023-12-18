<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Task;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class CreateTask
{
    /**
     * @param mixed $_
     * @param array $args
     * @return Task
     */
    public function __invoke(mixed $_, array $args, GraphQLContext $context): Task
    {
        return Task::query()->create([
            'title' => $args['title'],
            'description' => $args['description'],
            'user_id' => $context->user()->getAuthIdentifier(),
            'done' => false,
        ]);
    }
}
