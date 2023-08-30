<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBonusRefsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bonus_refs', function (Blueprint $table) {
            $table->unsignedInteger('id');
            $table->string('name',100)->nullable();
            $table->smallInteger('points')->default(0);
            $table->enum('type', ['add','sub'])->default('add');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bonus_refs');
    }
}
