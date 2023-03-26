<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOffresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offres', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('demande_service_id');
            $table->decimal('prix', 10, 2);
            $table->enum('statut', ['accepte', 'refuse', 'en_attente']);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('demande_service_id')->references('id')->on('demandes_service');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offres');
    }
}
