<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enxertos', function (Blueprint $table): void {
            $table->foreignId('parto_origem_id')->nullable()->after('mae_adotiva_id')->constrained('partos')->nullOnDelete();
            $table->foreignId('parto_adotivo_id')->nullable()->after('parto_origem_id')->constrained('partos')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('enxertos', function (Blueprint $table): void {
            $table->dropForeign(['parto_origem_id']);
            $table->dropForeign(['parto_adotivo_id']);
            $table->dropColumn(['parto_origem_id', 'parto_adotivo_id']);
        });
    }
};
