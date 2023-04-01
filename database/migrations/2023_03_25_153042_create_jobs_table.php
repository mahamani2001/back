<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {  if (!Schema::hasTable('jobs')) {
            Schema::create('jobs', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('description');
                $table->decimal('price_max');
                $table-> decimal('price_min');
                $table->boolean('availability')->default(true);
                $table->string('pictureUrl');
               $table->unsignedBigInteger('category_id')->nullable(); // add new foreign key column
                $table->foreign('category_id')->references('id')->on('categories');
                $table->timestamps();    
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobs');
    }
}
