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
        Schema::create('door_results', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id')->nullable();
            $table->integer('door_id')->nullable();
            $table->string('width')->nullable()->comment("eshik eni");
            $table->string('height')->nullable()->comment("eshik bo'yi");
            $table->string('count')->nullable()->comment("eshik soni");
            $table->string('door_type')->nullable()->comment("eshik turi");
            $table->string('door_color')->nullable()->comment("eshik rangi");
            $table->string('l_p')->nullable()->comment("chap-o'ngligi");
            $table->string('layer')->nullable()->comment("tabaqasi");
            $table->string('doorstep')->nullable()->comment("parogi");
            $table->string('box_size')->nullable()->comment("karobka o'lchami");
            $table->string('depth')->nullable()->comment("karobka qalinligi");
            $table->string('box_width')->nullable()->comment("karobka eni");
            $table->string('box_height')->nullable()->comment("karobka bo'yi");
            $table->string('lock_type')->nullable()->comment("zamok turi");
            $table->integer('transom_id')->nullable()->comment("dabor id");
            $table->string('transom')->nullable()->comment("dabor");
            $table->string('transom_name')->nullable();
            $table->string('transom_width')->nullable()->comment("dabor eni");
            $table->string('transom_height')->nullable()->comment("dabor bo'yi");
            $table->string('transom_thickness')->nullable()->comment("dabor qalinligi");
            $table->string('framoga_type')->nullable()->comment("framoga turi");
            $table->string('framoga_figure')->nullable()->comment("framoga shakli");
            $table->string('framoga_width')->nullable()->comment("framoga eni");
            $table->string('framoga_height')->nullable()->comment("framoga bo'yi");
            $table->text('jamb')->nullable()->comment("nalichnik");
            $table->string('ornament_type')->nullable()->comment("naqsh shakli");
            $table->string('ornament_model')->nullable()->comment("naqsh modeli");
            $table->string('ornament_width')->nullable()->comment("naqsh eni");
            $table->string('ornament_height')->nullable()->comment("naqsh bo'yi");
            $table->string('ornament_first_width')->nullable()->comment("naqsh birinchi eni");
            $table->string('ornament_second_width')->nullable()->comment("naqsh ikkinchi eni");
            $table->string('form_width')->nullable()->comment("qolip eni");
            $table->string('form_height')->nullable()->comment("qolip bo'yi");
            $table->string('form_first_width')->nullable()->comment("qolip birinchi eni");
            $table->string('form_second_width')->nullable()->comment("qolip ikkinchi eni");
            $table->string('rail_width')->nullable()->comment("reyka eni");
            $table->string('rail_height')->nullable()->comment("reyka bo'yi");
            $table->string('rail_first_width')->nullable()->comment("reyka birinchi eni");
            $table->string('rail_second_width')->nullable()->comment("reyka ikkinchi eni");
            $table->string('wall_thickness')->nullable()->comment("devor qalinligi");
            $table->string('loop_name')->nullable()->comment("chaspak nomi");
            $table->string('loop_count')->nullable()->comment("chaspak soni");
            $table->string('glass_type')->nullable()->comment("shisha turi");
            $table->string('glass_figure')->nullable()->comment("shisha shakli");
            $table->integer('glass_count')->nullable()->comment("shisha soni");
            $table->integer('crown_id')->nullable()->comment("korona id");
            $table->string('crown_name')->nullable()->comment("korona nomi");
            $table->integer('crown_count')->nullable()->comment("korona soni");
            $table->integer('cube_id')->nullable()->comment("kubik id");
            $table->string('cube_name')->nullable()->comment("kubik nomi");
            $table->integer('cube_count')->nullable()->comment("kubik soni");
            $table->integer('boot_id')->nullable()->comment("sapog id");
            $table->string('boot_name')->nullable()->comment("sapog nomi");
            $table->integer('boot_count')->nullable()->comment("sapog soni");
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
        Schema::dropIfExists('door_results');
    }
};
