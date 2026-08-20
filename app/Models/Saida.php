<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Saida extends Model
{
    use HasFactory;

    protected $table = 'saidas';

    public $timestamps = false;

    protected $fillable = [
        'animal_id',
        'data',
        'motivo',
        'peso_saida_kg',
        'destino_id',
        'usuario_id',
    ];

    protected $casts = [
        'data' => 'date',
        'peso_saida_kg' => 'decimal:2',
    ];

    public function animal()
    {
        return $this->belongsTo(
            Animal::class,
            'animal_id'
        );
    }

    public function destino()
    {
        return $this->belongsTo(
            Destino::class,
            'destino_id'
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