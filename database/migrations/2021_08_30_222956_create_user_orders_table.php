<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            //Referência do usuário (saber qual é o cliente que é dono do pedido)
            $table->unsignedBigInteger('user_id');
            //Exibir os pedidos da loja
            $table->unsignedBigInteger('store_id');
            //Referência do pedido (que manda pro pagseguro)
            $table->string('reference');
            //Código da transação no pagseguro
            $table->string('pagseguro_code');
            //Status da transação
            $table->integer('pagseguro_status');
            //Serializar os itens que estão na sessão do pedido para ter uma visualização rápida no pedido do usuário
            $table->text('items');

            //Data de criação e atualização
            $table->timestamps();
            //Chaves estrangeiras referenciando users e stores no database
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('user_orders');
    }
}
