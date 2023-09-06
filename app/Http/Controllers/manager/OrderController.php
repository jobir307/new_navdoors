<?php

namespace App\Http\Controllers\manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Jamb;
use App\Models\Invoice;

use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index() 
    {
        $confirmed_orders = DB::select('SELECT a.id, 
                                               a.phone_number, 
                                               a.contract_number, 
                                               a.contract_price, 
                                               a.last_contract_price,
                                               a.installation_price, 
                                               a.courier_price,
                                               a.deadline, 
                                               b.name as customer,
                                               (SELECT SUM(amount)
                                                FROM stocks
                                                WHERE invoice_id=i.id
                                                GROUP BY invoice_id
                                               ) AS paid,
                                               j.name as process,
                                               CASE
                                                    WHEN a.product = "door" THEN "Eshik"
                                                    WHEN a.product = "jamb" THEN "Nalichnik"
                                                    WHEN a.product = "transom" THEN "Dobor"
                                                    ELSE "Nalichnik+dobor"
                                               END AS product
                                        FROM (orders a, customers b)
                                        LEFT JOIN invoices i ON a.id=i.order_id
                                        LEFT JOIN jobs j ON j.id=a.job_id
                                        WHERE a.customer_id=b.id AND i.status=1
                                        ORDER BY a.created_at DESC');

        $not_confirmed_orders = DB::select('SELECT a.id, 
                                                   a.phone_number, 
                                                   a.contract_number, 
                                                   a.contract_price, 
                                                   a.installation_price, 
                                                   a.courier_price,
                                                   a.deadline, 
                                                   b.name as customer,
                                                   CASE
                                                        WHEN a.product = "door" THEN "Eshik"
                                                        WHEN a.product = "jamb" THEN "Nalichnik"
                                                        WHEN a.product = "transom" THEN "Dobor"
                                                        ELSE "Nalichnik+dobor"
                                                   END AS product
                                            FROM (orders a, customers b)
                                            LEFT JOIN invoices i ON a.id=i.order_id
                                            WHERE a.customer_id=b.id AND i.status=0
                                            ORDER BY a.created_at DESC');
        
        return view('manager.order.index', compact('confirmed_orders', 'not_confirmed_orders'));
    }
    public function confirm_invoice(Request $request)
    {
        $order = Order::find($request->order_id);

        // dd($order);
        $order->rebate_percent = $request->rebate_percent;
        $order->last_contract_price = ($order->contract_price - $order->installation_price - $order->courier_price) * (100 - $request->rebate_percent) / 100 + $order->installation_price + $order->courier_price; 
        $order->save();
        
        Invoice::where('order_id', $request->order_id)->update([
            'status' => 1
        ]);

        return redirect()->route('orders');
    }

    public function jamb_by_doortype(Request $request)
    {
        $jambs = Jamb::where('doortype_id', $request->doortype_id)->get();

        return response()->json([
            'jambs' => $jambs
        ]);
    }

    public function glass_types(Request $request)
    {
        $glasstypes = DB::select('SELECT DISTINCT a.id, a.name
                                  FROM glass_types a
                                  INNER JOIN glasses b ON a.id=b.glasstype_id
                                  WHERE b.glassfigure_id=?', [$request->glassfigure_id]);
        return response()->json([
            'glasstypes' => $glasstypes
        ]);
    }

}