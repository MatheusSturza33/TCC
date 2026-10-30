<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grupos_monta', function (Blueprint $table): void {
            $table->id();
            $table->string('identificacao')->unique();
            $table->string('semana_referencia');
            $table->enum('situacao', ['programado', 'em_servico', 'encerrado', 'cancelado'])->default('programado');
            $table->date('data_fim')->nullable();
            $table->foreignId('usuario_id')->nullable()->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grupos_monta');
    }
};
