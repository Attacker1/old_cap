<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropForeignsClientsFeedbacks extends Migration
{
    //ищем внешние ключи
    public function listTableForeignKeys($table)
    {
        $conn = Schema::getConnection()->getDoctrineSchemaManager();

        return array_map(function($key) {
            return $key->getName();
        }, $conn->listTableForeignKeys($table));
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //удаление внешнего ключа client_status_id таблицы clients
        if (Schema::hasTable('clients')) {
            Schema::table('clients', function (Blueprint $table) {
                if (Schema::hasColumn('clients', 'client_status_id')) {
                    $foreignKeys = $this->listTableForeignKeys('clients');

                    if(in_array('clients_ibfk_1', $foreignKeys))
                        $table->dropForeign('clients_ibfk_1');
                }
            });
        }

        //удаление внешнего ключа feedbackgeneral_quizes_client_uuid_foreign таблицы feedbackgeneral_quizes
        if (Schema::hasTable('feedbackgeneral_quizes')) {
            Schema::table('feedbackgeneral_quizes', function (Blueprint $table) {
                if (Schema::hasColumn('feedbackgeneral_quizes', 'client_uuid')) {
                    $foreignKeys = $this->listTableForeignKeys('feedbackgeneral_quizes');

                    if(in_array('feedbackgeneral_quizes_client_uuid_foreign', $foreignKeys))
                        $table->dropForeign('feedbackgeneral_quizes_client_uuid_foreign');
                }
            });
        }

        //удаление внешних ключей таблицы feedback_quizes
        if (Schema::hasTable('feedback_quizes')) {
            Schema::table('feedback_quizes', function (Blueprint $table) {
                $foreignKeys = $this->listTableForeignKeys('feedback_quizes');

                if (Schema::hasColumn('feedback_quizes', 'product_id')) {
                    if(in_array('feedback_quizes_product_id_foreign', $foreignKeys))
                        $table->dropForeign('feedback_quizes_product_id_foreign');
                }

                if (Schema::hasColumn('feedback_quizes', 'client_uuid')) {
                    if(in_array('feedback_quizes_client_uuid_foreign', $foreignKeys))
                        $table->dropForeign('feedback_quizes_client_uuid_foreign');
                }

                if (Schema::hasColumn('feedback_quizes', 'feedbackgeneral_quize_id')) {
                    if(in_array('feedback_quizes_feedbackgeneral_quize_id_foreign', $foreignKeys))
                        $table->dropForeign('feedback_quizes_feedbackgeneral_quize_id_foreign');
                }

            });
        }

        //удаление внешних ключей из таблицы orders
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                $foreignKeys = $this->listTableForeignKeys('orders');
                if (Schema::hasColumn('orders', 'client_uuid')) {

                    if(in_array('orders_client_uuid_foreign', $foreignKeys))
                        $table->dropForeign('orders_client_uuid_foreign');
                }
                if (Schema::hasColumn('orders', 'feedbackgeneral_quize_id')) {

                    if(in_array('orders_feedbackgeneral_quize_id_foreign', $foreignKeys))
                        $table->dropForeign('orders_feedbackgeneral_quize_id_foreign');
                }
            });
        }

        //удаление внешних ключей из таблицы order_products
        if (Schema::hasTable('order_products')) {
            Schema::table('order_products', function (Blueprint $table) {
                $foreignKeys = $this->listTableForeignKeys('order_products');

                if (Schema::hasColumn('order_products', 'order_uuid')) {

                    if(in_array('order_products_order_uuid_foreign', $foreignKeys))
                        $table->dropForeign('order_products_order_uuid_foreign');
                }

                if (Schema::hasColumn('order_products', 'product_id')) {

                    if(in_array('order_products_product_id_foreign', $foreignKeys))
                        $table->dropForeign('order_products_product_id_foreign');
                }

                if (Schema::hasColumn('order_products', 'feedback_quize_id')) {

                    if(in_array('order_products_feedback_quize_id_foreign', $foreignKeys))
                        $table->dropForeign('order_products_feedback_quize_id_foreign');
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

    }
}
