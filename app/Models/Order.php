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
        'installation_price',
        'courier_price',
        'contract_price',
        'rebate_percent',
        'last_contract_price',
        'manager_status',
        'moderator_receive',
        'job_id',
        'moderator_send',
        'warehouse_manager_receive',
        'warehouse_manager_send',
        'product',
    ];

    protected $hidden = [
        'door_id',
        'customer_id',
        'phone_number',
        'contract_number',
        'deadline',
        'with_installation',
        'installation_price',
        'with_courier',
        'courier_price',
        'contract_price',
        'rebate_percent',
        'last_contract_price',
        'manager_status',
        'moderator_receive',
        'job_id',
        'moderator_send',
        'warehouse_manager_receive',
        'warehouse_manager_send',
    ];
}
