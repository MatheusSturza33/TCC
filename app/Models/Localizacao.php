<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Localizacao extends Model
{
    use HasFactory;

    protected $table = 'localizacoes';

    public $timestamps = false;

    protected $fillable = [
        'nome',
        'tipo',
        'capacidade',
    ];

    protected $casts = [
        'capacidade' => 'integer',
    ];

    public function animais()
    {
        return $this->hasMany(
            Animal::class,
            'id_localizacao_atual'
        );
    }

    public function movimentacoesDestino()
    {
        return $this->hasMany(
            Movimentacao::class,
            'localizacao_destino_id'
        );
    }
}