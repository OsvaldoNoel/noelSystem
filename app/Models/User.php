<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Contracts\Role as RoleContract;

/**
 * Modelo User - Maneja todos los usuarios del sistema (landlord y tenants)
 *  
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    // Tipos de usuario
    public const TYPE_LANDLORD = 'landlord';
    public const TYPE_TENANT = 'tenant';

    // Roles predefinidos
    public const ROLE_OWNER = 'Propietario';
    public const ROLE_ADMIN = 'Admin';
    public const ROLE_SALES = 'Ventas';
    public const ROLE_PURCHASES = 'Compras';
    public const ROLE_CASHIER = 'Caja';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tenant_id',
        'user_profile_id',
        'sucursal',
        'username',
        'email',
        'password',
        'status',
        'password_changed_at' // Agregar este campo
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'password_changed_at' => 'datetime' // Agregar este cast
    ];

    /**
     * Relaciones que se cargarán por defecto 
     */
    protected $with = ['profile', 'tenant', 'roles', 'permissions'];

    /* RELACIONES */

    /**
     * Obtiene el tenant asociado al usuario
     * 
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Obtiene el perfil de usuario asociado
     * 
     */
    public function profile()
    {
        return $this->belongsTo(UserProfile::class, 'user_profile_id');
    }

    /* SCOPES */

    /**
     * Filtra usuarios por tenant
     * 
     */
    public function scopeForTenant($query, $tenantId = null)
    {
        return $query->where('tenant_id', $tenantId ?? $this->tenant_id);
    }

    /**
     * Filtra solo usuarios landlord
     * 
     */
    public function scopeLandlords($query)
    {
        return $query->whereNull('tenant_id');
    }

    /**
     * Filtra solo usuarios tenant
     * 
     */
    public function scopeTenants($query)
    {
        return $query->whereNotNull('tenant_id');
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

    /* HELPERS */

    /**
     * Determina si el usuario es landlord 
     */
    public function isLandlord(): bool
    {
        return is_null($this->tenant_id);
    }

    /**
     * Determina si el usuario es tenant 
     */
    public function isTenant(): bool
    {
        return !is_null($this->tenant_id);
    }

    // Método para verificar si necesita cambiar la contraseña
    public function needsPasswordChange(): bool
    {
        return is_null($this->password_changed_at);
    }

    //Manejar imagen del usuario
    public function updateProfilePhoto($photo)
    {
        // Eliminar foto anterior si existe
        if ($this->profile_photo_path && Storage::disk('public')->exists($this->profile_photo_path)) {
            Storage::disk('public')->delete($this->profile_photo_path);
        }

        // Guardar nueva foto
        $path = $photo->store('profile-photos', 'public');

        $this->forceFill([
            'profile_photo_path' => $path,
        ])->save();
    }

    public function getProfilePhotoUrlAttribute()
    {
        if (!$this->profile_photo_path) {
            return $this->defaultProfilePhotoUrl();
        }

        return asset('storage/' . $this->profile_photo_path);
    }


    protected function defaultProfilePhotoUrl()
    {
        $name = $this->profile ?
            urlencode($this->profile->name . ' ' . $this->profile->lastname) :
            urlencode('Usuario');

        return 'https://ui-avatars.com/api/?name=' . $name . '&color=7F9CF5&background=EBF4FF';
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

    // Sobreescribir el método para limpiar cache de permisos
    public function forgetCachedPermissions()
    {
        $cacheKeys = [
            "user.{$this->id}.permissions",
            "user.{$this->id}.permissions.tenant.{$this->tenant_id}"
        ];

        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }

        // También limpiar por tags si estás usando Redis
        Cache::tags([
            "tenant_{$this->tenant_id}",
            "user_{$this->id}"
        ])->flush();

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

    // En tu modelo User.php

    /**
     * Obtiene todos los permisos del usuario con cache en Redis
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllPermissions()
    {
        $cacheKey = "user:{$this->id}:permissions:tenant:{$this->tenant_id}";
        $tags = ["user_{$this->id}", "tenant_{$this->tenant_id}"];

        return Cache::tags($tags)->remember($cacheKey, now()->addHours(6), function () {
            if ($this->isLandlord()) {
                return Permission::all();
            }

            return Permission::where(function ($query) {
                $query->whereHas('users', function ($q) {
                    $q->where('model_has_permissions.model_id', $this->id)
                        ->where('model_has_permissions.tenant_id', $this->tenant_id);
                })->orWhereHas('roles.users', function ($q) {
                    $q->where('model_has_roles.model_id', $this->id)
                        ->where('model_has_roles.tenant_id', $this->tenant_id);
                });
            })->get()->unique();
        });
    }

    /**
     * Obtiene los roles del usuario con cache en Redis
     * Considerando el contexto multitenant
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCachedRoles()
    {
        $cacheKey = "user:{$this->id}:roles:tenant:{$this->tenant_id}";
        $tags = ["user_{$this->id}", "tenant_{$this->tenant_id}"];

        return Cache::tags($tags)->remember($cacheKey, now()->addHours(4), function () {
            return $this->getRoles(); // Utiliza tu método existente
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
            $user->forgetCachedPermissions();
            $user->clearPermissionCache();
        });

        static::deleted(function ($user) {
            $user->forgetCachedPermissions();
            $user->clearPermissionCache();
        });
    }

    public function clearPermissionCache()
    {
        $tags = ["user_{$this->id}", "tenant_{$this->tenant_id}"];
        Cache::tags($tags)->flush();

        // También limpia cache de relaciones
        Cache::forget("user:{$this->id}:roles:tenant:{$this->tenant_id}");
    }
}
