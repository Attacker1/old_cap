<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAddCommentsQuestionnariesTable extends Migration
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
                if(!Schema::hasColumn('questionnaires', 'manager_comment')) {
                    $table->text('manager_comment')->nullable()->after('amo_id');;
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
                if (Schema::hasColumn('questionnaires', 'manager_comment')) {
                    $table->dropColumn('manager_comment');
                }
            });
        }
    }
}
