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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('payer');
            $table->string('responsible');
            $table->integer('amount');
            $table->date('day');
            $table->text('reason')->nullable();
            $table->integer('order_id')->nullable();
            $table->text('parameters')->nullable(); // product_name, count, price, total_price
            $table->tinyInteger('status')->default(0); // амаки (ёки начальник) подтвердить килса кассага куринади. Нарядларда Зохид ака подтвердить киладилар
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
        Schema::dropIfExists('invoices');
    }
};
