<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Animal extends Model
{
    use HasFactory;

    protected $table = 'animais';

    public $timestamps = false;

    protected $fillable = [
        'tatuagem',
        'mossa',
        'brinco',
        'chip',
        'sexo',
        'tipo',
        'situacao',
        'estado_reprodutivo',
        'data_nascimento',
        'pai_nome',
        'mae_nome',
        'id_pai',
        'id_mae',
        'id_localizacao_atual',
    ];

    protected $casts = [
        'data_nascimento' => 'date',
    ];

    public function pai()
    {
        return $this->belongsTo(Animal::class, 'id_pai');
    }

    public function filhosComoPai()
    {
        return $this->hasMany(Animal::class, 'id_pai');
    }

    public function mae()
    {
        return $this->belongsTo(Animal::class, 'id_mae');
    }

    public function filhosComoMae()
    {
        return $this->hasMany(Animal::class, 'id_mae');
    }

    public function localizacaoAtual()
    {
        return $this->belongsTo(
            Localizacao::class,
            'id_localizacao_atual'
        );
    }

    public function racas()
    {
        return $this->belongsToMany(
            Raca::class,
            'animal_raca',
            'animal_id',
            'raca_id'
        )->withPivot('percentual');
    }

    public function movimentacoes()
    {
        return $this->hasMany(Movimentacao::class, 'animal_id');
    }

    public function pesagens()
    {
        return $this->hasMany(Pesagem::class, 'animal_id');
    }

    public function saidas()
    {
        return $this->hasMany(Saida::class, 'animal_id');
    }

    public function aplicacoesVacina()
    {
        return $this->hasMany(AplicacaoVacina::class, 'animal_id');
    }

    public function reproducoesComoFemea()
    {
        return $this->hasMany(Reproducao::class, 'femea_id');
    }

    public function reproducoesComoReprodutor()
    {
        return $this->hasMany(Reproducao::class, 'reprodutor_id');
    }

    public function partosComoMatriz()
    {
        return $this->hasMany(Parto::class, 'matriz_id');
    }

    public function partos()
    {
        return $this->belongsToMany(
            Parto::class,
            'animal_parto',
            'animal_id',
            'parto_id'
        );
    }

    public function enxertosComoLeitao()
    {
        return $this->hasMany(Enxerto::class, 'animal_id');
    }

    public function enxertosComoMaeOrigem()
    {
        return $this->hasMany(Enxerto::class, 'mae_origem_id');
    }

    public function enxertosComoMaeAdotiva()
    {
        return $this->hasMany(Enxerto::class, 'mae_adotiva_id');
    }

    public function desmames()
    {
        return $this->hasMany(Desmame::class, 'matriz_id');
    }

    public function matrizesPlano()
    {
        return $this->hasMany(MatrizPlano::class, 'animal_id');
    }
}
