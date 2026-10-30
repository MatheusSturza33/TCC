<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aplicacoes_vacina', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('vacina_id')->constrained('vacinas')->restrictOnDelete();
            $table->foreignId('animal_id')->constrained('animais')->cascadeOnDelete();
            $table->date('data_aplicacao');
            $table->date('proxima_dose')->nullable()->comment('Calculada a partir do intervalo de reforço da vacina');
            $table->string('lote_vacina')->nullable();
            $table->foreignId('responsavel_id')->nullable()->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aplicacoes_vacina');
    }
};
