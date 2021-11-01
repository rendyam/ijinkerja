<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCallCenterKeamananColumnEntryPermitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('entry_permits', function (Blueprint $table) {
            $table->integer('call_center_user_id')->nullable();
            $table->integer('call_center_status')->nullable();
            $table->timestamp('call_center_updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('entry_permits', function (Blueprint $table) {
            $table->dropColumn('call_center_user_id');
            $table->dropColumn('call_center_status');
            $table->dropColumn('call_center_updated_at');
        });
    }
}
