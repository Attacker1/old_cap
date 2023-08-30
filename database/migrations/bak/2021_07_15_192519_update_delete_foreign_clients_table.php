<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDeleteForeignClientsTable extends Migration
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

                    if(in_array('clients_client_status_id_foreign', $foreignKeys))
                        $table->dropForeign('clients_client_status_id_foreign');
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
