<?php

namespace App\Http\Controllers\manager\order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Crown;
use App\Models\Cube;
use App\Models\Boot;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Jamb;

use Illuminate\Support\Facades\Auth;
// crown+cube+boot+jamb
class CCBJController extends Controller
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
        $crowns = DB::select('SELECT id, name, len FROM crowns ORDER BY name, len');
        $boots = DB::select('SELECT id, name FROM boots ORDER BY name');
        $cubes = DB::select('SELECT id, name FROM cubes ORDER BY name');
        $jambs = DB::select('SELECT id, name, height, width FROM jambs ORDER BY name, height, width');
        $customers = DB::select('SELECT id, name FROM customers WHERE type IN ("Xaridor", "Yuridik") ORDER BY created_at DESC');
        $dealers = DB::select('SELECT id, name FROM customers WHERE type="Diler" ORDER BY created_at DESC');
        
        $data = array(
            'crowns'    => $crowns,
            'boots'     => $boots,
            'cubes'     => $cubes,
            'jambs'     => $jambs,
            'customers' => $customers,
            'dealers'   => $dealers
        );

        return view('manager.order.ccbj.create', $data);
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

        $order = new Order();
        $contract_price = 0;
        $check_crown = false;
        $check_cube = false;
        $check_boot = false;
        $check_jamb = false;

        $crown_count = 0;
        $cube_count = 0;
        $boot_count = 0;
        $jamb_count = 0;

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
        if (!empty($request->crown_id)) {
            $check_crown = true;

            foreach ($request->crown_id as $key => $value) {
                if (!empty($value)) {
                    $crown_count++;
                    $crown = new Crown();
                    $crown = $crown->getData($value);
                    if($request->customer_radio == "dealer")
                        $price = $crown->dealer_price;
                    else
                        $price = $crown->retail_price;
    
                    if ($request['door_width'][$key] > 1000 && $request['door_width'][$key] <= 1400)
                        $price = $price * 1.5;
                    if ($request['door_width'][$key] >= 1400)
                        $price = $price * 2;
    
                    $total_price = $price * $request['crown_count'][$key];
                    $contract_price += $total_price;
    
                    DB::insert('INSERT INTO crown_results(crown_id, crown_name, crown_color, count, price, total_price, door_width) VALUES(?,?,?,?,?,?,?)', [
                        $crown->id, 
                        $crown->name,
                        $request->crown_color,
                        $request['crown_count'][$key],
                        $price,
                        $total_price,
                        $request['door_width'][$key] - 50
                    ]);
                }
            }
        }

        if (!empty($request->cube_id)) {
            $check_cube = true;

            foreach ($request->cube_id as $key => $value) {
                if (!empty($value)) {
                    $cube_count++;
                    $cube = new Cube();
                    $cube = $cube->getDataByID($value);
                    if($request->customer_radio == "dealer")
                        $price = $cube->dealer_price;
                    else
                        $price = $cube->retail_price;
    
                    $total_price = $price * $request['cube_count'][$key];
                    $contract_price += $total_price;
    
                    DB::insert('INSERT INTO cube_results(cube_id, cube_name, cube_color, count, price, total_price) VALUES(?,?,?,?,?,?)', [
                        $cube->id, 
                        "Kubik " . $cube->name,
                        $request->cube_color,
                        $request['cube_count'][$key],
                        $price,
                        $total_price
                    ]);
                }
            }
        }

        if (!empty($request->boot_id)) {
            $check_boot = true;

            foreach($request->boot_id as $key => $value) {
                if (!empty($value)) {
                    $boot_count++;
                    $boot = new Boot();
                    $boot = $boot->getDataByID($value);
                    if($request->customer_radio == "dealer")
                        $price = $boot->dealer_price;
                    else
                        $price = $boot->retail_price;
    
                    $total_price = $price * $request['boot_count'][$key];
                    $contract_price += $total_price;
    
                    DB::insert('INSERT INTO boot_results(boot_id, boot_name, boot_color, count, price, total_price) VALUES(?,?,?,?,?,?)', [
                        $boot->id, 
                        "Sapog " .$boot->name,
                        $request->boot_color,
                        $request['boot_count'][$key],
                        $price,
                        $total_price
                    ]);
                }
            }
        }
        
        if (!empty($request->jamb_id)) {
            $check_jamb = true;
            foreach ($request->jamb_id as $key => $value) {
                if (!empty($value)){
                    $jamb_count++;
                    $jamb = new Jamb();
                    $jamb = $jamb->getData($value);
                    if($request->customer_radio == "dealer")
                        $price = $jamb->dealer_price;
                    else
                        $price = $jamb->retail_price;
    
                    $total_price = $price * $request['jamb_count'][$key];
                    $contract_price += $total_price;
    
                    DB::insert('INSERT INTO jamb_results(jamb_id, name, jamb_color, count, price, total_price) VALUES(?,?,?,?,?,?)', [
                        $jamb->id, 
                        $jamb->name,
                        $request->jamb_color,
                        $request['jamb_count'][$key],
                        $price,
                        $total_price
                    ]);
                }
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
        
        $contract_price += $courier_price;
        $contract_price += $installation_price;

        
        $order->product = "ccbj";
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
        
        if ($check_crown) {
            DB::update('UPDATE crown_results SET order_id=? 
                        ORDER BY created_at DESC
                        LIMIT ?', [$order->id, $crown_count]);
        }
        
        if ($check_boot) {
            DB::update('UPDATE boot_results SET order_id=? 
                        ORDER BY created_at DESC
                        LIMIT ?', [$order->id, $boot_count]);
        }

        if ($check_cube) {
            DB::update('UPDATE cube_results SET order_id=? 
                        ORDER BY created_at DESC
                        LIMIT ?', [$order->id, $cube_count]);
        }

        if ($check_jamb) {
            DB::update('UPDATE jamb_results SET order_id=? 
                        ORDER BY created_at DESC
                        LIMIT ?', [$order->id, $jamb_count]);
        }

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
                                    a.transom_installation_price,
                                    a.door_installation_price,
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

        $boot_results = DB::table('boot_results')->where('order_id', $id)->get();
        $cube_results = DB::table('cube_results')->where('order_id', $id)->get();
        $crown_results = DB::table('crown_results')->where('order_id', $id)->get();
        $jamb_results = DB::select('SELECT a.*, b.height, b.width 
                                    FROM jamb_results a
                                    INNER JOIN jambs b ON b.id=a.jamb_id
                                    WHERE order_id=?', [$id]);

        $data = array(
            'order'         => $order,
            'boot_results'  => $boot_results,
            'cube_results'  => $cube_results,
            'crown_results' => $crown_results,
            'jamb_results'  => $jamb_results
        );

        return view('manager.order.ccbj.show', $data);
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
        $crown_results = DB::select('SELECT * FROM crown_results WHERE order_id=?', [$order->id]);
        $crowns = DB::select('SELECT id, name, len FROM crowns ORDER BY name, len');

        $cube_results = DB::select('SELECT * FROM cube_results WHERE order_id=?', [$order->id]);
        $cubes = DB::select('SELECT id, name FROM cubes ORDER BY name');

        $boot_results = DB::select('SELECT * FROM boot_results WHERE order_id=?', [$order->id]);
        $boots = DB::select('SELECT id, name FROM boots ORDER BY name');

        $jamb_results = DB::select('SELECT * FROM jamb_results WHERE order_id=?', [$order->id]);
        $jambs = DB::select('SELECT id, name, height, width FROM jambs ORDER BY name, height, width');
        
        $customers = DB::select('SELECT id, name FROM customers WHERE type IN ("Xaridor", "Yuridik") ORDER BY created_at DESC');
        $dealers = DB::select('SELECT id, name FROM customers WHERE type="Diler" ORDER BY created_at DESC');

        $data = array(
            'order'         => $order,
            'crown_results' => $crown_results,
            'crowns'        => $crowns,
            'cube_results'  => $cube_results,
            'cubes'         => $cubes,
            'boot_results'  => $boot_results,
            'boots'         => $boots,
            'jamb_results'  => $jamb_results,
            'jambs'         => $jambs,
            'customers'     => $customers,
            'dealers'       => $dealers
        );
        return view('manager.order.ccbj.update', $data);
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

        DB::delete('DELETE FROM crown_results WHERE order_id=?', [$id]);
        DB::delete('DELETE FROM cube_results WHERE order_id=?', [$id]);
        DB::delete('DELETE FROM boot_results WHERE order_id=?', [$id]);
        DB::delete('DELETE FROM jamb_results WHERE order_id=?', [$id]);

        $order = Order::find($id);
        $contract_price = 0;

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

        if (!empty($request->crown_id)) {
            foreach ($request->crown_id as $key => $value) {
                if (!empty($value)) {
                    $crown = new Crown();
                    $crown = $crown->getData($value);
                    if($request->customer_radio == "dealer")
                        $price = $crown->dealer_price;
                    else
                        $price = $crown->retail_price;
    
                    if ($request['door_width'][$key] > 1000 && $request['door_width'][$key] <= 1400)
                        $price = $price * 1.5;
                    if ($request['door_width'][$key] >= 1400)
                        $price = $price * 2;
    
                    $total_price = $price * $request['crown_count'][$key];
                    $contract_price += $total_price;
    
                    DB::insert('INSERT INTO crown_results(order_id, crown_id, crown_name, crown_color, count, price, total_price, door_width) VALUES(?,?,?,?,?,?,?,?)', [
                        $id,
                        $crown->id, 
                        $crown->name,
                        $request->crown_color,
                        $request['crown_count'][$key],
                        $price,
                        $total_price,
                        $request['door_width'][$key] - 50
                    ]);
                }
            }
        }

        if (!empty($request->cube_id)) {
            foreach ($request->cube_id as $key => $value) {
                if (!empty($value)) {
                    $cube = new Cube();
                    $cube = $cube->getDataByID($value);
                    if($request->customer_radio == "dealer")
                        $price = $cube->dealer_price;
                    else
                        $price = $cube->retail_price;
    
                    $total_price = $price * $request['cube_count'][$key];
                    $contract_price += $total_price;
    
                    DB::insert('INSERT INTO cube_results(order_id, cube_id, cube_name, cube_color, count, price, total_price) VALUES(?,?,?,?,?,?,?)', [
                        $id,
                        $cube->id, 
                        "Kubik " . $cube->name,
                        $request->cube_color,
                        $request['cube_count'][$key],
                        $price,
                        $total_price
                    ]);
                }
            }
        }

        if (!empty($request->boot_id)) {            
            foreach($request->boot_id as $key => $value) {
                if (!empty($value)) {
                    $boot = new Boot();
                    $boot = $boot->getDataByID($value);
                    if($request->customer_radio == "dealer")
                        $price = $boot->dealer_price;
                    else
                        $price = $boot->retail_price;
    
                    $total_price = $price * $request['boot_count'][$key];
                    $contract_price += $total_price;
    
                    DB::insert('INSERT INTO boot_results(order_id, boot_id, boot_name, boot_color, count, price, total_price) VALUES(?,?,?,?,?,?,?)', [
                        $id,
                        $boot->id, 
                        "Sapog " .$boot->name,
                        $request->boot_color,
                        $request['boot_count'][$key],
                        $price,
                        $total_price
                    ]);
                }
            }
        }
        
        if (!empty($request->jamb_id)) {            
            foreach ($request->jamb_id as $key => $value) {
                if (!empty($value)) {
                    $jamb = new Jamb();
                    $jamb = $jamb->getData($value);
                    if($request->customer_radio == "dealer")
                        $price = $jamb->dealer_price;
                    else
                        $price = $jamb->retail_price;
    
                    $total_price = $price * $request['jamb_count'][$key];
                    $contract_price += $total_price;
    
                    DB::insert('INSERT INTO jamb_results(order_id, jamb_id, name, jamb_color, count, price, total_price) VALUES(?,?,?,?,?,?,?)', [
                        $id,
                        $jamb->id, 
                        $jamb->name,
                        $request->jamb_color,
                        $request['jamb_count'][$key],
                        $price,
                        $total_price
                    ]);
                }
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
        
        $contract_price += $courier_price;
        $contract_price += $installation_price;

        
        $order->product = "ccbj";
        $order->contract_price = $contract_price;
        $order->with_installation = $with_installation;
        $order->installation_price = $installation_price;
        $order->with_courier = $with_courier;
        $order->courier_price = $courier_price;
        $order->last_contract_price = $contract_price;
        $order->comments = $request->comments;
        $order->created_at = date('Y-m-d H:i:s');
        $order->updated_at = date('Y-m-d H:i:s');
        $order->save();

        $invoice = Invoice::where('order_id', $id)->first();
        $invoice->payer = Customer::find($order->customer_id)->name;
        $invoice->responsible = Auth::user()->username;
        $invoice->amount = $order->last_contract_price;
        $invoice->day = date('Y-m-d');
        $invoice->order_id = $id;
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
