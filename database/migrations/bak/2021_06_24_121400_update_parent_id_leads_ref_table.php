<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateParentIdLeadsRefTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('leads_refs')) {
            Schema::table('leads_refs', function (Blueprint $table) {
                $table->smallInteger('parent_id')->nullable()->after('name');
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
        if (Schema::hasTable('leads_refs')) {
            Schema::table('leads_refs', function (Blueprint $table) {
                $table->dropColumn('parent_id');
            });
        }
    }
}
