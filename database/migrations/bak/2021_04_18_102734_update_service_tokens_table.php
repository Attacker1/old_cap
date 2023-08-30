<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateServiceTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('service_tokens')){
            Schema::table('service_tokens', function (Blueprint $table) {
                $table->text('token')->nullable()->change();

                if (Schema::hasColumn('service_tokens','refresh_token')){
                    $table->text('refresh_token')->nullable()->change();
                }
                else {
                    $table->text('refresh_token')->nullable();
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
