<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAddSourceQuestionnairesTable extends Migration
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
                if(!Schema::hasColumn('questionnaires', 'source')) {
                    $table->enum('source', ['default','sber'])->default('default')->after('filename');
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
                if (Schema::hasColumn('questionnaires', 'source')) {
                    $table->dropColumn('source');
                }
            });
        }
    }
}
