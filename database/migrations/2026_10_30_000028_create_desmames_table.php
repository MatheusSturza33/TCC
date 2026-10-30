<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('desmames', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('matriz_id')->constrained('animais')->restrictOnDelete();
            $table->date('data_realizacao');
            $table->unsignedInteger('total_leitoes_desmamados');
            $table->decimal('peso_medio_desmame_kg', 8, 3)->nullable();
            $table->foreignId('usuario_id')->nullable()->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('desmames');
    }
};
