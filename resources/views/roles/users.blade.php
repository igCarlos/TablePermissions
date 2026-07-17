<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>
        Usuarios del rol
    </title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f3f4f6;
            color: #111827;
            font-family: Arial, sans-serif;
        }

        .container {
            width: min(1200px, 94%);
            margin: 40px auto;
        }

        .header,
        .actions {
            display: flex;
            gap: 12px;
            align-items: center;
            justify-content: space-between;
        }

        .header {
            margin-bottom: 24px;
        }

        .header h1 {
            margin: 0 0 8px;
        }

        .header p {
            margin: 0;
            color: #4b5563;
        }

        .button {
            display: inline-block;
            padding: 10px 16px;
            border: 0;
            border-radius: 8px;
            background: #2563eb;
            color: white;
            cursor: pointer;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
        }

        .button:hover {
            background: #1d4ed8;
        }

        .button-secondary {
            background: #4b5563;
        }

        .button-secondary:hover {
            background: #374151;
        }

        .alert {
            margin-bottom: 18px;
            padding: 14px;
            border-radius: 8px;
        }

        .success {
            background: #dcfce7;
            color: #166534;
        }

        .error {
            background: #fee2e2;
            color: #991b1b;
        }

        .warning {
            background: #fef3c7;
            color: #92400e;
        }

        .error ul {
            margin: 8px 0 0;
            padding-left: 20px;
        }

        .panel {
            overflow: hidden;
            border-radius: 12px;
            background: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .06);
        }

        .panel-header {
            display: flex;
            gap: 12px;
            align-items: center;
            justify-content: space-between;
            padding: 20px 24px;
            border-bottom: 1px solid #e5e7eb;
        }

        .panel-header h2 {
            margin: 0 0 5px;
            font-size: 20px;
        }

        .panel-header p {
            margin: 0;
            color: #6b7280;
            font-size: 14px;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 15px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
            vertical-align: middle;
        }

        th {
            background: #f9fafb;
            color: #374151;
            font-size: 14px;
        }

        tbody tr:hover {
            background: #f9fafb;
        }

        tbody tr:last-child td {
            border-bottom: 0;
        }

        .checkbox-cell {
            width: 130px;
            text-align: center;
        }

        .user-checkbox {
            width: 18px;
            height: 18px;
            margin: 0;
            accent-color: #2563eb;
            cursor: pointer;
        }

        .user-checkbox:disabled {
            cursor: not-allowed;
            opacity: .6;
        }

        .protected-message {
            margin-top: 6px;
            color: #b45309;
            font-size: 11px;
            font-weight: 700;
            line-height: 1.2;
        }

        .user-name {
            font-weight: 700;
        }

        .muted {
            color: #6b7280;
        }

        .roles {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .badge {
            display: inline-block;
            padding: 5px 9px;
            border-radius: 999px;
            background: #e5e7eb;
            color: #374151;
            font-size: 13px;
            font-weight: 700;
        }

        .badge-current {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .badge-protected {
            background: #fef3c7;
            color: #92400e;
        }

        .panel-footer {
            display: flex;
            gap: 12px;
            align-items: center;
            justify-content: flex-end;
            padding: 20px 24px;
            border-top: 1px solid #e5e7eb;
            background: #f9fafb;
        }

        .pagination-wrapper {
            margin-top: 20px;
        }

        .pagination-wrapper nav {
            display: flex;
            justify-content: center;
        }

        .empty {
            padding: 25px;
            color: #6b7280;
            text-align: center;
        }

        @media (max-width: 700px) {
            .container {
                margin: 24px auto;
            }

            .header {
                align-items: flex-start;
                flex-direction: column;
            }

            .header .actions {
                width: 100%;
                align-items: stretch;
                flex-direction: column;
            }

            .header .button {
                width: 100%;
                text-align: center;
            }

            .panel-header {
                align-items: flex-start;
                flex-direction: column;
                padding: 18px;
            }

            .panel-footer {
                align-items: stretch;
                flex-direction: column-reverse;
                padding: 18px;
            }

            .panel-footer .button {
                width: 100%;
                text-align: center;
            }

            th,
            td {
                padding: 12px;
            }
        }
    </style>
</head>

<body>

@php
    /*
    |--------------------------------------------------------------------------
    | Configuración del rol administrador
    |--------------------------------------------------------------------------
    */

    $administratorRole = config(
        'table-permissions.administrator_role',
        'administrador'
    );

    $administratorGuard = config(
        'table-permissions.guard',
        'web'
    );

    /*
    |--------------------------------------------------------------------------
    | Verificar si el rol actual es el administrador
    |--------------------------------------------------------------------------
    */

    $isAdministratorRole =
        $role->name === $administratorRole
        && $role->guard_name === $administratorGuard;

    /*
    |--------------------------------------------------------------------------
    | Cantidad total de administradores
    |--------------------------------------------------------------------------
    */

    $administratorCount = $isAdministratorRole
        ? $role->users()->count()
        : 0;
@endphp

<div class="container">

    <div class="header">

        <div>
            <h1>
                Usuarios del rol:
                {{ ucfirst($role->name) }}
            </h1>

            <p>
                Selecciona los usuarios que tendrán asignado este rol.
            </p>
        </div>

        <div class="actions">

            <a
                href="{{ route(
                    'table-permissions.roles.show',
                    $role
                ) }}"
                class="button button-secondary"
            >
                Volver al rol
            </a>

        </div>

    </div>

    @if(session('success'))
        <div class="alert success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert error">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert error">

            <strong>
                No se pudieron guardar los cambios.
            </strong>

            <ul>
                @foreach($errors->all() as $error)
                    <li>
                        {{ $error }}
                    </li>
                @endforeach
            </ul>

        </div>
    @endif

    @if(
        $isAdministratorRole
        && $administratorCount === 1
    )
        <div class="alert warning">
            El sistema tiene un solo administrador.
            Este usuario está protegido y no puede perder el rol.
        </div>
    @endif

    <form
        action="{{ route(
            'table-permissions.roles.users.update',
            $role
        ) }}"
        method="POST"
    >
        @csrf
        @method('PUT')

        <div class="panel">

            <div class="panel-header">

                <div>
                    <h2>
                        Lista de usuarios
                    </h2>

                    <p>
                        Marca o desmarca usuarios para asignarles este rol.
                    </p>
                </div>

            </div>

            <div class="table-wrapper">

                <table>

                    <thead>
                        <tr>
                            <th class="checkbox-cell">
                                Asignar
                            </th>

                            <th>
                                ID
                            </th>

                            <th>
                                Nombre
                            </th>

                            <th>
                                Correo
                            </th>

                            <th>
                                Roles asignados
                            </th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($users as $user)

                            @php
                                /*
                                |----------------------------------------------
                                | Verificar si el usuario tiene el rol actual
                                |----------------------------------------------
                                */

                                $userHasCurrentRole = in_array(
                                    (string) $user->getKey(),
                                    $assignedUserIds,
                                    true
                                );

                                /*
                                |----------------------------------------------
                                | Proteger al último administrador
                                |----------------------------------------------
                                */

                                $isLastAdministrator =
                                    $isAdministratorRole
                                    && $administratorCount === 1
                                    && $userHasCurrentRole;
                            @endphp

                            <tr>

                                <td class="checkbox-cell">

                                    <input
                                        type="checkbox"
                                        name="users[]"
                                        value="{{ $user->getKey() }}"
                                        class="user-checkbox"

                                        @checked($userHasCurrentRole)

                                        @disabled($isLastAdministrator)
                                    >

                                    @if($isLastAdministrator)

                                        {{--
                                            Los checkbox deshabilitados no
                                            se envían en la petición.

                                            Este campo hidden conserva al
                                            usuario como administrador.
                                        --}}

                                        <input
                                            type="hidden"
                                            name="users[]"
                                            value="{{ $user->getKey() }}"
                                        >

                                        <div class="protected-message">
                                            Último administrador
                                        </div>

                                    @endif

                                </td>

                                <td>
                                    {{ $user->getKey() }}
                                </td>

                                <td class="user-name">
                                    {{ $user->name ?? 'Sin nombre' }}
                                </td>

                                <td>
                                    <span class="muted">
                                        {{ $user->email ?? 'Sin correo' }}
                                    </span>
                                </td>

                                <td>

                                    <div class="roles">

                                        @forelse(
                                            $user->getRoleNames()
                                            as $userRole
                                        )

                                            <span
                                                class="badge
                                                    @if(
                                                        $userRole
                                                        === $role->name
                                                    )
                                                        badge-current
                                                    @endif

                                                    @if(
                                                        $isLastAdministrator
                                                        && $userRole
                                                        === $role->name
                                                    )
                                                        badge-protected
                                                    @endif
                                                "
                                            >
                                                {{ ucfirst($userRole) }}
                                            </span>

                                        @empty

                                            <span class="muted">
                                                Sin roles
                                            </span>

                                        @endforelse

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td
                                    colspan="5"
                                    class="empty"
                                >
                                    No existen usuarios registrados.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="panel-footer">

                <a
                    href="{{ route(
                        'table-permissions.roles.show',
                        $role
                    ) }}"
                    class="button button-secondary"
                >
                    Cancelar
                </a>

                <button
                    type="submit"
                    class="button"
                >
                    Guardar usuarios del rol
                </button>

            </div>

        </div>

    </form>

    @if($users->hasPages())
        <div class="pagination-wrapper">
            {{ $users->links() }}
        </div>
    @endif

</div>

</body>

</html>