<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServiceTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_tokens', function (Blueprint $table) {
            $table->increments('id');
            $table->string('service',36)->nullable();
            $table->string('token',99)->nullable();
            $table->string('refresh_token',99)->nullable();
            $table->timestamps();
            $table->dateTime('expired_at')->nullable();
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
        Schema::dropIfExists('service_tokens');
    }
}
