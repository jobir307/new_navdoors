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
        Schema::create('order_processes', function (Blueprint $table) {
            $table->id();
            $table->string('product')->nullable();
            $table->integer('order_id');
            $table->integer('worker_id')->nullable();
            $table->integer('job_id')->nullable();
            $table->tinyInteger('started')->default(0);
            $table->datetime('started_datetime')->nullable();
            $table->tinyInteger('done')->default(0);
            $table->datetime('done_datetime')->nullable();
            $table->double('product_count')->default(0);
            $table->double('salary')->default(0);
            $table->tinyInteger('paid')->default(0);
            $table->dateTime('paid_time')->nullable();
            $table->tinyInteger('cashier_paid')->default(0);
            $table->dateTime('cashier_paid_time')->nullable();
            $table->integer('stock_id')->nullable();
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
        Schema::dropIfExists('order_processes');
    }
};
