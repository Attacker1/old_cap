<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class StockOtherImages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('stock_other_images')) {
            Schema::create('stock_other_images', function (Blueprint $table) {
                $table->increments('id');
                $table->uuid('uuid')->nullable()->index();
                $table->string('url')->nullable();
                $table->dateTime('processed_at')->nullable();
            });

            DB::statement("ALTER TABLE `stock_other_images` comment 'Импорт доп. фотографий. тех таблица'");
        }
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
