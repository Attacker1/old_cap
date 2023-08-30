<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropAndCreateFeedbackQuizesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('feedback_quizes');

        Schema::create('feedback_quizes', function (Blueprint $table) {
            $table->bigInteger('id', true, true);
            $table->bigInteger('product_id', false, true)->nullable();
            $table->string('client_uuid', 36)->nullable();
            $table->bigInteger('amo_id', false, true)->nullable();
            $table->bigInteger('feedbackgeneral_quize_id', false, true)->nullable();
            $table->enum('action_result', ['buy','return'])->nullable();
            $table->enum('size_result', ['small','ok','big'])->nullable();
            $table->enum('quality_opinion', ['1','2','3','4','5'])->nullable();
            $table->enum('price_opinion', ['1','2','3','4','5'])->nullable();
            $table->enum('style_opinion', ['1','2','3','4','5'])->nullable();
            $table->string('comments',500)->nullable();

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
