<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'domain', 'sucursal', 'suscription_type', 'amount', 'bill_date', 'status'];

    public function payments()
    {
        return $this->hasMany(PaymentHistory::class);
    }

    public function mainTenant()
    {
        return $this->belongsTo(Tenant::class, 'sucursal');
    }
}
