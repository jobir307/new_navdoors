<?php

namespace App\Http\Controllers\cashier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Invoice;
use App\Models\Stock;
use NumberToWords\NumberToWords;

class OutsController extends Controller
{
    public function index($inout_type)
    {
        $inout_types = DB::select('SELECT id, name FROM inout_types WHERE action=-1');
        $payment_types = DB::select('SELECT id, name FROM payment_types');
        $outs = DB::select('SELECT a.id, 
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

        return view('cashier.outs.index', compact('inout_types', 'payment_types', 'outs', 'inout_type'));
    }

    public function store_stock_outs(Request $request)
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

        return redirect()->route('outs', $request->inout_type);
    }
}
