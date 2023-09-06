<?php

namespace App\Http\Controllers\accountant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use NumberToWords\NumberToWords;
use App\Models\Stock;

class OrderController extends Controller
{
    public function index()
    {
        $orders = DB::select('SELECT a.id AS order_id,
                                     i.id AS invoice_id,
                                     a.phone_number, 
                                     a.contract_number, 
                                     a.last_contract_price,
                                     b.name as customer,
                                     b.inn,
                                    (SELECT SUM(amount)
                                    FROM stocks
                                    WHERE invoice_id=i.id
                                    GROUP BY invoice_id
                                    ) AS paid,
                                    CASE
                                        WHEN a.product = "door" THEN "Eshik"
                                        WHEN a.product = "jamb" THEN "Nalichnik"
                                        WHEN a.product = "transom" THEN "Dobor"
                                        ELSE "Nalichnik+dobor"
                                    END AS product
                            FROM (orders a, customers b)
                            LEFT JOIN invoices i ON a.id=i.order_id
                            WHERE a.customer_id=b.id AND i.status=1
                            ORDER BY a.created_at DESC');

        return view('accountant.order', compact('orders'));
    }

    public function cashin(Request $request)
    {
        $request->validate([
            'amount' => 'required'
        ]);

        $numberToWords = new NumberToWords();

        Stock::create([
            'invoice_id'     => $request->invoice_id,
            'amount'         => $request->amount,
            'inout_type'     => 1, // naryadlar tushumi
            'payment_type'   => 5, // pul ko'chirish
            'in_words'       => NumberToWords::transformNumber('ru', $request->amount) . ' сум',
            'day'            => date('Y-m-d')
        ]);

        return redirect()->route('accountant');
    }

    public function payment_histories($invoice_id)
    {
        $orders = DB::select('SELECT d.id, 
                                     a.contract_number, 
                                     a.last_contract_price-(SELECT SUM(amount) 
                                                       FROM stocks
                                                       WHERE invoice_id=i.id AND id <= d.id
                                                      )+d.amount AS contract_price , 
                                     b.name as customer, 
                                     SUM(d.amount) AS payed, 
                                     a.last_contract_price-(SELECT SUM(amount) 
                                                       FROM stocks
                                                       WHERE invoice_id=i.id AND id <= d.id
                                                      ) AS debt, 
                                     d.created_at, d.in_words, e.name payment_type
                             FROM (orders a, inout_types c, invoices i, payment_types e)
                             LEFT JOIN customers b ON a.customer_id=b.id
                             LEFT JOIN stocks d ON i.id=d.invoice_id AND c.id=d.inout_type
                             WHERE c.action=1 AND d.invoice_id=? AND i.order_id=a.id AND e.id=d.payment_type AND i.status=1
                             GROUP BY d.id', [$invoice_id]);

        return view('accountant.show_order_payment', compact('orders'));
    }

    public function reconciliation_act()
    {
        return view('accountant.act');
    }

    public function customer_act(Request $request)
    {
        if ($request->customer_type == "Yuridik") {
            $act_histories = DB::select('SELECT d.id, 
                                                a.contract_number, 
                                                a.last_contract_price,
                                                a.last_contract_price-(SELECT SUM(amount) 
                                                                FROM stocks
                                                                WHERE invoice_id=i.id AND id <= d.id
                                                                )+d.amount AS contract_price , 
                                                b.name as customer, 
                                                b.phone_number,
                                                b.inn,
                                                SUM(d.amount) AS paid, 
                                                a.last_contract_price-(SELECT SUM(amount) 
                                                                FROM stocks
                                                                WHERE invoice_id=i.id AND id <= d.id
                                                                ) AS debt, 
                                                d.created_at, 
                                                d.in_words, 
                                                e.name payment_type
                                        FROM (orders a, inout_types c, invoices i, payment_types e)
                                        LEFT JOIN customers b ON a.customer_id=b.id
                                        LEFT JOIN stocks d ON i.id=d.invoice_id AND c.id=d.inout_type
                                        WHERE c.action=1 AND b.inn=? AND i.order_id=a.id AND e.id=d.payment_type AND i.status=1 AND i.day BETWEEN ? AND ?
                                        GROUP BY d.id', [$request->inn, $request->date_from, $request->date_to]);
        }
        
        if ($request->customer_type == "Xaridor") {
            $act_histories = DB::select('SELECT d.id, 
                                                a.contract_number, 
                                                a.last_contract_price,
                                                a.last_contract_price-(SELECT SUM(amount) 
                                                                FROM stocks
                                                                WHERE invoice_id=i.id AND id <= d.id
                                                                )+d.amount AS contract_price , 
                                                b.name as customer, 
                                                b.phone_number,
                                                SUM(d.amount) AS paid, 
                                                a.last_contract_price-(SELECT SUM(amount) 
                                                                FROM stocks
                                                                WHERE invoice_id=i.id AND id <= d.id
                                                                ) AS debt, 
                                                d.created_at, 
                                                d.in_words, 
                                                e.name payment_type
                                        FROM (orders a, inout_types c, invoices i, payment_types e)
                                        LEFT JOIN customers b ON a.customer_id=b.id
                                        LEFT JOIN stocks d ON i.id=d.invoice_id AND c.id=d.inout_type
                                        WHERE c.action=1 AND a.contract_number=? AND i.order_id=a.id AND e.id=d.payment_type AND i.status=1 AND i.day BETWEEN ? AND ?
                                        GROUP BY d.id', [$request->contract_number, $request->date_from, $request->date_to]);

        }

        if ($request->customer_type == "Diler") {
            $act_histories = DB::select('SELECT d.id, 
                                                a.contract_number, 
                                                a.last_contract_price,
                                                a.last_contract_price-(SELECT SUM(amount) 
                                                                FROM stocks
                                                                WHERE invoice_id=i.id AND id <= d.id
                                                                )+d.amount AS contract_price , 
                                                b.name as customer, 
                                                b.phone_number,
                                                SUM(d.amount) AS paid, 
                                                a.last_contract_price-(SELECT SUM(amount) 
                                                                FROM stocks
                                                                WHERE invoice_id=i.id AND id <= d.id
                                                                ) AS debt, 
                                                d.created_at, 
                                                d.in_words, 
                                                e.name payment_type
                                        FROM (orders a, inout_types c, invoices i, payment_types e)
                                        LEFT JOIN customers b ON a.customer_id=b.id
                                        LEFT JOIN stocks d ON i.id=d.invoice_id AND c.id=d.inout_type
                                        WHERE c.action=1 AND b.name LIKE "%'.$request->dealer_name.'%" AND i.order_id=a.id AND e.id=d.payment_type AND i.status=1 AND i.day BETWEEN ? AND ?
                                        GROUP BY d.id', [$request->date_from, $request->date_to]);

        }

        $customer_type = $request->customer_type;
       
        $data = array(
            'act_histories' => $act_histories,
            'customer_type' => $customer_type,
            'request' => $request
        );

        return view('accountant.act', $data);
    }
}
