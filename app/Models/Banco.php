<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banco extends Model
{
    use HasFactory;

    protected $fillable = ['tenant_id','entidad_id','titular','cuenta','tipoCta','moneda','sobregiro','oficial','phone','status'];

    function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    function entidad()
    {
        return $this->belongsTo(Entidad::class);
    }

    protected static function booted()
    {
        static::addGlobalScope('tenant', function ($builder) {
            $builder->where('bancos.tenant_id', Auth::user()->tenant_id);
        });
    }
}
