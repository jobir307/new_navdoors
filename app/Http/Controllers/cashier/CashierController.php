<?php

namespace App\Http\Controllers\cashier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CashierController extends Controller
{
    public function index($date)
    {
        $incomes = DB::select('SELECT SUM(a.amount) income, b.name AS inout_type
                               FROM stocks a, inout_types b
                               WHERE a.inout_type=b.id AND b.action=1 AND a.day=?
                               GROUP BY a.inout_type', [$date]);

        $expenses = DB::select('SELECT SUM(a.amount) expense, b.name AS inout_type
                                FROM stocks a, inout_types b
                                WHERE a.inout_type=b.id AND b.action=-1 AND a.day=?
                                GROUP BY a.inout_type', [$date]);
        $data = DB::select('SELECT SUM(CASE 
                                       WHEN b.action=-1 THEN a.amount 
                                       ELSE 0
                                       END
                                      ) AS expense, 
                                   SUM(CASE
                                       WHEN b.action=1 THEN a.amount 
                                       ELSE 0
                                       END
                                      ) AS income, a.day,
                                   (SELECT SUM(CASE 
                                           WHEN c.action=1 THEN d.amount 
                                           ELSE 0
                                           END
                                          )-SUM(CASE 
                                                WHEN c.action=-1 THEN d.amount 
                                                ELSE 0
                                                END
                                               )
                                    FROM stocks d
                                    RIGHT JOIN inout_types c ON d.inout_type=c.id
                                    WHERE d.day <= a.day
                                   ) AS saldo
                            FROM stocks a
                            RIGHT JOIN inout_types b ON a.inout_type=b.id
                            WHERE a.day=?', [$date]);

        return view('cashier.dashboard', compact('incomes', 'expenses', 'date', 'data'));
    }
}
