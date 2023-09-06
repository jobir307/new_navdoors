<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loop extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'dealer_price',
        'retail_price',
    ];

    protected $hidden = [
        'dealer_price', 
        'retail_price'
    ];

    public function getData($id)
    {
        $loop = Loop::find($id);
        return $loop;
    }
}
