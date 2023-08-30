<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropAndCreateFeedbackgeneralQuizesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('feedbackgeneral_quizes');

        Schema::create('feedbackgeneral_quizes', function (Blueprint $table) {
            $table->bigInteger('id', true, true);
            $table->string('client_uuid', 36)->nullable();
            $table->bigInteger('amo_id', false, true)->nullable();

            $table->enum('personal_attitude', ['1','2','3','4','5'])->nullable();
            $table->enum('general_impression', ['1','2','3','4','5'])->nullable();
            $table->enum('buy_more', ['1','2','3','4','5'])->nullable();

            $table->text('stylist_note_wanted')->nullable();
            $table->text('other_any_comments')->nullable();

            $table->enum('new_stylist', ['yes','no'])->nullable();
            $table->enum('design', ['1','2','3','4','5'])->nullable();
            $table->enum('recommended', ['1','2','3','4','5','6','7','8','9','10'])->nullable();

            $table->text('mark_reason')->nullable();
            $table->text('mark_up_actions')->nullable();
            $table->enum('repeat_date', ['week','month','two_months','half_year','other'])->nullable();
            $table->string('repeat_date_own', 255)->nullable();
            $table->enum('delivery_mark', ['1','2','3','4','5','6','7','8','9','10'])->nullable();
            $table->text('delivery_comment')->nullable();

            $table->timestamps();
            $table->dateTime('deleted_at')->nullable();
        });
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
