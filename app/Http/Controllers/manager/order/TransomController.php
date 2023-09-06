<?php

namespace App\Http\Controllers\manager\order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Transom;
use Illuminate\Support\Facades\Auth;

class TransomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $transoms = DB::select('SELECT id, name FROM transoms ORDER BY name');
        $customers = DB::select('SELECT id, name FROM customers WHERE type="Xaridor" ORDER BY created_at DESC');
        $dealers = DB::select('SELECT id, name FROM customers WHERE type="Diler" ORDER BY created_at DESC');

        return view('manager.order.transom.create', compact('transoms', 'customers', 'dealers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $contract_price = 0;
        $order = new Order();
        $transom_parameters = array(
            "color" => "",
            "name"  => "",
            "count" => 0,
            "price" => 0,
            "total_price" => 0,
            "height" => 0,
            "width_top" => 0,
            "width_bottom" => 0,
            "thickness" => 0
        );

        $total_count = 0;

        if ($request->customer_radio == "dealer") {
            $order->customer_id = $request->dealer;
            $order->customer_type = "Diler";
            $order->phone_number = Customer::find($request->dealer)->phone_number;
        } else {
            $order->customer_id = $request->customer;
            $order->customer_type = "Xaridor";
            $order->phone_number = Customer::find($request->customer)->phone_number;
        }
        
        $order->contract_number = $request->contract_number;
        $order->deadline = $request->deadline;
        for($i = 0; $i < count($request->transom_id); $i++) {
            if ($request['count'][$i] != "" && $request['count'][$i] != 0) {
                $total_count++;
                $transom = new Transom();
                $transom = $transom->getData($request['transom_id'][$i]);
                $transom_size = 0;
                $transom_size = $request['transom_height'][$i] * $request['transom_width_top'][$i] / 1000000;
    
                if ($request->customer_radio == "dealer"){
                    $transom_price = $transom_size * $transom->dealer_price;
                    $transom_parameters['color'] = $request->transom_color;
                    $transom_parameters['name'] = $transom->name;
                    $transom_parameters['count'] = $request['count'][$i];
                    $transom_parameters['price'] = $transom_price;
                    $transom_parameters['total_price'] = $transom_price * $request['count'][$i];
                    $transom_parameters['height'] = $request['transom_height'][$i];
                    $transom_parameters['width_top'] = $request['transom_width_top'][$i];
                    $transom_parameters['width_bottom'] = $request['transom_width_bottom'][$i];
                } else {
                    $transom_price = $transom_size * $transom->retail_price;
                    $transom_parameters['color'] = $request->transom_color;
                    $transom_parameters['name'] = $transom->name;
                    $transom_parameters['count'] = $request['count'][$i];
                    $transom_parameters['price'] = $transom_price;
                    $transom_parameters['total_price'] = $transom_price * $request['count'][$i];
                    $transom_parameters['height'] = $request['transom_height'][$i];
                    $transom_parameters['width_top'] = $request['transom_width_top'][$i];
                    $transom_parameters['width_bottom'] = $request['transom_width_bottom'][$i];
                }
    
                DB::insert('INSERT INTO transom_results(transom_color,
                                                        name,
                                                        height,
                                                        width_top,
                                                        width_bottom,
                                                        count,
                                                        price,
                                                        total_price) 
                            VALUES (?,?,?,?,?,?,?,?)', [
                                    $transom_parameters['color'], 
                                    $transom_parameters['name'], 
                                    $transom_parameters['height'], 
                                    $transom_parameters['width_top'], 
                                    $transom_parameters['width_bottom'],
                                    $transom_parameters['count'], 
                                    $transom_parameters['price'], 
                                    $transom_parameters['total_price']
                                ]);
                
                $contract_price += $transom_parameters['total_price'];
            }
        }

        if ($request->with_installation){
            $with_installation = 1;
            $installation_price = $request->transom_installation_price;
        } else {
            $with_installation = 0;
            $installation_price = 0;
        }

        if ($request->with_courier){
            $with_courier = 1;
            $courier_price = $request->courier_price;
        } else {
            $with_courier = 0;
            $courier_price = 0;
        }

        $contract_price = $contract_price + $installation_price + $courier_price;

        $order->product = "transom";
        $order->contract_price = $contract_price;
        $order->with_installation = $with_installation;
        $order->installation_price = $installation_price;
        $order->with_courier = $with_courier;
        $order->courier_price = $courier_price;
        $order->last_contract_price = $contract_price;
        $order->save();

        DB::update('UPDATE transom_results SET order_id=? 
                    ORDER BY created_at DESC
                    LIMIT ?', [$order->id, $total_count]);

        $invoice = new Invoice();
        $invoice->payer = Customer::find($order->customer_id)->name;
        $invoice->responsible = Auth::user()->username;
        $invoice->amount = $order->last_contract_price;
        $invoice->day = date('Y-m-d');
        $invoice->order_id = $order->id;
        $invoice->status = 0;
        $invoice->save();

        return redirect()->route('orders');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = DB::select('SELECT a.id, 
                                    a.door_id, 
                                    a.phone_number, 
                                    a.contract_number, 
                                    a.deadline, 
                                    a.installation_price,
                                    a.courier_price,
                                    a.rebate_percent,
                                    a.contract_price, 
                                    a.last_contract_price,
                                    b.name as customer, 
                                    (SELECT SUM(amount)
                                    FROM stocks
                                    WHERE invoice_id=c.id
                                    GROUP BY invoice_id
                                    ) AS paid,
                                    j.name as process
                            FROM (orders a, customers b)
                            LEFT JOIN invoices c ON a.id=c.order_id
                            LEFT JOIN jobs j ON j.id=a.job_id
                            WHERE a.customer_id=b.id AND a.id=?', [$id]);

        $transom_results = DB::select('SELECT * FROM transom_results WHERE order_id=?', [$id]);

        return view('manager.order.transom.show', compact('order', 'transom_results'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $order = Order::find($id);
        $transoms = DB::select('SELECT id, name FROM transoms ORDER BY name');
        $transom_results = DB::select('SELECT * FROM transom_results WHERE order_id=?', [$id]);
        $customers = DB::select('SELECT id, name FROM customers WHERE type="Xaridor" ORDER BY created_at DESC');
        $dealers = DB::select('SELECT id, name FROM customers WHERE type="Diler" ORDER BY created_at DESC');

        return view('manager.order.transom.update', compact('order', 'transoms', 'transom_results', 'customers', 'dealers'));
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
        $contract_price = 0;
        DB::delete("DELETE FROM transom_results WHERE order_id=?", [$id]);
        $order = Order::find($id);
        $transom_parameters = array(
            "color" => "",
            "name"  => "",
            "count" => 0,
            "price" => 0,
            "total_price" => 0,
            "height" => 0,
            "width_top" => 0,
            "width_bottom" => 0,
            "thickness" => 0
        );

        $total_count = 0;

        if ($request->customer_radio == "dealer") {
            $order->customer_id = $request->dealer;
            $order->customer_type = "Diler";
            $order->phone_number = Customer::find($request->dealer)->phone_number;
        } else {
            $order->customer_id = $request->customer;
            $order->customer_type = "Xaridor";
            $order->phone_number = Customer::find($request->customer)->phone_number;
        }
        
        $order->contract_number = $request->contract_number;
        $order->deadline = $request->deadline;
        for($i = 0; $i < count($request->transom_id); $i++) {
            if ($request['count'][$i] != "" && $request['count'][$i] != 0) {
                $total_count++;
                $transom = new Transom();
                $transom = $transom->getData($request['transom_id'][$i]);
                
                $transom_size = 0;
                $transom_size = $request['transom_height'][$i] * $request['transom_width_top'][$i] / 1000000;
    
                if ($request->customer_radio == "dealer"){
                    $transom_price = $transom_size * $transom->dealer_price;
                    $transom_parameters['color'] = $request->transom_color;
                    $transom_parameters['name'] = $transom->name;
                    $transom_parameters['count'] = $request['count'][$i];
                    $transom_parameters['price'] = $transom_price;
                    $transom_parameters['total_price'] = $transom_price * $request['count'][$i];
                    $transom_parameters['height'] = $request['transom_height'][$i];
                    $transom_parameters['width_top'] = $request['transom_width_top'][$i];
                    $transom_parameters['width_bottom'] = $request['transom_width_bottom'][$i];
                } else {
                    $transom_price = $transom_size * $transom->retail_price;
                    $transom_parameters['color'] = $request->transom_color;
                    $transom_parameters['name'] = $transom->name;
                    $transom_parameters['count'] = $request['count'][$i];
                    $transom_parameters['price'] = $transom_price;
                    $transom_parameters['total_price'] = $transom_price * $request['count'][$i];
                    $transom_parameters['height'] = $request['transom_height'][$i];
                    $transom_parameters['width_top'] = $request['transom_width_top'][$i];
                    $transom_parameters['width_bottom'] = $request['transom_width_bottom'][$i];
                }
    
                DB::insert('INSERT INTO transom_results(transom_color,
                                                        name,
                                                        height,
                                                        width_top,
                                                        width_bottom,
                                                        count,
                                                        price,
                                                        total_price) 
                            VALUES (?,?,?,?,?,?,?,?)', [
                                    $transom_parameters['color'], 
                                    $transom_parameters['name'], 
                                    $transom_parameters['height'], 
                                    $transom_parameters['width_top'], 
                                    $transom_parameters['width_bottom'],
                                    $transom_parameters['count'], 
                                    $transom_parameters['price'], 
                                    $transom_parameters['total_price']
                                ]);
                
                $contract_price += $transom_parameters['total_price'];
            }
        }

        if ($request->with_installation){
            $with_installation = 1;
            $installation_price = $request->transom_installation_price;
        } else {
            $with_installation = 0;
            $installation_price = 0;
        }

        if ($request->with_courier){
            $with_courier = 1;
            $courier_price = $request->courier_price;
        } else {
            $with_courier = 0;
            $courier_price = 0;
        }

        $contract_price = $contract_price + $installation_price + $courier_price;

        $order->contract_price = $contract_price;
        $order->with_installation = $with_installation;
        $order->installation_price = $installation_price;
        $order->with_courier = $with_courier;
        $order->courier_price = $courier_price;
        $order->last_contract_price = $contract_price;
        $order->save();

        DB::update('UPDATE transom_results SET order_id=? 
                    ORDER BY created_at DESC
                    LIMIT ?', [$order->id, $total_count]);

        $invoice = Invoice::where('order_id', $id)->first();
        $invoice->payer = Customer::find($order->customer_id)->name;
        $invoice->responsible = Auth::user()->username;
        $invoice->amount = $order->last_contract_price;
        $invoice->day = date('Y-m-d');
        $invoice->status = 0;
        $invoice->save();

        return redirect()->route('orders');
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
