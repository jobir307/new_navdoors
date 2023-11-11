<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Door;
use App\Models\Doortype;
use App\Models\Transom;
use App\Models\Jamb;
use PDF;

class PDFController extends Controller
{
    // manager order door
    public function manager_customer_order_door($id)
    {
        $order = DB::select('SELECT a.id, 
                                    a.door_id, 
                                    a.phone_number, 
                                    a.contract_number, 
                                    a.contract_price, 
                                    a.last_contract_price,
                                    a.rebate_percent,
                                    a.courier_price,
                                    a.installation_price,
                                    a.door_installation_price,
                                    a.transom_installation_price,
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
        
        $data = [
            'date'            => date('d.m.Y'),
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
            'ornament_model'  => $door->ornament_model,
            'doortype'        => $doortype->name,
            'glasses'         => $glasses,
            'crowns'          => $crowns,
            'cubes'           => $cubes,
            'boots'           => $boots
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

    public function manager_customer_order_ccbj($order_id)
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

        $crowns = DB::select('SELECT * FROM crown_results WHERE order_id=?', [$order_id]);
        $cubes = DB::select('SELECT * FROM cube_results WHERE order_id=?', [$order_id]);
        $boots = DB::select('SELECT * FROM boot_results WHERE order_id=?', [$order_id]);
        $jambs = DB::select('SELECT a.*, b.height, b.width 
                             FROM jamb_results a
                             INNER JOIN jambs b ON b.id=a.jamb_id
                             WHERE order_id=?', [$order_id]);

        $data = array(
            'order' => $order,
            'crowns' => $crowns,
            'cubes' => $cubes,
            'boots' => $boots,
            'jambs' => $jambs
        );
        
        // echo '<link rel="stylesheet" href="'.asset('assets/css/bootstrap.min.css').'">';
        $pdf = PDF::loadView('manager.order.ccbj.pdf', $data);
        return $pdf->download('shartnoma-' . $order[0]->id . '.pdf');
    }

    public function manager_customer_order_nsjamb ($order_id)
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
        $nsjambs = DB::select('SELECT * FROM nsjamb_results WHERE order_id=?', [$order_id]);

        // echo '<link rel="stylesheet" href="'.asset('assets/css/bootstrap.min.css').'">';
        $data = array(
            'order' => $order,
            'nsjambs' => $nsjambs
        );
        
        $pdf = PDF::loadView('manager.order.nsjamb.pdf', $data);
        return $pdf->download('shartnoma-' . $order[0]->id . '.pdf');
    }

    // moderator
    public function door_job_assignment($order_process_id)
    {
        $order_process = DB::table('order_processes')->find($order_process_id);
        $order = DB::select('SELECT a.id, 
                                    a.door_id, 
                                    a.phone_number, 
                                    a.contract_number, 
                                    a.last_contract_price, 
                                    a.deadline, 
                                    b.name as customer,
                                    b.type as customer_type,
                                    a.comments,
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
        $crown_parameters = json_decode($door[0]->crown_parameters, true);
        $cube_parameters = json_decode($door[0]->cube_parameters, true);
        $boot_parameters = json_decode($door[0]->boot_parameters, true);
        
        $jamb_count = 0;
        $transom_count = 0;
        $crown_count = 0;
        $cube_count = 0;
        $boot_count = 0;
        
        $door_jobs = [];
        $jamb_jobs = [];
        $transom_jobs = [];
        $crown_jobs = [];
        $cube_jobs = [];
        $boot_jobs = [];

        $job_door_results = [];
        $job_jamb_results = [];
        $job_transom_results = [];
        $job_crown_results = [];
        $job_cube_results = [];
        $job_boot_results = [];

        $table_headers = [];

        $output3 = array();
        if (!empty($crown_parameters)) {
            foreach($crown_parameters as $key => $value) {
                $crown_count += $value['total_count'];
                $output_element = &$output3[$value['name'] . "_" . $value['door_width']];
                $output_element['name'] = $value['name'];
                $output_element['door_width'] = $value['door_width'];
    
                if (!isset($output_element['total_count']))
                    $output_element['total_count'] = $value['total_count'];
                else
                    $output_element['total_count'] += $value['total_count'];
            }    
        }
        $crowns = array_values($output3);
        
        $output4 = array();
        if (!empty($cube_parameters)) {
            $cubes_array = array_reduce($cube_parameters, 'array_merge', array());
            foreach($cubes_array as $key => $value) {
                if(is_numeric($key)){
                    $cube_count += $value['total_count'];
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

        $output5 = array();
        if (!empty($boot_parameters)) {
            $boots_array = array_reduce($boot_parameters, 'array_merge', array());
            foreach($boots_array as $key => $value) {
                $boot_count += $value['total_count'];
                if(is_numeric($key)){
                    $output_element = &$output5[$value['name']];
                    $output_element['name'] = $value['name'];
        
                    if (!isset($output_element['total_count'])){
                        $output_element['total_count'] = $value['total_count'];
                    } else {
                        $output_element['total_count'] += $value['total_count'];
                    }
                }
            }    
        }
        $boots = array_values($output5);

        $output7 = array();
        if (!empty($jamb_parameters)) {
            $jambs_array = array_reduce($jamb_parameters, 'array_merge', array());
            foreach($jambs_array as $key => $value) {
                $jamb_count += $value['count'];
                if (isset($value['id']) && !empty($value['id'])) {
                    $output_element = &$output7[$value['id'] . '_' . $value['name']];
                    if (!isset($output_element['name'])) {
                        $output_element['name']  = $value['name'];
                        $output_element['count'] = $value['count'];
                    } else {
                        $output_element['name']  = $value['name'];
                        $output_element['count'] += $value['count'];
                    }
                }
            }
        }
        $jambs = array_values($output7);
        
        $output8 = array();
        foreach ($transom_parameters as $item) {
            $transom_count += $item['width_count'] + $item['height_count'];
            $output_element = &$output8[$item['id'] . '_' . $item['width'] . '_' . $item['thickness']];
            if (!isset($output_element['name']) && !isset($output_element['width']) && !isset($output_element['thickness'])) {
                $output_element['name']         = $item['name'];
                $output_element['size']         = $item['width'] . 'x' . $item['thickness'];
                $output_element['count']        = $item['width_count'];
            } else {
                $output_element['name']         = $item['name'];
                $output_element['size']         = $item['width'] . 'x' . $item['thickness'];
                $output_element['count']       += $item['width_count'];
            }

            $output_element2 = &$output8[$item['id'] . '_' . $item['height'] . '_' . $item['thickness']];
            if (!isset($output_element2['name']) && !isset($output_element2['height']) && !isset($output_element2['thickness'])) {
                $output_element2['name']          = $item['name'];
                $output_element2['size']           = $item['height'] . 'x' . $item['thickness'];
                $output_element2['count']         = $item['height_count'];
            } else {
                $output_element2['name']          = $item['name'];
                $output_element2['size']           = $item['height'] . 'x' . $item['thickness'];
                $output_element2['count']        += $item['height_count'];
            }
        }
        $transoms = array_values($output8);
        // dd($transoms);

        $job = DB::table('jobs')->find($order_process->job_id);
        switch ($order_process->product) {
            case 'jamb':
                $jamb_jobs = json_decode($job->jamb_job, true);
                $job_jamb_results = $jambs;
                break;
            case 'transom':
                $transom_jobs = json_decode($job->transom_job, true);
                $job_transom_results = $transoms;
                break;
            case 'crown':
                $crown_jobs = json_decode($job->crown_job, true);
                $job_crown_results = $crowns;
                break;
            case 'cube':
                $cube_jobs = json_decode($job->cube_job, true);
                $job_cube_results = $cubes;
                break;
            case 'boot':
                $boot_jobs = json_decode($job->boot_job, true);
                $job_boot_results = $boots;
                break;
            default:
                $output = json_decode($job->door_job, true);
                $door_jobs = [];
                foreach($output as $k => $v) {
                    $v['total_salary'] = 0;
                    $v['count'] = 0;
                    $v['framoga_count'] = 0;
                    $v['glass_count'] = 0;
                    $v['job_id'] = $order_process->job_id;
                    foreach($door_parameters as $key => $value) {
                        if (in_array($order_process->job_id,  array(2,7))) { // job_id = 7 (bu serverdagi presschini idsi), job_id = 2 bu serverdagi EMAL(G) idsi
                            if ($k == 1) {
                                if (isset($value['framogatype_id']) && $value['framogatype_id'] != "") {
                                    $v['framoga_count'] += $value['count'];
                                    $v['count'] += $value['count'];
                                    $v['total_salary'] += $value['count'] * $v['salary'];
                                }
                            } else {
                                $v['total_salary'] += $v['salary'] * $value['layer'] * $value['count'];
                                $v['count'] += $value['count'];
                            }
                        } else if ($order_process->job_id == 1){ // job_id = 1 bu serverdagi stolyarni idsi
                            if ($k == 2) {
                                if (isset($value['framogatype_id']) && $value['framogatype_id'] != "") {
                                    $v['framoga_count'] += $value['count'];
                                    $v['count'] += $value['count'];
                                    $v['total_salary'] += $value['count'] * $v['salary'];
                                }
                            } else {
                                $v['total_salary'] += $v['salary'] * $value['layer'] * $value['count'];
                                $v['count'] += $value['count'];
                            }
                        } else if ($order_process->job_id == 10) { // job_id = 10 bu serverdagi shisha o'rnatish idsi
                            if ($k == 1) {
                                if (isset($value['framogatype_id']) && $value['framogatype_id'] != "") {
                                    $v['framoga_count'] += $value['count'];
                                    $v['total_salary'] += $value['count'] * $v['salary'];
                                }
                            } else {
                                if (isset($value['glass_figure']) && !empty($value['glass_figure'])){
                                    $v['total_salary'] += $v['salary'] * $value['layer'] * $value['count'];
                                    $v['glass_count'] += $value['count'];
                                }
                            }
                        } else if ($order_process->job_id == 4){ // job_id = 4 bu serverdagi Emal (KDSPTJL) idsi
                            if ($k == 7) {
                                if (isset($value['framogatype_id']) && $value['framogatype_id'] != "") {
                                    $v['framoga_count'] += $value['count'];
                                    $v['count'] += $value['count'];
                                    $v['total_salary'] += $value['count'] * $v['salary'];
                                }
                            } else {
                                $v['total_salary'] += $v['salary'] * $value['layer'] * $value['count'];
                                $v['count'] += $value['count'];
                            }
                        } else if ($order_process->job_id == 3){ // job_id = 3 bu serverdagi EMAL(KL) idsi
                            if ($k == 2) {
                                if (isset($value['framogatype_id']) && $value['framogatype_id'] != "") {
                                    $v['framoga_count'] += $value['count'];
                                    $v['count'] += $value['count'];
                                    $v['total_salary'] += $value['count'] * $v['salary'];
                                }
                            } else {
                                $v['total_salary'] += $v['salary'] * $value['layer'] * $value['count'];
                                $v['count'] += $value['count'];
                            }
                        } else if ($order_process->job_id == 5){ // job_id = 5 bu serverdagi Emal (KDSPTL) idsi
                            if ($k == 6) {
                                if (isset($value['framogatype_id']) && $value['framogatype_id'] != "") {
                                    $v['framoga_count'] += $value['count'];
                                    $v['count'] += $value['count'];
                                    $v['total_salary'] += $value['count'] * $v['salary'];
                                }
                            } else {
                                $v['total_salary'] += $v['salary'] * $value['layer'] * $value['count'];
                                $v['count'] += $value['count'];
                            }
                        } else if ($order_process->job_id == 6){ // job_id = 6 bu serverdagi EMAL(KPL) idsi
                            if ($k == 3) {
                                if (isset($value['framogatype_id']) && $value['framogatype_id'] != "") {
                                    $v['framoga_count'] += $value['count'];
                                    $v['count'] += $value['count'];
                                    $v['total_salary'] += $value['count'] * $v['salary'];
                                }
                            } else {
                                $v['total_salary'] += $v['salary'] * $value['layer'] * $value['count'];
                                $v['count'] += $value['count'];
                            }
                        }  else {
                            $v['total_salary'] += $v['salary'] * $value['layer'] * $value['count'];
                            $v['count'] += $value['count'];
                        }
                    }
                    $door_jobs[$k] = $v;
                }
                $job_door_results = DB::select('SELECT '.$job->door_attributes.'
                                                FROM door_results
                                                WHERE door_id=?', [$order[0]->door_id]);
                $table_headers = DB::table('door_attributes')
                            ->whereIn('en_name', explode(",", $job->door_attributes))
                            ->get(['name', 'en_name']);
                break;
                
        }

        $data = array(
            'order'               => $order,
            'door'                => $door,
            'job'                 => $job,
            'job_door_results'    => $job_door_results,
            'door_jobs'           => $door_jobs,
            'job_jamb_results'    => $job_jamb_results,
            'jamb_jobs'           => $jamb_jobs,
            'jamb_count'          => $jamb_count,
            'job_transom_results' => $job_transom_results,
            'transom_jobs'        => $transom_jobs,
            'transom_count'       => $transom_count,
            'crown_jobs'          => $crown_jobs,
            'job_crown_results'   => $job_crown_results,
            'crown_count'         => $crown_count,
            'cube_jobs'           => $cube_jobs,
            'job_cube_results'    => $job_cube_results,
            'cube_count'          => $cube_count,
            'boot_jobs'           => $boot_jobs,
            'job_boot_results'    => $job_boot_results,
            'boot_count'          => $boot_count,
            'table_headers'       => $table_headers
        );
        
        // echo '<link rel="stylesheet" href="'.asset('assets/css/bootstrap.min.css').'">';
        $pdf = PDF::loadView('moderator.order.door_pdf', $data);
        return $pdf->download('Naryad jarayoni-' . $order_process_id . '.pdf');
    }

    public function jamb_job_assignment($order_process_id)
    {
        $order_process = DB::table('order_processes')->find($order_process_id);
        $order = DB::select('SELECT a.id, 
                                    a.phone_number, 
                                    a.contract_number, 
                                    a.last_contract_price, 
                                    a.deadline, 
                                    b.name as customer,
                                    b.type as customer_type,
                                    a.comments,
                                    a.created_at
                            FROM (orders a, customers b)
                            WHERE a.customer_id=b.id AND a.id=?', [$order_process->order_id]);

        $job = DB::table('jobs')->find($order_process->job_id);
        $jamb_jobs = json_decode($job->jamb_job, true);
        $job_jamb_results = DB::select('SELECT *
                                        FROM jamb_results
                                        WHERE order_id=?', [$order_process->order_id]);
        
        $data = array(
            'order'            => $order,
            'job'              => $job,
            'jamb_jobs'        => $jamb_jobs,
            'job_jamb_results' => $job_jamb_results
        );
        
        // echo '<link rel="stylesheet" href="'.asset('assets/css/bootstrap.min.css').'">';
        $pdf = PDF::loadView('moderator.order.jamb_pdf', $data);
        return $pdf->download('Naryad jarayoni-' . $order_process_id . '.pdf'); 
    }

    public function nsjamb_job_assignment($order_process_id)
    {
        $order_process = DB::table('order_processes')->find($order_process_id);
        $order = DB::select('SELECT a.id, 
                                    a.phone_number, 
                                    a.contract_number, 
                                    a.last_contract_price, 
                                    a.deadline, 
                                    b.name as customer,
                                    b.type as customer_type,
                                    a.comments,
                                    a.created_at
                            FROM (orders a, customers b)
                            WHERE a.customer_id=b.id AND a.id=?', [$order_process->order_id]);

        $job = DB::table('jobs')->find($order_process->job_id);
        $nsjamb_jobs = json_decode($job->nsjamb_job, true);
        $job_nsjamb_results = DB::select('SELECT *
                                           FROM nsjamb_results
                                           WHERE order_id=?', [$order_process->order_id]);
        
        $data = array(
            'order'               => $order,
            'job'                 => $job,
            'nsjamb_jobs'         => $nsjamb_jobs,
            'job_nsjamb_results'  => $job_nsjamb_results
        );
        
        // echo '<link rel="stylesheet" href="'.asset('assets/css/bootstrap.min.css').'">';
        $pdf = PDF::loadView('moderator.order.nsjamb_pdf', $data);
        return $pdf->download('Naryad jarayoni-' . $order_process_id . '.pdf'); 
    } 

    public function transom_job_assignment($order_process_id)
    {
        $order_process = DB::table('order_processes')->find($order_process_id);
        $order = DB::select('SELECT a.id, 
                                    a.phone_number, 
                                    a.contract_number, 
                                    a.last_contract_price, 
                                    a.deadline, 
                                    b.name as customer,
                                    b.type as customer_type,
                                    a.comments,
                                    a.created_at
                            FROM (orders a, customers b)
                            WHERE a.customer_id=b.id AND a.id=?', [$order_process->order_id]);

        $job = DB::table('jobs')->find($order_process->job_id);
        $transom_jobs = json_decode($job->transom_job, true);
        $job_transom_results = DB::select('SELECT *
                                           FROM transom_results
                                           WHERE order_id=?', [$order_process->order_id]);
        
        $data = array(
            'order'               => $order,
            'job'                 => $job,
            'transom_jobs'        => $transom_jobs,
            'job_transom_results' => $job_transom_results
        );
        
        // echo '<link rel="stylesheet" href="'.asset('assets/css/bootstrap.min.css').'">';
        $pdf = PDF::loadView('moderator.order.transom_pdf', $data);
        return $pdf->download('Naryad jarayoni-' . $order_process_id . '.pdf'); 
    }
    
    public function jamb_transom_job_assignment($order_process_id)
    {
        $order_process = DB::table('order_processes')->find($order_process_id);
        $order = DB::select('SELECT a.id, 
                                    a.phone_number, 
                                    a.contract_number, 
                                    a.last_contract_price, 
                                    a.deadline, 
                                    b.name as customer,
                                    b.type as customer_type,
                                    a.comments,
                                    a.created_at
                            FROM (orders a, customers b)
                            WHERE a.customer_id=b.id AND a.id=?', [$order_process->order_id]);
        
        $job = DB::table('jobs')->find($order_process->job_id);
        
        $jamb_jobs = [];
        $job_jamb_results = [];
        $transom_jobs = [];
        $job_transom_results = [];

        switch ($order_process->product) {
            case 'jamb':
                $jamb_jobs = json_decode($job->jamb_job, true);
                $job_jamb_results = DB::select('SELECT *
                                                FROM jamb_results
                                                WHERE order_id=?', [$order_process->order_id]);
                break;
            default:
                $transom_jobs = json_decode($job->transom_job, true);
                $job_transom_results = DB::select('SELECT *
                                                FROM transom_results
                                                WHERE order_id=?', [$order_process->order_id]);
                break;
        }

        
        $data = array(
            'order'               => $order,
            'job'                 => $job,
            'jamb_jobs'           => $jamb_jobs,
            'job_jamb_results'    => $job_jamb_results,
            'transom_jobs'        => $transom_jobs,
            'job_transom_results' => $job_transom_results
        );
        
        // echo '<link rel="stylesheet" href="'.asset('assets/css/bootstrap.min.css').'">';
        $pdf = PDF::loadView('moderator.order.jamb_transom_pdf', $data);
        return $pdf->download('Naryad jarayoni-' . $order_process_id . '.pdf'); 
    }

    public function crown_boot_cube_job_assignment($order_process_id)
    {
        $order_process = DB::table('order_processes')->find($order_process_id);
        $order = DB::select('SELECT a.id, 
                                    a.phone_number, 
                                    a.contract_number, 
                                    a.last_contract_price, 
                                    a.deadline, 
                                    b.name as customer,
                                    b.type as customer_type,
                                    a.comments,
                                    a.created_at
                            FROM (orders a, customers b)
                            WHERE a.customer_id=b.id AND a.id=?', [$order_process->order_id]);
        
        $job = DB::table('jobs')->find($order_process->job_id);
        
        $jamb_jobs = [];
        $jamb_job_results = [];
        $crown_jobs = [];
        $crown_job_results = [];
        $boot_jobs = [];
        $boot_job_results = [];
        $cube_jobs = [];
        $cube_job_results = [];

        switch ($order_process->product) {
            case 'jamb':
                $jamb_jobs = json_decode($job->jamb_job, true);
                $jamb_job_results = DB::select('SELECT *
                                                FROM jamb_results
                                                WHERE order_id=?', [$order_process->order_id]);
                break;
            case 'crown':
                $crown_jobs = json_decode($job->crown_job, true);
                $crown_job_results = DB::select('SELECT *
                                                FROM crown_results
                                                WHERE order_id=?', [$order_process->order_id]);
                break;
            case 'cube':
                $cube_jobs = json_decode($job->cube_job, true);
                $cube_job_results = DB::select('SELECT *
                                                FROM cube_results
                                                WHERE order_id=?', [$order_process->order_id]);
                break;
            default:
                $boot_jobs = json_decode($job->boot_job, true);
                $boot_job_results = DB::select('SELECT *
                                                FROM boot_results
                                                WHERE order_id=?', [$order_process->order_id]);
                break;
        }

        
        $data = array(
            'order'             => $order,
            'job'               => $job,
            'jamb_jobs'         => $jamb_jobs,
            'jamb_job_results'  => $jamb_job_results,
            'crown_jobs'        => $crown_jobs,
            'crown_job_results' => $crown_job_results,
            'boot_jobs'         => $boot_jobs,
            'boot_job_results'  => $boot_job_results,
            'cube_jobs'         => $cube_jobs,
            'cube_job_results'  => $cube_job_results
        );
        
        // echo '<link rel="stylesheet" href="'.asset('assets/css/bootstrap.min.css').'">';
        $pdf = PDF::loadView('moderator.order.crown_boot_cube_pdf', $data);
        return $pdf->download('Naryad jarayoni-' . $order_process_id . '.pdf'); 
    }

    // Skladga yuk yuborish
    public function moderator_orderwaybill_pdf(Request $request)
    {
        if (isset($request->waybill_id)) {
            $waybill = DB::select('SELECT a.id,
                                          a.order_id, 
                                          a._from, 
                                          a._to, 
                                          a.day, 
                                          b.name as driver, 
                                          b.gov_number,
                                          a.sended_details
                                   FROM waybills a
                                   INNER JOIN drivers b ON a.driver_id=b.id
                                   WHERE a.id=?', [$request->waybill_id]);
            $order = DB::select('SELECT a.id, 
                                        a.phone_number, 
                                        a.contract_number, 
                                        a.moderator_send,
                                        a.deadline, 
                                        a.product,
                                        b.name AS customer,
                                        b.type AS customer_type,
                                        a.created_at
                                FROM (orders a, customers b)
                                WHERE a.customer_id=b.id AND a.id=?', [$waybill[0]->order_id]);
            $sended_details = json_decode($waybill[0]->sended_details, true);

            $data = array(
                'order'          => $order,
                'waybill'        => $waybill,
                'sended_details' => $sended_details
            );
            // echo '<link rel="stylesheet" href="'.asset('assets/css/bootstrap.min.css').'">';
            $pdf = PDF::loadView('moderator.order.waybill_nakladnaya', $data);
            return $pdf->download('Nakladnaya (naryad raqami №' . $order[0]->contract_number . ').pdf');
            // return redirect()->route('moderator-waybill-show', $request->waybill_id);
        } else {
            if (isset($request->driver_id) && !empty($request->driver_id))
                $driver_id = $request->driver_id;
            if (isset($request->courier_id) && !empty($request->courier_id))
                $driver_id = $request->courier_id;
            if (!empty($driver_id)) {
                $sended_details = array();
                for($i = 0; $i < count($request->sended_detail); $i++) {
                    if (!empty($request['sended_detail'][$i]) && !empty($request['detail_count'][$i])) {
                        $sended_details[$i]['name'] = $request['sended_detail'][$i];
                        $sended_details[$i]['count'] = $request['detail_count'][$i];
                    }
                }
                
                $sended_details = array_values($sended_details);
                if (!empty($sended_details)) {
                    $waybillID = DB::table('waybills')->insertGetId([
                        'order_id'       => $request->order_id,
                        'driver_id'      => $driver_id,
                        '_from'          => $request->_from,
                        '_to'            => $request->_to,
                        'product'        => $request->product,
                        'details'        => $request->details,
                        'sended_details' => json_encode($sended_details),
                        'day'            => date('Y-m-d')
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
                                    WHERE a.id=?', [$waybillID]);
                $order = DB::select('SELECT a.id, 
                                            a.phone_number, 
                                            a.contract_number, 
                                            a.moderator_send,
                                            a.deadline, 
                                            a.product,
                                            b.name AS customer,
                                            b.type AS customer_type,
                                            a.created_at
                                    FROM (orders a, customers b)
                                    WHERE a.customer_id=b.id AND a.id=?', [$request->order_id]);
                $data = array(
                    'order'          => $order,
                    'waybill'        => $waybill,
                    'sended_details' => $sended_details
                );
                // echo '<link rel="stylesheet" href="'.asset('assets/css/bootstrap.min.css').'">';
                $pdf = PDF::loadView('moderator.order.waybill_nakladnaya', $data);
                return $pdf->download('Nakladnaya (naryad raqami №' . $order[0]->contract_number . ').pdf');
                // return redirect()->route('form-outfit', $request->order_id);
            } else {
                return redirect()->back()->with('message', "Xatolik mavjud. Iltimos tekshirib qayta jo'nating.");
            }
        }
        
    }

    // Naryadni yakunlab eshikni skladga jo'natish
    public function door_outfit_closed(Request $request) 
    {
        if (isset($request->driver_id) && !empty($request->driver_id))
            $driver_id = $request->driver_id;
        if (isset($request->courier_id) && !empty($request->courier_id))
            $driver_id = $request->courier_id;
        
        $order = DB::select('SELECT a.id, 
                                    a.door_id, 
                                    a.phone_number, 
                                    a.contract_number, 
                                    a.moderator_send,
                                    a.deadline, 
                                    a.product,
                                    b.name AS customer,
                                    b.type AS customer_type,
                                    a.created_at
                            FROM (orders a, customers b)
                            WHERE a.customer_id=b.id AND a.id=?', [$request->order_id]);

        $door = DB::select('SELECT a.*, b.name AS doortype 
                            FROM doors a 
                            INNER JOIN doortypes b ON a.doortype_id=b.id
                            WHERE a.id=?', [$order[0]->door_id]);

        $check_waybill = DB::select('SELECT * FROM waybills WHERE order_id=?', [$request->order_id]);
        if (empty($check_waybill)){
            DB::table('waybills')->insert([
                'order_id'  => $request->order_id,
                'driver_id' => $driver_id,
                '_from'     => $request->_from,
                '_to'       => $request->_to,
                'doortype'  => $door[0]->door_parameters,
                'jamb'      => $door[0]->jamb_parameters,
                'transom'   => $door[0]->transom_parameters,
                'product'   => 'door',
                'day'       => date('Y-m-d')
            ]);

            Order::where('id', $request->order_id)->update([
                'moderator_send' => 1,
                'moderator_send_time' => date('Y-m-d H:i:s'),
                'job_name' => 'Yakunlangan'
            ]);
        }

        $door_parameters = json_decode($door[0]->door_parameters, true);
        // dd($door_parameters);
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

        $burunduq_count = 0;
        foreach($door_parameters as $key => $value) {
            // Eshikni hisbolash
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
            $box_output_element1 = &$box_width_output[$value['doortype'] . "_" .$value['height']];
            $box_output_element2 = &$box_height_output[$value['doortype'] . "_" .$value['width']];

            $box_output_element1['size'] = $value['height'];
            $box_output_element1['doortype'] = $value['doortype'];
            $box_output_element2['size'] = $value['width'];
            $box_output_element2['doortype'] = $value['doortype'];
            
            !isset($box_output_element1['count']) && $box_output_element1['count'] = 0;
            !isset($box_output_element2['count']) && $box_output_element2['count'] = 0;
            
            if (!isset($box_output_element1['doortype']) && !isset($box_output_element1['height'])){
                $box_output_element1['count'] = 2 * $value['count'];
            } else {
                $box_output_element1['count'] += 2 * $value['count'];
            }

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
            } else {
                $output_element['name'] = $item['name'];
                $output_element['height_count'] += $item['height_count'];
                $output_element['width_count'] += $item['width_count'];
                $output_element['width'] = $item['width'];
                $output_element['height'] = $item['height'];
                $output_element['thickness'] = $item['thickness'];
            }
        }
        $transoms = array_values($transoms);
        $output3 = array();
        if (!is_null($crown_parameters)) {
            foreach($crown_parameters as $key => $value) {
                $output_element = &$output3[$value['name'] . "_" . $value['door_width']];
                $output_element['name'] = $value['name'];
                $output_element['door_width'] = $value['door_width'];
                $output_element['price'] = $value['price'];
    
                if (!isset($output_element['total_count']) && !isset($output_element['total_price']))
                    $output_element['total_count'] = $value['total_count'];
                else
                    $output_element['total_count'] += $value['total_count'];
            }    
        }
        $crowns = array_values($output3);
        
        $output4 = array();
        if (!is_null($cube_parameters)) {
            $cubes_array = array_reduce($cube_parameters, 'array_merge', array());
            foreach($cubes_array as $key => $value) {
                if(is_numeric($key)){
                    $output_element = &$output4[$value['name']];
                    $output_element['name'] = $value['name'];
                    $output_element['price'] = $value['price'];
        
                    if (!isset($output_element['total_count']) && !isset($output_element['total_price']))
                        $output_element['total_count'] = $value['total_count'];
                    else
                        $output_element['total_count'] += $value['total_count'];
                }
            }    
        }
        $cubes = array_values($output4);

        $output5 = array();
        if (!is_null($boot_parameters)) {
            $boots_array = array_reduce($boot_parameters, 'array_merge', array());
            foreach($boots_array as $key => $value) {
                if(is_numeric($key)){
                    $output_element = &$output5[$value['name']];
                    $output_element['name'] = $value['name'];
                    $output_element['price'] = $value['price'];
        
                    if (!isset($output_element['total_count']) && !isset($output_element['total_price']))
                        $output_element['total_count'] = $value['total_count'];
                    else
                        $output_element['total_count'] += $value['total_count'];
                }
            }    
        }
        $boots = array_values($output5);

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
            'order'          => $order,
            'waybill'        => $waybill,
            'door'           => $door,
            'doortypes'      => $doortypes,
            'jambs'          => $jambs,
            'transoms'       => $transoms,
            'crowns'         => $crowns,
            'cubes'          => $cubes,
            'boots'          => $boots,
            'width_boxes'    => $width_boxes,
            'height_boxes'   => $height_boxes,
            'framugas'       => $framugas,
            'burunduq_count' => $burunduq_count
        );
        // echo '<link rel="stylesheet" href="'.asset('assets/css/bootstrap.min.css').'">';
        $pdf = PDF::loadView('moderator.order.door_nakladnaya', $data);
        return $pdf->download('Nakladnaya (naryad raqami №' . $order[0]->contract_number . ').pdf');
    }

    // Naryadni yakunlab nalichnikni skladga jo'natish
    public function jamb_outfit_closed(Request $request) 
    {
        if (isset($request->driver_id) && !empty($request->driver_id))
            $driver_id = $request->driver_id;
        if (isset($request->courier_id) && !empty($request->courier_id))
            $driver_id = $request->courier_id;

        $jambs = DB::table('jamb_results')->where('order_id', $request->order_id)->get();
        $jamb_parameters = array(array(
            'id'          => '',
            'name'        => '', 
            'price'       => 0,
            'count'       => 0,
            'total_price' => 0
        ));
        
        foreach($jambs as $key => $value){
            $jamb_parameters[$key]['id'] = $value->jamb_id;
            $jamb_parameters[$key]['name'] = $value->name;
            $jamb_parameters[$key]['price'] = $value->price;
            $jamb_parameters[$key]['total_price'] = $value->total_price;
            $jamb_parameters[$key]['count'] = $value->count;
        }

        $check_waybill = DB::select('SELECT * FROM waybills WHERE order_id=?', [$request->order_id]);
        if (empty($check_waybill)){
            DB::table('waybills')->insert([
                'order_id'  => $request->order_id,
                'driver_id' => $driver_id,
                '_from'     => $request->_from,
                '_to'       => $request->_to,
                'jamb'      => json_encode($jamb_parameters),
                'product'   => 'jamb',
                'day'       => date('Y-m-d')
            ]);

            Order::where('id', $request->order_id)->update([
                'moderator_send' => 1,
                'moderator_send_time' => date('Y-m-d H:i:s'),
                'job_name' => 'Yakunlangan'
            ]);
        }

        $order = DB::select('SELECT a.id, 
                                    a.phone_number, 
                                    a.contract_number, 
                                    a.moderator_send,
                                    a.deadline, 
                                    a.product,
                                    b.name as customer,
                                    b.type as customer_type,
                                    a.created_at
                             FROM (orders a, customers b)
                             WHERE a.customer_id=b.id AND a.id=?', [$request->order_id]);

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
            'order'   => $order,
            'waybill' => $waybill,
            'jambs'   => $jambs
        );
        // echo '<link rel="stylesheet" href="'.asset('assets/css/bootstrap.min.css').'">';
        $pdf = PDF::loadView('moderator.order.jamb_nakladnaya', $data);
        return $pdf->download('Nakladnaya (naryad raqami №' . $order[0]->contract_number . ').pdf');
    }
    
    // Naryadni yakunlab nostandart nalichnikni skladga jo'natish
    public function nsjamb_outfit_closed(Request $request)
    {
        if (isset($request->driver_id) && !empty($request->driver_id))
            $driver_id = $request->driver_id;
        if (isset($request->courier_id) && !empty($request->courier_id))
            $driver_id = $request->courier_id;
        
        $order = DB::select('SELECT a.id, 
                                    a.phone_number, 
                                    a.contract_number, 
                                    a.moderator_send,
                                    a.deadline, 
                                    a.product,
                                    b.name as customer,
                                    b.type as customer_type,
                                    a.created_at
                            FROM (orders a, customers b)
                            WHERE a.customer_id=b.id AND a.id=?', [$request->order_id]);

        $nsjambs = DB::table('nsjamb_results')->where('order_id', $request->order_id)->get();
        $jamb_parameters = array(array(
            'id'            => '',
            'name'          => '', 
            'height'        => '',
            'width_top'     => '',
            'width_bottom'  => '',
            'price'         => 0,
            'count'         => 0,
            'total_price'   => 0
        ));

        foreach($nsjambs as $key => $value){
            $jamb_parameters[$key]['id'] = $value->nsjamb_id;
            $jamb_parameters[$key]['name'] = $value->nsjamb_name;
            $jamb_parameters[$key]['height'] = $value->height;
            $jamb_parameters[$key]['width_top'] = $value->width_top;
            $jamb_parameters[$key]['width_bottom'] = $value->width_bottom;
            $jamb_parameters[$key]['total_price'] = $value->total_price;
            $jamb_parameters[$key]['count'] = $value->count;
        }

        $check_waybill = DB::select('SELECT * FROM waybills WHERE order_id=?', [$request->order_id]);
        if (empty($check_waybill)){
            DB::table('waybills')->insert([
                'order_id'  => $request->order_id,
                'driver_id' => $driver_id,
                '_from'     => $request->_from,
                '_to'       => $request->_to,
                'nsjamb'    => json_encode($jamb_parameters),
                'product'   => 'nsjamb',
                'day'       => date('Y-m-d')
            ]);

            Order::where('id', $request->order_id)->update([
                'moderator_send' => 1,
                'moderator_send_time' => date('Y-m-d H:i:s'),
                'job_name' => 'Yakunlangan'
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
            'order'   => $order,
            'waybill' => $waybill,
            'nsjambs' => $nsjambs
        );
        // echo '<link rel="stylesheet" href="'.asset('assets/css/bootstrap.min.css').'">';
        $pdf = PDF::loadView('moderator.order.nsjamb_nakladnaya', $data);
        return $pdf->download('Nakladnaya (naryad raqami №' . $order[0]->contract_number . ').pdf');
    }
    
    // Naryadni yakunlab doborni skladga jo'natish
    public function transom_outfit_closed(Request $request) 
    {
        if (isset($request->driver_id) && !empty($request->driver_id))
            $driver_id = $request->driver_id;
        if (isset($request->courier_id) && !empty($request->courier_id))
            $driver_id = $request->courier_id;

        $order = DB::select('SELECT a.id, 
                                    a.phone_number, 
                                    a.contract_number, 
                                    a.moderator_send,
                                    a.deadline, 
                                    a.product,
                                    b.name as customer,
                                    b.type as customer_type,
                                    a.created_at
                            FROM (orders a, customers b)
                            WHERE a.customer_id=b.id AND a.id=?', [$request->order_id]);

        $transoms = DB::table('transom_results')->where('order_id', $request->order_id)->get();

        $transom_parameters = array(array(
            'id' => '', // transom_id
            'name' => '', // name
            'price' => 0, // price
            'total_price' => 0, //total_price
            'height' => 0, // height
            'width_top' => 0, // width_top
            'width_bottom' => 0, // width_bottom
            'count' => 0 // count
        ));

        foreach($transoms as $key => $value){
            $transom_parameters[$key]['id'] = $value->transom_id;
            $transom_parameters[$key]['name'] = $value->name;
            $transom_parameters[$key]['price'] = $value->price;
            $transom_parameters[$key]['total_price'] = $value->total_price;
            $transom_parameters[$key]['height'] = $value->height;
            $transom_parameters[$key]['width_top'] = $value->width_top;
            $transom_parameters[$key]['width_bottom'] = $value->width_bottom;
            $transom_parameters[$key]['count'] = $value->count;
        }

        $check_waybill = DB::select('SELECT * FROM waybills WHERE order_id=?', [$request->order_id]);
        if (empty($check_waybill)){
            DB::table('waybills')->insert([
                'order_id'  => $request->order_id,
                'driver_id' => $driver_id,
                '_from'     => $request->_from,
                '_to'       => $request->_to,
                'transom'   => json_encode($transom_parameters),
                'product'   => 'transom',
                'day'       => date('Y-m-d')
            ]);
            
            Order::where('id', $request->order_id)->update([
                'moderator_send' => 1,
                'moderator_send_time' => date('Y-m-d H:i:s'),
                'job_name' => 'Yakunlangan'
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
            'transoms'   => $transoms
        );
        // echo '<link rel="stylesheet" href="'.asset('assets/css/bootstrap.min.css').'">';
        $pdf = PDF::loadView('moderator.order.transom_nakladnaya', $data);
        return $pdf->download('Nakladnaya (naryad raqami №' . $order[0]->contract_number . ').pdf');
    }

    // Naryadni yakunlab nalichnik va doborni skladga jo'natish
    public function jamb_transom_outfit_closed(Request $request) 
    {
        if (isset($request->driver_id) && !empty($request->driver_id))
            $driver_id = $request->driver_id;
        if (isset($request->courier_id) && !empty($request->courier_id))
            $driver_id = $request->courier_id;
        
        $order = DB::select('SELECT a.id, 
                                    a.phone_number, 
                                    a.contract_number, 
                                    a.moderator_send,
                                    a.deadline, 
                                    a.product,
                                    b.name as customer,
                                    b.type as customer_type,
                                    a.created_at
                            FROM (orders a, customers b)
                            WHERE a.customer_id=b.id AND a.id=?', [$request->order_id]);

        $jambs = DB::table('jamb_results')->where('order_id', $request->order_id)->get();
        $transoms = DB::table('transom_results')->where('order_id', $request->order_id)->get();

        $transom_parameters = array(array(
            'id' => '', // transom_id
            'name' => '', // name
            'price' => 0, // price
            'total_price' => 0, //total_price
            'height' => 0, // height
            'width_top' => 0, // width_top
            'width_bottom' => 0, // width_bottom
            'count' => 0 // count
        ));

        $jamb_parameters = array(array(
            'id'          => '',
            'name'        => '', 
            'price'       => 0,
            'count'       => 0,
            'total_price' => 0
        ));

        foreach($jambs as $key => $value){
            $jamb_parameters[$key]['id'] = $value->jamb_id;
            $jamb_parameters[$key]['name'] = $value->name;
            $jamb_parameters[$key]['price'] = $value->price;
            $jamb_parameters[$key]['total_price'] = $value->total_price;
            $jamb_parameters[$key]['count'] = $value->count;
        }

        foreach($transoms as $key => $value){
            $transom_parameters[$key]['id'] = $value->transom_id;
            $transom_parameters[$key]['name'] = $value->name;
            $transom_parameters[$key]['price'] = $value->price;
            $transom_parameters[$key]['total_price'] = $value->total_price;
            $transom_parameters[$key]['height'] = $value->height;
            $transom_parameters[$key]['width_top'] = $value->width_top;
            $transom_parameters[$key]['width_bottom'] = $value->width_bottom;
            $transom_parameters[$key]['count'] = $value->count;
        }

        $check_waybill = DB::select('SELECT * FROM waybills WHERE order_id=?', [$request->order_id]);
        if (empty($check_waybill)){
            DB::table('waybills')->insert([
                'order_id'  => $request->order_id,
                'driver_id' => $driver_id,
                '_from'     => $request->_from,
                '_to'       => $request->_to,
                'jamb'      => json_encode($jamb_parameters),
                'transom'   => json_encode($transom_parameters),
                'product'   => 'jamb+transom',
                'day'       => date('Y-m-d')
            ]);
            
            Order::where('id', $request->order_id)->update([
                'moderator_send' => 1,
                'moderator_send_time' => date('Y-m-d H:i:s'),
                'job_name' => 'Yakunlangan'
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
            'jambs'      => $jambs,
            'transoms'   => $transoms
        );
        // echo '<link rel="stylesheet" href="'.asset('assets/css/bootstrap.min.css').'">';
        $pdf = PDF::loadView('moderator.order.jamb_transom_nakladnaya', $data);
        return $pdf->download('Nakladnaya (naryad raqami №' . $order[0]->contract_number . ').pdf');
    }
    
    // Naryadni yakunlab nalichnik+korona+sapog+kubikni skladga jo'natish
    public function crownbootcube_outfit_closed(Request $request)
    {
        if (isset($request->driver_id) && !empty($request->driver_id))
            $driver_id = $request->driver_id;
        if (isset($request->courier_id) && !empty($request->courier_id))
            $driver_id = $request->courier_id;
        
        $order = DB::select('SELECT a.id, 
                                    a.phone_number, 
                                    a.contract_number, 
                                    a.moderator_send,
                                    a.deadline, 
                                    a.product,
                                    b.name as customer,
                                    b.type as customer_type,
                                    a.created_at
                            FROM (orders a, customers b)
                            WHERE a.customer_id=b.id AND a.id=?', [$request->order_id]);

        $jambs = DB::table('jamb_results')->where('order_id', $request->order_id)->get();
        $crowns = DB::table('crown_results')->where('order_id', $request->order_id)->get();
        $cubes = DB::table('cube_results')->where('order_id', $request->order_id)->get();
        $boots = DB::table('boot_results')->where('order_id', $request->order_id)->get();
        $check_waybill = DB::select('SELECT * FROM waybills WHERE order_id=?', [$request->order_id]);
        if (empty($check_waybill)){
            DB::table('waybills')->insert([
                'order_id'  => $request->order_id,
                'driver_id' => $driver_id,
                '_from'     => $request->_from,
                '_to'       => $request->_to,
                'jamb'      => json_encode($jambs),
                'crown'     => json_encode($crowns),
                'cube'      => json_encode($cubes),
                'boot'      => json_encode($boots),
                'product'   => 'ccbj',
                'day'       => date('Y-m-d')
            ]);
            
            Order::where('id', $request->order_id)->update([
                'moderator_send' => 1,
                'moderator_send_time' => date('Y-m-d H:i:s'),
                'job_name' => 'Yakunlangan'
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
            'order'   => $order,
            'waybill' => $waybill,
            'jambs'   => $jambs,
            'crowns'  => $crowns,
            'boots'   => $boots,
            'cubes'   => $cubes
        );
        // echo '<link rel="stylesheet" href="'.asset('assets/css/bootstrap.min.css').'">';
        $pdf = PDF::loadView('moderator.order.crown_boot_cube_nakladnaya', $data);
        return $pdf->download('Nakladnaya (naryad raqami №' . $order[0]->contract_number . ').pdf');
    }

    public function moderator_doorshow_pdf(Request $request)
    {
        $order = DB::select('SELECT a.id, 
                                    a.door_id,
                                    a.phone_number, 
                                    a.contract_number, 
                                    a.moderator_send,
                                    a.deadline, 
                                    b.name as customer,
                                    b.type as customer_type,
                                    a.comments,
                                    a.created_at
                             FROM (orders a, customers b)
                             WHERE a.customer_id=b.id AND a.id=?', [$request->order_id]);
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
            'order'             => $order,
            'door'              => $door,
            'door_parameters'   => $door_parameters,
            'doortypes'         => $doortypes,
            'layers'            => $layers,
            'depths'            => $depths,
            'locktypes'         => $locktypes,
            'ornamenttypes'     => $ornamenttypes,
            'loops'             => $loops,
            'jambs'             => $jambs,
            'transoms'          => $transoms,
            'glasses'           => $glasses,
            'crowns'            => $crowns,
            'cubes'             => $cubes,
            'boots'             => $boots
        );

         // echo '<link rel="stylesheet" href="'.asset('assets/css/bootstrap.min.css').'">';
         $pdf = PDF::loadView('moderator.order.doorshow_pdf', $data);
         return $pdf->download('Naryad №' . $order[0]->contract_number . ' ma\'lumotlari.pdf');
    }   

    public function moderator_jambshow_pdf(Request $request)
    {
        $order = DB::select('SELECT a.id, 
                                    a.phone_number, 
                                    a.contract_number, 
                                    a.moderator_send,
                                    a.deadline, 
                                    b.name as customer,
                                    b.type as customer_type,
                                    a.comments,
                                    a.created_at
                             FROM (orders a, customers b)
                             WHERE a.customer_id=b.id AND a.id=?', [$request->order_id]);

        $jamb_results = DB::select('SELECT * FROM jamb_results WHERE order_id=?', [$order[0]->id]);

        $data = array(
            'order'         => $order,
            'jamb_results'  => $jamb_results
        );

         // echo '<link rel="stylesheet" href="'.asset('assets/css/bootstrap.min.css').'">';
         $pdf = PDF::loadView('moderator.order.jambshow_pdf', $data);
         return $pdf->download('Naryad №' . $order[0]->contract_number . ' ma\'lumotlari.pdf');
    }

    public function moderator_nsjambshow_pdf(Request $request)
    {
        $order = DB::select('SELECT a.id,
                                    a.phone_number, 
                                    a.contract_number, 
                                    a.moderator_send,
                                    a.deadline, 
                                    b.name as customer,
                                    b.type as customer_type,
                                    a.comments,
                                    a.created_at
                             FROM (orders a, customers b)
                             WHERE a.customer_id=b.id AND a.id=?', [$request->order_id]);

        $nsjamb_results = DB::select('SELECT * FROM nsjamb_results WHERE order_id=?', [$order[0]->id]);

        $data = array(
            'order'          => $order,
            'nsjamb_results' => $nsjamb_results
        );

         // echo '<link rel="stylesheet" href="'.asset('assets/css/bootstrap.min.css').'">';
         $pdf = PDF::loadView('moderator.order.nsjambshow_pdf', $data);
         return $pdf->download('Naryad №' . $order[0]->contract_number . ' ma\'lumotlari.pdf');
    }

    public function moderator_transomshow_pdf(Request $request)
    {
        $order = DB::select('SELECT a.id,
                                    a.phone_number, 
                                    a.contract_number, 
                                    a.moderator_send,
                                    a.deadline, 
                                    b.name as customer,
                                    b.type as customer_type,
                                    a.comments,
                                    a.created_at
                             FROM (orders a, customers b)
                             WHERE a.customer_id=b.id AND a.id=?', [$request->order_id]);

        $transom_results = DB::select('SELECT * FROM transom_results WHERE order_id=?', [$order[0]->id]);

        $data = array(
            'order'           => $order,
            'transom_results' => $transom_results
        );

         // echo '<link rel="stylesheet" href="'.asset('assets/css/bootstrap.min.css').'">';
         $pdf = PDF::loadView('moderator.order.transomshow_pdf', $data);
         return $pdf->download('Naryad №' . $order[0]->contract_number . ' ma\'lumotlari.pdf');
    }

    public function moderator_jambtransomshow_pdf(Request $request)
    {
        $order = DB::select('SELECT a.id,
                                    a.phone_number, 
                                    a.contract_number, 
                                    a.moderator_send,
                                    a.deadline, 
                                    b.name as customer,
                                    b.type as customer_type,
                                    a.comments,
                                    a.created_at
                             FROM (orders a, customers b)
                             WHERE a.customer_id=b.id AND a.id=?', [$request->order_id]);

        $transom_results = DB::select('SELECT * FROM transom_results WHERE order_id=?', [$order[0]->id]);
        $jamb_results = DB::select('SELECT * FROM jamb_results WHERE order_id=?', [$order[0]->id]);
        $data = array(
            'order'           => $order,
            'transom_results' => $transom_results,
            'jamb_results'    => $jamb_results
        );

         // echo '<link rel="stylesheet" href="'.asset('assets/css/bootstrap.min.css').'">';
         $pdf = PDF::loadView('moderator.order.jambtransomshow_pdf', $data);
         return $pdf->download('Naryad №' . $order[0]->contract_number . ' ma\'lumotlari.pdf');
    }

    public function moderator_crownbootcubeshow_pdf(Request $request)
    {
        $order = DB::select('SELECT a.id,
                                    a.phone_number, 
                                    a.contract_number, 
                                    a.moderator_send,
                                    a.deadline, 
                                    b.name as customer,
                                    b.type as customer_type,
                                    a.comments,
                                    a.created_at
                             FROM (orders a, customers b)
                             WHERE a.customer_id=b.id AND a.id=?', [$request->order_id]);
        $jamb_results = DB::select('SELECT * FROM jamb_results WHERE order_id=?', [$order[0]->id]);
        $crown_results = DB::select('SELECT * FROM crown_results WHERE order_id=?', [$order[0]->id]);
        $boot_results = DB::select('SELECT * FROM boot_results WHERE order_id=?', [$order[0]->id]);
        $cube_results = DB::select('SELECT * FROM cube_results WHERE order_id=?', [$order[0]->id]);
        $data = array(
            'order'         => $order,
            'jamb_results'  => $jamb_results,
            'crown_results' => $crown_results,
            'boot_results'  => $boot_results,
            'cube_results'  => $cube_results,
        );

         // echo '<link rel="stylesheet" href="'.asset('assets/css/bootstrap.min.css').'">';
         $pdf = PDF::loadView('moderator.order.crown_boot_cubeshow_pdf', $data);
         return $pdf->download('Naryad №' . $order[0]->contract_number . ' ma\'lumotlari.pdf');
    }
}
