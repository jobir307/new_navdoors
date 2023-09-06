<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doortype;
use Illuminate\Support\Facades\DB;

class DoortypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $doortypes = Doortype::all();
        return view('admin.doortype.index', compact('doortypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $jobs = DB::table('jobs')->get();

        return view('admin.doortype.create', compact('jobs'));
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
        
        Doortype::create([
            'name' => $request->name,
            'dealer_price' => $request->dealer_price,
            'retail_price' => $request->retail_price,
            'installation_price' => $request->installation_price,
            'layer15_koeffitsient' => $request->layer15_koeffitsient,
            'jobs' => $jobs
        ]);

        return redirect()->route('doortypes.index');
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
    public function edit(Doortype $doortype)
    {
        $all_jobs = DB::table('jobs')->get(['id', 'name'])->toArray();
    
        $jobs = explode(",", $doortype->jobs);
       
        $in_array = [];
        $not_in_array = [];
        foreach($all_jobs as $key => $value) {
            $arr = array(
                'id' => $value->id,
                'name' => $value->name
            );

            if ($pos = array_search($value->id, $jobs) !== false) 
                array_push($in_array, $arr);
            else 
                array_push($not_in_array, $arr);
        }
        // dd($not_in_array);
        $data = array(
            'in_array' => $in_array,
            'not_in_array' => $not_in_array,
            'doortype' => $doortype
        );

        return view('admin.doortype.update', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Doortype $doortype)
    {
        $jobs = "";
        
        if ($request->jobs) {
            $jobs = implode(",", $request->jobs);
        }
        
        $doortype->update([
            'name' => $request->name,
            'dealer_price' => $request->dealer_price,
            'retail_price' => $request->retail_price,
            'installation_price' => $request->installation_price,
            'layer15_koeffitsient' => $request->layer15_koeffitsient,
            'jobs' => $jobs
        ]);

        return redirect()->route('doortypes.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Doortype $doortype)
    {
        $doortype->delete();
        
        return redirect()->route('doortypes.index');
    }
}
