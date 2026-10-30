<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matriz_plano', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('animal_id')->constrained('animais')->cascadeOnDelete();
            $table->foreignId('plano_alimentar_id')->constrained('planos_alimentares')->cascadeOnDelete();
            $table->date('data_inicio');
            $table->date('data_fim')->nullable();
            $table->foreignId('usuario_id')->nullable()->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matriz_plano');
    }
};
