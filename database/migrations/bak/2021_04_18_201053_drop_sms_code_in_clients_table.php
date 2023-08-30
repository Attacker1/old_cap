<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropSmsCodeInClientsTable extends Migration
{

    //ищем ключи
    public function listTableIndexes($table)
    {
        $conn = Schema::getConnection()->getDoctrineSchemaManager();

        return array_map(function($key) {
            return $key->getName();
        }, $conn->listTableIndexes($table));
    }


    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('clients')) {
            Schema::table('clients', function (Blueprint $table) {
                if(Schema::hasColumn('clients', 'sms_code')) {

                    $indexes = $this->listTableIndexes('clients');

                    if(in_array('clients_phone_sms_code_index', $indexes))
                        $table->dropIndex('clients_phone_sms_code_index');

                    $table->dropColumn('sms_code');
                    $table->string('phone',16)->nullable()->unique()->change();
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
        Schema::table('clients', function (Blueprint $table) {
            //
        });
    }
}
