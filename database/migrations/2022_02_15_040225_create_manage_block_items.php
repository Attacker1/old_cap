<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManageBlockItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manage_block_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('key');
            $table->integer('sort')->default(50);
            $table->json('roles')->nullable();
            $table->integer('block_id');
            $table->string('title');
            $table->string('position')->default(null);
            $table->text('tooltip')->default(null);
            $table->string('input_type')->nullable();
            $table->string('input_value')->nullable();
            $table->json('input_rules')->nullable();
            $table->string('input_mask',50)->nullable();
            $table->string('filter',50)->nullable();
            $table->integer('sortable')->default(0);
            $table->integer('disabled')->default(0);
            $table->string('view_type')->nullable();
            $table->string('view_class')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manage_fields_items');
    }
}
