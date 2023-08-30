<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAddSocialLinksClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('clients')) {
        Schema::table('clients', function (Blueprint $table) {
            if(!Schema::hasColumn('clients', 'socialmedia_links')) {
                $table->string('socialmedia_links', 1000)->nullable()->after('comments');
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
