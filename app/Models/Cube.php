<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Cube extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'retail_price',
        'dealer_price',
        'jobs',
    ];

    protected $hidden = [
        'retail_price',
        'dealer_price',
    ];

    public function getData($name){
        $cutted_name = strtok($name, '(');
        // dd($cutted_name);
        $cube = Cube::where('name', 'LIKE', "%{$cutted_name}%")->first();

        return $cube;
    }

    public function getDataByID($id){        
        $cube = Cube::find($id);

        return $cube;
    }

    public function getDataByCrown($crown_id)
    {
        $cube_id = DB::table('ccbjs')->where('crown_id', $crown_id)->first()->cube_id;

        $cube = Cube::find($cube_id);

        return $cube;
    }
}
