<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Alerta extends Model
{
    use HasFactory;

    protected $table = 'alertas';

    public $timestamps = false;

    protected $fillable = [
        'tipo',
        'referencia_id',
        'data_prevista',
        'situacao',
        'usuario_id',
    ];

    protected $casts = [
        'data_prevista' => 'date',
        'referencia_id' => 'integer',
    ];

    public function usuario()
    {
        return $this->belongsTo(
            User::class,
            'usuario_id'
        );
    }
}