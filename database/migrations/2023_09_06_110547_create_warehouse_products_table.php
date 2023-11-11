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
        Schema::create('warehouse_products', function (Blueprint $table) {
            $table->id();
            $table->integer('warehouse_id');
            $table->integer('category_id');
            $table->integer('product_id');
            $table->string('unit');
            $table->string('package_name');
            $table->double('package_count');
            $table->double('last_amount');
            $table->double('alert_amount');
            $table->double('purchase_price');
            $table->double('retail_price');
            $table->double('dealer_price');
            $table->string('product_type')->comment('Товары или материалы');
            $table->tinyInteger('action')->comment('Приход или расход (1 or -1)');
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
        Schema::dropIfExists('warehouse_products');
    }
};
