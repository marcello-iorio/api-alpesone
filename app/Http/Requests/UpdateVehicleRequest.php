<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // --- Campos diretos do veículo ---
            'price'         => 'sometimes|required|numeric|min:0',
            'km'            => 'sometimes|required|integer|min:0',
            'year_build'    => 'sometimes|required|digits:4',
            'year_model'    => 'sometimes|required|digits:4',
            'new'           => 'sometimes|required|boolean',
            'board'         => 'sometimes|nullable|string|max:10',
            'doors'         => 'sometimes|required|integer|min:2|max:5',
            'description'   => 'sometimes|nullable|string',
            'status'        => 'sometimes|required|integer',

            // --- IDs de Relacionamentos ---
            // A regra 'exists' garante que o ID enviado realmente existe na tabela correspondente.
            'brand_id'        => 'sometimes|required|integer|exists:brands,id',
            'car_model_id'    => 'sometimes|required|integer|exists:car_models,id',
            'version_id'      => 'sometimes|required|integer|exists:versions,id',
            'color_id'        => 'sometimes|required|integer|exists:colors,id',
            'fuel_id'         => 'sometimes|required|integer|exists:fuels,id',
            'transmission_id' => 'sometimes|required|integer|exists:transmissions,id',
            'unit_id'         => 'sometimes|required|integer|exists:units,id',

            // --- Campos JSON ---
            // A regra 'array' garante que o valor enviado para estes campos seja uma lista.
            'photos'          => 'sometimes|nullable|array',
            'optionals'       => 'sometimes|nullable|array',
        ];
    }
}