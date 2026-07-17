<?php

namespace Carliban\TablePermissions\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Listado general de roles.
     */
    public function index(): View
    {
        $roles = Role::query()
            ->with([
                'permissions',
                'users',
            ])
            ->withCount([
                'permissions',
                'users',
            ])
            ->orderBy('name')
            ->get();

        return view(
            'table-permissions::roles.index',
            compact('roles')
        );
    }

    /**
     * Formulario para crear un rol.
     */
    public function create(): View
    {
        return view(
            'table-permissions::roles.create'
        );
    }

    /**
     * Guardar un rol nuevo.
     */
    public function store(
        Request $request
    ): RedirectResponse {
        $guard = config(
            'table-permissions.guard',
            'web'
        );

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('roles', 'name')
                    ->where('guard_name', $guard),
            ],
        ]);

        Role::create([
            'name' => $validated['name'],
            'guard_name' => $guard,
        ]);

        return redirect()
            ->route('table-permissions.roles.index')
            ->with(
                'success',
                'Rol creado correctamente.'
            );
    }

    /**
     * Mostrar información del rol.
     */
    public function show(Role $role): View
    {
        $role->load([
            'permissions',
            'users',
        ]);

        return view(
            'table-permissions::roles.show',
            compact('role')
        );
    }

    /**
     * Editar permisos del rol.
     */
    public function edit(Role $role): View
    {
        $role->load('permissions');

        $permissionGroups = Permission::query()
            ->where(
                'guard_name',
                $role->guard_name
            )
            ->orderBy('name')
            ->get()
            ->groupBy(
                fn (Permission $permission): string =>
                    str($permission->name)
                        ->before('.')
                        ->toString()
            );

        return view(
            'table-permissions::roles.edit',
            compact(
                'role',
                'permissionGroups'
            )
        );
    }

    /**
     * Actualizar nombre y permisos.
     */
    public function update(
        Request $request,
        Role $role
    ): RedirectResponse {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('roles', 'name')
                    ->where(
                        'guard_name',
                        $role->guard_name
                    )
                    ->ignore($role->id),
            ],

            'permissions' => [
                'nullable',
                'array',
            ],

            'permissions.*' => [
                'integer',
                'exists:permissions,id',
            ],
        ]);

        $role->update([
            'name' => $validated['name'],
        ]);

        $permissions = Permission::query()
            ->where(
                'guard_name',
                $role->guard_name
            )
            ->whereIn(
                'id',
                $validated['permissions'] ?? []
            )
            ->get();

        $role->syncPermissions($permissions);

        return redirect()
            ->route(
                'table-permissions.roles.show',
                $role
            )
            ->with(
                'success',
                'Rol y permisos actualizados.'
            );
    }

    /**
     * Eliminar un rol.
     */
    public function destroy(
        Role $role
    ): RedirectResponse {
        $administratorRole = config(
            'table-permissions.administrator_role',
            'administrador'
        );

        if ($role->name === $administratorRole) {
            return back()->with(
                'error',
                'El rol administrador está protegido.'
            );
        }

        $role->delete();

        return redirect()
            ->route('table-permissions.roles.index')
            ->with(
                'success',
                'Rol eliminado correctamente.'
            );
    }
}