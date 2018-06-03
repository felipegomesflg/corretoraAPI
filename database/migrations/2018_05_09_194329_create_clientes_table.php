<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome')->nullable(); 
            $table->string('cpf_cnpj')->nullable();
            $table->string('rg')->nullable();
            $table->string('rg_orgao')->nullable();
            $table->string('rg_data')->nullable();
            $table->string('sexo')->nullable();
            $table->string('profissao')->nullable();
            $table->string('mae')->nullable();
            $table->string('pai')->nullable();
            $table->string('estadoCivil')->nullable();
            $table->string('observacao')->nullable();
            $table->string('nascimento')->nullable();
            $table->boolean('ativo')->nullable($value = true);
            $table->unsignedInteger('contatoid')->nullable();;
            $table->foreign('contatoid')->references('id')->on('contatos');
            $table->unsignedInteger('enderecoid');
            $table->foreign('enderecoid')->references('id')->on('enderecos');
            $table->unsignedInteger('contaid');
            $table->foreign('contaid')->references('id')->on('contas');
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
        Schema::dropIfExists('clientes');
    }
}
