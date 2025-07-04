<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = ['tenant_id', 'total', 'items'];


    function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    function details()
    {
        return $this->hasMany(SaleItem::class);
    }

    protected static function booted()
    {
        static::addGlobalScope('tenant', function ($builder) {
            $builder->where('sales.tenant_id', Auth::user()->tenant_id);
        });
    }
}
