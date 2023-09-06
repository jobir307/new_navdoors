<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jamb extends Model
{
    use HasFactory;

    protected $fillable = [
        'doortype_id',
        'name',
        'dealer_price',
        'retail_price',
        'jobs'
    ];

    protected $hidden = [
        'dealer_price', 
        'retail_price'
    ];

    public function getData($id)
    {
        $jamb = Jamb::find($id);
        
        return $jamb;
    }
}
