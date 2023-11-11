<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\NSJamb;

class NSJambController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jambnames = DB::select('SELECT id, name FROM jamb_names');
        $nsjambs = DB::select('SELECT * FROM n_s_jambs');
        $jobs = DB::table('jobs')->get();

        return view('admin.nsjamb.index', compact('jambnames', 'nsjambs', 'jobs'));
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
        
        $jambname = DB::table('jamb_names')->where('id', $request->jambname_id)->first();
        if (!empty($request['retail_price']) && !empty($request['dealer_price'])) {
            NSJamb::create([
                'jambname_id'  => $jambname->id,
                'jambname'     => $jambname->name,
                'dealer_price' => $request['dealer_price'],
                'retail_price' => $request['retail_price'],
                'jobs'         => $jobs
            ]);
        }

        return redirect()->route('nsjambs.index');
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
        $nsjamb = NSJamb::find($id);
        $nsjamb_jobs = explode(",", $nsjamb->jobs);
        $jambnames = DB::select('SELECT id, name FROM jamb_names ORDER BY name');
        
        $diff_array = array_diff($result, $nsjamb_jobs);
        $in_array = [];
        foreach($nsjamb_jobs as $key => $value) {
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

        return view('admin.nsjamb.index', compact('nsjamb', 'jobs', 'diff_array', 'in_array', 'jambnames'));
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
        
        $nsjamb = NSJamb::find($id);
        $jambname = DB::table('jamb_names')->where('id', $request->jambname_id)->first();
        $nsjamb->update([
            'jambname_id'  => $jambname->id,
            'jambname'     => $jambname->name,
            'dealer_price' => $request->dealer_price,
            'retail_price' => $request->retail_price,
            'jobs'         => $jobs
        ]);

        return redirect()->route('nsjambs.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $nsjamb = NSJamb::find($id);
        $nsjamb->delete();
        
        return redirect()->route('nsjambs.index');
    }
}
