<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentHistory extends Model
{
    use HasFactory;

    protected $fillable = ['tenant_id', 'amount', 'currency_code', 'payment_date', 'payment_method', 'transaction_id', 'notes'];
}
