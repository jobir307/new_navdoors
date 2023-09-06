<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'phone_number',
        'carmodel_id',
        'gov_number',
        'type',
        'active',
    ];

    protected $hidden = [
        'name',
        'phone_number',
        'gov_number'
    ];
}