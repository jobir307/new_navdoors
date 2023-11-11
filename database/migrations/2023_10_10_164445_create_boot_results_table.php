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
        Schema::create('boot_results', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id')->nullable();
            $table->integer('boot_id');
            $table->string('boot_name');
            $table->string('boot_color');
            $table->integer('count');
            $table->double('price');
            $table->double('total_price');
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
        Schema::dropIfExists('boot_results');
    }
};
