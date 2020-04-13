<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApprovalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approvals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('work_permit_id');
            $table->integer('user_id');
            $table->integer('user_status');
            $table->unsignedBigInteger('status')->unsigned();
            $table->timestamps();

            $table->foreign('work_permit_id')->references('id')->on('work_permits');
            $table->foreign('status')->references('id')->on('approval_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('approvals');
    }
}
