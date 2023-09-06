<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'inout_type',
        'payment_type',
        'amount',
        'in_words',
        'day'
    ];

    protected $hidden = [
        'invoice_id',
        'amount',
        'in_words',
        'day'
    ];

}
