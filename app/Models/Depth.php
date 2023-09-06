<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Depth extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
    ];

    protected $hidden = ['price'];

    public function getData($id)
    {
        $depth = Depth::find($id);
     
        return $depth;
    }
}
