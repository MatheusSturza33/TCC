<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('servicos_reprodutivos', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('reproducao_id')->constrained('reproducoes')->cascadeOnDelete();
            $table->enum('tipo', ['natural', 'inseminacao_artificial', 'monta']);
            $table->date('data');
            $table->foreignId('reprodutor_id')->nullable()->constrained('animais')->nullOnDelete();
            $table->unsignedInteger('numero_tentativa')->default(1);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servicos_reprodutivos');
    }
};
