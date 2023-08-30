<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDeleteForeignLeadsTable extends Migration
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
        if (Schema::hasTable('leads')) {
            Schema::table('leads', function (Blueprint $table) {
                if (Schema::hasColumn('leads', 'state_id')) {
                    $foreignKeys = $this->listTableForeignKeys('leads');

                    if(in_array('leads_leads_refs_id_fk', $foreignKeys))
                        $table->dropForeign('leads_leads_refs_id_fk');
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
