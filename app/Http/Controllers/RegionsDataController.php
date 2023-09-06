<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegionsDataController extends Controller
{
    public function index()
    {
        /*ini_set('max_execution_time', 2400);
        DB::table('mahallas')->truncate();
        DB::table('streets')->truncate();

        // mahallalar
        $districts = DB::select('SELECT * FROM districts ORDER BY region_id');
        foreach ($districts as $key => $value) {
            $json_mahalla = file_get_contents('https://api.oilakredit.uz/api/v1/public/lists/mahalla?parent_id='.$value->id);
            $mahallalar = json_decode($json_mahalla);
            // dd($mahallalar->data);
            if(!empty($mahallalar->data) && !is_null($mahallalar->data)) {
                foreach ($mahallalar->data as $k => $v) {
                    // dd($v);
                    DB::insert('INSERT INTO mahallas (id, district_id, name, fullname) VALUES(?, ?, ?, ?)', [$v->id, $v->district_id, $v->name, $v->fullname]);
                }
            }
        }

        // ko'chalar
        $mahallas = DB::select('SELECT id FROM mahallas');
        foreach ($mahallas as $key => $value) {
            $json_streets = file_get_contents('https://api.oilakredit.uz/api/v2/public/mahalla/streets?mahalla_id='.$value->id);
            $streets = json_decode($json_streets);
            if(!empty($streets->data->data) && !is_null($streets->data->data)) {
                foreach ($streets->data->data as $k => $v) {
                    if ($v->id != 0) {
                        DB::insert('INSERT INTO streets (region_id, district_id, mahalla_id, name) VALUES(?, ?, ?, ?)', [$v->region_id, $v->district_id, $value->id, $v->name]);
                    }
                }
            }
        }*/
    }
}
