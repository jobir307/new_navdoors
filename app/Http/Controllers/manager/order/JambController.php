<?php

namespace App\Http\Controllers\manager\order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Jamb;
use Illuminate\Support\Facades\Auth;

class JambController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $jambs = DB::select('SELECT id, name, height, width FROM jambs ORDER BY name, height, width');
        $customers = DB::select('SELECT id, name FROM customers WHERE type IN ("Xaridor", "Yuridik") ORDER BY created_at DESC');
        $dealers = DB::select('SELECT id, name FROM customers WHERE type="Diler" ORDER BY created_at DESC');

        return view('manager.order.jamb.create', compact('jambs', 'customers', 'dealers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (empty($request->customer_radio)){
            $request->customer_radio = "dealer";
            $request->dealer = Auth::user()->dealer_id;
        }
        
        $contract_price = 0;
        $order = new Order();
        $jamb_parameters = array(
            "color" => "",
            "name"  => "",
            "count" => 0,
            "price" => 0,
            "total_price" => 0
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
        for($i = 0; $i < count($request->jamb_id); $i++) {
            if ($request['count'][$i] != "" || $request['count'][$i] != 0){ 
                $total_count++;
                $jamb = new Jamb();
                $jamb = $jamb->getData($request['jamb_id'][$i]);
    
                $jamb_parameters['color'] = $request->jamb_color;
                $jamb_parameters['name'] = $jamb->name . '(' . $jamb->height . 'x' . $jamb->width . ')';
                $jamb_parameters['count'] = $request['count'][$i];
                if ($request->customer_radio == "dealer"){
                    $jamb_parameters['price'] = $jamb->dealer_price;
                    $jamb_parameters['total_price'] = $jamb->dealer_price * $request['count'][$i];
                } else {
                    $jamb_parameters['price'] = $jamb->retail_price;
                    $jamb_parameters['total_price'] = $jamb->retail_price * $request['count'][$i];
                }

                DB::insert('INSERT INTO jamb_results(jamb_id, 
                                                     jamb_color, 
                                                     name, 
                                                     count, 
                                                     price, 
                                                     total_price
                                                     ) VALUES (?,?,?,?,?,?)', [
                    $jamb->id,
                    $jamb_parameters['color'], 
                    $jamb_parameters['name'], 
                    $jamb_parameters['count'], 
                    $jamb_parameters['price'], 
                    $jamb_parameters['total_price']
                ]);
                
                $contract_price += $jamb_parameters['total_price'];
            }
        }

        if ($request->with_installation){
            $with_installation = 1;
            $installation_price = $request->jamb_installation_price;
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

        $order->product = "jamb";
        $order->contract_price = $contract_price;
        $order->with_installation = $with_installation;
        $order->installation_price = $installation_price;
        $order->with_courier = $with_courier;
        $order->courier_price = $courier_price;
        $order->last_contract_price = $contract_price;
        $order->comments = $request->comments;
        $order->who_created_userid=Auth::user()->id;
        $order->who_created_username=Auth::user()->username;
        $order->created_at = date('Y-m-d H:i:s');
        $order->updated_at = date('Y-m-d H:i:s');
        $order->save();

        DB::update('UPDATE jamb_results SET order_id=? 
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
                                    a.phone_number, 
                                    a.contract_number, 
                                    a.deadline, 
                                    a.installation_price,
                                    a.courier_price,
                                    a.rebate_percent,
                                    a.contract_price, 
                                    a.last_contract_price,
                                    b.name AS customer, 
                                    a.comments,
                                    (SELECT SUM(amount)
                                     FROM stocks
                                     WHERE invoice_id=c.id
                                     GROUP BY invoice_id
                                    ) AS paid,
                                    c.status,
                                    a.who_created_userid,
                                    a.job_name AS process
                             FROM (orders a, customers b)
                             LEFT JOIN invoices c ON a.id=c.order_id
                             WHERE a.customer_id=b.id AND a.id=?', [$id]);

        $jamb_results = DB::select('SELECT * FROM jamb_results WHERE order_id=?', [$id]);

        return view('manager.order.jamb.show', compact('order', 'jamb_results'));
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
        $jamb_results = DB::select('SELECT * FROM jamb_results WHERE order_id=?', [$order->id]);
        $jambs = DB::select('SELECT id, name, height, width FROM jambs ORDER BY name, height, width');
        $customers = DB::select('SELECT id, name FROM customers WHERE type IN ("Xaridor", "Yuridik") ORDER BY created_at DESC');
        $dealers = DB::select('SELECT id, name FROM customers WHERE type="Diler" ORDER BY created_at DESC');

        return view('manager.order.jamb.update', compact('order', 'jamb_results', 'jambs', 'customers', 'dealers'));
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
        if (empty($request->customer_radio)){
            $request->customer_radio = "dealer";
            $request->dealer = Auth::user()->dealer_id;
        }
        
        $order = Order::find($id);
        DB::delete('DELETE FROM jamb_results WHERE order_id=?', [$id]);
        $contract_price = 0;
        $jamb_parameters = array(
            "color" => "",
            "name"  => "",
            "count" => 0,
            "price" => 0,
            "total_price" => 0
        );

        if ($request->customer_radio == "dealer") {
            $order->customer_id = $request->dealer;
            $order->customer_type = "Diler";
            $order->phone_number = Customer::find($request->dealer)->phone_number;
        } else {
            $order->customer_id = $request->customer;
            $order->customer_type = "Xaridor";
            $order->phone_number = Customer::find($request->customer)->phone_number;
        }
        $total_count = 0;
        $order->contract_number = $request->contract_number;
        $order->deadline = $request->deadline;
        for($i = 0; $i < count($request->jamb_id); $i++) {
            if ($request['count'][$i] != "" || $request['count'][$i] != 0){ 
                $total_count++;
                $jamb = new Jamb();
                $jamb = $jamb->getData($request['jamb_id'][$i]);
                
                $jamb_parameters['color'] = $request->jamb_color;
                $jamb_parameters['name'] = $jamb->name . '(' . $jamb->height . 'x' . $jamb->width . ')';
                $jamb_parameters['count'] = $request['count'][$i];

                if ($request->customer_radio == "dealer"){
                    $jamb_parameters['price'] = $jamb->dealer_price;
                    $jamb_parameters['total_price'] = $jamb->dealer_price * $request['count'][$i];
                } else {
                    $jamb_parameters['price'] = $jamb->retail_price;
                    $jamb_parameters['total_price'] = $jamb->retail_price * $request['count'][$i];
                }
                DB::insert('INSERT INTO jamb_results(order_id, 
                                                     jamb_id, 
                                                     jamb_color, 
                                                     name, 
                                                     count, 
                                                     price, 
                                                     total_price
                                                     ) VALUES (?,?,?,?,?,?,?)', [
                    $id,
                    $jamb->id,
                    $jamb_parameters['color'], 
                    $jamb_parameters['name'], 
                    $jamb_parameters['count'], 
                    $jamb_parameters['price'], 
                    $jamb_parameters['total_price']
                ]);
                
                $contract_price += $jamb_parameters['total_price'];
            }
        }

        if ($request->with_installation){
            $with_installation = 1;
            $installation_price = $request->jamb_installation_price;
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
        $order->comments = $request->comments;
        $order->updated_at = date('Y-m-d H:i:s');
        $order->save();

        $invoice = Invoice::where('order_id', $id)->first();
        $invoice->payer = Customer::find($order->customer_id)->name;
        $invoice->responsible = Auth::user()->username;
        $invoice->amount = $order->last_contract_price;
        $invoice->updated_at = date('Y-m-d H:i:s');
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
