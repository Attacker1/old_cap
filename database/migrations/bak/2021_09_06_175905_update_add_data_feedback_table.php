<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAddDataFeedbackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('feedback_quizes')) {
            Schema::table('feedback_quizes', function (Blueprint $table) {
                if(!Schema::hasColumn('feedback_quizes', 'data')) {
                    $table->json('data')->nullable()->after('old_cost');
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
