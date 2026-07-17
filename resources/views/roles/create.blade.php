<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>Crear rol</title>

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
            margin-bottom: 22px;
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

        .form-actions {
            display: flex;
            gap: 12px;
            align-items: center;
            justify-content: flex-end;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }

        @media (max-width: 640px) {
            .container {
                margin: 24px auto;
            }

            .header {
                align-items: flex-start;
                flex-direction: column;
            }

            .form-wrapper {
                padding: 18px;
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
            <h1>Crear rol</h1>

            <p>
                Registra un nuevo rol para asignarle permisos y usuarios.
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

    @if(session('error'))
        <div class="alert error">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert error">
            <strong>
                No se pudo crear el rol.
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
                'table-permissions.roles.store'
            ) }}"
            method="POST"
        >
            @csrf

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
                    value="{{ old('name') }}"
                    placeholder="Ejemplo: supervisor"
                    class="form-control
                        @error('name')
                            is-invalid
                        @enderror
                    "
                    autocomplete="off"
                    autofocus
                    required
                >

                @error('name')
                    <span class="field-error">
                        {{ $message }}
                    </span>
                @enderror

                <p class="help-text">
                    Utiliza un nombre corto y descriptivo, por ejemplo:
                    administrador, supervisor o vendedor.
                </p>

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
                    Crear rol
                </button>

            </div>

        </form>

    </div>

</div>

</body>

</html>