<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnderecoItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('endereco_items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('cep')->nullable();
            $table->string('endereco')->nullable();
            $table->unsignedInteger('enderecoid');
            $table->foreign('enderecoid')->references('id')->on('enderecos');
            $table->string('complemento')->nullable() ;
            $table->string('numero')->nullable();
            $table->string('estado')->nullable();
            $table->string('cidade')->nullable();
            $table->string('bairro')->nullable();
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
        Schema::dropIfExists('endereco_items');
    }
}
