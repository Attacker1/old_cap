<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LeadCorrectionsProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('lead_corrections_products')) {
            Schema::create('lead_corrections_products', function (Blueprint $table) {

                $table->bigIncrements('id');
                $table->integer('correction_id')->index();
                $table->integer('product_id')->index();
                $table->uuid('lead_uuid')->nullable()->index();
                $table->float('price')->nullable();
                $table->timestamps();
            });
        }
        DB::statement("ALTER TABLE `lead_corrections_products` comment 'Цены продукции по сделке'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
