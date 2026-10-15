<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['matricula' => 'ADMIN001'],
            [
                'name' => 'Administrador de Teste',
                'email' => 'admin.teste@granja.local',
                'perfil' => 'administrador',
                'situacao' => 'ativo',
                'password' => Hash::make('password'),
            ]
        );
    }
}
