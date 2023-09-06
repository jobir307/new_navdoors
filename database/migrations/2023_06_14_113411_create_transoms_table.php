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
        Schema::create('transoms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('doortype_id');
            $table->integer('dealer_price');
            $table->integer('retail_price');
            $table->integer('installation_price')->nullable();
            $table->text('jobs')->nullable();
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
        Schema::dropIfExists('transoms');
    }
};
