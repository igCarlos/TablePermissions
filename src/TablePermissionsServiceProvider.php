<?php

namespace Carliban\TablePermissions;

use Carliban\TablePermissions\Commands\InstallTablePermissionsCommand;
use Carliban\TablePermissions\Commands\SyncTablePermissionsCommand;
use Carliban\TablePermissions\Services\PermissionSynchronizer;
use Illuminate\Support\ServiceProvider;

class TablePermissionsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/table-permissions.php',
            'table-permissions'
        );

        $this->app->singleton(
            PermissionSynchronizer::class
        );
    }

    public function boot(): void
    {
        $this->loadViewsFrom(
            __DIR__.'/../resources/views',
            'table-permissions'
        );

        if (
            config(
                'table-permissions.routes.enabled',
                true
            )
        ) {
            $this->loadRoutesFrom(
                __DIR__.'/../routes/web.php'
            );
        }

        $this->publishes([
            __DIR__.'/../config/table-permissions.php'
                => config_path('table-permissions.php'),
        ], 'table-permissions-config');

        $this->publishes([
            __DIR__.'/../resources/views'
                => resource_path(
                    'views/vendor/table-permissions'
                ),
        ], 'table-permissions-views');

        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallTablePermissionsCommand::class,
                SyncTablePermissionsCommand::class,
            ]);
        }
    }
}