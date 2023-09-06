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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->integer('region_id')->nullable();
            $table->integer('district_id')->nullable();
            $table->integer('mahalla_id')->nullable();
            $table->integer('street_id')->nullable();
            $table->string('home')->nullable();
            $table->string('name');
            $table->text('address');
            $table->string('inn')->nullable();
            $table->string('phone_number');
            $table->string('type')->comment('customer or dealer'); // xaridor yoki diler
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
        Schema::dropIfExists('customers');
    }
};
