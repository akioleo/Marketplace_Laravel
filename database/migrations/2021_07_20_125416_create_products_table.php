<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            //auto-increment apontando para store_id
            $table->unsignedBigInteger('store_id');
            //nome do produto
            $table->string('name');
            //descrição do produto(exibida na home)
            $table->string('description');
            //conteúdo do produto
            $table->text('body');
            //preço. Tamanho 10 e precisão 2 (depois do ponto flutuante)
            $table->decimal('price', 10, 2);
            //url
            $table->string('slug');

            $table->timestamps();
            //chave estrangeira (store_id) referenciando id na tabela stores
            $table->foreign('store_id')->references('id')->on('stores');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
