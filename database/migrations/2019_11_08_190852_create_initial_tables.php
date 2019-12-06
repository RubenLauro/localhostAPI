<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInitialTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('places', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('image_url')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->float('average_rating', 2, 1);
            $table->float('latitude', 8, 6);
            $table->float('longitude', 8, 6);
            $table->integer('qt_reviews', false, true);
            $table->string('provider');
            //------------------
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('reviews', function (Blueprint $table) {
            $table->increments('id');
            $table->string('text')->nullable();
            $table->float('rating', 2, 1);
            $table->string('user_name');
            $table->string('user_image')->nullable();
            //Place relationship
            $table->unsignedInteger('place_id');
            $table->foreign('place_id')->references('id')->on('places');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('place_types', function (Blueprint $table) {
            $table->unsignedInteger('type_id');
            $table->foreign('type_id')->references('id')->on('types');
            $table->unsignedInteger('place_id');
            $table->foreign('place_id')->references('id')->on('places');
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
        Schema::dropIfExists('types');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('places');
    }
}
