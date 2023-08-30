<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDeleteForeignLinkLeadsTable extends Migration
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
        //удаление внешнего ключа client_status_id таблицы link_lead_products
        if (Schema::hasTable('link_lead_products')) {
            Schema::table('link_lead_products', function (Blueprint $table) {
                if (Schema::hasColumn('link_lead_products', 'feedback_quize_id')) {
                    $foreignKeys = $this->listTableForeignKeys('link_lead_products');

                    if(in_array('FK_link_lead_products_feedback_quizes', $foreignKeys))
                        $table->dropForeign('FK_link_lead_products_feedback_quizes');
                }

                if (Schema::hasColumn('link_lead_products', 'lead_uuid')) {
                    $foreignKeys = $this->listTableForeignKeys('link_lead_products');

                    if(in_array('link_lead_product_lead_uuid_foreign', $foreignKeys))
                        $table->dropForeign('link_lead_product_lead_uuid_foreign');
                }

                if (Schema::hasColumn('link_lead_products', 'product_id')) {
                    $foreignKeys = $this->listTableForeignKeys('link_lead_products');

                    if(in_array('link_lead_product_product_id_foreign', $foreignKeys))
                        $table->dropForeign('link_lead_product_product_id_foreign');
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
