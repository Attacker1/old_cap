<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('lead_id',36)->nullable()->index();
            $table->enum('source',['boxberry','logsys'])->nullable();
            $table->string('delivery_id',60)->nullable()->comment('Идентификатор заказа от доставщика');
            $table->integer('delivery_point_id')->nullable()->comment('ПВЗ - ID пункта выдачи заказа');
            $table->string('delivery_address',255)->nullable()->comment('Адрес пункта выдачи заказа текстом');
            $table->string('state',60)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deliveries');
    }
}
