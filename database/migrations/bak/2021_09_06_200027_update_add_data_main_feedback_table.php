<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAddDataMainFeedbackTable extends Migration
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
                if(!Schema::hasColumn('feedbackgeneral_quizes', 'data')) {
                    $table->json('data')->nullable()->after('old_id');
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
