<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MatrizPlano extends Model
{
    use HasFactory;

    protected $table = 'matriz_plano';

    public $timestamps = false;

    protected $fillable = [
        'animal_id',
        'plano_alimentar_id',
        'data_inicio',
        'data_fim',
        'usuario_id',
    ];

    protected $casts = [
        'data_inicio' => 'date',
        'data_fim' => 'date',
    ];

    public function animal()
    {
        return $this->belongsTo(
            Animal::class,
            'animal_id'
        );
    }

    public function planoAlimentar()
    {
        return $this->belongsTo(
            PlanoAlimentar::class,
            'plano_alimentar_id'
        );
    }

    public function usuario()
    {
        return $this->belongsTo(
            User::class,
            'usuario_id'
        );
    }
}