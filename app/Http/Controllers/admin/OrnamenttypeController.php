<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ornamenttype;

class OrnamenttypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ornamenttypes = Ornamenttype::all();

        return view('admin.ornamenttype.index', compact('ornamenttypes'));
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
        Ornamenttype::create([
            'name' => $request->name,
            'price' => $request->price,
        ]);

        return redirect()->route('ornamenttypes.index');
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
    public function edit(Ornamenttype $ornamenttype)
    {
        return view('admin.ornamenttype.index', compact('ornamenttype'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ornamenttype $ornamenttype)
    {
        $ornamenttype->update([
            'name' => $request->name,
            'price' => $request->price
        ]);

        return redirect()->route('ornamenttypes.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ornamenttype $ornamenttype)
    {
        $ornamenttype->delete();
        
        return redirect()->route('ornamenttypes.index');
    }
}
