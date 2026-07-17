<?php

namespace Carliban\TablePermissions\Commands;

use Carliban\TablePermissions\Services\PermissionSynchronizer;
use Illuminate\Console\Command;

class SyncTablePermissionsCommand extends Command
{
    protected $signature = 'table-permissions:sync-tables';

    protected $description =
        'Detecta las tablas y crea sus permisos faltantes';

    public function handle(
        PermissionSynchronizer $synchronizer
    ): int {
        $this->components->info(
            'Revisando tablas de la base de datos...'
        );

        $result = $synchronizer->sync();

        $created = $result['created'];
        $existing = $result['existing'];
        $errors = $result['errors'];

        if ($created->isNotEmpty()) {
            $this->table(
                ['Tabla', 'Permiso'],
                $created
                    ->map(
                        fn (array $item): array => [
                            $item['table'],
                            $item['permission'],
                        ]
                    )
                    ->all()
            );
        } else {
            $this->components->info(
                'No existen permisos pendientes.'
            );
        }

        $this->table(
            ['Resultado', 'Cantidad'],
            [
                [
                    'Tablas revisadas',
                    $result['tables']->count(),
                ],
                [
                    'Permisos creados',
                    $created->count(),
                ],
                [
                    'Permisos existentes',
                    $existing->count(),
                ],
                [
                    'Errores',
                    $errors->count(),
                ],
            ]
        );

        return $errors->isEmpty()
            ? self::SUCCESS
            : self::FAILURE;
    }
}