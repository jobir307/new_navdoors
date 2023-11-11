<?php
namespace App\Http\Controllers\moderator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Doortype;
use App\Models\Door;
use App\Models\Order;
use App\Models\Transom;
use App\Models\Jamb;
use App\Models\NSJamb;
use App\Models\Job;
use App\Models\Invoice;
use App\Models\Crown;
use App\Models\Cube;
use App\Models\Boot;

class OrderController extends Controller
{
    public function index()
    {
        $new_orders = DB::select('SELECT a.id, 
                                         a.phone_number, 
                                         a.contract_number, 
                                         a.deadline, 
                                         b.name AS customer,
                                         a.created_at AS when_created,
                                         a.manager_verified_time AS verified_time,
                                         CASE
                                            WHEN a.product = "door" THEN "Eshik"
                                            WHEN a.product = "jamb" THEN "Nalichnik"
                                            WHEN a.product = "nsjamb" THEN "NS nalichnik"
                                            WHEN a.product = "transom" THEN "Dobor"
                                            WHEN a.product = "jamb+transom" THEN "Nalichnik+dobor"
                                            ELSE "NKKS"
                                         END AS product,
                                         a.who_created_username AS who_created
                                  FROM (orders a, customers b)
                                  LEFT JOIN doors d ON a.door_id=d.id
                                  LEFT JOIN invoices i ON a.id=i.order_id
                                  WHERE a.customer_id=b.id AND a.moderator_receive=0 AND a.moderator_send=0 AND a.manager_status=1 AND i.status=1
                                  ORDER BY a.deadline');
        
        $inprocess_orders = DB::select('SELECT a.id, 
                                               a.phone_number, 
                                               a.contract_number, 
                                               a.deadline, 
                                               b.name AS customer,
                                               a.job_name,
                                               a.created_at AS when_created,
                                               a.manager_verified_time AS verified_time,
                                               DATEDIFF(a.deadline, ?) AS day_diff,
                                               CASE
                                                    WHEN a.product = "door" THEN "Eshik"
                                                    WHEN a.product = "jamb" THEN "Nalichnik"
                                                    WHEN a.product = "nsjamb" THEN "NS nalichnik"
                                                    WHEN a.product = "transom" THEN "Dobor"
                                                    WHEN a.product = "jamb+transom" THEN "Nalichnik+dobor"
                                                    ELSE "NKKS"
                                                END AS product,
                                                a.who_created_username AS who_created
                                        FROM (orders a, customers b)
                                        LEFT JOIN doors d ON a.door_id=d.id
                                        LEFT JOIN invoices i ON a.id=i.order_id
                                        WHERE a.customer_id=b.id AND a.moderator_receive=1 AND a.moderator_send=0 AND a.manager_status=1 AND i.status=1
                                        ORDER BY day_diff, a.created_at', [date('Y-m-d')]);
        
        $completed_orders = DB::select('SELECT a.id, 
                                               a.phone_number, 
                                               a.contract_number, 
                                               a.deadline, 
                                               b.name AS customer,
                                               a.job_name,
                                               a.created_at AS when_created,
                                               a.manager_verified_time AS verified_time,
                                               a.moderator_send_time,
                                               CASE
                                                    WHEN a.product = "door" THEN "Eshik"
                                                    WHEN a.product = "jamb" THEN "Nalichnik"
                                                    WHEN a.product = "nsjamb" THEN "NS nalichnik"
                                                    WHEN a.product = "transom" THEN "Dobor"
                                                    WHEN a.product = "jamb+transom" THEN "Nalichnik+dobor"
                                                    ELSE "NKKS"
                                               END AS product,
                                               a.who_created_username AS who_created
                                        FROM (orders a, customers b)
                                        LEFT JOIN doors d ON a.door_id=d.id
                                        LEFT JOIN invoices i ON a.id=i.order_id
                                        WHERE a.customer_id=b.id AND a.moderator_receive=1 AND a.moderator_send=1 AND a.manager_status=1 AND i.status=1
                                        ORDER BY a.moderator_send_time DESC');

        $jobs = DB::select('SELECT id, name FROM jobs');
        
        return view('moderator.order.index', compact('new_orders', 'inprocess_orders', 'completed_orders', 'jobs'));
    }
    // naryadda xatoliklar bo'lsa sotuv bo'limiga qaytarish
    public function redirect_back_order(Request $request)
    {
        $order = Order::find($request->order_id);
        Invoice::where('order_id', $request->order_id)->update([
            'status' => 0
        ]);

        return redirect()->route('moderator');
    }

    // Jarayonni boshlash (naryad yangidan jarayondagiga o'tadi)
    public function start_process(Request $request)
    {
        $order = Order::find($request->order_id);

        $order->update([
            'moderator_receive' => 1
        ]);
        
        switch ($order->product) {
            case 'jamb':
                $product = "jamb";
                $jamb_id = DB::table('jamb_results')->where('order_id', $order->id)->first()->jamb_id;
                $data = explode(",", Jamb::find($jamb_id)->jobs);
                $process_jobs = DB::table('jobs')->whereIn('id', $data)->get(['id']);
                if (!empty($process_jobs)) {
                    foreach ($process_jobs as $key => $value) {
                        DB::insert('INSERT INTO order_processes(product, order_id, job_id) VALUES(?,?,?)', [$product, $order->id, $value->id]);
                    }
                }
                break;
            case 'nsjamb':
                $product = "nsjamb";
                $nsjamb_id = DB::table('nsjamb_results')->where('order_id', $order->id)->first()->nsjamb_id;
                $data = explode(",", NSJamb::find($nsjamb_id)->jobs);
                $process_jobs = DB::table('jobs')->whereIn('id', $data)->get(['id']);
                if (!empty($process_jobs)) {
                    foreach ($process_jobs as $key => $value) {
                        DB::insert('INSERT INTO order_processes(product, order_id, job_id) VALUES(?,?,?)', [$product, $order->id, $value->id]);
                    }
                }
                break;
            case 'transom':
                $product = "transom";
                $transom_id = DB::table('transom_results')->where('order_id', $order->id)->first()->transom_id;
                $data = explode(",", Transom::find($transom_id)->jobs);
                $process_jobs = DB::table('jobs')->whereIn('id', $data)->get(['id']);
                if (!empty($process_jobs)) {
                    foreach ($process_jobs as $key => $value) {
                        DB::insert('INSERT INTO order_processes(product, order_id, job_id) VALUES(?,?,?)', [$product, $order->id, $value->id]);
                    }
                }
                break;
            case 'jamb+transom':
                $product = "jamb";
                $jamb_id = DB::table('jamb_results')->where('order_id', $order->id)->first()->jamb_id;
                $data = explode(",", Jamb::find($jamb_id)->jobs);
                $process_jobs = DB::table('jobs')->whereIn('id', $data)->get(['id']);
                $product2 = "transom";
                $transom_id = DB::table('transom_results')->where('order_id', $order->id)->first()->transom_id;
                $data2 = explode(",", Transom::find($transom_id)->jobs);
                $process_jobs2 = DB::table('jobs')->whereIn('id', $data2)->get(['id']);
                if (!empty($process_jobs)) {
                    foreach ($process_jobs as $key => $value) {
                        DB::insert('INSERT INTO order_processes(product, order_id, job_id) VALUES(?,?,?)', [$product, $order->id, $value->id]);
                    }
                }
                if (!empty($process_jobs2)) {
                    foreach ($process_jobs2 as $key => $value) {
                        DB::insert('INSERT INTO order_processes(product, order_id, job_id) VALUES(?,?,?)', [$product2, $order->id, $value->id]);
                    }
                }
                break;
            case 'door':
                $product = "door";
                // zamokka tekshirish (eshik zamoksiz zakaz qilingan bo'lsa zamokchini naryadga qo'shmaymiz)
                $locktype_false = 0;
                $glasstype_false = 0;
                $door_results = DB::table('door_results')->where('order_id', $order->id)->get();
                $door = Door::find($door_results[0]->door_id);
                $crowns = json_decode($door->crown_parameters, true);
                $cube_parameters = json_decode($door->cube_parameters, true);
                $boot_parameters = json_decode($door->boot_parameters, true);
                if (!empty($cube_parameters))
                    $cubes = array_reduce($cube_parameters, 'array_merge', array());

                if (!empty($boot_parameters))
                    $boots = array_reduce($boot_parameters, 'array_merge', array());

                foreach($door_results as $key => $value){
                    if (!empty($value->lock_type))
                        $locktype_false++;
                    if (!empty($value->glass_type))
                        $glasstype_false++;
                }
                $door = Door::find($order->door_id);
                $data = explode(",", Doortype::find($door->doortype_id)->jobs);
                $process_jobs = DB::table('jobs')->whereIn('id', $data)->get(['id']);
                if (!empty($process_jobs)) {
                    foreach ($process_jobs as $key => $value) {
                        if ($locktype_false != 0 && $glasstype_false != 0)
                            DB::insert('INSERT INTO order_processes(product, order_id, job_id) VALUES(?,?,?)', [$product, $order->id, $value->id]);
                        else {
                            if ($locktype_false == 0 && $glasstype_false != 0) { // bu serverdagi baza bilan tugri ishlaydi (bazadagi zamokchini id=8)
                                if ($value->id != 8) 
                                    DB::insert('INSERT INTO order_processes(product, order_id, job_id) VALUES(?,?,?)', [$product, $order->id, $value->id]);
                            } else if ($glasstype_false == 0 && $locktype_false != 0) { // bu serverdagi baza bilan tugri ishlaydi (bazadagi shisha o'rnatish ni id=10)
                                if ($value->id != 10)
                                    DB::insert('INSERT INTO order_processes(product, order_id, job_id) VALUES(?,?,?)', [$product, $order->id, $value->id]);
                            } else {
                                if (!in_array($value->id, array(8, 10)))
                                    DB::insert('INSERT INTO order_processes(product, order_id, job_id) VALUES(?,?,?)', [$product, $order->id, $value->id]);
                            }
                        }
                    }
                }

                $check_jamb = false;
                $check_transom = false;
                $check_crown = false;
                $check_cube = false;
                $check_boot = false;

                foreach($door_results as $key => $value) {
                    $jamb_false = 0;
                    $door_jambs = json_decode($value->jamb, true);
                    if (!empty($door_jambs)) {
                        foreach ($door_jambs as $k => $v) {
                            if (!empty($v['id']))
                            $jamb_false++;
                        }
                    }
                    
                    if ($jamb_false != 0) {
                        $product2 = "jamb";
                        foreach($door_jambs as $k => $v) {
                            $jamb = Jamb::find($v['id']);
                            if (isset($jamb)) {
                                $data = explode(",", $jamb->jobs);
                                $process_jobs2 = DB::table('jobs')->whereIn('id', $data)->get(['id']);
                            }
                            
                            if (isset($process_jobs2)) {
                                if (!$check_jamb) {
                                    foreach ($process_jobs2 as $k2 => $v2) {
                                        DB::insert('INSERT INTO order_processes(product, order_id, job_id) VALUES(?,?,?)', [$product2, $order->id, $v2->id]);
                                    }
                                    $check_jamb = true;
                                }
                            }
                            if ($check_jamb)
                                break;
                        }
                    }

                    if (!empty($value->transom_name)) {
                        $product3 = "transom";
                        $transom = Transom::where('doortype_id', $door->doortype_id)->first();
                        if (!empty($transom)) {
                            $data = explode(",", $transom->jobs);
                            $process_jobs3 = DB::table('jobs')->whereIn('id', $data)->get(['id']);
                        }
                        if (isset($process_jobs3)) {
                            if (!$check_transom) {
                                foreach ($process_jobs3 as $k3 => $v3) {
                                    DB::insert('INSERT INTO order_processes(product, order_id, job_id) VALUES(?,?,?)', [$product3, $order->id, $v3->id]);
                                }
                                $check_transom = true;
                            }
                        }
                    }
                    if (!empty($crowns) && !empty($crowns[0]['id'])) {
                        $crown = Crown::find($crowns[0]['id']);
                        if (!empty($crown)) {
                            $product4 = "crown";
                            $data = explode(",", $crown->jobs);
                            $process_jobs4 = DB::table('jobs')->whereIn('id', $data)->get(['id']);
                        }
                        if (isset($process_jobs4)) {
                            if (!$check_crown) {
                                foreach ($process_jobs4 as $k4 => $v4) {
                                    DB::insert('INSERT INTO order_processes(product, order_id, job_id) VALUES(?,?,?)', [$product4, $order->id, $v4->id]);
                                }
                                $check_crown = true;
                            }
                        }
                    }
                    if (isset($cubes)) {
                        $cube = Cube::find($cubes[0]['id']);
                        if (!empty($cube)) {
                            $product5 = "cube";
                            $data = explode(",", $cube->jobs);
                            $process_jobs5 = DB::table('jobs')->whereIn('id', $data)->get(['id']);
                        }
                        if (isset($process_jobs5)) {
                            if (!$check_cube) {
                                foreach ($process_jobs5 as $k5 => $v5) {
                                    DB::insert('INSERT INTO order_processes(product, order_id, job_id) VALUES(?,?,?)', [$product5, $order->id, $v5->id]);
                                }
                                $check_cube = true;
                            }
                        }
                    }
                    if (isset($boots)){
                        $boot = Boot::find($boots[0]['id']);
                        if (!empty($boot)) {
                            $product5 = "boot";
                            $data = explode(",", $boot->jobs);
                            $process_jobs6 = DB::table('jobs')->whereIn('id', $data)->get(['id']);
                        }
                        if (isset($process_jobs6)) {
                            if (!$check_boot) {
                                foreach ($process_jobs6 as $k6 => $v6) {
                                    DB::insert('INSERT INTO order_processes(product, order_id, job_id) VALUES(?,?,?)', [$product5, $order->id, $v6->id]);
                                }
                                $check_boot = true;
                            }
                        }
                    }

                    if ($check_jamb && $check_transom && $check_crown && $check_cube  && $check_boot)
                        break;
                }
                break;
            default:
                $product1 = "crown";
                $crown_results = DB::table('crown_results')->where('order_id', $order->id)->first();
                if (isset($crown_results)) {
                    $data = explode(",", Crown::find($crown_results->crown_id)->jobs);
                    $crown_jobs = DB::table('jobs')->whereIn('id', $data)->get(['id']);
                    if (!empty($crown_jobs)) {
                        foreach ($crown_jobs as $key => $value) {
                            DB::insert('INSERT INTO order_processes(product, order_id, job_id) VALUES(?,?,?)', [$product1, $order->id, $value->id]);
                        }
                    }
                }

                $product2 = "boot";
                $boot_results = DB::table('boot_results')->where('order_id', $order->id)->first();
                if (isset($boot_results)) {
                    $data2 = explode(",", Boot::find($boot_results->boot_id)->jobs);
                    $boot_jobs = DB::table('jobs')->whereIn('id', $data2)->get(['id']);
                    if (!empty($boot_jobs)) {
                        foreach ($boot_jobs as $key => $value) {
                            DB::insert('INSERT INTO order_processes(product, order_id, job_id) VALUES(?,?,?)', [$product2, $order->id, $value->id]);
                        }
                    }
                }

                $product3 = "cube";
                $cube_results = DB::table('cube_results')->where('order_id', $order->id)->first();
                if (isset($cube_results)) {
                    $data3 = explode(",", Cube::find($cube_results->cube_id)->jobs);
                    $cube_jobs = DB::table('jobs')->whereIn('id', $data3)->get(['id']);
                    if (!empty($cube_jobs)) {
                        foreach ($cube_jobs as $key => $value) {
                            DB::insert('INSERT INTO order_processes(product, order_id, job_id) VALUES(?,?,?)', [$product3, $order->id, $value->id]);
                        }
                    }
                }
                break;

                $product4 = "jamb";
                $jamb_results = DB::table('jamb_results')->where('order_id', $order->id)->first();
                if (isset($jamb_results)) {
                    $data4 = explode(",", Jamb::find($jamb_results->jamb_id)->jobs);
                    $jamb_jobs = DB::table('jobs')->whereIn('id', $data4)->get(['id']);
                    if (!empty($jamb_jobs)) {
                        foreach ($jamb_jobs as $key => $value) {
                            DB::insert('INSERT INTO order_processes(product, order_id, job_id) VALUES(?,?,?)', [$product4, $order->id, $value->id]);
                        }
                    }
                }
                break;
        }

        return redirect()->route('moderator');
    }

    // Naryad qanaqa holatda ekanligini boshqarish 
    public function form_outfit ($order_id)
    {
        $order = Order::find($order_id);
        $order_processes = DB::select('SELECT c.id,
                                              c.order_id,
                                              a.contract_number, 
                                              c.job_id,
                                              j.name AS job_name, 
                                              c.worker_id,
                                              c.started, 
                                              c.started_datetime,
                                              c.done,
                                              c.done_datetime,
                                              CASE
                                                  WHEN c.product = "door" THEN "Eshik"
                                                  WHEN c.product = "jamb" THEN "Nalichnik"
                                                  WHEN c.product = "nsjamb" THEN "NS Nalichnik"
                                                  WHEN c.product = "transom" THEN "Dobor"
                                                  WHEN c.product = "crown" THEN "Korona"
                                                  WHEN c.product = "cube" THEN "Kubik"
                                                  ELSE "Sapog"
                                              END AS product
                                       FROM (orders a, jobs j) 
                                       RIGHT JOIN order_processes c ON c.order_id=a.id AND c.job_id=j.id
                                       WHERE a.id=?
                                       ORDER BY c.product', [$order_id]);

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
        $check_process_over = false;
        $order_process_over = DB::select('SELECT * 
                                          FROM order_processes 
                                          WHERE order_id=? AND (started=0 OR done=0)', [$order_processes[0]->order_id]);
        $details = array();
        
        $waybills = DB::select('SELECT  a.name as driver, 
                                        CASE 
                                            WHEN a.type = "carrier" THEN "Kuryer" 
                                            ELSE "Korxona"
                                        END AS driver_type,
                                        b.driver_id, 
                                        b._from, 
                                        b._to,
                                        b.id,
                                        a.gov_number,
                                        c.name AS car_model,
                                        b.details,
                                        b.sended_details,
                                        b.created_at
                                FROM waybills b
                                LEFT JOIN drivers a ON a.id=b.driver_id
                                INNER JOIN car_models c ON c.id=a.carmodel_id
                                WHERE a.active = 1 AND b.order_id=?
                                ORDER BY b.created_at', [$order_id]);
        if (empty($order_process_over))
            $check_process_over = true;

        switch ($order->product) {
            case 'jamb':
                $jamb_results = DB::table('jamb_results')->where('order_id', $order->id)->get();
                $product_model = $jamb_results[0]->name;
                if (empty($waybills)) {
                    foreach($jamb_results as $key => $value) {
                        $details[$key]['name'] = $value->name;
                        $details[$key]['count'] = $value->count;
                    }
                } else {
                    $all_details = json_decode($waybills[count($waybills)-1]->details, true);
                    $sended_details = json_decode($waybills[count($waybills)-1]->sended_details, true);
                    if (!empty($all_details))
                        $details = array_values($this->check_diff_sended_details($all_details, $sended_details));
                    else
                        $details = [];
                }
                
                $data = array(
                    'order'              => $order,
                    'check_process_over' => $check_process_over,
                    'order_processes'    => $order_processes,
                    'company_drivers'    => $company_drivers,
                    'carrier_drivers'    => $carrier_drivers,
                    'product_model'      => $product_model,
                    'waybills'           => $waybills,
                    'details'            => $details
                );

                break;
            case 'nsjamb':
                $nsjambs = DB::table('nsjamb_results')->where('order_id', $order->id)->get();
                $product_model = $nsjambs[0]->nsjamb_name;
                if (empty($waybills)) {
                    foreach($nsjambs as $key => $value) {
                        $details[$key]['name'] = $value->nsjamb_name . '('. $value->height . 'x' . $value->width_top . 'x' . $value->width_bottom .')';
                        $details[$key]['count'] = $value->count;
                    }
                } else {
                    $all_details = json_decode($waybills[count($waybills)-1]->details, true);
                    $sended_details = json_decode($waybills[count($waybills)-1]->sended_details, true);
                    if (!empty($all_details))
                        $details = array_values($this->check_diff_sended_details($all_details, $sended_details));
                    else
                        $details = [];
                }

                $data = array(
                    'order'              => $order,
                    'check_process_over' => $check_process_over,
                    'order_processes'    => $order_processes,
                    'company_drivers'    => $company_drivers,
                    'carrier_drivers'    => $carrier_drivers,
                    'product_model'      => $product_model,
                    'waybills'           => $waybills,
                    'details'            => $details
                );

                break;
            case 'transom':
                $transom_results = DB::table('transom_results')->where('order_id', $order->id)->get();
                $product_model = $transom_results[0]->name;
                if (empty($waybills)) {
                    foreach($transom_results as $key => $value) {
                        $details[$key]['name'] = $value->name . ' ' . $value->height .'x'. $value->width_top . 'x' . $value->width_bottom;
                        $details[$key]['count'] = $value->count;
                    }
                } else {
                    $all_details = json_decode($waybills[count($waybills)-1]->details, true);
                    $sended_details = json_decode($waybills[count($waybills)-1]->sended_details, true);
                    if (!empty($all_details))
                        $details = array_values($this->check_diff_sended_details($all_details, $sended_details));
                    else
                        $details = [];
                }

                $data = array(
                    'order'              => $order,
                    'check_process_over' => $check_process_over,
                    'order_processes'    => $order_processes,
                    'company_drivers'    => $company_drivers,
                    'carrier_drivers'    => $carrier_drivers,
                    'product_model'      => $product_model,
                    'waybills'           => $waybills,
                    'details'            => $details
                );

                break;
            case 'jamb+transom':
                $jamb_results = DB::table('jamb_results')->where('order_id', $order->id)->get();
                $transom_results = DB::table('transom_results')->where('order_id', $order->id)->get();
                $product_model = $jamb_results[0]->name;
                $product_model2 = $transom_results[0]->name;
                if (empty($waybills)) {
                    foreach($jamb_results as $key => $value) {
                        $details[$key]['name'] = $value->name;
                        $details[$key]['count'] = $value->count;
                    }
                    
                    $i = count($jamb_results);
                    
                    foreach($transom_results as $key => $value) {
                        $details[$i+$key]['name'] = $value->name . ' ' . $value->height .'x'. $value->width_top . 'x' . $value->width_bottom;
                        $details[$i+$key]['count'] = $value->count;
                    }
                } else {
                    $all_details = json_decode($waybills[count($waybills)-1]->details, true);
                    $sended_details = json_decode($waybills[count($waybills)-1]->sended_details, true);
                    if (!empty($all_details))
                        $details = array_values($this->check_diff_sended_details($all_details, $sended_details));
                    else
                        $details = [];
                }

                $data = array(
                    'order'              => $order,
                    'check_process_over' => $check_process_over,
                    'order_processes'    => $order_processes,
                    'company_drivers'    => $company_drivers,
                    'carrier_drivers'    => $carrier_drivers,
                    'product_model'      => $product_model,
                    'product_model2'     => $product_model2,
                    'waybills'           => $waybills,
                    'details'            => $details
                );

                break;
            case 'door':
                $door = DB::select('SELECT a.*, b.name AS doortype 
                                    FROM doors a 
                                    INNER JOIN doortypes b ON a.doortype_id=b.id
                                    WHERE a.id=?', [$order->door_id]);
                $product_model = $door[0]->doortype;
                if (empty($waybills)) {
                    $door_parameters = json_decode($door[0]->door_parameters, true);
                    $crown_parameters = json_decode($door[0]->crown_parameters, true);
                    $cube_parameters = json_decode($door[0]->cube_parameters, true);
                    $boot_parameters = json_decode($door[0]->boot_parameters, true);
                    // eshik turi
                    $output = array();
                    // karopka
                    $box_width_output = array();
                    $box_height_output = array();
                    // framuga
                    $framuga_output = array();
                    // burunduq soni
                    $burunduq_count = 0;
                    foreach($door_parameters as $key => $value) {
                        // Eshikni hisoblash
                        $output_element = &$output[$value['doortype'] . "_" . $value['width'] . "_" . $value['height']];
                        $output_element['name'] = $value['doortype'];
                        $output_element['width'] = $value['width'];
                        $output_element['height'] = $value['height'];
                        !isset($output_element['count']) && $output_element['count'] = 0;
                        if (!isset($output_element['name']) && !isset($output_element['width']) && !isset($output_element['height'])){
                            $output_element['count'] = $value['count'];
                        } else {
                            $output_element['count'] += $value['count'];
                        }
    
                        // Karopkani hisoblash
                        $box_output_element1 = &$box_height_output[$value['doortype'] . "_" .$value['height']];
                        $box_output_element2 = &$box_width_output[$value['doortype'] . "_" .$value['width']];
    
                        $box_output_element1['size'] = $value['height'];
                        $box_output_element1['doortype'] = $value['doortype'];
                        $box_output_element2['size'] = $value['width'];
                        $box_output_element2['doortype'] = $value['doortype'];
                        
                        !isset($box_output_element1['count']) && $box_output_element1['count'] = 0;
                        !isset($box_output_element2['count']) && $box_output_element2['count'] = 0;
                        
                        if (!isset($box_output_element1['doortype']) && !isset($box_output_element1['height']))
                            $box_output_element1['count'] = 2 * $value['count'];
                        else
                            $box_output_element1['count'] += 2 * $value['count'];
    
                        if (!isset($box_output_element2['doortype']) && !isset($box_output_element2['width'])){
                            if ($value['doorstep'] == "parogli") {
                                if (isset($value['framogafigure_name']) && !empty($value['framogafigure_name']))
                                    $box_output_element2['count'] = 3 * $value['count'];
                                else
                                    $box_output_element2['count'] = 2 * $value['count'];
                            }
                            else {
                                if (isset($value['framogafigure_name']) && !empty($value['framogafigure_name']))
                                    $box_output_element2['count'] = 2 * $value['count'];
                                else
                                    $box_output_element2['count'] = $value['count'];
                            }
                        } else {
                            if ($value['doorstep'] == "parogli") {
                                if (isset($value['framogafigure_name']) && !empty($value['framogafigure_name']))
                                    $box_output_element2['count'] += 3 * $value['count'];
                                else
                                    $box_output_element2['count'] += 2 * $value['count'];
                            }
                            else {
                                if (isset($value['framogafigure_name']) && !empty($value['framogafigure_name']))
                                    $box_output_element2['count'] += 2 * $value['count'];
                                else
                                    $box_output_element2['count'] += $value['count'];
                            }
                        }
                        // framugani hisoblash
                        if (!empty($value['framogatype_name']) && !empty($value['framogafigure_name']) && !empty($value['framoga_width']) && !empty($value['framoga_height'])) {
                            $framuga_output_element = &$framuga_output[$value['framogatype_name'] . "_" . $value['framogafigure_name'] . "_" . $value['framoga_width'] . "_" . $value['framoga_height']];
                            $framuga_output_element['type'] = $value['framogatype_name'];
                            $framuga_output_element['figure'] = $value['framogafigure_name'];
                            $framuga_output_element['width'] = $value['framoga_width'];
                            $framuga_output_element['height'] = $value['framoga_height'];
                
                            !isset($framuga_output_element['count']) && $framuga_output_element['count'] = 0;
                            if (empty($framuga_output_element['type']) && empty($framuga_output_element['figure']) && empty($framuga_output_element['height']) && empty($framuga_output_element['width'])){
                                $framuga_output_element['count'] = 0;
                            } else {
                                $framuga_output_element['count'] += $value['count'];
                            }
                        }
    
                        // burunduq hisoblash
                        if ($value['layer'] != 1)
                            $burunduq_count += 2 * $value['count'];
                    }      
                    
                    $doortypes = array_values($output);
                    $width_boxes = array_values($box_width_output);
                    $height_boxes = array_values($box_height_output);
                    $framugas = array_values($framuga_output);
                    if (!empty($doortypes)) {
                        foreach($doortypes as $key => $value) {
                            $details[$key]['name'] = 'Eshik:' . $value['name'] . $value['height'] . 'x' .$value['width'];
                            $details[$key]['count'] = $value['count'];
                        }
                    }
                    
                    $i = count($details);
                    if (!empty($width_boxes)) {
                        foreach($width_boxes as $key => $value){
                            $details[$key+$i]['name'] = 'Korobka:' . $value['doortype'] . '(' . $value['size'] . 'mm)';
                            $details[$key+$i]['count'] = $value['count'];
                        }
                    }
    
                    $i = count($details);
                    if (!empty($height_boxes)) {
                        foreach($height_boxes as $key => $value){
                            $details[$key+$i]['name'] = 'Korobka:' . $value['doortype'] . '(' . $value['size'] . 'mm)';
                            $details[$key+$i]['count'] = $value['count'];
                        }
                    }
    
                    $i = count($details);
                    if (!empty($framugas)) {
                        foreach($framugas as $key => $value){
                            $details[$key+$i]['name'] = 'Framuga turi:' . $value['type'] . '(' . $value['height'] . 'x' . $value['width'] .')';
                            $details[$key+$i]['count'] = $value['count'];
                        }
                    }
                    
                    if ($burunduq_count != 0) {
                        $i = count($details);
                        $details[$i]['name'] = "Burunduq";
                        $details[$i]['count'] = $burunduq_count;
                    }
    
                    // nalichnik
                    $jamb_parameters = json_decode($door[0]->jamb_parameters, true);
                    if (!empty($jamb_parameters)) {
                        $jambs_array = array_reduce($jamb_parameters, 'array_merge', array());
                        $jambs = array_reduce($jambs_array, function($jamb, $item){
                            if (isset($item['id']) && $item['count'] != 0) {
                                if(!isset($jamb[$item['id']])){ 
                                    $jamb[$item['id']] = [
                                        'name'        => $item['name'], 
                                        'count'       => $item['count']
                                    ]; 
                                } else {
                                    $jamb[$item['id']]['name'] = $item['name'];
                                    $jamb[$item['id']]['count'] += $item['count'];
                                }
                                return $jamb;
                            }
                        });
                    }

                    if (!empty($jambs)) {
                        $jambs = array_values($jambs);
                        $i = count($details);
                        foreach($jambs as $key => $value){
                            $details[$key+$i]['name'] = $value['name'];
                            $details[$key+$i]['count'] = $value['count'];
                        }
                    }

                    // dobor
                    $transoms = array();
                    $transom_parameters = json_decode($door[0]->transom_parameters, true);
                    if (!empty($transom_parameters)) {
                        foreach ($transom_parameters as $item) {
                            $output_element = &$transoms[$item['id'] . '_' . $item['width'] . '_' . $item['height'] . '_' . $item['thickness']];
                            if (!isset($output_element['name']) && !isset($output_element['width']) && !isset($output_element['height']) && !isset($output_element['thickness'])) {
                                $output_element['name']         = $item['name'];
                                $output_element['height_count'] = $item['height_count'];
                                $output_element['width_count']  = $item['width_count'];
                                $output_element['width']        = $item['width'];
                                $output_element['height']       = $item['height'];
                                $output_element['thickness']    = $item['thickness'];
                            } else {
                                $output_element['name'] = $item['name'];
                                $output_element['height_count'] += $item['height_count'];
                                $output_element['width_count'] += $item['width_count'];
                                $output_element['width'] = $item['width'];
                                $output_element['height'] = $item['height'];
                                $output_element['thickness'] = $item['thickness'];
                            }
                        }
                    }
                    $transoms = array_values($transoms);
                    $i = count($details);
                    foreach($transoms as $key => $value) {
                        if (!empty($value['name'])) {
                            $details[2*$key+$i]['name'] = $value['name'] . ' ' . $value['height'] . 'x' . $value['thickness'];
                            $details[2*$key+$i]['count'] = $value['height_count'];
                            $details[2*$key+$i+1]['name'] = $value['name'] . ' ' . $value['width'] . 'x' . $value['thickness'];
                            $details[2*$key+1+$i]['count'] = $value['width_count'];
                        }
                    }
    
                    $output3 = array();
                    if (!empty($crown_parameters)) {
                        foreach($crown_parameters as $key => $value) {
                            if (!empty($value['name']) && $value['total_count'] != 0) {
                                $output_element = &$output3[$value['name'] . "_" . $value['door_width']];
                                $output_element['name'] = $value['name'];
                                $output_element['door_width'] = $value['door_width'];
                    
                                if (!isset($output_element['total_count']))
                                    $output_element['total_count'] = $value['total_count'];
                                else
                                    $output_element['total_count'] += $value['total_count'];
                            }
                        }    
                    }
                    $crowns = array_values($output3);
                    $i = count($details);
                    foreach($crowns as $key => $value) {
                        $details[$key+$i]['name'] = $value['name'] . '(' . $value['door_width'] . 'mm)';
                        $details[$key+$i]['count'] = $value['total_count'];
                    }
    
                    $output4 = array();
                    if (!empty($cube_parameters)) {
                        $cubes_array = array_reduce($cube_parameters, 'array_merge', array());
                        foreach($cubes_array as $key => $value) {
                            if(is_numeric($key) && !empty($value['name']) && $value['total_count'] != 0){
                                $output_element = &$output4[$value['name']];
                                $output_element['name'] = $value['name'];
                    
                                if (!isset($output_element['total_count']))
                                    $output_element['total_count'] = $value['total_count'];
                                else
                                    $output_element['total_count'] += $value['total_count'];
                            }
                        }    
                    }
                    $cubes = array_values($output4);
    
                    $i = count($details);
                    foreach($cubes as $key => $value) {
                        $details[$key+$i]['name'] = $value['name'];
                        $details[$key+$i]['count'] = $value['total_count'];
                    }
    
                    $output5 = array();
                    if (!empty($boot_parameters)) {
                        $boots_array = array_reduce($boot_parameters, 'array_merge', array());
                        foreach($boots_array as $key => $value) {
                            if(is_numeric($key) && !empty($value['name']) && $value['total_count'] != 0){
                                $output_element = &$output5[$value['name']];
                                $output_element['name'] = $value['name'];
                    
                                if (!isset($output_element['total_count']))
                                    $output_element['total_count'] = $value['total_count'];
                                else
                                    $output_element['total_count'] += $value['total_count'];
                            }
                        }    
                    }
                    $boots = array_values($output5);
    
                    $i = count($details);
                    foreach($boots as $key => $value) {
                        $details[$key+$i]['name'] = $value['name'];
                        $details[$key+$i]['count'] = $value['total_count'];
                    }

                } else {
                    $all_details = json_decode($waybills[count($waybills)-1]->details, true);
                    $sended_details = json_decode($waybills[count($waybills)-1]->sended_details, true);
                    if (!empty($all_details))
                        $details = array_values($this->check_diff_sended_details($all_details, $sended_details));
                    else
                        $details = [];
                }
                
                $data = array(
                    'order'              => $order,
                    'check_process_over' => $check_process_over,
                    'order_processes'    => $order_processes,
                    'company_drivers'    => $company_drivers,
                    'carrier_drivers'    => $carrier_drivers,
                    'product_model'      => $product_model,
                    'waybills'           => $waybills,
                    'details'            => $details
                );
                break;
            default:
                $crown_results = DB::table('crown_results')->where('order_id', $order->id)->get();
                $product_model = $crown_results[0]->crown_name ?? "";
                $boot_results = DB::table('boot_results')->where('order_id', $order->id)->get();
                $product_model2 = $boot_results[0]->boot_name ?? "";
                $cube_results = DB::table('cube_results')->where('order_id', $order->id)->get();
                $product_model3 = $cube_results[0]->cube_name ?? "";
                $jamb_results = DB::table('jamb_results')->where('order_id', $order->id)->get();
                $product_model4 = $jamb_results[0]->name ?? "";

                if (empty($waybills)) {
                    $i = 0;
                    if (!empty($crown_results)) {
                        foreach($crown_results as $key => $value) {
                            $details[$i+$key]['name'] = $value->crown_name;
                            $details[$i+$key]['count'] = $value->count;
                        }
                    }
    
                    $i = count($details);
                    if (!empty($cube_results)) {
                        foreach($cube_results as $key => $value) {
                            $details[$i+$key]['name'] = $value->cube_name;
                            $details[$i+$key]['count'] = $value->count;
                        }
                    }
    
                    $i = count($details);
                    if (!empty($boot_results)) {
                        foreach($boot_results as $key => $value) {
                            $details[$i+$key]['name'] = $value->boot_name;
                            $details[$i+$key]['count'] = $value->count;
                        }
                    }
                    
                    $i = count($details);
                    if (!empty($jamb_results)) {
                        foreach($jamb_results as $key => $value) {
                            $details[$i+$key]['name'] = $value->name;
                            $details[$i+$key]['count'] = $value->count;
                        }
                    }
                } else {
                    $all_details = json_decode($waybills[count($waybills)-1]->details, true);
                    $sended_details = json_decode($waybills[count($waybills)-1]->sended_details, true);
                    if (!empty($all_details))
                        $details = array_values($this->check_diff_sended_details($all_details, $sended_details));
                    else
                        $details = [];
                }

                $data = array(
                    'order'              => $order,
                    'check_process_over' => $check_process_over,
                    'order_processes'    => $order_processes,
                    'company_drivers'    => $company_drivers,
                    'carrier_drivers'    => $carrier_drivers,
                    'product_model'      => $product_model,
                    'product_model2'     => $product_model2,
                    'product_model3'     => $product_model3,
                    'product_model4'     => $product_model4,
                    'waybills'           => $waybills,
                    'details'            => $details
                );
                break;
        }
        
        return view('moderator.order.outfit', $data);
    }

    private function check_diff_sended_details($arraya, $arrayb){
        foreach ($arraya as $keya => $valuea) {
            $key1 = array_search($valuea, $arrayb);
            if ($key1 !== false) {
                unset($arraya[$keya]);
            } else {
                $key2 = array_search($valuea['name'], array_column($arrayb, 'name'));
                if ($key2 !== false) {
                    $arraya[$keya]['count'] = (double)$arraya[$keya]['count'] - (double)$arrayb[$key2]['count'];
                }
            }
        }
        return $arraya;
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
            'started' => 1,
            'started_datetime' => date('Y-m-d H:i:s')
        ]);

        $order_id = $query->first()->order_id;

        $check = DB::select('SELECT * FROM order_processes WHERE order_id=? AND done=1', [$order_id]);

        if (empty($empty)) {
            Order::where('id', $order_id)->update([
                'job_name' => "Boshlandi"
            ]);
        }

        return response()->json([
            'message' => "Naryad muvaffaqiyatli boshlandi."
        ]);
    }

    // Har bir ishchining naryadini alohida-alohida tugatish
    public function end_outfit(Request $request)
    {
        $query = DB::table('order_processes')->where('id', $request->order_process);
        $result = $query->first();
        $job = Job::find($result->job_id);
        $order = Order::find($result->order_id);
        if (($order->product == 'door' && $result->product == 'door') || ($order->product != 'door')) {
            $order->update([
                'job_name' => $job->name
            ]);
        }

        switch ($order->product) {
            case 'jamb':
                $jambs = DB::table('jamb_results')->where('order_id', $order->id)->get();
                $jamb_jobs = json_decode($job->jamb_job, true);

                $jamb_count = 0;
                foreach($jambs as $key => $value) {
                    $jamb_count += $value->count;
                }

                $total_salary = 0;
                if (!is_null($jamb_jobs)) {
                    foreach($jamb_jobs as $key => $value) {
                        $total_salary += $value['salary'] * $jamb_count;
                    }
                }
                $query->update([
                    'done_datetime' => date('Y-m-d H:i:s'),
                    'done' => 1,
                    'product_count' => $jamb_count,
                    'salary' => $total_salary
                ]);
                break;

            case 'nsjamb':
                $nsjambs = DB::table('nsjamb_results')->where('order_id', $order->id)->get();
                $nsjamb_count = 0;
                foreach($nsjambs as $key => $value) {
                    $nsjamb_count += $value->count;
                }
                
                $nsjamb_jobs = json_decode($job->nsjamb_job, true);
                $total_salary = 0;
                if (!is_null($nsjamb_jobs)) {
                    foreach($nsjamb_jobs as $key => $value) {
                        $total_salary += $value['salary'] * $nsjamb_count;
                    }
                }
                $query->update([
                    'done_datetime' => date('Y-m-d H:i:s'),
                    'done' => 1,
                    'product_count' => $nsjamb_count,
                    'salary' => $total_salary
                ]);
                break;
            
            case 'transom':
                $transoms = DB::table('transom_results')->where('order_id', $order->id)->get();
                $transom_jobs = json_decode($job->transom_job, true);

                $transom_count = 0;
                foreach($transoms as $key => $value) {
                    $transom_count += $value->count;
                }

                $total_salary = 0;
                if (!is_null($transom_jobs)) {
                    foreach($transom_jobs as $key => $value) {
                        $total_salary += $value['salary'] * $transom_count;
                    }
                }
                $query->update([
                    'done_datetime' => date('Y-m-d H:i:s'),
                    'done' => 1,
                    'product_count' => $transom_count,
                    'salary' => $total_salary
                ]);
                break;

            case 'jamb+transom':
                switch ($result->product) {
                    case 'jamb':
                        $jambs = DB::table('jamb_results')->where('order_id', $order->id)->get();
                        $jamb_jobs = json_decode($job->jamb_job, true);

                        $jamb_count = 0;
                        foreach($jambs as $key => $value) {
                            $jamb_count += $value->count;
                        }

                        $total_salary = 0;
                        if (!is_null($jamb_jobs)) {
                            foreach($jamb_jobs as $key => $value) {
                                $total_salary += $value['salary'] * $jamb_count;
                            }
                        }
                        $query->update([
                            'done_datetime' => date('Y-m-d H:i:s'),
                            'done' => 1,
                            'product_count' => $jamb_count,
                            'salary' => $total_salary
                        ]);
                        break;
                    default:
                        $transoms = DB::table('transom_results')->where('order_id', $order->id)->get();
                        $transom_jobs = json_decode($job->transom_job, true);
        
                        $transom_count = 0;
                        foreach($transoms as $key => $value) {
                            $transom_count += $value->count;
                        }
        
                        $total_salary = 0;
                        if (!is_null($transom_jobs)) {
                            foreach($transom_jobs as $key => $value) {
                                $total_salary += $value['salary'] * $transom_count;
                            }
                        }
                        $query->update([
                            'done_datetime' => date('Y-m-d H:i:s'),
                            'done' => 1,
                            'product_count' => $transom_count,
                            'salary' => $total_salary
                        ]);
                        break;
                }
                break;
            case "door":
                $door = Door::find($order->door_id);
                switch ($result->product) {
                    case 'jamb':
                        $jamb_parameters = json_decode($door->jamb_parameters, true);
                        $jambs = array_reduce($jamb_parameters, 'array_merge', array());
                        
                        $jamb_count = 0;
                        foreach($jambs as $key => $value) {
                            $jamb_count += $value['count'];
                        }
                        
                        $jamb_jobs = json_decode($job->jamb_job, true);
                        $total_salary = 0;
                        if (!is_null($jamb_jobs)) {
                            foreach($jamb_jobs as $key => $value) {
                                $total_salary += $value['salary'] * $jamb_count;
                            }
                        }
                        $query->update([
                            'done_datetime' => date('Y-m-d H:i:s'),
                            'done' => 1,
                            'product_count' => $jamb_count,
                            'salary' => $total_salary
                        ]);
                        break;
                    case 'transom':
                        $transoms = json_decode($door->transom_parameters, true);
                        $transom_jobs = json_decode($job->transom_job, true);
        
                        $transom_count = 0;
                        foreach($transoms as $key => $value) {
                            $transom_count += (double)$value['height_count'];
                            $transom_count += (double)$value['width_count'];
                        }
        
                        $total_salary = 0;
                        if (!is_null($transom_jobs)) {
                            foreach($transom_jobs as $key => $value) {
                                $total_salary += $value['salary'] * $transom_count;
                            }
                        }
                        $query->update([
                            'done_datetime' => date('Y-m-d H:i:s'),
                            'done' => 1,
                            'product_count' => $transom_count,
                            'salary' => $total_salary
                        ]);
                        break;
                    case 'crown':
                        $crown_parameters = json_decode($door->crown_parameters, true);
                        $crown_jobs = json_decode($job->crown_job, true);
                        $crown_count = 0;
                        
                        if (!empty($crown_parameters)) {
                            foreach($crown_parameters as $key => $value) {
                                $crown_count += $value['total_count'];
                            }    
                        }
                        
                        $total_salary = 0;
                        if (!empty($crown_jobs)) {
                            foreach($crown_jobs as $key => $value) {
                                $total_salary += $value['salary'] * $crown_count;
                            }
                        }
                        $query->update([
                            'done_datetime' => date('Y-m-d H:i:s'),
                            'done' => 1,
                            'product_count' => $crown_count,
                            'salary' => $total_salary
                        ]);
                        break;
                    case 'boot':
                        $boot_parameters = json_decode($door->boot_parameters, true);
                        $boot_jobs = json_decode($job->boot_job, true);
                        $boot_count = 0;
                        if (!empty($boot_parameters)) {
                            $boots_array = array_reduce($boot_parameters, 'array_merge', array());
                            foreach($boots_array as $key => $value) {
                                $boot_count += $value['total_count'];
                            }    
                        }

                        $total_salary = 0;
                        if (!empty($boot_jobs)) {
                            foreach($boot_jobs as $key => $value) {
                                $total_salary += $value['salary'] * $boot_count;
                            }
                        }
                        $query->update([
                            'done_datetime' => date('Y-m-d H:i:s'),
                            'done' => 1,
                            'product_count' => $boot_count,
                            'salary' => $total_salary
                        ]);
                        break;
                    case 'cube':
                        $cube_parameters = json_decode($door->cube_parameters, true);
                        $cube_jobs = json_decode($job->cube_job, true);
                        $cube_count = 0;
                        if (!empty($cube_parameters)) {
                            $cubes_array = array_reduce($cube_parameters, 'array_merge', array());
                            foreach($cubes_array as $key => $value) {
                                $cube_count += $value['total_count'];
                            }    
                        }

                        $total_salary = 0;
                        if (!empty($cube_jobs)) {
                            foreach($cube_jobs as $key => $value) {
                                $total_salary += $value['salary'] * $cube_count;
                            }
                        }
                        $query->update([
                            'done_datetime' => date('Y-m-d H:i:s'),
                            'done' => 1,
                            'product_count' => $cube_count,
                            'salary' => $total_salary
                        ]);
                        break;
                    default:
                        $doors = json_decode($door->door_parameters, true);
                        $door_jobs = json_decode($job->door_job, true);
                        
                        $door_count = 0;
                        $total_salary = 0;
                        
                        foreach($doors as $key => $value) {
                            $door_count += $value['count'];
                        }

                        foreach($door_jobs as $k => $v) {
                            foreach($doors as $key => $value) {
                                if (in_array($result->job_id, array(2, 7))) { // job_id = 7 (bu serverdagi presschini idsi), job_id = 2 (bu serverdagi EMAL(G) idsi)
                                    if ($k == 1) {
                                        if (!empty($value['framogatype_id']))
                                            $total_salary += $value['count'] * $v['salary'];
                                    } else {
                                        $total_salary += $v['salary'] * $value['layer'] * $value['count'];
                                    }
                                } else if ($result->job_id == 1) { // job_id = 1 bu serverdagi stolyarni idsi
                                    if ($k == 2) {
                                        if (!empty($value['framogatype_id']))
                                            $total_salary += $value['count'] * $v['salary'];
                                    } else {
                                        $total_salary += $v['salary'] * $value['layer'] * $value['count'];
                                    }
                                } else if ($result->job_id == 10) { // job_id = 10 bu serverdagi shisha o'rnatish idsi
                                    if ($k == 1) {
                                        if (!empty($value['framogatype_id']))
                                            $total_salary += $value['count'] * $v['salary'];
                                    } else {
                                        if (isset($value['glass_figure']) && !empty($value['glass_figure'])){
                                            $total_salary += $v['salary'] * $value['layer'] * $value['count'];
                                        }
                                    }
                                } else if ($result->job_id == 4) { // job_id = 2 (bu serverdagi Emal (KDSPTJL) idsi)
                                    if ($k == 7) {
                                        if (!empty($value['framogatype_id']))
                                            $total_salary += $value['count'] * $v['salary'];
                                    } else {
                                        $total_salary += $v['salary'] * $value['layer'] * $value['count'];
                                    }
                                } else if ($result->job_id == 3) { // job_id = 3 (bu serverdagi EMAL(KL) idsi)
                                    if ($k == 2) {
                                        if (!empty($value['framogatype_id']))
                                            $total_salary += $value['count'] * $v['salary'];
                                    } else {
                                        $total_salary += $v['salary'] * $value['layer'] * $value['count'];
                                    }
                                } else if ($result->job_id == 5) { // job_id = 3 (bu serverdagi Emal (KDSPTL) idsi)
                                    if ($k == 6) {
                                        if (!empty($value['framogatype_id']))
                                            $total_salary += $value['count'] * $v['salary'];
                                    } else {
                                        $total_salary += $v['salary'] * $value['layer'] * $value['count'];
                                    }
                                } else if ($result->job_id == 6) { // job_id = 3 (bu serverdagi EMAL(KPL) idsi)
                                    if ($k == 3) {
                                        if (!empty($value['framogatype_id']))
                                            $total_salary += $value['count'] * $v['salary'];
                                    } else {
                                        $total_salary += $v['salary'] * $value['layer'] * $value['count'];
                                    }
                                } else {
                                    $total_salary += $v['salary'] * $value['layer'] * $value['count'];
                                }
                            }
                        }

                        $query->update([
                            'done_datetime' => date('Y-m-d H:i:s'),
                            'done' => 1,
                            'product_count' => $door_count,
                            'salary' => $total_salary
                        ]);
                        break;
                }
                break;
            default:
                switch($result->product) {
                    case 'crown':
                        $crowns = DB::table('crown_results')->where('order_id', $order->id)->get();
                        $crown_jobs = json_decode($job->crown_job, true);

                        $crown_count = 0;
                        foreach($crowns as $key => $value) {
                            $crown_count += $value->count;
                        }

                        $total_salary = 0;
                        if (!empty($crown_jobs)) {
                            foreach($crown_jobs as $key => $value) {
                                $total_salary += $value['salary'] * $crown_count;
                            }
                        }
                        $query->update([
                            'done_datetime' => date('Y-m-d H:i:s'),
                            'done' => 1,
                            'product_count' => $crown_count,
                            'salary' => $total_salary
                        ]);
                        break;
                    case 'jamb':
                        $jambs = DB::table('jamb_results')->where('order_id', $order->id)->get();
                        $jamb_jobs = json_decode($job->jamb_job, true);

                        $jamb_count = 0;
                        foreach($jambs as $key => $value) {
                            $jamb_count += $value->count;
                        }

                        $total_salary = 0;
                        if (!empty($jamb_jobs)) {
                            foreach($jamb_jobs as $key => $value) {
                                $total_salary += $value['salary'] * $jamb_count;
                            }
                        }
                        $query->update([
                            'done_datetime' => date('Y-m-d H:i:s'),
                            'done' => 1,
                            'product_count' => $jamb_count,
                            'salary' => $total_salary
                        ]);
                        break;
                    case 'boot':
                        $boots = DB::table('boot_results')->where('order_id', $order->id)->get();
                        $boot_jobs = json_decode($job->boot_job, true);

                        $boot_count = 0;
                        foreach($boots as $key => $value) {
                            $boot_count += $value->count;
                        }

                        $total_salary = 0;
                        if (!empty($boot_jobs)) {
                            foreach($boot_jobs as $key => $value) {
                                $total_salary += $value['salary'] * $boot_count;
                            }
                        }
                        $query->update([
                            'done_datetime' => date('Y-m-d H:i:s'),
                            'done' => 1,
                            'product_count' => $boot_count,
                            'salary' => $total_salary
                        ]);
                        break;
                    default:
                        $cubes = DB::table('cube_results')->where('order_id', $order->id)->get();
                        $cube_jobs = json_decode($job->cube_job, true);

                        $cube_count = 0;
                        foreach($cubes as $key => $value) {
                            $cube_count += $value->count;
                        }

                        $total_salary = 0;
                        if (!empty($cube_jobs)) {
                            foreach($cube_jobs as $key => $value) {
                                $total_salary += $value['salary'] * $cube_count;
                            }
                        }
                        $query->update([
                            'done_datetime' => date('Y-m-d H:i:s'),
                            'done' => 1,
                            'product_count' => $cube_count,
                            'salary' => $total_salary
                        ]);
                        break;
                }
                break;
        }

        return response()->json([
            'message' => "Naryad muvaffaqiyatli yakunlandi."
        ]);
    }

    // Naryadni yakunlash
    public function order_closed(Request $request)
    {
        Order::where('id', $request->order_id)->update([
            'moderator_send' => 1,
            'moderator_send_time' => date('Y-m-d H:i:s'),
            'job_name' => 'Yakunlangan'
        ]);

        return redirect()->route('form-outfit', $request->order_id);
    }


    // Moderator shartnoma detallarini ko'rish
    public function show($order_id)
    {
        $order = DB::select('SELECT a.id, 
                                    a.door_id, 
                                    a.phone_number, 
                                    a.contract_number, 
                                    a.deadline, 
                                    b.name AS customer, 
                                    a.comments,
                                    a.product,
                                    a.moderator_receive
                             FROM (orders a, customers b)
                             WHERE a.customer_id=b.id AND a.id=?', [$order_id]);

        switch ($order[0]->product) {
            case 'transom':
                $transom_results = DB::table('transom_results')->where('order_id', $order_id)->get();
                $data = array(
                    'order'            => $order,
                    'transom_results'  => $transom_results
                );

                return view('moderator.order.transom_show', $data);
                break;
            case 'jamb':
                $jamb_results = DB::table('jamb_results')->where('order_id', $order_id)->get();
                $data = array(
                    'order'         => $order,
                    'jamb_results'  => $jamb_results
                );

                return view('moderator.order.jamb_show', $data);
                break;
            case 'nsjamb':
                $nsjamb_results = DB::table('nsjamb_results')->where('order_id', $order_id)->get();
                $data = array(
                    'order'         => $order,
                    'nsjamb_results'  => $nsjamb_results
                );

                return view('moderator.order.nsjamb_show', $data);
                break;
            case 'jamb+transom':
                $jamb_results = DB::table('jamb_results')->where('order_id', $order_id)->get();
                $transom_results = DB::table('transom_results')->where('order_id', $order_id)->get();

                $data = array(
                    'order'           => $order,
                    'jamb_results'    => $jamb_results,
                    'transom_results' => $transom_results
                );
                return view('moderator.order.jamb_transom_show', $data);
                break;
            case 'door':
                $door = Door::find($order[0]->door_id);
                $door_parameters = json_decode($door->door_parameters, true);
                $layers = array_reduce($door_parameters, function($layer, $item){ 
                    if (isset($item['layer'])) {
                        if(!isset($layer[$item['layer']])){ 
                            $layer[$item['layer']] = [
                                'name'        => $item['layer'], 
                                'count'       => $item['count'], 
                                'price'       => $item['layer_price'],
                                'total_price' => $item['total_layer_price']
                            ]; 
                        } else {
                            $layer[$item['layer']]['name'] = $item['layer'];
                            $layer[$item['layer']]['count'] += $item['count'];
                            $layer[$item['layer']]['price'] = $item['layer_price'];
                            $layer[$item['layer']]['total_price'] += $item['total_layer_price'];
                        }
                        return $layer;
                    }
                });
        
                $depths = array_reduce($door_parameters, function($depth, $item){ 
                    if (isset($item['depth'])) {
                        if(!isset($depth[$item['depth']])){ 
                            $depth[$item['depth']] = [
                                'name'        => $item['depth'], 
                                'count'       => $item['count'], 
                                'price'       => $item['depth_price'],
                                'total_price' => $item['total_depth_price']
                            ]; 
                        } else {
                            $depth[$item['depth']]['name'] = $item['depth'];
                            $depth[$item['depth']]['count'] += $item['count'];
                            $depth[$item['depth']]['price'] = $item['depth_price'];
                            $depth[$item['depth']]['total_price'] += $item['total_depth_price'];
                        }
                        return $depth;
                    }
                });
        
                $locktypes = array_reduce($door_parameters, function($locktype, $item){ 
                    if (isset($item['locktype'])) {
                        if(!isset($locktype[$item['locktype']])){ 
                            $locktype[$item['locktype']] = [
                                'name'        => $item['locktype'], 
                                'count'       => $item['count'], 
                                'price'       => $item['locktype_price'],
                                'total_price' => $item['total_locktype_price']
                            ];
                        } else {
                            $locktype[$item['locktype']]['name'] = $item['locktype'];
                            $locktype[$item['locktype']]['count'] += $item['count'];
                            $locktype[$item['locktype']]['price'] = $item['locktype_price'];
                            $locktype[$item['locktype']]['total_price'] += $item['total_locktype_price'];
                        }
                        return $locktype;
                    }
                });
        
                $ornamenttypes = array_reduce($door_parameters, function($ornamenttype, $item){
                    if (isset($item['ornamenttype'])) {
                        if(!isset($ornamenttype[$item['ornamenttype']])){ 
                            $ornamenttype[$item['ornamenttype']] = [
                                'name'        => $item['ornamenttype'], 
                                'count'       => $item['count'], 
                                'price'       => $item['ornamenttype_price'],
                                'total_price' => $item['total_ornamenttype_price']
                            ]; 
                        } else {
                            $ornamenttype[$item['ornamenttype']]['name'] = $item['ornamenttype'];
                            $ornamenttype[$item['ornamenttype']]['count'] += $item['count'];
                            $ornamenttype[$item['ornamenttype']]['price'] = $item['ornamenttype_price'];
                            $ornamenttype[$item['ornamenttype']]['total_price'] += $item['total_ornamenttype_price'];
                        }
                        return $ornamenttype;
                    }
                });
        
                $loops = array_reduce($door_parameters, function($loop, $item){
                    if (isset($item['loop_name'])) {
                        if(!isset($loop[$item['loop_name']])){ 
                            $loop[$item['loop_name']] = [
                                'name'        => $item['loop_name'], 
                                'count'       => $item['loop_count'], 
                                'price'       => $item['loop_price'],
                                'total_price' => $item['total_loop_price']
                            ]; 
                        } else {
                            $loop[$item['loop_name']]['name'] = $item['loop_name'];
                            $loop[$item['loop_name']]['count'] += $item['loop_count'];
                            $loop[$item['loop_name']]['price'] = $item['loop_price'];
                            $loop[$item['loop_name']]['total_price'] += $item['total_loop_price'];
                        }
                        return $loop;
                    }
                });
        
                $output = array();
                foreach($door_parameters as $key => $value) {
                    $output_element = &$output[$value['width'] . "_" . $value['height'] . "_" . $value['layer']];
                    $output_element['name'] = $value['doortype'];
                    $output_element['width'] = $value['width'];
                    $output_element['height'] = $value['height'];
                    $output_element['layer'] = $value['layer'];
                    $output_element['price'] = $value['doortype_price'];
        
                    if (!isset($output_element['count']) && !isset($output_element['total_price'])){
                        $output_element['count'] = $value['count'];
                        $output_element['total_price'] = $value['total_doortype_price'];
                    } else {
                        $output_element['count'] += $value['count'];
                        $output_element['total_price'] += $value['total_doortype_price'];
                    }
                }    
                $doortypes = array_values($output);
        
                $output2 = array();
                $glass_parameters = json_decode($door->glass_parameters, true);
                
                if (!is_null($glass_parameters)) {
                    foreach($glass_parameters as $key => $value) {
                        $output_element = &$output2[$value['type'] . "_" . $value['figure']];
                        $output_element['type'] = $value['type'];
                        $output_element['figure'] = $value['figure'];
                        $output_element['price'] = $value['price'];
            
                        if (!isset($output_element['total_count']) && !isset($output_element['total_price'])){
                            $output_element['total_count'] = $value['total_count'];
                            $output_element['total_price'] = $value['total_price'];
                        } else {
                            $output_element['total_count'] += $value['total_count'];
                            $output_element['total_price'] += $value['total_price'];
                        }
                    }    
                }
                $glasses = array_values($output2);

                $output3 = array();
                $crown_parameters = json_decode($door->crown_parameters, true);
                if (!is_null($crown_parameters)) {
                    foreach($crown_parameters as $key => $value) {
                        $output_element = &$output3[$value['name'] . "_" . $value['door_width']];
                        $output_element['name'] = $value['name'];
                        $output_element['door_width'] = $value['door_width'];
                        $output_element['price'] = $value['price'];
            
                        if (!isset($output_element['total_count']) && !isset($output_element['total_price'])){
                            $output_element['total_count'] = $value['total_count'];
                            $output_element['total_price'] = $value['total_price'];
                        } else {
                            $output_element['total_count'] += $value['total_count'];
                            $output_element['total_price'] += $value['total_price'];
                        }
                    }    
                }
                $crowns = array_values($output3);
                
                $output4 = array();
                $cube_parameters = json_decode($door->cube_parameters, true);
                if (!is_null($cube_parameters)) {
                    $cubes_array = array_reduce($cube_parameters, 'array_merge', array());
                    foreach($cubes_array as $key => $value) {
                        if(is_numeric($key)){
                            $output_element = &$output4[$value['name']];
                            $output_element['name'] = $value['name'];
                            $output_element['price'] = $value['price'];
                
                            if (!isset($output_element['total_count']) && !isset($output_element['total_price'])){
                                $output_element['total_count'] = $value['total_count'];
                                $output_element['total_price'] = $value['total_price'];
                            } else {
                                $output_element['total_count'] += $value['total_count'];
                                $output_element['total_price'] += $value['total_price'];
                            }
                        }
                    }    
                }
                $cubes = array_values($output4);

                $output5 = array();
                $boot_parameters = json_decode($door->boot_parameters, true);
                if (!is_null($boot_parameters)) {
                    $boots_array = array_reduce($boot_parameters, 'array_merge', array());
                    foreach($boots_array as $key => $value) {
                        if(is_numeric($key)){
                            $output_element = &$output5[$value['name']];
                            $output_element['name'] = $value['name'];
                            $output_element['price'] = $value['price'];
                
                            if (!isset($output_element['total_count']) && !isset($output_element['total_price'])){
                                $output_element['total_count'] = $value['total_count'];
                                $output_element['total_price'] = $value['total_price'];
                            } else {
                                $output_element['total_count'] += $value['total_count'];
                                $output_element['total_price'] += $value['total_price'];
                            }
                        }
                    }    
                }
                $boots = array_values($output5);
        
                $jamb_parameters = json_decode($door->jamb_parameters, true);
                $jambs_array = array_reduce($jamb_parameters, 'array_merge', array());
                $jambs = array_reduce($jambs_array, function($jamb, $item){
                    if (isset($item['id'])) {
                        if(!isset($jamb[$item['id']])){ 
                            $jamb[$item['id']] = [
                                'name'        => $item['name'], 
                                'count'       => $item['count'], 
                                'price'       => $item['price'],
                                'total_price' => $item['total_price']
                            ]; 
                        } else {
                            $jamb[$item['id']]['name'] = $item['name'];
                            $jamb[$item['id']]['count'] += $item['count'];
                            $jamb[$item['id']]['price'] = $item['price'];
                            $jamb[$item['id']]['total_price'] += $item['total_price'];
                        }
                        return $jamb;
                    }
                });

                $transom_parameters = json_decode($door->transom_parameters, true);
                $current_transom_id = 0;
                $transoms = array();
                foreach ($transom_parameters as $item) {
                    if ($item['name'] != "") {
                        $current_transom_id = $item['id'];
                    }
        
                    $output_element = &$transoms[$item['id'] . '_' . $item['width'] . '_' . $item['height'] . '_' . $item['thickness']];
                    if (!isset($output_element['name']) && !isset($output_element['width']) && !isset($output_element['height']) && !isset($output_element['thickness'])) {
                        $output_element['name']         = $item['name'];
                        $output_element['height_count'] = $item['height_count'];
                        $output_element['width_count']  = $item['width_count'];
                        $output_element['width']        = $item['width'];
                        $output_element['height']       = $item['height'];
                        $output_element['thickness']    = $item['thickness'];
                        $output_element['price']        = $item['price'];
                        $output_element['total_price']  = $item['total_price'];
                    } else {
                        $output_element['name'] = $item['name'];
                        $output_element['height_count'] += $item['height_count'];
                        $output_element['width_count'] += $item['width_count'];
                        $output_element['width'] = $item['width'];
                        $output_element['height'] = $item['height'];
                        $output_element['thickness'] = $item['thickness'];
                        $output_element['price'] = $item['price'];
                        $output_element['total_price'] += $item['total_price'];
                    }
                }

                $data = array(
                    'order'            => $order,
                    'door'             => $door,
                    'door_parameters'  => $door_parameters,
                    'doortypes'        => $doortypes,
                    'layers'           => $layers,
                    'depths'           => $depths,
                    'locktypes'        => $locktypes,
                    'ornamenttypes'    => $ornamenttypes,
                    'loops'            => $loops,
                    'jambs'            => $jambs,
                    'transoms'         => $transoms,
                    'glasses'          => $glasses,
                    'crowns'           => $crowns,
                    'boots'            => $boots,
                    'cubes'            => $cubes,
                );

                return view('moderator.order.door_show', $data);
                break;
            default: 
                $crown_results = DB::table('crown_results')->where('order_id', $order_id)->get();
                $boot_results = DB::table('boot_results')->where('order_id', $order_id)->get();
                $cube_results = DB::table('cube_results')->where('order_id', $order_id)->get();
                $jamb_results = DB::table('jamb_results')->where('order_id', $order_id)->get();

                $data = array(
                    'order'         => $order,
                    'crown_results' => $crown_results,
                    'boot_results'  => $boot_results,
                    'cube_results'  => $cube_results,
                    'jamb_results'  => $jamb_results
                );

                return view('moderator.order.crown_boot_cube_show', $data);
                break;
        }
    }

    // Yuk xati detallarini ko'rish
    public function waybill_show($waybill_id)
    {
        $waybill = DB::select('SELECT  a.name as driver, 
                                        CASE 
                                            WHEN a.type = "carrier" THEN "Kuryer" 
                                            ELSE "Korxona"
                                        END AS driver_type,
                                        a.gov_number,
                                        a.phone_number,
                                        c.name AS car_model,
                                        b.id,
                                        b.order_id,
                                        b._from,
                                        b._to,
                                        b.day,
                                        b.sended_details,
                                        b.created_at
                               FROM waybills b
                               INNER JOIN drivers a ON a.id=b.driver_id
                               INNER JOIN car_models c ON c.id=a.carmodel_id
                               WHERE a.active = 1 AND b.id=?', [$waybill_id]);
        
        return view('moderator.order.waybill_show', compact('waybill'));
    }
}
