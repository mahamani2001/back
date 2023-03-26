<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicejobbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servicejobbers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jobber_id');
            $table->unsignedBigInteger('job_id');
            $table->float('price');
            $table->timestamps();
            $table->foreign('jobber_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('servicejobbers');
    }
}
