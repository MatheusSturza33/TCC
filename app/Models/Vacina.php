<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vacina extends Model
{
    use HasFactory;

    protected $table = 'vacinas';

    public $timestamps = false;

    protected $fillable = [
        'nome',
        'categoria_animal',
        'fase_aplicacao',
        'intervalo_reforco',
        'ativa',
    ];

    protected $casts = [
        'intervalo_reforco' => 'integer',
        'ativa' => 'boolean',
    ];

    public function aplicacoes()
    {
        return $this->hasMany(
            AplicacaoVacina::class,
            'vacina_id'
        );
    }
}