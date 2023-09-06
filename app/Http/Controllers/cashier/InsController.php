<?php

namespace App\Http\Controllers\cashier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Stock;
use App\Models\Invoice;
use NumberToWords\NumberToWords;

class InsController extends Controller
{
    public function index($inout_type)
    {
        $orders  = [];
        $ins = [];
        if ($inout_type == 1) {
            $orders = DB::select('SELECT i.id, a.contract_number, a.contract_price, a.last_contract_price, b.name as customer, SUM(d.amount) AS payed, a.last_contract_price-SUM(d.amount) AS debt
                                  FROM (orders a, inout_types c, invoices i)
                                  LEFT JOIN customers b ON a.customer_id=b.id
                                  LEFT JOIN stocks d ON i.id=d.invoice_id AND c.id=d.inout_type
                                  WHERE c.action=1 AND a.id=i.order_id AND i.status=1
                                  GROUP BY a.id, a.contract_number, a.contract_price, a.last_contract_price, b.name
                                  ORDER BY a.created_at DESC');
        } else {
            $ins = DB::select('SELECT a.id, b.name AS payment_type, a.amount, c.day, c.payer, c.responsible, c.reason, a.in_words
                               FROM (stocks a, payment_types b)
                               INNER JOIN invoices c ON a.invoice_id=c.id
                               WHERE a.inout_type=? AND a.payment_type=b.id AND c.status=1
                               ORDER BY a.created_at DESC', [$inout_type]); // stocklar select qilinadi
        }
        
        $inout_types = DB::select('SELECT id, name FROM inout_types WHERE action=1');
        $payment_types = DB::select('SELECT id, name FROM payment_types');

        return view('cashier.ins.index', compact('inout_types', 'payment_types', 'orders', 'ins', 'inout_type'));
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

        return redirect()->route('ins', $inout_type=1);
    }

    public function show_order_payments($invoice_id)
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

        $payment_types = DB::select('SELECT id, name FROM payment_types');

        return view('cashier.ins.show_order_payment', compact('orders', 'payment_types'));
    }

    
    public function update_order_payment(Request $request, $id)
    {
        Stock::where('id', $id)->update([
            'payment_type' => $request->payment_type,
            'amount'       => $request->amount,
            'in_words'     => NumberToWords::transformNumber('ru', $request->amount) . ' сум'
        ]);

        return redirect()->route('ins', $inout_type=1);
    }

    public function store_stock_ins(Request $request)
    {
        $request->validate([
            'amount' => 'required',
            'responsible' => 'required',
            'payer' => 'required'
        ]);

        $invoice = Invoice::create([
            'payer'       => $request->payer,
            'responsible' => $request->responsible,
            'amount'      => $request->amount,  
            'day'         => date('Y-m-d'),
            'reason'      => $request->reason,
            'status'      => 1
        ]);

        Stock::create([
            'invoice_id'   => $invoice->id,
            'inout_type'   => $request->inout_type,
            'payment_type' => $request->payment_type,
            'amount'       => $request->amount,
            'in_words'     => NumberToWords::transformNumber('ru', $request->amount) . ' сум',
            'day'          => date('Y-m-d')
        ]);

        return redirect()->route('ins', $request->inout_type);
    }
}
