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
            $table->unsignedInteger('acaoid');
            $table->foreign('acaoid')->references('id')->on('acaos');
            $table->boolean('ver')->nullable($value = true);
            $table->boolean('criar')->nullable($value = true);
            $table->boolean('editar')->nullable($value = true);
            $table->boolean('apagar')->nullable($value = true);
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
