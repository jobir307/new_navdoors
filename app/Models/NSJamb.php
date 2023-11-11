<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NSJamb extends Model
{
    use HasFactory;

    protected $fillable = [
        'jambname_id',
        'jambname',
        'dealer_price',
        'retail_price',
        'jobs',
    ];

    protected $hidden = [
        'dealer_price',
        'retail_price',
        'jobs',
    ];

    public function getData($id)
    {
        $nsjamb = NSJamb::find($id);
        
        return $nsjamb;
    }
}
