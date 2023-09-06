<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'door_job',
        'transom_job',
        'jamb_job',
        'door_attributes',
    ];

    protected $hidden = [
        'door_attributes', 'door_job', 'transom_job', 'jamb_job'
    ];    
}
