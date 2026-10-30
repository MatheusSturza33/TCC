<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('periodos_plano', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('plano_alimentar_id')->constrained('planos_alimentares')->cascadeOnDelete();
            $table->unsignedTinyInteger('dia_inicio');
            $table->unsignedTinyInteger('dia_fim');
            $table->decimal('quantidade_kg_dia', 8, 3);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('periodos_plano');
    }
};
