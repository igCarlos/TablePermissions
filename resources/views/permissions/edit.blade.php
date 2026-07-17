<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Permisos del rol {{ ucfirst($role->name) }}
    </title>

    <style>

        *{
            box-sizing:border-box;
        }

        body{
            margin:0;
            font-family:Arial,Helvetica,sans-serif;
            background:#f3f4f6;
            color:#111827;
        }

        .container{
            width:min(1200px,95%);
            margin:40px auto;
        }

        .header{
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-bottom:25px;
        }

        h1{
            margin:0;
        }

        .button{
            display:inline-block;
            padding:10px 18px;
            border:none;
            border-radius:8px;
            cursor:pointer;
            text-decoration:none;
            color:white;
            background:#2563eb;
            font-weight:bold;
        }

        .button:hover{
            background:#1d4ed8;
        }

        .button-success{
            background:#16a34a;
        }

        .button-success:hover{
            background:#15803d;
        }

        .button-secondary{
            background:#6b7280;
        }

        .button-secondary:hover{
            background:#4b5563;
        }

        .card{
            background:white;
            border-radius:12px;
            padding:25px;
            box-shadow:0 3px 10px rgba(0,0,0,.08);
        }

        .permission-group{

            border:1px solid #e5e7eb;
            border-radius:10px;

            margin-bottom:20px;
            padding:20px;

        }

        .permission-header{

            display:flex;
            justify-content:space-between;
            align-items:center;

            margin-bottom:20px;

        }

        .permission-header h2{

            margin:0;
            text-transform:capitalize;

        }

        .permissions{

            display:grid;
            grid-template-columns:
                repeat(auto-fill,minmax(180px,1fr));

            gap:12px;

        }

        .permission{

            display:flex;
            align-items:center;
            gap:10px;

            background:#f9fafb;
            border-radius:8px;

            padding:12px;

        }

        .footer{

            margin-top:25px;

            display:flex;
            justify-content:flex-end;
            gap:10px;

        }

        .alert{

            background:#dcfce7;
            color:#166534;

            padding:12px;

            border-radius:8px;

            margin-bottom:20px;

        }

    </style>

</head>

<body>

<div class="container">

    <div class="header">

        <div>

            <h1>
                Editar permisos
            </h1>

            <p>

                Rol:

                <strong>

                    {{ ucfirst($role->name) }}

                </strong>

            </p>

        </div>
        @can('permissions.view')    
            <a

                href="{{ route('table-permissions.permissions.index') }}"

                class="button button-secondary"

            >

                Volver

            </a>
        @endcan

    </div>


    @if(session('success'))

        <div class="alert">

            {{ session('success') }}

        </div>

    @endif


    <div class="card">

        <form

            action="{{ route('table-permissions.permissions.roles.update',$role) }}"

            method="POST"

        >

            @csrf

            @method('PUT')


            @foreach($permissionGroups as $module=>$permissions)

                <div class="permission-group">

                    <div class="permission-header">

                        <h2>

                            {{ ucfirst(str_replace('_',' ',$module)) }}

                        </h2>

                        <button

                            type="button"

                            class="button button-secondary"

                            onclick="toggleModule('{{ $module }}')"

                        >

                            Marcar / Desmarcar

                        </button>

                    </div>


                    <div class="permissions">

                        @foreach($permissions as $permission)

                            @php

                                $action =

                                    str($permission->name)
                                        ->after('.');

                            @endphp

                            <label class="permission">

                                <input

                                    class="module-{{ $module }}"

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

                                    {{ ucfirst($action) }}

                                </span>

                            </label>

                        @endforeach

                    </div>

                </div>

            @endforeach


            <div class="footer">

                <a

                    href="{{ route('table-permissions.permissions.index') }}"

                    class="button button-secondary"

                >

                    Cancelar

                </a>

                <button

                    type="submit"

                    class="button button-success"

                >

                    Guardar permisos

                </button>

            </div>

        </form>

    </div>

</div>

<script>

function toggleModule(module){

    let items =
        document.querySelectorAll(
            '.module-'+module
        );

    let allChecked = true;

    items.forEach(item=>{

        if(!item.checked){

            allChecked=false;

        }

    });

    items.forEach(item=>{

        item.checked=!allChecked;

    });

}

</script>

</body>

</html>