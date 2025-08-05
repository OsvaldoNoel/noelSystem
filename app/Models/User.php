<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Traits\HasRoles;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Contracts\Role as RoleContract;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'tenant_id',
        'user_profile_id',
        'sucursal',
        'username',
        'email',
        'password',
        'status'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relaciones
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function profile()
    {
        return $this->belongsTo(UserProfile::class, 'user_profile_id');
    }

    public function sales()
    {
        return $this->hasMany(Sale::class, 'tenant_id');
    }

    // Agregar esta relación para optimizar las consultas de permisos
    public function cachedPermissions()
    {
        return $this->getAllPermissions();
    }

    // Scopes útiles
    public function scopeForTenant($query, $tenantId = null)
    {
        return $query->where('tenant_id', $tenantId ?? $this->tenant_id);
    }

    public function scopeLandlords($query)
    {
        return $query->whereNull('tenant_id');
    }

    public function scopeTenants($query)
    {
        return $query->whereNotNull('tenant_id');
    }

    // Helpers
    public function isLandlord(): bool
    {
        return is_null($this->tenant_id);
    }

    public function isTenant(): bool
    {
        return !is_null($this->tenant_id);
    }

    /**
     * Sobreescribir el método getStoredRole para manejar correctamente la búsqueda de roles
     * considerando el tenant_id
     */
    protected function getStoredRole($role): RoleContract
    {
        if (is_numeric($role)) {
            return Role::where('id', $role)
                ->when($this->isTenant(), function ($query) {
                    $query->where('tenant_id', $this->tenant_id);
                })
                ->firstOrFail();
        }

        if (is_string($role)) {
            return Role::where('name', $role)
                ->when($this->isTenant(), function ($query) {
                    $query->where('tenant_id', $this->tenant_id);
                })
                ->firstOrFail();
        }

        if ($role instanceof RoleContract) {
            return $role;
        }

        throw new \InvalidArgumentException('Invalid role value');
    }

    /**
     * Sobreescribir syncRoles para manejar el tenant_id
     */
    public function syncRoles($roles, $tenantId = null)
    {
        if ($this->isTenant() && is_null($tenantId)) {
            $tenantId = $this->tenant_id;
        }

        $this->roles()->detach();

        foreach ($roles as $role) {
            $this->assignRole($role, $tenantId);
        }

        return $this;
    }

    // Sobreescribir el método para filtrar roles por tenant
    public function assignRole($role, $tenantId = null)
    {
        if ($this->isTenant() && is_null($tenantId)) {
            $tenantId = $this->tenant_id;
        }

        $role = $this->getStoredRole($role);
        $this->roles()->attach($role->id, ['tenant_id' => $tenantId]);

        $this->forgetCachedPermissions();

        return $this;
    }

    // Obtener roles considerando el tenant
    public function getRoles()
    {
        if ($this->isLandlord()) {
            return $this->roles;
        }

        return $this->roles()->wherePivot('tenant_id', $this->tenant_id)->get();
    }

    // Verificar permisos considerando el tenant 
    public function hasPermissionTo($permission, $tenantId = null): bool
    {
        if ($this->isLandlord()) {
            return true;
        }

        $tenantId = $tenantId ?? $this->tenant_id;

        // Primero verificar permisos directos
        if ($this->permissions()
            ->wherePivot('tenant_id', $tenantId)
            ->where('name', $permission)
            ->exists()
        ) {
            return true;
        }

        // Luego verificar permisos a través de roles
        return $this->roles()
            ->wherePivot('tenant_id', $tenantId)
            ->whereHas('permissions', function ($query) use ($permission) {
                $query->where('name', $permission);
            })
            ->exists();
    }

    // Agrega caché para los permisos en el modelo User
    protected $with = ['roles', 'permissions'];

    /**
     * Obtiene todos los permisos del usuario (directos y a través de roles)
     * con cache para mejor rendimiento
     */
    // En User.php
    public function getAllPermissions()
    {
        $cacheKey = "user.{$this->id}.permissions.tenant.{$this->tenant_id}";

        return cache()->remember($cacheKey, 3600, function () {
            if ($this->isLandlord()) {
                return Permission::all();
            }

            // Permisos directos del usuario para este tenant
            $directPermissions = $this->permissions()
                ->wherePivot('tenant_id', $this->tenant_id)
                ->get();

            // Permisos a través de roles para este tenant
            $rolePermissions = DB::table('permissions')
                ->join('role_has_permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                ->join('model_has_roles', function ($join) {
                    $join->on('role_has_permissions.role_id', '=', 'model_has_roles.role_id')
                        ->where('model_has_roles.model_id', $this->id)
                        ->where('model_has_roles.model_type', User::class)
                        ->where('model_has_roles.tenant_id', $this->tenant_id);
                })
                ->select('permissions.*')
                ->distinct()
                ->get()
                ->map(function ($item) {
                    return Permission::hydrate([(array)$item])->first();
                });

            return $directPermissions->merge($rolePermissions)->unique('id');
        });
    }

    /**
     * Obtiene los permisos sin usar cache
     */
    public function getFreshPermissions()
    {
        if ($this->isLandlord()) {
            return Permission::all();
        }

        $permissions = $this->permissions()->get();

        foreach ($this->roles()->get() as $role) {
            $permissions = $permissions->merge($role->permissions()->get());
        }

        return $permissions->unique('id');
    }

    /**
     * Obtiene solo los nombres de los permisos
     */
    public function getPermissionNames()
    {
        return $this->getAllPermissions()->pluck('name')->toArray();
    }

    public function getGroupedPermissions()
    {
        $permissions = $this->getAllPermissions();

        // Agrupar por módulo y mantener el orden del seeder
        $moduleOrder = [
            'Usuarios' => [],
            'Roles' => [],
            'Ventas' => [],
            'Compras' => [],
            'Caja' => [],
            'Reportes' => [],
            'Configuración' => []
        ];

        foreach ($permissions as $permission) {
            $module = $this->getModuleName($permission->name);
            if (!isset($moduleOrder[$module])) {
                $moduleOrder[$module] = [];
            }
            $moduleOrder[$module][] = $permission;
        }

        // Eliminar módulos vacíos y mantener el orden
        return collect($moduleOrder)->filter()->reject(function ($permissions) {
            return empty($permissions);
        });
    }

    protected function getModuleName($permissionName)
    {
        $module = explode('-', $permissionName)[1] ?? 'otros';

        return match ($module) {
            'usuarios' => 'Usuarios',
            'roles' => 'Roles',
            'ventas' => 'Ventas',
            'compras' => 'Compras',
            'caja' => 'Caja',
            'reportes' => 'Reportes',
            'configuracion' => 'Configuración',
            default => ucfirst($module)
        };
    }

    public static function booted()
    {
        static::updated(function ($user) {
            cache()->forget("user.{$user->id}.permissions");
        });

        static::deleted(function ($user) {
            cache()->forget("user.{$user->id}.permissions");
        });
    }
}
