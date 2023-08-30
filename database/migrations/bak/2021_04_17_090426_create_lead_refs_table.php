<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadRefsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::dropIfExists('leads_refs');
        Schema::create('leads_refs', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary()->index();
            $table->string('name', 100)->nullable();
            $table->timestamps();
            $table->dateTime('deleted_at')->nullable();
        });

        if (Schema::hasTable('leads')) {
            Schema::table('leads', function (Blueprint $table) {
                if (Schema::hasColumn('leads', 'state_id')) {

                    if (Schema::hasColumn('leads', 'state')) {
                        $table->dropColumn('state');
                    }
                    $table->unsignedInteger('state_id')->index()->change();
                    $table->foreign('state_id')->references('id')->on('leads_refs');
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
        Schema::dropIfExists('leads_refs');
    }
}
