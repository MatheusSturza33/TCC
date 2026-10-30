<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('partos', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('reproducao_id')->constrained('reproducoes')->restrictOnDelete();
            $table->foreignId('matriz_id')->constrained('animais')->restrictOnDelete();
            $table->dateTime('dt_inicio');
            $table->enum('tipo', ['normal', 'cesariana', 'aborto']);
            $table->unsignedInteger('total_vivos')->default(0);
            $table->unsignedInteger('total_natimortos')->default(0);
            $table->unsignedInteger('total_mumificados')->default(0);
            $table->enum('situacao', ['previsto', 'realizado', 'cancelado'])->default('previsto');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partos');
    }
};
