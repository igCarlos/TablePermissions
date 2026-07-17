<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>
        Editar rol
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

        .error ul {
            margin: 8px 0 0;
            padding-left: 20px;
        }

        .form-wrapper {
            padding: 24px;
            border-radius: 12px;
            background: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .06);
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 700;
        }

        .form-control {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            background: white;
            color: #111827;
            font-size: 15px;
            outline: none;
            transition:
                border-color .2s,
                box-shadow .2s;
        }

        .form-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, .15);
        }

        .form-control.is-invalid {
            border-color: #dc2626;
        }

        .form-control.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(220, 38, 38, .15);
        }

        .field-error {
            display: block;
            margin-top: 7px;
            color: #dc2626;
            font-size: 14px;
        }

        .help-text {
            margin: 7px 0 0;
            color: #6b7280;
            font-size: 14px;
        }

        .permissions-header {
            display: flex;
            gap: 12px;
            align-items: center;
            justify-content: space-between;
            margin: 28px 0 18px;
            padding-top: 24px;
            border-top: 1px solid #e5e7eb;
        }

        .permissions-header h2 {
            margin: 0 0 5px;
            font-size: 22px;
        }

        .permissions-header p {
            margin: 0;
            color: #6b7280;
            font-size: 14px;
        }

        .permission-groups {
            display: grid;
            grid-template-columns: repeat(
                2,
                minmax(0, 1fr)
            );
            gap: 18px;
        }

        .permission-group {
            overflow: hidden;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            background: white;
        }

        .permission-group-header {
            display: flex;
            gap: 12px;
            align-items: center;
            justify-content: space-between;
            padding: 15px 18px;
            border-bottom: 1px solid #e5e7eb;
            background: #f9fafb;
        }

        .permission-group-header h3 {
            margin: 0;
            font-size: 17px;
        }

        .permission-count {
            padding: 4px 9px;
            border-radius: 999px;
            background: #e5e7eb;
            color: #374151;
            font-size: 12px;
            font-weight: 700;
        }

        .permission-options {
            display: grid;
            grid-template-columns: repeat(
                2,
                minmax(0, 1fr)
            );
            gap: 10px;
            padding: 16px;
        }

        .permission-option {
            display: flex;
            gap: 10px;
            align-items: center;
            min-height: 44px;
            padding: 10px 12px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            background: #fff;
            cursor: pointer;
            transition:
                border-color .2s,
                background .2s;
        }

        .permission-option:hover {
            border-color: #93c5fd;
            background: #eff6ff;
        }

        .permission-option input {
            width: 17px;
            height: 17px;
            margin: 0;
            accent-color: #2563eb;
            cursor: pointer;
        }

        .permission-option span {
            color: #374151;
            font-size: 14px;
            font-weight: 700;
        }

        .form-actions {
            display: flex;
            gap: 12px;
            align-items: center;
            justify-content: flex-end;
            margin-top: 28px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }

        @media (max-width: 850px) {
            .permission-groups {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {
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

            .form-wrapper {
                padding: 18px;
            }

            .permissions-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .permission-options {
                grid-template-columns: 1fr;
            }

            .form-actions {
                align-items: stretch;
                flex-direction: column-reverse;
            }

            .form-actions .button {
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
                Editar rol: {{ ucfirst($role->name) }}
            </h1>

            <p>
                Modifica el nombre del rol y administra sus permisos.
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
                Ver rol
            </a>

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

    <div class="form-wrapper">

        <form
            action="{{ route(
                'table-permissions.roles.update',
                $role
            ) }}"
            method="POST"
        >
            @csrf
            @method('PUT')

            <div class="form-group">

                <label
                    for="name"
                    class="form-label"
                >
                    Nombre del rol
                </label>

                <input
                    id="name"
                    name="name"
                    type="text"
                    value="{{ old('name', $role->name) }}"
                    class="form-control
                        @error('name')
                            is-invalid
                        @enderror
                    "
                    autocomplete="off"
                    required
                >

                @error('name')
                    <span class="field-error">
                        {{ $message }}
                    </span>
                @enderror

                <p class="help-text">
                    Utiliza un nombre corto y descriptivo para identificar
                    fácilmente este rol.
                </p>

            </div>

            <div class="permissions-header">

                <div>
                    <h2>
                        Permisos del rol
                    </h2>

                    <p>
                        Selecciona las acciones que podrá realizar este rol.
                    </p>
                </div>

            </div>

            <div class="permission-groups">

                @foreach(
                    $permissionGroups as $module => $permissions
                )

                    <section class="permission-group">

                        <div class="permission-group-header">

                            <h3>
                                {{ ucfirst($module) }}
                            </h3>

                            <span class="permission-count">
                                {{ $permissions->count() }}
                            </span>

                        </div>

                        <div class="permission-options">

                            @foreach($permissions as $permission)

                                <label class="permission-option">

                                    <input
                                        type="checkbox"
                                        name="permissions[]"
                                        value="{{ $permission->id }}"

                                        @checked(
                                            $role->permissions->contains(
                                                'id',
                                                $permission->id
                                            )
                                        )
                                    >

                                    <span>
                                        {{
                                            ucfirst(
                                                str($permission->name)
                                                    ->after('.')
                                            )
                                        }}
                                    </span>

                                </label>

                            @endforeach

                        </div>

                    </section>

                @endforeach

            </div>

            <div class="form-actions">

                <a
                    href="{{ route(
                        'table-permissions.roles.index'
                    ) }}"
                    class="button button-secondary"
                >
                    Cancelar
                </a>

                <button
                    type="submit"
                    class="button"
                >
                    Guardar cambios
                </button>

            </div>

        </form>

    </div>

</div>

</body>

</html>
