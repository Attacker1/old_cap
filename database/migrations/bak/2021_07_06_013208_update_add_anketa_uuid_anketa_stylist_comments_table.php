<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAddAnketaUuidAnketaStylistCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('anketa_stylist_comments')) {
            Schema::table('anketa_stylist_comments', function (Blueprint $table) {
                if(!Schema::hasColumn('anketa_stylist_comments', 'anketa_uuid')) {
                    $table->string('anketa_uuid', 36)->nullable()->after('anketa_id');
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
        if (Schema::hasTable('anketa_stylist_comments')) {
            Schema::table('anketa_stylist_comments', function (Blueprint $table) {
                if (Schema::hasColumn('anketa_stylist_comments', 'anketa_uuid')) {
                    $table->dropColumn('anketa_uuid');
                }
            });
        }
    }
}
