<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = DB::select('SELECT a.*, 
                                        (SELECT COUNT(*) FROM orders WHERE customer_id=a.id) AS shopping_count
                                 FROM customers a 
                                 ORDER BY a.type, shopping_count');
        
        return view('admin.customer.index', compact('customers'));
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
            'type' => 'required|string',
            'address' => 'required|max:600'
        ]);

        Customer::create([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'type' => $request->type,
            'address' => $request->address,
            'inn' => $request->inn
        ]);

        return redirect()->route('customers.index');

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
    public function edit(Customer $customer)
    {
        return view('admin.customer.index', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required|min:3|max:100',
            'phone_number' => 'required|max:20',
            'type' => 'required|string',
            'address' => 'required|max:600'
        ]);
        
        $customer->update([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'type' => $request->type,
            'address' => $request->address,
            'inn' => $request->inn
        ]);

        return redirect()->route('customers.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customers.index');
    }
}
