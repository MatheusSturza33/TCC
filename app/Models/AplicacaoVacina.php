<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AplicacaoVacina extends Model
{
    use HasFactory;

    protected $table = 'aplicacoes_vacina';

    public $timestamps = false;

    protected $fillable = [
        'vacina_id',
        'animal_id',
        'data_aplicacao',
        'proxima_dose',
        'lote_vacina',
        'responsavel_id',
    ];

    protected $casts = [
        'data_aplicacao' => 'date',
        'proxima_dose' => 'date',
    ];

    public function vacina()
    {
        return $this->belongsTo(
            Vacina::class,
            'vacina_id'
        );
    }

    public function animal()
    {
        return $this->belongsTo(
            Animal::class,
            'animal_id'
        );
    }

    public function responsavel()
    {
        return $this->belongsTo(
            Usuario::class,
            'responsavel_id'
        );
    }
}