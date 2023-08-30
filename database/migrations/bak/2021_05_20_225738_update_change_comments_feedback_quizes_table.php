<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateChangeCommentsFeedbackQuizesTable extends Migration
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
                if (Schema::hasColumn('feedback_quizes','comments')){
                    $table->dropColumn('comments');
                }    
            });
            Schema::table('feedback_quizes', function (Blueprint $table) {
                if (!Schema::hasColumn('feedback_quizes','comments')){
                    $table->text('comments')->nullable()->after('style_opinion');
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
