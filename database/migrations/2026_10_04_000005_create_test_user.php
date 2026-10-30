<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('users')) {
            return;
        }

        $now = now();

        DB::table('users')->updateOrInsert(
            ['matricula' => 'ADMIN001'],
            [
                'name' => 'Administrador de Teste',
                'email' => 'admin.teste@granja.local',
                'email_verified_at' => $now,
                'cpf' => null,
                'perfil' => 'administrador',
                'situacao' => 'ativo',
                'password' => Hash::make('password'),
                'remember_token' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('users')) {
            DB::table('users')->where('matricula', 'ADMIN001')->delete();
        }
    }
};
