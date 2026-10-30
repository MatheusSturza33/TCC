<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enxertos', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('animal_id')->constrained('animais')->cascadeOnDelete();
            $table->foreignId('mae_origem_id')->constrained('animais')->restrictOnDelete();
            $table->foreignId('mae_adotiva_id')->constrained('animais')->restrictOnDelete();
            $table->date('data_enxerto');
            $table->string('motivo')->nullable();
            $table->foreignId('usuario_id')->nullable()->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enxertos');
    }
};
