<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('animal_raca', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('animal_id')->constrained('animais')->cascadeOnDelete();
            $table->foreignId('raca_id')->constrained('racas')->cascadeOnDelete();
            $table->decimal('percentual', 5, 2)->unsigned();
            $table->unique(['animal_id', 'raca_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('animal_raca');
    }
};
