<?php

namespace Carliban\TablePermissions\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Throwable;

class PermissionSynchronizer
{
    public function tables(): Collection
    {
        $excludedTables = collect(
            config('table-permissions.excluded_tables', [])
        );

        $excludedPrefixes = collect(
            config('table-permissions.excluded_prefixes', [])
        );

        return collect(Schema::getTables())
            ->map(function (array $table): ?string {
                return $table['name']
                    ?? $table['table_name']
                    ?? null;
            })
            ->filter()
            ->reject(
                fn (string $table): bool =>
                    $excludedTables->contains($table)
            )
            ->reject(function (
                string $table
            ) use ($excludedPrefixes): bool {
                return $excludedPrefixes->contains(
                    fn (string $prefix): bool =>
                        Str::startsWith($table, $prefix)
                );
            })
            ->sort()
            ->values();
    }

    public function actions(): Collection
    {
        return collect(
            config('table-permissions.actions', [])
        )
            ->map(
                fn ($action): string =>
                    trim((string) $action)
            )
            ->filter()
            ->unique()
            ->values();
    }

    public function permissionNames(
        string $table
    ): Collection {
        return $this->actions()
            ->map(
                fn (string $action): string =>
                    "{$table}.{$action}"
            );
    }

    public function sync(): array
    {
        $created = collect();
        $existing = collect();
        $errors = collect();

        $guard = config(
            'table-permissions.guard',
            'web'
        );

        foreach ($this->tables() as $table) {
            foreach (
                $this->permissionNames($table)
                as $permissionName
            ) {
                try {
                    $permission = Permission::query()
                        ->where('name', $permissionName)
                        ->where('guard_name', $guard)
                        ->first();

                    if ($permission !== null) {
                        $existing->push([
                            'table' => $table,
                            'permission' => $permissionName,
                        ]);

                        continue;
                    }

                    Permission::findOrCreate(
                        $permissionName,
                        $guard
                    );

                    $created->push([
                        'table' => $table,
                        'permission' => $permissionName,
                    ]);
                } catch (Throwable $exception) {
                    $errors->push([
                        'table' => $table,
                        'permission' => $permissionName,
                        'error' => $exception->getMessage(),
                    ]);
                }
            }
        }

        $this->assignToAdministrator(
            $created->pluck('permission')->all()
        );

        app(PermissionRegistrar::class)
            ->forgetCachedPermissions();

        return [
            'tables' => $this->tables(),
            'created' => $created,
            'existing' => $existing,
            'errors' => $errors,
        ];
    }

    protected function assignToAdministrator(
        array $permissions
    ): void {
        if ($permissions === []) {
            return;
        }

        $guard = config(
            'table-permissions.guard',
            'web'
        );

        $roleName = config(
            'table-permissions.administrator_role',
            'administrador'
        );

        $role = Role::findOrCreate(
            $roleName,
            $guard
        );

        $role->givePermissionTo($permissions);
    }

    public function groupedPermissions(): Collection
    {
        $guard = config(
            'table-permissions.guard',
            'web'
        );

        return Permission::query()
            ->where('guard_name', $guard)
            ->orderBy('name')
            ->get()
            ->groupBy(
                fn (Permission $permission): string =>
                    Str::before($permission->name, '.')
            );
    }
}