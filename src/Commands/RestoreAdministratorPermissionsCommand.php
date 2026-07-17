<?php

namespace Carliban\TablePermissions\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Throwable;

class RestoreAdministratorPermissionsCommand extends Command
{
    /**
     * Nombre y opciones del comando.
     *
     * --force:
     * Ejecuta el comando sin solicitar confirmación.
     *
     * --skip-sync:
     * No ejecuta la generación de permisos faltantes.
     */
    protected $signature = '
        table-permissions:restore
        {--force : Ejecutar sin solicitar confirmación}
        {--skip-sync : No sincronizar permisos faltantes}
    ';

    /**
     * Descripción mostrada en php artisan list.
     */
    protected $description =
        'Restaura todos los permisos del rol administrador';

    /**
     * Ejecutar el comando.
     */
    public function handle(
        PermissionRegistrar $permissionRegistrar
    ): int {
        $administratorRole = config(
            'table-permissions.administrator_role',
            'administrador'
        );

        $guard = config(
            'table-permissions.guard',
            'web'
        );

        $this->newLine();

        $this->components->info(
            'Restauración de permisos del administrador'
        );

        $this->line(
            "Rol administrador: <comment>{$administratorRole}</comment>"
        );

        $this->line(
            "Guard: <comment>{$guard}</comment>"
        );

        $this->newLine();

        /*
        |--------------------------------------------------------------------------
        | Confirmar la operación
        |--------------------------------------------------------------------------
        */

        if (! $this->option('force')) {
            $confirmed = $this->confirm(
                '¿Deseas asignar todos los permisos al rol administrador?',
                true
            );

            if (! $confirmed) {
                $this->components->warn(
                    'La restauración fue cancelada.'
                );

                return self::SUCCESS;
            }
        }

        try {
            /*
            |--------------------------------------------------------------------------
            | Limpiar caché antes de comenzar
            |--------------------------------------------------------------------------
            */

            $permissionRegistrar->forgetCachedPermissions();

            /*
            |--------------------------------------------------------------------------
            | Generar permisos faltantes
            |--------------------------------------------------------------------------
            |
            | Ejecuta el comando que ya posee el paquete para crear permisos
            | basándose en las tablas o modelos configurados.
            |
            */

            if (! $this->option('skip-sync')) {
                $this->components->task(
                    'Generando permisos faltantes',
                    function (): bool {
                        return $this->callSilent(
                            'table-permissions:sync-tables'
                        ) === self::SUCCESS;
                    }
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Buscar o volver a crear el rol administrador
            |--------------------------------------------------------------------------
            */

            $role = Role::findOrCreate(
                $administratorRole,
                $guard
            );

            /*
            |--------------------------------------------------------------------------
            | Obtener todos los permisos del guard configurado
            |--------------------------------------------------------------------------
            */

            $permissions = Permission::query()
                ->where('guard_name', $guard)
                ->orderBy('name')
                ->get();

            if ($permissions->isEmpty()) {
                $this->components->error(
                    "No existen permisos para el guard [{$guard}]."
                );

                $this->line(
                    'Verifica que el comando '
                    . 'table-permissions:sync-tables esté generando los permisos.'
                );

                return self::FAILURE;
            }

            /*
            |--------------------------------------------------------------------------
            | Restaurar todos los permisos del administrador
            |--------------------------------------------------------------------------
            |
            | syncPermissions elimina las asignaciones anteriores del rol y
            | asigna exactamente todos los permisos encontrados.
            |
            | Esto solamente modifica el rol administrador.
            |
            */

            $role->syncPermissions($permissions);

            /*
            |--------------------------------------------------------------------------
            | Limpiar nuevamente la caché
            |--------------------------------------------------------------------------
            */

            $permissionRegistrar->forgetCachedPermissions();

            /*
            |--------------------------------------------------------------------------
            | Resultado
            |--------------------------------------------------------------------------
            */

            $this->newLine();

            $this->components->info(
                'Permisos restaurados correctamente.'
            );

            $this->table(
                [
                    'Dato',
                    'Resultado',
                ],
                [
                    [
                        'Rol',
                        $role->name,
                    ],
                    [
                        'Guard',
                        $role->guard_name,
                    ],
                    [
                        'Permisos asignados',
                        (string) $permissions->count(),
                    ],
                ]
            );

            $this->newLine();

            $this->line(
                '<info>El rol administrador recuperó todos los permisos.</info>'
            );

            return self::SUCCESS;
        } catch (Throwable $exception) {
            $this->newLine();

            $this->components->error(
                'No se pudieron restaurar los permisos.'
            );

            $this->line(
                '<error>'
                . $exception->getMessage()
                . '</error>'
            );

            if ($this->output->isVerbose()) {
                $this->newLine();
                $this->line($exception->getTraceAsString());
            }

            return self::FAILURE;
        }
    }
}