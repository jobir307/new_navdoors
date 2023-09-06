<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
    use HasFactory;
    protected $fillable = [
        'fullname',
        'address',
        'phone_number',
        'order_number',
        'path',
        'active'
    ];

    protected $hidden = [
        'address',
        'phone_number',
        'order_number',
        'path',
    ];
}
