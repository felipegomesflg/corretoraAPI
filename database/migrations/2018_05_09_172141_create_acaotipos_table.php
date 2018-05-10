<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcaotiposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acao_tipos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('tipoid');
            $table->foreign('tipoid')->references('id')->on('tipos');
            $table->unsignedInteger('tipoacao');
            $table->foreign('tipoacao')->references('id')->on('acaos');
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
        Schema::dropIfExists('acaotipos');
    }
}
