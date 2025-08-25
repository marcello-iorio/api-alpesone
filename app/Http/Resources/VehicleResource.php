<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VehicleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'brand' => $this->resource->brand,
            'model' => $this->resource->model,
            'version' => $this->resource->version,
            'color' => $this->resource->color,
            'fuel' => $this->resource->fuel,
            'transmission' => $this->resource->transmission,
            'category' => $this->resource->category,
            'sold' => $this->resource->sold,
            'year_build' => $this->resource->year_build,
            'year_model' => $this->resource->year_model,
            'km' => $this->resource->km,
            'price' => (float) $this->resource->price,
            'description' => $this->resource->description,
            'photos' => $this->resource->photos,
            'optionals' => $this->resource->optionals,
            'created_at' => $this->resource->created_at->toIso8601String()
        ];
    }
}