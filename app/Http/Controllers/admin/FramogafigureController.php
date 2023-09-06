<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Framogafigure;

class FramogafigureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $framogafigures = Framogafigure::all();

        return view('admin.framogafigure.index', compact('framogafigures'));
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
        Framogafigure::create([
            'name' => $request->name,
            'price' => $request->price,
            'min_price' => $request->min_price
        ]);

        return redirect()->route('framogafigures.index');
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
    public function edit(Framogafigure $framogafigure)
    {
        return view('admin.framogafigure.index', compact('framogafigure'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Framogafigure $framogafigure)
    {
        $framogafigure->update([
            'name' => $request->name,
            'price' => $request->price,
            'min_price' => $request->min_price
        ]);

        return redirect()->route('framogafigures.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Framogafigure $framogafigure)
    {
        $framogafigure->delete();
        
        return redirect()->route('framogafigures.index');
    }
}
