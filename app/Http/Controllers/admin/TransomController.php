<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transom;
use App\Models\Doortype;
use Illuminate\Support\Facades\DB;

class TransomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transoms = DB::select('SELECT a.id,
                                       a.doortype_id,
                                       b.name doortype,
                                       a.name,
                                       a.dealer_price,
                                       a.retail_price,
                                       a.installation_price
                                FROM transoms a, doortypes b 
                                WHERE a.doortype_id=b.id ');

        $transomnames = DB::select('SELECT id, name FROM transom_names ORDER BY name');

        $jobs = DB::table('jobs')->get(['id', 'name'])->toArray();

        $doortypes = DB::select('SELECT id, name FROM doortypes ORDER BY name');

        return view('admin.transom.index', compact('transoms', 'doortypes', 'jobs', 'transomnames'));
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

        $check = DB::select('SELECT * FROM transoms WHERE doortype_id=? AND name=?', [$request->doortype_id, $request->name]);
        
        if (empty($check)) {
            Transom::create([
                'doortype_id'        => $request->doortype_id,
                'name'               => $request->name,
                'dealer_price'       => $request->dealer_price,
                'retail_price'       => $request->retail_price,
                'installation_price' => $request->installation_price,
                'jobs'               => $jobs
            ]);
        }

        return redirect()->route('transoms.index');
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
    public function edit(Transom $transom)
    {
        $doortypes = DB::select('SELECT id, name FROM doortypes ORDER BY name');

        $transom_jobs = explode(",", $transom->jobs);

        $jobs = DB::table('jobs')->get(['id', 'name'])->toArray();

        $transomnames = DB::select('SELECT id, name FROM transom_names ORDER BY name');
        
        return view('admin.transom.index', compact('transom', 'doortypes', 'transom_jobs', 'jobs', 'transomnames'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transom $transom)
    {
        $jobs = "";

        if ($request->jobs){ 
            $jobs = implode(",", $request->jobs);
        }

        $transom->update([
            'doortype_id'        => $request->doortype_id,
            'name'               => $request->name,
            'dealer_price'       => $request->dealer_price,
            'retail_price'       => $request->retail_price,
            'installation_price' => $request->installation_price,
            'jobs'               => $jobs,
        ]);

        return redirect()->route('transoms.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transom $transom)
    {
        $transom->delete();
        
        return redirect()->route('transoms.index');
    }
}
