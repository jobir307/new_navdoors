<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Locktype extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'retail_price',
        'dealer_price',
    ];

    protected $hidden = [
        'retail_price', 
        'dealer_price'
    ];

    public function getData($id)
    {
        $locktype = Locktype::find($id);
        
        return $locktype;
    }
}
