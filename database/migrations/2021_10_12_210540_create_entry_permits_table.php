<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntryPermitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entry_permits', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('status'); //source work_permit_status
            $table->json('docs');
            $table->timestamps();
            $table->integer('approver')->nullable(); //source db_efile.users
            $table->integer('approver_status')->nullable();; //source work_permit_status juga
            $table->string('remark')->nullable();;
            $table->timestamp('approver_updated_at')->nullable();;
            $table->string('subject', 200)->nullable();
            $table->string('message', 500)->nullable();
            $table->string('number', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entry_permits');
    }
}
