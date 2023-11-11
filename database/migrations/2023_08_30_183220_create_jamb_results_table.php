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
        Schema::create('jamb_results', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id');
            $table->integer('jamb_id');
            $table->string('jamb_color');
            $table->string('name');
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
        Schema::dropIfExists('jamb_results');
    }
};
