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
        Schema::create('doors', function (Blueprint $table) {
            $table->id();
            $table->integer('doortype_id');
            $table->longText('door_parameters')->nullable();
            $table->longText('jamb_parameters')->nullable();
            $table->longText('transom_parameters')->nullable();
            $table->longText('glass_parameters')->nullable();
            $table->longText('crown_parameters')->nullable();
            $table->longText('boot_parameters')->nullable();
            $table->longText('cube_parameters')->nullable();
            $table->longText('output')->nullable();
            $table->string('door_color');
            $table->string('ornament_model');
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
        Schema::dropIfExists('doors');
    }
};
