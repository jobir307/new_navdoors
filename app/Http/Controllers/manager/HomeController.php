<?php

namespace App\Http\Controllers\manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Doortype;

class HomeController extends Controller
{
    public function glasses()
    {
        $glasses = DB::select('SELECT a.name as glasstype, b.name as glassfigure, c.id, c.price
                               FROM glasses c
                               INNER JOIN glass_types a ON a.id=c.glasstype_id
                               INNER JOIN glass_figures b ON b.id=c.glassfigure_id
                               ORDER BY a.name, b.name');

        return view('manager.glass.index', compact('glasses'));
    }

    public function doors()
    {
        $doortypes = DB::select('SELECT * FROM doortypes ORDER BY name');

        return view('manager.doortype.index', compact('doortypes'));
    }

}
