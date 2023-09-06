<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doortype extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'retail_price',
        'dealer_price',
        'jobs',
        'installation_price',
        'layer15_koeffitsient',
    ];

    protected $hidden = ['retail_price', 'dealer_price', 'jobs', 'installation_price'];

    public function getData($id)
    {
        $doortype = Doortype::find($id);

        return $doortype;
    }
}
