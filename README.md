# Laravel Table Permissions

Sistema completo para la administración de **Roles**, **Permisos** y **Usuarios** en Laravel utilizando **Spatie Laravel Permission**.

El paquete genera automáticamente los permisos de las tablas de la base de datos y proporciona una interfaz web para administrar:

* Roles
* Permisos
* Usuarios por rol
* Sincronización automática de permisos

Compatible con **Laravel 12** y **PHP 8.2+**.

---

# Características

* Administración de Roles.
* Administración de Permisos.
* Asignación de usuarios a roles.
* Sincronización automática de permisos.
* Generación automática de permisos desde las tablas de la base de datos.
* Integración con Spatie Laravel Permission.
* Vistas listas para usar.
* Rutas configuradas automáticamente.
* Configuración personalizable.
* Compatible con múltiples guards.

---

# Requisitos

* PHP 8.2 o superior
* Laravel 12
* Composer

---

# Instalación

Instale el paquete mediante Composer.

```bash
composer require carliban/laravel-table-permissions
```

---

# Publicar archivos

Publique los archivos de configuración, vistas y migraciones.

```bash
php artisan vendor:publish --provider="Carliban\TablePermissions\TablePermissionsServiceProvider"
```

También puede publicarlos individualmente.

## Configuración

```bash
php artisan vendor:publish --tag=table-permissions-config
```

## Vistas

```bash
php artisan vendor:publish --tag=table-permissions-views
```

## Migraciones

```bash
php artisan vendor:publish --tag=table-permissions-migrations
```

---

# Ejecutar las migraciones

```bash
php artisan migrate
```

---

## Primero Registra un Usuario
# Instalación automática

El paquete incluye un instalador.

```bash
php artisan table-permissions:install
```

Este comando realiza automáticamente:

* Publica la configuración.
* Publica las vistas.
* Publica las migraciones.
* Ejecuta las migraciones.
* Crea el rol administrador.
* Sincroniza los permisos.
* Genera los permisos automáticamente.

---

# Sincronizar permisos

Si crea nuevas tablas o modelos puede sincronizar nuevamente los permisos.

```bash
php artisan table-permissions:sync
```
---
# Restaurar permisos para rol administrador en caso de perdida
```bash
php artisan table-permissions:restore
```
---

# Configuración

Después de publicar la configuración encontrará el archivo:

```
config/table-permissions.php
```

Ejemplo:

```php
return [

    /*
    |--------------------------------------------------------------------------
    | Guard
    |--------------------------------------------------------------------------
    */

    'guard' => 'web',

    /*
    |--------------------------------------------------------------------------
    | Rol administrador
    |--------------------------------------------------------------------------
    */

    'administrator_role' => 'Administrador',

    /*
    |--------------------------------------------------------------------------
    | Acciones que se crearán automáticamente
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
    | Prefijo de las rutas
    |--------------------------------------------------------------------------
    */

    'route_prefix' => 'administracion/permisos',

];
```

---

# Permisos generados

Para una tabla llamada:

```
products
```

El paquete generará automáticamente:

```
products.view
products.create
products.update
products.delete
```

Lo mismo ocurre con cualquier tabla de la base de datos.

---

# Excluir tablas

Puede excluir tablas para que no generen permisos.

```php
'excluded_tables' => [

    'migrations',

    'failed_jobs',

    'password_reset_tokens',

    'cache',

    'jobs',

];
```

---

# Excluir prefijos

También puede excluir tablas por prefijo.

```php
'excluded_prefixes' => [

    'telescope_',

    'pulse_',

    'cache_',

];
```

---

# Rutas

El paquete registra automáticamente las siguientes rutas.

## Roles

```
GET     administracion/permisos/roles
GET     administracion/permisos/roles/create
POST    administracion/permisos/roles
GET     administracion/permisos/roles/{role}
GET     administracion/permisos/roles/{role}/edit
PUT     administracion/permisos/roles/{role}
DELETE  administracion/permisos/roles/{role}
```

## Permisos

```
GET     administracion/permisos/permisos
POST    administracion/permisos/permisos/sync
GET     administracion/permisos/permisos/{role}/edit
PUT     administracion/permisos/permisos/{role}
```

## Usuarios por rol

```
GET     administracion/permisos/roles/{role}/users
PUT     administracion/permisos/roles/{role}/users
```

---

# Middleware

Las rutas utilizan por defecto:

```php
'web',
'auth',
```

Puede modificarlos desde el archivo de configuración.

---

# Uso

Una vez instalado el paquete acceda a:

```
/administracion/permisos
```

Desde allí podrá:

* Crear roles.
* Editar roles.
* Eliminar roles.
* Asignar permisos.
* Sincronizar permisos.
* Asignar usuarios a un rol.

---

# Integración con Spatie

Puede seguir utilizando todas las funciones de Spatie.

Asignar un rol.

```php
$user->assignRole('Administrador');
```

Asignar un permiso.

```php
$role->givePermissionTo('products.create');
```

Comprobar permisos.

```php
$user->can('products.update');
```

Comprobar roles.

```php
$user->hasRole('Administrador');
```

---

# Capturas de pantalla

Puede agregar imágenes de:

* Listado de roles
* Administración de permisos
* Asignación de usuarios
* Sincronización de permisos

Ejemplo:

```
docs/images/roles.png

docs/images/permissions.png

docs/images/users.png
```

---

# Licencia

MIT License

---

# Autor

**Carlos Iván**

GitHub:

https://github.com/carliban

---

# Contribuciones

Las contribuciones son bienvenidas.

1. Realice un Fork del proyecto.
2. Cree una rama.
3. Realice los cambios.
4. Envíe un Pull Request.

---

# Changelog

## v1.0.2

* Administración de Roles.
* Administración de Permisos.
* Asignación de Usuarios.
* Sincronización automática.
* Generación automática de permisos.
* Integración con Spatie.
* Compatible con Laravel 12.
