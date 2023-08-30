<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAddDescriptionBonusTransactionsTable extends Migration
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
                $table->json('description')->nullable()->after('user_id');
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
        Schema::table('bonus_transactions', function (Blueprint $table) {
            if (Schema::hasColumn('bonus_transactions', 'description')) {
                $table->dropColumn('description');
            }
        });
    }
}
