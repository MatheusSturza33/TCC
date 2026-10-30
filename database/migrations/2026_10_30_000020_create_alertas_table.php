<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alertas', function (Blueprint $table): void {
            $table->id();
            $table->enum('tipo', ['parto', 'desmame', 'vacina', 'consanguinidade', 'outro']);
            $table->unsignedBigInteger('referencia_id');
            $table->date('data_prevista');
            $table->enum('situacao', ['pendente', 'concluido', 'cancelado'])->default('pendente');
            $table->foreignId('usuario_id')->nullable()->constrained('users')->nullOnDelete();
            $table->index(['tipo', 'referencia_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alertas');
    }
};
