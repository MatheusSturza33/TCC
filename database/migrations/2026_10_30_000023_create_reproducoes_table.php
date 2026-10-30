<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reproducoes', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('femea_id')->constrained('animais')->restrictOnDelete();
            $table->foreignId('grupo_monta_id')->nullable()->constrained('grupos_monta')->nullOnDelete();
            $table->date('data_primeiro_servico');
            $table->date('previsao_parto')->nullable()->comment('Calculada conforme a data do primeiro serviço');
            $table->enum('situacao', ['aberta', 'confirmada', 'parto', 'cancelada'])->default('aberta');
            $table->foreignId('reprodutor_id')->nullable()->constrained('animais')->nullOnDelete();
            $table->foreignId('usuario_id')->nullable()->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reproducoes');
    }
};
