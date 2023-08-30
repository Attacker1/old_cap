<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnStatusFeedbackGeneralQuizeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('feedbackgeneral_quizes')) {
            Schema::table('feedbackgeneral_quizes', function (Blueprint $table) {
                if(!Schema::hasColumn('feedbackgeneral_quizes', 'status')) {
                    $table->enum('status', ['новый','завершен'])->nullable()->after('lead_id');
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
        if (Schema::hasTable('feedbackgeneral_quizes')) {
            Schema::table('feedbackgeneral_quizes', function (Blueprint $table) {
                if(Schema::hasColumn('feedbackgeneral_quizes', 'status')) {
                    $table->dropColumn('status');
                }
            });
        }
    }
}
