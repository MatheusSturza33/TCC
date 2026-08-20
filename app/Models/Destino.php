<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Destino extends Model
{
    use HasFactory;

    protected $table = 'destinos';

    public $timestamps = false;

    protected $fillable = [
        'nome',
        'tipo',
    ];

    public function saidas()
    {
        return $this->hasMany(Saida::class, 'destino_id');
    }
}