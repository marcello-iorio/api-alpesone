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
            
             'id'          => $this->resource->id,          // Este é o ID do nosso banco (ex: 1, 2, 3...). É o que se usa na URL da API.
            'original_id' => $this->resource->original_id, // Este é o ID que veio da fonte de dados original (ex: 1001, 1002...).
            'price' => (float) $this->resource->price,
            'new' => $this->resource->new,
            'year_build' => $this->resource->year_build,
            'year_model' => $this->resource->year_model,
            'km' => $this->resource->km,
            'board' => $this->resource->board,
            'description' => $this->resource->description,
            'photos' => $this->resource->photos,
            'optionals' => $this->resource->optionals,
            'created_at' => $this->resource->created_at->toIso8601String(),

            // Dados dos relacionamentos (só aparecem se forem carregados no controller)
            'brand' => new BrandResource($this->whenLoaded('brand')),
            'model' => new CarModelResource($this->whenLoaded('carModel')),
            'version' => new VersionResource($this->whenLoaded('version')),
            'color' => new ColorResource($this->whenLoaded('color')),
            'fuel' => new FuelResource($this->whenLoaded('fuel')),
            'transmission' => new TransmissionResource($this->whenLoaded('transmission')),
            'unit' => new UnitResource($this->whenLoaded('unit')),
        ];
    }
}