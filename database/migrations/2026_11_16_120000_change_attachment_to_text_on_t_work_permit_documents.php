<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangeAttachmentToTextOnTWorkPermitDocuments extends Migration
{
    /**
     * Isian teks (Jumlah Personil, Alat Berat, Jumlah Trucking, dll.)
     * sebelumnya varchar(100) sehingga insert > 100 karakter gagal
     * (QueryException 1406) dan membuat baris approval PEMOHON tidak
     * pernah dibuat.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('t_work_permit_documents') && Schema::hasColumn('t_work_permit_documents', 'attachment')) {
            DB::statement('ALTER TABLE `t_work_permit_documents` MODIFY `attachment` TEXT NULL');
        }
    }

    /**
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('t_work_permit_documents') && Schema::hasColumn('t_work_permit_documents', 'attachment')) {
            DB::statement('ALTER TABLE `t_work_permit_documents` MODIFY `attachment` VARCHAR(100) NULL');
        }
    }
}
