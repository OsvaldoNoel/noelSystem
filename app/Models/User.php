<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Roles del sistema
    const ROLE_TENANT = 1;
    const ROLE_LANDLORD = 2;

    protected $fillable = [
        'tenant_id',
        'role',
        'ci',
        'username',
        'name',
        'lastname',
        'phone',
        'email',
        'address',
        'barrio',
        'city',
        'status',
        'password'
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

    public function sales()
    {
        return $this->hasMany(Sale::class, 'tenant_id');
    }

    // Scopes útiles
    public function scopeForTenant($query, $tenantId = null)
    {
        return $query->where('tenant_id', $tenantId ?? $this->tenant_id);
    }

    public function scopeLandlords($query)
    {
        return $query->whereNull('tenant_id')->where('role', self::ROLE_LANDLORD);
    }

    public function scopeTenants($query)
    {
        return $query->whereNotNull('tenant_id')->where('role', self::ROLE_TENANT);
    }

    // Helpers
    public function isLandlord(): bool
    {
        return $this->role === self::ROLE_LANDLORD && is_null($this->tenant_id);
    }

    public function isTenant(): bool
    {
        return $this->role === self::ROLE_TENANT && !is_null($this->tenant_id);
    }
}


// 2. Uso en Controladores (Ejemplos)
// Para Landlords:
// // Listar todos los landlords
// $landlords = User::landlords()->get();

// // Listar todos los usuarios (con filtro opcional por tenant)
// $users = User::when($request->tenant_id, function($q, $tenantId) {
//     $q->forTenant($tenantId);
// })->get();

// Para Tenants:
// // Listar usuarios del tenant actual
// $users = User::forTenant(auth()->user()->tenant_id)->get();

// // Crear nuevo usuario en el tenant actual
// User::create([
//     'tenant_id' => auth()->user()->tenant_id,
//     'role' => User::ROLE_TENANT,
//     // ... otros campos
// ]);


// 3. Middleware de Tenant (Opcional)
// Si necesitas automatizar el filtrado en ciertas rutas:
// namespace App\Http\Middleware;

// use Closure;

// class ApplyTenantScope
// {
//     public function handle($request, Closure $next)
//     {
//         if (auth()->check() && auth()->user()->isTenant()) {
//             // Aplica automáticamente el scope para usuarios tenant
//             User::addGlobalScope('tenant', function($builder) {
//                 $builder->where('tenant_id', auth()->user()->tenant_id);
//             });
//         }

//         return $next($request);
//     }
// }