<?php

namespace App\GraphQL\Queries;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use App\Models\Department;

class AllDepartment
{
    /**
     * @param mixed $_
     * @param array $args
     * @return Department|null
     */
    public function __invoke(mixed $_, array $args): Array

    {
            $department = Department::query()->paginate(20);

            return $department;

    }
}
