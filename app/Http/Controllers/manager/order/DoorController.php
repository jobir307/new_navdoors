<?php

namespace App\Http\Controllers\manager\order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Door;
use App\Models\Order;
use App\Models\Depth;
use App\Models\Layer;
use App\Models\Dealer;
use App\Models\Locktype;
use App\Models\Framogatype;
use App\Models\Framogafigure;
use App\Models\Transom;
use App\Models\Ornamenttype;
use App\Models\Doortype;
use App\Models\Jamb;
use App\Models\Loop;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Glass;

use Illuminate\Support\Facades\Auth;

class DoorController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $doortypes = DB::select('SELECT id, name FROM doortypes ORDER BY name');
        $customers = DB::select('SELECT id, name FROM customers WHERE type="Xaridor" ORDER BY created_at DESC');
        $dealers = DB::select('SELECT id, name FROM customers WHERE type="Diler" ORDER BY created_at DESC');
        $depths = DB::table('depths')->get(['id', 'name']);
        $layers = DB::table('layers')->get(['id', 'name']);
        $ornamenttypes = DB::table('ornamenttypes')->get(['id', 'name']);
        $locktypes = DB::table('locktypes')->get(['id', 'name']);
        $framogatypes = DB::table('framogatypes')->get(['id', 'name']);
        $framogafigures = DB::table('framogafigures')->get(['id', 'name']);
        $jambs = DB::select('SELECT id, name FROM jambs WHERE doortype_id=?', [$doortypes[0]->id]);
        $loops = DB::table('loops')->get(['id', 'name']);
        $glass_figures = DB::select('SELECT DISTINCT a.id, a.name, a.path
                                     FROM glass_figures a
                                     INNER JOIN glasses b ON b.glassfigure_id=a.id');
        $data = array(
            'doortypes'      => $doortypes,
            'customers'      => $customers,
            'dealers'        => $dealers,
            'depths'         => $depths,
            'layers'         => $layers,
            'ornamenttypes'  => $ornamenttypes,
            'locktypes'      => $locktypes,
            'framogatypes'   => $framogatypes,
            'framogafigures' => $framogafigures,
            'jambs'          => $jambs,
            'loops'          => $loops,
            'glass_figures'  => $glass_figures
        );

        return view('manager.order.door.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $door_parameters = array(array(
            'count'                     => 0,
            'layer'                     => '', 
            'layer_price'               => 0, 
            'total_layer_price'         => '',
            'depth'                     => '', 
            'depth_price'               => 0, 
            'total_depth_price'         => 0,
            'locktype'                  => '', 
            'locktype_price'            => 0, 
            'total_locktype_price'      => 0,
            'framogatype_id'            => '', 
            'framogatype_name'          => '', 
            'framogafigure_id'          => '',
            'framogafigure_name'        => '',
            'framogafigure_price'       => 0, 
            'total_framogafigure_price' => 0,
            'framoga_width'             => '',
            'framoga_height'            => '',
            'transom'                   => '',
            'transom_side'              => 0,       
            'transom_price'             => 0,
            'total_transom_price'       => 0,
            'ornamenttype'              => '',
            'ornamenttype_price'        => 0,
            'total_ornamenttype_price'  => 0,
            'loop_id' => '',
            'loop_name'=> '',
            'loop_count' => 0,
            'loop_price' => 0,
            'total_loop_price' => 0,
            'box_size' => '',
            'doorstep' => '',
            'l_p' => '',
            'wall_thickness' => '',
            'width' => '',
            'height' => '',
            'doortype' => '',
            'doortype_price' => 0,
            'total_doortype_price' => 0,
        ));

        $jamb_parameters = array();

        $transom_parameters = array(array(
            'id' => '',
            'name' => '',
            'price' => 0,
            'total_price' => 0, 
            'height' => 0,
            'width' => 0,
            'thickness' => 0,
            'height_count' => 0,
            'width_count' => 0
        ));

        $glass_parameters = array(array(
            'id' => '',
            'type' => '',
            'figure' => '',
            'count' => 0,
            'total_count' => 0,
            'price' => 0,
            'total_price' => 0
        ));

        // output - bu proizvodstvada ishlatiladigan parametrlar
        $output = array(array(
            'count'           => 0, // eshik soni
            'width'           => '', // eni
            'height'          => '', // bo'yi
            'form_width'      => '', // qolip eni
            'form_height'     => '', // qolip bo'yi
            'ornament_width'  => '', // naqsh eni
            'ornament_height' => '', // naqsh bo'yi
            'box_width'       => '', // korobka eni
            'box_height'      => '', // korobka bo'yi
            'rail_width'      => '', // reyka eni
            'rail_height'     => '', // reyka bo'yi
            'transom_width'   => '' // dobor qalinligi = wall_thickness - depth + 10
        ));

        $total_doors_count = 0;
        $transom_installation_price = 0;
        $contract_price = 0;

        $order = new Order(); // door->save() dan keyin order->save() qilinadi
        if ($request->customer_radio == "dealer") {
            $order->customer_id = $request->dealer;
            $order->customer_type = "Diler";
            $order->phone_number = Customer::find($request->dealer)->phone_number;
        } else {
            $order->customer_id = $request->customer;
            $order->customer_type = "Xaridor";
            $order->phone_number = Customer::find($request->customer)->phone_number;
        }

        $order->contract_number = $request->contract_number;
        $order->deadline = $request->deadline;
        
        $doortype = new Doortype();
        $doortype = $doortype->getData($request->doortype);
        
        for ($i = 0; $i < count($request->height); $i++) { 
            $total_doors_count += $request['count'][$i];
            if (!empty($request['count'][$i]) && !empty($request['width'][$i]) && !empty($request['height'][$i])) {
                $depth = new Depth();
                $depth = $depth->getData($request['depth_id'][$i]);
                $loop = new Loop();
                $loop = $loop->getData($request['hidden_loop_id'][$i]);
                $layer = new Layer();
                $layer = $layer->getData($request['layer_id'][$i]);
                $lock_type = new Locktype();
                $lock_type = $lock_type->getData($request['locktype_id'][$i]);
                
                if (!empty($request['wall_thickness'][$i]) && $request['wall_thickness'][$i] != 0)
                    $transom = Transom::where('doortype_id', $request->doortype)->first();
                $ornamenttype = new OrnamentType();
                $ornamenttype = $ornamenttype->getData($request['ornament_id'][$i]);
                // output
                $width = $request['width'][$i]; // eni
                $height = $request['height'][$i]; // bo'yi


                if ($layer->name == 1.5) {
                    if (($width - 2 * $depth->name) / 3 < 410)
                        $form_width = 410;
                    else
                        $form_width = ($width - 2 * $depth->name) / 3;
                } else if ($layer->name == 2) {
                    $form_width = ($width - 2 * $depth->name) / 2;
                } else {
                    $form_width = $width-2*$depth->name; // qolip eni
                }

                if ($request['doorstep'][$i] == "bez") {
                    $form_height = $height-$depth->name; // qolip bo'yi
                }
                else {
                    $form_height = $height-2*$depth->name; // qolip bo'yi   
                }
                $ornament_width = $form_width-240; // naqsh eni
                $ornament_height = $form_height-240; // naqsh bo'yi
                
                $box_width = $width; // korobka eni
                $box_height = $form_height-4; // korobka bo'yi

                $rail_width = $form_width-80; // reyka eni
                $rail_height = $form_height; // reyka bo'yi

                $transom_width = 0;
                $transom_size = "";
                if (!empty($request['wall_thickness'][$i]) && $request['wall_thickness'][$i] != 0) {
                    $transom_width = $request['wall_thickness'][$i] - $request['box_size'][$i] + 10;
                    $transom_size = (2 * $height + $width) * $transom_width / 1000000;
                }
                $output[$i]['count'] = $request['count'][$i];
                $output[$i]['width'] = $width;
                $output[$i]['height'] = $height;
                $output[$i]['form_width'] = $form_width;
                $output[$i]['form_height'] = $form_height;
                $output[$i]['ornament_width'] = $ornament_width;
                $output[$i]['ornament_height'] = $ornament_height;
                $output[$i]['box_width'] = $box_width;
                $output[$i]['box_height'] = $box_height;
                $output[$i]['rail_width'] = $rail_width;
                $output[$i]['rail_height'] = $rail_height;
                $output[$i]['transom_width'] = $transom_width;

                // door_parameters
                $door_parameters[$i]['count'] = $request['count'][$i];
                $door_parameters[$i]['box_size'] = $request['box_size'][$i];
                $door_parameters[$i]['doorstep'] = $request['doorstep'][$i];
                $door_parameters[$i]['l_p'] = $request['l_p'][$i];
                $door_parameters[$i]['wall_thickness'] = !empty($request['wall_thickness'][$i]) ? $request['wall_thickness'][$i] : 0;

                $door_parameters[$i]['width'] = $width;
                $door_parameters[$i]['height'] = $height;

                $door_parameters[$i]['depth'] = $depth->name;
                $door_parameters[$i]['depth_price'] = $depth->price;
                $door_parameters[$i]['total_depth_price'] = $depth->price * $request['count'][$i];
                
                $door_parameters[$i]['layer'] = $layer->name;
                $door_parameters[$i]['layer_price'] = $layer->price;
                $door_parameters[$i]['total_layer_price'] = $layer->price * $request['count'][$i];
               
                if (!empty($transom)) {
                    $door_parameters[$i]['transom'] = $transom->name;
                    $door_parameters[$i]['transom_side'] = $request['transom_side'][$i];
                } else {
                    $door_parameters[$i]['transom'] = "";
                    $door_parameters[$i]['transom_side'] = "";
                }

                $door_parameters[$i]['locktype'] = $lock_type->name;
                $door_parameters[$i]['doortype'] = $doortype->name;

                $door_parameters[$i]['loop_id'] = $loop->id;
                $door_parameters[$i]['loop_name'] = $loop->name;
                $door_parameters[$i]['loop_count'] = $request['hidden_loop_count'][$i] * $request['count'][$i] / 2;

                
                $price_for_one_metrkv = 0;
                $layerdoortype_price = 0;
                $height_over2090_price = 0;
                $doortype_price = 0;
                $transom_price = 0;
                $total_transom_price = 0;

                if ($request->customer_radio == "dealer"){
                    $doortype_price = $doortype->dealer_price;
                    $door_parameters[$i]['locktype_price'] = $lock_type->dealer_price;
                    $door_parameters[$i]['total_locktype_price'] = $lock_type->dealer_price * $request['count'][$i];
                    
                    if (!empty($transom))
                        $transom_price = intval($transom_size) * $transom->dealer_price;

                    $door_parameters[$i]['loop_price'] = $loop->dealer_price;
                    $door_parameters[$i]['total_loop_price'] = $loop->dealer_price * $request['count'][$i] * $request['hidden_loop_count'][$i] / 2;
                } else {
                    $doortype_price = $doortype->retail_price;
                    $door_parameters[$i]['locktype_price'] = $lock_type->retail_price;
                    $door_parameters[$i]['total_locktype_price'] = $lock_type->retail_price * $request['count'][$i];
                    
                    if (!empty($transom))
                        $transom_price = intval($transom_size) * $transom->retail_price;
                    
                    $door_parameters[$i]['loop_price'] = $loop->retail_price;
                    $door_parameters[$i]['total_loop_price'] = $loop->retail_price * $request['count'][$i] * $request['hidden_loop_count'][$i] / 2;
                }
                
                $total_transom_price = $transom_price * $request['count'][$i];
                $door_parameters[$i]['transom_price'] = $transom_price;
                $door_parameters[$i]['total_transom_price'] = $total_transom_price;

                if ($request['height'][$i] >= 2090) {
                    $price_for_one_metrkv = $doortype_price * 1000000 / (2060 * 860); // standart eshikni 1m2 uchun narxi
                    $height_over2090_price = (($request['height'][$i] - 2000) * $request['width'][$i] / 1000000) * $price_for_one_metrkv;
                }

                if ($layer->name == 1.5)
                    $layerdoortype_price = ($doortype_price + $height_over2090_price) * $doortype->layer15_koeffitsient;
                else 
                    $layerdoortype_price = ($doortype_price + $height_over2090_price) * intval($layer->name);
                
                // framoga 
                if (!is_null($request['hidden_framoga_type'][$i]) && !is_null($request['hidden_framoga_figure'][$i])) {

                    $framogatype = new Framogatype();
                    $framogatype = $framogatype->getData($request['hidden_framoga_type'][$i]);
                    
                    $framogafigure = new Framogafigure();
                    $framogafigure = $framogafigure->getData($request['hidden_framoga_figure'][$i]);

                    $framoga_width = $request['width'][$i]; // framoga eni
                    $framoga_height = $request['height'][$i] - 2 * $depth->name - $framogatype->name; // framoga bo'yi

                    $door_parameters[$i]['framogatype_id'] = $framogatype->id;
                    $door_parameters[$i]['framogatype_name'] = $framogatype->name;

                    $door_parameters[$i]['framogafigure_id'] = $framogafigure->id;
                    $door_parameters[$i]['framogafigure_name'] = $framogafigure->name;
                   
                    if ($framoga_width * $framoga_height  * $framogafigure->price / 1000000 < $framogafigure->min_price)
                        $door_parameters[$i]['framogafigure_price'] = $framogafigure->min_price;
                    else
                        $door_parameters[$i]['framogafigure_price'] = $framoga_width * $framoga_height  * $framogafigure->price / 1000000;

                    $door_parameters[$i]['total_framogafigure_price'] = $door_parameters[$i]['framogafigure_price'] * $request['count'][$i];
                    $door_parameters[$i]['framoga_width'] = $framoga_width;
                    $door_parameters[$i]['framoga_height'] = $framoga_height;
                }
               
                // shisha
                if (!is_null($request['hidden_glasstype_id'][$i]) && !is_null($request['hidden_glassfigure_id'][$i]) && !is_null($request['hidden_glass_count'][$i])) {
                    $glass = new Glass();
                    $glass = $glass->getData($request['hidden_glasstype_id'][$i], $request['hidden_glassfigure_id'][$i]);
                    $glass_parameters[$i]['id'] = $glass[0]->id;
                    $glass_parameters[$i]['type'] = $glass[0]->glasstype;
                    $glass_parameters[$i]['figure'] = $glass[0]->glassfigure;
                    $glass_parameters[$i]['count'] = $request['hidden_glass_count'][$i];
                    $glass_parameters[$i]['price'] = $glass[0]->price;
                    
                    if ($layer->name == 1.5) {
                        $glass_parameters[$i]['total_price'] = $glass[0]->price * $request['count'][$i] * $doortype->layer15_koeffitsient;
                        $glass_parameters[$i]['total_count'] = $request['hidden_glass_count'][$i] * $request['count'][$i] * 2;
                    } else {
                        $glass_parameters[$i]['total_price'] = $glass[0]->price * $request['count'][$i] * $layer->name;
                        $glass_parameters[$i]['total_count'] = $request['hidden_glass_count'][$i] * $request['count'][$i] * $layer->name;
                    }
                }

                $door_parameters[$i]['ornamenttype'] = $ornamenttype->name;
                $door_parameters[$i]['ornamenttype_price'] = $ornamenttype->price;
                $door_parameters[$i]['total_ornamenttype_price'] = $ornamenttype->price * $request['count'][$i];

                $sum = $door_parameters[$i]['total_depth_price'] + $door_parameters[$i]['total_layer_price'] + $door_parameters[$i]['total_locktype_price'] + $door_parameters[$i]['total_ornamenttype_price'] + $door_parameters[$i]['total_loop_price'];
                
                if (isset($door_parameters[$i]['total_framogafigure_price']))
                    $sum += $door_parameters[$i]['total_framogafigure_price'];
                
                if (isset($glass_parameters[$i]['total_price']))
                    $sum += $glass_parameters[$i]['total_price'];

                $sum += $total_transom_price;

                $contract_price += $sum;

                // transom_parameters
                if (!empty($transom)) {
                    $transom_parameters[$i]['id'] = $transom->id;
                    $transom_parameters[$i]['name'] = $transom->name;
                    $transom_parameters[$i]['height'] = $request['height'][$i];
                    $transom_parameters[$i]['width'] = $request['width'][$i];
                    $transom_parameters[$i]['thickness'] = $request['wall_thickness'][$i] - $request['box_size'][$i] + 10;
                    $transom_parameters[$i]['height_count'] = 2 * $request['count'][$i];
                    $transom_parameters[$i]['width_count'] = $request['count'][$i];
                    $transom_parameters[$i]['price'] = $transom_price;
                    $transom_parameters[$i]['total_price'] = $total_transom_price;
                    $transom_installation_price = $transom->installation_price; // dobor ustanovka narxi
                } else {
                    $transom_parameters[$i]['id'] = "";
                    $transom_parameters[$i]['name'] = "";
                    $transom_parameters[$i]['height'] = 0;
                    $transom_parameters[$i]['width'] = 0;
                    $transom_parameters[$i]['thickness'] = 0;
                    $transom_parameters[$i]['height_count'] = 0;
                    $transom_parameters[$i]['width_count'] = 0;
                    $transom_parameters[$i]['price'] = 0;
                    $transom_parameters[$i]['total_price'] = 0;
                    $transom_installation_price = 0; // dobor ustanovka narxi
                }

                // jamb_parameters
                $child_jamb = array(array(
                    'id'          => '',
                    'name'        => '', 
                    'price'       => 0,
                    'count'       => 0,
                    'total_price' => 0
                ));
                if (isset($request['jamb'][$i]) && !empty($request['jamb'][$i])){
                    $jamb_data = json_decode($request['jamb'][$i], true);
                    for($j = 0; $j < count($jamb_data); $j++) {
                        if (!empty($jamb_data[$j]['jamb'])) {
                            $jamb = new Jamb();
                            $jamb = $jamb->getData($jamb_data[$j]['jamb']);
                            $child_jamb[$j]['id'] = $jamb->id;
                            $child_jamb[$j]['name'] = $jamb->name;
                            $child_jamb[$j]['count'] = $jamb_data[$j]['jamb_count'] * $request['count'][$i];
                            if ($request->customer_radio == "dealer"){
                                $child_jamb[$j]['price'] = $jamb->dealer_price;
                                $child_jamb[$j]['total_price'] = $jamb->dealer_price * $jamb_data[$j]['jamb_count'] * $request['count'][$i];
                            } else {
                                $child_jamb[$j]['price'] = $jamb->retail_price;
                                $child_jamb[$j]['total_price'] = $jamb->retail_price * $jamb_data[$j]['jamb_count'] * $request['count'][$i];
                            }
                            $contract_price += $child_jamb[$j]['total_price'];
                        }
                    }
                    $jamb_parameters[$i] = $child_jamb;
                }

                $total_door_price = $layerdoortype_price * $request['count'][$i]; 
                $contract_price += $total_door_price;
                $door_parameters[$i]['doortype_price'] = $layerdoortype_price;
                $door_parameters[$i]['total_doortype_price'] = $total_door_price;
                
                DB::insert('INSERT INTO door_results(door_id, width, height, count, door_type, door_color, l_p, layer, doorstep, box_size, depth, box_width, box_height, lock_type, transom, transom_width, transom_height, transom_thickness, ornament_type, ornament_model, ornament_width, ornament_height, ornament_first_width, ornament_second_width,framoga_type, framoga_figure, framoga_width, framoga_height, jamb, form_width, form_height, form_first_width, form_second_width, rail_width, rail_height, rail_first_width, rail_second_width, wall_thickness, loop_name, loop_count, glass_type, glass_figure, glass_count) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)', [
                    NULL, // door_id
                    $request['width'][$i],
                    $request['height'][$i],
                    $request['count'][$i],
                    $doortype->name,
                    $request->door_color,
                    $request['l_p'][$i],
                    $door_parameters[$i]['layer'],
                    $request['doorstep'][$i],
                    $request['box_size'][$i],
                    $depth->name,
                    $output[$i]['box_width'],
                    $output[$i]['box_height'],
                    $door_parameters[$i]['locktype'],
                    !empty($transom_parameters[$i]['name']) ? $transom_parameters[$i]['name'] : NULL,
                    !empty($transom_parameters[$i]['width']) ? $transom_parameters[$i]['width'] : NULL,
                    !empty($transom_parameters[$i]['height']) ? $transom_parameters[$i]['height'] : NULL,
                    !empty($transom_parameters[$i]['thickness']) ? $transom_parameters[$i]['thickness'] : NULL,
                    $ornamenttype->name,
                    $request->ornament_model,
                    $output[$i]['ornament_width'],
                    $output[$i]['ornament_height'],
                    NULL, // ornament_first_width
                    NULL, // ornament_second_width
                    !empty($door_parameters[$i]['framogatype_name']) ? $door_parameters[$i]['framogatype_name'] : NULL,
                    !empty($door_parameters[$i]['framogafigure_name']) ? $door_parameters[$i]['framogafigure_name'] : NULL,
                    !empty($door_parameters[$i]['framoga_width']) ? $door_parameters[$i]['framoga_width'] : NULL,
                    !empty($door_parameters[$i]['framoga_height']) ? $door_parameters[$i]['framoga_height'] : NULL,
                    !empty($jamb_parameters[$i]) ? json_encode($jamb_parameters[$i]) : NULL,
                    $output[$i]['form_width'],
                    $output[$i]['form_height'],
                    NULL, // form_first_width,
                    NULL, // form_second_width,
                    $output[$i]['rail_width'],
                    $output[$i]['rail_height'],
                    NULL, // rail_first_width
                    NULL, // rail_second_width
                    !empty($request['wall_thickness'][$i]) ? $request['wall_thickness'][$i] : NULL,
                    $loop->name,
                    $request['hidden_loop_count'][$i] / 2,
                    !empty($glass_parameters[$i]['type']) ? $glass_parameters[$i]['type'] : NULL,
                    !empty($glass_parameters[$i]['figure']) ? $glass_parameters[$i]['figure'] : NULL,
                    !empty($glass_parameters[$i]['count']) ? $glass_parameters[$i]['count'] : NULL
                ]);
            }
        }

        $door = new Door();
        $door->doortype_id = $doortype->id;
        $door->door_parameters = json_encode($door_parameters);
        $door->jamb_parameters = json_encode($jamb_parameters);
        $door->transom_parameters = json_encode($transom_parameters);
        $door->glass_parameters = json_encode($glass_parameters);
        $door->output = json_encode($output);
        $door->door_color = $request->door_color;
        $door->ornament_model = $request->ornament_model;
        $door->save();

        DB::update('UPDATE door_results SET door_id=? 
                    ORDER BY created_at DESC
                    LIMIT ?', [$door->id, count($door_parameters)]);

        if ($request->with_installation){
            $with_installation = 1;
            $installation_price = ($doortype->installation_price  + $transom_installation_price) * $total_doors_count;
        } else {
            $with_installation = 0;
            $installation_price = 0;
        }

        if ($request->with_courier){
            $with_courier = 1;
            $courier_price = $request->courier_price;
        } else {
            $with_courier = 0;
            $courier_price = 0;
        }

        $contract_price += $courier_price;
        $contract_price += $installation_price;

        $order->door_id = $door->id;
        $order->product = "door";
        $order->contract_price = $contract_price;
        $order->with_installation = $with_installation;
        $order->installation_price = $installation_price;
        $order->with_courier = $with_courier;
        $order->courier_price = $courier_price;
        $order->last_contract_price = $contract_price;
        $order->save();

        $invoice = new Invoice();
        $invoice->payer = Customer::find($order->customer_id)->name;
        $invoice->responsible = Auth::user()->username;
        $invoice->amount = $order->last_contract_price;
        $invoice->day = date('Y-m-d');
        $invoice->order_id = $order->id;
        $invoice->status = 0;
        $invoice->save();

        return redirect()->route('orders');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = DB::select('SELECT a.id, 
                                    a.door_id, 
                                    a.phone_number, 
                                    a.contract_number, 
                                    a.deadline, 
                                    a.installation_price,
                                    a.courier_price,
                                    a.rebate_percent,
                                    a.contract_price, 
                                    a.last_contract_price,
                                    b.name as customer, 
                                    (SELECT SUM(amount)
                                     FROM stocks
                                     WHERE invoice_id=c.id
                                     GROUP BY invoice_id
                                    ) AS paid,
                                    j.name as process
                             FROM (orders a, customers b)
                             LEFT JOIN invoices c ON a.id=c.order_id
                             LEFT JOIN jobs j ON j.id=a.job_id
                             WHERE a.customer_id=b.id AND a.id=?', [$id]);
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
        $transom_installation_price = 0;
        if ($current_transom_id != 0) {
            $transom_installation_price = Transom::find($current_transom_id)->installation_price;
        }
        
        $data = array(
            'order'                      => $order,
            'door_parameters'            => $door_parameters,
            'doortypes'                  => $doortypes,
            'layers'                     => $layers,
            'depths'                     => $depths,
            'locktypes'                  => $locktypes,
            'ornamenttypes'              => $ornamenttypes,
            'loops'                      => $loops,
            'jambs'                      => $jambs,
            'transoms'                   => $transoms,
            'glasses'                    => $glasses, 
            'transom_installation_price' => $transom_installation_price
        );
        return view('manager.order.door.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $order = Order::find($id);
        $door = Door::find($order->door_id);
        $door_parameters = json_decode($door->door_parameters, true);
        // $jamb_parameters = json_decode($door->jamb_parameters, true);
        $output = json_decode($door->output, true);

        $doortypes = DB::table('doortypes')->get(['id', 'name']);
        $customers = DB::select('SELECT id, name FROM customers WHERE type="Xaridor" ORDER BY name');
        $dealers = DB::select('SELECT id, name FROM customers WHERE type="Diler" ORDER BY name');
        $depths = DB::table('depths')->get(['id', 'name']);
        $layers = DB::table('layers')->get(['id', 'name']);
        $ornamenttypes = DB::table('ornamenttypes')->get(['id', 'name']);
        $locktypes = DB::table('locktypes')->get(['id', 'name']);
        $framogatypes = DB::table('framogatypes')->get(['id', 'name']);
        $framogafigures = DB::table('framogafigures')->get(['id', 'name']);
        $jambs = DB::select('SELECT id, name FROM jambs WHERE doortype_id=?', [$door->doortype_id]);
        $loops = DB::table('loops')->get(['id', 'name']);
        $glass_figures = DB::select('SELECT DISTINCT a.id, a.name, a.path
                                   FROM glass_figures a
                                   INNER JOIN glasses b ON b.glassfigure_id=a.id');
        
        $data = array(
            'order'           => $order,
            'door'            => $door,
            'door_parameters' => $door_parameters,
            'output'          => $output,
            'doortypes'       => $doortypes,
            'customers'       => $customers,
            'dealers'         => $dealers,
            'depths'          => $depths,
            'layers'          => $layers,
            'ornamenttypes'   => $ornamenttypes,
            'locktypes'       => $locktypes,
            'framogatypes'    => $framogatypes,
            'framogafigures'  => $framogafigures,
            'jambs'           => $jambs,
            'loops'           => $loops,
            'glass_figures'   => $glass_figures,
        );

        return view('manager.order.door.update', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $order = Order::find($id); // door->save() dan keyin order->save() qilinadi

        DB::delete('DELETE FROM door_results WHERE door_id=?', [$order->door_id]);
       
        $door_parameters = array(array(
            'count'                     => 0,
            'layer'                     => '', 
            'layer_price'               => 0, 
            'total_layer_price'         => '',
            'depth'                     => '', 
            'depth_price'               => 0, 
            'total_depth_price'         => 0,
            'locktype'                  => '', 
            'locktype_price'            => 0, 
            'total_locktype_price'      => 0,
            'framogatype_id'            => '', 
            'framogatype_name'          => '', 
            'framogafigure_id'          => '',
            'framogafigure_name'        => '',
            'framogafigure_price'       => 0, 
            'total_framogafigure_price' => 0,
            'framoga_width'             => '',
            'framoga_height'            => '',
            'transom'                   => '',
            'transom_side'              => 0,
            'transom_price'             => 0,
            'total_transom_price'       => 0,
            'ornamenttype'              => '',
            'ornamenttype_price'        => 0,
            'total_ornamenttype_price'  => 0,
            'loop_id' => '',
            'loop_name'=> '',
            'loop_count' => 0,
            'loop_price' => 0,
            'total_loop_price' => 0,
            'box_size' => '',
            'doorstep' => '',
            'l_p' => '',
            'wall_thickness' => '',
            'width' => '',
            'height' => '',
            'doortype' => '',
            'doortype_price' => 0,
            'total_doortype_price' => 0
        ));
        $total_doors_count = 0;
        $total_transoms_installation = 0;

        $jamb_parameters = array();

        $transom_parameters = array(array(
            'id' => '',
            'name' => '',
            'price' => 0,
            'total_price' => 0,
            'height' => 0,
            'width' => 0,
            'thickness' => 0,
            'height_count' => 0,
            'width_count' => 0
        ));

        $glass_parameters = array(array(
            'id' => '',
            'type' => '',
            'figure' => '',
            'count' => 0,
            'total_count' => 0,
            'price' => 0,
            'total_price' => 0
        ));

        // output - bu proizvodstvada ishlatiladigan parametrlar
        $output = array(array(
            'count'           => 0, // eshik soni
            'width'           => '', // eni
            'height'          => '', // bo'yi
            'form_width'      => '', // qolip eni
            'form_height'     => '', // qolip bo'yi
            'ornament_width'  => '', // naqsh eni
            'ornament_height' => '', // naqsh bo'yi
            'box_width'       => '', // korobka eni
            'box_height'      => '', // korobka bo'yi
            'rail_width'      => '', // reyka eni
            'rail_height'     => '', // reyka bo'yi
            'transom_width'   => '' // dobor qalinligi = wall_thickness - depth + 10
        ));

        $total_doors_count = 0;
        $transom_installation_price = 0;
        $contract_price = 0;

        if ($request->customer_radio == "dealer") {
            $order->customer_id = $request->dealer;
            $order->customer_type = "Diler";
            $order->phone_number = Customer::find($request->dealer)->phone_number;
        } else {
            $order->customer_id = $request->customer;
            $order->customer_type = "Xaridor";
            $order->phone_number = Customer::find($request->customer)->phone_number;
        }

        $order->contract_number = $request->contract_number;
        $order->deadline = $request->deadline;
        
        $doortype = new Doortype();
        $doortype = $doortype->getData($request->doortype);
        
        for ($i = 0; $i < count($request->height); $i++) { 
            $total_doors_count += $request['count'][$i];
            if (!empty($request['count'][$i]) && !empty($request['width'][$i]) && !empty($request['height'][$i])) { 
                $depth = new Depth();
                $depth = $depth->getData($request['depth_id'][$i]);
                $loop = new Loop();
                $loop = $loop->getData($request['hidden_loop_id'][$i]);
                $layer = new Layer();
                $layer = $layer->getData($request['layer_id'][$i]);
                $lock_type = new Locktype();
                $lock_type = $lock_type->getData($request['locktype_id'][$i]);
                
                if (!empty($request['wall_thickness'][$i]) && $request['wall_thickness'][$i] != 0)
                    $transom = Transom::where('doortype_id', $request->doortype)->first();

                $ornamenttype = new OrnamentType();
                $ornamenttype = $ornamenttype->getData($request['ornament_id'][$i]);
                // output
                $width = $request['width'][$i]; // eni
                $height = $request['height'][$i]; // bo'yi

                if ($layer->name == 1.5) {
                    if (($width - 2 * $depth->name) / 3 < 410)
                        $form_width = 410;
                    else
                        $form_width = ($width - 2 * $depth->name) / 3;
                } else if ($layer->name == 2) {
                    $form_width = ($width - 2 * $depth->name) / 2;
                } else {
                    $form_width = $width - 2 * $depth->name; // qolip eni
                }

                if ($request['doorstep'][$i] == "bez") {
                    $form_height = $height-$depth->name; // qolip bo'yi
                }
                else {
                    $form_height = $height - 2 * $depth->name; // qolip bo'yi   
                }
                $ornament_width = $form_width - 240; // naqsh eni
                $ornament_height = $form_height - 240; // naqsh bo'yi
                
                $box_width = $width; // korobka eni
                $box_height = $form_height - 4; // korobka bo'yi

                $rail_width = $form_width - 80; // reyka eni
                $rail_height = $form_height; // reyka bo'yi

                $transom_width = "";
                $transom_size = "";
                if (!empty($request['wall_thickness'][$i]) && $request['wall_thickness'][$i] != 0) {
                    $transom_width = $request['wall_thickness'][$i] - $request['box_size'][$i] + 10;
                    $transom_size = (2 * $height + $width) * $transom_width / 1000000;
                }
                
                $output[$i]['count'] = $request['count'][$i];
                $output[$i]['width'] = $width;
                $output[$i]['height'] = $height;
                $output[$i]['form_width'] = $form_width;
                $output[$i]['form_height'] = $form_height;
                $output[$i]['ornament_width'] = $ornament_width;
                $output[$i]['ornament_height'] = $ornament_height;
                $output[$i]['box_width'] = $box_width;
                $output[$i]['box_height'] = $box_height;
                $output[$i]['rail_width'] = $rail_width;
                $output[$i]['rail_height'] = $rail_height;
                $output[$i]['transom_width'] = $transom_width;

                // door_parameters
                $door_parameters[$i]['count'] = $request['count'][$i];
                $door_parameters[$i]['box_size'] = $request['box_size'][$i];
                $door_parameters[$i]['doorstep'] = $request['doorstep'][$i];
                $door_parameters[$i]['l_p'] = $request['l_p'][$i];
                $door_parameters[$i]['wall_thickness'] = !empty($request['wall_thickness'][$i]) ? $request['wall_thickness'][$i] : 0;

                $door_parameters[$i]['width'] = $width;
                $door_parameters[$i]['height'] = $height;

                $door_parameters[$i]['depth'] = $depth->name;
                $door_parameters[$i]['depth_price'] = $depth->price;
                $door_parameters[$i]['total_depth_price'] = $depth->price * $request['count'][$i];
                
                $door_parameters[$i]['layer'] = $layer->name;
                $door_parameters[$i]['layer_price'] = $layer->price;
                $door_parameters[$i]['total_layer_price'] = $layer->price * $request['count'][$i];
               
                if (!empty($transom)) {
                    $door_parameters[$i]['transom'] = $transom->name;
                    $door_parameters[$i]['transom_side'] = $request['transom_side'][$i];
                } else {
                    $door_parameters[$i]['transom'] = "";
                    $door_parameters[$i]['transom_side'] = "";
                }

                $door_parameters[$i]['locktype'] = $lock_type->name;

                $door_parameters[$i]['loop_id'] = $loop->id;
                $door_parameters[$i]['loop_name'] = $loop->name;
                $door_parameters[$i]['loop_count'] = $request['hidden_loop_count'][$i] * $request['count'][$i] / 2;

                $door_parameters[$i]['doortype'] = $doortype->name;
                
                $price_for_one_metrkv = 0;
                $layerdoortype_price = 0;
                $height_over2090_price = 0;
                $doortype_price = 0;
                $transom_price = 0;
                $total_transom_price = 0;

                if ($request->customer_radio == "dealer"){
                    $doortype_price = $doortype->dealer_price;
                    $door_parameters[$i]['locktype_price'] = $lock_type->dealer_price;
                    $door_parameters[$i]['total_locktype_price'] = $lock_type->dealer_price * $request['count'][$i];
                    
                    if (!empty($transom))
                        $transom_price = intval($transom_size) * $transom->dealer_price;

                    $door_parameters[$i]['loop_price'] = $loop->dealer_price;
                    $door_parameters[$i]['total_loop_price'] = $loop->dealer_price * $request['count'][$i] * $request['hidden_loop_count'][$i] / 2;
                } else {
                    $doortype_price = $doortype->retail_price;
                    $door_parameters[$i]['locktype_price'] = $lock_type->retail_price;
                    $door_parameters[$i]['total_locktype_price'] = $lock_type->retail_price * $request['count'][$i];
                    
                    if (!empty($transom))
                        $transom_price = intval($transom_size) * $transom->retail_price;
                    
                    $door_parameters[$i]['loop_price'] = $loop->retail_price;
                    $door_parameters[$i]['total_loop_price'] = $loop->retail_price * $request['count'][$i] * $request['hidden_loop_count'][$i] / 2;
                }
                
                $total_transom_price = $transom_price * $request['count'][$i];
                $door_parameters[$i]['transom_price'] = $transom_price;
                $door_parameters[$i]['total_transom_price'] = $total_transom_price;

                if ($request['height'][$i] >= 2090) {
                    $price_for_one_metrkv = $doortype_price * 1000000 / (2060 * 860); // standart eshikni 1m2 uchun narxi
                    $height_over2090_price = (($request['height'][$i] - 2000) * $request['width'][$i] / 1000000) * $price_for_one_metrkv;
                }

                if ($layer->name == 1.5)
                    $layerdoortype_price = ($doortype_price + $height_over2090_price) * $doortype->layer15_koeffitsient;
                else 
                    $layerdoortype_price = ($doortype_price + $height_over2090_price) * intval($layer->name);
                
                // framoga 
                if (!is_null($request['hidden_framoga_type'][$i]) && !is_null($request['hidden_framoga_figure'][$i])) {
                    $framogatype = new Framogatype();
                    $framogatype = $framogatype->getData($request['hidden_framoga_type'][$i]);
                    
                    $framogafigure = new Framogafigure();
                    $framogafigure = $framogafigure->getData($request['hidden_framoga_figure'][$i]);

                    $framoga_width = $request['width'][$i]; // framoga eni
                    $framoga_height = $request['height'][$i] - 2 * $depth->name - $framogatype->name; // framoga bo'yi

                    $door_parameters[$i]['framogatype_id'] = $framogatype->id;
                    $door_parameters[$i]['framogatype_name'] = $framogatype->name;

                    $door_parameters[$i]['framogafigure_id'] = $framogafigure->id;
                    $door_parameters[$i]['framogafigure_name'] = $framogafigure->name;
                   
                    if ($framoga_width * $framoga_height  * $framogafigure->price / 1000000 < $framogafigure->min_price)
                        $door_parameters[$i]['framogafigure_price'] = $framogafigure->min_price;
                    else
                        $door_parameters[$i]['framogafigure_price'] = $framoga_width * $framoga_height  * $framogafigure->price / 1000000;

                    $door_parameters[$i]['total_framogafigure_price'] = $door_parameters[$i]['framogafigure_price'] * $request['count'][$i];
                    $door_parameters[$i]['framoga_width'] = $framoga_width;
                    $door_parameters[$i]['framoga_height'] = $framoga_height;
                }

                // shisha
                if (!is_null($request['hidden_glasstype_id'][$i]) && !is_null($request['hidden_glassfigure_id'][$i]) && !is_null($request['hidden_glass_count'][$i])) {
                    $glass = new Glass();
                    $glass = $glass->getData($request['hidden_glasstype_id'][$i], $request['hidden_glassfigure_id'][$i]);
                    
                    $glass_parameters[$i]['id'] = $glass[0]->id;
                    $glass_parameters[$i]['type'] = $glass[0]->glasstype;
                    $glass_parameters[$i]['figure'] = $glass[0]->glassfigure;
                    $glass_parameters[$i]['count'] = $request['hidden_glass_count'][$i];
                    $glass_parameters[$i]['price'] = $glass[0]->price;

                    if ($layer->name == 1.5) {
                        $glass_parameters[$i]['total_price'] = $glass[0]->price * $request['count'][$i] * $doortype->layer15_koeffitsient;
                        $glass_parameters[$i]['total_count'] = $request['hidden_glass_count'][$i] * $request['count'][$i] * 2;
                    } else {
                        $glass_parameters[$i]['total_price'] = $glass[0]->price * $request['count'][$i] * $layer->name;
                        $glass_parameters[$i]['total_count'] = $request['hidden_glass_count'][$i] * $request['count'][$i] * $layer->name;
                    }
                }
                
                $door_parameters[$i]['ornamenttype'] = $ornamenttype->name;
                $door_parameters[$i]['ornamenttype_price'] = $ornamenttype->price;
                $door_parameters[$i]['total_ornamenttype_price'] = $ornamenttype->price * $request['count'][$i];

                $sum = $door_parameters[$i]['total_depth_price'] + $door_parameters[$i]['total_layer_price'] + $door_parameters[$i]['total_locktype_price'] + $door_parameters[$i]['total_ornamenttype_price'] + $door_parameters[$i]['total_loop_price'];
                
                if (isset($door_parameters[$i]['total_framogafigure_price']))
                    $sum += $door_parameters[$i]['total_framogafigure_price'];
                
                if (isset($glass_parameters[$i]['total_price']))
                    $sum += $glass_parameters[$i]['total_price'];

                $sum += $total_transom_price;

                $contract_price += $sum;

                // transom_parameters
                if (!empty($transom)) {
                    $transom_parameters[$i]['id'] = $transom->id;
                    $transom_parameters[$i]['name'] = $transom->name;
                    $transom_parameters[$i]['height'] = $request['height'][$i];
                    $transom_parameters[$i]['width'] = $request['width'][$i];
                    $transom_parameters[$i]['thickness'] = $request['wall_thickness'][$i] - $request['box_size'][$i] + 10;
                    $transom_parameters[$i]['height_count'] = 2 * $request['count'][$i];
                    $transom_parameters[$i]['width_count'] = $request['count'][$i];
                    $transom_parameters[$i]['price'] = $transom_price;
                    $transom_parameters[$i]['total_price'] = $total_transom_price;
                    $transom_installation_price = $transom->installation_price; // dobor ustanovka narxi
                } else {
                    $transom_parameters[$i]['id'] = "";
                    $transom_parameters[$i]['name'] = "";
                    $transom_parameters[$i]['height'] = 0;
                    $transom_parameters[$i]['width'] = 0;
                    $transom_parameters[$i]['thickness'] = 0;
                    $transom_parameters[$i]['height_count'] = 0;
                    $transom_parameters[$i]['width_count'] = 0;
                    $transom_parameters[$i]['price'] = 0;
                    $transom_parameters[$i]['total_price'] = 0;
                    $transom_installation_price = 0; // dobor ustanovka narxi
                }

                // jamb_parameters
                $child_jamb = array(array(
                    'id'          => '',
                    'name'        => '', 
                    'price'       => 0,
                    'count'       => 0,
                    'total_price' => 0
                ));

                if (isset($request['jamb'][$i]) && !empty($request['jamb'][$i])){
                    $jamb_data = json_decode($request['jamb'][$i], true);
                    for($j = 0; $j < count($jamb_data); $j++) {
                        if (!empty($jamb_data[$j]['jamb'])) {
                            $jamb = new Jamb();
                            $jamb = $jamb->getData($jamb_data[$j]['jamb']);
                            $child_jamb[$j]['id'] = $jamb->id;
                            $child_jamb[$j]['name'] = $jamb->name;
                            $child_jamb[$j]['count'] = $jamb_data[$j]['jamb_count'] * $request['count'][$i];
                            if ($request->customer_radio == "dealer"){
                                $child_jamb[$j]['price'] = $jamb->dealer_price;
                                $child_jamb[$j]['total_price'] = $jamb->dealer_price * $jamb_data[$j]['jamb_count'] * $request['count'][$i];
                            } else {
                                $child_jamb[$j]['price'] = $jamb->retail_price;
                                $child_jamb[$j]['total_price'] = $jamb->retail_price * $jamb_data[$j]['jamb_count'] * $request['count'][$i];
                            }
                            $contract_price += $child_jamb[$j]['total_price'];
                        }
                    }
                    $jamb_parameters[$i] = $child_jamb;
                }

                $contract_price += $layerdoortype_price * $request['count'][$i];
                $door_parameters[$i]['doortype_price'] = $layerdoortype_price;
                $door_parameters[$i]['total_doortype_price'] = $layerdoortype_price * $request['count'][$i];
                
                DB::insert('INSERT INTO door_results(door_id, width, height, count, door_type, door_color, l_p, layer, doorstep, box_size, depth, box_width, box_height, lock_type, transom, transom_width, transom_height, transom_thickness, ornament_type, ornament_model, ornament_width, ornament_height, ornament_first_width, ornament_second_width,framoga_type, framoga_figure, framoga_width, framoga_height, jamb, form_width, form_height, form_first_width, form_second_width, rail_width, rail_height, rail_first_width, rail_second_width, wall_thickness, loop_name, loop_count, glass_type, glass_figure, glass_count) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)', [
                    NULL, // door_id
                    $request['width'][$i],
                    $request['height'][$i],
                    $request['count'][$i],
                    $doortype->name,
                    $request->door_color,
                    $request['l_p'][$i],
                    $door_parameters[$i]['layer'],
                    $request['doorstep'][$i],
                    $request['box_size'][$i],
                    $depth->name,
                    $output[$i]['box_width'],
                    $output[$i]['box_height'],
                    $door_parameters[$i]['locktype'],
                    !empty($transom_parameters[$i]['name']) ? $transom_parameters[$i]['name'] : NULL,
                    !empty($transom_parameters[$i]['width']) ? $transom_parameters[$i]['width'] : NULL,
                    !empty($transom_parameters[$i]['height']) ? $transom_parameters[$i]['height'] : NULL,
                    !empty($transom_parameters[$i]['thickness']) ? $transom_parameters[$i]['thickness'] : NULL,
                    $ornamenttype->name,
                    $request->ornament_model,
                    $output[$i]['ornament_width'],
                    $output[$i]['ornament_height'],
                    NULL, // ornament_first_width
                    NULL, // ornament_second_width
                    !empty($door_parameters[$i]['framogatype_name']) ? $door_parameters[$i]['framogatype_name'] : NULL,
                    !empty($door_parameters[$i]['framogafigure_name']) ? $door_parameters[$i]['framogafigure_name'] : NULL,
                    !empty($door_parameters[$i]['framoga_width']) ? $door_parameters[$i]['framoga_width'] : NULL,
                    !empty($door_parameters[$i]['framoga_height']) ? $door_parameters[$i]['framoga_height'] : NULL,
                    !empty($jamb_parameters[$i]) ? json_encode($jamb_parameters[$i]) : NULL,
                    $output[$i]['form_width'],
                    $output[$i]['form_height'],
                    NULL, // form_first_width,
                    NULL, // form_second_width,
                    $output[$i]['rail_width'],
                    $output[$i]['rail_height'],
                    NULL, // rail_first_width
                    NULL, // rail_second_width
                    !empty($request['wall_thickness'][$i]) ? $request['wall_thickness'][$i] : NULL,
                    $loop->name,
                    $request['hidden_loop_count'][$i] / 2,
                    !empty($glass_parameters[$i]['type']) ? $glass_parameters[$i]['type'] : NULL,
                    !empty($glass_parameters[$i]['figure']) ? $glass_parameters[$i]['figure'] : NULL,
                    !empty($glass_parameters[$i]['count']) ? $glass_parameters[$i]['count'] : NULL
                ]);
            }
        }

        $door = Door::find($order->door_id );
        $door->doortype_id = $doortype->id;
        $door->door_parameters = json_encode($door_parameters);
        $door->jamb_parameters = json_encode($jamb_parameters);
        $door->transom_parameters = json_encode($transom_parameters);
        $door->glass_parameters = json_encode($glass_parameters);
        $door->output = json_encode($output);
        $door->door_color = $request->door_color;
        $door->ornament_model = $request->ornament_model;
        $door->save();

        DB::update('UPDATE door_results SET door_id=? 
                    ORDER BY created_at DESC
                    LIMIT ?', [$door->id, count($door_parameters)]);

        if ($request->with_installation){
            $with_installation = 1;
            $installation_price = ($doortype->installation_price  + $transom_installation_price) * $total_doors_count;
        }
        else {
            $with_installation = 0;
            $installation_price = 0;
        }

        if ($request->with_courier){
            $with_courier = 1;
            $courier_price = $request->courier_price;
        }
        else {
            $with_courier = 0;
            $courier_price = 0;
        }

        $contract_price += $courier_price;
        $contract_price += $installation_price;

        // $order->door_id = $door->id;
        $order->contract_price = $contract_price;
        $order->with_installation = $with_installation;
        $order->installation_price = $installation_price;
        $order->with_courier = $with_courier;
        $order->courier_price = $courier_price;
        $order->last_contract_price = $contract_price;
        $order->save();

        $invoice = Invoice::where('order_id', $order->id)->first();
        $invoice->amount = $order->last_contract_price;
        $invoice->save();

        return redirect()->route('orders');
    }
}
