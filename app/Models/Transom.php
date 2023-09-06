<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transom extends Model
{
    use HasFactory;

    protected $fillable = [
        'doortype_id',
        'name',
        'dealer_price',
        'retail_price',
        'installation_price',
        'jobs',
    ];

    protected $hidden = [
        'doortype_id',
        'dealer_price', 
        'retail_price',
        'installation_price'
    ];

    public function getData($id)
    {
        $transom = Transom::find($id);
        
        return $transom;
    }
}
