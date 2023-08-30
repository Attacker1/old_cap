<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('leads');
        Schema::create('leads', function (Blueprint $table) {
            $table->string('id',36)->primary()->index();
            $table->string('client_id',36)->index(); // ->collation(false)
            $table->unsignedInteger('stylist_id')->index()->nullable(); // User_id ?
            $table->unsignedInteger('amo_lead_id')->index()->nullable();
            $table->unsignedInteger('state')->index()->nullable();
            $table->unsignedInteger('state_logistic')->index()->nullable();
            $table->timestamps();

            // TODO - Ошибка связи из-за collation!
            $table->foreign('client_id')->references('uuid')->on('clients')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leads');
    }
}
