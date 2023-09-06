<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Layer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
    ];

    protected $hidden = ['price'];

    public function getData($id)
    {
        $layer = Layer::find($id);
        
        return $layer;
    }
}
