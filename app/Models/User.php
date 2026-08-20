<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'cpf',
        'matricula',
        'perfil',
        'situacao',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function gruposMonta()
    {
        return $this->hasMany(GrupoMonta::class, 'usuario_id');
    }

    public function reproducoes()
    {
        return $this->hasMany(Reproducao::class, 'usuario_id');
    }

    public function enxertos()
    {
        return $this->hasMany(Enxerto::class, 'usuario_id');
    }

    public function desmames()
    {
        return $this->hasMany(Desmame::class, 'usuario_id');
    }

    public function pesagens()
    {
        return $this->hasMany(Pesagem::class, 'usuario_id');
    }

    public function saidas()
    {
        return $this->hasMany(Saida::class, 'usuario_id');
    }

    public function aplicacoesVacina()
    {
        return $this->hasMany(AplicacaoVacina::class, 'responsavel_id');
    }

    public function alertas()
    {
        return $this->hasMany(Alerta::class, 'usuario_id');
    }

    public function planosAlimentares()
    {
        return $this->hasMany(PlanoAlimentar::class, 'usuario_id');
    }

    public function matrizesPlano()
    {
        return $this->hasMany(MatrizPlano::class, 'usuario_id');
    }
}