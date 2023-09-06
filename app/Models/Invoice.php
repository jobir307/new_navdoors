<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $fillable = [
        'payer',
        'responsible',
        'amount',
        'day',
        'reason',
        'order_id',
        'parameters',
        'status'
    ];

    protected $hidden = [
        'payer',
        'responsible',
        'amount',
        'order_id',
        'parameters'
    ];
}
