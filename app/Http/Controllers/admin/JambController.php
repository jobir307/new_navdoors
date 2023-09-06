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
        $jambs = DB::select('SELECT a.id,
                                    a.doortype_id,
                                    b.name doortype,
                                    a.name,
                                    a.dealer_price,
                                    a.retail_price
                             FROM jambs a, doortypes b 
                             WHERE a.doortype_id=b.id');

        $jobs = DB::table('jobs')->get();

        $doortypes = DB::select('SELECT id, name FROM doortypes ORDER BY name');

        return view('admin.jamb.index', compact('jambs', 'jobs', 'doortypes'));
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

        Jamb::create([
            'doortype_id'  => $request->doortype_id,
            'name'         => $request->name,
            'dealer_price' => $request->dealer_price,
            'retail_price' => $request->retail_price,
            'jobs'         => $jobs
        ]);

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
    public function edit(Jamb $jamb)
    {
        $jobs = DB::table('jobs')->get(['id', 'name'])->toArray();

        $jamb_jobs = explode(",", $jamb->jobs);
        
        $doortypes = DB::select('SELECT id, name FROM doortypes ORDER BY name');

        return view('admin.jamb.index', compact('jamb', 'doortypes', 'jobs', 'jamb_jobs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Jamb $jamb)
    {
        $jobs = "";
        if ($request->jobs){ 
            $jobs = implode(",", $request->jobs);
        }
        
        $jamb->update([
            'doortype_id'  => $request->doortype_id,
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
    public function destroy(Jamb $jamb)
    {
        $jamb->delete();
        
        return redirect()->route('jambs.index');
    }
}
