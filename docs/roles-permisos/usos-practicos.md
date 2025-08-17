# Documentación de Uso: Middlewares, Roles y Permisos

## 1. Middlewares

### Middlewares Disponibles

| Middleware                | Uso Recomendado                          | Descripción |
|---------------------------|------------------------------------------|-------------|
| `cache.permissions`       | Rutas con alta frecuencia                | Cachea verificaciones de permisos en Redis |
| `tenant.permission`       | Operaciones críticas                     | Verificación directa en BD |
| `tenant.role`             | Control de acceso por roles              | Verifica roles del tenant |
| `protect.owner`           | Protección de usuario Propietario        | Bloquea modificación del Propietario |
| `user.type`               | Filtrado por tipo de usuario             | Distingue landlord/tenant |

### Ejemplos en Rutas

```php
// Uso básico de permisos (con cache)
Route::get('/dashboard', [DashboardController::class, 'index'])
     ->middleware('cache.permissions:ver-dashboard');

// Múltiples permisos (OR lógico)
Route::post('/ventas', [VentasController::class, 'store'])
     ->middleware('tenant.permission:crear-ventas,editar-ventas');

// Combinación con roles
Route::get('/config', [ConfigController::class, 'index'])
     ->middleware('tenant.role:Admin,Supervisor');

// Protección especial
Route::delete('/users/{user}', [UserController::class, 'destroy'])
     ->middleware('protect.owner');
```

## 2. Controladores

### Verificación Programática

```php
// En cualquier controlador
public function edit(User $user)
{
    // Verificación directa
    if (!Auth::user()->hasPermissionTo('editar-usuarios', $user->tenant_id)) {
        abort(403);
    }

    // Alternativa con helper
    $this->authorize('update', $user);

    // Usando cache
    if (!Auth::user()->hasCachedRole('Admin')) {
        return back()->with('error', 'Acceso denegado');
    }
}
```

### Uso con Policies

```php
// En App\Policies\UserPolicy.php
public function update(User $user, User $model)
{
    return $user->hasCachedRole('Admin') || 
           ($user->tenant_id === $model->tenant_id && 
            $user->hasPermissionTo('editar-usuarios'));
}

// En controlador
public function update(Request $request, User $user)
{
    $this->authorize('update', $user);
    // ... lógica de actualización
}
```

## 3. Vistas (Blade)

### Directivas Básicas

```blade
{{-- Verificación simple --}}
@can('ver-reportes')
    <a href="/reportes">Ver Reportes</a>
@endcan

{{-- Con cache --}}
@if(Auth::user()->hasCachedPermission('gestionar-configuracion'))
    @include('components.config-panel')
@endif

{{-- Combinación de roles y permisos --}}
@role('Admin')
    @can('gestionar-usuarios')
        <button>Administrar Usuarios</button>
    @endcan
@endrole
```

### Componentes Dinámicos

```blade
@php
    $userPermissions = Auth::user()->getCachedPermissions();
@endphp

@foreach($modules as $module)
    @if($userPermissions->contains('name', 'ver-'.$module))
        <x-module-card :module="$module" />
    @endif
@endforeach
```

## 4. Livewire Components

### Ejemplo en Componentes

```php
// En tu componente Livewire
public function render()
{
    return view('livewire.some-component', [
        'showAdminSection' => Auth::user()->hasCachedPermission('acceso-admin'),
        'roles' => Auth::user()->getCachedRoles()
    ]);
}
```

```blade
<!-- Vista del componente -->
@if($showAdminSection)
    <livewire:admin-panel />
@endif
```

## 5. Casos Especiales

### Invalidación Manual de Cache

```php
// Cuando ocurren cambios críticos
Auth::user()->forgetCachedPermissions();
Cache::tags(['user_'.auth()->id()])->flush();
```

### Grupos de Middleware

```php
// routes/web.php
Route::middleware(['cache.permissions:ver-inventario', 'tenant.role:Inventario'])->group(function () {
    Route::get('/inventario', [InventoryController::class, 'index']);
    Route::get('/inventario/reportes', [InventoryController::class, 'reports']);
});
```

## Tabla Resumen

| Contexto       | Método Recomendado                     | Ejemplo |
|----------------|----------------------------------------|---------|
| Rutas          | Middleware directo                     | `->middleware('cache.permissions:ver-dashboard')` |
| Controladores  | Policies + `authorize()`               | `$this->authorize('update', $user)` |
| Vistas         | Directivas `@can`/`@role`              | `@can('editar-productos')` |
| Livewire       | Cached methods en mount/render         | `Auth::user()->getCachedRoles()` |
| API            | Middleware + cache                     | `'cache.permissions:api-access'` |

## Buenas Prácticas

1. **Prefiere cache en**:
   - Vistas públicas/compartidas
   - Datos que cambian poco
   - Operaciones con muchas verificaciones

2. **Evita cache en**:
   - Operaciones de escritura
   - Datos sensibles/transaccionales
   - Donde la precisión es crítica

3. **Nomenclatura consistente**:
   ```php
   // Bien
   'gestionar-usuarios'
   'ver-reportes'
   
   // Evitar
   'user_management'
   'see_reports'
   ```

Esta documentación cubre los escenarios más comunes. Para casos específicos, adapta estos patrones manteniendo la consistencia en tu implementación.