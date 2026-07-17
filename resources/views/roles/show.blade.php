<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>
        {{ ucfirst($role->name) }}
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

        .stats {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
            margin-bottom: 24px;
        }

        .stat-card {
            padding: 22px;
            border-radius: 12px;
            background: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .06);
        }

        .stat-card span {
            display: block;
            margin-bottom: 8px;
            color: #6b7280;
            font-size: 14px;
            font-weight: 700;
        }

        .stat-card strong {
            color: #111827;
            font-size: 30px;
        }

        .section {
            margin-bottom: 24px;
            padding: 24px;
            border-radius: 12px;
            background: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .06);
        }

        .section-header {
            display: flex;
            gap: 12px;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 18px;
        }

        .section-header h2 {
            margin: 0;
            font-size: 20px;
        }

        .permissions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .badge {
            display: inline-block;
            padding: 7px 11px;
            border-radius: 999px;
            background: #e5e7eb;
            color: #374151;
            font-size: 13px;
            font-weight: 700;
        }

        .empty {
            margin: 0;
            color: #6b7280;
        }

        .table-wrapper {
            overflow-x: auto;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
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
        }

        tbody tr:last-child td {
            border-bottom: 0;
        }

        tbody tr:hover {
            background: #f9fafb;
        }

        .footer-actions {
            display: flex;
            gap: 12px;
            align-items: center;
            justify-content: flex-end;
            flex-wrap: wrap;
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

            .stats {
                grid-template-columns: 1fr;
            }

            .section {
                padding: 18px;
            }

            .section-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .footer-actions {
                align-items: stretch;
                flex-direction: column;
            }

            .footer-actions .button {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <div class="header">

        <div>
            <h1>
                Rol: {{ ucfirst($role->name) }}
            </h1>

            <p>
                Consulta los permisos y usuarios asignados a este rol.
            </p>
        </div>

        <div class="actions">

            <a
                href="{{ route(
                    'table-permissions.roles.index'
                ) }}"
                class="button button-secondary"
            >
                Volver a roles
            </a>

        </div>

    </div>

    @if(session('success'))
        <div class="alert success">
            {{ session('success') }}
        </div>
    @endif

    <div class="stats">

        <div class="stat-card">
            <span>
                Permisos asignados
            </span>

            <strong>
                {{ $role->permissions->count() }}
            </strong>
        </div>

        <div class="stat-card">
            <span>
                Usuarios asignados
            </span>

            <strong>
                {{ $role->users->count() }}
            </strong>
        </div>

    </div>

    <div class="section">

        <div class="section-header">

            <h2>
                Permisos
            </h2>

            <a
                href="{{ route(
                    'table-permissions.roles.edit',
                    $role
                ) }}"
                class="button"
            >
                Editar permisos
            </a>

        </div>

        <div class="permissions">

            @forelse($role->permissions as $permission)

                <span class="badge">
                    {{ $permission->name }}
                </span>

            @empty

                <p class="empty">
                    Este rol no tiene permisos asignados.
                </p>

            @endforelse

        </div>

    </div>

    <div class="section">

        <div class="section-header">

            <h2>
                Usuarios
            </h2>

            <a
                href="{{ route(
                    'table-permissions.roles.users.edit',
                    $role
                ) }}"
                class="button"
            >
                Administrar usuarios
            </a>

        </div>

        <div class="table-wrapper">

            <table>

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($role->users as $user)

                        <tr>
                            <td>
                                {{ $user->getKey() }}
                            </td>

                            <td>
                                {{ $user->name ?? 'Sin nombre' }}
                            </td>

                            <td>
                                {{ $user->email ?? 'Sin correo' }}
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="3">
                                No hay usuarios asignados.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    <div class="footer-actions">

        <a
            href="{{ route(
                'table-permissions.roles.index'
            ) }}"
            class="button button-secondary"
        >
            Volver
        </a>

        <a
            href="{{ route(
                'table-permissions.roles.users.edit',
                $role
            ) }}"
            class="button button-secondary"
        >
            Administrar usuarios
        </a>

        <a
            href="{{ route(
                'table-permissions.roles.edit',
                $role
            ) }}"
            class="button"
        >
            Editar permisos
        </a>

    </div>

</div>

</body>

</html>

