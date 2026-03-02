<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mensagens', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('conteudo');
            $table->string('tipo', 20)->default('info');
            $table->boolean('ativo')->default(true);
            $table->timestamp('inicio_exibicao')->nullable();
            $table->timestamp('fim_exibicao')->nullable();
            $table->unsignedInteger('prioridade')->default(0);
            $table->string('sistema')->default('geral');
            $table->json('publico')->nullable();
            $table->timestamps();

            $table->index(['ativo', 'inicio_exibicao', 'fim_exibicao']);
            $table->index(['sistema', 'prioridade']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mensagens');
    }
};
