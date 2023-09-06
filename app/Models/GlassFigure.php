<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GlassFigure extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'path',
    ];

    public function getData($id)
    {
        $glassfigure = GlassFigure::find($id);
        return $glassfigure;
    }
}
