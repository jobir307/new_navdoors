<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('door_id')->nullable();
            $table->integer('customer_id');
            $table->string('customer_type');
            $table->string('phone_number');
            $table->string('contract_number');
            $table->date('deadline');
            $table->tinyInteger('with_installation')->default(0);
            $table->integer('installation_price')->default(0);
            $table->tinyInteger('with_courier')->default(0);
            $table->integer('courier_price')->default(0);
            $table->integer('contract_price');
            $table->integer('rebate_percent')->default(0);
            $table->integer('last_contract_price');
            $table->tinyInteger('manager_status')->default(1);
            $table->tinyInteger('moderator_receive')->default(0);
            $table->integer('job_id')->nullable();
            $table->tinyInteger('moderator_send')->default(0);
            $table->tinyInteger('warehouse_manager_receive')->default(0);
            $table->tinyInteger('warehouse_manager_send')->default(0);
            $table->string('product');
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
