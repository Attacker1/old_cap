<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('stock_attributes')) {
            Schema::create('stock_attributes', function (Blueprint $table) {
                $table->increments('id');
                $table->uuid('uuid')->nullable();
                $table->uuid('external_id')->nullable();
                $table->string('name')->nullable();
                $table->string('type')->nullable();
                $table->string('description')->nullable();
                $table->boolean('required')->nullable();
                $table->integer('value')->nullable();
                $table->timestamps();
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
        Schema::dropIfExists('stock_attributes');
    }
}
