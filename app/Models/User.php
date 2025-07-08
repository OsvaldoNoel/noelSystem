<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

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