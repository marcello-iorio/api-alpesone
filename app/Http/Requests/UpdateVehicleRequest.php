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
            
            'brand'         => 'sometimes|required|string|max:255',
            'model'         => 'sometimes|required|string|max:255',
            'version'       => 'sometimes|required|string|max:255',
            'color'         => 'sometimes|required|string|max:255',
            'fuel'          => 'sometimes|required|string|max:255',
            'transmission'  => 'sometimes|required|string|max:255',
            'category'      => 'sometimes|nullable|string|max:255',
            'sold'          => 'sometimes|required|boolean',
            'price'         => 'sometimes|required|numeric|min:0',
            'km'            => 'sometimes|required|integer|min:0',
            'year_build'    => 'sometimes|required|digits:4',
            'year_model'    => 'sometimes|required|digits:4',
            'new'           => 'sometimes|required|boolean',
            'board'         => 'sometimes|nullable|string|max:10',
            'doors'         => 'sometimes|required|integer|min:2|max:5',
            'description'   => 'sometimes|nullable|string',
            'photos'        => 'sometimes|nullable|array',
            'optionals'     => 'sometimes|nullable|array',
        ];
    }
}