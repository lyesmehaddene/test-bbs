<?php

declare(strict_types=1);

use App\Models\User;
use function Pest\Laravel\travel;

function refresh(?string $token = null){
    return postGraphql(
        [
            'query' => /** @lang GraphQL */ <<<'GRAPHQL'
                query refresh {
                    refresh
                }
                GRAPHQL,
        ],
        [
            'Authorization' => $token ? "Bearer {$token}" : null,
        ],
    );
}

test('can refresh token', function () {
    $user = User::factory()->create(['password' => Hash::make('GoodPassword1+')]);
    $token = auth('api')->fromUser($user);

    $secondToken = refresh($token)->dump()->json('data.refresh.token');

    expect($token)->not->toBe($secondToken);

    me($secondToken)
        ->dump()
        ->assertJson([
        'data' => [
            'me' => [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
            ],
        ],
    ]);
});

test('refresh without token', function (){
    refresh()
        ->dump()
        ->assertGraphQLErrorFree()
        ->assertJson([
            'data' => ['refresh' => null]
        ]);
});

test('cant refresh when refresh TTL is expired', function () {
    // à toi de jouer
    // il y a une fonctionnalité de time travel, et le guard JWT a un TTL  de refresh (check la conf)

    $user = User::factory()->create(['password' => Hash::make('GoodPassword1+')]);

    $token = auth('api')->fromUser($user);

    travel(config('jwt.refresh_ttl') + 1)->minutes();

    refresh($token)
        ->dump()
        ->assertGraphQLErrorFree()
        ->assertJson([
            'data' => ['refresh' => null]
        ]);
});
