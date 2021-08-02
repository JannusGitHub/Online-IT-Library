<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkloadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workloads', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('work_instruction_title');
            $table->string('description');
            $table->tinyInteger('status')->comment = '1-done,2-pending';
            $table->string('file')->nullable();
            $table->string('uploaded_date');
            $table->bigInteger('created_by')->unsigned();
            $table->bigInteger('last_updated_by')->unsigned();
            $table->bigInteger('logdel')->nullable()->default(0)->comment = '0-active, 1-deleted';
            $table->timestamps();

            // Foreign Key
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('last_updated_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('workloads');
    }
}
