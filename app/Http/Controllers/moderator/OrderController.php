<?php
namespace App\Http\Controllers\moderator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Doortype;
use App\Models\Door;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $new_orders = DB::select('SELECT a.id, 
                                         a.phone_number, 
                                         a.contract_number, 
                                         a.deadline, 
                                         b.name as customer,
                                         j.name as job_name,
                                         c.name as doortype
                                  FROM (orders a, customers b, doortypes c)
                                  LEFT JOIN jobs j ON a.job_id=j.id
                                  INNER JOIN doors d ON c.id=d.doortype_id
                                  WHERE a.door_id=d.id AND a.customer_id=b.id AND a.moderator_receive=0 AND a.moderator_send=0 AND a.manager_status=1
                                  ORDER BY a.updated_at DESC');
        
        $inprocess_orders = DB::select('SELECT a.id, 
                                               a.phone_number, 
                                               a.contract_number, 
                                               a.deadline, 
                                               b.name as customer,
                                               j.name as job_name,
                                               c.name as doortype
                                        FROM (orders a, customers b, doortypes c)
                                        LEFT JOIN jobs j ON a.job_id=j.id
                                        INNER JOIN doors d ON c.id=d.doortype_id
                                        WHERE a.door_id=d.id AND a.customer_id=b.id AND a.moderator_receive=1 AND a.moderator_send=0 AND a.manager_status=1
                                        ORDER BY a.updated_at DESC');
        
        $completed_orders = DB::select('SELECT a.id, 
                                               a.phone_number, 
                                               a.contract_number, 
                                               a.deadline, 
                                               b.name as customer,
                                               j.name as job_name,
                                               c.name as doortype
                                        FROM (orders a, customers b, doortypes c)
                                        LEFT JOIN jobs j ON a.job_id=j.id
                                        INNER JOIN doors d ON c.id=d.doortype_id
                                        WHERE a.door_id=d.id AND a.customer_id=b.id AND a.moderator_receive=1 AND a.moderator_send=1 AND a.manager_status=1
                                        ORDER BY a.updated_at DESC');

        $jobs = DB::select('SELECT id, name FROM jobs');
        
        return view('moderator.order.index', compact('new_orders', 'inprocess_orders', 'completed_orders', 'jobs'));
    }

    // Jarayonni boshlash (naryad yangidan jarayondagiga o'tadi)
    public function start_process(Request $request)
    {
        Order::where('id', $request->order_id)->update([
            'moderator_receive' => 1,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        $door_id = Order::find($request->order_id)->door_id;
        $doortype_id = Door::find($door_id)->doortype_id;
        $doortype_jobs_ids = explode(",", Doortype::find($doortype_id)->jobs); 
        
        foreach ($doortype_jobs_ids as $key => $value) {
                DB::insert('INSERT INTO order_processes(order_id, job_id) VALUES(?,?)', [$request->order_id, $value]);
        }

        return redirect()->route('moderator');
    }

    // Naryad qanaqa holatda ekanligini boshqarish 
    public function form_outfit ($order_id)
    {
        $order_processes = DB::select('SELECT c.id,
                                              c.order_id,
                                              a.contract_number,  
                                              c.job_id,
                                              j.name as job_name, 
                                              c.worker_id,
                                              c.started, 
                                              c.done,
                                              b.name as doortype
                                       FROM (orders a, jobs j, doors d) 
                                       INNER JOIN doortypes b ON b.id=d.doortype_id AND a.door_id=d.id
                                       RIGHT JOIN order_processes c ON c.order_id=a.id AND c.job_id=j.id
                                       WHERE a.id=?', [$order_id]);
                                       
        $carrier_drivers = DB::select('SELECT a.id, 
                                              a.name as driver, 
                                              b.name as car_model,
                                              a.gov_number,
                                              a.type
                                       FROM drivers a
                                       INNER JOIN car_models b ON a.carmodel_id=b.id
                                       WHERE a.active=1 AND a.type="carrier"');
        
        $company_drivers = DB::select('SELECT a.id, 
                                              a.name as driver, 
                                              b.name as car_model,
                                              a.gov_number,
                                              a.type
                                       FROM drivers a
                                       INNER JOIN car_models b ON a.carmodel_id=b.id
                                       WHERE a.active=1 AND a.type="company"');

        return view('moderator.order.outfit', compact('order_processes', 'company_drivers', 'carrier_drivers'));
    }

    // Har bir naryad bo'yicha ishchilarni biriktirish 
    public function set_worker(Request $request)
    {
        DB::table('order_processes')->where('id', $request->order_process)->update([
            'worker_id' => $request->worker_id
        ]);

        return response()->json([
            'message' => "Naryad bo'yicha xodim muvaffaqiyatli saqlandi."
        ]);
    }

    // Har bir ishchining naryadini alohida-alohida boshlash
    public function start_outfit(Request $request)
    {
        $query = DB::table('order_processes')->where('id', $request->order_process);
        $query->update([
            'started' => 1
        ]);
        $result = $query->first();

        Order::where('id', $result->order_id)->update([
            'job_id' => $result->job_id
        ]);

        return response()->json([
            'message' => "Naryad muvaffaqiyatli boshlandi."
        ]);
    }

    // Har bir ishchining naryadini alohida-alohida tugatish
    public function end_outfit(Request $request)
    {
        $query = DB::table('order_processes')->where('id', $request->order_process);
        $query->update([
            'done' => 1
        ]);

        return response()->json([
            'message' => "Naryad muvaffaqiyatli yakunlandi."
        ]);   
    }
}
