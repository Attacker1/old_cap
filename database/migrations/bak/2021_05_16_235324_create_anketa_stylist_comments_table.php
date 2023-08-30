<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnketaStylistCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('anketa_stylist_comments')) {    
            Schema::create('anketa_stylist_comments', function (Blueprint $table) {
                $table->bigInteger('id', true, true);
                $table->bigInteger('stylist_id', false, true)->nullable();
                $table->bigInteger('anketa_id', false, true)->nullable();
                $table->text('content')->nullable();
                $table->timestamps();
                $table->dateTime('deleted_at')->nullable();
                $table->unique(['anketa_id', 'stylist_id']);
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
        Schema::dropIfExists('anketa_stylist_comments');
    }
}
