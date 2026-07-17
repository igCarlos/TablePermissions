<?php

namespace Carliban\TablePermissions\Controllers;

use Carliban\TablePermissions\Services\PermissionSynchronizer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionController extends Controller
{
    /**
     * Mostrar listado de roles.
     */
    public function index(): View
    {
        $roles = Role::query()
            ->withCount([
                'permissions',
                'users',
            ])
            ->with([
                'users' => function ($query) {
                    $query->limit(5);
                },
            ])
            ->orderBy('name')
            ->get();

        return view(
            'table-permissions::permissions.index',
            compact('roles')
        );
    }

    /**
     * Mostrar permisos de un rol específico.
     */
    public function edit(
        Role $role,
        PermissionSynchronizer $synchronizer
    ): View {
        $role->load('permissions');

        $permissionGroups =
            $synchronizer->groupedPermissions();

        return view(
            'table-permissions::permissions.edit',
            compact(
                'role',
                'permissionGroups'
            )
        );
    }

    /**
     * Actualizar permisos del rol.
     */
    public function update(
        Request $request,
        Role $role
    ): RedirectResponse {
        $validated = $request->validate([
            'permissions' => [
                'nullable',
                'array',
            ],

            'permissions.*' => [
                'integer',
                'exists:permissions,id',
            ],
        ]);

        $permissionIds = collect(
            $validated['permissions'] ?? []
        )
            ->map(fn ($id): int => (int) $id)
            ->unique()
            ->values();

        $permissions = Permission::query()
            ->where(
                'guard_name',
                $role->guard_name
            )
            ->whereIn(
                'id',
                $permissionIds
            )
            ->get();

        $role->syncPermissions($permissions);

        app(PermissionRegistrar::class)
            ->forgetCachedPermissions();

        return redirect()
            ->route(
                'table-permissions.permissions.index'
            )
            ->with(
                'success',
                "Permisos del rol {$role->name} actualizados correctamente."
            );
    }

    /**
     * Detectar modelos y crear permisos faltantes.
     */
    public function synchronize(
        PermissionSynchronizer $synchronizer
    ): RedirectResponse {
        $result = $synchronizer->sync();

        $createdCount =
            $result['created']->count();

        $errorCount =
            $result['errors']->count();

        if ($errorCount > 0) {
            return back()->with(
                'error',
                "Se crearon {$createdCount} permisos, ".
                "pero ocurrieron {$errorCount} errores."
            );
        }

        if ($createdCount === 0) {
            return back()->with(
                'info',
                'Todos los modelos ya tienen sus permisos.'
            );
        }

        return back()->with(
            'success',
            "Se crearon {$createdCount} permisos nuevos."
        );
    }
}