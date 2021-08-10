<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableStoresAddColumnLogo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Para alterar uma table
        //Passa o nome da tabela que deseja alterar (stores) e segundo parâmetro função anônima

        Schema::table('stores', function(Blueprint $table){
            //Não obrigatório upload de logo
            $table -> string('logo')-> nullable;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stores', function(Blueprint $table){
            $table->dropColumn('logo');
        });
    }
}
