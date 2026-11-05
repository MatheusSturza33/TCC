<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parto extends Model
{
    use HasFactory;

    protected $table = 'partos';

    public $timestamps = false;

    protected $fillable = [
        'reproducao_id',
        'matriz_id',
        'dt_inicio',
        'total_nascidos',
        'tipo',
        'total_vivos',
        'total_natimortos',
        'total_mumificados',
        'situacao',
    ];

    protected $casts = [
        'dt_inicio' => 'datetime',
        'total_nascidos' => 'integer',
        'total_vivos' => 'integer',
        'total_natimortos' => 'integer',
        'total_mumificados' => 'integer',
    ];

    public function matriz()
    {
        return $this->belongsTo(Animal::class, 'matriz_id');
    }

    public function animais()
    {
        return $this->belongsToMany(
            Animal::class,
            'animal_parto',
            'parto_id',
            'animal_id'
        )->withPivot(['hora_nascimento', 'peso_kg', 'situacao_ao_nascer']);
    }
}
