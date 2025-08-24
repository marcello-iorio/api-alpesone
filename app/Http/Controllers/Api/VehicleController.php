<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreVehicleRequest;
use App\Http\Requests\UpdateVehicleRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\VehicleResource;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Carrega os veículos com seus relacionamentos para evitar múltiplas queries
        $vehicles = Vehicle::with(['brand', 'carModel', 'version', 'color', 'unit'])
            ->orderBy('original_id', 'asc')
            ->paginate(15);

        // Retorna a coleção formatada pelo VehicleResource
        return VehicleResource::collection($vehicles);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVehicleRequest $request)
    {
        
        $vehicle = Vehicle::create($request->validated());

        return (new VehicleResource($vehicle))
                ->response()
                ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Vehicle $vehicle)
    {
        // Carrega todos os relacionamentos para a resposta detalhada
        $vehicle->load(['brand', 'carModel', 'version', 'color', 'unit', 'fuel', 'transmission']);

        // Retorna um único recurso formatado
        return new VehicleResource($vehicle);
    }

    
    public function update(UpdateVehicleRequest $request, Vehicle $vehicle)
    {
        // A validação é feita automaticamente pelo UpdateVehicleRequest antes de o código chegar aqui.
        $validatedData = $request->validated();

        // O método update preenche o modelo com os dados validados e salva no banco.
        $vehicle->update($validatedData);

        // Retornamos o recurso do veículo com os dados já atualizados.
        return new VehicleResource($vehicle);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();

        return response()->noContent(); // Retorno padrão para delete bem-sucedido
    }
}