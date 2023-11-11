<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CCBJController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results= DB::select('SELECT e.id,
                                     a.name AS crown_name,  
                                     a.len AS crown_len,
                                     b.name AS cube_name,
                                     c.name AS boot_name,
                                     d.name AS jamb_name
                              FROM ccbjs e
                              LEFT JOIN crowns a ON a.id=e.crown_id
                              LEFT JOIN cubes b ON b.id=e.cube_id
                              LEFT JOIN boots c ON c.id=e.boot_id
                              LEFT JOIN jamb_names d ON d.id=e.jamb_id');

        $crowns = DB::select('SELECT id, name, len FROM crowns ORDER BY name');
        $cubes = DB::select('SELECT id, name FROM cubes ORDER BY name');
        $boots = DB::select('SELECT id, name FROM boots ORDER BY name');
        $jambs = DB::select('SELECT id, name FROM jamb_names ORDER BY name');

        return view('admin.ccbj.index', compact('results', 'crowns', 'cubes', 'boots', 'jambs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        for ($i = 0; $i < count($request->crown_id); $i++) {
            if (!empty($request['cube_id'][$i]) && !empty($request['boot_id'][$i]) && !empty($request['jamb_id'][$i])) {
                DB::insert('INSERT INTO ccbjs(crown_id, cube_id, boot_id, jamb_id) VALUES (?,?,?,?)', [
                    $request['crown_id'][$i],
                    $request['cube_id'][$i],
                    $request['boot_id'][$i],
                    $request['jamb_id'][$i]
                ]);
            }
        }

        return redirect()->route('ccbjs.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $result = DB::table('ccbjs')->find($id);

        $crowns = DB::select('SELECT id, name, len FROM crowns ORDER BY name');
        $cubes = DB::select('SELECT id, name FROM cubes ORDER BY name');
        $boots = DB::select('SELECT id, name FROM boots ORDER BY name');
        $jambs = DB::select('SELECT id, name FROM jamb_names ORDER BY name');
        
        return view('admin.ccbj.index', compact('result', 'crowns', 'cubes', 'boots', 'jambs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!empty($request->cube_id) && !empty($request->boot_id) && !empty($request->jamb_id)) {
            $result = DB::table('ccbjs')->where('id', $id)->update([
                'crown_id' => $request->crown_id,
                'cube_id' => $request->cube_id,
                'boot_id' => $request->boot_id,
                'jamb_id' => $request->jamb_id,
            ]);
        }

        return redirect()->route('ccbjs.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('ccbjs')->where('id', $id)->delete();

        return redirect()->route('ccbjs.index');
    }
}
