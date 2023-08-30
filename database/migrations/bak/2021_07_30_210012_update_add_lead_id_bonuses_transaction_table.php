<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAddLeadIdBonusesTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('bonus_transactions')) {
            Schema::table('bonus_transactions', function (Blueprint $table) {
                if(!Schema::hasColumn('bonus_transactions', 'lead_uuid')) {
                    $table->string('lead_uuid', 36)->nullable()->after('client_id');
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
