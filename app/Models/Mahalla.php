<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahalla extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'fullname',
        'district_id'
    ];
    protected $hidden = [
        'district_id',
    ];
}
