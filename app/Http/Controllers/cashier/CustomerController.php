<?php

namespace App\Http\Controllers\cashier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = DB::select('SELECT * FROM (
                                    SELECT a.id,
                                           a.name,
                                           a.type,
                                           a.phone_number,
                                           (SELECT COUNT(o.id) 
                                            FROM orders o 
                                            INNER JOIN invoices i ON i.order_id=o.id 
                                            WHERE o.customer_id=a.id AND i.status=1) AS shopping_count,
                                           (SELECT SUM(b.amount)
                                            FROM stocks b
                                            LEFT JOIN invoices d ON b.invoice_id=d.id
                                            LEFT JOIN orders c ON c.id=d.order_id
                                            WHERE d.status=1 AND c.customer_id=a.id
                                            GROUP BY c.customer_id
                                           ) AS payed,
                                           SUM(g.last_contract_price) AS contract_price
                                    FROM (customers a, invoices f) 
                                    INNER JOIN orders g ON (g.customer_id=a.id AND f.order_id=g.id)
                                    WHERE a.id=g.customer_id AND f.status=1
                                    GROUP BY g.customer_id) dd
                                 ORDER BY type, shopping_count DESC');

        return view('cashier.customer.index', compact('customers'));
    }

    public function shopping($customer_id)
    {
        $customer_orders = DB::select('SELECT a.id, 
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
                                       WHERE a.customer_id=b.id AND a.customer_id=? AND i.status=1', [$customer_id]);
        
        return view('cashier.customer.shopping', compact('customer_orders'));
    }
}
