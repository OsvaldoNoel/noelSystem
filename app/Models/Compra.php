<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Compra extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'proveedor_id',
        'date',
        'numero_factura',
        'timbrado',
        'otrosRecibo',
        'condCompr',
        'tipoCompr',
        'status',
        'stock',
        'items',
        'total',
        'gravada_10',
        'gravada_5',
        'exenta',
        'iva_10',
        'iva_5'
    ];

    protected $casts = [
        'date' => 'date',
        'timbrado' => 'integer',
        'condCompr' => 'integer',
        'tipoCompr' => 'integer',
        'status' => 'integer',
        'stock' => 'integer',
        'items' => 'integer',
        'total' => 'decimal:2',
        'gravada_10' => 'decimal:2',
        'gravada_5' => 'decimal:2',
        'exenta' => 'decimal:2',
        'iva_10' => 'decimal:2',
        'iva_5' => 'decimal:2',
    ];

    // Relaciones
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function items()
    {
        return $this->hasMany(CompraItem::class);
    }

    // Scopes
    public function scopePendientes($query)
    {
        return $query->where('status', 1);
    }

    public function scopeContado($query)
    {
        return $query->where('condCompr', 0);
    }

    public function scopeCredito($query)
    {
        return $query->where('condCompr', 1);
    }

    public function scopePorFactura($query, $factura)
    {
        return $query->where('numero_factura', 'like', "%$factura%");
    }

    public function scopePorRangoFechas($query, $desde, $hasta)
    {
        return $query->whereBetween('date', [$desde, $hasta]);
    }

    protected static function booted()
    {
        static::addGlobalScope('tenant', function ($builder) {
            if (Auth::check()) {
                $builder->where('compras.tenant_id', Auth::user()->tenant_id);
            }
        });
    }
}
