<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnDashboardAuth extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('auth', function (Blueprint $table) {
            $table->integer('unprocessed_order')->nullable()->after('client_id');
            $table->json('dashboard_data')->nullable()->after('unprocessed_order');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('auth', function (Blueprint $table) {
            $table->dropColumn(['unprocessed_order', 'dashboard_data']);
        });
    }
}
