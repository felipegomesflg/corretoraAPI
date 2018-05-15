<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome')->nullable();
            $table->string('usuario')->nullable()->unique();
            $table->string('senha')->nullable();
            $table->string('foto',1000)->nullable();
            $table->string('cpf')->nullable();
            $table->string('cor')->nullable();
            $table->string('menu')->nullable();
            $table->string('api_token')->unique();
            $table->boolean('ativo')->nullable($value = true);
            $table->unsignedInteger('contatoid');
            $table->foreign('contatoid')->references('id')->on('contatos');
            $table->unsignedInteger('tipoid');
            $table->foreign('tipoid')->references('id')->on('tipos');
            $table->unsignedInteger('empresaid');
            $table->foreign('empresaid')->references('id')->on('empresas');
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
        Schema::dropIfExists('usuarios');
    }
}
