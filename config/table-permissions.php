<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Método de detección
    |--------------------------------------------------------------------------
    |
    | models: detecta modelos Eloquent dentro de app/Models.
    | tables: detecta directamente las tablas de la base de datos.
    |
    */

    'discovery' => 'models',

    /*
    |--------------------------------------------------------------------------
    | Ubicación de los modelos
    |--------------------------------------------------------------------------
    */

    'models_path' => app_path('Models'),

    'models_namespace' => 'App\\Models',

    /*
    |--------------------------------------------------------------------------
    | Modelos excluidos
    |--------------------------------------------------------------------------
    |
    | Puedes agregar modelos que no deban generar permisos.
    |
    */

    'excluded_models' => [
        // App\Models\User::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Guard de autenticación
    |--------------------------------------------------------------------------
    */

    'guard' => 'web',

    /*
    |--------------------------------------------------------------------------
    | Rol administrador
    |--------------------------------------------------------------------------
    */

    'administrator_role' => 'administrador',

    /*
    |--------------------------------------------------------------------------
    | Acciones generadas
    |--------------------------------------------------------------------------
    */

    'actions' => [
        'view',
        'create',
        'update',
        'delete',
    ],

    /*
    |--------------------------------------------------------------------------
    | Tablas excluidas
    |--------------------------------------------------------------------------
    |
    | Solo se utilizan cuando:
    |
    | 'discovery' => 'tables'
    |
    */

    'excluded_tables' => [
        'migrations',
        'cache',
        'cache_locks',
        'jobs',
        'job_batches',
        'failed_jobs',
        'sessions',
        'password_reset_tokens',
        'personal_access_tokens',

        
        'model_has_roles',
        'model_has_permissions',
        'role_has_permissions',
    ],

    /*
    |--------------------------------------------------------------------------
    | Prefijos excluidos
    |--------------------------------------------------------------------------
    |
    | Solo se utilizan cuando:
    |
    | 'discovery' => 'tables'
    |
    */

    'excluded_prefixes' => [
        'pma_',
        'telescope_',
        'horizon_',
        'pulse_',
        'personal_access_',
        'cache_',
        'job_',
    ],

    /*
    |--------------------------------------------------------------------------
    | Panel administrativo
    |--------------------------------------------------------------------------
    */

    'routes' => [
        'enabled' => true,

        'prefix' => 'administracion',

        'middleware' => [
            'web',
            'auth',
        ],
    ],

];