<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnTokoNameAndLinkTokoInAuthTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('auth', function (Blueprint $table) {
            $table->string('toko_name')->after('user_id')->nullable();
            $table->string('link_toko')->after('toko_name')->nullable();
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
            $table->dropColumn(['toko_name', 'link_toko']);
        });
    }
}
