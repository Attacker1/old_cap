<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSetForeignBonusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('bonuses')) {
            Schema::table('bonuses', function (Blueprint $table) {
                if (Schema::hasColumn('bonuses', 'client_id')) {
                    $table->foreign('client_id')->references('uuid')->on('clients')->onUpdate('cascade')->onDelete('cascade');
                }
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
        //
    }
}
