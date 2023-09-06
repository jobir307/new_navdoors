<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Door;
use App\Models\Doortype;
use App\Models\Transom;
use PDF;

class PDFController extends Controller
{
    // manager order door
    public function manager_customer_order_door($id) {
        $order = DB::select('SELECT a.id, 
                                    a.door_id, 
                                    a.phone_number, 
                                    a.contract_number, 
                                    a.contract_price, 
                                    a.last_contract_price,
                                    a.rebate_percent,
                                    a.courier_price,
                                    a.installation_price,
                                    a.deadline, 
                                    b.name as customer,
                                    b.type as customer_type,
                                    a.created_at
                             FROM (orders a, customers b)
                             WHERE a.customer_id=b.id AND a.id=?', [$id]);
        $door = Door::find($order[0]->door_id);
        $doortype = Doortype::find($door->doortype_id);
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

        $output2 = array();
        $glass_parameters = json_decode($door->glass_parameters, true);
        // dd($glass_parameters);
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
        $glasses = array_values($output2);

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
        $current_transom_id = 0;
        $transom_parameters = json_decode($door->transom_parameters, true);
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
        $transom_installation_price = 0;
        if ($current_transom_id != 0) {
            $transom_installation_price = Transom::find($current_transom_id)->installation_price;
        }
        $data = [
            'date'            => date('dd.mm.yyyy'),
            'order'           => $order,
            'door_parameters' => $door_parameters,
            'doortypes'       => $doortypes,
            'layers'          => $layers,
            'depths'          => $depths,
            'locktypes'       => $locktypes,
            'ornamenttypes'   => $ornamenttypes,
            'loops'           => $loops,
            'jambs'           => $jambs,
            'transoms'        => $transoms,
            'door_color'      => $door->door_color,
            'doortype'        => $doortype->name,
            'glasses'         => $glasses,
            'transom_installation_price' => $transom_installation_price
        ];

        // echo '<link rel="stylesheet" href="'.asset('assets/css/bootstrap.min.css').'">';
        $pdf = PDF::loadView('manager.order.door.pdf', $data);
        return $pdf->download('shartnoma-' . $order[0]->id . '.pdf');
    }
    
    public function manager_customer_order_jamb($order_id)
    {
        $order = DB::select('SELECT a.id, 
                                    a.phone_number, 
                                    a.contract_number, 
                                    a.contract_price, 
                                    a.last_contract_price,
                                    a.rebate_percent,
                                    a.courier_price,
                                    a.installation_price,
                                    a.deadline, 
                                    b.name as customer,
                                    b.type as customer_type,
                                    a.created_at
                             FROM (orders a, customers b)
                             WHERE a.customer_id=b.id AND a.id=?', [$order_id]);
        $jambs = DB::select('SELECT * FROM jamb_results WHERE order_id=?', [$order_id]);

        // echo '<link rel="stylesheet" href="'.asset('assets/css/bootstrap.min.css').'">';
        $data = array(
            'order' => $order,
            'jambs' => $jambs
        );

        $pdf = PDF::loadView('manager.order.jamb.pdf', $data);
        return $pdf->download('shartnoma-' . $order[0]->id . '.pdf');
    }

    public function manager_customer_order_transom($order_id)
    {
        $order = DB::select('SELECT a.id, 
                                    a.phone_number, 
                                    a.contract_number, 
                                    a.contract_price, 
                                    a.last_contract_price,
                                    a.rebate_percent,
                                    a.courier_price,
                                    a.installation_price,
                                    a.deadline, 
                                    b.name as customer,
                                    b.type as customer_type,
                                    a.created_at
                             FROM (orders a, customers b)
                             WHERE a.customer_id=b.id AND a.id=?', [$order_id]);
        $transoms = DB::select('SELECT * FROM transom_results WHERE order_id=?', [$order_id]);

        // echo '<link rel="stylesheet" href="'.asset('assets/css/bootstrap.min.css').'">';
        $data = array(
            'order' => $order,
            'transoms' => $transoms
        );
        
        $pdf = PDF::loadView('manager.order.transom.pdf', $data);
        return $pdf->download('shartnoma-' . $order[0]->id . '.pdf');
    }

    public function manager_customer_order_jamb_transom($order_id)
    {
        $order = DB::select('SELECT a.id, 
                                    a.phone_number, 
                                    a.contract_number, 
                                    a.contract_price, 
                                    a.last_contract_price,
                                    a.rebate_percent,
                                    a.courier_price,
                                    a.installation_price,
                                    a.deadline, 
                                    b.name as customer,
                                    b.type as customer_type,
                                    a.created_at
                             FROM (orders a, customers b)
                             WHERE a.customer_id=b.id AND a.id=?', [$order_id]);
        $transoms = DB::select('SELECT * FROM transom_results WHERE order_id=?', [$order_id]);
        $jambs = DB::select('SELECT * FROM jamb_results WHERE order_id=?', [$order_id]);

        // echo '<link rel="stylesheet" href="'.asset('assets/css/bootstrap.min.css').'">';
        $data = array(
            'order' => $order,
            'transoms' => $transoms,
            'jambs' => $jambs
        );
        
        $pdf = PDF::loadView('manager.order.jamb_transom.pdf', $data);
        return $pdf->download('shartnoma-' . $order[0]->id . '.pdf');
    }


    // moderator
    public function job_assignment($order_process_id)
    {
        $order_process = DB::table('order_processes')->find($order_process_id);
        $order = DB::select('SELECT a.id, 
                                    a.door_id, 
                                    a.phone_number, 
                                    a.contract_number, 
                                    a.contract_price, 
                                    a.deadline, 
                                    b.name as customer,
                                    b.type as customer_type,
                                    a.created_at
                             FROM (orders a, customers b)
                             WHERE a.customer_id=b.id AND a.id=?', [$order_process->order_id]);
        $door = DB::select('SELECT a.*, b.name as doortype 
                            FROM doors a 
                            INNER JOIN doortypes b ON a.doortype_id=b.id
                            WHERE a.id=?', [$order[0]->door_id]);
        $door_parameters = json_decode($door[0]->door_parameters, true);
        $jamb_parameters = json_decode($door[0]->jamb_parameters, true);
        $transom_parameters = json_decode($door[0]->transom_parameters, true);
        
        $door_count = 0;
        foreach ($door_parameters as $key => $value) {
            $door_count += $value['count'];
        }
        
        $jamb_count = 0;
        if (!is_null($jamb_parameters)) {
            $jambs_array = array_reduce($jamb_parameters, 'array_merge', array());
            foreach ($jambs_array as $key => $value) {
                $jamb_count += $value['count'];
            }
        }

        $transom_count = 0;
        if (!is_null($transom_parameters)) {
            foreach ($transom_parameters as $key => $value) {
                $transom_count += $value['width_count'];
            }
        }

        $job = DB::table('jobs')->find($order_process->job_id);
        $door_jobs = json_decode($job->door_job, true);
        $jamb_jobs = json_decode($job->jamb_job, true);
        $transom_jobs = json_decode($job->transom_job, true);  
        $job_door_results = DB::select('SELECT '.$job->door_attributes.'
                                        FROM door_results
                                        WHERE door_id=?
                                      ', [$order[0]->door_id]);
        $table_headers = DB::table('door_attributes')
                    ->whereIn('en_name', explode(",", $job->door_attributes))
                    ->get(['name', 'en_name']);
        
        $data = array(
            'order'            => $order,
            'door'             => $door,
            'job'              => $job,
            'job_door_results' => $job_door_results,
            'door_jobs'        => $door_jobs,
            'door_count'       => $door_count,
            'jamb_jobs'        => $jamb_jobs,
            'jamb_count'       => $jamb_count,
            'transom_jobs'     => $transom_jobs,
            'transom_count'    => $transom_count,
            'table_headers'    => $table_headers
        );
        
        // echo '<link rel="stylesheet" href="'.asset('assets/css/bootstrap.min.css').'">';
        $pdf = PDF::loadView('moderator.order.pdf', $data);
        return $pdf->download('Naryad jarayoni-' . $order_process_id . '.pdf'); 
    }
    // Naryadni yakunlab tayyor mahsulotni skladga jo'natish
    public function outfit_closed(Request $request) 
    {
        if (!is_null($request->driver_id))
            $driver_id = $request->driver_id;
        else if (!is_null($request->courier_id))
            $driver_id = $request->courier_id;
        else 
            return redirect()->back()->with('message', "Xatolik mavjud, tekshirib qaytadan ko'ring.");
            
        $order = DB::select('SELECT a.id, 
                                    a.door_id, 
                                    a.phone_number, 
                                    a.contract_number, 
                                    a.moderator_send,
                                    a.deadline, 
                                    b.name as customer,
                                    b.type as customer_type,
                                    a.created_at
                             FROM (orders a, customers b)
                             WHERE a.customer_id=b.id AND a.id=?', [$request->order_id]);
        
        Order::where('id', $request->order_id)->update([
            'moderator_send' => 1
        ]);

        $door = DB::select('SELECT a.*, b.name as doortype 
                            FROM doors a 
                            INNER JOIN doortypes b ON a.doortype_id=b.id
                            WHERE a.id=?', [$order[0]->door_id]);

        $door_parameters = json_decode($door[0]->door_parameters, true);
        // eshik turi
        $output = Array();
        foreach($door_parameters as $key => $value) {
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
        }      
        
        $doortypes = array_values($output);

        // nalichnik
        $jamb_parameters = json_decode($door[0]->jamb_parameters, true);
        $jambs_array = array_reduce($jamb_parameters, 'array_merge', array());
        $jambs = array_reduce($jambs_array, function($jamb, $item){
            if (isset($item['id'])) {
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
        // dobor
        $transoms = array();
        $transom_parameters = json_decode($door[0]->transom_parameters, true);
        foreach ($transom_parameters as $item) {
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
        $check_waybill = DB::select('SELECT * FROM waybills WHERE order_id=?', [$request->order_id]);
        if (empty($check_waybill)){
            DB::table('waybills')->insert([
                'order_id'  => $order[0]->id,
                'driver_id' => $driver_id,
                '_from'     => $request->_from,
                '_to'       => $request->_to,
                'doortype'  => $door[0]->door_parameters,
                'jamb'      => $door[0]->jamb_parameters,
                'transom'   => $door[0]->transom_parameters,
                'day'       => date('Y-m-d')
            ]);
        } else {
            DB::table('waybills')->where('id', $check_waybill[0]->id)->update([
                'order_id'  => $order[0]->id,
                'driver_id' => $driver_id,
                '_from'     => $request->_from,
                '_to'       => $request->_to,
                'doortype'  => $door[0]->door_parameters,
                'jamb'      => $door[0]->jamb_parameters,
                'transom'   => $door[0]->transom_parameters
            ]);
        }

        $waybill = DB::select('SELECT a.id,
                                      a.order_id, 
                                      a._from, 
                                      a._to, 
                                      a.day, 
                                      b.name as driver, 
                                      b.gov_number
                               FROM waybills a
                               INNER JOIN drivers b ON a.driver_id=b.id
                               WHERE a.order_id=?', [$request->order_id]);
        $data = array(
            'order'      => $order,
            'waybill'    => $waybill,
            'door'       => $door,
            'doortypes'  => $doortypes,
            'jambs'      => $jambs,
            'transoms'   => $transoms
        );
        // echo '<link rel="stylesheet" href="'.asset('assets/css/bootstrap.min.css').'">';
        $pdf = PDF::loadView('moderator.order.nakladnaya', $data);
        return $pdf->download('Nakladnaya (shartnoma raqami-' . $order[0]->id . ').pdf');
    }
}
