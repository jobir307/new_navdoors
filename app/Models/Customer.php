<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $fillable = [
        'region_id',
        'district_id',
        'mahalla_id',
        'street_id',
        'home',
        'name',
        'type',
        'address',
        'phone_number',
        'inn'
    ];

    protected $hidden = [
        'region_id',
        'district_id',
        'mahalla_id',
        'street_id',
        'home',
        'name',
        'type',
        'address',
        'phone_number',
        'inn'
    ];

    public function getData($name)
    {
        $customer = Customer::where('name', $name)->first();

        return $customer; 
    }
}
