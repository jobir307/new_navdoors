<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cube;
use App\Models\Job;
use Illuminate\Support\Facades\DB;

class CubeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cubes = Cube::all();

        $jobs = DB::table('jobs')->get(['id', 'name']);
        
        return view('admin.cube.index', compact('cubes', 'jobs'));
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
        $jobs = "";
        if ($request->jobs){ 
            $jobs = implode(",", $request->jobs);
        }
        
        for ($i = 0; $i < count($request->dealer_price); $i++) {
            if (!empty($request['cubename'][$i]) && !empty($request['retail_price'][$i]) && !empty($request['dealer_price'][$i])) {
                Cube::create([
                    'name'         => $request['cubename'][$i],
                    'dealer_price' => $request['dealer_price'][$i],
                    'retail_price' => $request['retail_price'][$i],
                    'jobs'         => $jobs
                ]);
            }
        }

        return redirect()->route('cubes.index');
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
        $jobs = DB::table('jobs')->get(['id', 'name'])->toArray();
        $result = array_column($jobs, 'id', 'name');
        $cube = Cube::find($id);
        $cube_jobs = explode(",", $cube->jobs);

        $diff_array = array_diff($result, $cube_jobs);
        $in_array = [];
        foreach($cube_jobs as $key => $value) {
            foreach($jobs as $k => $v) {
                if ($v->id == $value) { 
                    $arr = array(
                        'id' => $v->id,
                        'name' => $v->name
                    );
                    array_push($in_array, $arr);
                }
            }

        }
        return view('admin.cube.index', compact('cube', 'jobs', 'diff_array', 'in_array'));
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
        $jobs = "";
        if ($request->jobs){ 
            $jobs = implode(",", $request->jobs);
        }
        
        $cube = Cube::find($id);

        $cube->update([
            'name'         => $request->cubename,
            'dealer_price' => $request->dealer_price,
            'retail_price' => $request->retail_price,
            'jobs'         => $jobs
        ]);

        return redirect()->route('cubes.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cube = Cube::find($id);

        $cube->delete();

        return redirect()->route('cubes.index');
    }
}
