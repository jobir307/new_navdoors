<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Locktype;

class LocktypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $locktypes = Locktype::all();

        return view('admin.locktype.index', compact('locktypes'));
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
        Locktype::create([
            'name' => $request->name,
            'retail_price' => $request->retail_price,
            'dealer_price' => $request->dealer_price
        ]);

        return redirect()->route('locktypes.index');
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
    public function edit(Locktype $locktype)
    {
        return view('admin.locktype.index', compact('locktype'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Locktype $locktype)
    {
        $locktype->update([
            'name' => $request->name,
            'retail_price' => $request->retail_price,
            'dealer_price' => $request->dealer_price
        ]);

        return redirect()->route('locktypes.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Locktype $locktype)
    {
        $locktype->delete();
        
        return redirect()->route('locktypes.index');
    }
}
