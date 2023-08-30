<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUtmsTableAddAnketaUuid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('utms')) {
            Schema::table('utms', function (Blueprint $table) {
                if(Schema::hasColumn('utms', 'anketa_id')) {
                    $table->dropColumn('anketa_id');
                }
                if(!Schema::hasColumn('utms', 'anketa_uuid')) {
                    $table->uuid('anketa_uuid')->nullable()->after('id');
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
