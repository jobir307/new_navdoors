<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jamb;
use App\Models\Doortype;
use Illuminate\Support\Facades\DB;

class JambController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jambs = DB::select('SELECT * FROM jambs');
        $jobs = DB::table('jobs')->get();
        $jambnames = DB::select('SELECT id, name FROM jamb_names ORDER BY name');

        return view('admin.jamb.index', compact('jambs', 'jobs', 'jambnames'));
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
        
        for ($i = 0; $i < count($request->height); $i++) {
            if (!empty($request['height'][$i]) && !empty($request['width'][$i])  && !empty($request['retail_price'][$i]) && !empty($request['dealer_price'][$i])) {
                Jamb::create([
                    'height'       => $request['height'][$i],
                    'width'        => $request['width'][$i],
                    'name'         => $request->name,
                    'dealer_price' => $request['dealer_price'][$i],
                    'retail_price' => $request['retail_price'][$i],
                    'jobs'         => $jobs
                ]);
            }
        }

        return redirect()->route('jambs.index');
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
        $jamb = Jamb::find($id);
        $jamb_jobs = explode(",", $jamb->jobs);
        $jambnames = DB::select('SELECT id, name FROM jamb_names ORDER BY name');
        
        $diff_array = array_diff($result, $jamb_jobs);
        $in_array = [];
        foreach($jamb_jobs as $key => $value) {
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

        return view('admin.jamb.index', compact('jamb', 'jobs', 'diff_array', 'in_array', 'jambnames'));
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
        
        $jamb = Jamb::find($id);

        $jamb->update([
            'height'       => $request->height,
            'width'        => $request->width,
            'name'         => $request->name,
            'dealer_price' => $request->dealer_price,
            'retail_price' => $request->retail_price,
            'jobs'         => $jobs
        ]);

        return redirect()->route('jambs.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $jamb = Jamb::find($id);
        $jamb->delete();
        
        return redirect()->route('jambs.index');
    }
}
