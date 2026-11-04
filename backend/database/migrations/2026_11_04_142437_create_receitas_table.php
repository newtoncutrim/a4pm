<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('receitas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_usuarios');
            $table->unsignedBigInteger('id_categorias')->nullable();
            $table->string('nome', 45)->nullable();
            $table->unsignedInteger('tempo_preparo_minutos')->nullable();
            $table->unsignedInteger('porcoes')->nullable();
            $table->text('modo_preparo');
            $table->text('ingredientes')->nullable();
            $table->dateTime('criado_em');
            $table->dateTime('alterado_em');

            $table->index('id_usuarios', 'idx_receitas_usuario');
            $table->index('id_categorias', 'idx_receitas_categoria');

            $table->foreign('id_usuarios')->references('id')->on('usuarios')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('id_categorias')->references('id')->on('categorias')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('receitas');
    }
};
