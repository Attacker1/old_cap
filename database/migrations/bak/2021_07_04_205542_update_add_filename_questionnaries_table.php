<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAddFilenameQuestionnariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('questionnaires')) {
            Schema::table('questionnaires', function (Blueprint $table) {
                if(!Schema::hasColumn('questionnaires', 'filename')) {
                    $table->string('filename',100)->nullable()->after('manager_comment');
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
        if (Schema::hasTable('questionnaires')) {
            Schema::table('questionnaires', function (Blueprint $table) {
                if (Schema::hasColumn('questionnaires', 'filename')) {
                    $table->dropColumn('filename');
                }
            });
        }
    }
}
