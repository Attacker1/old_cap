<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLinkLeadProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('link_lead_product')) {
            Schema::create('link_lead_product', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('lead_uuid', 36)->nullable();
                $table->bigInteger('product_id', false, true)->nullable();
                $table->bigInteger('feedback_quize_id', false, true)->nullable();
                $table->dateTime('deleted_at', $precision = 0)->nullable();
                $table->timestamps();

                $table->foreign('lead_uuid')->references('uuid')->on('leads');
                $table->foreign('product_id')->references('id')->on('products');
                $table->foreign('feedback_quize_id')->references('id')->on('feedback_quizes');
               
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
        Schema::table('link_lead_product', function (Blueprint $table) {
            $table->dropForeign('link_lead_product_feedback_quize_id_foreign');
            $table->dropForeign('link_lead_product_lead_uuid_foreign');
            $table->dropForeign('link_lead_product_product_id_foreign');
        });
        Schema::dropIfExists('link_lead_product');
    }
}
