<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AnimalParto extends Model
{
    use HasFactory;

    protected $table = 'animal_parto';

    public $timestamps = false;

    protected $fillable = [
        'animal_id',
        'parto_id',
    ];

    public function animal()
    {
        return $this->belongsTo(
            Animal::class,
            'animal_id'
        );
    }

    public function parto()
    {
        return $this->belongsTo(
            Parto::class,
            'parto_id'
        );
    }
}