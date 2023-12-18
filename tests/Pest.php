<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

use Illuminate\Testing\TestResponse;

uses(
    Tests\TestCase::class,
    \Illuminate\Foundation\Testing\LazilyRefreshDatabase::class,
    // Illuminate\Foundation\Testing\RefreshDatabase::class,
)->in('Feature');

uses(
    \Nuwave\Lighthouse\Testing\MakesGraphQLRequests::class,
)->in('Feature/Graphql');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function postGraphql(array $data, array $headers = [])
{
    return test()->postGraphql($data, $headers);
}

function login(array $data): TestResponse
{
    return postGraphql(
        [
            'query' => /** @lang GraphQL */ <<<'GRAPHQL'
                query login(
                    $email: String!,
                    $password: String!
                ) {
                    login(
                        email: $email
                        password: $password
                    ) {
                        id
                        token
                        first_name
                        last_name
                        email
                        created_at
                        updated_at
                    }
                }
                GRAPHQL,
            'variables' => $data,
        ],
    );
}

function me(?string $token = null): TestResponse
{
    return postGraphql(
        [
            'query' => /** @lang GraphQL */ <<<'GRAPHQL'
                query me {
                    me {
                        id
                        first_name
                        last_name
                        email
                        created_at
                        updated_at
                    }
                }
                GRAPHQL,
        ],
        [
            'Authorization' => $token ? "Bearer {$token}" : null,
        ],
    );
}
