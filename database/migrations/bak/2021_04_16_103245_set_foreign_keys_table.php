<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetForeignKeysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //установка внешнего ключа client_status_id таблицы clients
        if (Schema::hasTable('clients')) {
            Schema::table('clients', function (Blueprint $table) {
                if (Schema::hasColumn('clients', 'client_status_id')) {
                    $table->foreign('client_status_id')->references('id')->on('client_statuses');
                }
            });
        }

        //установка внешнего ключа таблицы feedbackgeneral_quizes
        if (Schema::hasTable('feedbackgeneral_quizes')) {
            Schema::table('feedbackgeneral_quizes', function (Blueprint $table) {
                if (Schema::hasColumn('feedbackgeneral_quizes', 'client_uuid')) {
                    $table->foreign('client_uuid')->references('uuid')->on('clients');
                }
            });
        }

        //установка внешних ключей таблицы feedback_quizes
        if (Schema::hasTable('feedback_quizes')) {
            Schema::table('feedback_quizes', function (Blueprint $table) {

                if (Schema::hasColumn('feedback_quizes', 'client_uuid')) {
                    $table->foreign('client_uuid')->references('uuid')->on('clients');
                }

                if (Schema::hasColumn('feedback_quizes', 'feedbackgeneral_quize_id')) {
                    $table->foreign('feedbackgeneral_quize_id')->references('id')->on('feedbackgeneral_quizes');
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
        $table->dropForeign('clients_client_status_id_foreign');
        $table->dropForeign('feedbackgeneral_quizes_client_uuid_foreign');
        $table->dropForeign('feedback_quizes_client_uuid_foreign');
        $table->dropForeign('feedback_quizes_feedbackgeneral_quize_id_foreign');
    }
}
