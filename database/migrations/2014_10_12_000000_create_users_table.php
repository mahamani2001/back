<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); 
            $table->string('role'); 
            $table->boolean('is_client')->default(false);
            $table->boolean('is_prestataire')->default(false);
            $table->json('profil')->nullable();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('address');
            $table->string('password');
            $table->string('phone')->length(100);
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('photo')->nullable();
            $table->string('competence')->nullable();
            $table->string('numero_cin')->nullable();
            $table->string('diplome')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
