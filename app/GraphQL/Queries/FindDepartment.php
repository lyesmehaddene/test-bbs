<?php

namespace App\GraphQL\Queries;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use App\Models\Department;

class FindDepartment
{
    /**
     * @param mixed $_
     * @param array $args
     * @return Department|null
     */
    public function __invoke(mixed $_, array $args): ?Department

    {
        try {
            $department = Department::query()
                ->where('id', $args['id'])
                ->firstOrFail();

            return $department;
        }

        catch(ModelNotFoundException $exception){
            throw ValidationException::withMessages(['id' => 'The requested Department id does not exist.']);
        }
    }
}
