<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoliTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('doli_transactions')) {
            Schema::create('doli_transactions', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->uuid('lead_uuid')->index();
                $table->string('doli_id', 20)->index()->comment("Номер заказа для ДОЛИ");
                $table->json('items')->nullable()->comment("Приобретаемые товары по заказу");
                $table->float('amount')->nullable();
                $table->float('x_correlation_id')->nullable();
                $table->json('returned_items')->nullable();
                $table->enum('state', ["created", "commit", "committed", "approved", "wait_for_commit", "rejected", "canceled", "completed", "refunded"])->default("created")->nullable();
                $table->dateTime('completed_at')->nullable()->comment("Данные успеха");
                $table->dateTime('refund_at')->nullable()->comment("Данные возврата");
                $table->dateTime('deleted_at')->nullable();
                $table->timestamps();
            });
        }

        DB::statement("ALTER TABLE `doli_transactions` comment 'Транзакции по сервису Долями'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('doli_transactions');
    }
}
