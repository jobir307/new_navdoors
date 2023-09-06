<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Loop;

class LoopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $loops = Loop::all();

        return view('admin.loop.index', compact('loops'));
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
        Loop::create([
            'name' => $request->name,
            'dealer_price' => $request->dealer_price,
            'retail_price' => $request->retail_price,
        ]);

        return redirect()->route('loops.index');
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
    public function edit(Loop $loop)
    {
        return view('admin.loop.index', compact('loop'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Loop $loop)
    {
        $loop->update([
            'name' => $request->name,
            'dealer_price' => $request->dealer_price,
            'retail_price' => $request->retail_price,
        ]);

        return redirect()->route('loops.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Loop $loop)
    {
        $loop->delete();
        
        return redirect()->route('loops.index');
    }
}
