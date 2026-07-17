<?php

namespace Carliban\TablePermissions\Commands;

use Carliban\TablePermissions\Services\PermissionSynchronizer;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Throwable;

class InstallTablePermissionsCommand extends Command
{
    protected $signature =
        'table-permissions:install
        {--force : Sobrescribir archivos publicados}';

    protected $description =
        'Instala y configura completamente Table Permissions';

    public function handle(
        PermissionSynchronizer $synchronizer
    ): int {
        $this->components->info(
            'Instalando Table Permissions...'
        );

        try {
            /*
            |--------------------------------------------------------------------------
            | 1. Publicar archivos de Spatie Permission
            |--------------------------------------------------------------------------
            */

            $this->components->task(
                'Publicando configuración y migraciones de Spatie',
                function (): bool {
                    $arguments = [
                        '--provider' =>
                            'Spatie\Permission\PermissionServiceProvider',
                    ];

                    if ($this->option('force')) {
                        $arguments['--force'] = true;
                    }

                    Artisan::call(
                        'vendor:publish',
                        $arguments
                    );

                    return true;
                }
            );

            /*
            |--------------------------------------------------------------------------
            | 2. Publicar configuración del paquete
            |--------------------------------------------------------------------------
            */

            $this->components->task(
                'Publicando configuración de Table Permissions',
                function (): bool {
                    return $this->publishTag(
                        'table-permissions-config'
                    );
                }
            );

            /*
            |--------------------------------------------------------------------------
            | 3. Publicar vistas del paquete
            |--------------------------------------------------------------------------
            */

            $this->components->task(
                'Publicando vistas de Table Permissions',
                function (): bool {
                    return $this->publishTag(
                        'table-permissions-views'
                    );
                }
            );

            /*
            |--------------------------------------------------------------------------
            | 4. Limpiar caché de configuración
            |--------------------------------------------------------------------------
            */

            $this->components->task(
                'Limpiando caché',
                function (): bool {
                    Artisan::call('config:clear');

                    return true;
                }
            );

            /*
            |--------------------------------------------------------------------------
            | 5. Ejecutar migraciones
            |--------------------------------------------------------------------------
            */

            $migrationExitCode = null;

            $this->components->task(
                'Ejecutando migraciones',
                function () use (
                    &$migrationExitCode
                ): bool {
                    $migrationExitCode =
                        Artisan::call(
                            'migrate',
                            [
                                '--force' => true,
                            ]
                        );

                    return $migrationExitCode === 0;
                }
            );

            if ($migrationExitCode !== 0) {
                $this->newLine();

                $this->components->error(
                    'Ocurrió un error al ejecutar las migraciones.'
                );

                $this->line(
                    Artisan::output()
                );

                return self::FAILURE;
            }

            /*
            |--------------------------------------------------------------------------
            | 6. Verificar tablas de Spatie
            |--------------------------------------------------------------------------
            */

            $rolesTable = config(
                'permission.table_names.roles',
                'roles'
            );

            $permissionsTable = config(
                'permission.table_names.permissions',
                'permissions'
            );

            if (! Schema::hasTable($rolesTable)) {
                $this->components->error(
                    "La tabla [{$rolesTable}] no existe."
                );

                return self::FAILURE;
            }

            if (! Schema::hasTable($permissionsTable)) {
                $this->components->error(
                    "La tabla [{$permissionsTable}] no existe."
                );

                return self::FAILURE;
            }

            /*
            |--------------------------------------------------------------------------
            | 7. Limpiar caché de permisos
            |--------------------------------------------------------------------------
            */

            app(PermissionRegistrar::class)
                ->forgetCachedPermissions();

            /*
            |--------------------------------------------------------------------------
            | 8. Crear rol administrador
            |--------------------------------------------------------------------------
            */

            $administratorRole = config(
                'table-permissions.administrator_role',
                'administrador'
            );

            $guard = config(
                'table-permissions.guard',
                'web'
            );

            $role = null;

            $this->components->task(
                'Creando rol administrador',
                function () use (
                    &$role,
                    $administratorRole,
                    $guard
                ): bool {
                    $role = Role::findOrCreate(
                        $administratorRole,
                        $guard
                    );

                    return true;
                }
            );

            /*
            |--------------------------------------------------------------------------
            | 9. Sincronizar permisos
            |--------------------------------------------------------------------------
            */

            $result = null;

            $this->components->task(
                'Generando permisos automáticamente',
                function () use (
                    $synchronizer,
                    &$result
                ): bool {
                    $result =
                        $synchronizer->sync();

                    return true;
                }
            );

            /*
            |--------------------------------------------------------------------------
            | 10. Obtener permisos
            |--------------------------------------------------------------------------
            */

            app(PermissionRegistrar::class)
                ->forgetCachedPermissions();

            $permissionModel = config(
                'permission.models.permission'
            );

            $permissions =
                $permissionModel::query()
                    ->where(
                        'guard_name',
                        $guard
                    )
                    ->get();

            /*
            |--------------------------------------------------------------------------
            | 11. Asignar permisos al administrador
            |--------------------------------------------------------------------------
            */

            $this->components->task(
                'Asignando permisos al administrador',
                function () use (
                    $role,
                    $permissions
                ): bool {
                    $role->syncPermissions(
                        $permissions
                    );

                    return true;
                }
            );

            /*
            |--------------------------------------------------------------------------
            | 12. Asignar rol al primer usuario
            |--------------------------------------------------------------------------
            */

            $userModel = config(
                'auth.providers.users.model'
            );

            $administratorUser = null;

            if (
                is_string($userModel)
                && class_exists($userModel)
                && is_subclass_of(
                    $userModel,
                    Model::class
                )
            ) {
                $userInstance =
                    new $userModel();

                $administratorUser =
                    $userModel::query()
                        ->orderBy(
                            $userInstance->getKeyName()
                        )
                        ->first();
            }

            if ($administratorUser) {
                if (! method_exists(
                    $administratorUser,
                    'assignRole'
                )) {
                    $this->components->warn(
                        'El modelo User no utiliza el trait HasRoles.'
                    );
                } else {
                    $this->components->task(
                        'Asignando rol al primer usuario',
                        function () use (
                            $administratorUser,
                            $role
                        ): bool {
                            if (
                                ! $administratorUser
                                    ->hasRole(
                                        $role->name
                                    )
                            ) {
                                $administratorUser
                                    ->assignRole(
                                        $role
                                    );
                            }

                            return true;
                        }
                    );
                }
            } else {
                $this->components->warn(
                    'No existe ningún usuario para asignarle el rol administrador.'
                );
            }

            app(PermissionRegistrar::class)
                ->forgetCachedPermissions();

            /*
            |--------------------------------------------------------------------------
            | Resultado final
            |--------------------------------------------------------------------------
            */

            $createdCount =
                isset($result['created'])
                    ? $result['created']->count()
                    : 0;

            $userResult =
                $administratorUser
                    ? (
                        $administratorUser->email
                        ?? 'ID '
                            . $administratorUser
                                ->getKey()
                    )
                    : 'No encontrado';

            $this->newLine();

            $this->components->info(
                'Instalación completada correctamente.'
            );

            $this->table(
                [
                    'Elemento',
                    'Resultado',
                ],
                [
                    [
                        'Configuración',
                        'Publicada',
                    ],
                    [
                        'Vistas',
                        'Publicadas',
                    ],
                    [
                        'Migraciones',
                        'Ejecutadas',
                    ],
                    [
                        'Rol administrador',
                        $role->name,
                    ],
                    [
                        'Guard',
                        $role->guard_name,
                    ],
                    [
                        'Usuario administrador',
                        $userResult,
                    ],
                    [
                        'Permisos nuevos',
                        $createdCount,
                    ],
                    [
                        'Permisos asignados',
                        $permissions->count(),
                    ],
                ]
            );

            return self::SUCCESS;
        } catch (Throwable $exception) {
            $this->newLine();

            $this->components->error(
                'No se pudo completar la instalación.'
            );

            $this->components->error(
                $exception->getMessage()
            );

            if ($this->output->isVerbose()) {
                $this->newLine();

                $this->line(
                    $exception->getTraceAsString()
                );
            }

            return self::FAILURE;
        }
    }

    private function publishTag(
        string $tag
    ): bool {
        $arguments = [
            '--tag' => $tag,
        ];

        if ($this->option('force')) {
            $arguments['--force'] = true;
        }

        $exitCode = Artisan::call(
            'vendor:publish',
            $arguments
        );

        return $exitCode === 0;
    }
}