<?php

namespace Carliban\TablePermissions\Commands;

use Carliban\TablePermissions\Services\PermissionSynchronizer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Role;

class InstallTablePermissionsCommand extends Command
{
    protected $signature =
        'table-permissions:install
        {--migrate : Ejecutar las migraciones automáticamente}';

    protected $description =
        'Instala y configura Table Permissions';

    public function handle(
        PermissionSynchronizer $synchronizer
    ): int {
        $this->components->info(
            'Instalando Table Permissions...'
        );

        Artisan::call('vendor:publish', [
            '--provider' =>
                'Spatie\Permission\PermissionServiceProvider',
        ]);

        Artisan::call('vendor:publish', [
            '--tag' => 'table-permissions-config',
        ]);

        if ($this->option('migrate')) {
            Artisan::call('migrate', [
                '--force' => true,
            ]);

            $this->output->write(
                Artisan::output()
            );
        }

        Role::findOrCreate(
            config(
                'table-permissions.administrator_role',
                'administrador'
            ),
            config(
                'table-permissions.guard',
                'web'
            )
        );

        if ($this->option('migrate')) {
            $result = $synchronizer->sync();

            $this->components->info(
                "Permisos creados: {$result['created']->count()}"
            );
        }

        $this->components->info(
            'Instalación completada.'
        );

        $this->components->warn(
            'Agrega HasRoles al modelo User.'
        );

        return self::SUCCESS;
    }
}