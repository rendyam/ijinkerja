<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddListDokumenColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('work_permits', function (Blueprint $table) {
            $table->json('list_dokumen')->nullable()->after('dokumen_pendukung');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('work_permits', function (Blueprint $table) {
            $table->dropColumn('list_dokumen');
        });
    }
}
