<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdutosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            $table->integer('codigo')->unique();
            $table->string('categoria', 50);
            $table->string('nome', 50);
            $table->decimal('preco', $precision= 8, $scale=2);
            $table->string('composicao', 50);
            $table->date('data_cadastro');
            $table->string('tamanho');
            $table->integer('quantidade_produto');
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
        Schema::dropIfExists('produtos');
    }
}
