<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Movimentacao extends Model
{
    use HasFactory;

    protected $table = 'movimentacoes';

    public $timestamps = false;

    protected $fillable = [
        'animal_id',
        'localizacao_destino_id',
        'data',
        'evento_origem',
    ];

    protected $casts = [
        'data' => 'date',
    ];

    public function animal()
    {
        return $this->belongsTo(Animal::class, 'animal_id');
    }

    public function localizacaoDestino()
    {
        return $this->belongsTo(
            Localizacao::class,
            'localizacao_destino_id'
        );
    }
}