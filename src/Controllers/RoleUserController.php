<?php

namespace Carliban\TablePermissions\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Spatie\Permission\Models\Role;

class RoleUserController extends Controller
{
    /**
     * Modelo de usuario configurado en auth.
     */
    protected function userModel(): string
    {
        return config(
            'auth.providers.users.model',
            \App\Models\User::class
        );
    }

    /**
     * Mostrar usuarios y su asignación al rol.
     */
    public function edit(Role $role): View
    {
        $userModel = $this->userModel();

        /** @var Model $user */
        $user = new $userModel();

        $users = $userModel::query()
            ->with('roles')
            ->orderBy(
                $user->getKeyName(),
                'desc'
            )
            ->paginate(20);

        $assignedUserIds = $role
            ->users()
            ->pluck(
                $user->qualifyColumn(
                    $user->getKeyName()
                )
            )
            ->map(
                fn ($id): string => (string) $id
            )
            ->all();

        return view(
            'table-permissions::roles.users',
            compact(
                'role',
                'users',
                'assignedUserIds'
            )
        );
    }

    /**
     * Sincronizar los usuarios del rol.
     */
    public function update(
        Request $request,
        Role $role
    ): RedirectResponse {
        $userModel = $this->userModel();

        $validated = $request->validate([
            'users' => [
                'nullable',
                'array',
            ],

            'users.*' => [
                'integer',
            ],
        ]);

        $selectedIds = collect(
            $validated['users'] ?? []
        )
            ->map(
                fn ($id): string => (string) $id
            );

        $users = $userModel::query()
            ->with('roles')
            ->get();

        foreach ($users as $user) {
            $mustHaveRole = $selectedIds->contains(
                (string) $user->getKey()
            );

            $currentlyHasRole = $user->hasRole($role);

            if ($mustHaveRole && !$currentlyHasRole) {
                $user->assignRole($role);
            }

            if (!$mustHaveRole && $currentlyHasRole) {
                $user->removeRole($role);
            }
        }

        return redirect()
            ->route(
                'table-permissions.roles.show',
                $role
            )
            ->with(
                'success',
                'Usuarios del rol actualizados.'
            );
    }
}