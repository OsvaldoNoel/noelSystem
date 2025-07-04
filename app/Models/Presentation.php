<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Presentation extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'tenant_id'];

    function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    protected static function booted()
    {
        static::addGlobalScope('tenant', function ($builder) {
            $builder->where('tenant_id', Auth::user()->tenant_id);
        });
    }
    
}
