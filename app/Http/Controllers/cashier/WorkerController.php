<?php

namespace App\Http\Controllers\cashier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Worker;
use App\Models\Invoice;
use App\Models\Stock;
use NumberToWords\NumberToWords;

use Illuminate\Support\Facades\Auth;

class WorkerController extends Controller
{
    public function index()
    {
        $workers = DB::select('SELECT a.id,
                                      a.fullname, 
                                      a.phone_number,
                                      group_concat(c.name) AS jobs,
                                      (SELECT SUM(salary)
                                       FROM order_processes
                                       WHERE a.id=worker_id AND paid=1 AND cashier_paid=0
                                      ) as total_salary
                                FROM (workers a, jobs c )
                                INNER JOIN worker_jobs b ON a.id=b.worker_id AND b.job_id=c.id
                                WHERE a.active=1
                                GROUP BY b.worker_id
                                ORDER BY a.fullname');

        return view('cashier.worker.index', compact('workers'));
    }

    public function salary($worker_id)
    {
        $worker = Worker::find($worker_id);
        $notpaid_salaries = DB::select('SELECT a.id AS order_id, 
                                                d.id,
                                                a.contract_number,
                                                c.name AS job,
                                                CASE
                                                    WHEN d.product = "door" THEN "Eshik"
                                                    WHEN d.product = "jamb" THEN "Nalichnik"
                                                    WHEN d.product = "transom" THEN "Dobor"
                                                    WHEN d.product = "crown" THEN "Korona"
                                                    WHEN d.product = "cube" THEN "Kubik"
                                                    ELSE "Sapog"
                                                END AS order_process_product,
                                                d.product_count,
                                                SUM(d.salary) AS salary,
                                                d.paid_time
                                        FROM (orders a, workers b, jobs c)
                                        LEFT JOIN order_processes d ON (d.order_id=a.id AND b.id=d.worker_id AND c.id=d.job_id)
                                        WHERE a.moderator_send=1 AND 
                                            d.started=1 AND 
                                            d.done=1 AND
                                            b.active=1 AND 
                                            d.paid=1 AND
                                            d.cashier_paid=0 AND 
                                            d.worker_id=?
                                        GROUP BY d.worker_id, d.order_id, d.job_id, d.product
                                        ORDER BY d.paid_time DESC', [$worker_id]);

        $paid_salaries = DB::select('SELECT a.id, 
                                            SUM(d.salary) AS salary,
                                            d.cashier_paid_time
                                     FROM (stocks a, workers b)
                                     LEFT JOIN order_processes d ON (d.stock_id=a.id AND b.id=d.worker_id)
                                     WHERE d.started=1 AND 
                                        d.done=1 AND
                                        b.active=1 AND 
                                        d.paid=1 AND
                                        d.cashier_paid=1 AND 
                                        d.worker_id=?
                                     GROUP BY d.stock_id
                                     ORDER BY d.paid_time DESC', [$worker_id]);
        $data = array(
            'notpaid_salaries' => $notpaid_salaries,
            'paid_salaries'    => $paid_salaries,
            'worker'           => $worker
        );

        return view('cashier.worker.salary', $data);
    }

    public function pay_salary(Request $request)
    {
        if (!empty($request->orderprocess_id)) {
            $worker = Worker::find($request->worker_id);
            $query = DB::table('order_processes')->whereIn('id', $request->orderprocess_id);
            
            $order_processes = $query->get();
            $total_salary = 0;
            foreach ($order_processes as $key => $value) {
                $total_salary += $value->salary;
            }

            $invoice = Invoice::create([
                'payer'       => "Kassa",
                'responsible' => Auth::user()->username,
                'amount'      => $total_salary,
                'day'         => date('Y-m-d'),
                'reason'      => $worker->fullname . "ning oylik maoshi",
                'status'      => 1
            ]);
    
            $stock = Stock::create([
                'invoice_id'   => $invoice->id,
                'inout_type'   => 9, // naryadchilar bilan hisob-kitob
                'payment_type' => 1, // naqd
                'amount'       => $total_salary,
                'in_words'     => NumberToWords::transformNumber('ru', $total_salary) . ' сум',
                'day'          => date('Y-m-d')
            ]);

            $query->update([
                'cashier_paid' => 1,
                'cashier_paid_time' => date("Y-m-d H:i:s"),
                'stock_id' => $stock->id
            ]);
        }

        return redirect()->route('cashier-worker-salaries', ['worker_id' => $request->worker_id]);
    }

    public function show_stock_details($stock_id)
    {
        $details = DB::select('SELECT a.id AS order_id, 
                                      a.contract_number,
                                      c.name AS job,
                                      CASE
                                          WHEN d.product = "door" THEN "Eshik"
                                          WHEN d.product = "jamb" THEN "Nalichnik"
                                          WHEN d.product = "transom" THEN "Dobor"
                                          WHEN d.product = "crown" THEN "Korona"
                                          WHEN d.product = "cube" THEN "Kubik"
                                          ELSE "Sapog"
                                      END AS order_process_product,
                                      d.product_count,
                                      SUM(d.salary) AS salary,
                                      d.paid_time,
                                      d.cashier_paid_time
                                FROM (orders a, workers b, jobs c)
                                LEFT JOIN order_processes d ON (d.order_id=a.id AND b.id=d.worker_id AND c.id=d.job_id)
                                WHERE a.moderator_send=1 AND 
                                    d.started=1 AND 
                                    d.done=1 AND
                                    b.active=1 AND 
                                    d.paid=1 AND
                                    d.cashier_paid=1 AND 
                                    d.stock_id=?
                                GROUP BY d.worker_id, d.order_id, d.job_id, d.product
                                ORDER BY d.paid_time DESC', [$stock_id]);
        return view('cashier.worker.stock_details', compact('details'));
    }
}
