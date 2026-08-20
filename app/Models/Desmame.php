<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Desmame extends Model
{
    use HasFactory;

    protected $table = 'desmames';

    public $timestamps = false;

    protected $fillable = [
        'matriz_id',
        'data_realizacao',
        'total_leitoes_desmamados',
        'peso_medio_desmame_kg',
        'usuario_id',
    ];

    protected $casts = [
        'data_realizacao' => 'date',
        'total_leitoes_desmamados' => 'integer',
        'peso_medio_desmame_kg' => 'decimal:2',
    ];

    public function matriz()
    {
        return $this->belongsTo(
            Animal::class,
            'matriz_id'
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