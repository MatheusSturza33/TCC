<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PeriodoPlano extends Model
{
    use HasFactory;

    protected $table = 'periodos_plano';

    public $timestamps = false;

    protected $fillable = [
        'plano_alimentar_id',
        'dia_inicio',
        'dia_fim',
        'quantidade_kg_dia',
    ];

    protected $casts = [
        'dia_inicio' => 'integer',
        'dia_fim' => 'integer',
        'quantidade_kg_dia' => 'decimal:2',
    ];

    public function planoAlimentar()
    {
        return $this->belongsTo(
            PlanoAlimentar::class,
            'plano_alimentar_id'
        );
    }
}