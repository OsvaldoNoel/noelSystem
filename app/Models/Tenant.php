<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'domain',
        'sucursal',
        'subscription_type',
        'amount',
        'date_bill',
        'status',
        'tenant_type' // Agregar el nuevo campo
    ];

    // Constantes para tipos de tenant
    const TYPE_POS = 1;
    const TYPE_SERVICIOS = 2;
    const TYPE_MICROVENTAS = 3;
    const TYPE_RESTAURANTE = 4;

    public function payments()
    {
        return $this->hasMany(PaymentHistory::class);
    }

    public function branches()
    {
        return $this->hasMany(Tenant::class, 'sucursal');
    }

    public function mainTenant()
    {
        return $this->belongsTo(Tenant::class, 'sucursal');
    }

    public function isMainTenant(): bool
    {
        return is_null($this->sucursal);
    }

    public function isBranch(): bool
    {
        return !is_null($this->sucursal);
    }

    public function getEffectiveTypeAttribute()
    {
        return $this->sucursal 
            ? $this->mainTenant->tenant_type
            : $this->tenant_type;
    }

    // Nuevo método para obtener el tipo como texto
    public function getTypeNameAttribute(): string
    {
        return match ($this->tenant_type) {
            self::TYPE_POS => 'POS',
            self::TYPE_SERVICIOS => 'Servicios',
            self::TYPE_MICROVENTAS => 'MicroVentas',
            self::TYPE_RESTAURANTE => 'Restaurante',
        };
    }
}
