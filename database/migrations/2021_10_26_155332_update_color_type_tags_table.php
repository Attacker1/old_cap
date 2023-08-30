<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateColorTypeTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('tags')) {
            Schema::table('tags', function (Blueprint $table) {
                if(!Schema::hasColumn('tags', 'color')) {
                    $table->string('color',255)->nullable()->after('name');
                }
                if(!Schema::hasColumn('tags', 'type')) {
                    $table->enum('type', ['lead'])->default('lead')->after('color');
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
