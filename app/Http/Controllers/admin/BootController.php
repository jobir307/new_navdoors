<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\Boot;
use Illuminate\Support\Facades\DB;

class BootController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $boots = Boot::all();
        $jobs = DB::table('jobs')->get(['id', 'name']);

        return view('admin.boot.index', compact('boots', 'jobs'));
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
        
        for ($i = 0; $i < count($request->bootname); $i++) {
            if (!empty($request['bootname'][$i]) && !empty($request['retail_price'][$i]) && !empty($request['dealer_price'][$i])) {
                Boot::create([
                    'name'         => $request['bootname'][$i],
                    'dealer_price' => $request['dealer_price'][$i],
                    'retail_price' => $request['retail_price'][$i],
                    'jobs'         => $jobs
                ]);
            }
        }

        return redirect()->route('boots.index');
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
        $boot = Boot::find($id);
        $boot_jobs = explode(",", $boot->jobs);

        $diff_array = array_diff($result, $boot_jobs);
        $in_array = [];
        foreach($boot_jobs as $key => $value) {
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
        return view('admin.boot.index', compact('boot', 'jobs', 'diff_array', 'in_array'));
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
        
        $boot = Boot::find($id);

        $boot->update([
            'name'         => $request->bootname,
            'dealer_price' => $request->dealer_price,
            'retail_price' => $request->retail_price,
            'jobs'         => $jobs
        ]);

        return redirect()->route('boots.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $boot = Boot::find($id);

        $boot->delete();

        return redirect()->route('boots.index');
    }
}
