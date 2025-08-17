# Documentación del Sistema de Roles y Permisos Multitenant

## Estructura General

El sistema utiliza un enfoque multitenant con roles y permisos implementados mediante el paquete Spatie Permission, adaptado para funcionar en un entorno donde cada tenant (cliente) tiene sus propios roles y asignaciones de usuarios, pero comparte el mismo conjunto global de permisos.

## Componentes Clave

### 1. Migración de Roles y Permisos

La estructura de base de datos está diseñada para:
- **Permisos globales**: Compartidos por todos los tenants
- **Roles específicos por tenant**: Cada tenant puede tener sus propios roles
- **Asignaciones con contexto de tenant**: Relaciones usuario-rol y usuario-permiso incluyen tenant_id

```php
Schema::create($tableNames['roles'], function (Blueprint $table) {
    $table->id();
    $table->string('name');       // Ej: 'Propietario'
    $table->string('guard_name'); // Normalmente 'web'
    $table->unsignedBigInteger('tenant_id')->nullable()->comment('Null para landlord');
    $table->timestamps();

    $table->unique(['name', 'tenant_id']);
});
```

### 2. Modelo User

Extiende la funcionalidad básica de Spatie con:
- Distinción entre usuarios landlord (sistema) y tenant
- Caché de permisos optimizada para multitenant
- Métodos helper para gestión de roles/permisos en contexto de tenant

```php
// Ejemplo de método para verificar permisos
public function hasPermissionTo($permission, $tenantId = null): bool
{
    if ($this->isLandlord()) {
        return true;
    }

    $tenantId = $tenantId ?? $this->tenant_id;

    // Verificación directa y a través de roles
    return $this->permissions()->wherePivot('tenant_id', $tenantId)->exists() || 
           $this->roles()->wherePivot('tenant_id', $tenantId)->whereHas('permissions')->exists();
}
```

## Flujos de Trabajo Principales

### 1. Creación y Gestión de Roles

**Componente:** `RoleManager`

- **Roles predefinidos**: 'Propietario', 'Admin', 'Ventas', 'Compras', 'Caja'
- **Roles personalizados**: Los tenants pueden crear sus propios roles
- **Asignación de permisos**: Solo para roles personalizados (los predefinidos tienen permisos fijos)

**Reglas clave:**
- Solo el landlord puede crear roles globales
- Los tenants solo pueden modificar sus propios roles
- No se pueden editar los permisos de roles predefinidos

### 2. Asignación de Roles a Usuarios

**Componente:** `UserRoleAssignment`

- **Jerarquía de roles**:
  - Propietario: Acceso completo al tenant, puede asignar cualquier rol
  - Admin: Acceso casi completo, pero no puede asignar el rol Admin
  - Otros roles: Según permisos asignados

**Restricciones:**
- No se puede modificar el rol Propietario
- Un Admin no puede modificar a otro Admin (solo el Propietario puede)
- Los usuarios solo pueden ser asignados a roles de su mismo tenant

### 3. Gestión de Usuarios

**Componente:** `UserListController`

- **Creación de usuarios**: Asigna automáticamente rol 'Ventas' por defecto
- **Edición de roles**: Solo usuarios con permiso 'gestionar-usuarios'
- **Protección especial**: El usuario Propietario no puede ser eliminado

## Buenas Prácticas para Mantenimiento

### 1. Adición de Nuevos Permisos

1. **Modificar el seeder** `S02_RolesAndPermissionsSeeder`:
```php
private const MODULE_NUEVO = 'nuevo_modulo';

// En el método createPermissions()
self::MODULE_NUEVO => ['ver', 'crear', 'editar', 'eliminar']
```

2. **Ejecutar el seeder** (solo en desarrollo):
```bash
php artisan db:seed --class=S02_RolesAndPermissionsSeeder
```

3. **Para producción**, crear una migración específica:
```php
public function up()
{
    $permissions = [
        'ver-nuevo_modulo',
        'crear-nuevo_modulo',
        // ...
    ];

    foreach ($permissions as $permission) {
        Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
    }
}
```

### 2. Protección de Rutas

Usar el middleware `CheckTenantPermissions`:
```php
Route::get('/ruta-protegida', [Controller::class, 'method'])
    ->middleware('permission:ver-modulo,editar-modulo');
```

### 3. Verificación en Vistas

```blade
@can('ver-modulo')
    <!-- Contenido protegido -->
@endcan

@role('Admin')
    <!-- Solo para Admins -->
@endrole
```

## Recomendaciones para Uso

1. **Para funcionalidades críticas**, verificar permisos directamente:
```php
if (!$user->hasPermissionTo('gestionar-caja', $tenantId)) {
    abort(403);
}
```

2. **Para roles predefinidos**, se puede verificar por nombre de rol:
```php
if ($user->hasRole('Propietario', $tenantId)) {
    // Acciones exclusivas del propietario
}
```

3. **Cache de permisos**: El sistema implementa caché automática, pero se puede limpiar manualmente cuando sea necesario:
```php
$user->forgetCachedPermissions();
```

## Documentación para Nuevos Desarrolladores

### Estructura de Permisos

Los permisos siguen el formato: `accion-modulo`

Ejemplos:
- `ver-usuarios`
- `crear-ventas`
- `eliminar-compras`

### Jerarquía de Accesos

1. **Landlord**: Acceso completo a todo el sistema
2. **Propietario (tenant)**: Acceso completo a su tenant
3. **Admin (tenant)**: Acceso casi completo, con algunas restricciones
4. **Otros roles**: Según permisos asignados

### Políticas de Acceso

- **UserPolicy**: Controla acceso a gestión de usuarios
- **RolePolicy**: Controla creación/eliminación de roles

## Consideraciones de Seguridad

1. **Nunca confiar solo en la verificación de roles** en el frontend - siempre validar en el backend
2. **El landlord debe tener cuidado** al asignar roles globales
3. **Auditar periódicamente** las asignaciones de permisos

## Ejemplo de Flujo Completo

1. **Crear un nuevo módulo**:
   - Añadir permisos en el seeder
   - Crear políticas si es necesario
   - Proteger rutas con middleware
   - Implementar verificaciones en vistas

2. **Asignar permisos**:
   - Crear rol personalizado en `RoleManager`
   - Asignar permisos específicos
   - Asignar el rol a usuarios en `UserRoleAssignment`

## Depuración

Para problemas con permisos:
1. Verificar caché: `php artisan cache:clear`
2. Verificar asignaciones directas en `model_has_permissions`
3. Verificar asignaciones por rol en `role_has_permissions`

Esta documentación proporciona una base sólida para el mantenimiento y expansión del sistema de roles y permisos. Se recomienda actualizarla cada vez que se realicen cambios significativos en la estructura de permisos.