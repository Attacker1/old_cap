<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAddAmountFeedbackTable extends Migration
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
                if(!Schema::hasColumn('feedback_quizes', 'price')) {
                    $table->decimal('price', 10,2)->nullable()->after('action_result');
                }

                if(!Schema::hasColumn('feedback_quizes', 'discount_price')) {
                    $table->decimal('discount_price', 10,2)->nullable()->after('price');
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
