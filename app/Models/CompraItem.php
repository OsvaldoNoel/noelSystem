<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; 

class CompraItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'compra_id',
        'product_id',
        'quantity',
        'price_unit',
        'price_unit_discounted',
        'discount_amount',
        'discount_percent',
        'row_total',
        'iva',
        'deleted_at'
    ];

    protected $casts = [
        'price_unit' => 'decimal:2',
        'price_unit_discounted' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'discount_percent' => 'decimal:2',
        'row_total' => 'decimal:2',
        'iva' => 'integer'
    ];

    // Relaciones
    public function compra()
    {
        return $this->belongsTo(Compra::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    protected static function booted()
    {
        static::creating(function ($compraItem) {
            if ($compraItem->row_total <= 0) {
                throw new \Exception("El total del item no puede ser cero");
            }
        });
    }
}
