<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $fillable = [
        'ci',
        'name',
        'lastname',
        'phone',
        'address',
        'barrio',
        'city'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
