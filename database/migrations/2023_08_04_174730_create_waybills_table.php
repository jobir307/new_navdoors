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
        Schema::create('waybills', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id');
            $table->integer('driver_id');
            $table->string('_from')->default('zavoddan');
            $table->string('_to');
            $table->text('doortype')->nullable();
            $table->text('jamb')->nullable();
            $table->text('nsjamb')->nullable();
            $table->text('transom')->nullable();
            $table->text('crown')->nullable();
            $table->text('boot')->nullable();
            $table->text('cube')->nullable();
            $table->date('day');
            $table->string('product');
            $table->text('details')->nullable();
            $table->text('sended_details')->nullable();
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
        Schema::dropIfExists('waybills');
    }
};
