<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockProductAttributesUniqueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('stock_product_attributes_unique')) {
            Schema::create('stock_product_attributes_unique', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('attribute_id')->nullable();
                $table->json('value')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_product_attributes_unique');
    }
}
