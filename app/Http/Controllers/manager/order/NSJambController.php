<?php

namespace App\Http\Controllers\manager\order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Invoice;
use App\Models\Customer;
use App\Models\NSJamb;
use Illuminate\Support\Facades\Auth;

class NSJambController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $nsjambs = DB::select('SELECT id, jambname FROM n_s_jambs ORDER BY jambname');
        $customers = DB::select('SELECT id, name FROM customers WHERE type IN ("Xaridor", "Yuridik") ORDER BY created_at DESC');
        $dealers = DB::select('SELECT id, name FROM customers WHERE type="Diler" ORDER BY created_at DESC');

        return view('manager.order.nsjamb.create', compact('nsjambs', 'customers', 'dealers'));
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
            if ($request['count'][$i] != "" && $request['count'][$i] != 0) {
                $total_count++;
                $nsjamb = new NSJamb();
                $nsjamb = $nsjamb->getData($request['jamb_id'][$i]);
                $nsjamb_size = 0;
                $nsjamb_size = $request['jamb_height'][$i] * $request['jamb_width_top'][$i] / 1000000;
                
                if ($request->customer_radio == "dealer")
                    $nsjamb_price = $nsjamb_size * $nsjamb->dealer_price;
                else
                    $nsjamb_price = $nsjamb_size * $nsjamb->retail_price;
    
                DB::insert('INSERT INTO nsjamb_results(nsjamb_id,
                                                       nsjamb_color,
                                                       nsjamb_name,
                                                       height,
                                                       width_top,
                                                       width_bottom,
                                                       count,
                                                       price,
                                                       total_price) 
                            VALUES (?,?,?,?,?,?,?,?,?)', [
                                    $nsjamb->id,
                                    $request->jamb_color, 
                                    $nsjamb->jambname, 
                                    $request['jamb_height'][$i], 
                                    $request['jamb_width_top'][$i], 
                                    $request['jamb_width_bottom'][$i],
                                    $request['count'][$i], 
                                    $nsjamb_price, 
                                    $nsjamb_price * $request['count'][$i]
                                ]);
                
                $contract_price += $nsjamb_price * $request['count'][$i];
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

        $order->product = "nsjamb";
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

        DB::update('UPDATE nsjamb_results SET order_id=? 
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

        $nsjamb_results = DB::select('SELECT * FROM nsjamb_results WHERE order_id=?', [$id]);
        return view('manager.order.nsjamb.show', compact('order', 'nsjamb_results'));
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
        $nsjambs = DB::select('SELECT id, jambname FROM n_s_jambs ORDER BY jambname');
        $nsjamb_results = DB::select('SELECT * FROM nsjamb_results WHERE order_id=?', [$id]);
        $customers = DB::select('SELECT id, name FROM customers WHERE type IN ("Xaridor", "Yuridik") ORDER BY created_at DESC');
        $dealers = DB::select('SELECT id, name FROM customers WHERE type="Diler" ORDER BY created_at DESC');
        return view('manager.order.nsjamb.update', compact('order', 'nsjambs', 'nsjamb_results', 'customers', 'dealers'));
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
        
        DB::table('nsjamb_results')->where('order_id', $id)->delete();
        $contract_price = 0;
        $order = Order::find($id);
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
        for($i = 0; $i < count($request->nsjamb_id); $i++) {
            if ($request['jamb_count'][$i] != "" && $request['jamb_count'][$i] != 0) {
                $total_count++;
                $nsjamb = new NSJamb();
                $nsjamb = $nsjamb->getData($request['nsjamb_id'][$i]);
                $nsjamb_size = 0;
                $nsjamb_size = $request['jamb_height'][$i] * $request['jamb_width_top'][$i] / 1000000;
                
                if ($request->customer_radio == "dealer")
                    $nsjamb_price = $nsjamb_size * $nsjamb->dealer_price;
                else
                    $nsjamb_price = $nsjamb_size * $nsjamb->retail_price;
    
                DB::insert('INSERT INTO nsjamb_results(order_id,
                                                       nsjamb_id,
                                                       nsjamb_color,
                                                       nsjamb_name,
                                                       height,
                                                       width_top,
                                                       width_bottom,
                                                       count,
                                                       price,
                                                       total_price) 
                            VALUES (?,?,?,?,?,?,?,?,?,?)', [
                                    $id,
                                    $nsjamb->id,
                                    $request->jamb_color, 
                                    $nsjamb->jambname, 
                                    $request['jamb_height'][$i], 
                                    $request['jamb_width_top'][$i], 
                                    $request['jamb_width_bottom'][$i],
                                    $request['jamb_count'][$i], 
                                    $nsjamb_price, 
                                    $nsjamb_price * $request['jamb_count'][$i]
                                ]);
                
                $contract_price += $nsjamb_price * $request['jamb_count'][$i];
            }
        }

        if ($request->with_installation){
            $with_installation = 1;
            $installation_price = $request->installation_price;
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
