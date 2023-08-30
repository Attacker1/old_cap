<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropAndCreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::dropIfExists('clients');

        Schema::create('clients', function (Blueprint $table) {
            $table->string('uuid', 36);

            $table->string('login',255)->nullable();
            $table->string('password',255)->nullable();
            $table->string('name',255)->nullable();
            $table->string('second_name',255)->nullable();

            $table->string('phone',16)->nullable();
            $table->string('email',255)->nullable();

            $table->text('comments')->nullable();

            $table->string('referal_code', 15)->nullable()->unique();
            $table->integer('client_status_id')->nullable()->unsigned()->index();
            $table->integer('sms_code', false, true)->length(10)->nullable();
            $table->index(['phone', 'sms_code']);

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

    }
}
