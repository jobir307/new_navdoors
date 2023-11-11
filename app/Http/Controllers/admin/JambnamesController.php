<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JambnamesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jambnames = DB::table('jamb_names')->get();

        return view('admin.details.jamb', compact('jambnames'));
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
            'name' => 'required'
        ]);

        $fileName = time() . '_' . $request->image->getClientOriginalName();  
        $path = $request->image->move('uploads/jambnames/', $fileName);
        DB::insert('INSERT INTO jamb_names (name, half_height, path) VALUES (?,?,?)', [$request->name, $request->half_height, $path]);

        return redirect()->route('jamb-names.index');
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
        $jambname = DB::table('jamb_names')->where('id', $id)->first();

        return view('admin.details.jamb', compact('jambname'));
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
            'name' => 'required'
        ]);

        $fileName = time() . '_' . $request->image->getClientOriginalName();
        $path = $request->image->move('uploads/jambnames/', $fileName);

        DB::table('jamb_names')->where('id', $id)->update([
            'name'        => $request->name,
            'half_height' => $request->half_height,
            'path'        => $path
        ]);

        return redirect()->route('jamb-names.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('jamb_names')->where('id', $id)->delete();

        return redirect()->route('jamb-names.index');
    }
}
