<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GlassFigure;

class GlassFigureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $glass_figures = GlassFigure::all();

        return view('admin.glass.glassfigure.index', compact('glass_figures'));
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
        $request->validate([
            'name' => 'required',
            'image' => 'required|mimes:png,jpg,jpeg|max:3072'
        ]);

        $fileName = time() . '_' . $request->image->getClientOriginalName();  
       
        $path = $request->image->move('uploads/', $fileName);        
        
        GlassFigure::create([
            'name' => $request->name,
            'path' => $path
        ]);
        
        return redirect()->route('glass-figures.index');
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
        $glass_figure = GlassFigure::find($id);

        return view('admin.glass.glassfigure.index', compact('glass_figure'));
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
        $request->validate([
            'name' => 'required',
            'image' => 'required|mimes:png,jpg,jpeg|max:3072'
        ]);
        $fileName = time() . '_' . $request->image->getClientOriginalName();
        $path = $request->image->move('uploads/', $fileName);

        GlassFigure::where('id', $id)->update([
            'name' => $request->name,
            'path' => $path
        ]);

        return redirect()->route('glass-figures.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        GlassFigure::destroy($id);

        return redirect()->route('glass-figures.index');
    }
}
