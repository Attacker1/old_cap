<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid', 36)->nullable();
            $table->string('external_uuid', 36)->nullable();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->integer('category_id')->unsigned()->nullable();
            $table->integer('supplier_id')->unsigned()->nullable();
            $table->string('article', 55)->nullable();
            $table->string('code',55)->nullable()->comment('Штрихкод/код товара');
            $table->string('externalCode', 55)->nullable();
            $table->string('pathName', 255)->nullable();
            $table->integer('price')->nullable();
            $table->integer('salePrice')->nullable();
            $table->integer('stock')->nullable();
            $table->integer('quantity')->nullable();
            $table->integer('reserve')->default(false);
            $table->integer('inTransit')->nullable();
            $table->string('uuidHref')->nullable();
            $table->string('slug')->nullable();
            $table->boolean('visible')->default(true);
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
        Schema::dropIfExists('stock_products');
    }
}
