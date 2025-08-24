<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Para o 'create', os campos são 'required' em vez de 'sometimes'
            'original_id' => 'required|integer|unique:vehicles,original_id',
            'price' => 'required|numeric|min:0',
            'km' => 'required|integer|min:0',
            'year_build' => 'required|digits:4',
            'year_model' => 'required|digits:4',
            'new' => 'required|boolean',
            'board' => 'nullable|string|max:10',
            'doors' => 'required|integer',
            'description' => 'nullable|string',

            // Para os relacionamentos, esperamos receber os IDs que já existem no banco
            'brand_id' => 'required|integer|exists:brands,id',
            'car_model_id' => 'required|integer|exists:car_models,id',
            'version_id' => 'required|integer|exists:versions,id',
            'color_id' => 'required|integer|exists:colors,id',
            'fuel_id' => 'required|integer|exists:fuels,id',
            'transmission_id' => 'required|integer|exists:transmissions,id',
            'unit_id' => 'required|integer|exists:units,id',
            'status' => 'required|integer',
        ];
    }
}