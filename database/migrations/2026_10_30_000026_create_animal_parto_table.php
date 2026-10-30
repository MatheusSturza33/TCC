<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('animal_parto', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('animal_id')->constrained('animais')->cascadeOnDelete();
            $table->foreignId('parto_id')->constrained('partos')->cascadeOnDelete();
            $table->time('hora_nascimento')->nullable();
            $table->enum('situacao_ao_nascer', ['vivo', 'natimorto', 'mumificado'])->default('vivo');
            $table->unique(['animal_id', 'parto_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('animal_parto');
    }
};
