<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadControlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_controls', function (Blueprint $table) {
            $table->increments('id');
            $table->string('leadUuid', 36)->nullable();
            $table->boolean('stylistNotified')->default(false);
            $table->boolean('methodistNotified')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lead_controls');
    }
}
