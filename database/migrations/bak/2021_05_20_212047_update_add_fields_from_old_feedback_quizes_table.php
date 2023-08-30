<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAddFieldsFromOldFeedbackQuizesTable extends Migration
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
                $table->tinyInteger('order', false, true)->nullable()->after('amo_id');
                $table->bigInteger('old_id', false, true)->nullable()->after('comments');
                $table->string('old_code', 20)->nullable()->after('old_id');
                $table->text('old_url')->nullable()->after('old_code');
                $table->string('old_cost', 64)->nullable()->after('old_url');
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
        
    }
}
