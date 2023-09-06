<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\GlassFigure;
use App\Models\GlassType;
use App\Models\Glass;

class GlassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $glasses = DB::select('SELECT a.name as glasstype, b.name as glassfigure, c.id, c.price
                               FROM glasses c
                               INNER JOIN glass_types a ON a.id=c.glasstype_id
                               INNER JOIN glass_figures b ON b.id=c.glassfigure_id');

        $glass_figures = GlassFigure::all();
        
        $glass_types = GlassType::all();

        return view('admin.glass.index', compact('glasses', 'glass_figures', 'glass_types'));
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
        Glass::create([
            'glasstype_id'   => $request->glasstype_id,
            'glassfigure_id' => $request->glassfigure_id,
            'price'          => $request->price
        ]);

        return redirect()->route('glasses.index');
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
        $glass = Glass::find($id);

        $glass_types = GlassType::all();
        
        $glass_figures = GlassFigure::all();

        return view('admin.glass.index', compact('glass', 'glass_types', 'glass_figures'));
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
        Glass::where('id', $id)->update([
            'glasstype_id'   => $request->glasstype_id,
            'glassfigure_id' => $request->glassfigure_id,
            'price'          => $request->price
        ]);

        return redirect()->route('glasses.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Glass::destroy($id);

        return redirect()->route('glasses.index');
    }
}
