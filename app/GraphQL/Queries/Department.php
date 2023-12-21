<?php

namespace App\GraphQL\Queries;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use App\Models\Department as Model;

class Department
{
    /**
     * @param mixed $_
     * @param array $args
     * @return Department|null
     */
    public function __invoke(mixed $_, array $args): ?Department

    {
            $department = Model::query()
                ->where('id', $args['id'])
                ->firstOrFail();

            return $department;
        }
    }
