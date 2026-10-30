<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movimentacoes', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('animal_id')->constrained('animais')->cascadeOnDelete();
            $table->foreignId('localizacao_destino_id')->constrained('localizacoes')->restrictOnDelete();
            $table->date('data');
            $table->enum('tipo_movimentacao', ['individual', 'lote'])->default('individual');
            $table->string('lote_identificacao')->nullable();
            $table->enum('evento_origem', ['desmame', 'descreche', 'terminacao', 'transferencia', 'outro'])->default('transferencia');
            $table->foreignId('usuario_id')->nullable()->constrained('users')->nullOnDelete();
            $table->index(['lote_identificacao', 'data']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimentacoes');
    }
};
