<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContaItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conta_items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome')->nullable();
            $table->string('CPF')->nullable();
            $table->unsignedInteger('bancoid')->nullable();;
            $table->foreign('bancoid')->references('id')->on('bancos');
            $table->unsignedInteger('contaid');
            $table->foreign('contaid')->references('id')->on('contatos');
            $table->string('agencia')->nullable();
            $table->string('tipo')->nullable();
            $table->string('conta')->nullable();
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
        Schema::dropIfExists('conta_items');
    }
}
