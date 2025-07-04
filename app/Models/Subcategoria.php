<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Subcategoria extends Model
{
    use HasFactory;
    protected $fillable = ['tenant_id', 'categoria_id', 'name', 'status'];

    public function categorias()
    {
        return $this->belongsTo(Categoria::class);
    }

    function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    protected static function booted()
    {
        static::addGlobalScope('tenant', function ($builder) {
            $builder->where('subcategorias.tenant_id', Auth::user()->tenant_id);
        });
    }

}
