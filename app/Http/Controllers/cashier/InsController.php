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
        $ins = DB::select('SELECT a.id, 
                                  b.name AS payment_type, 
                                  a.amount, 
                                  c.day, 
                                  c.payer, 
                                  c.responsible, 
                                  c.reason, 
                                  a.in_words
                           FROM (stocks a, payment_types b)
                           INNER JOIN invoices c ON a.invoice_id=c.id
                           WHERE a.inout_type=? AND a.payment_type=b.id AND c.status=1
                           ORDER BY a.created_at DESC', [$inout_type]); // stocklar select qilinadi
        $inout_types = DB::select('SELECT id, name FROM inout_types WHERE action=1 AND id<>1');
        $payment_types = DB::select('SELECT id, name FROM payment_types');

        return view('cashier.ins.index', compact('ins', 'inout_types', 'payment_types', 'inout_type'));
    }

    public function update_order_payment(Request $request, $id)
    {
        Stock::where('id', $id)->update([
            'payment_type' => $request->payment_type,
            'amount'       => $request->amount,
            'in_words'     => NumberToWords::transformNumber('ru', $request->amount) . ' сум'
        ]);

        return redirect()->route('ins', $inout_type=2);
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
