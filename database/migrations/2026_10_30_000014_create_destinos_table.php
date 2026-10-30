<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('destinos', function (Blueprint $table): void {
            $table->id();
            $table->string('nome');
            $table->enum('tipo', ['abate', 'venda', 'morte', 'transferencia', 'outro']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('destinos');
    }
};
