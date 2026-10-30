<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('localizacoes', function (Blueprint $table): void {
            $table->id();
            $table->string('nome');
            $table->enum('tipo', ['maternidade', 'gestacao', 'creche', 'terminacao', 'quarentena', 'outro']);
            $table->unsignedInteger('capacidade')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('localizacoes');
    }
};
