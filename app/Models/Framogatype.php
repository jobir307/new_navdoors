<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Framogatype extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
    ];

    public function getData($id)
    {
        $framogatype = Framogatype::find($id);
        
        return $framogatype;
    }
}
