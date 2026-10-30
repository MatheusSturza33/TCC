<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movimentacao extends Model
{
    use HasFactory;

    protected $table = 'movimentacoes';

    public $timestamps = false;

    protected $fillable = [
        'animal_id',
        'localizacao_destino_id',
        'data',
        'tipo_movimentacao',
        'lote_identificacao',
        'evento_origem',
        'usuario_id',
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
        return $this->belongsTo(Localizacao::class, 'localizacao_destino_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
