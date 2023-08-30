<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStockCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('stock_carts')) {
            Schema::table('stock_carts', function (Blueprint $table) {
                if (!Schema::hasColumn('stock_carts','lead_uuid')) {
                    $table->uuid('lead_uuid')->nullable()->after('stylist_id');
                }
                if (!Schema::hasColumn('stock_carts','reserved_at')) {
                    $table->dateTime('reserved_at')->nullable()->after('stylist_id');
                }
                if (!Schema::hasColumn('stock_carts','deleted_at')) {
                    $table->dateTime('deleted_at')->nullable()->after('stylist_id');
                }
                if (!Schema::hasColumn('stock_carts','state_id')) {
                    $table->integer('state_id')->nullable()->after('stylist_id');
                }
                if (!Schema::hasColumn('stock_carts','confirmed_at')) {
                    $table->dateTime('confirmed_at')->nullable()->after('stylist_id');
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
        //
    }
}
