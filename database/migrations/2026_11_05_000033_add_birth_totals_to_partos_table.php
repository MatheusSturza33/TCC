<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('partos', function (Blueprint $table): void {
            $table->unsignedInteger('total_nascidos')->default(0)->after('dt_inicio');
        });

        Schema::table('animal_parto', function (Blueprint $table): void {
            $table->decimal('peso_kg', 8, 3)->nullable()->after('hora_nascimento');
        });
    }

    public function down(): void
    {
        Schema::table('animal_parto', function (Blueprint $table): void {
            $table->dropColumn('peso_kg');
        });

        Schema::table('partos', function (Blueprint $table): void {
            $table->dropColumn('total_nascidos');
        });
    }
};
