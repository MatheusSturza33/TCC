<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Raca extends Model
{
    use HasFactory;

    protected $table = 'racas';

    public $timestamps = false;

    protected $fillable = [
        'nome',
        'observacoes',
    ];

    public function animais()
    {
        return $this->belongsToMany(
            Animal::class,
            'animal_raca',
            'raca_id',
            'animal_id'
        )->withPivot('percentual');
    }

    public function animaisRaca()
    {
        return $this->hasMany(AnimalRaca::class, 'raca_id');
    }
}