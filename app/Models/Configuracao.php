<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Configuracao extends Model
{
    use HasFactory;

    protected $table = 'configuracoes';

    public $timestamps = false;

    protected $fillable = [
        'chave',
        'valor',
    ];
}