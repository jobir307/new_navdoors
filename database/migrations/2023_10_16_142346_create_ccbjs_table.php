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
        Schema::create('ccbjs', function (Blueprint $table) { // korona+kubik+sapog+nalichnik
            $table->id();
            $table->integer('crown_id');
            $table->integer('cube_id');
            $table->integer('boot_id');
            $table->integer('jamb_id');
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
        Schema::dropIfExists('ccbjs');
    }
};
