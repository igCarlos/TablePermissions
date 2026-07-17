<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>Roles</title>

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

        .button {
            display: inline-block;
            padding: 10px 16px;
            border: 0;
            border-radius: 8px;
            background: #2563eb;
            color: white;
            cursor: pointer;
            font-weight: 700;
            text-decoration: none;
        }

        .button-secondary {
            background: #4b5563;
        }

        .button-danger {
            background: #dc2626;
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

        .table-wrapper {
            overflow-x: auto;
            border-radius: 12px;
            background: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .06);
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
            vertical-align: top;
        }

        th {
            background: #f9fafb;
        }

        .users {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .badge {
            padding: 5px 9px;
            border-radius: 999px;
            background: #e5e7eb;
            font-size: 13px;
        }

        .inline {
            display: inline;
        }
    </style>
</head>

<body>

<div class="container">

    <div class="header">
        <div>
            <h1>Roles</h1>
            <p>
                Administra los roles, permisos y usuarios asignados.
            </p>
        </div>

        <div class="actions">
            <a
                href="{{ route(
                    'table-permissions.permissions.index'
                ) }}"
                class="button button-secondary"
            >
                Ver permisos
            </a>

            <a
                href="{{ route(
                    'table-permissions.roles.create'
                ) }}"
                class="button"
            >
                Crear rol
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

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Rol</th>
                    <th>Permisos</th>
                    <th>Usuarios asignados</th>
                    <th>Usuarios</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                @forelse($roles as $role)
                    <tr>
                        <td>
                            <strong>
                                {{ ucfirst($role->name) }}
                            </strong>
                        </td>

                        <td>
                            {{ $role->permissions_count }}
                        </td>

                        <td>
                            {{ $role->users_count }}
                        </td>

                        <td>
                            <div class="users">
                                @forelse(
                                    $role->users->take(5)
                                    as $user
                                )
                                    <span class="badge">
                                        {{
                                            $user->name
                                            ?? $user->email
                                            ?? 'Usuario '.$user->getKey()
                                        }}
                                    </span>
                                @empty
                                    Sin usuarios
                                @endforelse

                                @if($role->users_count > 5)
                                    <span class="badge">
                                        +{{ $role->users_count - 5 }}
                                    </span>
                                @endif
                            </div>
                        </td>

                        <td>
                            <div class="actions">
                                <a
                                    href="{{ route(
                                        'table-permissions.roles.show',
                                        $role
                                    ) }}"
                                    class="button button-secondary"
                                >
                                    Ver
                                </a>

                                <a
                                    href="{{ route(
                                        'table-permissions.roles.edit',
                                        $role
                                    ) }}"
                                    class="button"
                                >
                                    Editar
                                </a>

                                <a
                                    href="{{ route(
                                        'table-permissions.roles.users.edit',
                                        $role
                                    ) }}"
                                    class="button button-secondary"
                                >
                                    Usuarios
                                </a>
                                @can('roles.delete')
                                <form
                                    class="inline"
                                    action="{{ route(
                                        'table-permissions.roles.destroy',
                                        $role
                                    ) }}"
                                    method="POST"
                                    onsubmit="
                                        return confirm(
                                            '¿Eliminar este rol?'
                                        )
                                    "
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="button button-danger"
                                    >
                                        Eliminar
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            No hay roles registrados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

</body>
</html>