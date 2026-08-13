<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GrupoMonta extends Model
{
    use HasFactory;

    protected $table = 'grupos_monta';

    public $timestamps = false;

    protected $fillable = [
        'identificacao',
        'semana_referencia',
        'situacao',
        'data_fim',
        'usuario_id',
    ];

    protected $casts = [
        'data_fim' => 'date',
    ];

    public function femeas()
    {
        return $this->hasMany(Reproducao::class, 'grupo_monta_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}