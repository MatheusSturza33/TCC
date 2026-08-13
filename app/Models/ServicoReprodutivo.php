<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServicoReprodutivo extends Model
{
    use HasFactory;

    protected $table = 'servicos_reprodutivos';

    public $timestamps = false;

    protected $fillable = [
        'reproducao_id',
        'tipo',
        'data',
        'reprodutor_id',
        'numero_tentativa',
    ];

    protected $casts = [
        'data' => 'date',
        'numero_tentativa' => 'integer',
    ];

    public function reproducao()
    {
        return $this->belongsTo(
            Reproducao::class,
            'reproducao_id'
        );
    }

    public function reprodutor()
    {
        return $this->belongsTo(
            Animal::class,
            'reprodutor_id'
        );
    }
}