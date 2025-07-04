<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caja extends Model
{
    use HasFactory;

    protected $fillable = ['tenant_id','name','ubi','status','estado'];

    function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    protected static function booted()
    {
        static::addGlobalScope('tenant', function ($builder) {
            $builder->where('cajas.tenant_id', Auth::user()->tenant_id);
        });
    }
}
