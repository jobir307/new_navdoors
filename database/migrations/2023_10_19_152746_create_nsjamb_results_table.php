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
        Schema::create('nsjamb_results', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id');
            $table->integer('nsjamb_id');
            $table->string('name');
            $table->string('color');
            $table->double('height');
            $table->double('width_top');
            $table->double('width_bottom');
            $table->double('count');
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
        Schema::dropIfExists('nsjamb_results');
    }
};
