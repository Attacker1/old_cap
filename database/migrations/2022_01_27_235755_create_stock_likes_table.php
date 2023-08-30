<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_likes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('stock_product_id')->unsigned();
            $table->integer('stylist_id')->unsigned();
            $table->unique(['stock_product_id', 'stylist_id']);
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
        Schema::dropIfExists('stock_likes');
    }
}
