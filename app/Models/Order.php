<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'door_id',
        'customer_id',
        'customer_type',
        'phone_number',
        'contract_number',
        'deadline',
        'installation_price', // installation_price = door_installation_price + transom_installation_price
        'door_installation_price',
        'transom_installation_price',
        'courier_price',
        'contract_price',
        'rebate_percent',
        'last_contract_price',
        'manager_status',
        'moderator_receive',
        'job_name',
        'moderator_send',
        'warehouse_manager_receive',
        'warehouse_manager_send',
        'product',
        'comments',
        'manager_verified_time',
        'moderator_send_time',
        'who_created_userid',
        'who_created_username',
    ];

    protected $hidden = [
        'door_id',
        'customer_id',
        'phone_number',
        'contract_number',
        'deadline',
        'with_installation',
        'installation_price',
        'door_installation_price',
        'transom_installation_price',
        'with_courier',
        'courier_price',
        'contract_price',
        'rebate_percent',
        'last_contract_price',
        'manager_status',
        'moderator_receive',
        'job_name',
        'moderator_send',
        'warehouse_manager_receive',
        'warehouse_manager_send',
        'comments',
        'who_created_userid',
        'who_created_username',
        
    ];
}
