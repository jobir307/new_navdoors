<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Door extends Model
{
    use HasFactory;
    protected $fillable = [
        'doortype_id',
        'door_parameters',
        'jamb_parameters',
        'transom_parameters',
        'glass_parameters',
        'door_color',
        'ornamenttype_id',
    ];

    protected $hidden = [
        'door_parameters',
        'jamb_parameters',
        'transom_parameters',
        'glass_parameters',
    ];
}
