<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Enxerto extends Model
{
    use HasFactory;

    protected $table = 'enxertos';

    public $timestamps = false;

    protected $fillable = [
        'animal_id',
        'mae_origem_id',
        'mae_adotiva_id',
        'data_enxerto',
        'motivo',
        'usuario_id',
    ];

    protected $casts = [
        'data_enxerto' => 'date',
    ];

    public function animal()
    {
        return $this->belongsTo(
            Animal::class,
            'animal_id'
        );
    }

    public function maeOrigem()
    {
        return $this->belongsTo(
            Animal::class,
            'mae_origem_id'
        );
    }

    public function maeAdotiva()
    {
        return $this->belongsTo(
            Animal::class,
            'mae_adotiva_id'
        );
    }

    public function usuario()
    {
        return $this->belongsTo(
            Usuario::class,
            'usuario_id'
        );
    }
}