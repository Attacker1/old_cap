<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('uuid', 36)->change();

            $table->string('login',255)->nullable()->change();
            $table->string('password',255)->nullable()->change();
            $table->string('name',255)->nullable()->change();
            $table->string('second_name',255)->nullable()->change();

            $table->string('phone',16)->nullable()->change();

            $table->string('email',255)->nullable()->change();

            $table->dateTime('deleted_at')->nullable()->change();

            $table->text('comments')->nullable();

            $table->string('referal_code', 15)->nullable()->unique();
            $table->integer('client_status_id')->nullable()->unsigned()->index();

            $table->foreign('client_status_id')->references('id')->on('client_statuses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('comments');
            $table->dropColumn('referal_code');
            $table->dropForeign('clients_client_status_id_foreign');
            $table->dropColumn('client_status_id');
        });
    }
}
