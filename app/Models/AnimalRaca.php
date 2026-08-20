<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AnimalRaca extends Model
{
    use HasFactory;

    protected $table = 'animal_raca';

    public $timestamps = false;

    protected $fillable = [
        'animal_id',
        'raca_id',
        'percentual',
    ];

    protected $casts = [
        'percentual' => 'decimal:2',
    ];

    public function animal()
    {
        return $this->belongsTo(Animal::class, 'animal_id');
    }

    public function raca()
    {
        return $this->belongsTo(Raca::class, 'raca_id');
    }
}