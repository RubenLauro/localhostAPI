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
        Schema::create('categories', function (Blueprint $table){
            $table->increments('id');
            $table->string('name');
            $table->string('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('place_types', function (Blueprint $table){
            $table->increments('id');
            $table->string('name');
            //categories relationship
            $table->integer('category',false,true);
            $table->foreign('category')->references('id')->on('categories');
            //-----------------------
            $table->string('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('regions', function (Blueprint $table){
            $table->increments('id');
            $table->string('name');
            $table->float('latitude', 8,6);
            $table->float('longitude', 8,6);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('providers', function (Blueprint $table){
            $table->increments('id');
            $table->string('name');
            $table->string('url');
            $table->string('logo_url');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('reviews', function (Blueprint $table){
            $table->increments('id');
            //providers relationship
            $table->integer('provider',false,true);
            $table->foreign('provider')->references('id')->on('providers');
            //----------------------
            $table->float('score',2,1);
            //user relationship
            $table->integer('user',false,true);
            $table->foreign('user')->references('id')->on('users');
            //-----------------
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('places', function (Blueprint $table){
            $table->increments('id');
            $table->string('name');
            $table->float('average_rating',2,1);
            //reviews relationship
            $table->integer('reviews',false,true);
            $table->foreign('reviews')->references('id')->on('reviews');
            //--------------------
            $table->float('latitude',8,6);
            $table->float('longitude',8,6);
            //types relationship
            $table->integer('type',false,true);
            $table->foreign('type')->references('id')->on('place_types');
            //------------------
            $table->integer('qt_reviews',false,true);
            //regions relationship
            $table->integer('region',false,true);
            $table->foreign('region')->references('id')->on('regions');
            //--------------------
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
        Schema::dropIfExists('place_types');
        Schema::dropIfExists('regions');
        Schema::dropIfExists('providers');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('places');
    }
}
