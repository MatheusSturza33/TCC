<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Parto extends Model
{
    use HasFactory;

    protected $table = 'partos';

    public $timestamps = false;

    protected $fillable = [
        'reproducao_id',
        'matriz_id',
        'dt_inicio',
        'tipo',
        'total_vivos',
        'total_natimortos',
        'total_mumificados',
        'situacao',
    ];

    protected $casts = [
        'dt_inicio' => 'datetime',
        'total_vivos' => 'integer',
        'total_natimortos' => 'integer',
        'total_mumificados' => 'integer',
    ];

    public function reproducao()
    {
        return $this->belongsTo(
            Reproducao::class,
            'reproducao_id'
        );
    }

    public function matriz()
    {
        return $this->belongsTo(
            Animal::class,
            'matriz_id'
        );
    }

    public function animais()
    {
        return $this->belongsToMany(
            Animal::class,
            'animal_parto',
            'parto_id',
            'animal_id'
        );
    }

    public function animaisParto()
    {
        return $this->hasMany(
            AnimalParto::class,
            'parto_id'
        );
    }
}