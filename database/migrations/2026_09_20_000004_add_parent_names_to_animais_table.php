<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('animais', function (Blueprint $table): void {
            $table->string('pai_nome')->nullable()->after('data_nascimento');
            $table->string('mae_nome')->nullable()->after('pai_nome');
        });
    }

    public function down(): void
    {
        Schema::table('animais', function (Blueprint $table): void {
            $table->dropColumn(['pai_nome', 'mae_nome']);
        });
    }
};
