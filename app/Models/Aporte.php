<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aporte extends Model
{
    use HasFactory;

    protected $fillable = ['tenant_id','aportante_id','operacion','destino','monto','detail','saldo','date'];

    function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    function aportante()
    {
        return $this->belongsTo(Aportante::class);
    }

    protected static function booted()
    {
        static::addGlobalScope('tenant', function ($builder) {
            $builder->where('aportes.tenant_id', Auth::user()->tenant_id);
        });
    }
}
