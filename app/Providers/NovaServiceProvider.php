<?php

declare(strict_types=1);

namespace App\Providers;

use App\Nova\Dashboards\Main;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;
use Laravel\Nova\Util;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    protected function routes(): void
    {
        Nova::routes()
            ->withAuthenticationRoutes()
            ->withPasswordResetRoutes()
            ->register();
    }

    protected function gate(): void
    {
        Gate::define('viewNova', function ($user) {
            return filled($user);
        });
    }

    /**
     * @return array<int, \Laravel\Nova\Dashboard>
     */
    protected function dashboards(): array
    {
        return [
            Main::make(),
        ];
    }

    public function register(): void
    {
        Nova::createUserUsing(
            createUserCommandCallback: function ($command) {
                return [
                    $command->ask('First Name'),
                    $command->ask('Last Name'),
                    $command->ask('Email Address'),
                    $command->secret('Password'),
                ];
            },
            createUserCallback: function ($first_name, $last_name, $email, $password) {
                $model = Util::userModel();

                return tap((new $model())->forceFill([
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'email' => $email,
                    'password' => Hash::make($password),
                ]))->save();
            },
        );
    }
}
