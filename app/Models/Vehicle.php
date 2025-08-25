<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

     /**
     * Informa ao Laravel que a chave primária não é auto-incrementável.
     * @var bool
     */
    public $incrementing = false;

    /**
     * Opcional, mas boa prática: informa ao Laravel o tipo da chave.
     * @var string
     */
    protected $keyType = 'integer';

    protected $fillable = [
        'id',
        'brand',
        'model',
        'version',
        'color',
        'fuel',
        'transmission',
        'category',
        'sold',
        'year_build',
        'year_model',
        'km',
        'board',
        'doors',
        'price',
        'description',
        'photos',
        'optionals',
    ];

    protected $casts = [
        'photos' => 'array',
        'optionals' => 'array',
        'sold' => 'boolean',
    ];
}