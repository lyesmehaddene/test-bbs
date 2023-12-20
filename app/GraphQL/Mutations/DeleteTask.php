<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Task;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Nuwave\Lighthouse\Exceptions\ValidationException;
class DeleteTask
{
    /**
     * @param mixed $_
     * @param array $args
     * @return Task
     * @throws ValidationException
     */
    public function __invoke(mixed $_, array $args): Task
    {
        try {
            $task = Task::query()
                ->where('user_id', auth()->user()->getAuthIdentifier())
                ->findOrFail($args['id']);

            $task->delete();

            return $task;
        }
        catch (ModelNotFoundException $exception) {
            throw ValidationException::withMessages(['id' => 'The selected validation.attributes.id is invalid.']);
        }
    }
}
