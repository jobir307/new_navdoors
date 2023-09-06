<?php

namespace App\Http\Controllers\moderator;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Driver;

class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $car_models = DB::select('SELECT id, name FROM car_models');

        $drivers = DB::select('SELECT a.id, 
                                      a.name as driver, 
                                      a.phone_number,
                                      b.name as car_model,
                                      a.gov_number,
                                      a.type
                               FROM drivers a
                               INNER JOIN car_models b ON a.carmodel_id=b.id
                               WHERE a.active=1
                            ');

        return view('moderator.driver.index', compact('car_models', 'drivers'));
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
    public function store(Request $request):RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name'         => 'required|max:255',
            'phone_number' => 'required',
            'gov_number'   => 'required'
        ]);
 
        if ($validator->fails()) {
            return redirect()->route('drivers.index')
                        ->withErrors($validator)
                        ->withInput();
        }

        Driver::create([
            'name'         => $request->name,
            'phone_number' => $request->phone_number,
            'carmodel_id'  => $request->carmodel_id,
            'gov_number'   => strtoupper($request->gov_number),
            'type'         => $request->type
        ]);

        return redirect()->route('drivers.index')->with(['message' => 'Haydovchi muvaffaqiyatli saqlandi.']);
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
