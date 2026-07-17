<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Roles y permisos</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f3f4f6;
            color: #111827;
            font-family: Arial, Helvetica, sans-serif;
        }

        .container {
            width: min(1200px, 94%);
            margin: 40px auto;
        }

        .header {
            display: flex;
            gap: 20px;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .header h1 {
            margin: 0 0 8px;
            font-size: 30px;
        }

        .header p {
            margin: 0;
            color: #6b7280;
        }

        .header-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 42px;
            padding: 10px 18px;
            border: 0;
            border-radius: 8px;
            background: #2563eb;
            color: white;
            font-weight: 700;
            cursor: pointer;
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
            margin-bottom: 20px;
            padding: 14px 16px;
            border-radius: 8px;
            font-weight: 600;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
        }

        .alert-info {
            background: #dbeafe;
            color: #1e40af;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
        }

        .table-container {
            overflow-x: auto;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            background: white;
            box-shadow:
                0 4px 12px
                rgba(0, 0, 0, 0.05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 16px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
            vertical-align: middle;
        }

        th {
            background: #f9fafb;
            color: #374151;
            font-size: 14px;
            text-transform: uppercase;
        }

        tr:last-child td {
            border-bottom: 0;
        }

        .role-name {
            font-weight: 700;
            text-transform: capitalize;
        }

        .badge {
            display: inline-flex;
            padding: 5px 10px;
            border-radius: 999px;
            background: #e5e7eb;
            font-size: 13px;
        }

        .users-list {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        .empty {
            padding: 35px;
            text-align: center;
            color: #6b7280;
        }

        @media (max-width: 700px) {
            .header {
                align-items: stretch;
                flex-direction: column;
            }

            .header-actions {
                flex-direction: column;
            }

            .button {
                width: 100%;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <div class="header">

        <div>
            <h1>Roles y permisos</h1>

            <p>
                Selecciona un rol para administrar sus permisos.
            </p>
        </div>

        <div class="header-actions">

            @can('roles.view')
                <a
                    href="{{ route(
                        'table-permissions.roles.index'
                    ) }}"
                    class="button button-secondary"
                >
                    Administrar roles
                </a>
            @endcan

            @can('permissions.create')
                <form
                    action="{{ route(
                        'table-permissions.permissions.synchronize'
                    ) }}"
                    method="POST"
                >
                    @csrf

                    <button
                        type="submit"
                        class="button"
                    >
                        Detectar permisos nuevos
                    </button>
                </form>
            @endcan

        </div>

    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info">
            {{ session('info') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-error">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="table-container">

        <table>

            <thead>
                <tr>
                    <th>Rol</th>
                    <th>Permisos asignados</th>
                    <th>Usuarios asignados</th>
                    <th>Usuarios</th>
                    <th>Acción</th>
                </tr>
            </thead>

            <tbody>

                @forelse($roles as $role)

                    <tr>

                        <td>
                            <span class="role-name">
                                {{ $role->name }}
                            </span>
                        </td>

                        <td>
                            <span class="badge">
                                {{ $role->permissions_count }}
                                permisos
                            </span>
                        </td>

                        <td>
                            <span class="badge">
                                {{ $role->users_count }}
                                usuarios
                            </span>
                        </td>

                        <td>
                            <div class="users-list">

                                @forelse($role->users as $user)

                                    <span class="badge">
                                        {{
                                            $user->name
                                            ?? $user->email
                                            ?? 'Usuario '.$user->getKey()
                                        }}
                                    </span>

                                @empty

                                    <span>
                                        Sin usuarios
                                    </span>

                                @endforelse

                                @if(
                                    $role->users_count
                                    > $role->users->count()
                                )
                                    <span class="badge">
                                        +{{
                                            $role->users_count
                                            - $role->users->count()
                                        }}
                                    </span>
                                @endif

                            </div>
                        </td>

                        <td>
                            @can('permissions.update')
                                <a
                                    href="{{ route(
                                        'table-permissions.permissions.roles.edit',
                                        $role
                                    ) }}"
                                    class="button"
                                >
                                    Editar permisos
                                </a>
                            @else
                                <span class="badge">
                                    Sin autorización
                                </span>
                            @endcan
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td
                            colspan="5"
                            class="empty"
                        >
                            No existen roles registrados.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

</body>
</html>