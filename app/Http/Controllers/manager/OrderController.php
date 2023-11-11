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
        if (Auth::user()->role_id == 8){ // diler
            $confirmed_orders = DB::select('SELECT a.id, 
                                                    a.phone_number, 
                                                    a.contract_number, 
                                                    a.contract_price, 
                                                    a.last_contract_price,
                                                    a.installation_price, 
                                                    a.courier_price,
                                                    a.manager_verified_time AS verified_time,
                                                    a.deadline, 
                                                    a.created_at AS when_created,
                                                    b.name AS customer,
                                                    (SELECT SUM(amount)
                                                        FROM stocks
                                                        WHERE invoice_id=i.id
                                                        GROUP BY invoice_id
                                                    ) AS paid,
                                                    a.job_name AS process,
                                                    CASE
                                                        WHEN a.product = "door" THEN "Eshik"
                                                        WHEN a.product = "jamb" THEN "Nalichnik"
                                                        WHEN a.product = "nsjamb" THEN "NS nalichnik"
                                                        WHEN a.product = "transom" THEN "Dobor"
                                                        WHEN a.product = "jamb+transom" THEN "Nalichnik+dobor"
                                                        ELSE "NKKS"
                                                    END AS product,
                                                    a.who_created_username
                                            FROM (orders a, customers b)
                                            LEFT JOIN invoices i ON a.id=i.order_id
                                            WHERE a.customer_id=b.id AND i.status=1 AND a.moderator_send=0 AND (a.who_created_userid=? OR a.customer_id=?)
                                            ORDER BY a.created_at DESC', [Auth::user()->id, Auth::user()->dealer_id]);

            $not_confirmed_orders = DB::select('SELECT a.id, 
                                                        a.phone_number, 
                                                        a.contract_number, 
                                                        a.contract_price, 
                                                        a.installation_price, 
                                                        a.courier_price,
                                                        a.deadline, 
                                                        a.created_at AS when_created,
                                                        b.name as customer,
                                                        CASE
                                                            WHEN a.product = "door" THEN "Eshik"
                                                            WHEN a.product = "jamb" THEN "Nalichnik"
                                                            WHEN a.product = "nsjamb" THEN "NS nalichnik"
                                                            WHEN a.product = "transom" THEN "Dobor"
                                                            WHEN a.product = "jamb+transom" THEN "Nalichnik+dobor"
                                                            ELSE "NKKS"
                                                        END AS product,
                                                        a.who_created_username,
                                                        a.who_created_userid
                                                FROM (orders a, customers b)
                                                LEFT JOIN invoices i ON a.id=i.order_id
                                                WHERE a.customer_id=b.id AND 
                                                      i.status=0 AND 
                                                      (a.who_created_userid=? OR a.customer_id=?)
                                                ORDER BY a.created_at DESC', [Auth::user()->id, Auth::user()->dealer_id]);

            $completed_orders = DB::select('SELECT a.id, 
                                                    a.phone_number, 
                                                    a.contract_number, 
                                                    a.contract_price, 
                                                    a.last_contract_price,
                                                    (SELECT SUM(amount)
                                                        FROM stocks
                                                        WHERE invoice_id=i.id
                                                        GROUP BY invoice_id
                                                    ) AS paid,
                                                    a.deadline, 
                                                    a.moderator_send_time AS send_time,
                                                    b.name AS customer,
                                                    a.job_name,
                                                    a.created_at AS when_created,
                                                    a.moderator_send_time,
                                                    CASE
                                                        WHEN a.product = "door" THEN "Eshik"
                                                        WHEN a.product = "jamb" THEN "Nalichnik"
                                                        WHEN a.product = "nsjamb" THEN "NS nalichnik"
                                                        WHEN a.product = "transom" THEN "Dobor"
                                                        WHEN a.product = "jamb+transom" THEN "Nalichnik+dobor"
                                                        ELSE "NKKS"
                                                    END AS product,
                                                    a.who_created_username
                                            FROM (orders a, customers b)
                                            LEFT JOIN invoices i ON a.id=i.order_id
                                            WHERE a.customer_id=b.id AND 
                                                  a.moderator_receive=1 AND 
                                                  a.moderator_send=1 AND 
                                                  a.manager_status=1 AND 
                                                  i.status=1 AND 
                                                  (a.who_created_userid=? OR a.customer_id=?)
                                            ORDER BY a.moderator_send_time DESC',[Auth::user()->id, Auth::user()->dealer_id]);
            $all_orders = DB::select('SELECT a.id, 
                                             a.phone_number, 
                                             a.contract_number, 
                                             a.contract_price, 
                                             a.last_contract_price,
                                             a.installation_price, 
                                             a.courier_price,
                                             a.manager_verified_time AS verified_time,
                                             a.deadline, 
                                             a.created_at AS when_created,
                                             b.name AS customer,
                                             (SELECT SUM(amount)
                                              FROM stocks
                                              WHERE invoice_id=i.id
                                              GROUP BY invoice_id
                                             ) AS paid,
                                             a.job_name AS process,
                                             CASE
                                                WHEN a.product = "door" THEN "Eshik"
                                                WHEN a.product = "jamb" THEN "Nalichnik"
                                                WHEN a.product = "nsjamb" THEN "NS nalichnik"
                                                WHEN a.product = "transom" THEN "Dobor"
                                                WHEN a.product = "jamb+transom" THEN "Nalichnik+dobor"
                                                ELSE "NKKS"
                                             END AS product,
                                             a.who_created_username
                                      FROM (orders a, customers b)
                                      LEFT JOIN invoices i ON a.id=i.order_id
                                      WHERE a.customer_id=b.id AND (a.who_created_userid=? OR a.customer_id=?)
                                     ORDER BY a.created_at DESC', [Auth::user()->id, Auth::user()->dealer_id]);       
            
        } else {
            $confirmed_orders = DB::select('SELECT a.id, 
                                                    a.phone_number, 
                                                    a.contract_number, 
                                                    a.contract_price, 
                                                    a.last_contract_price,
                                                    a.installation_price, 
                                                    a.courier_price,
                                                    a.manager_verified_time AS verified_time,
                                                    a.deadline, 
                                                    a.created_at AS when_created,
                                                    b.name AS customer,
                                                    a.job_name AS process,
                                                    (SELECT SUM(amount)
                                                        FROM stocks
                                                        WHERE invoice_id=i.id
                                                        GROUP BY invoice_id
                                                    ) AS paid,
                                                    CASE
                                                        WHEN a.product = "door" THEN "Eshik"
                                                        WHEN a.product = "jamb" THEN "Nalichnik"
                                                        WHEN a.product = "nsjamb" THEN "NS nalichnik"
                                                        WHEN a.product = "transom" THEN "Dobor"
                                                        WHEN a.product = "jamb+transom" THEN "Nalichnik+dobor"
                                                        ELSE "NKKS"
                                                    END AS product,
                                                    a.who_created_username
                                            FROM (orders a, customers b)
                                            LEFT JOIN invoices i ON a.id=i.order_id
                                            WHERE a.customer_id=b.id AND 
                                                  i.status=1 AND 
                                                  a.moderator_send=0
                                            ORDER BY a.created_at DESC');

            $not_confirmed_orders = DB::select('SELECT a.id, 
                                                    a.phone_number, 
                                                    a.contract_number, 
                                                    a.contract_price, 
                                                    a.installation_price, 
                                                    a.courier_price,
                                                    a.deadline, 
                                                    a.created_at AS when_created,
                                                    b.name as customer,
                                                    CASE
                                                        WHEN a.product = "door" THEN "Eshik"
                                                        WHEN a.product = "jamb" THEN "Nalichnik"
                                                        WHEN a.product = "nsjamb" THEN "NS nalichnik"
                                                        WHEN a.product = "transom" THEN "Dobor"
                                                        WHEN a.product = "jamb+transom" THEN "Nalichnik+dobor"
                                                        ELSE "NKKS"
                                                    END AS product,
                                                    a.who_created_username,
                                                    a.who_created_userid
                                                FROM (orders a, customers b)
                                                LEFT JOIN invoices i ON a.id=i.order_id
                                                WHERE a.customer_id=b.id AND 
                                                      i.status=0
                                                ORDER BY a.created_at DESC');

            $completed_orders = DB::select('SELECT a.id, 
                                                   a.phone_number, 
                                                   a.contract_number, 
                                                   a.contract_price, 
                                                   a.last_contract_price,
                                                   (SELECT SUM(amount)
                                                    FROM stocks
                                                    WHERE invoice_id=i.id
                                                    GROUP BY invoice_id
                                                   ) AS paid,
                                                   a.deadline, 
                                                   a.moderator_send_time AS send_time,
                                                   b.name AS customer,
                                                   a.job_name,
                                                   a.created_at AS when_created,
                                                   a.moderator_send_time,
                                                   CASE
                                                        WHEN a.product = "door" THEN "Eshik"
                                                        WHEN a.product = "jamb" THEN "Nalichnik"
                                                        WHEN a.product = "nsjamb" THEN "NS nalichnik"
                                                        WHEN a.product = "transom" THEN "Dobor"
                                                        WHEN a.product = "jamb+transom" THEN "Nalichnik+dobor"
                                                        ELSE "NKKS"
                                                   END AS product,
                                                   a.who_created_username
                                            FROM (orders a, customers b)
                                            LEFT JOIN invoices i ON a.id=i.order_id
                                            WHERE a.customer_id=b.id AND 
                                                  a.moderator_receive=1 AND 
                                                  a.moderator_send=1 AND 
                                                  a.manager_status=1 AND 
                                                  i.status=1
                                            ORDER BY a.moderator_send_time DESC');
            $all_orders = DB::select('SELECT a.id, 
                                             a.phone_number, 
                                             a.contract_number, 
                                             a.contract_price, 
                                             a.last_contract_price,
                                             (SELECT SUM(amount)
                                              FROM stocks
                                              WHERE invoice_id=i.id
                                              GROUP BY invoice_id
                                             ) AS paid,
                                             a.deadline, 
                                             a.moderator_send_time AS send_time,
                                             b.name AS customer,
                                             a.job_name,
                                             a.created_at AS when_created,
                                             a.moderator_send_time,
                                             CASE
                                                WHEN a.product = "door" THEN "Eshik"
                                                WHEN a.product = "jamb" THEN "Nalichnik"
                                                WHEN a.product = "nsjamb" THEN "NS nalichnik"
                                                WHEN a.product = "transom" THEN "Dobor"
                                                WHEN a.product = "jamb+transom" THEN "Nalichnik+dobor"
                                                ELSE "NKKS"
                                             END AS product,
                                             a.who_created_username,
                                             a.who_created_userid
                                      FROM (orders a, customers b)
                                      LEFT JOIN invoices i ON a.id=i.order_id
                                      WHERE a.customer_id=b.id
                                      ORDER BY a.created_at DESC');
        }
        
        return view('manager.order.index', compact('confirmed_orders', 'not_confirmed_orders', 'completed_orders', 'all_orders'));
    }

    public function confirm_invoice(Request $request)
    {
        $order = Order::find($request->order_id);

        $order->rebate_percent = $request->rebate_percent;
        $order->manager_verified_time = date('Y-m-d H:i:s');
        $order->last_contract_price = ($order->contract_price - $order->installation_price - $order->courier_price) * (100 - $request->rebate_percent) / 100 + $order->installation_price + $order->courier_price; 
        $order->save();
        
        Invoice::where('order_id', $request->order_id)->update([
            'status' => 1,
            'amount' => $order->last_contract_price
        ]);

        return redirect()->route('orders');
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

    public function set_new_order_price(Request $request) // that's for only administrator !!!
    {
        Order::where('id', $request->order_id)->update([
            'last_contract_price' => $request->last_contract_price,
            'rebate_percent' => 0,
            'manager_verified_time' => date('Y-m-d H:i:s')
        ]);

        Invoice::where('order_id', $request->order_id)->update([
            'amount' => $request->last_contract_price,
            'status' => 1
        ]);

        return redirect()->route('orders');
    }

    public function delete(Request $request)
    {
        $order = Order::find($request->order_id);
        $order->delete();

        return redirect()->route('orders');
    }
}