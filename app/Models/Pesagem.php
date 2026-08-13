<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pesagem extends Model
{
    use HasFactory;

    protected $table = 'pesagens';

    public $timestamps = false;

    protected $fillable = [
        'animal_id',
        'fase',
        'data',
        'peso_kg',
        'gp_kg',
        'gmd_kg',
        'usuario_id',
    ];

    protected $casts = [
        'data' => 'date',
        'peso_kg' => 'decimal:2',
        'gp_kg' => 'decimal:2',
        'gmd_kg' => 'decimal:2',
    ];

    public function animal()
    {
        return $this->belongsTo(
            Animal::class,
            'animal_id'
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