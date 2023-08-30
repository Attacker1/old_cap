<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid', 36)->nullable();
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->integer('parent_id')->unsigned()->nullable();
            $table->string('slug')->nullable();
            $table->boolean('visible')->nullable();
            $table->string('externalCode')->nullable();
            $table->string('externalId')->nullable();
            $table->json('productFolder')->nullable();
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
        Schema::dropIfExists('stock_categories');
    }
}
