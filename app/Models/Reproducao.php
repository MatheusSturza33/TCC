<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reproducao extends Model
{
    use HasFactory;

    protected $table = 'reproducoes';

    public $timestamps = false;

    protected $fillable = [
        'femea_id',
        'grupo_monta_id',
        'data_primeiro_servico',
        'previsao_parto',
        'situacao',
        'reprodutor_id',
        'usuario_id',
    ];

    protected $casts = [
        'data_primeiro_servico' => 'date',
        'previsao_parto' => 'date',
    ];

    public function femea()
    {
        return $this->belongsTo(Animal::class, 'femea_id');
    }

    public function grupoMonta()
    {
        return $this->belongsTo(
            GrupoMonta::class,
            'grupo_monta_id'
        );
    }

    public function servicosReprodutivos()
    {
        return $this->hasMany(
            ServicoReprodutivo::class,
            'reproducao_id'
        );
    }

    public function partos()
    {
        return $this->hasMany(
            Parto::class,
            'reproducao_id'
        );
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function reprodutor()
{
    return $this->belongsTo(
        Animal::class,
        'reprodutor_id'
    );
}
}