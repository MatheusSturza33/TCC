<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesagens', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('animal_id')->constrained('animais')->cascadeOnDelete();
            $table->enum('fase', ['maternidade', 'creche', 'terminacao', 'reproducao', 'outro']);
            $table->date('data');
            $table->decimal('peso_kg', 8, 3);
            $table->decimal('gp_kg', 8, 3)->nullable()->comment('Calculado a partir de pesagens anteriores');
            $table->decimal('gmd_kg', 8, 3)->nullable()->comment('Ganho médio diário calculado');
            $table->foreignId('usuario_id')->nullable()->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesagens');
    }
};
