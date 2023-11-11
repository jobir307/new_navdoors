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
        Schema::create('cube_results', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id')->nullable();
            $table->integer('cube_id');
            $table->string('cube_name');
            $table->string('cube_color');
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
        Schema::dropIfExists('cube_results');
    }
};
