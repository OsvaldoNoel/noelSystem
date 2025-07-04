<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Aportante extends Model
{
    use HasFactory;

    protected $fillable = ['tenant_id','name','aporte','status'];

    function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    protected static function booted()
    {
        static::addGlobalScope('tenant', function ($builder) {
            $builder->where('aportantes.tenant_id', Auth::user()->tenant_id);
        });
    }
}
