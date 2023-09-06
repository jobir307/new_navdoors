<?php

namespace App\Http\Controllers\manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManagerRegionController extends Controller
{
    public function get_district(Request $request)
    {
        $districts = DB::select('SELECT id, name FROM districts WHERE region_id=?', [$request->region_id]);
        foreach ($districts as $key => $value) {
            echo '<option value="'.$value->id.'">'.$value->name.'</option>';
        }
    }

    public function get_mahalla(Request $request)
    {
        $mahallas = DB::select('SELECT id, name FROM mahallas WHERE district_id=?', [$request->district_id]);
        foreach ($mahallas as $key => $value) {
            echo '<option value="'.$value->id.'">'.$value->name.'</option>';
        }
    }

    public function get_street(Request $request)
    {
        $streets = DB::select('SELECT id, name FROM streets WHERE mahalla_id=?', [$request->mahalla_id]);
        foreach ($streets as $key => $value) {
            echo '<option value="'.$value->id.'">'.$value->name.'</option>';
        }
    }
}
