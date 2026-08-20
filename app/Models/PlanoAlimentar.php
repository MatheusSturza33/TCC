<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PlanoAlimentar extends Model
{
    use HasFactory;

    protected $table = 'planos_alimentares';

    public $timestamps = false;

    protected $fillable = [
        'nome',
        'observacoes',
        'ativo',
        'usuario_id',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function periodos()
    {
        return $this->hasMany(
            PeriodoPlano::class,
            'plano_alimentar_id'
        );
    }

    public function matrizes()
    {
        return $this->hasMany(
            MatrizPlano::class,
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