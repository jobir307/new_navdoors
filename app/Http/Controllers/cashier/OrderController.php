<?php

namespace App\Http\Controllers\cashier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Stock;
use App\Models\Invoice;
use NumberToWords\NumberToWords;

class OrderController extends Controller
{
    public function index()
    {
        $new_orders = DB::select('SELECT i.id, 
                                         a.id AS order_id, 
                                         a.contract_number, 
                                         a.contract_price, 
                                         a.last_contract_price, 
                                         b.name as customer, 
                                         SUM(d.amount) AS payed, 
                                         a.last_contract_price-SUM(d.amount) AS debt,
                                         a.manager_verified_time
                                  FROM (orders a, inout_types c, invoices i)
                                  LEFT JOIN customers b ON a.customer_id=b.id
                                  LEFT JOIN stocks d ON i.id=d.invoice_id AND c.id=d.inout_type
                                  WHERE c.action=1 AND a.id=i.order_id AND i.status=1
                                  GROUP BY a.id, a.contract_number, a.contract_price, a.last_contract_price, b.name
                                  HAVING payed IS NULL
                                  ORDER BY a.created_at DESC');

        $prepaid_orders = DB::select('SELECT i.id, 
                                             a.id AS order_id, 
                                             a.contract_number, 
                                             a.contract_price, 
                                             a.last_contract_price, 
                                             b.name as customer, 
                                             SUM(d.amount) AS payed, 
                                             a.last_contract_price-SUM(d.amount) AS debt,
                                             a.manager_verified_time
                                      FROM (orders a, inout_types c, invoices i)
                                      LEFT JOIN customers b ON a.customer_id=b.id
                                      LEFT JOIN stocks d ON i.id=d.invoice_id AND c.id=d.inout_type
                                      WHERE c.action=1 AND a.id=i.order_id AND i.status=1
                                      GROUP BY a.id, a.contract_number, a.contract_price, a.last_contract_price, b.name
                                      HAVING payed>0 AND debt > 0
                                      ORDER BY a.created_at DESC');

        $fullpaid_orders = DB::select('SELECT i.id, 
                                              a.id AS order_id, 
                                              a.contract_number, 
                                              a.contract_price, 
                                              a.last_contract_price, 
                                              b.name as customer, 
                                              SUM(d.amount) AS payed, 
                                              a.last_contract_price-SUM(d.amount) AS debt,
                                              a.manager_verified_time
                                       FROM (orders a, inout_types c, invoices i)
                                       LEFT JOIN customers b ON a.customer_id=b.id
                                       LEFT JOIN stocks d ON i.id=d.invoice_id AND c.id=d.inout_type
                                       WHERE c.action=1 AND a.id=i.order_id AND i.status=1
                                       GROUP BY a.id, a.contract_number, a.contract_price, a.last_contract_price, b.name
                                       HAVING debt<=0
                                       ORDER BY a.created_at DESC');
       
        $all_orders = DB::select('SELECT i.id, 
                                         a.id AS order_id, 
                                         a.contract_number, 
                                         a.contract_price, 
                                         a.last_contract_price, 
                                         b.name as customer, 
                                         SUM(d.amount) AS payed, 
                                         a.last_contract_price-SUM(d.amount) AS debt,
                                         a.manager_verified_time,
                                         a.job_name
                                  FROM (orders a, inout_types c, invoices i)
                                  LEFT JOIN customers b ON a.customer_id=b.id
                                  LEFT JOIN stocks d ON i.id=d.invoice_id AND c.id=d.inout_type
                                  WHERE c.action=1 AND a.id=i.order_id AND i.status=1
                                  GROUP BY a.id, a.contract_number, a.contract_price, a.last_contract_price, b.name
                                  ORDER BY a.created_at DESC');

        
        $payment_types = DB::select('SELECT id, name FROM payment_types');

        return view('cashier.order.index', compact('payment_types', 'new_orders', 'prepaid_orders', 'fullpaid_orders', 'all_orders'));
    }

    public function store_order_payment(Request $request)
    {
        $request->validate([
            'amount' => 'required'
        ]);

        $numberToWords = new NumberToWords();

        Stock::create([
            'invoice_id'     => $request->invoice_id,
            'inout_type'     => $request->inout_type,
            'payment_type'   => $request->payment_type,
            'amount'         => $request->amount,
            'in_words'       => NumberToWords::transformNumber('ru', $request->amount) . ' сум',
            'day'            => date('Y-m-d')
        ]);

        return redirect()->route('cashier-order');
    }

    public function show_order_payments($invoice_id)
    {
        // dd($invoice_id);
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
                                     d.created_at, 
                                     d.in_words, 
                                     d.invoice_id,
                                     e.name payment_type
                             FROM (orders a, inout_types c, invoices i, payment_types e)
                             LEFT JOIN customers b ON a.customer_id=b.id
                             LEFT JOIN stocks d ON i.id=d.invoice_id AND c.id=d.inout_type
                             WHERE c.action=1 AND d.invoice_id=? AND i.order_id=a.id AND e.id=d.payment_type AND i.status=1
                             GROUP BY d.id', [$invoice_id]);

        return view('cashier.order.show_order_payment', compact('orders'));
    }

    public function set_new_price(Request $request)
    {
        $stock = Stock::find($request->stock_id);
        $stock->update([
            'amount'         => $request->new_price,
            'in_words'       => NumberToWords::transformNumber('ru', $request->new_price) . ' сум',
        ]);
        return redirect()->route('show_order_payments', $request->invoice_id);
    }
}
