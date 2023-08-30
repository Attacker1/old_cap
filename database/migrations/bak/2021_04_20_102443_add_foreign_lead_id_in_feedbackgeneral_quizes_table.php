<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignLeadIdInFeedbackgeneralQuizesTable extends Migration
{
    //ищем внешние ключи
    public function listTableForeignKeys($table)
    {
        $conn = Schema::getConnection()->getDoctrineSchemaManager();

        return array_map(function($key) {
            return $key->getName();
        }, $conn->listTableForeignKeys($table));
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('feedbackgeneral_quizes')) {
            Schema::table('feedbackgeneral_quizes', function (Blueprint $table) {
                if(Schema::hasColumn('feedbackgeneral_quizes', 'amo_id')) {
                    $table->dropColumn('amo_id');
                }

                if(!Schema::hasColumn('feedbackgeneral_quizes', 'lead_id')) {
                    $table->string('lead_id', 36)->nullable();
                }

                $foreignKeys = $this->listTableForeignKeys('feedbackgeneral_quizes');

                if(!in_array('feedbackgeneral_quizes_lead_id_foreign', $foreignKeys))
                    $table->foreign('lead_id')->references('uuid')->on('leads');
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
        Schema::table('feedbackgeneral_quizes', function (Blueprint $table) {
            //
        });
    }
}
