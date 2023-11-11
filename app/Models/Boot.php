<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Boot extends Model
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
        
        $boot = Boot::where('name', 'LIKE', "%{$cutted_name}%")->first();

        return $boot;
    }

    public function getDataByID($id){
        $boot = Boot::find($id);

        return $boot;
    }

    public function getDataByCrown($crown_id)
    {
        $boot_id = DB::table('ccbjs')->where('crown_id', $crown_id)->first()->boot_id;

        $boot = Boot::find($boot_id);

        return $boot;
    }
}
