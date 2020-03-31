<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkPermitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_permits', function (Blueprint $table) {
            $table->id();
            $table->enum('kategori', ['Resiko Rendah', 'Resiko Tinggi']);
            $table->json('jenis_resiko');
            $table->json('izin_diberikan_kepada');
            $table->integer('pic_safety_officer')->unsigned();
            $table->bigInteger('pic_pemohon')->unsigned();
            $table->json('masa_berlaku');
            $table->string('lokasi_pekerjaan');
            $table->text('uraian_singkat_pekerjaan');
            $table->json('jenis_bahaya');
            $table->json('apd');
            $table->text('catatan_safety_officer');
            $table->json('dokumen_pendukung');
            $table->json('perpanjangan_pekerjaan');
            $table->json('penutupan_ijin_kerja');
            $table->integer('status')->unsigned();
            $table->timestamps();

            $table->foreign('pic_safety_officer')->references('id')->on('db_efile.users');
            $table->foreign('pic_pemohon')->references('id')->on('users');
            $table->foreign('status')->references('id')->on('work_permit_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('work_permits');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
