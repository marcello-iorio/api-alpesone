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

            'id' => 'required|integer|unique:vehicles,id',
            'brand'         => 'required|string|max:255',
            'model'         => 'required|string|max:255',
            'version'       => 'required|string|max:255',
            'color'         => 'required|string|max:255',
            'fuel'          => 'required|string|max:255',
            'transmission'  => 'required|string|max:255',
            'category'      => 'nullable|string|max:255',
            'sold'          => 'sometimes|boolean', 
            'year_build'    => 'required|string|max:4',
            'year_model'    => 'required|string|max:4',
            'price'         => 'required|numeric|min:0',
            'km'            => 'required|integer|min:0',
            'board'         => 'nullable|string|max:10',
            'doors'         => 'required|integer|min:2|max:5',
            'description'   => 'nullable|string',
            'photos'        => 'nullable|array',
            'optionals'     => 'nullable|array',
            
        ];
    }
}