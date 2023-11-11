<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Crown extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'len',
        'retail_price',
        'dealer_price',
        'jobs',
    ];

    protected $hidden = [
        'retail_price',
        'dealer_price'
    ];

    public function getData($id){
        $crown = Crown::find($id);

        return $crown;
    }
}
