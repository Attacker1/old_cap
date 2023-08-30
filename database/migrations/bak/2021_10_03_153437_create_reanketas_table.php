<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReanketasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('reanketas')) {
            Schema::create('reanketas', function (Blueprint $table) {

                $table->string('uuid', 36);
                $table->bigInteger('id', true, true);
                $table->string('code', 40)->nullable();
                $table->json('data')->nullable();
                $table->float('amount', 10, 2)->nullable();
                $table->bigInteger('amo_id', false, true)->nullable();
                $table->text('client_uuid')->nullable();
                $table->text('manager_comment')->nullable();
                $table->string('filename',100)->nullable();
                $table->string('status', 20)->nullable();

                $table->timestamps();
                $table->dateTime('deleted_at')->nullable();

            });
        }//if (!Schema::hasTable
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reanketas');
    }
}
