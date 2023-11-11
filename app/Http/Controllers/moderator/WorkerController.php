<?php

namespace App\Http\Controllers\moderator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Worker;

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
        
        return view('moderator.worker.index', compact('workers'));
    }

    public function salary($worker_id)
    {
        $worker = Worker::find($worker_id);
        $in_process_orders_salaries = DB::select('SELECT a.id AS order_id, 
                                                         a.contract_number,
                                                         c.name AS job,
                                                         a.job_name,
                                                         CASE
                                                            WHEN d.product = "door" THEN "Eshik"
                                                            WHEN d.product = "jamb" THEN "Nalichnik"
                                                            WHEN d.product = "nsjamb" THEN "NS Nalichnik"
                                                            WHEN d.product = "transom" THEN "Dobor"
                                                            WHEN d.product = "crown" THEN "Korona"
                                                            WHEN d.product = "cube" THEN "Kubik"
                                                            ELSE "Sapog"
                                                         END AS order_process_product,
                                                         d.product_count,
                                                         SUM(d.salary) AS salary,
                                                         d.paid      
                                                 FROM (orders a, workers b, jobs c)
                                                 LEFT JOIN order_processes d ON (d.order_id=a.id AND b.id=d.worker_id AND c.id=d.job_id)
                                                 WHERE a.moderator_send=0 AND 
                                                       d.started=1 AND 
                                                       d.done=1 AND
                                                       b.active=1 AND 
                                                       d.paid=0 AND
                                                       d.worker_id=?
                                                 GROUP BY d.worker_id, d.order_id, d.job_id, d.product
                                                 ORDER BY d.order_id', [$worker_id]);

        $completed_orders_salaries = DB::select('SELECT d.id,
                                                        a.id AS order_id, 
                                                        a.contract_number,
                                                        a.moderator_send_time AS completed_time,
                                                        c.name AS job,
                                                        CASE
                                                            WHEN d.product = "door" THEN "Eshik"
                                                            WHEN d.product = "jamb" THEN "Nalichnik"
                                                            WHEN d.product = "nsjamb" THEN "NS Nalichnik"
                                                            WHEN d.product = "transom" THEN "Dobor"
                                                            WHEN d.product = "crown" THEN "Korona"
                                                            WHEN d.product = "cube" THEN "Kubik"
                                                            ELSE "Sapog"
                                                        END AS order_process_product,
                                                        d.product_count,
                                                        SUM(d.salary) AS salary
                                                FROM (orders a, workers b, jobs c)
                                                LEFT JOIN order_processes d ON (d.order_id=a.id AND b.id=d.worker_id AND c.id=d.job_id)
                                                WHERE a.moderator_send=1 AND 
                                                    d.started=1 AND 
                                                    d.done=1 AND
                                                    b.active=1 AND 
                                                    d.paid=0 AND
                                                    d.cashier_paid=0 AND
                                                    d.worker_id=? 
                                                GROUP BY d.worker_id, d.order_id, d.job_id, d.product
                                                ORDER BY d.order_id', [$worker_id]);

        $send_payment_orders_salaries = DB::select('SELECT a.id AS order_id, 
                                                            a.contract_number,
                                                            c.name AS job,
                                                            CASE
                                                                WHEN d.product = "door" THEN "Eshik"
                                                                WHEN d.product = "jamb" THEN "Nalichnik"
                                                                WHEN d.product = "nsjamb" THEN "NS Nalichnik"
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

        $paid_orders_salaries = DB::select('SELECT a.id, 
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
            'in_process_orders_salaries'   => $in_process_orders_salaries,
            'completed_orders_salaries'    => $completed_orders_salaries,
            'send_payment_orders_salaries' => $send_payment_orders_salaries,
            'paid_orders_salaries'         => $paid_orders_salaries,
            'worker'                       => $worker
        );

        return view('moderator.worker.salary', $data);
    }

    public function pay_salary(Request $request)
    {
        if (!is_null($request->orderprocess_id)) {
            DB::table('order_processes')->whereIn('id', $request->orderprocess_id)->update([
                'paid' => 1,
                'paid_time' => date("Y-m-d H:i:s")
            ]);
        }

        return redirect()->route('worker-salaries', ['worker_id' => $request->worker_id]);
    }

    public function show_stock_details($stock_id)
    {
        $details = DB::select('SELECT a.id AS order_id, 
                                      a.contract_number,
                                      c.name AS job,
                                      CASE
                                          WHEN d.product = "door" THEN "Eshik"
                                          WHEN d.product = "jamb" THEN "Nalichnik"
                                          WHEN d.product = "nsjamb" THEN "NS Nalichnik"
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
                                
        return view('moderator.worker.stock_details', compact('details'));
    }
}
