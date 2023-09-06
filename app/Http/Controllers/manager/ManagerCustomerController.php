<?php

namespace App\Http\Controllers\manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;

class ManagerCustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = DB::select('SELECT * FROM customers WHERE type="Xaridor" ORDER BY created_at DESC');
        $regions = DB::select('SELECT id, name FROM regions');

        return view('manager.customer.index', compact('customers', 'regions'));
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
            'name' => 'required|min:3|max:100',
            'phone_number' => 'required|max:20',
            'address' => 'required|max:600'
        ]);

        Customer::create([
            'region_id' => $request->region_id,
            'district_id' => $request->district_id,
            'mahalla_id' => $request->mahalla_id,
            'street_id' => $request->street_id,
            'home' => $request->home,
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'type' => 'Xaridor',
            'address' => $request->address
        ]);

        return redirect()->route('orders.create');
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
    public function edit(Request $request)
    {    
        $customer = Customer::find($request->id);
        $regions = DB::select('SELECT id, name FROM regions');
        $districts = DB::select('SELECT id, name FROM districts WHERE region_id=?', [$customer->region_id]);
        $mahallas = DB::select('SELECT id, name FROM mahallas WHERE district_id=?', [$customer->district_id]);
        $streets = DB::select('SELECT id, name FROM streets WHERE mahalla_id=?', [$customer->mahalla_id]);

        echo '<div class="modal-body">
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label class="form-label" for="username">FIO</label>
                    <input type="text" name="name" class="form-control" id="username" autocomplete="off" value="'.$customer->name.'">
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label" for="phone_number">Telefon raqami</label>
                    <input type="text" name="phone_number" class="form-control" id="phone_number" autocomplete="off" value="'.$customer->phone_number.'">
                  </div>
                  <div class="col-md-4 mb-3">
                    <label class="form-label" for="region">Viloyat</label>
                    <select class="form-control regions" id="region" name="region_id" style="width: 100%;">
                      <option value=""></option>';
                      foreach($regions as $key => $value){
                        if ($customer->region_id == $value->id)
                          echo '<option value="'.$value->id.'" selected>'.$value->name.'</option>';
                        else
                          echo '<option value="'.$value->id.'">'.$value->name.'</option>';
                      }
                    echo '</select>
                  </div>
                  <div class="col-md-4 mb-3">
                    <label class="form-label" for="district">Tuman</label>
                    <select class="form-control districts" name="district_id" id="district" style="width: 100%;">
                        <option value=""></option>';
                        foreach ($districts as $key => $value) {
                            if ($customer->district_id == $value->id)
                              echo '<option value="'.$value->id.'" selected>'.$value->name.'</option>';
                            else
                              echo '<option value="'.$value->id.'">'.$value->name.'</option>';
                        }
                    echo '</select>
                  </div>
                  <div class="col-md-4 mb-3">
                    <label class="form-label" for="mahalla">Mahalla</label>
                    <select class="form-control mahalla" name="mahalla_id" id="mahalla" style="width: 100%;">
                        <option value=""></option>';
                        foreach ($mahallas as $key => $value) {
                            if ($customer->mahalla_id == $value->id)
                              echo '<option value="'.$value->id.'" selected>'.$value->name.'</option>';
                            else
                              echo '<option value="'.$value->id.'">'.$value->name.'</option>';
                        }
                    echo '</select>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label" for="street">Ko\'cha</label>
                    <select class="form-control streets" name="street_id" id="street" style="width: 100%;">
                        <option value=""></option>';
                        foreach ($streets as $key => $value) {
                            if ($customer->street_id == $value->id)
                              echo '<option value="'.$value->id.'" selected>'.$value->name.'</option>';
                            else
                              echo '<option value="'.$value->id.'">'.$value->name.'</option>';
                        }
                    echo '</select>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label" for="home">Uy</label>
                    <input type="text" id="home" name="home" class="form-control home" autocomplete="off" value="'.$customer->home.'">
                  </div>
                  <div class="col-md-12 mb-3">
                    <label class="form-label" for="address">To\'liq manzili</label>
                    <input type="text" name="address" class="form-control full_address" id="address" value="'.$customer->address.'" autocomplete="off">
                  </div>
                </div>
              </div>';
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
            'name' => 'required|min:3|max:100',
            'phone_number' => 'required|max:20',
            'address' => 'required|max:600'
        ]);

        Customer::where('id', $id)->update([
            'region_id' => $request->region_id,
            'district_id' => $request->district_id,
            'mahalla_id' => $request->mahalla_id,
            'street_id' => $request->street_id,
            'home' => $request->home,
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'address' => $request->address
        ]);

        return redirect()->route('manager-customers.index');
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
