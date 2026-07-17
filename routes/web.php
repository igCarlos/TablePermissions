<?php

use Carliban\TablePermissions\Controllers\PermissionController;
use Carliban\TablePermissions\Controllers\RoleController;
use Carliban\TablePermissions\Controllers\RoleUserController;
use Illuminate\Support\Facades\Route;

Route::prefix(
    config(
        'table-permissions.routes.prefix',
        'administracion'
    )
)
    ->middleware(
        config(
            'table-permissions.routes.middleware',
            [
                'web',
                'auth',
                'role:administrador',
            ]
        )
    )
    ->name('table-permissions.')
    ->group(function (): void {

        /*
        |--------------------------------------------------------------------------
        | Roles
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/roles',
            [RoleController::class, 'index']
        )
            ->middleware('can:roles.view')
            ->name('roles.index');

        Route::get(
            '/roles/crear',
            [RoleController::class, 'create']
        )
            ->middleware('can:roles.create')
            ->name('roles.create');

        Route::post(
            '/roles',
            [RoleController::class, 'store']
        )
            ->middleware('can:roles.create')
            ->name('roles.store');

        Route::get(
            '/roles/{role}',
            [RoleController::class, 'show']
        )
            ->middleware('can:roles.view')
            ->name('roles.show');

        Route::get(
            '/roles/{role}/editar',
            [RoleController::class, 'edit']
        )
            ->middleware('can:roles.update')
            ->name('roles.edit');

        Route::put(
            '/roles/{role}',
            [RoleController::class, 'update']
        )
            ->middleware('can:roles.update')
            ->name('roles.update');

        Route::delete(
            '/roles/{role}',
            [RoleController::class, 'destroy']
        )
            ->middleware('can:roles.delete')
            ->name('roles.destroy');

        Route::get(
            '/roles/{role}/usuarios',
            [RoleUserController::class, 'edit']
        )
            ->middleware('can:roles.update')
            ->name('roles.users.edit');

        Route::put(
            '/roles/{role}/usuarios',
            [RoleUserController::class, 'update']
        )
            ->middleware('can:roles.update')
            ->name('roles.users.update');

        /*
        |--------------------------------------------------------------------------
        | Permisos
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/permisos',
            [PermissionController::class, 'index']
        )
            ->middleware('can:permissions.view')
            ->name('permissions.index');

        Route::post(
            '/permisos/sincronizar',
            [PermissionController::class, 'synchronize']
        )
            ->middleware('can:permissions.create')
            ->name('permissions.synchronize');

        Route::get(
            '/permisos/roles/{role}/editar',
            [PermissionController::class, 'edit']
        )
            ->middleware('can:permissions.update')
            ->name('permissions.roles.edit');

        Route::put(
            '/permisos/roles/{role}',
            [PermissionController::class, 'update']
        )
            ->middleware('can:permissions.update')
            ->name('permissions.roles.update');
    });