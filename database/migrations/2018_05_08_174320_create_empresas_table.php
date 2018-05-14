<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmpresasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('logo', 1000)->nullable();
            $table->string('cnpj')->nullable();
            $table->string('razaoSocial')->nullable();
            $table->string('nomeFantasia')->nullable();
            $table->string('cep')->nullable();
            $table->string('endereco')->nullable();
            $table->string('complemento')->nullable() ;
            $table->string('numero')->nullable();
            $table->string('estado')->nullable();
            $table->string('cidade')->nullable();
            $table->boolean('padrao')->default(false);
            $table->string('cor')->nullable();
            $table->string('menu')->nullable();
            $table->unsignedInteger('contatoid');
            $table->foreign('contatoid')->references('id')->on('contatos');
            $table->boolean('ativo')->nullable($value = true);
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
        Schema::dropIfExists('empresas');
    }
}
