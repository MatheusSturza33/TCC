<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saidas', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('animal_id')->constrained('animais')->restrictOnDelete();
            $table->date('data');
            $table->enum('motivo', ['abate', 'venda', 'morte', 'transferencia', 'outro']);
            $table->decimal('peso_saida_kg', 8, 3)->nullable();
            $table->foreignId('destino_id')->nullable()->constrained('destinos')->nullOnDelete();
            $table->foreignId('usuario_id')->nullable()->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saidas');
    }
};
