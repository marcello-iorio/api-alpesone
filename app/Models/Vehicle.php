<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vehicle extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'original_id',
        'brand_id',
        'car_model_id',
        'version_id',
        'color_id',
        'fuel_id',
        'transmission_id',
        'unit_id',
        'status',
        'year_build',
        'year_model',
        'new',
        'km',
        'board',
        'doors',
        'price',
        'description',
        'photos',
        'optionals',
        'portals',
        'published_portals',
        'stamps',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'new' => 'boolean',
        'price' => 'decimal:2',
        'photos' => 'array',
        'optionals' => 'array',
        'portals' => 'array',
        'published_portals' => 'array',
        'stamps' => 'array',
    ];

    // RELACIONAMENTOS (Um veículo pertence a...)

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function carModel(): BelongsTo
    {
        return $this->belongsTo(CarModel::class);
    }

    public function version(): BelongsTo
    {
        return $this->belongsTo(Version::class);
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class);
    }

    public function fuel(): BelongsTo
    {
        return $this->belongsTo(Fuel::class);
    }

    public function transmission(): BelongsTo
    {
        return $this->belongsTo(Transmission::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }
}