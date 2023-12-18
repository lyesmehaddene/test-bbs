<?php

namespace App\GraphQL\Queries;

use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class Refresh
{

    public function __invoke(mixed $_, array $args, GraphQLContext $context): ?string
    {
        try {
            return auth('api')->refresh(true, true);
        }
        catch(\Throwable $e){
            return null;
        }
    }

}
