<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadCorrectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('lead_corrections')) {
            Schema::create('lead_corrections', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->uuid('lead_uuid')->index()->comment("Товары из Записки > notes.id");
                $table->integer('note_id')->nullable()->index()->comment("Товары из Записки > notes.id");
                $table->json('data')->nullable();
                $table->integer('user_id')->nullable()->comment("Пользователь > users.id");
                $table->dateTime('deleted_at')->nullable();
                $table->timestamps();
            });
        }

        DB::statement("ALTER TABLE `lead_corrections` comment 'Коррекция цен товаров по сделке'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('lead_corrections');
    }
}
