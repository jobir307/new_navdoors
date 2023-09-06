<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Framogafigure extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'min_price'
    ];

    protected $hidden = ['price', 'min_price'];

    public function getData($id)
    {
        $framogafigure = Framogafigure::find($id);
        
        return $framogafigure;
    }
}
