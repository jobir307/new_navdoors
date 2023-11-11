<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Crown;
use App\Models\Job;
use Illuminate\Support\Facades\DB;

class CrownController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $crowns = Crown::all();
        $jobs = DB::table('jobs')->get(['id', 'name']);

        return view('admin.crown.index', compact('crowns', 'jobs'));
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

        for ($i = 0; $i < count($request->len); $i++) {
            if (!empty($request['len'][$i]) && !empty($request['retail_price'][$i]) && !empty($request['dealer_price'][$i])) {
                Crown::create([
                    'len'          => $request['len'][$i],
                    'name'         => $request->name,
                    'dealer_price' => $request['dealer_price'][$i],
                    'retail_price' => $request['retail_price'][$i],
                    'jobs'         => $jobs
                ]);
            }
        }

        return redirect()->route('crowns.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
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
        $crown = Crown::find($id);
        $crown_jobs = explode(",", $crown->jobs);

        $diff_array = array_diff($result, $crown_jobs);
        $in_array = [];
        foreach($crown_jobs as $key => $value) {
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
        return view('admin.crown.index', compact('crown', 'jobs', 'diff_array', 'in_array'));
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
        
        $crown = Crown::find($id);

        $crown->update([
            'name'         => $request->name,
            'len'          => $request->len,
            'dealer_price' => $request->dealer_price,
            'retail_price' => $request->retail_price,
            'jobs'         => $jobs
        ]);

        return redirect()->route('crowns.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $crown = Crown::find($id);
        $crown->delete();
        
        return redirect()->route('crowns.index');
    }
}
