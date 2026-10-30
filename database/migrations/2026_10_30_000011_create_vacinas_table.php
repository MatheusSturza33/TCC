<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vacinas', function (Blueprint $table): void {
            $table->id();
            $table->string('nome');
            $table->enum('categoria_animal', ['matriz', 'reprodutor', 'leitao', 'todos']);
            $table->string('fase_aplicacao')->nullable();
            $table->unsignedInteger('intervalo_reforco')->default(0)->comment('Intervalo de reforço em dias');
            $table->boolean('ativa')->default(true);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vacinas');
    }
};
