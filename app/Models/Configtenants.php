<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Configtenants extends Model
{
    use HasFactory;
    protected $fillable = ['bajaLista1', 'bajaLista2', 'bajaLista3', 'mediaLista1', 'mediaLista2', 'mediaLista3', 'altaLista1', 'altaLista2', 'altaLista3'];

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
