<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['tenant_id', 'categoria_id', 'subcategoria_id', 'marca_id','presentation_id','code','name','stock','stockMin','stockMax','costoPromedio','priceList1','priceList2','priceList3','tipoUtilidad','redondeo','vto','image','status'];


    function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    function subcategoria()
    {
        return $this->belongsTo(Subcategoria::class);
    }

    function marca()
    {
        return $this->belongsTo(Marca::class);
    }

    function presentation()
    {
        return $this->belongsTo(Presentation::class);
    }

    function sales()
    {
        return $this->hasMany(SaleItem::class);
    }



    protected static function booted()
    {
        static::addGlobalScope('tenant', function ($builder) {
            $builder->where('products.tenant_id', Auth::user()->tenant_id);
        });
    }
}
